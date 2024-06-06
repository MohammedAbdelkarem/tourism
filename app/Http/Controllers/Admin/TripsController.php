<?php

namespace App\Http\Controllers\Admin;

use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;

class TripsController extends Controller
{
    use ResponseTrait;

    public function addTrip(){

        
    }

    public function updateTrip(){}

    public function deleteTrip(){}

    public function getPinnedTrips(){} //the trips that the admin did not make it active(able to book by the user)

    public function getRunningTrips(){} //the trips that is running currently

    public function getFinishidTrips(){} //the trips that has been finished

    public function getTripDetails(){}
}
