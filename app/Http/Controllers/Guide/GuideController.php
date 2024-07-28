<?php

namespace App\Http\Controllers\Guide;

use Carbon\Carbon;
use App\Models\Trip;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;

class GuideController extends Controller
{
    use ResponseTrait;

    public function home(){}// contain the cards of the trips and the accepted by admin 
    public function getPendingTrips(){}
    public function getCominSoonTrips(){}
    public function getInProgressTrips(){}
    public function getHistoryTrips(){}
    public function getDays(){}
    public function getDayDetails(){}//contain if the guide can add note or not(depend on the inprogress helper function)
    public function transactions(){}
    public function addNote(){}
     
    
}
