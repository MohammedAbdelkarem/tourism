<?php

use App\Models\Guide;


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\Admin\DaysController;
use App\Http\Controllers\Auth\AdminController;
use App\Http\Controllers\Auth\GuideController;
use App\Http\Controllers\Admin\TripsController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\GuidesController;
use App\Http\Controllers\User\FacilityController;
use App\Http\Controllers\Admin\FacilitesController;
use App\Http\Controllers\User\AppointmentController;
use App\Http\Controllers\Admin\AdminNotificationController;
use App\Http\Controllers\User\TripsController as UserTripsController;
use App\Http\Controllers\Admin\TripsController as AdminTripsController;
use App\Http\Controllers\User\CountryController;

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


//Admin
Route::group(['prefix' => 'admin'], function () {

    Route::group(['prefix' => 'auth'], function () {
        Route::post('send' , [AdminController::class , 'sendCode']);
        Route::post('register' , [AdminController::class , 'register']);
        Route::post('login' , [AdminController::class , 'login']);
        Route::post('reset' , [AdminController::class , 'resetPassword']);
        Route::post('store' , [AdminController::class , 'storeEmail']);
        Route::post('logout', [AdminController::class, 'logout'])->middleware('auth:admin');

    });

    
    Route::controller(UsersController::class)->middleware('auth:admin')->group(function () {
        Route::post('wallet/{userId}', 'addToWallet');
    });

    //Admin 
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

    Route::controller(TripsController::class)->middleware('auth:admin')->group(function () {
        Route::post('trip/store', 'addTrip');
        Route::post('trip/{trip}', 'updateTrip');
        Route::delete('trip/{trip}', 'deleteTrip');
        Route::get('trip/finished/{id}', 'finishTrip');
     });
    Route::controller(TripsController::class)->group(function () {
        Route::get('trip/pending', 'getPinnedTrips');
        Route::get('trip/in_progress', 'getInprogressTrip');
        Route::get('trip/active', 'getRunningTrips');
        Route::get('trip/finish', 'getFinishidTrips');
        Route::get('trip', 'getTrips');
        Route::get('trip/{trip}', 'getTripDetails');
        Route::get('trip/active/{id}', 'activeTrip');
        Route::get('trip/in_progress/{id}', 'inProgressTrip');
        Route::get('days', 'getDays');
        Route::get('countries', 'getcountries');
    });
    
    Route::controller(DaysController::class)->middleware('auth:admin')->group(function () {
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
    
    //Admin notifications with middleware
    Route::controller(AdminNotificationController::class)->middleware('auth:admin')->group(function () {
        Route::get('getAdminNotification', 'getAdminNotification');
        Route::get('getUnReadAdminNotification', 'getUnReadAdminNotification');
        Route::get('markReadAdminNotification', 'markReadAdminNotification');
        Route::delete('deleteAllNotification', 'deleteAllNotification');
        Route::delete('deleteNotification/{id}', 'deleteNotification');
    });
});





//User without middleware
Route::group(['prefix' => 'user'], function () {

    Route::group(['prefix' => 'auth'], function () {

        Route::controller(UserController::class)->group(function () {
            Route::post('send',  'sendCode');
            Route::post('register',  'register');
            Route::post('login',  'login');
            Route::post('reset',  'resetPassword');
        });
    });

    Route::group(['prefix' => 'trip'], function () {

        Route::controller(UserTripsController::class)->group(function () {
            Route::get('listoffers', 'getAllOffers');
            Route::get('listrec', 'getAllRecommended');
            Route::post('triplist', 'getTripsListByCountry');
            Route::post('details', 'getTripDetails');
            Route::post('search', 'search');
        });
    });

    Route::group(['prefix' => 'facility'], function () {

        Route::controller(FacilityController::class)->group(function () {
            Route::post('details',  'getFacilityDetails');
        });
    });
    Route::group(['prefix' => 'country'], function () {

        Route::controller(CountryController::class)->group(function () {
            Route::get('get',  'getCountries');
            Route::post('details',  'getCountryDetails');
        });
    });
    Route::group(['prefix' => 'appoint'], function () {

        Route::controller(AppointmentController::class)->group(function () {
            Route::post('day',  'getDayDetails');
        });
    });
    

});


//User with middleware
Route::group(['prefix' => 'user', 'middleware' => ['auth:user']], function () {

    Route::group(['prefix' => 'auth'], function () {

        Route::controller(UserController::class)->group(function () {
            Route::post('edit', 'updateProfile');
            Route::post('logout', 'logout');
            Route::get('profile', 'profile');
            Route::delete('delete', 'deleteAccount');
            Route::delete('deleteph', 'deletePhoto');
        });
    });

    Route::group(['prefix' => 'appoint'], function () {

        Route::controller(AppointmentController::class)->group(function () {
            Route::post('appoint',  'appointTrip');
            Route::post('unappoint',  'unAppointTrip');
            Route::get('get',  'getMyTrips');
            Route::post('modify',  'modifyAppointment');
            Route::get('transactions',  'getTransactions');
        });
    });

    Route::group(['prefix' => 'favourite'], function () {

        Route::controller(UserTripsController::class)->group(function () {
            Route::post('add',  'addToFav');
            Route::post('delete',  'deleteFav');
            Route::get('get',  'getFav');
        });
    });
    Route::group(['prefix' => 'comment'], function () {

        Route::controller(UserTripsController::class)->group(function () {
            Route::post('add' , 'addComment');
            Route::post('delete' , 'deleteComment');
        });
    });
    Route::group(['prefix' => 'rate'], function () {

        Route::controller(FacilityController::class)->group(function () {
            Route::post('add' , 'addRate');
            Route::post('delete' , 'deleteRate');
            Route::post('update' , 'modifyRate');
        });
    });

});



//Guide without middleware
Route::group(['prefix' => 'guide'], function () {

    Route::group(['prefix' => 'auth'], function () {

        Route::controller(GuideController::class)->group(function () {
            Route::post('send',  'sendCode');
            Route::post('register',  'register');
            Route::post('login',  'login');
            Route::post('reset',  'resetPassword');
        });
    });
});


//Guide with middleware
Route::group(['prefix' => 'guide', 'middleware' => ['auth:guide']], function () {

    Route::group(['prefix' => 'auth'], function () {

        Route::controller(GuideController::class)->group(function () {
            Route::post('edit',  'updateProfile');
            Route::post('logout',  'logout');
            Route::get('profile',  'profile');
            Route::delete('delete',  'deleteAccount');
        });
    });
});
