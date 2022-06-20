<?php

namespace App\Http\Controllers;

use App\Repositories\UserRepository;
use Exception;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * @var userRepository
     */
    private $userRepository;
    //

    public function __construct(UserRepository $userRepository) {
        $this->userRepository = $userRepository;
    }

    /**
     * Polician Dashboard 
     *
     */
    public function dashboard()
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
            echo '<pre>'; print_r($e->getMessage()); die;
            return $this->apiResponse->handleAndResponseException($e);
        }
    }
}
