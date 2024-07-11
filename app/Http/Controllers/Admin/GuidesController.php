<?php

namespace App\Http\Controllers\Admin;

use App\Models\Trip;
use App\Models\Guide;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use App\Models\AvailableGuide;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Date;
use App\Models\Guides_backups;
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


public function update_can_change_unique_id($guideId)
{
    $guide = Guide::find($guideId);

    if ($guide) {
        $guideEmail = $guide->email;
        $guideBackups =Guides_backups::where('email', $guideEmail)->get();

        if ($guide->can_change_unique_id === 'able') {
            $guide->can_change_unique_id = 'unable';
        } else {
            $guide->can_change_unique_id = 'able';
        }
        $guide->save();

        foreach ($guideBackups as $guideBackup) {
            if ($guideBackup->can_change_unique_id === 'able') {
                $guideBackup->can_change_unique_id = 'unable';
            } else {
                $guideBackup->can_change_unique_id = 'able';
            }
            $guideBackup->save();
        }
    }

    return $this->SendResponse(response::HTTP_OK, 'successfully');
    }



        public function update_accept_by_admin($guideId){
            $guide = Guide::find($guideId);
            $guideEmail = $guide->email;
            $guideBackups =Guides_backups::where('email', $guideEmail)->get();
      
        if ($guide->accept_by_admin === 'accepted') {
            $guide->accept_by_admin = 'rejected';
        } else {
            $guide->accept_by_admin = 'accepted';
        }
        $guide->save();

        foreach ($guideBackups as $guideBackup) {
            if ($guideBackup->accept_by_admin === 'accepted') {
                $guideBackup->accept_by_admin = 'rejected';
            } else {
                $guideBackup->accept_by_admin = 'accepted';
            }
            $guideBackup->save();
        }
        return $this->SendResponse(response::HTTP_OK, 'successfully');
}






}
