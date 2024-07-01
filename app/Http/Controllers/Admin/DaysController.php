<?php

namespace App\Http\Controllers\Admin;

use App\Models\Trip;
use App\Models\Facility;
use App\Models\FacilityDay;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use Ramsey\Uuid\Type\Integer;
use App\Http\Controllers\Controller;
use PhpParser\Node\Expr\Cast\Double;
use App\Http\Requests\Admin\DayRequest;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\Admin\FacilityInDayRequest;
use App\Models\FacilityInDay;

class DaysController extends Controller
{
     use ResponseTrait;
     





     
     public function addDay(DayRequest $request)
     {
         // allow user to select multiple facilities
        //  $selectedFacilities = $request->validated()['selected_facilities'];
        //  foreach ($selectedFacilities as $facilityId) {
             $day = FacilityDay::create([
                 'date' => $request->date,
                 'day_id' => $request->day_id,
                 'trip_id' => $request->trip_id,
                 //  'facility_id' => $facilityId,
                 //  'start_time' => $request->start_time,
                //  'end_time' => $request->end_time,
             ]);
        //  
     
         return $this->SendResponse(response::HTTP_CREATED, 'day added successfully');
     }


     public function updateDay(DayRequest $request ,FacilityDay $day)
     {
         // allow user to select multiple facilities
        //  $selectedFacilities = $request->input('selected_facilities');
         // Validate that at least one facility is selected
        //  $request->validate([
            //  'selected_facilities' => 'required|array|min:1', ]);
        //  foreach ($selectedFacilities as $facilityId) {
             $day ->update([
                 'date' => $request->date,
                //  'start_time' => $request->start_time,
                //  'end_time' => $request->end_time,
                 'day_id' => $request->day_id,
                //  'facility_id' => $facilityId,
                 'trip_id' => $request->trip_id,
             ]);
        //  }
     
         return $this->SendResponse(response::HTTP_CREATED, 'day updated successfully');
     }


     

     public function addFacilityInDay(FacilityInDayRequest $request)
{
    $day = FacilityInDay::create([
        'start_time' => $request->start_time,
        'end_time' => $request->end_time,
        'facility_id' => $request->facility_id,
        'facility_day_id' => $request->facility_day_id,
        'note' => $request->note,
    ]);
    $trip = $day->facilityDay->trip;
    $facilitiesInDay = FacilityInDay::where('facility_day_id', $day->facility_day_id)->get();
    $minAvailablePlaces = $facilitiesInDay->pluck('facility')->min('number_of_available_places'); 
    $trip->number_of_original_places = $minAvailablePlaces;
    $totalFacilityPrice = 0;
    foreach ($facilitiesInDay as $facilityInDay) {
        $totalFacilityPrice += $facilityInDay->facility->price_per_person;
    }
    $trip->price_per_one_new = $totalFacilityPrice;
    $trip->save();
   
    return $this->SendResponse(response::HTTP_CREATED, 'facility added successfully');
}


     public function updateFacilityInDay(FacilityInDayRequest $request,FacilityInDay $facilityInDay )
     {
        $facilityInDay ->update([
            
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'facility_id' =>$request-> facility_id,
            'facility_day_id' => $request->facility_day_id,
            'note' => $request->note,
        ]);
    
       
         return $this->SendResponse(response::HTTP_CREATED, 'facility updated successfully');
     }
     

}

