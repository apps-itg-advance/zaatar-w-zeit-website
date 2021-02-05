<?php

namespace App\Http\Controllers;

use App\Http\Libraries\MenuLibrary;
use App\Http\Libraries\OrderLibrary;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->limit_order = 30;
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

    public function getAllOrders(Request $request)
    {
        $favorite = false;
        if ($request->has('favorite')) {
            $favorite = true;
        }
        $loyalty_id = session()->get('loyalty_id');
        $response = OrderLibrary::GetOrdersHistory($loyalty_id, 0, $this->limit_order, $favorite);
        return response()->json($response);
    }

    public function getItemsByPlus(Request $request){
        $plus = json_decode($request->plus);
        $response = MenuLibrary::GetMenuItemsByPlus(implode(',', $plus));
        return response()->json($response->data);
    }
}
