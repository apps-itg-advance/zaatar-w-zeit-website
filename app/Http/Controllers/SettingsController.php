<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Libraries\MenuLibrary as MenuLibrary;
use App\Extensions\MongoSessionHandler;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Carbon;

class SettingsController extends Controller
{

    public function calender(Request $request)
    {
        $open_time = $request->get('open_time');
        $close_time = $request->get('close_time');
        $date_selected = $request->get('date_selected');
        $eta = $request->get('eta');
        $time='';
        $org=session()->get('_org');
        if(isset($org->timezone))
        {
            $current_date=Carbon::now($org->timezone);
            $time= $current_date->format('H:i:s');
        }
        return view('checkouts._time_calender',compact('open_time','close_time','time','date_selected','current_date','eta'));  //
    }
}
