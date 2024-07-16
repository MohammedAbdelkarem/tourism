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
use App\Http\Controllers\Admin\GuidesController;
use App\Http\Controllers\User\AppointmentController;
use App\Http\Controllers\User\TripsController as UserTripsController;
use App\Http\Controllers\Admin\TripsController as AdminTripsController;
use App\Models\Guide;

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


//Admin with middleware
Route::group(['prefix' => 'admin'], function () {

    Route::group(['prefix' => 'auth'], function () {
        Route::post('send' , [AdminController::class , 'sendCode']);
        Route::post('register' , [AdminController::class , 'register']);
        Route::post('login' , [AdminController::class , 'login']);
        Route::post('reset' , [AdminController::class , 'resetPassword']);
        Route::post('store' , [AdminController::class , 'storeEmail']);
        Route::post('logout', [AdminController::class, 'logout']); 
        // users with middleware
        Route::post('wallet/{userId}', [UsersController::class, 'addToWallet']);
    });
    //Admin without middleware
    Route::post('update_admin_ratio' , [AdminController::class , 'updateAdminRatio']);
    // facility
    
    Route::controller(FacilitesController::class)->group(function () {
        Route::post('facility/store', 'storeFacility');
        Route::get('allfacility', 'getFacilities');
        Route::get('restaurants', 'getRestaurants');
        Route::get('hotels', 'getHotels');
        Route::get('places', 'getPlaces');
        Route::get('facility/{id}', 'getFacilityDetails');
        Route::post('facility/{facility}', 'updateFacility');
        Route::delete('facilities/{facility}', 'deleteFacility');
        Route::get('facilities/nearest/{trip_id}', 'getNearestFacilities');
    });
    //trip
    Route::controller(TripsController::class)->group(function () {
        Route::post('trip/store', 'addTrip');
        Route::post('trip/{trip}', 'updateTrip');
        Route::delete('trip/{trip}', 'deleteTrip');
        Route::get('trip/pending', 'getPinnedTrips');
        Route::get('trip/active', 'getRunningTrips');
        Route::get('trip/finish', 'getFinishidTrips');
        Route::get('trip', 'getTrips');
        Route::get('trip/{trip}', 'getTripDetails');
        Route::get('trip/active/{id}', 'activeTrip');
        Route::get('trip/in_progress/{id}', 'inProgressTrip');
        Route::get('trip/finished/{id}', 'finishTrip');
    });
    
    Route::controller(DaysController::class)->group(function () {
    //days
    Route::post('days', 'addDay');
    Route::post('days/{day}', 'updateDay');
    Route::delete('days/{day}', 'deleteDay');
    //facility in day
    Route::post('facility_in_day', 'addFacilityInDay');
    Route::post('facility_in_day/{facilityInDay}', 'updateFacilityInDay');
    Route::delete('facility_in_day/{facilityInDay}', 'deleteFacilityInDay');
    });
    // users 

    Route::get('users', [UsersController::class, 'getUsers']);
    
    //guides
 
    Route::controller(GuidesController::class)->group(function () {
        Route::post('guides', 'getAvailableGuides');
        Route::get('acceptedTrips', 'acceptedTrips');
        Route::get('rejectedTrips', 'rejectedTrips');
        Route::get('can_change_unique_id/{guideId}', 'update_can_change_unique_id');
        Route::get('accept_by_admin/{guideId}', 'update_accept_by_admin');
    });
    
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
        Route::delete('deleteph', [UserController::class, 'deletePhoto']);
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
