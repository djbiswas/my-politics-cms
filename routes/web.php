<?php

use App\Http\Controllers\PoliticianController;

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

    Route::get('politicians', [PoliticianController::class, 'index'])->name('politicians');
});
require __DIR__.'/auth.php';
