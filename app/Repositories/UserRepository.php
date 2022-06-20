<?php

namespace App\Repositories;

use App\Exceptions\InvalidCredentialsException;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use JWTAuth;

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
}

?>
