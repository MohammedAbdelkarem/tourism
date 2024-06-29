<?php
namespace App\Services;

use App\Models\Reservatoin;
use App\Models\Trip;

 
class AppointmentService
{
    public function addTripReservation($trip_id , $reservation_price , $number_of_places)
    {
        $trip = Trip::find($trip_id);
        $trip->modify($reservation_price , 'total_price' , '+');
        $trip->modify($number_of_places , 'number_of_filled_places' , '+');
        $trip->modify($number_of_places , 'number_of_available_places' , '-');
    }
    public function disTripReservation($trip_id , $reservation_price , $number_of_places)
    {
        $trip = Trip::find($trip_id);
        $trip->modify($reservation_price , 'total_price' , '-');
        $trip->modify($number_of_places , 'number_of_filled_places' , '-');
        $trip->modify($number_of_places , 'number_of_available_places' , '+');
    }

    public function addToReservation($diffPrice , $diffPlaces , $reservation_id)
    {
        $reservation = Reservatoin::find($reservation_id);
        $reservation->modify($diffPrice , 'total_price' , '+');
        $reservation->modify($diffPlaces , 'number_of_places' , '+');
    }
    public function disFromReservation($diffPrice , $diffPlaces , $reservation_id)
    {
        $reservation = Reservatoin::find($reservation_id);
        $reservation->modify($diffPrice , 'total_price' , '-');
        $reservation->modify($diffPlaces , 'number_of_places' , '-');
    }
}