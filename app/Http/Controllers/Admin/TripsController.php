<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\TripRequest;
use App\Models\Trip;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\Admin\TripResource;
use App\Http\Resources\Admin\TripDetailsResource;



class TripsController extends Controller
{
    use ResponseTrait;

    public function addTrip(TripRequest $request){
        $Trip = Trip::create([
            'name' => $request->name,
            'photo' => $request->photo,
            'lat' => $request->lat,
            'long' => $request->long,
            'bio' => $request->bio,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'guide_backup_id' => $request->guide_backup_id,
            'country_id' =>$request->country_id,
        ]);
        

        return $this->SendResponse(response::HTTP_CREATED, 'trip added successfully',$Trip);
        
    }

    public function updateTrip(TripRequest $request, Trip $trip){

        if ($trip->status !== 'pending') {
            return $this->SendResponse(response::HTTP_BAD_REQUEST, 'Trip status must be pending before updating');
        }
        $trip->update([
            'name' => $request->name,
            'photo' => $request->photo,
            'lat' => $request->lat,
            'long' => $request->long,
            'bio' => $request->bio,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'guide_backup_id' => $request->guide_backup_id,
            'country_id' =>$request->country_id,
            'offer_ratio' => $request->offer_ratio, 
        ]);
        $trip->update(['price_per_one_old' => $trip->price_per_one_new]);

    // Calculate new price_per_one_new
    $newPrice = $trip->price_per_one_old * $request->offer_ratio / 100;
    $trip->update(['price_per_one_new' => $newPrice]);


        return $this->SendResponse(response::HTTP_CREATED, 'trip updated successfully');

    }
    

    public function deleteTrip( Trip $trip){{
        $trip->delete();
        
        return $this->SendResponse(response::HTTP_OK, 'trip deleted successfully');
    }

    }

    public function getPinnedTrips(){
         $trips = Trip::where('status', 'pending')->get();
        if ($trips->isEmpty()) {
            return $this->SendResponse(response::HTTP_NOT_FOUND, 'No trips found');
        }
        $data = TripResource::collection($trips);
        return $this->SendResponse(response::HTTP_OK, 'pending trips retrieved successfully',$data);

    } //the trips that the admin did not make it active(able to book by the user)

    public function getRunningTrips(){
        $trips = Trip::where('status', 'active')->get();
        if ($trips->isEmpty()) {
            return $this->SendResponse(response::HTTP_NOT_FOUND, 'No trips found');
        }
        $data = TripResource::collection($trips);
        return $this->SendResponse(response::HTTP_OK, 'active trips retrieved successfully',$data);

    } //the trips that is running currently

    public function getFinishidTrips(){

        $trips = Trip::where('status', 'finished')->get();
        if ($trips->isEmpty()) {
            return $this->SendResponse(response::HTTP_NOT_FOUND, 'No trips found');
        }
        $data = TripResource::collection($trips);
        return $this->SendResponse(response::HTTP_OK, 'finished trips retrieved successfully',$data);

    } //the trips that has been finished


    public function getTrips()
    {

        $trips = Trip::all();
        $data = TripResource::collection($trips);
        
        return $this->SendResponse(response::HTTP_OK, 'trips retrieved successfully', $data);
    }



    public function getTripDetails(string $id){

        $trip = Trip::query()
        ->where('id', $id)
        ->get();

        
        $data = TripDetailsResource::collection($trip);
        return $this->SendResponse(response::HTTP_OK, 'trip details retrieved successfully',  $data);


        
    }
    public function activeTrip( string $id)
{
    $trip = Trip::find($id);

    $trip->status = 'active';
    $trip->save();
    return $this->SendResponse(response::HTTP_OK, 'Trip activated successfully' );
  
}

public function inProgressTrip(string $id)
{
    $trip = Trip::find($id);

    $trip->status = 'in_progress';
    $trip->save();
    return $this->SendResponse(response::HTTP_OK, 'Trip updated to in progress');
}

public function finishTrip(string $id)
{
    $trip = Trip::find($id);

    $trip->status = 'finished';
    $trip->save();
    return $this->SendResponse(response::HTTP_OK, 'Trip marked as finished');
}

}
