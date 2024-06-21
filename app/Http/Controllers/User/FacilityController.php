<?php

namespace App\Http\Controllers\User;

use App\Models\Facility;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use App\Http\Requests\IdRequest;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\FacilityDetailsResource;
use App\Http\Resources\User\FacilityDetailsResource as UserFacilityDetailsResource;
use App\Http\Resources\User\FacilityListResource;
use Symfony\Component\HttpFoundation\Response;

class FacilityController extends Controller
{
    use ResponseTrait;

    public function getFacilitiesByCountry(IdRequest $request)
    {
        $id = $request->validated()['id'];

        $records = Facility::where('country_id' , $id)->get();

        $records = FacilityListResource::collection($records);

        return $this->SendResponse(response::HTTP_OK , 'facility list retrieved with success' , $records);
    }

    public function getFacilityDetails(IdRequest $request)
    {
        $id = $request->validated()['id'];

        $records = Facility::findOrFail($id);

        // $records = new UserFacilityDetailsResource($records);

        return $this->SendResponse(response::HTTP_OK , 'facility details retrieved with success' , $records);
    }
}
