<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Http\Libraries\AuthLibrary;
use App\Http\Libraries\MenuLibrary;
use App\Http\Libraries\SettingsLib;
use App\Http\Libraries\CustomerLibrary;
use Illuminate\Http\Request;

class LoyaltyController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->limit_order = 3;
        $this->skey = session()->get('skey');
        if ($this->skey != '') {
            $key = 'user' . $this->skey;
            $user = session()->has($key) ? session()->get($key) : array();
            $this->level_id = (isset($user->details->LevelId) and !is_null($user->details->LevelId)) ? $user->details->LevelId : '';
        }
        view()->composer('*', function ($view) {
            $view->with('limit', $this->limit_order);
            $view->with('LEVEL_ID', $this->level_id);
        });
    }

    public function getLoyaltyCorner(Request $request){
        $response = CustomerLibrary::GetLoyaltyCorner();
        return response()->json($response);
    }
}
