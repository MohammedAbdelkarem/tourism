<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Base\BaseNotificationController;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use App\Http\Requests\IdRequest;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;

class NotificationController extends BaseNotificationController
{
    use ResponseTrait;

    public function getAll()
    {
        $user = User::find(user_id());

        return $this->getNotifications($user , 'notifications');
    }
    public function getUnread()
    {
        $user = User::find(user_id());

        return $this->getNotifications($user , 'unreadNotifications');
    }
    public function getRead()
    {
        $user = User::find(user_id());

        return $this->getNotifications($user , 'readNotifications');
    }
    public function markOneAsRead(IdRequest $request)
    {
        $user = User::find(user_id());

        $id = $request->validated()['id'];

        return $this->markNotificationsAsRead($user , $id);
    }
    public function markAllAsRead()
    {
        $user = User::find(user_id());

        return $this->markNotificationsAsRead($user);
    }
}
