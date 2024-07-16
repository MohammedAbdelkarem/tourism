<?php

namespace App\Http\Controllers\Admin;

use App\Models\Trip;
use App\Models\Facility;
use App\Models\FacilityDay;
use Illuminate\Http\Request;
use App\Models\FacilityInDay;
use App\Traits\ResponseTrait;
use Ramsey\Uuid\Type\Integer;
use App\Models\AvailableGuide;
use App\Http\Controllers\Controller;
use PhpParser\Node\Expr\Cast\Double;
use App\Services\TripPriceCalculator;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\Admin\DayRequest;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\Admin\FacilityInDayRequest;

class DaysController extends Controller
{
     use ResponseTrait;
     



     
     public function addDay(DayRequest $request)
     {
             $day = FacilityDay::create([
                 'date' => $request->date,
                 'day_id' => $request->day_id,
                 'trip_id' => $request->trip_id,
             ]);
             $trip = $day->trip;
           // Update trip prices

           $tripPriceCalculator = new TripPriceCalculator();
           $trip->price_per_one_new = $tripPriceCalculator->calculateTripPrice($trip);
           $trip->save();

   
     
         return $this->SendResponse(response::HTTP_CREATED, 'day added successfully',$day);
     }


     public function updateDay(DayRequest $request ,FacilityDay $day)
     {
       
             $day ->update([
                 'date' => $request->date,
                 'day_id' => $request->day_id,
                 'trip_id' => $request->trip_id,
             ]);
        
             $trip = $day->trip;
             
             $tripPriceCalculator = new TripPriceCalculator();
             $trip->price_per_one_new = $tripPriceCalculator->calculateTripPrice($trip);
             $trip->save();
  
         return $this->SendResponse(response::HTTP_CREATED, 'day updated successfully');
     }


     

     public function addFacilityInDay(FacilityInDayRequest $request)
{
    $day = FacilityInDay::create([
        'start_time' => $request->start_time,
        'end_time' => $request->end_time,
        'facility_id' => $request->facility_id,
        'facility_day_id' => $request->facility_day_id,
        // 'note' => $request->note,
    ]);

    
    $trip = $day->facilityDay->trip;

    $tripPriceCalculator = new TripPriceCalculator();
    $trip->price_per_one_new = $tripPriceCalculator->calculateTripPrice($trip);
    $trip->number_of_original_places = $tripPriceCalculator->calculateNumberOfOriginalPlaces($trip);
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
            // 'note' => $request->note,
        ]);
    
    
// number_of_original_places
    $trip = $facilityInDay->facilityDay->trip;
    $tripPriceCalculator = new TripPriceCalculator();
    $trip->price_per_one_new = $tripPriceCalculator->calculateTripPrice($trip);
    $trip->number_of_original_places = $tripPriceCalculator->calculateNumberOfOriginalPlaces($trip);
    $trip->save();

         return $this->SendResponse(response::HTTP_CREATED, 'facility updated successfully');
     }
     



     public function deleteFacilityInDay(FacilityInDay $facilityInDay)
     {
         $facilityInDay->delete();
         
         $trip = $facilityInDay->facilityDay->trip;

         $tripPriceCalculator = new TripPriceCalculator();
         $trip->price_per_one_new = $tripPriceCalculator->calculateTripPrice($trip);
         $trip->number_of_original_places = $tripPriceCalculator->calculateNumberOfOriginalPlaces($trip)?? 0;
         $trip->save();


         return $this->SendResponse(response::HTTP_OK, 'facility in day deleted successfully');
     }






     public function deleteDay(FacilityDay $day)
     {
        $trip = $day->trip;
        $day->delete();
        

        $tripPriceCalculator = new TripPriceCalculator();
        $trip->price_per_one_new = $tripPriceCalculator->calculateTripPrice($trip)?? 0;
        $trip->number_of_original_places = $tripPriceCalculator->calculateNumberOfOriginalPlaces($trip)?? 0;
        $trip->save();

         return $this->SendResponse(response::HTTP_OK, 'day deleted successfully');
     }

}


