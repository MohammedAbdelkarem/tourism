<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\Auth\AdminController;
use App\Http\Controllers\Auth\GuideController;
use App\Http\Controllers\Admin\FacilitesController;
use App\Http\Controllers\Admin\TripsController;
use App\Http\Controllers\User\FacilityController;
use App\Http\Controllers\User\TripsController as UserTripsController;

use App\Http\Controllers\Admin\DaysController;
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

        // facility
        Route::post('facility/store',[ FacilitesController::class,'storeFacility']);
        Route::get('allfacility', [FacilitesController::class,'getFacilities']);
        Route::get('restaurants', [FacilitesController::class, 'getRestaurants']);
        Route::get('hotels', [FacilitesController::class, 'getHotels']);
        Route::get('places', [FacilitesController::class, 'getPlaces']);
        Route::get('facility/{id}', [FacilitesController::class,'getFacilityDetails']);
        Route::post('/facility/{facility}', [FacilitesController::class, 'updateFacility']);
        Route::delete('/facilities/{facility}',[FacilitesController::class, 'deleteFacility']);
        
         //trip
        Route::post('trip/store',[TripsController::class,'addTrip']);
        Route::post('/trip/{trip}', [TripsController::class, 'updateTrip']);
        Route::delete('/trip/{trip}', [TripsController::class, 'deleteTrip']);
        Route::get('/trip/pending', [TripsController::class, 'getPinnedTrips']);
        Route::get('/trip/active', [TripsController::class, 'getRunningTrips']);
        Route::get('/trip/finish', [TripsController::class, 'getFinishidTrips']);
        Route::get('/trip', [TripsController::class, 'getTrips']);
        Route::get('/trip/{trip}', [TripsController::class, 'getTripDetails']);

        //day
        Route::post('/days', [DaysController::class,'addDay']);
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

    Route::group(['prefix' => 'facility'], function () {
        Route::post('getlist', [FacilityController::class, 'getFacilitiesByCountry']);
        Route::post('details', [FacilityController::class, 'getFacilityDetails']);
    });

    Route::group(['prefix' => 'trip'], function () {
        Route::get('homeoffers', [UserTripsController::class, 'getHomeOffers']);
        Route::get('listoffers', [UserTripsController::class, 'getAllOffers']);
        Route::get('homerec', [UserTripsController::class, 'getHomeRecommended']);
        Route::get('listrec', [UserTripsController::class, 'getAllRecommended']);
        Route::post('triplist', [UserTripsController::class, 'getTripsListByCountry']);
        Route::post('details', [UserTripsController::class, 'getTripDetails']);
        Route::post('search', [UserTripsController::class, 'search']);
        Route::get('co', [UserTripsController::class, 'getHomeCountries']);
        Route::post('codetails', [UserTripsController::class, 'getCountryDetails']);
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




