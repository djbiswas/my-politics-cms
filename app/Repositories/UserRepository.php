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

/**
 * Class UserRepository.
 */
class UserRepository
{
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
            //'role_id' => config('constants.role.user')
        ];

        $userLogin = Auth::guard()->attempt($loginCondition);

        if (empty($userLogin)) {
            throw new InvalidCredentialsException();
        }

        // Fetch user details
        $user = [
            'id' => Auth::id()
        ];

        $userDetails = self::fetchUserDetails($user);

        return [
            'token' => JWTAuth::fromUser($userDetails),
            'user' => $userDetails
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
                   'user' => $userDetails
                ];

            case('email'):
                $user = [
                    'email' => $request->fieldValue
                ];

                $userDetails = self::fetchUserDetails($user);

                $otp = self::generateOTP();

                $storeOtp = self::storeOTP($otp);

                Mail::to($request->fieldValue)->send(new SendForgotPasswordOtpMail($otp));

                return [
                    'action' => 'email',
                    'user' => $userDetails
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

    /**
     * For Updating record into user_meta table
     */
    public function updateUserMetaData($condition, $fields)
    {
        return UserMeta::updateOrCreate($condition, $fields);
    }

    public function updateProfile(Request $request)
    {
        $condition = [
            'id' =>  Auth::user()->id
        ];

        $fields = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
        ];

        $userDetails = self::fetchUserDetails($condition);

        $updateUser = self::updateUserData($condition, $fields);

        $metaRequest = $request->except('first_name', 'last_name');

        foreach($metaRequest as $key=>$value){

            $metaCondition = [
                'user_id' =>  Auth::user()->id,
                'meta_key' => $key
            ];

            $metaFields = [
                'user_id' =>  Auth::user()->id,
                'meta_key' => $key,
                'meta_value' => $value
            ];

            $updateUserMeta = self::updateUserMetaData($metaCondition, $metaFields);

        }

        if($updateUser) {
            return [
                'user' => self::fetchUserDetails($condition)
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

                    Mail::to($request->fieldValue)->send(new SendForgotPasswordOtpMail($otp));

                    return [
                        'action' => 'emailOTPsend',
                        'status' => 'success'
                    ];

                }

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

        return $result;
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
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'reg_status' => 2,
            'registered_date' => now(),
            'rank_id' => 0,
            'role_id' => 1
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

        $fields = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'reg_status' => 3
        ];

        $userDetails = self::fetchUserDetails($condition);

        $updateUser = self::updateUserData($condition, $fields);

        $metaRequest = $request->except('first_name', 'last_name', 'email', 'phone', 'reg_status', 'registered_date');

        foreach($metaRequest as $key=>$value){

            $metaCondition = [
                'user_id' =>  $userDetails->id,
                'meta_key' => $key
            ];

            $metaFields = [
                'user_id' =>  $userDetails->id,
                'meta_key' => $key,
                'meta_value' => $value
            ];

            $updateUserMeta = self::updateUserMetaData($metaCondition, $metaFields);

            return $userDetails;
        }

    }
}


?>
