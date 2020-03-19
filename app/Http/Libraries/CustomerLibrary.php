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
    public static function UpdateAddress($array)
    {
        $s_org=session()->get('_org');

        $array['token']=$s_org->token;
        $array['organization_id']=$s_org->id;
        $array['channel_id']=1;

        $url=env('BASE_URL').'addresses/edit';
        $query=Helper::postApi($url,$array);
        dd($query, $array);
        return $query;
    }
    public static function AddAddress($array)
    {
        $s_org=session()->get('_org');

        $array['token']=$s_org->token;
        $array['organization_id']=$s_org->id;
        $array['channel_id']=1;

        $url=env('BASE_URL').'addresses/add';
        $query=Helper::postApi($url,$array);
        return $query;
    }

    public static function DeleteAddress($id,$loyalty_id)
    {
        $s_org=session()->get('_org');

        $array['token']=$s_org->token;
        $array['organization_id']=$s_org->id;
        $array['channel_id']=1;
        $array['ID']=$id;
        $array['LoyaltyId']=$loyalty_id;

        $url=env('BASE_URL').'addresses/delete';
        $query=Helper::postApi($url,$array);
        self::UpdateSessionAddresses($loyalty_id);
        return $query;
    }
    public static function UpdateCustomers($array)
    {
        $s_org=session()->get('_org');

        $array['token']=$s_org->token;
        $array['organization_id']=$s_org->id;
        $array['channel_id']=1;

        $url=env('BASE_URL').'customers/edit';
        $query=Helper::postApi($url,$array);
        return $query;
    }
    public static function UpdateSessionAddresses($loyalty_id)
    {
        $Skey=session()->get('skey');
        $s_org=session()->get('_org');
        $url=env('BASE_URL').'addresses/get?token='.$s_org->token.'&organization_id='.$s_org->id.'&channel_id=1&LoyaltyId='.$loyalty_id;
            $query=Helper::getApi($url);
            $res=array();
            if(isset($query->data))
            {
                $res=$query->data;
            }
            session()->put('addresses'.$Skey,$res);
        return $res;
    }
    public static function GetOrdersHistory($loyalty_id)
    {
        $Skey=session()->get('skey');
        $s_org=session()->get('_org');
        $url=env('BASE_URL').'orders/GetOrdersHistory?token='.$s_org->token.'&organization_id='.$s_org->id.'&channel_id=1&LoyaltyId='.$loyalty_id;
        $query=Helper::getApi($url);
        $res=$query->data;
        return $res;
    }
    public static function GetAddressTypes($loyalty_id)
    {
        $Skey=session()->get('skey');
        $s_org=session()->get('_org');
        $url=env('BASE_URL').'orders/GetOrdersHistory?token='.$s_org->token.'&organization_id='.$s_org->id.'&channel_id=1&LoyaltyId='.$loyalty_id;
        $query=Helper::getApi($url);
        $res=$query->data;
        return $res;
    }
    public static function GetVouchers($array)
    {
        $s_org=session()->get('_org');

        $array['token']=$s_org->token;
        $array['organization_id']=$s_org->id;
        $array['channel_id']=1;
        $url=env('BASE_URL').'LoyaltiesApi/GetVouchers';
        $query=Helper::postApi($url,$array);
        $res=$query->data;
        return $res;
    }


}