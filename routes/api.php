<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\v1\UserController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/* Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
}); */

Route::group(['namespace' => 'API\v1'], function() {
    // User API
    Route::post('login', [UserController::class, 'login'])->name('user.login');
    Route::post('forgot-password', [UserController::class, 'forgotPassword'])->name('user.forgot-password');
    Route::post('update-password', [UserController::class, 'updatePassword'])->name('user.update-password');
});