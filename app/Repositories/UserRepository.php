<?php

namespace App\Repositories;

use App\Exceptions\InvalidCredentialsException;
use App\Models\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use JWTAuth;
use Mail;
use App\Mail\SendForgotPasswordOtpMail;
use App\Models\OtpData;
use App\Models\UserMeta;
use App\Http\CommonTraits\UploadMedia;

/**
 * Class UserRepository.
 */
class UserRepository
{
    use UploadMedia;
    /**
     * For Updating the record respective model in storage
     *
     * @param Request $request
     */
    public function login(Request $request)
    {
        $loginCondition = [
            $request->fieldType => $request->fieldValue,
            'password' => $request->password,
            'role_id' => config('constants.role.user')
        ];

        $userLogin = Auth::guard()->attempt($loginCondition);

        if (empty($userLogin)) {
            throw new InvalidCredentialsException();
        }

        // Fetch user details
        $user = [
            'id' => Auth::id()
        ];
        
        $userMetaDetails = User::with(['ranks'])->withCount('posts')->where($user)->firstOrFail();
        $userMeta =  $userMetaDetails->getMeta()->toArray();
        
        $userMetaDetails['meta_data'] = $userMeta;

        return [
            'token' => JWTAuth::fromUser($userMetaDetails),
            'user' => $userMetaDetails
        ];
    }

    /**
     * For Forgot Password send otp by email or phone
     *
     * @param Request $request
     */
    public function forgotPassword(Request $request)
    {
        switch($request->fieldType){
            case('phone'):
                $user = [
                    'phone' => $request->fieldValue
                ];

                $userDetails = self::fetchUserDetails($user);

                return [
                   'action' => 'phoneExistence',
                   'user' => $userDetails,
                   'status' => 'success'
                ];

            case('email'):
                $user = [
                    'email' => $request->fieldValue
                ];

                $userDetails = self::fetchUserDetails($user);

                $otp = self::generateOTP();

                $storeOtp = self::storeOTP($otp);

                // Mail::to($request->fieldValue)->send(new SendForgotPasswordOtpMail($otp));

                return [
                    'action' => 'email',
                    'user' => $userDetails,
                    'status' => 'success'
                ];

            default:
                return [];
        }
    }

    /**
     * For Updating the record respective model in storage
     *
     * @param Request $request
     */
    public function updatePassword(Request $request)
    {
        $condition = [
            $request->fieldType => $request->fieldValue,
        ];

        $fields = [
            'password' => bcrypt($request->password)
        ];

        $userDetails = self::fetchUserDetails($condition);

        $updateUser = self::updateUserData($condition, $fields);

        if($updateUser) {
            return [
                'status' => 'success',
                'user' => self::fetchUserDetails($condition)
            ];
        }
    }

    /**
     * For Updating the record respective model in storage
     *
     * @param Request $request
     */
    public function changePassword(Request $request)
    {
        $condition = [
            'id' =>  Auth::user()->id
        ];

        $fields = [
            'password' => bcrypt($request->password)
        ];
        $updateUser = self::updateUserData($condition, $fields);

        if($updateUser) {
            return [
                'user' => self::fetchUserDetails($condition)
            ];
        }
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

        return true;
    }


    /**
     * For selecting the data
     *
     * @param array $condition
     * @throws InvalidOtpException
     */
    public function fetchUserDetails(array $condition)
    {
        return User::where($condition)->firstOrFail();
    }

    /**
     * For selecting the data
     *
     * @param array $condition
     */
    public function getUserDetails(array $condition)
    {
        return User::where($condition)->first();
    }

    /**
     * For generate otp data
     */
    public function generateOTP()
    {
        return rand(100000, 999999);
    }

    /**
     * For store otp data
     *
     * @param string $otp
     */
    public function storeOTP(string $otp)
    {
        $otpData = [
            'otp' => $otp,
            'expiry_date' =>  Carbon::now()->addHours(24)
        ];

        return OtpData::create($otpData);
    }

    /**
     * For Updating record into table
     */
    public function updateUserData($condition, $fields)
    {
        return User::where($condition)->update($fields);
    }

    public function updateProfile(Request $request)
    {
        $condition = [
            'id' =>  Auth::user()->id
        ];

        if($request->has('profilePhoto')){
            $image = Self::imageUpload($request);
        }

        $fields = [

            'first_name' => $request->firstName,
            'last_name' => $request->lastName,
            'image' => $image ?? null
        ];

        $userDetails = self::fetchUserDetails($condition);

        $updateUser = self::updateUserData($condition, $fields);

        $metaRequest = $request->except('userId','firstName', 'lastName', 'email', 'phone', 'profilePhoto', 'reg_status', 'registered_date', 'status', 'action', 'step', 'fieldType', 'fieldValue');

        $userDetails->setMeta($metaRequest);

        $userDetails->save();

        if($updateUser) {
            return [
                'user' => self::fetchUserDetails($condition),
                'status' => 'success'
            ];
        }
    }

    public function register(Request $request){

        switch ($request->step) {

            case (1):

                $result = self::sendVerificationCode($request);

                return $result;

                break;

            case (2):

                $result = self::userRegistrationStepTwo($request);

                return $result;

                break;

            case (3):

                $result = self::userRegistrationStepThree($request);

                return $result;

                break;

            default:

                return [];
        }
    }


    public function sendVerificationCode(object $request){

        switch ($request->fieldType) {

            case('email'):

            case('resendVerificationEmail'):

                $user = [
                    'email' => $request->fieldValue
                ];

                $userDetails = self::getUserDetails($user);

                if(empty($userDetails)){

                    $otp = self::generateOTP();

                    $storeOtp = self::storeOTP($otp);

                    //  Mail::to($request->fieldValue)->send(new SendForgotPasswordOtpMail($otp));

                    return [
                        'action' => 'emailOTPsend',
                        'status' => 'success'
                    ];

                }

                return [
                    'action' => 'step_three',
                    'status' => 'success'
                ];

                break;

            case('phone'):

                $user = [
                    'phone' => $request->fieldValue
                ];

                $userDetails = self::getUserDetails($user);

                if(empty($userDetails)){

                    //send otp

                    return [
                        'action' => 'phoneOTPsend',
                        'status' => 'success'
                     ];

                }

                break;

            default:

                return [
                    'action' => 'step1',
                    'status' => 'error'
                ];

        }

    }

    public function userRegistrationStepTwo(object $request) {

        switch ($request->fieldType) {

            case ('email'):

                $user = [
                    'email' => $request->fieldValue
                ];

                $userDetails = self::getUserDetails($user);

                break;

            case ('phone'):

                $user = [
                    'phone' => $request->fieldValue
                ];

                $userDetails = self::getUserDetails($user);

        }
        if (empty($userDetails)) {

            $data = self::registerUserSetpTwo($request);

            if (!empty($data)) {
                return [
                    'action' => 'step_two',
                    'status' => 'success'
                ];
            }

        } else {

           return [
                'action' => 'step_two',
                'status' => 'error'
            ];

        }

        return [
            'action' => 'step_two',
            'status' => 'error'
        ];

    }

    public function registerUserSetpTwo($request) {

        switch ($request->fieldType) {

            case ('email'):

                $otp = $request->validationCode;

                $data = self::verifyOtp($otp);

                if (!empty($data)) {

                    self::deleteOtp($otp);

                    $userData = self::registerUser($request);

                    return $userData;

                }

                break;

            case ('phone'):

                $userData = self::registerUser($request);

                return $userData;

                break;

        }
    }

    public function verifyOtp($otp) {

       $otpData = OtpData::where('otp', $otp)->where('expiry_date', '>=', now())->first();

        return $otpData;
    }

    public function deleteOtp($otp) {

        $otpData = OtpData::where('otp', $otp)->where('expiry_date', '>=', now())->first();

        $otpData->delete();

        return $otpData;
    }

    public function registerUser($request) {

        $fields = [
            'email' => $request->fieldValue,
            'password' => bcrypt($request->password),
            'reg_status' => '{"step":2,"status":0}',
            'registered_date' => now(),
            'rank_id' => 0,
            'role_id' => config('constants.role.user')
        ];

        $user = User::create($fields);

        return $user;
    }

    public function userRegistrationStepThree($request){

        $email = $request->email;

        $fieldType = $request->fieldType;

        if ($fieldType == 'phone') {

            $user = [
                    'email' => $request->fieldValue
                ];

                $userDetails = self::getUserDetails($user);

            if (empty($userDetails)) {

                $status = self::registerUserStepThree($request);
                if ($status == 1) {
                    return [
                        'action' => 'step_three',
                        'status' => 'success'
                    ];
                }
            } else {
                return [
                    'action' => 'step_three',
                    'status' => 'error'
                ];
            }
        } else {

            $registeredUser = self::registerUserStepThree($request);

            if (!empty($registeredUser)) {
                return [
                    'action' => 'step_three',
                    'status' => 'success'
                ];
            } else {
                return [
                    'action' => 'step_three',
                    'status' => 'error'
                ];
            }
        }

        return $result;

    }

    public function registerUserStepThree($request){

        $condition = [
            'email' => $request->email
        ];

        if($request->has('profilePhoto')){
            $image = Self::imageUpload($request);
        }

        $fields = [

            'first_name' => $request->firstName,
            'last_name' => $request->lastName,
            'email' => $request->email,
            'phone' => $request->phone,
            'reg_status' => '{"step":3,"status":1}',
            'image' => $image ?? null
        ];

        $userDetails = self::fetchUserDetails($condition);

        $updateUser = self::updateUserData($condition, $fields);

        $metaRequest = $request->except('userId', 'validationCode', 'firstName', 'lastName', 'email', 'phone', 'profilePhoto', 'reg_status', 'registered_date', 'status', 'action', 'step', 'fieldType', 'fieldValue');
        
        $userDetails->setMeta($metaRequest);
        $userDetails->save();

        return $userDetails;

    }
    
    /**
     * For Create / Updating record into ranks table
     */
    public function saveData($condition = [], $fields, $metaData = [])
    {
        if($fields['password']){
            $fields['password'] = \Hash::make($fields['password']);
        }

        // Just note : below updateOrCreate function not working proper for poltician and user model so set condition here, plz do'not remove it.
        if(!empty($condition)){
            $userObj = User::updateOrCreate($condition, $fields);
        }else{
            $userObj = User::create($fields);
        }
        if(!empty($metaData)){
            $userObj->setMeta($metaData);
            $userObj->save();
        }
        return $userObj;
    }
}


?>
