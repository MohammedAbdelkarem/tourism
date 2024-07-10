<?php

namespace App\Http\Controllers\Guide;

use Carbon\Carbon;
use App\Models\Trip;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;

class GuideController extends Controller
{
    use ResponseTrait;

    public function getPreviousTrips()
    {
        $trips = Trip::
        where('guide_backup_id' , guide_id())
        ->where('end_date' , '<' , Carbon::now())
        ->get();

        return $this->SendResponse(response::HTTP_OK , 'previous trips retrieved with success' , $trips);
    }
    public function getComingTrips()
    {
        $trips = Trip::
        where('guide_backup_id' , guide_id())
        ->where('start_date' , '>' , Carbon::now())
        ->get();

        return $this->SendResponse(response::HTTP_OK , 'coming trips retrieved with success' , $trips);
    }
    public function getCurrentTrips()
    {
        $trips = Trip::
        where('guide_backup_id' , guide_id())
        ->where('status' , 'in_progress')
        ->get();

        return $this->SendResponse(response::HTTP_OK , 'current trips retrieved with success' , $trips);
    }
    public function getPendingTrips(){}
    public function getTripDetails(){}
    public function GuideTransactios(){}
    public function changeStatus(){}
    public function changeUniqueId(){}
    public function getPermissions(){} // if he can modify the unique is + if he is accepted by the admin
}
