<?php

namespace App\Http\Controllers;

use App\Http\Libraries\SettingsLib;
use Illuminate\Http\Request;

class GeneralController extends Controller
{
    public function getCities(Request $request)
    {
        $cities = SettingsLib::GetCities();
        return response()->json($cities);
    }
}
