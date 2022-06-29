<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\v1\UserController;
use App\Http\Controllers\API\v1\PoliticianCategoryController;
use App\Http\Controllers\API\v1\PoliticianController;
use App\Http\Controllers\API\v1\UserPostController;

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
    Route::post('register', [UserController::class, 'register'])->name('user.register');
    Route::post('login', [UserController::class, 'login'])->name('user.login');
    Route::post('forgot-password', [UserController::class, 'forgotPassword'])->name('user.forgot.password');
    Route::post('update-password', [UserController::class, 'updatePassword'])->name('user.update.password');

    // Get Potician Catgory API 
    Route::get('get-politician-categories', [PoliticianCategoryController::class, 'getPoliticianCategories'])->name('get.politician.categories');

    // Get Potician API 
    Route::get('get-politicians', [PoliticianController::class, 'getPoliticians'])->name('get.politicians');

    // Get Potician Details API 
    Route::get('get-politician-detail', [PoliticianController::class, 'getPoliticianDetail'])->name('get.politician.detail');

    // Get Potician Votes API
    Route::get('get-politician-votes', [PoliticianController::class, 'getPoliticianVotes'])->name('get.politician.votes'); 
    
    // Get Trust API 
    Route::get('get-trust', [PoliticianController::class, 'getTrust'])->name('get.politician.trust');
    
    Route::group(['middleware' => ['jwt.verify']], function () {
        // User API
        Route::post('update-profile', [UserController::class, 'updateProfile'])->name('user.update.profile');
        Route::patch('change-password', [UserController::class, 'changePassword'])->name('user.change.password');
        Route::get('logout', [UserController::class, 'logout'])->name('user.logout');

        //Post API
        Route::post('create-post', [UserPostController::class, 'createUserPost'])->name('user.create.post');
        Route::post('media-uplaod', [UserPostController::class, 'mediaUpload'])->name('user.media.uplaod');
        Route::patch('update-post', [UserPostController::class, 'updatePost'])->name('user.update.post');
        Route::delete('delete-post', [UserPostController::class, 'deletePost'])->name('user.delete.post');
        Route::post('post-reaction', [UserPostController::class, 'postReaction'])->name('user.post.reaction');
        Route::post('post-comment', [UserPostController::class, 'postComment'])->name('user.post.comment');
        Route::get('get-comments', [UserPostController::class, 'getComments'])->name('user.get.comment');

        // Politican Vote API
        Route::post('set-politician-votes', [PoliticianController::class, 'setPoliticianVote'])->name('set.politician.vote');
    });
});