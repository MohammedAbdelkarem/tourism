<?php

namespace App\Http\Controllers;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Facility;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
// use App\Http\Resources\Dashboard\FacilityResource ;
use App\Http\Resources\Dashboard\FacilityResource as DashboardFacilityResource;
use Illuminate\Support\Facades\Validator;
class FacilitesController extends Controller
{
    use ResponseTrait;

    public function __construct()
        {
           // $this->middleware('auth:admin', ['except' => ['store']]);
        }
    
    /**
     * Display a listing of the resource.
     */
    public function Hotel()
    {
        {
            $hotels = Facility::where('type', 'hotel')->get();
            if ($hotels->isEmpty()) {
                return $this->SendResponse(response::HTTP_NOT_FOUND, 'No hotels found');
            }
            return $this->SendResponse(response::HTTP_OK, 'hotels retrieved successfully', ['data' => $hotels]);
        }
        
    }
    
    public function Places()
    {
        {
            $places = Facility::where('type', 'place')->get();
            if ($places->isEmpty()) {
                return $this->SendResponse(response::HTTP_NOT_FOUND, 'No places found');
            }
            return $this->SendResponse(response::HTTP_OK, 'places retrieved successfully', ['data' => $places]);
        }
        
    }
    

    public function Restaurants()
{
    $restaurants = Facility::where('type', 'Restaurant')->get();
    if ($restaurants->isEmpty()) {
        return $this->SendResponse(response::HTTP_NOT_FOUND, 'No restaurants found');
    }
    return $this->SendResponse(response::HTTP_OK, 'Restaurants retrieved successfully', ['data' => $restaurants]);
}


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'lat' => 'required|string|max:200',
            'long' => 'required|string|max:200',
            'bio' => 'required|string',
            'photo' => 'required|mimes:jpg,jpeg,png|max:2048',
            'number_of_places' => 'required|integer|min:1',
            'price_per_person' => 'required|integer',
            'type' => 'required|string|between:2,100',
            'country_id' => 'required|integer',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }
            
            $facility = Facility::create([
                'name' => $request->name,
                'photo' =>$request->photo,
                'lat' =>$request->lat,
                'long' =>$request->long,
                'bio' =>$request->bio,
                'type' =>$request->type,
                'number_of_places' =>$request->number_of_places,
                'price_per_person' =>$request->price_per_person, 
                'country_id' =>$request->country_id,
            ]);
    
            return $this->SendResponse(response::HTTP_CREATED , 'facility added successfully');
        }
    

    /**
     * Display the specified resource.
     */
    public function AllFacilities()
    {
        
            $facility= Facility::all();
            $data = new DashboardFacilityResource($facility);
            return $this->SendResponse(response::HTTP_OK , 'Facility data retrieved successfully' ,$data);
    
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Facility $facility)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
 /**
 * Update the specified resource in storage.
 */
public function update(Request $request, Facility $facility)
{
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|between:2,100',
        'lat' => 'required|string|max:200',
        'long' => 'required|string|max:200',
        'bio' => 'required|string',
        'photo' => 'nullable|mimes:jpg,jpeg,png|max:2048',
        'number_of_places' => 'required|integer|min:1',
        'price_per_person' => 'required|integer',
        'type' => 'required|string',
        'country_id' => 'required|integer',
    ]);

    if ($validator->fails()) {
        return response()->json($validator->errors()->toJson(), 400);
    }

    $facility->update([
        'name' => $request->name,
        'photo' => $request->photo,
        'lat' => $request->lat,
        'long' => $request->long,
        'bio' => $request->bio,
        'type' => $request->type,
        'number_of_places' => $request->number_of_places,
        'price_per_person' => $request->price_per_person,
        'country_id' =>$request->country_id,
    ]);

    return $this->SendResponse(response::HTTP_OK, 'Facility updated successfully');
}
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Facility $facility)
    {
        $facility->delete();
        return response()->json('product deleted successfully');
    }
}
