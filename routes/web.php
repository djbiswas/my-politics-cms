<?php

use App\Http\Controllers\PoliticianController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RankController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PageController;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\CourseCategoryController;
use App\Http\Controllers\IssueCategoryController;
use App\Http\Controllers\IssueController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\RoleController;
use App\Models\CourseCategory;

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
    Route::get('add-politician', [PoliticianController::class, 'getPolitician'])->name('add.politician');
    Route::post('post-politician', [PoliticianController::class, 'postPolitician'])->name('post.politician');
    Route::delete('politicians/{id}', [PoliticianController::class, 'delete'])->name('politicians.delete');

    //Route for user
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::get('get-user/{id}', [UserController::class, 'getUser'])->name('get.user');
    Route::get('add-user', [UserController::class, 'getUser'])->name('add.user');
    Route::post('post-user', [UserController::class, 'postUser'])->name('post.user');
    Route::post('check-user-email', [UserController::class, 'checkEmail'])->name('check.user.email');
    Route::post('check-user-phone', [UserController::class, 'checkPhone'])->name('check.user.phone');
    Route::delete('users/{id}', [UserController::class, 'delete'])->name('users.delete');

    // Route for Admin User
    Route::get('admin-users', [UserController::class, 'admin_users'])->name('admin.users');
    Route::get('get-admin/{id}', [UserController::class, 'getAdmin'])->name('get.admin');
    Route::get('add-admin', [UserController::class, 'getAdmin'])->name('add.admin');

    //Route for rank
    Route::get('ranks', [RankController::class, 'index'])->name('ranks.index');
    Route::get('get-rank/{id}', [RankController::class, 'getRank'])->name('get.rank');
    Route::post('post-rank', [RankController::class, 'postRank'])->name('post.rank');
    Route::post('check-rank-title', [RankController::class, 'checkTitle'])->name('check.rank.title');


    //Route for role
    Route::get('roles', [RoleController::class, 'index'])->name('roles.index');
    Route::get('get-role/{id}', [RoleController::class, 'getRole'])->name('get.role');
    Route::post('post-role', [RoleController::class, 'postRole'])->name('post.role');
    Route::post('check-role-title', [RoleController::class, 'checkTitle'])->name('check.role.title');

    //Route for category
    Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('get-category/{id}', [CategoryController::class, 'getCategory'])->name('get.category');
    Route::post('post-category', [CategoryController::class, 'postCategory'])->name('post.category');
    Route::post('check-category-name', [CategoryController::class, 'checkName'])->name('check.category.name');

    // Route for posts
    Route::get('posts', [PostController::class, 'index'])->name('postsclear.index');


    //Route for Issue Category
    Route::get('issue-categories', [IssueCategoryController::class, 'index'])->name('issue_categories.index');
    Route::get('get-issue-category/{id}', [IssueCategoryController::class, 'getIssueCategory'])->name('get.issue_category');
    Route::post('post-issue-category', [IssueCategoryController::class, 'postIssueCategory'])->name('post.issue_category');
    Route::post('check-issue-category-name', [IssueCategoryController::class, 'checkName'])->name('check.issue_category.name');

    // Route For Issue
    Route::get('issues', [IssueController::class, 'index'])->name('issues.index');
    Route::get('get-issue/{id}', [IssueController::class, 'getIssue'])->name('get.issue');
    Route::post('post-issue', [IssueController::class, 'postIssue'])->name('post.issue');
    Route::post('check-issue-name', [IssueController::class, 'checkName'])->name('check.issue.name');
    Route::delete('issues/{id}', [IssueController::class, 'delete'])->name('issues.delete');



    //Route for Course category
    Route::get('course-categories', [CourseCategoryController::class, 'index'])->name('course_categories.index');
    Route::get('get-course-category/{id}', [CourseCategoryController::class, 'getCategory'])->name('get.course_category');
    Route::post('post-course-category', [CourseCategoryController::class, 'postCategory'])->name('post.course_category');
    // Route::post('check-category-name', [CourseCategoryController::class, 'checkName'])->name('check.category.name');

    //Route for page
    Route::get('pages', [PageController::class, 'index'])->name('pages.index');
    Route::get('get-page/{id}', [PageController::class, 'getPage'])->name('get.page');
    Route::get('get-page', [PageController::class, 'getPage'])->name('add.page');
    Route::post('post-page', [PageController::class, 'postPage'])->name('post.page');
    Route::post('check-page-name', [PageController::class, 'checkName'])->name('check.page.name');
    Route::delete('pages/{id}', [PageController::class, 'delete'])->name('pages.delete');

});
require __DIR__.'/auth.php';
