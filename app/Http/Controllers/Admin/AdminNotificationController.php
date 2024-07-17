<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;

class AdminNotificationController extends Controller
{

    use ResponseTrait;
    
    public function getAdminNotification(){
        $Admin = Admin::find(auth()->id());
        $notification=$Admin->notifications()->get();;
        return $this->SendResponse(response::HTTP_OK, 'notification retrieved successfully', $notification);
}

public function getUnReadAdminNotification(){
    $Admin = Admin::find(auth()->id());
    $notification=$Admin->unreadNotifications()->get();
    return $this->SendResponse(response::HTTP_OK, 'notification retrieved successfully', $notification);
}

public function markReadAdminNotification(){
    $Admin = Admin::find(auth()->id());
    foreach ($Admin->unreadNotifications as $notification) {
        $notification->markAsRead();}
   
    return $this->SendResponse(response::HTTP_OK, 'success');
}

public function deleteAllNotification(){
    $Admin = Admin::find(auth()->id());
    $Admin->notifications()->delete();
   
    return $this->SendResponse(response::HTTP_OK, ' All notifications deleted successfully');
}

public function deleteNotification($id){
    $Admin = Admin::find(auth()->id());
    DB::table("notifications")->where('id',$id)->delete();
   
    return $this->SendResponse(response::HTTP_OK, 'notification deleted successfully');
}
}