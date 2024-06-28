<?php

namespace App\Http\Controllers\User;

use Carbon\Carbon;
use App\Models\Trip;
use App\Models\Reservatoin;
use App\Models\UsersBackup;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Requests\IdRequest;
use App\Http\Requests\User\AppointmentRequest;
use App\Http\Requests\User\ModifyAppointmentRequest;
use App\Http\Resources\User\AppointResource;
use App\Models\UserTransaction;
use Symfony\Component\HttpFoundation\Response;

class AppointmentController extends Controller
{
    use ResponseTrait;

    public function appointTrip(AppointmentRequest $request)
    {
        /**
         * number of places
         *
         * user_id
         * 
         * trip_id
         * 
         * calculations:
         * 
         * total price of the reservation = price_per_one_new * number of places
         * wallet of the user(from user backup table)
         * 
         * modifications:
         * 
         * trips:
         * total price += total price of the reservation
         * number of filled places += number of places
         * number of available places -= number of places
         * 
         * reservations:
         * data of the table:
         * user_id
         * trip_id
         * total price(calculated above)
         * number of places(exist above)
         * 
         * transactions:
         * data of the table:
         * 
         * wallet -= total price of the reservation
         * user_id
         * date
         * amount
         * type
         * reservation_id
         * 
         * 
         */

        $number_of_places = $request->validated()['number_of_places'];
        $trip_id = $request->validated()['trip_id'];
        $user_id = user_id();

        $price_per_one = Trip::where('id', $trip_id)->pluck('price_per_one_new')->first();
        $available_places = Trip::where('id', $trip_id)->pluck('number_of_available_places')->first();
        $current_wallet = UsersBackup::where('id', $user_id)->pluck('wallet')->first();

        $reservation_price = $price_per_one * $number_of_places;
        
        if($number_of_places > $available_places)
        {
            return $this->SendResponse(response::HTTP_UNPROCESSABLE_ENTITY , 'your number is more than the number of available places which is: '.$available_places);
        }
        if($reservation_price > $current_wallet)
        {
            return $this->SendResponse(response::HTTP_UNPROCESSABLE_ENTITY , 'your wallet is: '.$current_wallet.' which is less than the reservation price: '.$reservation_price.' you may charge your wallet or decrease the number of places');
        }
        $trip = Trip::find($trip_id);
        $trip->modify($reservation_price , 'total_price' , '+');
        $trip->modify($number_of_places , 'number_of_filled_places' , '+');
        $trip->modify($number_of_places , 'number_of_available_places' , '-');

        $data['user_id'] = $user_id;
        $data['trip_id'] = $trip_id;
        $data['total_price'] = $reservation_price;
        $data['number_of_places'] = $number_of_places;

        $reservation = Reservatoin::create($data);

        $user = UsersBackup::find($user_id);
        $user->modify($reservation_price , 'wallet' , '-');

        $new_wallet = $user->wallet;

        $data2['wallet'] = $new_wallet;
        $data2['user_id'] = $user_id;
        $data2['date'] = Carbon::now();;
        $data2['amount'] = $reservation_price;
        $data2['type'] = 'dis';
        $data2['reservation_id'] = $reservation->id;

        UserTransaction::create($data2);

        $details['$reservation_price'] = $reservation_price;
        $details['$number_of_places'] = $number_of_places;
        $details['$price_per_one'] = $price_per_one;
        $details['$current_wallet'] = $current_wallet;

        return $this->SendResponse(response::HTTP_CREATED , 'appointed with success' , $details);
         

    }
    public function unAppointTrip(IdRequest $request)
    {
        /**
         *
         * trips:
         * total_price -= reservatoion_price
         * number of filled places -= number of places
         * number of available places += number of places
         * 
         * transaction:
         * new record
         * 
         * wallet += total price of the reservation
         * user_id
         * trip_id
         * date
         * amount
         * type:add
         * reservation_id
         * 
         * reservation:delete
         */

        $reservation_id = $request->validated()['id'];

        $reservation = Reservatoin::find($reservation_id);

        $trip_id = $reservation->trip_id;
        $user_id = $reservation->user_id;
        $reservation_price = $reservation->total_price;
        $number_of_places = $reservation->number_of_places;

        $trip = Trip::find($trip_id);
        $trip->modify($reservation_price , 'total_price' , '-');
        $trip->modify($number_of_places , 'number_of_filled_places' , '-');
        $trip->modify($number_of_places , 'number_of_available_places' , '+');


        $user = UsersBackup::find($user_id);

        $old_wallet = $user->wallet;

        $user->modify($reservation_price , 'wallet' , '+');

        $new_wallet = $user->wallet;

        $data2['wallet'] = $new_wallet;
        $data2['user_id'] = $user_id;
        $data2['date'] = Carbon::now();;
        $data2['amount'] = $reservation_price;
        $data2['type'] = 'add';
        $data2['reservation_id'] = $reservation_id;

        UserTransaction::create($data2);

        Reservatoin::where('id' , $reservation_id)->delete();

        $details['price_per_one'] = $trip->price_per_one_new;
        $details['old_wallet'] = $old_wallet;
        $details['new_wallet'] = $new_wallet;

        return $this->SendResponse(response::HTTP_OK , 'reservation deleted with success' , $details);
    }
    public function modifyAppointment(ModifyAppointmentRequest $requerst)
    {

        /**
         * number of places
         * 
         * 
         * if > or < than the available places
         * 
         * if > or < as a price than the wallet
         * 
         * if > or < than the old number of places--> add or dis
         * 
         */
    }
    public function getMyTrips()
    {
        $records = Reservatoin::where('user_id' , user_id())->with('trip')->get();

        if($records->isEmpty())
        {
            return $this->SendResponse(response::HTTP_OK , 'you do not have reservations yet');
        }
        $records = AppointResource::collection($records);
        return $this->SendResponse(response::HTTP_OK , 'reservations retrieve with success' , $records);
    }
    
}
