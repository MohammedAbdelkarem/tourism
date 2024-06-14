<?php

namespace App\Http\Controllers\Admin;

use App\Models\Facility;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Admin\FacilityResource;
use App\Http\Resources\Admin\FacilityDetailsResource;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\Admin\FacilityRequest;
// use App\Http\Resources\Dashboard\FacilityResource as DashboardFacilityresource;

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
        $data = FacilityResource::collection($hotels);
        return $this->SendResponse(response::HTTP_OK, 'hotels retrieved successfully',$data);
        
    }

    public function getPlaces() 
    { 
        $places = Facility::where('type', 'place')->get();
        if ($places->isEmpty()) {
            return $this->SendResponse(response::HTTP_NOT_FOUND, 'No places found');
        }$data = FacilityResource::collection($places);
        return $this->SendResponse(response::HTTP_OK, 'places retrieved successfully',$data);
    }


    public function getRestaurants()
    {
        $restaurants = Facility::where('type', 'Restaurant')->get();
        if ($restaurants->isEmpty()) {
            return $this->SendResponse(response::HTTP_NOT_FOUND, 'No restaurants found');
        }$data = FacilityResource::collection($restaurants);
        return $this->SendResponse(response::HTTP_OK, 'Restaurants retrieved successfully',  $data);
    }
    public function storeFacility(FacilityRequest $request)
    {
        $facility = Facility::create([
            'name' => $request->name,
            'photo' => $request->photo,
            'lat' => $request->lat,
            'long' => $request->long,
            'bio' => $request->bio,
            'type' => $request->type,
            'number_of_places_available' => $request->number_of_places_available,
            'price_per_person' => $request->price_per_person,
            'country_id' =>$request->country_id,
        ]);

        return $this->SendResponse(response::HTTP_CREATED, 'facility added successfully');
    }
    public function getFacilities()
    {

        $facility = Facility::all();
        $data = FacilityResource::collection($facility);
        
        return $this->SendResponse(response::HTTP_OK, 'Facility data retrieved successfully', $data);
    }
    public function updateFacility( FacilityRequest $request, Facility $facility)
    {
    
        $facility->update([
            'name' => $request->name,
            'photo' => $request->photo,
            'lat' => $request->lat,
            'long' => $request->long,
            'bio' => $request->bio,
            'type' => $request->type,
            'number_of_places_available' => $request->number_of_places_available,
            'price_per_person' => $request->price_per_person,
            'country_id' =>$request->country_id,
        ]);

        return $this->SendResponse(response::HTTP_OK, 'Facility updated successfully');
    }
    public function deleteFacility(Facility $facility)
    {
        $facility->delete();
        return response()->json('facility deleted successfully');
    }

    public function getFacilityDetails(string $id)
    {
        $facility = Facility::query()
        ->where('id', $id)
        ->get();

        
        $data = FacilityDetailsResource::collection($facility);
        return $this->SendResponse(response::HTTP_OK, 'facility details retrieved successfully',  $data);
    }

}
