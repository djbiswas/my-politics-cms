<?php

namespace App\Repositories;

use App\Exceptions\InvalidCredentialsException;
use App\Models\User;
use Auth;
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
            'role' => config('constants.role.user')
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
                    'user' => $userDetails
                ];

            default:
                return [];
        }
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
        $data['otp'] = $otp;
        $data['expiry_date'] = date("Y-m-d H:i:s", strtotime('+24 hours'));

        $otpData = OtpData::create($data);        
    }
}

?>
