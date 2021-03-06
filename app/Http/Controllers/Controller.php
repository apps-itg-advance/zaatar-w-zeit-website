<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use App\Http\Libraries\MenuLibrary;
use App\Http\Libraries\SettingsLib;
use App\Extensions\MongoSessionHandler;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response as HTTP;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
        SettingsLib::CompanyChildren();
        $this->_org = session()->get('_org');

        if (!isset($this->_org)) {
            session()->flush();
            cache()->clear();
            return redirect(route('home.menu'));
        }
        $navigation = MenuLibrary::GetCategories($this->_org->id, $this->_org->token);
        if (isset($navigation->data)) {
            session()->put('navigations_' . $this->_org->id, $navigation->data);
            session()->put('first_category_' . $this->_org->id, $navigation->data[0]->ID);
            session()->put('first_category_name_' . $this->_org->id, $navigation->data[0]->Label);
        }
        if (session()->has('cart')) {
            $cart = Session()->get('cart');
            $_ctotal = count($cart);
        } else {
            $_ctotal = 0;

        }
        session::put('total_cart_items', $_ctotal);

        if (!session()->has('skey')) {
            $skey = Carbon::now()->timestamp;
            session()->put('skey', $skey);
        }
        $this->GetActiveMenu();
    }

    public function GetActiveMenu()
    {
        $current = Route::currentRouteName();
        $this->menu_active = '';
        $this->favourite_active = '';
        $this->profile_active = '';
        $this->login_active = '';
        switch ($current) {
            case 'home.menu':
                $this->menu_active = 'active';
                break;
            case 'customer.favourites':
                $this->favourite_active = 'active';
                break;
            case 'customer.index':
                $this->profile_active = 'active';
                break;
            case 'auth.login':
                $this->login_active = 'active';
                break;
        }
    }

    public function switch_organization($id)
    {
        session()->forget('cart');
        session()->save();
        SettingsLib::SetOrganization($id);
        return redirect(route('home'));
    }

    public function LevelId()
    {
        $skey = session()->has('skey') ? session()->get('skey') : '';
        $level_id = '';
        if ($skey != '') {
            $key = 'user' . $skey;
            $user = session()->has($key) ? session()->get($key) : array();
            $level_id = (isset($user->details->LevelId) and !is_null($user->details->LevelId)) ? $user->details->LevelId : '';
        }
        return $level_id;
    }


    public function respondWithError($message, $statusCode = HTTP::HTTP_BAD_REQUEST)
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
        ],$statusCode);
    }

}
