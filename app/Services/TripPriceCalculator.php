<?php
namespace App\Services;

use App\Models\Trip;
use App\Models\FacilityDay;
use App\Models\FacilityInDay;
use App\Models\AvailableGuide;
use Illuminate\Support\Facades\Cache;

class TripPriceCalculator
{
    public function calculateTripPrice(Trip $trip)
    {
        $facilityDays = $trip->facilityDay;
        $totalFacilityPrice = 0;
        foreach ($facilityDays as $facilityDay) {
            $facilitiesInDay = FacilityInDay::where('facility_day_id', $facilityDay->id)->get();
            foreach ($facilitiesInDay as $facilityInDay) {
                $totalFacilityPrice += $facilityInDay->facility->price_per_person;
            }
        }

        $availableGuide = AvailableGuide::where('trip_id', $trip->id)->first();
        $guideFeePerPerson = $availableGuide->guide->price_per_person_one_day;
        $numDays = $trip->facilityDay->count();
        $totalGuideFee = $guideFeePerPerson * $numDays;
         // Calculate the total trip price
        $totalTripPrice = $totalFacilityPrice + $totalGuideFee;
        // Retrieve the admin ratio from the cache
        $adminRatio = Cache::get('admin_ratio');
        $adminFee = $totalTripPrice * ($adminRatio / 100);

        return $totalTripPrice + $adminFee;
    }

    public function calculateNumberOfOriginalPlaces(Trip $trip)
    {
        $facilityDays = $trip->facilityDay;
        $minAvailablePlaces = [];

        foreach ($facilityDays as $facilityDay) {
            $facilitiesInDay = FacilityInDay::where('facility_day_id', $facilityDay->id)->get();
            $availablePlaces = $facilitiesInDay->pluck('facility')->min('number_of_available_places');
            $minAvailablePlaces[] = $availablePlaces;
        }

        return collect($minAvailablePlaces)->min();
    }
}