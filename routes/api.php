<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\AuthController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//Test route
Route::get('/test', function () {
    return 'test';
});

//JWT route
Route::group(['middleware' => 'api', 'prefix' => 'auth'], function ($router) {
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/refresh', [AuthController::class, 'refresh'])->name('refresh');
    Route::post('/me', [AuthController::class, 'me'])->name('me');
});

//Blog route
Route::group(['middleware' => 'api', 'prefix' => 'blog'], function ($router) {
    Route::resource('blogs', BlogController::class);
    Route::post('/blog-bulk-insert', [BlogController::class, 'blogBulkInsert']);
});

//Comment route
Route::group(['middleware' => 'api', 'prefix' => 'comment'], function ($router) {
    Route::resource('comments', CommentController::class);
});

//Role route
Route::group(['middleware' => 'api', 'prefix' => 'role'], function ($router) {
    Route::resource('roles', RoleController::class);
});

//Permission route
Route::group(['middleware' => 'api', 'prefix' => 'permission'], function ($router) {
    Route::resource('permissions', PermissionController::class);
});