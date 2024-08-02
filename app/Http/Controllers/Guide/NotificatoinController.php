<?php

namespace App\Http\Controllers\Guide;

use App\Http\Controllers\Base\BaseNotificationController;
use App\Models\Guide;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use App\Http\Requests\IdRequest;
use App\Http\Controllers\Controller;

class NotificatoinController extends BaseNotificationController
{
    use ResponseTrait;

    public function getAll()
    {
        $guide = Guide::find(guide_id());

        return $this->getNotifications($guide , 'notifications');
    }
    public function getUnread()
    {
        $guide = Guide::find(guide_id());

        return $this->getNotifications($guide , 'unreadNotifications');
    }
    public function getRead()
    {
        $guide = Guide::find(guide_id());

        return $this->getNotifications($guide , 'readNotifications');
    }
    public function markOneAsRead(IdRequest $request)
    {
        $guide = Guide::find(guide_id());

        $id = $request->validated()['id'];

        return $this->markNotificationsAsRead($guide , $id);
    }
    public function markAllAsRead()
    {
        $guide = Guide::find(guide_id());

        return $this->markNotificationsAsRead($guide);
    }
}
