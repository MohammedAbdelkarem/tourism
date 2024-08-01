<?php
namespace App\Services\Notifications;

use App\Models\FacilityDay;
use App\Models\User;
use App\Models\Reservatoin;
use App\Models\FacilityInDay;
use Illuminate\Support\Facades\Log;
use App\Notifications\User\NoteNotification;
use Illuminate\Support\Facades\Notification;
use App\Notifications\User\OfferNotification;
use App\Notifications\User\WalletNotification;
use App\Notifications\User\UserWalletNotification;


class UserNotificatoinService
{
    public function SendWalletNotification($user_id , $amount , $type , $reservatioin_id = null)//done
    {
        $user = User::where('id', $user_id)->first();

        Notification::send($user, new WalletNotification($reservatioin_id, $amount , $type));
    }
    public function sendOfferNotification($trip_id)//done
    {
        $users = User::all();

        Notification::send($users, new OfferNotification($trip_id));
    }
    public function SendNoteNotification($note , $facility_in_day_id)//done
    {
        $facility_day_id = FacilityInDay::find($facility_in_day_id)->pluck('id')->first();

        $trip_id = FacilityDay::find($facility_day_id)->pluck('trip_id')->first();
        
        $users = User::whereIn('id', function ($query) use ($trip_id) {
            $query->select('user_id')
                ->from('reservatoins')
                ->where('trip_id', $trip_id);
        })->get();

        Notification::send($users, new NoteNotification($note , $facility_in_day_id));
    }
}