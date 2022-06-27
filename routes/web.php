<?php

use App\Http\Controllers\PoliticianController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RankController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PageController;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('dashboard', [PoliticianController::class, 'dashboard'])->name('dashboard');

    //Route for politician
    Route::get('politicians', [PoliticianController::class, 'index'])->name('politicians.index');
    Route::get('get-politician/{id}', [PoliticianController::class, 'getPolitician'])->name('get.politician');
    Route::post('post-politician', [PoliticianController::class, 'postPolitician'])->name('post.politician');

    //Route for user
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::get('get-user/{id}', [UserController::class, 'getUser'])->name('get.user');
    Route::post('post-user', [UserController::class, 'postUser'])->name('post.user');

    //Route for rank
    Route::get('ranks', [RankController::class, 'index'])->name('ranks.index');
    Route::get('get-rank/{id}', [RankController::class, 'getRank'])->name('get.rank');
    Route::post('post-rank', [RankController::class, 'postRank'])->name('post.rank');
    Route::post('check-rank-title', [RankController::class, 'checkTitle'])->name('check.rank.title');

    //Route for category
    Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('get-category/{id}', [CategoryController::class, 'getCategory'])->name('get.category');
    Route::post('post-category', [CategoryController::class, 'postCategory'])->name('post.category');
    Route::post('check-category-name', [CategoryController::class, 'checkName'])->name('check.category.name');

    //Route for rank
    Route::get('pages', [PageController::class, 'index'])->name('pages.index');
    Route::get('get-page/{id}', [PageController::class, 'getPage'])->name('get.page');
    Route::get('get-page', [PageController::class, 'getPage'])->name('add.page');
    Route::post('post-page', [PageController::class, 'postPage'])->name('post.page');
    Route::post('check-page-name', [PageController::class, 'checkName'])->name('check.page.name');
    Route::post('delete-page', [PageController::class, 'getDelete'])->name('delete.page');
    
});
require __DIR__.'/auth.php';
