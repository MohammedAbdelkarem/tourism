<?php
namespace App\Services\Notifications;
use App\Models\Admin;
use App\Notifications\Admin\AdminWallet;
use Illuminate\Support\Facades\Log;
use App\Notifications\Admin\NewCommentOnTrip;
use App\Notifications\Admin\NewGuideRegistered;
use App\Notifications\Admin\TripAcceptedByGuide;
use Illuminate\Support\Facades\Notification;
use App\Notifications\Admin\TripFilledPlacesUpdate;

class AdminNotification{

function sendAdminNotification($trip, $amount){
    $superAdmin = Admin::where('role', 'super_admin')->first();
    Log::info('Sending admin notification with data:', [
        'trip' => $trip,
        'amount' => $amount,
    ]);
    Notification::send($superAdmin, new AdminWallet($trip, $amount));
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
    Log::info('Sending notification to all admins with data:', [
        'trip' => $trip,
        'filled_places' => $filled_places,
    ]);
    foreach ($admins as $admin) {
        Notification::send($admin, new TripFilledPlacesUpdate($trip,$filled_places));
    }
}

function sendNotificationIfTripAcceptedByGuide($trip) {
    $admins = Admin::all();
    Log::info('Sending notification to all admins with data:', [
        'trip' => $trip,
        
    ]);
    foreach ($admins as $admin) {
        Notification::send($admin, new TripAcceptedByGuide($trip));
    }
}

function sendNotificationIfNewCommentOnTrip($trip,$comment,$user) {
    $admins = Admin::all();
    Log::info('Sending notification to all admins with data:', [
        'trip' => $trip,
        'comment' => $comment,
        'user' => $user,
    ]);
    foreach ($admins as $admin) {
        Notification::send($admin, new NewCommentOnTrip($trip,$comment,$user));
    }
}


}