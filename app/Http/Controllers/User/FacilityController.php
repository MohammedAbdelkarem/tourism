<?php

namespace App\Http\Controllers\User;

use App\Models\Facility;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use App\Http\Requests\IdRequest;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\RateRequest;
use App\Http\Requests\User\UpdateRateRequest;
use App\Http\Resources\Admin\FacilityDetailsResource;
use App\Http\Resources\User\FacilityDetailsResource as UserFacilityDetailsResource;
use App\Http\Resources\User\FacilityListResource;
use App\Models\FacilityRate;
use App\Models\TripRate;
use Symfony\Component\HttpFoundation\Response;

class FacilityController extends Controller
{
    use ResponseTrait;


    public function getFacilityDetails(IdRequest $request)
    {
        $id = $request->validated()['id'];

        $records = Facility::findOrFail($id);

        $records = new UserFacilityDetailsResource($records);

        return $this->SendResponse(response::HTTP_OK , 'facility details retrieved with success' , $records);
    }

    public function addRate(RateRequest $request)
    {
        $data['facility_id'] = $request->facility_id;
        $data['rate'] = $request->rate;
        $data['user_backup_id'] = user_id();

        FacilityRate::create($data);

        return $this->SendResponse(response::HTTP_CREATED , 'rate added with success');
    }
    public function deleteRate(IdRequest $request)
    {
        $id = $request->validated()['id'];

        FacilityRate::where('id' , $id)->delete();

        return $this->SendResponse(response::HTTP_OK , 'rate removed with success');
    }
    public function modifyRate(UpdateRateRequest $request)
    {
        $id = $request->validated()['id'];
        $rate = $request->validated()['rate'];

        FacilityRate::where('id' , $id)->update([
            'rate'=> $rate
        ]);

        return $this->SendResponse(response::HTTP_OK , 'rate updated with success');
    }
}
