<?php

namespace App\Http\Controllers\User;

use App\Models\Trip;
use App\Models\Country;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use App\Http\Requests\IdRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\User\CountryResource;
use App\Http\Resources\User\HomeRecResource;
use App\Http\Requests\User\TripFilterRequest;
use App\Http\Resources\User\HomeOfferResource;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\User\TripDetailsResource;
use App\Http\Resources\User\CountryDetailsResource;

class TripsController extends Controller
{
    use ResponseTrait;

    public function getHomeOffers()//for the offers in the home , regardless of the country
    {
        $records = Trip::Active()->Offer()->orderByDesc('offer_ratio')->take(7)->get();

        $records = HomeOfferResource::collection($records);

        return $this->SendResponse(response::HTTP_OK , 'home offers retrieved with success' , $records);
    }
    public function getAllOffers()//to view all the offers when clicking view_all
    {
        $records = Trip::Active()->Offer()->orderByDesc('offer_ratio')->get();

        $records = HomeOfferResource::collection($records);//not sure if there will be the offer in the resource or not

        return $this->SendResponse(response::HTTP_OK , 'offers list retrieved with success' , $records);
    }
    public function getHomeRecommended()
    {
        $records = Trip::Active()->where('rate' , '>=' , '3')->take(7)->get();

        $records = HomeRecResource::collection($records);
        
        return $this->SendResponse(response::HTTP_OK , 'home recommendeds retrieved with success' , $records);
    }
    public function getAllRecommended()
    {
        $records = Trip::Active()->where('rate' , '>=' , '3')->get();

        $records = HomeRecResource::collection($records);
        
        return $this->SendResponse(response::HTTP_OK , 'recommended list retrieved with success' , $records);
    }

    // public function getTripsList(Request $request) // should be implemented depending on the price range
    // {
    //     $startPrice = $request->validated()['start'];
    //     $endPrice = $request->validated()['end'];

    //     $trips = Trip::Active()->when($startPrice !== null, function ($query) use ($startPrice) {
    //         return $query->where('price', '>=', $startPrice);
    //     })
    //     ->when($endPrice !== null, function ($query) use ($endPrice) {
    //         return $query->where('price', '<=', $endPrice);
    //     })
    //     ->get();


    //     if($trips->isEmpty())
    //     {
    //         return $this->SendResponse(response::HTTP_OK  , 'no results');
    //     }
    //     return $this->SendResponse(response::HTTP_OK , 'results retrieved with success' , $trips);
    // }
    public function getTripsListByCountry(TripFilterRequest $request)
    {
        $startPrice = $request->validated()['start'];
        $endPrice = $request->validated()['end'];
        $id = $request->validated()['country_id'];

        $trips = 

        Trip::
        // when($startPrice !== null, function ($query) use ($startPrice) {
        //     return $query->where('price', '>=', $startPrice);
        // })
        // ->when($endPrice !== null, function ($query) use ($endPrice) {
        //     return $query->where('price', '<=', $endPrice);
        // })
        Active()
        ->where('price_per_one_new', '>=', $startPrice)
        ->where('price_per_one_new', '<=', $endPrice)
        ->where('country_id' , $id)
        ->get();
        
        
        if($trips->isEmpty())
        {
            return $this->SendResponse(response::HTTP_OK  , 'no results');
        }

        $trips = HomeRecResource::collection($trips);

        return $this->SendResponse(response::HTTP_OK , 'results retrieved with success' , $trips);
    }
    public function getTripDetails(IdRequest $request)
    {
        $id = $request->validated()['id'];

        $data = Trip::find($id);

        $data = new TripDetailsResource($data);

        return $this->SendResponse(response::HTTP_OK , 'trip details retrived with success' ,  $data);
    }

    public function search(Request $request)
    {
        $field = $request->field;
        $results = Trip::Active()->
        where('name', 'LIKE', "%$field%")
        ->orWhere('price_per_one_new', 'LIKE', "%$field%")
        ->orWhere('bio', 'LIKE', "%$field%")
        ->get();
        
        if($results->isEmpty())
        {
            return $this->SendResponse(response::HTTP_OK  , 'no results');
        }

        $results = HomeRecResource::collection($results);

        return $this->SendResponse(response::HTTP_OK , 'results retrieved with success' , $results);
    }

    public function getHomeCountries()
    {
        $records = Country::take(6)->get();

        $records = CountryResource::collection($records);

        return $this->SendResponse(response::HTTP_OK , 'countries retrieved with success' , $records);
    }

    public function getCountryDetails(IdRequest $request)
    {
        $id = $request->validated()['id'];

        $data = Country::find($id);

        $data = new CountryDetailsResource($data);

        return $this->SendResponse(response::HTTP_OK , 'countries retrieved with success' , $data);
    }
    public function appointTrip(){}
    public function unAppointTrip(){}
    public function modifyAppointment(){}
    public function getOldTrips(){}
    

}
