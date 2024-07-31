<?php

namespace App\Http\Controllers\Guide;

use Carbon\Carbon;
use App\Models\Trip;
use App\Models\Guide;
use App\Models\FacilityDay;
use App\Models\Reservatoin;
use Illuminate\Http\Request;
use App\Models\FacilityInDay;
use App\Traits\ResponseTrait;
use App\Models\AvailableGuide;
use App\Http\Requests\IdRequest;
use App\Models\GuideTransaction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Guide\NoteRequest;
use App\Http\Resources\User\DayResource;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\Guide\PendingStatusRequest;
use App\Services\Notifications\AdminNotification;

class GuideController extends Controller
{
    use ResponseTrait;

    public function home()
    {
        $data['accepted_by_admin'] = Guide::where('id' , guide_id())->pluck('accept_by_admin')->first();

        $data['pending'] = AvailableGuide::nullTrips()->where('guide_id' , guide_id())->get();

        $now = Carbon::now();

        $data['coming_soon'] = AvailableGuide::where('guide_id', guide_id())->acceptedTrips()
        ->whereHas('trip', function ($query) use ($now) {
            $query->where('start_date', '>', $now);
        })
        ->get();

        $data['in_progress'] = AvailableGuide::where('guide_id', guide_id())->acceptedTrips()
        ->whereHas('trip', function ($query) use ($now) {
            $query->where('start_date', '<=', $now)->where('end_date', '>', $now);
        })
        ->get();

        $data['history'] = AvailableGuide::where('guide_id', guide_id())->acceptedTrips()
        ->whereHas('trip', function ($query) use ($now) {
            $query->where('end_date', '<', $now);
        })
        ->get();

        return $this->SendResponse(response::HTTP_OK , 'home data retrieved with success' , $data);
    }
    public function getDays(IdRequest $request)
    {
        $trip_id = $request->validated()['id'];

        $days = FacilityDay::where('trip_id' , $trip_id)->with('facilityInDay')->get();

        return $this->SendResponse(response::HTTP_CREATED , 'days retrieved with success' , $days);
    }
    public function getDayDetails(IdRequest $request)
    {
        $day_id = $request->validated()['id'];

        $day = FacilityDay::find($day_id);

        $day->load(['facilityInDay' => function ($query) use ($day_id) {
            $query->where('facility_day_id', $day_id);
        }]);

        $day = new DayResource($day);

        $data['day_details'] = $day;



        return $this->SendResponse(response::HTTP_OK , 'day details retrieved with success' , $data);

    }
    public function transactions()
    {
        $data = GuideTransaction::where('guide_id' , 222)->get();

        if($data->isEmpty())
        {
            return $this->SendResponse(response::HTTP_OK , 'there is no transactions yet');
        }

        return $this->SendResponse(response::HTTP_OK , 'guide tranactoins retrieved with success' , $data);
    }
    public function addNote(NoteRequest $request)
    {
        $id = $request->validated()['id'];

        $note = $request->validated()['note'];

        FacilityInDay::where('id' , $id)->update([
            'note' => $note
        ]);

        return $this->SendResponse(response::HTTP_OK , 'note added or updated with success');
    }
    public function modifyPending(PendingStatusRequest $request)
    {
        $id = $request->validated()['id'];
        $status = $request->validated()['status'];

        $record = AvailableGuide::find($id);

        $trip_id = $record->trip_id;

        if($status == 'accepted')
        {
            Trip::where('id' , $trip_id)->update([
                'guide_backup_id' => guide_id()
            ]);
        }
        AvailableGuide::where('id' , $id)->update([
            'accept_trip' => $status
        ]);

        $adminNotification = new AdminNotification();
        $adminNotification->sendNotificationIfTripAcceptedByGuide($trip_id);
        return $this->SendResponse(response::HTTP_OK , 'done with success');
    }
}
