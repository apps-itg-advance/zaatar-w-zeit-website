<?php
/**
 * Created by PhpStorm.
 * User: MohammadMasarra
 * Date: 05/02/2020
 * Time: 10:54 AM
 */

namespace App\Http\Libraries;

use App\Http\Helpers\Helper;


class CustomerLibrary
{

    public static function getAllAddresses()
    {
        $skey = session()->get('skey');
        $s_org = session()->get('_org');
        $token = session()->get('token');
        $lang = session()->get('locale');
        $user = session()->has('user' . $skey) ? session()->get('user' . $skey) : array();
        $loyalty_id = $user->details->LoyaltyId;
        $url = env('BASE_URL') . 'addresses/get?token=' . $token . '&organization_id=' . $s_org->id . '&channel_id=1&LoyaltyId=' . $loyalty_id . '&lang=' . $lang;
        $query = Helper::getApi($url);
        $response = [];
        if (isset($query->data)) {
            $response = $query->data;
            session()->put('addresses' . $skey, $response);
            session()->save();
        }
        return $response;
    }

    public static function updateAddress($data)
    {
        $url = env('BASE_URL') . 'addresses/edit';
        return Helper::postApi($url, $data);
    }

    public static function addAddress($data)
    {
        $url = env('BASE_URL') . 'addresses/add';
        return Helper::postApi($url, $data);
    }

    public static function deleteAddress($id)
    {
        $data = ['ID' => $id];
        $url = env('BASE_URL') . 'addresses/delete';
        $response = Helper::postApi($url, $data);
        self::getAllAddresses();
        return $response;
    }

    public static function checkZone($lat, $lng)
    {
        $skey = session()->get('skey');
        $s_org = session()->get('_org');
        $token = session()->get('token');
        $lang = session()->get('locale');
        $user = session()->has('user' . $skey) ? session()->get('user' . $skey) : array();
        $loyalty_id = $user->details->LoyaltyId;
        $url = env('BASE_URL') . 'MapApi/check_in_zone?token=' . $token . '&organization_id=' . $s_org->id . '&lat=' . $lat . '&lng=' . $lng . '&is_multi=true';
        $query = Helper::getApi($url);
        $response = [];
        if (isset($query->data)) {
            $response = $query->data;
        }
        return $response;
    }

    public static function DeleteCreditCards($id)
    {
        $data = ['CardId' => $id];
        $url = env('BASE_URL') . 'customers/DeleteCreditCard';
        return Helper::postApi($url, $data);
    }

    public static function UpdateCustomers($data)
    {
        $url = env('BASE_URL') . 'customers/edit';
        return Helper::postApi($url, $data);
    }

    public static function GetOrdersHistory($loyalty_id, $row = false, $limit = false, $favorite)
    {
        $Skey = session()->get('skey');
        $s_org = session()->get('_org');
        $token = session()->get('token');
        $extra = '';
        if (!$row) {
            $row = 0;
            $limit = 3;
        }
        $extra = '&row=' . $row . '&limit=' . $limit;
        if ($favorite) {
            $extra .= '&favorite=1';
        }
        $lang = session()->get('locale');
        $url = env('BASE_URL') . 'orders/GetOrdersHistory?token=' . $token . '&organization_id=' . $s_org->id . '&channel_id=1&LoyaltyId=' . $loyalty_id . $extra . '&lang=' . $lang;
        $query = Helper::getApi($url);
        $res = $query->data;
        return array('rows' => $res, 'total' => $query->total);
    }

    public static function GetAddressTypes($loyalty_id)
    {
        $Skey = session()->get('skey');
        $s_org = session()->get('_org');
        $token = session()->get('token');
        $lang = session()->get('locale');
        $url = env('BASE_URL') . 'orders/GetOrdersHistory?token=' . $token . '&organization_id=' . $s_org->id . '&channel_id=1&LoyaltyId=' . $loyalty_id . '&lang=' . $lang;
        $query = Helper::getApi($url);
        $res = $query->data;
        return $res;
    }

    public static function GetVouchers($array)
    {
        $s_org = session()->get('_org');
        $token = session()->get('token');
        $array['token'] = $token;
        $array['organization_id'] = $s_org->id;
        $array['channel_id'] = 1;
        $lang = session()->get('locale');
        $url = env('BASE_URL') . 'LoyaltiesApi/GetVouchers?lang=' . $lang;
        $query = Helper::postApi($url, $array);
        $res = $query->data;
        return $res;
    }

    public static function GetLoyaltyCorner()
    {
        $skey = session()->get('skey');
        $s_org = session()->get('_org');
        $token = session()->get('token');
        $lang = session()->get('locale');
        $user = session()->has('user' . $skey) ? session()->get('user' . $skey) : array();
        $loyalty_id = $user->details->LoyaltyId;
        $url = env('BASE_URL') . 'loyalty/corner?token=' . $token . '&organization_id=' . $s_org->id . '&channel_id=1&LoyaltyId=' . $loyalty_id . '&lang=' . $lang;
        $query = Helper::getApi($url);
        $res = $query->data;
        return $res;
    }
}
