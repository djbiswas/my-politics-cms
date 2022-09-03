<?php

use App\Http\Controllers\PoliticianController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RankController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PageController;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
// use App\Http\Controllers\CourseCategoryController;
use App\Http\Controllers\IssueCategoryController;
use App\Http\Controllers\IssueController;
use App\Http\Controllers\PermissionCategoryController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PoliticianVotingAlertController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PostFlagController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\UserWarnController;


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


    // Route for voting Alert
    Route::get('politician-voting-alerts', [PoliticianVotingAlertController::class, 'index'])->name('politician.voting.alerts');
    Route::get('politician-voting-alert/{id}', [PoliticianVotingAlertController::class, 'getPVA'])->name('get.pva');
    Route::post('post-pva', [PoliticianVotingAlertController::class, 'postPVA'])->name('post.pva');

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

    Route::post('post-admin', [UserController::class, 'postAdmin'])->name('post.admin');

    // Route Warn for user
    Route::get('user-warns', [UserWarnController::class, 'index'])->name('user.warns');
    Route::post('post-warn', [UserController::class, 'postWarn'])->name('post.warn');
    Route::post('post-ban', [UserController::class, 'postBan'])->name('post.ban');
    Route::post('post-block', [UserController::class, 'postBlock'])->name('post.block');


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
    Route::get('posts', [PostController::class, 'index'])->name('posts.index');
    Route::get('get-post/{id}', [PostController::class, 'getPost'])->name('get.post');
    Route::post('post-post', [PostController::class, 'postPost'])->name('post.post');
    Route::delete('posts/{id}', [PostController::class, 'delete'])->name('post.delete');

    // Route for post flag
    Route::get('flags', [PostFlagController::class, 'index'])->name('flag.index');
    Route::get('get-flag/{id}', [PostFlagController::class, 'getFlag'])->name('get.flag');
    Route::post('post-flag', [PostFlagController::class, 'postFlag'])->name('post.flag');
    Route::delete('flags/{id}', [PostFlagController::class, 'delete'])->name('flag.delete');


    //Route for Issue Category
    Route::get('issue-categories', [IssueCategoryController::class, 'index'])->name('issue_categories.index');
    Route::get('get-issue-category/{id}', [IssueCategoryController::class, 'getIssueCategory'])->name('get.issue_category');
    Route::post('post-issue-category', [IssueCategoryController::class, 'postIssueCategory'])->name('post.issue_category');
    Route::post('check-issue-category-name', [IssueCategoryController::class, 'checkName'])->name('check.issue_category.name');


    // Route for Permissions Category
    Route::get('permission-categories', [PermissionCategoryController::class, 'index'])->name('permission_categories.index');
    Route::get('get-permission-category/{id}', [PermissionCategoryController::class, 'getPermissionCategory'])->name('get.permission_category');
    Route::post('post-permission-category', [PermissionCategoryController::class, 'postPermissionCategory'])->name('post.permission_category');
    Route::post('check-permission-category-name', [PermissionCategoryController::class, 'checkName'])->name('check.permission_category.name');


    // Route For Permissions
    Route::get('permissions', [PermissionController::class, 'index'])->name('permissions.index');
    Route::get('get-permission/{id}', [PermissionController::class, 'getPermission'])->name('get.permission');
    Route::post('post-permission', [PermissionController::class, 'postPermission'])->name('post.permission');
    Route::post('check-permission-name', [PermissionController::class, 'checkName'])->name('check.permission.name');
    Route::delete('permission/{id}', [PermissionController::class, 'delete'])->name('permission.delete');

    // Route For Set Role Permission
    Route::get('role-permissions',[RolePermissionController::class, 'index'])->name('role.permissions.index');
    Route::get('ger-role-permission/{id}', [RolePermissionController::class, 'getRolePermission'])->name('get.role.permission');
    Route::post('post-role-permission', [RolePermissionController::class, 'postRolePermission'])->name('post.role.permission');
    Route::post('check-role-permission-name', [RolePermissionController::class, 'checkRolePermission'])->name('check.role.permission');
    Route::delete('role-permisson/{id}', [RolePermissionController::class, 'delete'])->name('role.permission.delete');

    // Route For Issue
    Route::get('issues', [IssueController::class, 'index'])->name('issues.index');
    Route::get('get-issue/{id}', [IssueController::class, 'getIssue'])->name('get.issue');
    Route::post('post-issue', [IssueController::class, 'postIssue'])->name('post.issue');
    Route::post('check-issue-name', [IssueController::class, 'checkName'])->name('check.issue.name');
    Route::delete('issues/{id}', [IssueController::class, 'delete'])->name('issues.delete');

    //Route for Course category
    // Route::get('course-categories', [CourseCategoryController::class, 'index'])->name('course_categories.index');
    // Route::get('get-course-category/{id}', [CourseCategoryController::class, 'getCategory'])->name('get.course_category');
    // Route::post('post-course-category', [CourseCategoryController::class, 'postCategory'])->name('post.course_category');
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
