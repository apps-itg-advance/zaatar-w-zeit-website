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

    public static function DeleteAddress($res)
    {
        $Skey=session()->get('skey');
        $data=$res->data;
        if($res->type=='login')
        {
            session()->put('user'.$Skey,$data->customer);
            session()->put('addresses'.$Skey,$data->addresses);
        }
        session()->put('is_login',true);
        session()->put('token',$data->token);


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
            $res=$query->data;
            session()->put('addresses'.$Skey,$res);
        return $res;
    }

}