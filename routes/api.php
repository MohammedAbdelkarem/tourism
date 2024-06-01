<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\Auth\AdminController;

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

Route::post('checkcode' , [AuthController::class , 'checkCode']);

//Admin without middleware

Route::group(['prefix' => 'admin'], function () {

    Route::group(['prefix' => 'auth'], function () {
        Route::post('send' , [AdminController::class , 'sendCode']);
        Route::post('register' , [AdminController::class , 'register']);
        Route::post('login' , [AdminController::class , 'login']);
        Route::post('reset' , [AdminController::class , 'resetPassword']);
    });

});


//Admin with middleware
Route::group(['prefix' => 'admin' , 'middleware' => ['auth:admin']] , function(){
    
    Route::group(['prefix' => 'auth'], function () {
        Route::post('logout', [AdminController::class, 'logout']); 
    });

});


//User without middleware
Route::group(['prefix' => 'user'], function () {

    Route::group(['prefix' => 'auth'], function () {
        Route::post('send' , [UserController::class , 'sendCode']);
        Route::post('register' , [UserController::class , 'register']);
        Route::post('login' , [UserController::class , 'login']);
        Route::post('reset' , [UserController::class , 'resetPassword']);
    });

});


//User with middleware
Route::group(['prefix' => 'user' , 'middleware' => ['auth:user']] , function(){
    
    Route::group(['prefix' => 'auth'], function () {
        Route::post('edit', [UserController::class, 'updateProfile']);
        Route::post('logout', [UserController::class, 'logout']);
        Route::get('profile', [UserController::class, 'profile']);
        Route::delete('delete', [UserController::class, 'deleteAccount']);
    });
});