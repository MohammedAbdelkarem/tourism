<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TripsController extends Controller
{
    use ResponseTrait;

    public function getOffers(){}
    public function getRecommended(){}
    public function getThirdthing(){}
    public function getTripDetails(){}
    public function appointTrip(){}
    public function unAppointTrip(){}
    public function modifyAppointment(){}
    public function getOldTrips(){}
    public function getCountries(){}
    public function getCountryDetails(){}

}
