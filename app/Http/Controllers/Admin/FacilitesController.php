<?php

namespace App\Http\Controllers\Admin;

use App\Models\Facility;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class FacilitesController extends Controller
{
    use ResponseTrait;

    //the returned data is gonna be alot of details , so custom the data using resource(return just the name , photo and the id of the facility , do the sam thing with getPlaces and getResturants)
    public function getHotels()
    { 
        $hotels = Facility::where('type', 'hotel')->get();
        if ($hotels->isEmpty()) {
            return $this->SendResponse(response::HTTP_NOT_FOUND, 'No hotels found');
        }
        return $this->SendResponse(response::HTTP_OK, 'hotels retrieved successfully', ['data' => $hotels]);
        
    }

    public function getPlaces() 
    { 
        $places = Facility::where('type', 'place')->get();
        if ($places->isEmpty()) {
            return $this->SendResponse(response::HTTP_NOT_FOUND, 'No places found');
        }
        return $this->SendResponse(response::HTTP_OK, 'places retrieved successfully', ['data' => $places]);
    }


    public function getRestaurants()
    {
        $restaurants = Facility::where('type', 'Restaurant')->get();
        if ($restaurants->isEmpty()) {
            return $this->SendResponse(response::HTTP_NOT_FOUND, 'No restaurants found');
        }
        return $this->SendResponse(response::HTTP_OK, 'Restaurants retrieved successfully', ['data' => $restaurants]);
    }
    public function storeFacility(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'lat' => 'required|string|max:200',
            'long' => 'required|string|max:200',
            'bio' => 'required|string',
            'photo' => 'required|mimes:jpg,jpeg,png|max:2048',
            'number_of_places' => 'required|integer|min:1',
            'price_per_person' => 'required|integer',
            'type' => 'required|string|between:2,100',
            'country_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $facility = Facility::create([
            'name' => $request->name,
            'photo' => $request->photo,
            'lat' => $request->lat,
            'long' => $request->long,
            'bio' => $request->bio,
            'type' => $request->type,
            'number_of_places' => $request->number_of_places,
            'price_per_person' => $request->price_per_person,
            'country_id' =>$request->country_id,
        ]);

        return $this->SendResponse(response::HTTP_CREATED, 'facility added successfully');
    }
    public function getFacilities()
    {

        $facility = Facility::all();
        return $this->SendResponse(response::HTTP_OK, 'Facility data retrieved successfully', ['data' => $facility]);
    }
    public function updateFacility(Request $request, Facility $facility)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'lat' => 'required|string|max:200',
            'long' => 'required|string|max:200',
            'bio' => 'required|string',
            'photo' => 'nullable|mimes:jpg,jpeg,png|max:2048',
            'number_of_places' => 'required|integer|min:1',
            'price_per_person' => 'required|integer',
            'type' => 'required|string',
            'country_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $facility->update([
            'name' => $request->name,
            'photo' => $request->photo,
            'lat' => $request->lat,
            'long' => $request->long,
            'bio' => $request->bio,
            'type' => $request->type,
            'number_of_places' => $request->number_of_places,
            'price_per_person' => $request->price_per_person,
            'country_id' =>$request->country_id,
        ]);

        return $this->SendResponse(response::HTTP_OK, 'Facility updated successfully');
    }
    public function deleteFacility(Facility $facility)
    {
        $facility->delete();
        return response()->json('product deleted successfully');
    }

    public function getFacilityDetails()
    {
        //what to wend in the request:the id of the facility

        //what to response: the facility details
    }
}
