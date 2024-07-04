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
use Illuminate\Support\Facades\Cache;

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
        $facilityDays = $trip->facilityDay;
        $totalFacilityPrice = 0;
        foreach ($facilityDays as $facilityDay) {
            $facilitiesInDay = FacilityInDay::where('facility_day_id', $facilityDay->id)->get();
            foreach ($facilitiesInDay as $facilityInDay) {
                $totalFacilityPrice += $facilityInDay->facility->price_per_person;
            }
        }
        $guideBackup = $trip->Guides_backups;
        $guideFeePerPerson = $guideBackup->price_per_person_one_day;
        $numDays = $trip->facilityDay->count();
        $totalGuideFee = $guideFeePerPerson * $numDays;
          // Calculate the total trip price
        $totalTripPrice = $totalFacilityPrice + $totalGuideFee;

         // Retrieve the admin ratio from the cache
        $adminRatio = Cache::get('admin_ratio');
 
         // Calculate the admin fee
        $adminFee = $totalTripPrice * ($adminRatio / 100);
 
         // Add the admin fee to the total trip price
        $trip->price_per_one_new = $totalTripPrice + $adminFee;
 
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
             // Update trip prices
          $facilityDays = $trip->facilityDay;
          $totalFacilityPrice = 0;
          foreach ($facilityDays as $facilityDay) {
              $facilitiesInDay = FacilityInDay::where('facility_day_id', $facilityDay->id)->get();
              foreach ($facilitiesInDay as $facilityInDay) {
                  $totalFacilityPrice += $facilityInDay->facility->price_per_person;
              }
          }
          $guideBackup = $trip->Guides_backups;
          $guideFeePerPerson = $guideBackup->price_per_person_one_day;
          $numDays = $trip->facilityDay->count();
          $totalGuideFee = $guideFeePerPerson * $numDays;
            // Calculate the total trip price
          $totalTripPrice = $totalFacilityPrice + $totalGuideFee;
  
           // Retrieve the admin ratio from the cache
          $adminRatio = Cache::get('admin_ratio');
   
           // Calculate the admin fee
          $adminFee = $totalTripPrice * ($adminRatio / 100);
   
           // Add the admin fee to the total trip price
          $trip->price_per_one_new = $totalTripPrice + $adminFee;
   
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
        'note' => $request->note,
    ]);

// number_of_original_places
    $trip = $day->facilityDay->trip;
    $facilitiesInDay = FacilityInDay::where('facility_day_id', $day->facility_day_id)->get();
    $minAvailablePlaces = $facilitiesInDay->pluck('facility')->min('number_of_available_places'); 
    $trip->number_of_original_places = $minAvailablePlaces;
    

// total Facilities price in all days 

    $trip = $day->facilityDay->trip;
    $facilityDays = $trip->facilityDay;
    $totalFacilityPrice = 0;

    foreach ($facilityDays as $facilityDay) {
        $facilitiesInDay = FacilityInDay::where('facility_day_id', $facilityDay->id)->get();
        foreach ($facilitiesInDay as $facilityInDay) {
            $totalFacilityPrice += $facilityInDay->facility->price_per_person;
        }
    }

// total guide fee
    $guideBackup = $trip->Guides_backups;
    $guideFeePerPerson = $guideBackup->price_per_person_one_day;

    // Count the number of days in the trip
    $numDays = $trip->facilityDay->count();

    // Calculate the total guide fee
    $totalGuideFee = $guideFeePerPerson * $numDays;


     // Calculate the total trip price
     $totalTripPrice = $totalFacilityPrice + $totalGuideFee;

     // Retrieve the admin ratio from the cache
     $adminRatio = Cache::get('admin_ratio');
 
     // Calculate the admin fee
     $adminFee = $totalTripPrice * ($adminRatio / 100);
 
     // Add the admin fee to the total trip price
     $trip->price_per_one_new = $totalTripPrice + $adminFee;
 
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
    
    
// number_of_original_places
$trip = $facilityInDay->facilityDay->trip;
$facilitiesInDay = FacilityInDay::where('facility_day_id',  $facilityInDay->facility_day_id)->get();
$minAvailablePlaces = $facilitiesInDay->pluck('facility')->min('number_of_available_places'); 
$trip->number_of_original_places = $minAvailablePlaces;


// total Facilities price in all days 

$trip =  $facilityInDay->facilityDay->trip;
$facilityDays = $trip->facilityDay;
$totalFacilityPrice = 0;

foreach ($facilityDays as $facilityDay) {
    $facilitiesInDay = FacilityInDay::where('facility_day_id', $facilityDay->id)->get();
    foreach ($facilitiesInDay as $facilityInDay) {
        $totalFacilityPrice += $facilityInDay->facility->price_per_person;
    }
}

// total guide fee
$guideBackup = $trip->Guides_backups;
$guideFeePerPerson = $guideBackup->price_per_person_one_day;

// Count the number of days in the trip
$numDays = $trip->facilityDay->count();

// Calculate the total guide fee
$totalGuideFee = $guideFeePerPerson * $numDays;

  // Calculate the total trip price
  $totalTripPrice = $totalFacilityPrice + $totalGuideFee;

  // Retrieve the admin ratio from the cache
  $adminRatio = Cache::get('admin_ratio');

  // Calculate the admin fee
  $adminFee = $totalTripPrice * ($adminRatio / 100);

  // Add the admin fee to the total trip price
  $trip->price_per_one_new = $totalTripPrice + $adminFee;

  $trip->save();
  
         return $this->SendResponse(response::HTTP_CREATED, 'facility updated successfully');
     }
     



     public function deleteFacilityInDay(FacilityInDay $facilityInDay)
     {
         $facilityInDay->delete();
         // Update trip prices
         $trip = $facilityInDay->facilityDay->trip;
         $facilityDays = $trip->facilityDay;
         $totalFacilityPrice = 0;
         foreach ($facilityDays as $facilityDay) {
             $facilitiesInDay = FacilityInDay::where('facility_day_id', $facilityDay->id)->get();
             foreach ($facilitiesInDay as $facilityInDay) {
                 $totalFacilityPrice += $facilityInDay->facility->price_per_person;
             }
         }
         $guideBackup = $trip->Guides_backups;
         $guideFeePerPerson = $guideBackup->price_per_person_one_day;
         $numDays = $trip->facilityDay->count();
         $totalGuideFee = $guideFeePerPerson * $numDays;
        
           // Calculate the total trip price
     $totalTripPrice = $totalFacilityPrice + $totalGuideFee;

     // Retrieve the admin ratio from the cache
     $adminRatio = Cache::get('admin_ratio');
 
     // Calculate the admin fee
     $adminFee = $totalTripPrice * ($adminRatio / 100);
 
     // Add the admin fee to the total trip price
     $trip->price_per_one_new = $totalTripPrice + $adminFee;
 
     $trip->save();
         return $this->SendResponse(response::HTTP_OK, 'facility in day deleted successfully');
     }






     public function deleteDay(FacilityDay $day)
     {
        $trip = $day->trip;
        $day->delete();
        
        // Update trip prices
        $facilityDays = $trip->facilityDay;
        $totalFacilityPrice = 0;
        foreach ($facilityDays as $facilityDay) {
            $facilitiesInDay = FacilityInDay::where('facility_day_id', $facilityDay->id)->get();
            foreach ($facilitiesInDay as $facilityInDay) {
                $totalFacilityPrice += $facilityInDay->facility->price_per_person;
            }
        }
        $guideBackup = $trip->Guides_backups;
        $guideFeePerPerson = $guideBackup->price_per_person_one_day;
        $numDays = $trip->facilityDay->count();
        $totalGuideFee = $guideFeePerPerson * $numDays;
          // Calculate the total trip price
        $totalTripPrice = $totalFacilityPrice + $totalGuideFee;

         // Retrieve the admin ratio from the cache
        $adminRatio = Cache::get('admin_ratio');
 
         // Calculate the admin fee
        $adminFee = $totalTripPrice * ($adminRatio / 100);
 
         // Add the admin fee to the total trip price
        $trip->price_per_one_new = $totalTripPrice + $adminFee;
 
     $trip->save();
         return $this->SendResponse(response::HTTP_OK, 'day deleted successfully');
     }

}


