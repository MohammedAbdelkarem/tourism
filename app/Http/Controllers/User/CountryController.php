<?php

namespace App\Http\Controllers\User;

use App\Models\Trip;
use App\Models\Country;
use App\Models\Facility;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use App\Http\Requests\IdRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\User\CountryResource;
use App\Http\Resources\User\HomeRecResource;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\User\FacilityListResource;
use App\Http\Resources\User\CountryDetailsResource;

class CountryController extends Controller
{
    use ResponseTrait;

    public function getCountries()
    {
        $records = Country::get();

        $records = CountryResource::collection($records);

        return $this->SendResponse(response::HTTP_OK , 'countries retrieved with success' , $records);
    }

    public function getCountryDetails(IdRequest $request)
    {
        $country_id = $request->validated()['id'];

        $userId = user_id();

        $data['country_details'] = Country::find($country_id);

        $data['country_details'] = new CountryDetailsResource($data['country_details']);

        $data['facilities'] = Facility::where('country_id' , $country_id)->get();

        $data['facilities'] = FacilityListResource::collection($data['facilities']);

        $data['trips'] = 

        Trip::
        Active()
        ->where('country_id' , $country_id)
        ->favourite()
        ->get();

        $data['trips'] = HomeRecResource::collection($data['trips']);

        return $this->SendResponse(response::HTTP_OK , 'country data retrived with success' , $data);

    }
}
