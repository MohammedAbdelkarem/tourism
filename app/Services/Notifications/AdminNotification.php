<?php
namespace App\Services\Notifications;
use App\Models\Trip;
use App\Models\Admin;
use Illuminate\Support\Facades\Log;
use App\Notifications\Admin\AdminWallet;
use Illuminate\Support\Facades\Notification;
use App\Notifications\Admin\NewCommentOnTrip;
use App\Notifications\Admin\NewGuideRegistered;
use App\Notifications\Admin\TripAcceptedByGuide;
use App\Notifications\Admin\TripFilledPlacesUpdate;

class AdminNotification{

function sendAdminWalletNotification($trip, $amount){
    $superAdmin = Admin::where('role', 'super_admin')->first();
    $trip_id = $trip -> id;
    Log::info('Sending admin notification with data:', [
        'trip_id' => $trip_id,
        'amount' => $amount,
    ]);
    Notification::send($superAdmin, new AdminWallet($trip_id, $amount));
}



function sendNotificationIfNewGuideRegistered($guide) {
    $admins = Admin::where('role', 'super_admin')->first();
    Log::info('Sending notification to all admins with data:', [
        'guide' => $guide,
    ]);
    Notification::send($admins, new NewGuideRegistered( $guide));
   
    
}


function sendNotificationIfTripFilledPlacesUpdate($trip,$filled_places) {
    $admins = Admin::all();
    $trip_name = Trip::find($trip)->name;
    Log::info('Sending notification to all admins with data:', [
        'trip' => $trip,
        'filled_places' => $filled_places,
        'trip_name' =>$trip_name,
    ]);
    foreach ($admins as $admin) {
        Notification::send($admin, new TripFilledPlacesUpdate($trip_name,$trip,$filled_places));
    }
}

function sendNotificationIfTripAcceptedByGuide($trip) {
    $admins = Admin::all();
    $trip_name = Trip::find($trip)->name;
    Log::info('Sending notification to all admins with data:', [
        'trip' => $trip,
        'trip_name' =>$trip_name,
    ]);
    foreach ($admins as $admin) {
        Notification::send($admin, new TripAcceptedByGuide($trip_name,$trip));
    }
}

function sendNotificationIfNewCommentOnTrip($trip,$comment,$user) {
    $admins = Admin::all();
    $trip_name = Trip::find($trip)->name;
    Log::info('Sending notification to all admins with data:', [
        'trip_name' =>$trip_name,
        'trip' => $trip,
        'comment' => $comment,
        'user' => $user,
    ]);
    foreach ($admins as $admin) {
        Notification::send($admin, new NewCommentOnTrip($trip_name,$trip,$comment,$user));
    }
}


}