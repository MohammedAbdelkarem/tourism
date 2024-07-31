<?php

namespace App\Http\Controllers\User;

use Carbon\Carbon;
use App\Models\Trip;
use App\Models\User;
use App\Models\FacilityDay;
use App\Models\Reservatoin;
use App\Models\UsersBackup;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use App\Models\UserTransaction;
use App\Http\Requests\IdRequest;
use App\Http\Controllers\Controller;
use App\Services\AppointmentService;
use App\Http\Resources\User\DayResource;
use App\Http\Resources\User\AppointResource;
use App\Http\Requests\User\AppointmentRequest;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\User\ReservationResource;
use App\Http\Resources\User\TransactionResource;
use App\Services\Notifications\AdminNotification;
use App\Http\Requests\User\ModifyAppointmentRequest;
use App\Services\Notifications\UserNotificatoinService;

class AppointmentController extends Controller
{
    use ResponseTrait;

    private AppointmentService $appointmentService;
    private UserNotificatoinService $userNotificatoinService;
 
    public function __construct(AppointmentService $appointmentService , UserNotificatoinService $userNotificatoinService)
    {
        $this->appointmentService = $appointmentService;
        $this->userNotificatoinService = $userNotificatoinService;
    } 

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
        $this->appointmentService->addTripReservation($trip_id , $reservation_price , $number_of_places);

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
        $details['$current_wallet'] = $new_wallet;




        $trip = Trip::find($trip_id);
        $filled_places = $trip->number_of_filled_places;
        $original_places = $trip->number_of_original_places;
    
        
    
        if ($filled_places == $original_places || $filled_places == ($original_places / 2)) {
            $adminNotification = new AdminNotification();
            $adminNotification->sendNotificationIfTripFilledPlacesUpdate($trip, $filled_places);
        }
    

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
        $this->appointmentService->disTripReservation($trip_id , $reservation_price , $number_of_places);


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
         * reservation id
         * 
         * 
         * if > or < than the available places
         * 
         * if > or < as a price than the wallet
         * 
         * if > or < than the old number of places--> add or dis
         * 
         * values to add or dis:
         * 
         * 
         * the diffPlaces(number of places to add or dis) = abs(old places - new places)
         * the diffPrice (reservatoin price to add or dis) = diffplaces * price per one new
         * 
         * modificatoins:
         * 
         * trips:
         * total price(add or dis the diffPrice)
         * number of available places(add or dis the diffplace)
         * number of filled places(add or dis the diffplace)
         * 
         * reservaion:
         * total price of the reservation(add or dis form it the diffprice)
         * number of places(add or dis the diffplaces)
         * 
         * userbackup:
         * wallet: add or dis the difprice
         * 
         * reservatoin:
         * wallet = userbackup wallet
         * user id
         * date
         * amount = difPrice
         * type:add or dis
         * reservatoin id
         * 
         * 
         * 
         */

        //number of places = old number + (new number which is + or -)
        $new_number_of_places = $requerst->validated()['number_of_places'];
        $reservation_id = $requerst->validated()['reservation_id'];
        
        $reservation = Reservatoin::find($reservation_id);
        $trip_id = $reservation->trip_id;
        //the old price of the reservation
        $original_price = $reservation->total_price;
        //the old number of places
        $original_places = $reservation->number_of_places;

        $trip = Trip::find($trip_id);
        $available_places = $trip->number_of_available_places;
        $price_per_one = $trip->price_per_one_new;

        if($new_number_of_places == $original_places)
        {
            return $this->SendResponse(response::HTTP_OK , 'nothing has changed');
        }
        
        if($new_number_of_places > $available_places + $original_places)
        {
            return $this->SendResponse(response::HTTP_UNPROCESSABLE_ENTITY , 'your number is more than the number of available places which is: '.$available_places);
        }

        $user = UsersBackup::find(user_id());
        $current_wallet = $user->wallet;
        $diffPlaces = abs($new_number_of_places - $original_places);
        $diffPrice = $diffPlaces * $price_per_one;

        if($original_places > $new_number_of_places)
        {
            $this->appointmentService->disTripReservation($trip_id , $diffPrice , $diffPlaces);

            $this->appointmentService->disFromReservation($diffPrice , $diffPlaces , $reservation_id);

            $user->modify($diffPrice , 'wallet' , '+');

            $data['wallet'] = $user->wallet;
            $data['user_id'] = $user->id;
            $data['date'] = Carbon::now();
            $data['amount'] = $diffPrice;
            $data['type'] = 'add';
            $data['reservation_id'] = $reservation_id;

            UserTransaction::create($data);

            return $this->SendResponse(response::HTTP_CREATED , $diffPlaces.' places returned back with success' , $data);
        }
        if($diffPrice > $current_wallet)
        {
            return $this->SendResponse(response::HTTP_UNPROCESSABLE_ENTITY , 'the additional cost is: '.$diffPrice.' which is more than your wallet: '.$current_wallet.' , you may charge your wallet or decrease the number of places');
        }
        $this->appointmentService->addTripReservation($trip_id , $diffPrice , $diffPlaces);

        $this->appointmentService->addToReservation($diffPrice , $diffPlaces , $reservation_id);

        $user->modify($diffPrice , 'wallet' , '-');

        $data['wallet'] = $user->wallet;
        $data['user_id'] = $user->id;
        $data['date'] = Carbon::now();
        $data['amount'] = $diffPrice;
        $data['type'] = 'dis';
        $data['reservation_id'] = $reservation_id;

        UserTransaction::create($data);

        return $this->SendResponse(response::HTTP_CREATED , $diffPlaces.' new places added with success' , $data);

    }

    public function getTransactions()
    {
        $data = UserTransaction::where('user_id' ,user_id())->get();

        $data = TransactionResource::collection($data);

        return $this->SendResponse(response::HTTP_OK , 'transactions retrieved with success' , $data);
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

    public function getReservationDetails(IdRequest $request)
    {
        $id = $request->validated()['id'];

        $record = Reservatoin::find($id);

        $record = new ReservationResource($record);

        return $this->SendResponse(response::HTTP_OK ,'reservation details retrieved with success' , $record);
    }

    public function getDayDetails(IdRequest $request)
    {
        $day_id = $request->validated()['id'];

        $day = FacilityDay::find($day_id);

        $day->load(['facilityInDay' => function ($query) use ($day_id) {
            $query->where('facility_day_id', $day_id);
        }]);

        $trip_id = $day->trip_id;
        $day = new DayResource($day);

        $in_reserve = Reservatoin::where('user_id' , user_id())->where('trip_id' , $trip_id)->get();
        $data['day_details'] = $day;
        $data['in_reserve'] = $in_reserve->count();


        return $this->SendResponse(response::HTTP_OK , 'day details retrieved with success' , $data);
    }

    public function test()
    {
        $this->userNotificatoinService->SendNoteNotification(1);

        return $this->SendResponse(response::HTTP_OK , 'succcess');
    }
    
}
