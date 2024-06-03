<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\Auth\AdminController;
use App\Http\Controllers\Auth\GuideController;
use App\Http\Controllers\Admin\FacilitesController;
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

//Admin 

Route::group(['prefix' => 'admin'], function () {

    Route::group(['prefix' => 'auth'], function () {
        Route::post('send' , [AdminController::class , 'sendCode']);
        Route::post('register' , [AdminController::class , 'register']);
        Route::post('login' , [AdminController::class , 'login']);
        Route::post('reset' , [AdminController::class , 'resetPassword']);
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



//Guide without middleware
Route::group(['prefix' => 'guide'], function () {

    Route::group(['prefix' => 'auth'], function () {
        Route::post('send' , [GuideController::class , 'sendCode']);
        Route::post('register' , [GuideController::class , 'register']);
        Route::post('login' , [GuideController::class , 'login']);
        Route::post('reset' , [GuideController::class , 'resetPassword']);
    });

});


//Guide with middleware
Route::group(['prefix' => 'guide' , 'middleware' => ['auth:guide']] , function(){
    
    Route::group(['prefix' => 'auth'], function () {
        Route::post('edit', [GuideController::class, 'updateProfile']);
        Route::post('logout', [GuideController::class, 'logout']);
        Route::get('profile', [GuideController::class, 'profile']);
        Route::delete('delete', [GuideController::class, 'deleteAccount']);
    });
});

Route::group(['prefix' => 'admin'], function () {
    //add facility
    Route::post('facility/store',[ FacilitesController::class,'storeFacility']);

    Route::get('allfacility', [FacilitesController::class,'getFacilities']);
    Route::get('restaurants', [FacilitesController::class, 'getRestaurants']);
    Route::get('hotels', [FacilitesController::class, 'getHotels']);
    Route::get('places', [FacilitesController::class, 'getPlaces']);
    //update facility
    Route::post('/facility/{facility}', [FacilitesController::class, 'updateFacility']);
    Route::delete('/facilities/{facility}',[FacilitesController::class, 'deleteFacility']);
});