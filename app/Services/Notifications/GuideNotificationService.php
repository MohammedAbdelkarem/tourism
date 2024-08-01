<?php
namespace App\Services\Notifications;

use App\Models\Guide;
use App\Notifications\Guide\ApprovmentNotification;
use App\Notifications\Guide\NewTripNotification;
use App\Notifications\Guide\UniqueIdNotification;
use App\Notifications\Guide\WalletNotification;
use Illuminate\Support\Facades\Notification;




class GuideNotificationService
{
    public function SendApprovmentNotification($approvment , $guide_id)//done
    {
        $guide = Guide::where('id', $guide_id)->first();

        Notification::send($guide, new ApprovmentNotification($approvment));
    }
    public function SendUniqueIdNotification($status , $guide_id)//done
    {
        $guide = Guide::where('id', $guide_id)->first();

        Notification::send($guide, new UniqueIdNotification($status));
    }
    public function SendNewTripNotification($trip_id , $guide_id)//done
    {
        $guide = Guide::where('id', $guide_id)->first();

        Notification::send($guide, new NewTripNotification($trip_id));
    }
    public function SendWalletNotification($amount , $trip_id , $guide_id)//done
    {
        $guide = Guide::where('id', $guide_id)->first();

        Notification::send($guide, new WalletNotification($amount , $trip_id));
    }
}