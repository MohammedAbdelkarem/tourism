<?php

namespace App\Http\Controllers\Admin;

use App\Models\Trip;
use App\Models\Guide;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use App\Models\AvailableGuide;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Date;
use App\Http\Requests\Admin\DateRequest;
use Symfony\Component\HttpFoundation\Response;

class GuidesController extends Controller
{
     use ResponseTrait;


public function getAvailableGuides(DateRequest $request)
{
    $startDate = Date::parse($request->validated()['start_date']);
    $endDate = Date::parse($request->validated()['end_date']);

    $availableGuides = Guide::available()->whereDoesntHave('availableGuide.trip', function ($query) use ($startDate, $endDate) {
        $query->where(function ($query) use ($startDate, $endDate) {
            $query->where('start_date', '<=', $endDate)
                  ->where('end_date', '>=', $startDate);
        });
    })->get();

    return $this->SendResponse(response::HTTP_OK, 'Available guides retrieved successfully', $availableGuides);
}                       


public function acceptedTrips(){
    $tripIds = AvailableGuide::acceptedTrips()->pluck('trip_id');
    $trips = Trip::whereIn('id', $tripIds)->get();
    return $this->SendResponse(response::HTTP_OK, 'accepted trips retrieved successfully', $trips);
} 
public function rejectedTrips(){
    $tripIds = AvailableGuide::rejectedTrips()->pluck('trip_id');
    $trips = Trip::whereIn('id', $tripIds)->get();
    return $this->SendResponse(response::HTTP_OK, 'rejected trips retrieved successfully', $trips);
} 
}
