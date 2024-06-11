<?php

namespace App\Http\Controllers\User;

use App\Models\Trip;
use App\Models\Country;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use App\Http\Requests\IdRequest;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;

class TripsController extends Controller
{
    use ResponseTrait;

    public function getHomeOffers()
    {
        $records = Trip::orderByDesc('offer_ratio')->take(7)->get();
        //resource
    }
    public function getAllOffers()
    {
        $records = Trip::orderByDesc('offer_ratio')->get();
        //resource
    }
    public function getHomeRecommended()
    {
        $records = Trip::where('rate' , '>=' , '3')->take(7)->get();
        //resource
    }
    public function getAllRecommended()
    {
        $records = Trip::where('rate' , '>=' , '3')->get();
        //resource
    }

    public function getTripsList(Request $request) // should be implemented depending on the price range
    {
        $startPrice = $request->validated()['start'];
        $endPrice = $request->validated()['end'];

        $trips = Trip::when($startPrice !== null, function ($query) use ($startPrice) {
            return $query->where('price', '>=', $startPrice);
        })
        ->when($endPrice !== null, function ($query) use ($endPrice) {
            return $query->where('price', '<=', $endPrice);
        })
        ->get();
        //resource
    }
    public function getTripsListByCountry(Request $request)
    {
        $startPrice = $request->validated()['start'];
        $endPrice = $request->validated()['end'];
        $id = $request->validated()['country_id'];

        $trips = Trip::when($startPrice !== null, function ($query) use ($startPrice) {
            return $query->where('price', '>=', $startPrice);
        })
        ->when($endPrice !== null, function ($query) use ($endPrice) {
            return $query->where('price', '<=', $endPrice);
        })
        ->where('country_id' , $id)
        ->get();
        //resource
    }
    public function getTripDetails(IdRequest $request)
    {
        $id = $request->validated()['id'];

        $data = Trip::where('id' , $id)->get();

        //resource

        return $this->SendResponse(response::HTTP_OK , 'trip details retrived with success' ,  $data);
    }

    public function search($field)
    {
        $medicines = Trip::where('name', 'LIKE', "%$field%")
        ->bio('price_per_one_new', 'LIKE', "%$field%")
        ->orWhere('bio', 'LIKE', "%$field%")
        ->get();
        
        ///resource
    }

    public function getHomeCountries()
    {
        $records = Country::take(7)->get();

        //resource
    }
    public function appointTrip(){}
    public function unAppointTrip(){}
    public function modifyAppointment(){}
    public function getOldTrips(){}
    
    public function getCountryDetails(){}

}
