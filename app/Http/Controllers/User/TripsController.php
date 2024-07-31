<?php

namespace App\Http\Controllers\User;

use App\Models\Trip;
use App\Models\Country;
use App\Models\Favourite;
use App\Models\TripComment;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use App\Http\Requests\IdRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\FavRequest;
use App\Http\Resources\User\FavResource;
use App\Http\Resources\Admin\TripResource;
use App\Http\Resources\User\CountryResource;
use App\Http\Resources\User\HomeRecResource;
use App\Http\Requests\User\AddCommentRequest;
use App\Http\Requests\User\TripFilterRequest;
use App\Http\Resources\User\HomeOfferResource;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\User\TripDetailsResource;
use App\Services\Notifications\AdminNotification;
use App\Http\Resources\User\CountryDetailsResource;

class TripsController extends Controller
{
    use ResponseTrait;

    public function getHomeOffers()//for the offers in the home , regardless of the country
    {
        $records = Trip::Active()->Offer()->favourite()->orderByDesc('offer_ratio')->take(7)->get();

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

    
    
    public function getTripDetails(IdRequest $request)
    {
        $id = $request->validated()['id'];

        $userId = user_id();

        $trips = Trip::find($id);

        $trips->load(['favourites' => function ($query) use ($userId , $id) {
            $query->where('user_id', $userId)->where('trip_id' , $id);
        }]);
        $trips->load(['reservatoin' => function ($query) use ($userId , $id) {
            $query->where('user_id' , $userId)
                ->where('trip_id', $id);
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

    public function addToFav(IdRequest $request)
    {

        $data['trip_id'] = $request->validated()['id'];
        $data['user_id'] = user_id();

        Favourite::create($data);

        return $this->SendResponse(response::HTTP_OK , 'added to favourites with success');
    }
    public function deleteFav(IdRequest $request)
    {
        $tripId = $request->validated()['id'];

        Favourite::where('trip_id' , $tripId)->
        where('user_id' , user_id())->
        delete();

        return $this->SendResponse(response::HTTP_OK , 'deleted from favourites with success');
    }
    public function getFav()
    {
        $userId = user_id();

        $ids = Favourite::where('user_id' , $userId)->pluck('trip_id');

        // dd($ids);

        $data = [];
        foreach($ids as $id)
        {
            $data[] = Trip::where('id' , $id)->get()->first();
        }

        // $data = Trip::where('user_id' , $userId)->with('trip')->get();
        if(count($data) == 0)
        {
            return $this->SendResponse(response::HTTP_OK , 'no favourites yet');
        }

        // $data = TripDetailsResource::collection($data);

        return $this->SendResponse(response::HTTP_OK , 'favourites retrieved with success' , $data);
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

    public function addComment(AddCommentRequest $request)
    {
        $trip_id = $request->validated()['trip_id'];
        $comment = $request->validated()['comment'];

        $user_id = user_id();

        $data['user_backup_id'] = $user_id;
        $data['trip_id'] = $trip_id;
        $data['comment'] = $comment;

        TripComment::create($data);

        $adminNotification = new AdminNotification();
        $adminNotification->sendNotificationIfNewCommentOnTrip($trip_id,$comment,$user_id);
        return $this->SendResponse(response::HTTP_CREATED , 'comment added with success');
    }
    public function deleteComment(IdRequest $request)
    {
        $id = $request->validated()['id'];

        TripComment::where('id' , $id)->delete();

        return $this->SendResponse(response::HTTP_OK , 'comment deleted with success');
    }

}
