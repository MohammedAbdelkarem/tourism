<?php

namespace App\Http\Controllers\Base;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;

class BaseNotificationController extends Controller
{
    use ResponseTrait;

    protected function getNotifications($user, $notificationType)
    {
        $notifications['content'] = $user->{$notificationType}()->get();
        $notifications['count'] = $user->{$notificationType}()->count();

        if ($notifications['count'] == 0)
        {
            return $this->SendResponse(Response::HTTP_OK, 'no notifications yet');
        }

        $notificationTypeString = ($notificationType === 'notifications') ? 'all' : ($notificationType === 'unreadNotifications' ? 'unreaded' : 'readed');

        return $this->SendResponse(Response::HTTP_OK, "{$notificationTypeString} notifications retrieved with success", $notifications);
    }

    protected function markNotificationsAsRead($user , $notificatoinId = null)
    {
        $notificationTypeString = ($notificatoinId === null) ? 'all notifications' : 'one notification';
        if($notificatoinId)
        {
            DB::table('notifications')->where('id' , $notificatoinId)->update([
                'read_at' => Carbon::now()
            ]);
        }
        else
        {
            $user->unreadNotifications()->update([
                'read_at' => Carbon::now()
            ]);
        }

        return $this->SendResponse(Response::HTTP_OK, "{$notificationTypeString} marked as read with success");
    }
}
