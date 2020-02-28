<?php
/**
 * Created by PhpStorm.
 * User: MohammadMasarra
 * Date: 05/02/2020
 * Time: 10:54 AM
 */

namespace App\Http\Libraries;
use App\Http\Helpers\Helper;
use App\Http\Libraries\SettingsLib;


class AuthLibrary
{
    public static function Login($array)
    {
        $s_org=session()->get('_org');

        $post_array['token']=$s_org->token;
        $post_array['organization_id']=$s_org->id;
        $post_array['channel_id']=1;
        $post_array['MobileNumber']=$array['mobile'];
        $post_array['Email']=$array['email'];
        $post_array['CountryCode']=$array['country_code'];

        $url=env('BASE_URL').'auth/Signup';
        $query=Helper::postApi($url,$post_array);
        return $query;
    }
    public static function PinConfirmation($array)
    {
        $s_org=session()->get('_org');

        $post_array['token']=$s_org->token;
        $post_array['organization_id']=$s_org->id;
        $post_array['channel_id']=1;
        $post_array['RequestId']=$array['request_id'];
        $post_array['MobileNumber']=$array['mobile'];
        $post_array['PinCode']=$array['pin'];
        $post_array['CountryCode']=$array['country_code'];

        $url=env('BASE_URL').'auth/SignupConfirmation';
        $query=Helper::postApi($url,$post_array);
        return $query;
    }

    public static function LoginSession($res)
    {
        $Skey=session()->get('skey');
        $data=$res->data;
        $loyalty_id=$data->customer->details->LoyaltyId;
        $org_id=$data->customer->details->OrgId;
        session()->put('user'.$Skey,$data->customer);
        session()->put('addresses'.$Skey,$data->addresses);
        session()->put('is_login',true);
        session()->put('token',$data->token);
        session()->put('loyalty_id',$loyalty_id);
        session()->put('OrgId',$org_id);
        SettingsLib::UserTokens($loyalty_id);


    }
    public static function PinSession($res)
    {
        $Skey=session()->get('skey');
        $data=$res->data;
        session()->put('LoginRequestId'.$Skey,$data->RequestId);
        session()->put('LoginMobile'.$Skey,$data->MobileNumber);
        session()->put('LoginCountryCode'.$Skey,$data->CountryCode);
    }
    public static function LogOut()
    {
        session()->flush();
        session()->save();
        return true;
    }
    public static function ResendPin()
    {
        $s_org=session()->get('_org');
        $Skey=session()->get('skey');
        $post_array['token']=$s_org->token;
        $post_array['organization_id']=$s_org->id;
        $post_array['channel_id']=1;
        $post_array['RequestId']=session()->get('LoginRequestId'.$Skey);
        $post_array['MobileNumber']=session()->get('LoginMobile'.$Skey);

        $url=env('BASE_URL').'auth/ResendSMS';
        $query=Helper::postApi($url,$post_array);
        return $query;
    }
    public static function Register($array)
    {
        $s_org=session()->get('_org');
        $Skey=session()->get('skey');
        $post_array['token']=$s_org->token;
        $post_array['organization_id']=$s_org->id;
        $post_array['channel_id']=1;
        $post_array['RequestId']=$array['request_id'.$Skey];
        $post_array['MobileNumber']=$array['mobile'.$Skey];
        $post_array['Email']=$array['email'.$Skey];
        $post_array['FirstName']=$array['first_name'.$Skey];
        $post_array['LastName']=$array['family_name'.$Skey];

        $url=env('BASE_URL').'auth/Register';
        $query=Helper::postApi($url,$post_array);
        dump($query);
        die;
        return $query;


    }
}