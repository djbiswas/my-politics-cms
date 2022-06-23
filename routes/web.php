<?php

use App\Http\Controllers\PoliticianController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RankController;

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

    
});
require __DIR__.'/auth.php';
