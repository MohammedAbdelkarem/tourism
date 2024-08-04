<?php

namespace App\Http\Controllers\Admin;

use App\Models\Day;
use App\Models\Trip;
use App\Models\User;
use App\Models\Admin;
use App\Models\Guide;
use App\Models\Country;
use Illuminate\Http\Request;
use App\Models\FacilityInDay;
use App\Traits\ResponseTrait;
use App\Models\AvailableGuide;
use App\Models\GuideTransaction;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\Admin\TripRequest;
use App\Http\Resources\Admin\DayResource;
use App\Http\Resources\Admin\TripResource;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\Admin\TripDetailsResource;
use App\Services\Notifications\AdminNotification;
use App\Services\Notifications\GuideNotificationService;
use App\Services\Notifications\UserNotificatoinService;





class TripsController extends Controller
{
    use ResponseTrait;

    private UserNotificatoinService $userNotificatoinService;
    private GuideNotificationService $guideNotificationService;

    public function __construct(UserNotificatoinService $userNotificatoinService , GuideNotificationService $guideNotificationService)
    {
        $this->userNotificatoinService = $userNotificatoinService;
        $this->guideNotificationService = $guideNotificationService;
    }
    public function addTrip(TripRequest $request)
    {
        $Trip = Trip::create([
            'name' => $request->name,
            'photo' =>photoPath($request->photo),
            'lat' => $request->lat,
            'long' => $request->long,
            'bio' => $request->bio,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'guide_backup_id' =>  null,
            'country_id' => $request->country_id,
        ]);

        // create a new available guide record
        $availableGuide = AvailableGuide::create([
            'trip_id' => $Trip->id,
            'guide_id' => $request->guide_id,
            'accept_trip' => null,
        ]);

        $guide_id = $availableGuide->guide_id;
        if($guide_id)
        {
            $this->guideNotificationService->SendNewTripNotification(
                $Trip->id,
                $guide_id
            );
        }

        return $this->SendResponse(response::HTTP_CREATED, 'trip added successfully', $Trip);
    }

    public function updateTrip(TripRequest $request, Trip $trip)
    {

        if ($trip->status !== 'pending') {
            return $this->SendResponse(response::HTTP_BAD_REQUEST, 'Trip status must be pending before updating');
        }
        $current_offer = $trip->offer_ratio;
        $trip->update([
            'name' => $request->name,
            'photo' => photoPath($request->photo),
            'lat' => $request->lat,
            'long' => $request->long,
            'bio' => $request->bio,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'country_id' => $request->country_id,
            'offer_ratio' => $request->offer_ratio,
        ]);

        // Check if the guide ID already exists for this trip
        $existingGuide = AvailableGuide::where('trip_id', $trip->id)
            ->where('guide_id', $request->guide_id)
            ->first();

        if (!$existingGuide) {
            // Delete all existing available guides for this trip
            AvailableGuide::where('trip_id', $trip->id)->delete();

            // Create a new available guide record
            $availableGuide = AvailableGuide::create([
                'trip_id' => $trip->id,
                'guide_id' => $request->guide_id,
                'accept_trip' => null,
            ]);
        }

        $guide_id = $availableGuide->guide_id;
        if($guide_id)
        {
            $this->guideNotificationService->SendNewTripNotification(
                $trip->id,
                $guide_id
            );
        }


        // Update trip prices
        $facilityDays = $trip->facilityDay;
        $totalFacilityPrice = 0;
        foreach ($facilityDays as $facilityDay) {
            $facilitiesInDay = FacilityInDay::where('facility_day_id', $facilityDay->id)->get();
            foreach ($facilitiesInDay as $facilityInDay) {
                $totalFacilityPrice += $facilityInDay->facility->price_per_person;
            }
        }
        // $guideBackup = $trip->Guides_backups;
        $availableGuide = AvailableGuide::where('trip_id', $trip->id)->first();
        $guideFeePerPerson = $availableGuide->guide->price_per_person_one_day;
        // $guideFeePerPerson = $guideBackup->price_per_person_one_day;
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


        $trip->update(['price_per_one_old' => $trip->price_per_one_new]);

        // Calculate new price_per_one_new
        $newPrice = $trip->price_per_one_old * $request->offer_ratio / 100;
        $trip->update(['price_per_one_new' => $newPrice]);


        $new_offer = $request->validated()['offer_ratio'];

        // dd($new_offer , $current_offer);
        if ($new_offer > $current_offer) {
            $this->userNotificatoinService->sendOfferNotification(
                $trip->id
            );
        }

        return $this->SendResponse(response::HTTP_CREATED, 'trip updated successfully');
    }


    public function deleteTrip( Trip $trip){{
        $trip->delete();
        
        return $this->SendResponse(response::HTTP_OK, 'trip deleted successfully');
    }

    }


public function getTripsByType($status)
{
    $trips = Trip::OfStatus($status)->get();
    if ($trips->isEmpty()) {
        return $this->SendResponse(response::HTTP_NOT_FOUND, 'No trips found');
    }
    $data = TripResource::collection($trips);
    return $this->SendResponse(response::HTTP_OK, "$status trips retrieved successfully", $trips);
}
 

  


    public function getTrips()
    {

        $trips = Trip::all();
        $data = TripResource::collection($trips);

        return $this->SendResponse(response::HTTP_OK, 'trips retrieved successfully', $data);
    }



    public function getTripDetails(string $id)
    {

        $trip = Trip::query()
            ->where('id', $id)
            ->get();


        $data = TripDetailsResource::collection($trip);
        return $this->SendResponse(response::HTTP_OK, 'trip details retrieved successfully',  $data);
    }

    public function activeTrip(string $id)
    {
        $trip = Trip::find($id);
        if ($trip) {
            $availableGuide = AvailableGuide::where('trip_id', $id)->first();
            if ($availableGuide && $availableGuide->accept_trip === 'accepted') {
                $trip->status = 'active';
                $trip->save();
                return $this->SendResponse(response::HTTP_OK, 'Trip activated successfully');
            } else {
                return $this->SendResponse(response::HTTP_BAD_REQUEST, 'Guide has not accepted the trip');
            }
        } else {
            return $this->SendResponse(response::HTTP_NOT_FOUND, 'Trip not found');
        }
    }

    public function inProgressTrip(string $id)
    {
        $trip = Trip::find($id);

        $trip->status = 'in_progress';
        $trip->save();
        return $this->SendResponse(response::HTTP_OK, 'Trip updated to in progress');
    }

// public function finishTrip(string $id)
// {
//     $trip = Trip::find($id);

//     $trip->status = 'finished';
//     $trip->save();
//     return $this->SendResponse(response::HTTP_OK, 'Trip marked as finished');
// }

    public function finishTrip(string $id)
    {
        $trip = Trip::find($id);

        if (!$trip) {
            return $this->SendResponse(response::HTTP_NOT_FOUND, 'Trip not found');
        }
        if ($trip->status == 'finished') {
            return $this->SendResponse(response::HTTP_BAD_REQUEST, 'Trip is already finished');
        }

        $trip->status = 'finished';
        $trip->save();

        // calculate all facility price 


        $facilityDays = $trip->facilityDay;
        $totalFacilityPrice = 0;
        foreach ($facilityDays as $facilityDay) {
            $facilitiesInDay = FacilityInDay::where('facility_day_id', $facilityDay->id)->get();
            foreach ($facilitiesInDay as $facilityInDay) {
                $totalFacilityPrice += $facilityInDay->facility->price_per_person;
            }
        }

        // Get total trip price from trip table
        $totalTripPrice = $trip->total_price;

        // Calculate facility profits
        $facilityDays = $trip->facilityDay;
        $facilityProfit = 0;
        foreach ($facilityDays as $facilityDay) {
            $facilitiesInDay = FacilityInDay::where('facility_day_id', $facilityDay->id)->get();
            foreach ($facilitiesInDay as $facilityInDay) {
                $facility = $facilityInDay->facility;
                $facilityProfit += $facilityInDay->facility->price_per_person * $trip->number_of_filled_places;
                $facility->profits += $facilityInDay->facility->price_per_person * $trip->number_of_filled_places;
                $facility->save();
            }
        }


        // Calculate guide profits
        $availableGuide = AvailableGuide::where('trip_id', $trip->id)->first();
        $guide = $availableGuide->guide;
        $guideBackup = $trip->Guides_backups;
        $guideFeePerPerson = $availableGuide->guide->price_per_person_one_day;
        $numDays = $trip->facilityDay->count();
        $totalGuideFeePerDay = $guideFeePerPerson * $numDays;
        $totalGuideFee = $totalGuideFeePerDay * $trip->number_of_filled_places;

        // Add the guide fee to the guide's wallet
        $guideBackup->wallet += $totalGuideFee;
        $guideBackup->save();

        // Create a new guide transaction record
        GuideTransaction::create([
            'guide_id' => $guide->id,
            'amount' => $totalGuideFee,
            'wallet' =>  $guideBackup->wallet,
            'date' => now(),
        ]);

        // add  totalGuideFee to totalFacilityPrice to calculate total price without admin profits
        $totalprice = $totalGuideFee + $totalFacilityPrice * $trip->number_of_filled_places;
        // Calculate admin profits
        $adminRatio = Cache::get('admin_ratio');
        $adminProfit = $totalprice * ($adminRatio / 100);

        // Get the super admin
        $superAdmin = Admin::where('role', 'super_admin')->first();

        // Add the admin profit to the super admin's wallet
        $superAdmin->wallet += $adminProfit;
        $superAdmin->save();


    // Send notification to super admin
    $adminNotification = new AdminNotification();
    $adminNotification->sendAdminWalletNotification($trip, $adminProfit);



        // To test the validity of mathematical calculations
        //  $totalTripPrice -= $facilityProfit;
        // $totalTripPrice -= $totalGuideFee;
        // $totalTripPrice -= $adminProfit;
        // $trip->total_price=$totalTripPrice;
        $trip->save();

        $this->guideNotificationService->SendWalletNotification(
            $guideBackup->wallet,
            $trip->id,
            $guide->id
        );


        return $this->SendResponse(response::HTTP_OK, 'Trip marked as finished');
    }



public function getDays()
{
    $days = Day::all();
    $data = DayResource::collection($days);
    return $this->SendResponse(response::HTTP_OK, 'Days retrieved successfully', $data);
}
public function getcountries()
{
    $countries = Country::all();
    $data = DayResource::collection($countries);
    return $this->SendResponse(response::HTTP_OK, 'countries retrieved successfully', $data);
}


public function search(Request $request)
    {
        $field = $request->field;
        $results = Trip::
        where('name', 'LIKE', "%$field%")
        ->orWhere('price_per_one_new', 'LIKE', "%$field%")
        ->orWhere('bio', 'LIKE', "%$field%")
        ->get();
        $users = User::where('name', 'LIKE', "%$field%")->get();

        $guides = Guide::where('name', 'LIKE', "%$field%")
        ->orWhere('unique_id', 'LIKE', "%$field%")
        ->get();
        $mergedResults = $results->merge($users)->merge($guides);
        if($mergedResults->isEmpty())
        {
            return $this->SendResponse(response::HTTP_OK  , 'no results');
        }
        return $this->SendResponse(response::HTTP_OK , 'results retrieved with success' , $mergedResults);
    }

}
