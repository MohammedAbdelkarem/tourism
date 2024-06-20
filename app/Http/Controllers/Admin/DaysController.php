<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Admin\DayRequest;
use App\Models\FacilityDay;
use App\Models\Facility;
use App\Models\Trip;
use Symfony\Component\HttpFoundation\Response;
use App\Traits\ResponseTrait;
use PhpParser\Node\Expr\Cast\Double;
use Ramsey\Uuid\Type\Integer;

class DaysController extends Controller
{
     use ResponseTrait;
     

         public function getNearestFacilities($trip_id)
         {
             $trip = Trip::find($trip_id);
             if (!$trip) {
                 // handle the case where the trip is not found
                 return [];
             }
         
             $lat = $trip->lat;
             $long = $trip->long;
         
             $facilities = Facility::selectRaw("*, ST_Distance_Sphere(point(`long`, `lat`), point(?,?)) as distance")
                 ->addBinding($long)
                 ->addBinding($lat)
                 ->having('distance', '<=', 2000) // 2000 meters = 2 kilometers
                 ->orderBy('distance')
                 ->get();
         

                 return $this->SendResponse(response::HTTP_OK, 'Facilities retrieved successfully', $facilities);
          
         }




     
     public function addDay(DayRequest $request)
     {
         
     
        //  $nearestFacilities = $this->getNearestFacilities($lat, $long);
     
         // allow user to select multiple facilities
         $selectedFacilities = $request->input('selected_facilities');
     
         // Validate that at least one facility is selected
         $request->validate([
             'selected_facilities' => 'required|array|min:1',
         ]);
     
         foreach ($selectedFacilities as $facilityId) {
             $day = FacilityDay::create([
                 'date' => $request->date,
                 'start_time' => $request->start_time,
                 'end_time' => $request->end_time,
                 'day_id' => $request->day_id,
                 'facility_id' => $facilityId,
                 'trip_id' => $request->trip_id,
             ]);
         }
     
         return $this->SendResponse(response::HTTP_CREATED, 'day added successfully');
     }


     public function updateDay(DayRequest $request ,FacilityDay $day)
     {
         $trip = Trip::find($request->trip_id);
         $lat = $trip->lat;
         $long = $trip->long;
     
         $nearestFacilities = $this->getNearestFacilities($lat, $long);
     
         // allow user to select multiple facilities
         $selectedFacilities = $request->input('selected_facilities');
     
         // Validate that at least one facility is selected
         $request->validate([
             'selected_facilities' => 'required|array|min:1',
         ]);
     
         foreach ($selectedFacilities as $facilityId) {
             $day ->update([
                 'date' => $request->date,
                 'start_time' => $request->start_time,
                 'end_time' => $request->end_time,
                 'day_id' => $request->day_id,
                 'facility_id' => $facilityId,
                 'trip_id' => $request->trip_id,
             ]);
         }
     
         return $this->SendResponse(response::HTTP_CREATED, 'day updated successfully');
     }

     

}

