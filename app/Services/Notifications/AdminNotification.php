<?php
namespace App\Services\Notifications;
use App\Models\Admin;
use App\Notifications\AdminWallet;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class AdminNotification{

function sendAdminNotification($trip, $amount
){
    $superAdmin = Admin::where('role', 'super_admin')->first();
    Log::info('Sending admin notification with data:', [
        'trip' => $trip,
        'amount' => $amount,
    ]);
    Notification::send($superAdmin, new AdminWallet($trip, $amount));
}

}