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
            // 'role_id' => config('constants.role.user')
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
}

?>
