<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Visitor;

class VisitorController extends Controller
{
    public function GetVisitorDetails(){
        
        $ip_address = $_SERVER['REMOTE_ADDR'];
        date_default_timezone_set("Africa/Algiers");
        $visit_time = date("h:i:sa");
        $visit_date = date("d-m-Y");

        $result = Visitor::create([
            'ip_address' =>  $ip_address,
            'visit_time' => $visit_time,
            'visit_date' => $visit_date
        ]);

        return $result;

    } //end method 

    public function totalVisitors()
    {
        $totalVisitors = Visitor::count();
        return response()->json(['total_visitors' => $totalVisitors]);

    }//End Method
}
