<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FacilityController extends Controller
{
    use ResponseTrait;

    public function getFacilitiesByCountry(Request $request)
    {
        $id = $request->vaildated()['id'];

        $records = Facility::where('country_id' , $id)->get();

        //resource
    }

    public function getFacilityDetails(Request $request)
    {
        $id = $request->validated()['id'];

        $record = Facility::where('id' , $id)->get();

        //resource
    }
}
