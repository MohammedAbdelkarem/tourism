<?php

namespace App\Http\Controllers\Admin;

use App\Models\Trip;
use App\Models\Facility;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Admin\LatLongRequest;
use App\Http\Requests\Admin\FacilityRequest;
use App\Http\Resources\Admin\FacilityResource;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\Admin\FacilityDetailsResource;
// use App\Http\Resources\Dashboard\FacilityResource as DashboardFacilityresource;

class FacilitesController extends Controller
{
    use ResponseTrait;
    

 public function getFacilitiesByType($type)
{
    $facilities = Facility::ofType($type)->get();

    if ($facilities->isEmpty()) {
        return $this->SendResponse(response::HTTP_NOT_FOUND, "No $type found");
    }

    $data = FacilityResource::collection($facilities);
    return $this->SendResponse(response::HTTP_OK, "$type retrieved successfully", $data);
}



    public function getNearestFacilities($trip_id)
    {
        $trip = Trip::find($trip_id);
    
        $lat = $trip->lat;
        $long = $trip->long;
    

       $lat1=$lat+0.05;
       $lat2=$lat-0.05;
       $long1=$long+0.05;
       $long2=$long-0.05;

       $facilities = Facility::where('lat', '>=', $lat2)
       ->where('lat', '<=', $lat1)
       ->where('long', '>=', $long2)
       ->where('long', '<=', $long1)
       ->get();
       return $this->SendResponse(response::HTTP_OK, 'Facilities retrieved successfully', $facilities );
           
     
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
            'number_of_available_places' => $request->number_of_available_places,
            'price_per_person' => $request->price_per_person,
            'country_id' =>$request->country_id,
        ]);

        return $this->SendResponse(response::HTTP_CREATED, 'facility added successfully');
    }
    public function getFacilities()
    {

        $facility = Facility::all();
        $data = FacilityResource::collection($facility);
        
        return $this->SendResponse(response::HTTP_OK, 'Facilities retrieved successfully', $data);
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
            'number_of_available_places' => $request->number_of_available_places,
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
