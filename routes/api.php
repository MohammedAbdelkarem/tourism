<?php

use Illuminate\Support\Facades\Route;


use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\Admin\DaysController;
use App\Http\Controllers\Auth\AdminController;
use App\Http\Controllers\Auth\GuideController;
use App\Http\Controllers\Admin\TripsController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\User\FacilityController;
use App\Http\Controllers\Admin\FacilitesController;
use App\Http\Controllers\User\AppointmentController;
use App\Http\Controllers\User\TripsController as UserTripsController;
use App\Http\Controllers\Admin\TripsController as AdminTripsController;



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

Route::post('checkcode', [AuthController::class, 'checkCode']);

//Admin 

// Route::group(['prefix' => 'admin'], function () {

//     Route::controller(AdminController::class)->group(['prefix' => 'auth'], function () {
//         Route::post('send', 'sendCode');
//         Route::post('register', 'register');
//         Route::post('login', 'login');
//         Route::post('reset', 'resetPassword');
//         Route::post('logout', 'logout');
//     });

Route::group(['prefix' => 'admin'], function () {

    Route::group(['prefix' => 'auth'], function () {
        Route::post('send' , [AdminController::class , 'sendCode']);
        Route::post('register' , [AdminController::class , 'register']);
        Route::post('login' , [AdminController::class , 'login']);
        Route::post('reset' , [AdminController::class , 'resetPassword']);
        Route::post('store' , [AdminController::class , 'storeEmail']);
        Route::post('logout', [AdminController::class, 'logout']); 
        Route::post('wallet/{userId}', [UsersController::class, 'addToWallet']);
    });
    // facility
    Route::post('facility/store', [FacilitesController::class, 'storeFacility']);
    Route::get('allfacility', [FacilitesController::class, 'getFacilities']);
    Route::get('restaurants', [FacilitesController::class, 'getRestaurants']);
    Route::get('hotels', [FacilitesController::class, 'getHotels']);
    Route::get('places', [FacilitesController::class, 'getPlaces']);
    Route::get('facility/{id}', [FacilitesController::class, 'getFacilityDetails']);
    Route::post('facility/{facility}', [FacilitesController::class, 'updateFacility']);
    Route::delete('facilities/{facility}', [FacilitesController::class, 'deleteFacility']);
    Route::get('facilities/nearest/{trip_id}', [FacilitesController::class, 'getNearestFacilities']);
    //trip
    Route::post('trip/store', [TripsController::class, 'addTrip']);
    Route::post('trip/{trip}', [TripsController::class, 'updateTrip']);
    Route::delete('trip/{trip}', [TripsController::class, 'deleteTrip']);
    Route::get('trip/pending', [TripsController::class, 'getPinnedTrips']);
    Route::get('trip/active', [TripsController::class, 'getRunningTrips']);
    Route::get('trip/finish', [TripsController::class, 'getFinishidTrips']);
    Route::get('trip', [TripsController::class, 'getTrips']);
    Route::get('trip/{trip}', [TripsController::class, 'getTripDetails']);
    Route::get('trip/active/{id}', [TripsController::class, 'activeTrip']);
    Route::get('trip/in_progress/{id}', [TripsController::class, 'inProgressTrip']);
    Route::get('trip/finished/{id}', [TripsController::class, 'finishTrip']);
    //days
    Route::post('days', [DaysController::class, 'addDay']);
    Route::post('days/{days}', [DaysController::class, 'updateDay']);

    

    //facility in day
    Route::post('facility_in_day', [DaysController::class, 'addFacilityInDay']);
    Route::post('facility_in_day/{facilityInDay}', [DaysController::class, 'updateFacilityInDay']);


    // users

    Route::get('users', [UsersController::class, 'getUsers']);
    // Route::post('wallet/{userId}', [UsersController::class, 'addToWallet']);

});




//User without middleware
Route::group(['prefix' => 'user'], function () {

    Route::group(['prefix' => 'auth'], function () {
        Route::post('send', [UserController::class, 'sendCode']);
        Route::post('register', [UserController::class, 'register']);
        Route::post('login', [UserController::class, 'login']);
        Route::post('reset', [UserController::class, 'resetPassword']);
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
        Route::post('countrytrips', [UserTripsController::class, 'getCountryTrips']);
    });

    Route::group(['prefix' => 'facility'], function () {
        Route::post('getlist', [FacilityController::class, 'getFacilitiesByCountry']);
        Route::post('details', [FacilityController::class, 'getFacilityDetails']);
    });

    

});


//User with middleware
Route::group(['prefix' => 'user', 'middleware' => ['auth:user']], function () {

    Route::group(['prefix' => 'auth'], function () {
        Route::post('edit', [UserController::class, 'updateProfile']);
        Route::post('logout', [UserController::class, 'logout']);
        Route::get('profile', [UserController::class, 'profile']);
        Route::delete('delete', [UserController::class, 'deleteAccount']);
    });


    Route::group(['prefix' => 'appoint'], function () {
        Route::post('appoint', [AppointmentController::class, 'appointTrip']);
        Route::post('unappoint', [AppointmentController::class, 'unAppointTrip']);
        Route::get('get', [AppointmentController::class, 'getMyTrips']);
        Route::post('modify', [AppointmentController::class, 'modifyAppointment']);
    });

    Route::group(['prefix' => 'favourite'], function () {
        Route::post('add', [UserTripsController::class, 'addToFav']);
        Route::post('delete', [UserTripsController::class, 'deleteFav']);
        Route::get('get', [UserTripsController::class, 'getFav']);
    });
});



//Guide without middleware
Route::group(['prefix' => 'guide'], function () {

    Route::group(['prefix' => 'auth'], function () {
        Route::post('send', [GuideController::class, 'sendCode']);
        Route::post('register', [GuideController::class, 'register']);
        Route::post('login', [GuideController::class, 'login']);
        Route::post('reset', [GuideController::class, 'resetPassword']);
    });
});


//Guide with middleware
Route::group(['prefix' => 'guide', 'middleware' => ['auth:guide']], function () {

    Route::group(['prefix' => 'auth'], function () {
        Route::post('edit', [GuideController::class, 'updateProfile']);
        Route::post('logout', [GuideController::class, 'logout']);
        Route::get('profile', [GuideController::class, 'profile']);
        Route::delete('delete', [GuideController::class, 'deleteAccount']);
    });
});
