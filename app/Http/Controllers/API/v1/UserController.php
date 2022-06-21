<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Response\CustomApiResponse;
use App\Http\Requests\UserChangePasswordValidationRequest; 
use App\Http\Requests\UserForgotPasswordValidationRequest; 
use App\Http\Requests\UserLoginValidationRequest; 
use App\Http\Requests\UserUpdatePasswordValidationRequest; 
use App\Repositories\UserRepository;
use Exception;
use Illuminate\Http\Request;


/**
 * @OA\Info(
 *     description="",
 *     version="1.0.0",
 *     title="My Political API",
 * )
 */

/**
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *         type="http",
 *         scheme="bearer",
 *         bearerFormat="JWT"
 *     ),
 */
class UserController extends Controller
{

    /**
     * @var userRepository
     */
    private $userRepository;

    
    /**
     * @var CustomApiResponse
     */
    private $apiResponse;

    public function __construct(CustomApiResponse $customApiResponse, UserRepository $userRepository) {
        $this->apiResponse = $customApiResponse;
        $this->userRepository = $userRepository;
    }

    /**
     * Register API
     *
     * @param Request $request
     */
    public function Register(Request $request)
    {
        try {
            $user = $this->userRepository->login($request);

            if (!empty($user)) {
                $success = [
                    $user
                ];
                $message = trans('lang.register');

                return $this->apiResponse->getResponseStructure(config('constants.api_success_fail.true'), $success, $message);
            }
        } catch (Exception $e) {
            return $this->apiResponse->handleAndResponseException($e);
        }
    }

    /**
     * @OA\Post(
     *     path="/v1/login",
     *     tags={"Login"},
     *     summary="User Login",
     *     operationId="user-login",
     *
     *     @OA\Parameter(
     *       name="fieldType",
     *       in="query",
     *       required=true,
     *       description="mobile or email",
     *       @OA\Schema(
     *          type="string"
     *       )
     *     ),
     *     @OA\Parameter(
     *       name="fieldValue",
     *       in="query",
     *       required=true,
     *       @OA\Schema(
     *          type="string"
     *       )
     *     ),
     *     @OA\Parameter(
     *       name="password",
     *       in="query",
     *       required=true,
     *       @OA\Schema(
     *           type="password"
     *       )
     *     ),
     * 
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid request"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="not found"
     *     ),
     * )
     */

    /**
     * login API
     *
     * @param UserLoginValidationRequest $request
     */
    public function login(UserLoginValidationRequest $request)
    {
        try {
            $user = $this->userRepository->login($request);

            if (!empty($user)) {
                $success = [
                    $user
                ];
                $message = trans('lang.login');

                return $this->apiResponse->getResponseStructure(config('constants.api_success_fail.true'), $success, $message);
            }
        } catch (Exception $e) {
            return $this->apiResponse->handleAndResponseException($e);
        }
    }

    /**
     * @OA\Post(
     *     path="/v1/forgot-password",
     *     tags={"Forgot Password"},
     *     summary="User Forgot Password",
     *     operationId="forgot-password",
     *
     *     @OA\Parameter(
     *       name="fieldType",
     *       in="query",
     *       required=true,
     *       description="mobile or email",
     *       @OA\Schema(
     *          type="string"
     *       )
     *     ),
     *     @OA\Parameter(
     *       name="fieldValue",
     *       in="query",
     *       required=true,
     *       @OA\Schema(
     *          type="string"
     *       )
     *     ),
     * 
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid request"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="not found"
     *     ),
     * )
     */
    /**
     * forgot-password API
     *
     * @param UserForgotPasswordValidationRequest $request
     */
    public function forgotPassword(UserForgotPasswordValidationRequest $request)
    {
        try {
            $forgotPassword = $this->userRepository->forgotPassword($request);

            if (!empty($forgotPassword)) {
                $success = [
                    $forgotPassword
                ];

                switch($request->fieldType) {
                    case('phone'):
                        $message = trans('lang.phone_otp');

                        return $this->apiResponse->getResponseStructure(config('constants.api_success_fail.true'), $success, $message);      
        
                    case('email'):
                        $message = trans('lang.email_otp');

                        return $this->apiResponse->getResponseStructure(config('constants.api_success_fail.true'), $success, $message);
                    
                    default:
                        $message = trans('message.record_not_found');

                        return $this->apiResponse->getResponseStructure(config('constants.api_success_fail.true'), $success, $message);
            
                }
            }
        } catch (Exception $e) {
            return $this->apiResponse->handleAndResponseException($e);
        }
    }

    /**
     * @OA\Post(
     *     path="/v1/update-password",
     *     tags={"Update Password"},
     *     summary="User Update Password",
     *     operationId="update-password",
     *
     *     @OA\Parameter(
     *       name="fieldType",
     *       in="query",
     *       required=true,
     *       description="mobile or email",
     *       @OA\Schema(
     *          type="string"
     *       )
     *     ),
     *     @OA\Parameter(
     *       name="fieldValue",
     *       in="query",
     *       required=true,
     *       @OA\Schema(
     *          type="string"
     *       )
     *     ),
     *     @OA\Parameter(
     *       name="password",
     *       in="query",
     *       required=true,
     *       @OA\Schema(
     *           type="password"
     *       )
     *     ),
     * 
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid request"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="not found"
     *     ),
     * )
     */
    /**
     * update-password API
     *
     * @param UserUpdatePasswordValidationRequest $request
     */
    public function updatePassword(UserUpdatePasswordValidationRequest $request)
    {
        try {
            $updatePassword = $this->userRepository->updatePassword($request);

            if (!empty($updatePassword)) {
                $success = [
                    $updatePassword
                ];
                $message = trans('lang.password_update');

                return $this->apiResponse->getResponseStructure(config('constants.api_success_fail.true'), $success, $message);   
            }
        } catch (Exception $e) {
            return $this->apiResponse->handleAndResponseException($e);
        }
    }

    /**
     * Update-profile API
     *
     * @param Request $request
     */
    public function updateProfile(Request $request)
    {
        try {
            $updatePassword = $this->userRepository->updatePassword($request);

            if (!empty($updatePassword)) {
                $success = [
                    $updatePassword
                ];
                $message = trans('lang.profile_update');

                return $this->apiResponse->getResponseStructure(config('constants.api_success_fail.true'), $success, $message);   
            }
        } catch (Exception $e) {
            return $this->apiResponse->handleAndResponseException($e);
        }
    }

    /**
     * @OA\Patch(
     *     security={{"bearerAuth":{}}},
     *     path="/v1/change-password",
     *     tags={"Change Password"},
     *     summary="Change Password",
     *     operationId="change-password",
     *
     *    @OA\Parameter(
     *       name="password",
     *       in="query",
     *       required=true,
     *       @OA\Schema(
     *          type="password"
     *       )
     *     ),
     *     @OA\Parameter(
     *       name="confirm_password",
     *       in="query",
     *       required=true,
     *       @OA\Schema(
     *           type="password"
     *       )
     *     ),
     * 
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid request"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="not found"
     *     ),
     * )
     */
    /**
     * change-password API
     *
     * @param UserChangePasswordValidationRequest $request
     */
    public function changePassword(UserChangePasswordValidationRequest $request)
    {
        try {
            $user = $this->userRepository->changePassword($request);

            if (!empty($user)) {
                $success = [
                    $user
                ];
                $message = trans('lang.password_update');

                return $this->apiResponse->getResponseStructure(config('constants.api_success_fail.true'), $success, $message);
            }
        } catch (Exception $e) {
            return $this->apiResponse->handleAndResponseException($e);
        }
    }

    /**
     * @OA\Get(
     *     security={{"bearerAuth":{}}},
     *     path="/v1/logout",
     *     tags={"Logout"},
     *     description="Logout API for user",
     *     operationId="logout",
     *
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid request"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="not found"
     *     ),
     * )
     */
    /**
     * Logout API
     *
     * @param Request $request
     */
    public function logout(Request $request)
    {
        try {
            $this->userRepository->logout($request);

            $message = trans('lang.logout');
            
            return $this->apiResponse->getResponseStructure(config('constants.api_success_fail.true'), '', $message);   
        } catch (Exception $e) {
            return $this->apiResponse->handleAndResponseException($e);
        }
    }
}
