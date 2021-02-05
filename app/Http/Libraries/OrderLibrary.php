<?php
/**
 * Created by PhpStorm.
 * User: MohammadMasarra
 * Date: 05/02/2020
 * Time: 10:54 AM
 */

namespace App\Http\Libraries;

use App\Http\Helpers\Helper;

class OrderLibrary
{
    public static function GetOrdersHistory($loyalty_id, $row = false, $limit = false, $favorite)
    {
        $Skey = session()->get('skey');
        $s_org = session()->get('_org');
        $token = session()->get('token');
        $extra = '';
        if (!$row) {
            $row = 0;
            $limit = 30;

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
}
