<?php

namespace App\Http\Controllers\User;

use App\Models\Trip;
use App\Models\Country;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use App\Http\Requests\IdRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\FavRequest;
use App\Http\Resources\User\CountryResource;
use App\Http\Resources\User\HomeRecResource;
use App\Http\Requests\User\TripFilterRequest;
use App\Http\Resources\User\HomeOfferResource;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\User\TripDetailsResource;
use App\Http\Resources\User\CountryDetailsResource;
use App\Models\Favourite;

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

    
    public function getCountryTrips(IdRequest $request)
    {
        $id = $request->validated()['id'];

        $userId = user_id();

        $trips = 

        Trip::
        Active()
        ->where('country_id' , $id)
        ->favourite()
        ->take(7)->get();
        
        
        if($trips->isEmpty())
        {
            return $this->SendResponse(response::HTTP_OK  , 'no results');
        }

        $trips = HomeRecResource::collection($trips);

        return $this->SendResponse(response::HTTP_OK , 'results retrieved with success' , $trips);
    }
    public function getTripsListByCountry(TripFilterRequest $request)
    {
        $startPrice = $request->validated()['start'];
        $endPrice = $request->validated()['end'];
        $id = $request->validated()['country_id'];

        $userId = user_id();

        $trips = 

        Trip::
        Active()
        ->whereBetween('price_per_one_new', [$startPrice, $endPrice])
        ->where('country_id' , $id)
        ->favourite()
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

        $userId = user_id();

        $trips = Trip::find($id);

        $trips->load(['favourites' => function ($query) use ($userId) {
            $query->where('user_id', $userId);
        }]);
            

        $trips = new TripDetailsResource($trips);

        return $this->SendResponse(response::HTTP_OK , 'trip details retrived with success' ,  $trips);
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
    public function addToFav(FavRequest $request)
    {

        $data['trip_id'] = $request->validated()['trip_id'];
        $data['user_id'] = $request->validated()['user_id'];

        Favourite::create($data);

        return $this->SendResponse(response::HTTP_OK , 'added to favourites with success');
    }
    public function deleteFav(IdRequest $request)
    {
        $favid = $request->validated()['id'];

        Favourite::where('id' , $favid)->delete();

        return $this->SendResponse(response::HTTP_OK , 'deleted from favourites with success');
    }
    public function getFav(IdRequest $request)
    {
        $userId = $request->validated()['id'];

        Favourite::where('user_id' , $userId)->delete();

        return $this->SendResponse(response::HTTP_OK , 'favourites retrieved with success');
    }
    public function appointTrip(){}
    public function unAppointTrip(){}
    public function modifyAppointment(){}
    public function getOldTrips(){}
    
    

}
