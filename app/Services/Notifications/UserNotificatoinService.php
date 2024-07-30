<?php
namespace App\Services\Notifications;

use App\Models\Reservatoin;
use App\Models\User;
use App\Notifications\User\NoteNotification;
use App\Notifications\User\OfferNotification;
use App\Notifications\User\UserWalletNotification;
use App\Notifications\User\WalletNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;


class UserNotificatoinService
{
    public function SendWalletNotification($user_id , $amount , $reservatioin_id , $type)
    {
        $user = User::where('id', $user_id)->first();

        Notification::send($user, new WalletNotification($reservatioin_id, $amount , $type));
    }
    public function sendOfferNotification($trip_id)
    {
        $users = User::all();

        Notification::send($users, new OfferNotification($trip_id));
    }
    public function SendNoteNotification($trip_id)
    {
        $users = User::whereIn('id', function ($query) use ($trip_id) {
            $query->select('user_id')
                ->from('reservatoins')
                ->where('trip_id', $trip_id);
        })->get();

        Notification::send($users, new NoteNotification($trip_id));
    }
}