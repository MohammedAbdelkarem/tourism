<?php

namespace App\Http\Controllers;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Facility;
use Illuminate\Http\Request;
use Validator;
class FacilitesController extends Controller
{


    public function __construct()
        {
           // $this->middleware('auth:admin', ['except' => ['store']]);
        }
    
    /**
     * Display a listing of the resource.
     */
    public function GetHotel()
    {
        
    }
    public function Get
    ()
    {
        
    }
    public function GetResturant()
    {
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
            'bio' => 'required|text',
            'photo' => 'required|mimes:jpg,jpeg,png|max:2048',
            'number_of_places' => 'required|integer|min:1',
            'price_per_person' => 'required|integer',
            'type' => 'required|string|between:2,100',
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
            ]);
    
            return $this->SendResponse(response::HTTP_CREATED , 'facility added successfully');
        }
    

    /**
     * Display the specified resource.
     */
    public function show(Facility $facility)
    {
        //
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
    public function update(Request $request, Facility $facility)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Facility $facility)
    {
        //
    }
}
