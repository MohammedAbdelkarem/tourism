<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Models\User;

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
    Route::post('/logout', [AdminController::class, 'logout']);
    Route::post('/refresh', [AdminController::class, 'refresh']);
    Route::get('/user-profile', [AdminController::class, 'userProfile']); 
});

Route::group([
    'middleware' => ['DbBackup'],
    'prefix' => 'auth/user'
], function ($router) {
    Route::post('/login', [UserController::class, 'login']);
    Route::post('/register', [UserController::class, 'register']);
    Route::post('/logout', [UserController::class, 'logout']);
    Route::post('/refresh', [UserController::class, 'refresh']);
    Route::get('/user-profile', [UserController::class, 'userProfile']); 
    Route::delete('/softDelete', [UserController::class, 'softDelete']);   
});