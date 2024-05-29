<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

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
//general routes

Route::post('auth/check' , [AuthController::class , 'checkCode']);

//Admin Auth

Route::group(['prefix' => 'auth/admin'], function () {
    Route::post('send' , [AdminController::class , 'sendCode']);
    Route::post('register' , [AdminController::class , 'register']);
    Route::post('login' , [AdminController::class , 'login']);
    Route::post('reset' , [AdminController::class , 'resetPassword']);
});

Route::group([
    'middleware' => ['DbBackup'],
    'prefix' => 'auth/admin'
], function ($router) {
    Route::post('/logout', [AadminController::class, 'logout']);
    Route::post('/refresh', [AdminController::class, 'refresh']);
    Route::get('/user-profile', [AdminController::class, 'userProfile']); 
});

//User Auth
Route::group(['prefix' => 'auth/user'], function () {
    Route::post('send' , [UserController::class , 'sendCode']);
    Route::post('register' , [UserController::class , 'register']);
});