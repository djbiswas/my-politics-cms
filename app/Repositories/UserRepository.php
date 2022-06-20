<?php

namespace App\Repositories;

use App;
use App\Actions\CreateUserAction;
use App\Actions\CreateUserDeviceAction;
use App\Actions\UpdateUserProfileAction;
use App\Actions\UserLoginAction;
use App\Exceptions\CityInvalidException;
use App\Exceptions\CountryCodeException;
use App\Exceptions\InvalidCredentialsException;
use App\Exceptions\InvalidOtpException;
use App\Models\City;
use App\Models\Country;
use App\Models\KitchenOwnerCuisine;
use App\Models\Language;
use App\Models\Setting;
use App\Models\User;
use App\Models\UserDevice;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use JWTAuth;

/**
 * Class UserRepository.
 */
class UserRepository
{
    /**
     * @var createUserAction
     */
    private $createUserAction;

    /**
     * @var userLoginAction
     */
    private $userLoginAction;

    /**
     * @var userLoginAction
     */
    private $userDeviceAction;
    /**
     * @var UpdateUserProfileAction
     */
    private $updateUserProfileAction;

    public function __construct(
        CreateUserAction $action,
        UserLoginAction $login,
        CreateUserDeviceAction $userDevice,
        UpdateUserProfileAction $updateUserProfileAction
    )
    {
        $this->createUserAction = $action;
        $this->userLoginAction = $login;
        $this->userDeviceAction = $userDevice;
        $this->updateUserProfileAction = $updateUserProfileAction;
    }

    /**
     * For Creating the record respective model in storage
     *
     * @param Request $request
     */
    public function register(Request $request): object
    {
        self::languageIsExists($request->language_id);

        $otp = generateOTP();

        $registerData = [
            'role_id' => !empty(config('constants.role.user')) ? config('constants.role.user') : 3,
            'language_id' => $request->language_id,
            'name' => !empty($request->name) ? $request->name : NULL,
            'email' => !empty($request->email) ? $request->email : NULL,
            'social_platform_code' => !empty($request->social_platform_code) ? $request->social_platform_code : NULL,
            'social_platform_type' => !empty($request->social_platform_type) ? $request->social_platform_type : NULL,
            'otp' => $otp,
            'is_social_login' => $request->is_social_login,
            'device_token' => $request->device_token,
            'device_id' => $request->device_id,
            'platform' => $request->platform,
            'is_login' => config('constants.status.inActive'),
        ];
        // Store user data
        $user = $this->createUserAction->do($registerData);

        // Store user device data
        $registerData['user_id'] = $user['user_id'];
        $this->userDeviceAction->do($registerData);

        // Fetch user details
        $userId = [
            'id' => $user['user_id']
        ];
        $userDetails = self::fetchUserDetails($userId);

        // Set User Language
        self::setUserLanguage($userDetails['language_id']);

        // Send OTP on the user email or mobile number
        if ($registerData['is_social_login'] == config('constants.status.active')) {
            sendOtpMail($userDetails, $otp);
        }

        $userDetails->token = JWTAuth::fromUser($userDetails);

        return $userDetails;
    }

    /**
     * Check language is exists or not
     *
     * @param int $id
     * @return object
     */
    public function languageIsExists(int $id)
    {
        return Language::select('id', 'name', 'short_code')->findOrFail($id);
    }

    /**
     * For selecting the data
     *
     * @param array $condition
     * @throws InvalidOtpException
     */
    public function fetchUserDetails(array $condition)
    {
        return User::with(['country'])->select('id', 'language_id', 'country_id', 'name', 'mobile_number', 'email', 'otp_verified', 'avatar','gender','date_of_birth', 'social_platform_type', 'social_platform_code')
            ->where($condition)
            ->with(['language', 'userAddress.city', 'userAddress' => function ($query) {
                $query->where('is_delivery_address', '=', config('constants.status.active'))->orderBy('id', 'DESC');
            }])->firstOrFail();
    }

    /**
     * For setting user language
     *
     * @param int $id
     * @return object
     */
    public function setUserLanguage(int $id)
    {
        $language = Language::select('id', 'name', 'short_code')->where('id', $id)->firstOrFail();
        App::setlocale($language->short_code);
        return $language;
    }

    /**
     * For Updating the record respective model in storage
     *
     * @param Request $request
     * @throws CountryCodeException
     */
    public function login(Request $request): object
    {
        self::languageIsExists($request->language_id);
        if (!empty($request->country_code)) {
            $countryCode = self::countryCodeIsExists($request->country_code,$request->short_code);
        }

        $otp = generateOTP();

        $loginData = [
            'role_id' => !empty($request->role_id) ? $request->role_id : 3,
            'language_id' => $request->language_id,
            'country_code' => !empty($countryCode) ? $countryCode->id : null,
            'key' => $request->key,
            'value' => $request->value,
            'otp' => $otp,
            'device_token' => $request->device_token,
            'device_id' => $request->device_id,
            'platform' => $request->platform,
            'is_login' => config('constants.status.active'),
        ];

        $loginCondition = [
            'email' => $loginData['value'],
            'role_id' => $loginData['role_id']
        ];

        //check user is existed
        if ($loginData['key'] == config('constants.loginType.emailAddress')) {
            $fetchUser = User::where($loginCondition)->first();
            if ($fetchUser == null) {
                throw new InvalidCredentialsException();
            }
        }

        // Update user data with OTP
        $user = $this->userLoginAction->do($loginData);

        // Store user device data
        $loginData['user_id'] = $user['user_id'];
        $this->userDeviceAction->do($loginData);

        // Fetch user details
        $userId = [
            'id' => $user['user_id']
        ];
        $userDetails = self::fetchUserDetails($userId);

        if (!empty($userDetails['country_id']) && $loginData['key'] == 'mobile') {
            if (!empty($userDetails['country_id'] != $countryCode['id'])) {
                throw new CountryCodeException();
            }
            if ($userDetails['country_id'] == null) {
                $userDetails['country_id'] = User::where('id', $user['user_id'])->update(['country_id' => $countryCode['id']]);
            }
        }
        // Set User Language
        self::setUserLanguage($userDetails['language_id']);

        $userDetails['token'] = JWTAuth::fromUser($userDetails);

        // Send OTP on the user email or mobile number
        /*switch ($request['key']) {
            case config('constants.loginType.mobileNumber'):
                sendMessage($userDetails['mobile_number'], $otp);
                break;
            case config('constants.loginType.emailAddress'):
                sendOtpMail($userDetails, $otp);
                break;
        }*/
        if($request['key'] == config('constants.loginType.emailAddress')) {
                sendOtpMail($userDetails, $otp);
        }
        return $userDetails;
    }

    /**
     * Check country code  is exists or not
     *
     * @param int $id
     * @return object
     */
    public function countryCodeIsExists(int $id, $shortCode)
    {
        $getCountryId = Country::select('id')->where(['country_code' => $id, 'short_code' => $shortCode])->first();
        if ($getCountryId == null) {
            throw new CountryCodeException();
        }
        return $getCountryId;
    }

    /**
     * For Logout user
     *
     * @param Request $request
     */
    public function logout($request)
    {
        $token = $request->header('Authorization');
        $parseValue = JWTAuth::parseToken();
        $userId = $parseValue->authenticate()->id;
        $parseValue->invalidate($token);

        $fields = ['id' => $userId];
        $condition = ['otp_verified' => config('constants.status.inActive')];
        $this->updateUserLogin($fields, $condition);

        $fields = ['user_id' => $userId];
        $condition = ['is_login' => config('constants.status.active')];
        UserDevice::where($fields)->update($condition);
    }

    /**
     * For updating user records into table
     *
     * @param array $condition
     * @param array $fields
     */
    public function updateUserLogin(array $condition, array $fields)
    {
        return User::where($condition)->update($fields);
    }

    /**
     * For updating user device records into table
     *
     * @param array $condition
     * @param array $fields
     */
    public function updateUserDevice(array $condition, array $fields)
    {
       return UserDevice::where($condition)->updateOrCreate($fields);
    }

    /**
     * For Updating the record respective model in storage
     *
     * @param Request $request
     */
    public function adminLogin(Request $request)
    {
        $userLogin = Auth::guard()->attempt(['email' => $request->email, 'password' => $request->password]);

        if (empty($userLogin)) {
            throw new InvalidCredentialsException();
        }

        // Fetch user details
        $user = [
            'role_id' => Auth::user()->role_id,
            'id' => Auth::id()
        ];

        $userDetails = self::fetchUserDetails($user);

        return [
            'token' => JWTAuth::fromUser($userDetails),
            'user' => $userDetails
        ];
    }

    /**
     * For Fetching kitchen owners
     *
     * @param Request $request
     */
    public function getKitchenOwners(Request $request)
    {
        $cityId = self::cityIsExists($request->city_name);

        if (empty($cityId)) {
            throw new CityInvalidException();
        }

        // Find the list of kitchen owners
        $kitchenOwners = self::fetchKitchenOwners($request, $cityId);


        foreach ($kitchenOwners as $kitchenOwner) {
            if ($kitchenOwner->user_status == config('constants.review_status.under_review') or $kitchenOwner->user_status == config('constants.review_status.rejected')) {
                $ownerJson = json_decode($kitchenOwner->old_data, true);
                $kitchenOwner->picture = !empty($ownerJson['avatar']) ? $ownerJson['avatar'] : NULL;
            }

            $kitchenOwner->userCount = self::countUserByRating($kitchenOwner->business_owner_id);

            if ($kitchenOwner->avg_rating) {
                User::where('id', $kitchenOwner->business_owner_id)->update(['rating' => $kitchenOwner->avg_rating]);
            }
            $kitchenOwner->kitchenOwnercuisine = KitchenOwnerCuisine::select('id','business_owner_id','cuisine_id')
            ->with(['Cuisine' => function ($query) {
                    $query->select('id', 'name');
                }])->where('business_owner_id', $kitchenOwner->business_owner_id)->get();
        }

        // Count number of kitchen owners
        $totalKitchens = self::countKitchenOwners($cityId);

        return [
            'total_kitchens' => $totalKitchens[0]->toal_kitchens . trans('lang.kitchen_owner_arround_you'),
            'kitchen_owners' => $kitchenOwners,
        ];
    }

    /**
     * For selecting the data
     *
     * @param Request $request
     *
     */
    public function fetchKitchenOwners(Request $request, $cityId)
    {
        $condition = [];
        $menuCondition = [];
        $menuItemCondition = [];
        $cuisine = [];
        //$areaCondition = [];
        if (!empty($request->search_by)) {
            $condition[] = ['user_addresses.office_name', 'LIKE', '%' . trim($request->search_by) . '%'];
            //TODO
            //$areaCondition[] = ['user_addresses.area', 'LIKE', '%' . trim($request->search_by) . '%'];
            $menuCondition[] = ['km.name', 'LIKE', '%' . trim($request->search_by) . '%'];
            $menuItemCondition[] = ['menu_items.name', 'LIKE', '%' . trim($request->search_by) . '%'];

            $getFirstCuisine = explode(" ", $request->search_by);
            $cuisine[] = ['cuisine.name', 'LIKE', '%' . trim($getFirstCuisine[0]) . '%'];
        }
        $condition[] = ['user_addresses.user_id', '<>', Auth::id()];
        $condition[] = ['user_addresses.city_id', '=', $cityId];
        //$condition[] = ['user_addresses.address_review_status', '=', config('constants.review_status.approved')];
        $query = DB::table('user_addresses')
            ->select("user_addresses.id", "user_addresses.user_id as business_owner_id", "user_addresses.office_name as name",
                "user_addresses.address as address", "user_addresses.area as area", "user_addresses.land_mark as land_mark", "user_addresses.latitude as latitude",
                "user_addresses.longitude as longitude", "cities.name as city_name", "rating.business_owner_id as BOI", "km.delivery_will_through as delivery_will_through", "users.rating as rating", "users.status as user_status", "users.old_data as old_data",
                DB::raw("6371 * acos(cos(radians(" . $request->lat . "))
                                    * cos(radians(latitude)) * cos(radians(longitude) - radians(" . $request->long . "))
                                    + sin(radians(" . $request->lat . ")) * sin(radians(latitude))) AS distance"),
                DB::raw("(select (CASE WHEN avatar <> '' THEN CONCAT('https://".config('constants.settings.AWS_BUCKET').".".config('constants.image.driver').".".config('constants.settings.AWS_DEFAULT_REGION').".amazonaws.com/".config('constants.image.user')."/',avatar) ELSE 'https://via.placeholder.com/150x150?text=Default%20Image' END) from users where id = user_addresses.user_id  limit 1) as picture"),
                DB::raw("COUNT(km.business_owner_id) as business_menu"))
            ->having('distance', '<', $request->radius)
            ->leftJoin('cities', 'user_addresses.city_id', '=', 'cities.id')
            ->leftJoin('users', 'user_addresses.user_id', '=', 'users.id')->where(['role_id' => config('constants.role.kitchen_owner')])
            ->leftJoin('kitchen_owner_cuisine', 'user_addresses.user_id', '=', 'kitchen_owner_cuisine.business_owner_id')
            ->leftJoin('cuisine', 'kitchen_owner_cuisine.cuisine_id', '=', 'cuisine.id')
            ->leftJoin('menus as km', 'user_addresses.user_id', '=', 'km.business_owner_id')
            ->leftJoin('ratings as rating', 'user_addresses.user_id', '=', 'rating.business_owner_id')
            ->selectRaw('ROUND(AVG(rating.rating),1) AS avg_rating')

            ->where(function ($query) {
                $query->whereNull('km.date_of_menu')
                    ->orWhere('km.date_of_menu', '>=', Carbon::now())->orwhere('km.is_permanent_menu', config('constants.is_menu_item_selected.True'));
            })
            ->where('km.menu_update_first_time', config('constants.review_status.approved'))
            ->whereNull('km.deleted_at')
            ->where('km.is_menu_completed', config('constants.is_menu_selected.True'))->having('business_menu', '>', 0)
            ->where($condition)->orWhere($menuCondition)->orWhere($menuItemCondition)->orWhere($cuisine)->groupBy('user_addresses.office_name');

        //search menu item
        if (!empty($menuItemCondition)) {
            $query = $query->leftJoin('menu_items', 'user_addresses.user_id', '=', 'menu_items.business_owner_id')
                ->where(function ($query) {
                    $query->where('is_menu_items_completed', config('constants.is_menu_item_selected.partial'))
                        ->orWhere('is_menu_items_completed', config('constants.is_menu_item_selected.True'));
                })->where(function ($query) {
                    $query->whereNull('km.date_of_menu')
                        ->orWhere('km.date_of_menu', '>=', Carbon::now())->orwhere('km.is_permanent_menu', config('constants.is_menu_item_selected.True'));
                });
        }
        if (!empty($request->filter_name_by)) {
            $query = $query->Where(function ($query) use ($request) {
                $query->orwhereIn('cuisine.name', $request->filter_name_by)
                    ->orWhereIn('km.delivery_will_through', $request->filter_name_by);
            });

            if ($request->filter_name_by[0] == config('constants.user_rating.rating')) {
                $query = $query->orWhere('users.rating', '>=', config('constants.user_rating.rating'))->whereNull('users.deleted_at');
            }
        }

        $query = $query->orderBy('distance', 'asc');

        return $query->simplePaginate(config('constants.pagination.pagination_limit'));
    }

    /**
     * For counting the data
     *
     * @param Request $request
     *
     */
    public function countKitchenOwners()
    {
        return DB::table('user_addresses')
            ->select(DB::raw('count(*) as toal_kitchens'))
            ->where('user_id', '<>', Auth::id())
            ->get();
    }

    public function countUserByRating($businessOwnerId)
    {
        return DB::table('ratings')
            ->select(DB::raw('COUNT(DISTINCT ratings.user_id) as user_count'))
            ->where('business_owner_id', '=', $businessOwnerId)
            ->get();
    }

    /**
     * For Fetching kitchen owners details
     *
     * @param Request $request
     */
    public function getKitchenOwnersDetails(Request $request)
    {
        // Find the list of kitchen owners details
        $kitchenOwners = self::fetchKitchenOwnersDetails($request);
        foreach ($kitchenOwners as $kitchenOwner) {
            $kitchenOwner->userCount = self::countUserByRating($kitchenOwner->business_owner_id);
            $kitchenOwner->kitchenOwnercuisine = KitchenOwnerCuisine::select('id','business_owner_id','cuisine_id')
            ->with(['Cuisine' => function ($query) {
                    $query->select('id', 'name');
                }])->where('business_owner_id', $kitchenOwner->business_owner_id)->get();
        }

        $kitchenOwners[0]->distance = round($kitchenOwners[0]->distance, config('constants.address.distance')) . " km";
        $kitchenOwners[0]->delivery_in_your_area = ($kitchenOwners[0]->distance < $request->radius) ? "yes" : "no";

        return $kitchenOwners;
    }

    /**
     * For selecting the data
     *
     * @param Request $request
     *
     */
    public function fetchKitchenOwnersDetails(Request $request)
    {
        $condition[] = ['user_addresses.user_id', '<>', Auth::id()];
        $condition[] = ['user_addresses.id', '=', $request->kitchen_owner_id];

        return DB::table('user_addresses')
            ->leftJoin('users', 'user_addresses.user_id', '=', 'users.id')
            ->leftJoin('cities', 'user_addresses.city_id', '=', 'cities.id')
            ->select("users.id as business_owner_id", "user_addresses.id", "user_addresses.office_name as name", "user_addresses.address", "user_addresses.area", "user_addresses.land_mark", "user_addresses.pin_code",
                "user_addresses.latitude", "user_addresses.longitude", "cities.name as city", "users.mobile_number as contact",
                DB::raw("6371 * acos(cos(radians(" . $request->lat . "))
                                * cos(radians(latitude)) * cos(radians(longitude) - radians(" . $request->long . "))
                                + sin(radians(" . $request->lat . ")) * sin(radians(latitude))) AS distance"))
            ->leftJoin('ratings as rating', 'user_addresses.user_id', '=', 'rating.business_owner_id')
            ->selectRaw('ROUND(AVG(rating.rating),1) AS rating')
            ->where($condition)
            ->get();
    }

    public function userUpdateProfile(Request $request)
    {

        $userId = Auth::user();
        if (!empty($request->avatar)) {
            $fileName = uploadFile(config('constants.image.user'), $request->avatar);
        }

        //get country id
        if (!empty($request->country_code)) {
            $countryCode = self::countryCodeIsExists($request->country_code,$request->short_code);
        }

        # Set user parameters
        $updateUserData = [
            'id' => $userId->id,
            'country_id' => !empty($countryCode['id']) ? $countryCode['id'] : $userId->country_id,
            'name' => !empty($request->name) ? $request->name : $userId->name,
            'email' => !empty($request->email) ? $request->email : $userId->email,
            'mobile_number' => !empty($request->mobile_number) ? $request->mobile_number : $userId->mobile_number,
            'gender' => !empty($request->gender) ? $request->gender : $userId->gender,
            'date_of_birth' => !empty($request->date_of_birth) ? Carbon::parse($request->date_of_birth) : $userId->date_of_birth,
            'language_id' => !empty($request->language_id) ? $request->language_id : $userId->language_id,
            'avatar' => !empty($fileName) ? $fileName : $userId->avatar,
        ];
        if ($userId->mobile_number != $request->mobile_number && !empty($request->mobile_number)) {
            $otp = generateOTP();
            $updateUserData['otp'] = $otp;
            //sendMessage($request->mobile_number, $otp);
        }

        $user = $this->updateUserProfileAction->do($updateUserData);

        $userId = [
            'id' => $user['user_id']
        ];

        return self::fetchUserDetails($userId);
    }

    /**
     * Check city is exists or not
     *
     * @param string $name
     * @return object
     */
    public function cityIsExists($name)
    {
        $city = City::select('id', 'name')->where('name', $name)->first();

        return !empty($city->id) ? $city->id: null;
    }

    public function resendOtp($request)
    {
        $userId = User::findOrFail($request->user_id);
        $otp = generateOTP();

        $otpData = [
            'otp' => $otp,
        ];
        $fields = [
            'otp' => $otpData['otp'],
        ];

        if ($request['key'] == config('constants.loginType.emailAddress')) {
            $condition = [
                'id' => $request->user_id,
                'email' => $request['value']
            ];

            $user = User::where($condition)->update($fields);

            $userDetails = self::fetchUserDetails($condition);
            sendOtpMail($userDetails, $otp);
        }
        /*switch ($request['key']) {
            case config('constants.loginType.mobileNumber'):
                $condition = [
                    'id' => $userId->id,
                    'mobile_number' => $request['value']
                ];
                break;
            case config('constants.loginType.emailAddress'):
                $condition = [
                    'email' => $request['value']
                ];
                break;
        }*/

        /*switch ($request['key']) {
            case config('constants.loginType.mobileNumber'):
                sendMessage($userDetails['mobile_number'], $otp);
                break;
            case config('constants.loginType.emailAddress'):
                sendOtpMail($userDetails, $otp);
                break;
        }*/

        return $userDetails;

    }

    /**
     * For getting user for updating user  device records into table
     *
     * @param Request $request
     *
     */
    public function userUpdateDevice($request)
    {
        $userId = Auth::id();
        UserDevice::where('user_id',$userId)->delete();
        $condition = [
            'user_id' => $userId,
            'device_token' => $request['device_token'],
            'platform' => $request['platform'],
            'device_id' => $request['device_id'],
        ];
        $fields = [
            'user_id' => $userId,
            'device_token' => $request['device_token'],
            'device_id' => $request['device_id'],
            'is_login' => config('constants.status.inActive'),
            'platform' => $request['platform']
        ];

        $updateUserDevice = $this->updateUserDevice($condition, $fields);
        return $updateUserDevice;
    }

    public function updateUserLanguage($request){
        $userId = Auth::id();

        self::languageIsExists($request->language_id);

        return User::where('id',$userId)->update(['language_id' => $request->language_id]);
    }

    public function getSettings(): Object
    {
        return Setting::select('id', 'key_name', 'key_value')->where('key_name','=','RAZOR_KEY')->get();
    }

    /**
     * @return mixed
     */
    public function getUserInfo ()
    {
        $id = Auth::id();
        $fetchAdminData = User::findOrFail($id);

        return $fetchAdminData;
    }
}

?>
