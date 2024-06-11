<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\IdRequest;
use App\Models\Trip;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TripsController extends Controller
{
    use ResponseTrait;

    public function getOffers(){}
    public function getRecommended(){}
    public function getThirdthing(){}

    public function getTripsList() // should be inplemented depending on the price range , the other thing(vip)
    {
        $data = Trip::where('status' , 'active');
    }
    public function getTripDetails(IdRequest $request)
    {
        $id = $request->validated()['id'];

        $data = Trip::where('id' , $id)->get();

        return $this->SendResponse(response::HTTP_OK , 'trip details retrived with success' ,  $data);
    }
    public function appointTrip(){}
    public function unAppointTrip(){}
    public function modifyAppointment(){}
    public function getOldTrips(){}
    public function getCountries(){}
    public function getCountryDetails(){}

}
