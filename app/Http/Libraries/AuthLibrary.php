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
        $post_array['MobileNumber'] = $array['mobile'];
        $post_array['Email'] = $array['email'];
        $post_array['CountryCode'] = $array['country_code'];
        $post_array['ReferralSource'] = 'web';
        $url = env('BASE_URL') . 'auth/Signup';
        $query = Helper::postApi($url, $post_array, false);
        return $query;
    }

    public static function PinConfirmation($array)
    {
        $post_array['RequestId'] = $array['request_id'];
        $post_array['MobileNumber'] = $array['mobile'];
        $post_array['PinCode'] = $array['pin'];
        $post_array['CountryCode'] = $array['country_code'];
        $url = env('BASE_URL') . 'auth/SignupConfirmation';
        $query = Helper::postApi($url, $post_array, false);
        return $query;
    }

    public static function LoginSession($res)
    {
        $Skey = session()->get('skey');
        $data = $res->data;
        $loyalty_id = $data->customer->details->LoyaltyId;
        $org_id = $data->customer->details->OrgId;
        $data->customer->vouchers = array();
        session()->put('user' . $Skey, $data->customer);
        session()->put('addresses' . $Skey, $data->addresses);
        session()->put('is_login', true);
        session()->put('token', $data->token);
        session()->put('loyalty_id', $loyalty_id);
        session()->put('OrgId', $org_id);
        session()->save();
    }

    public static function RegisterSession($res)
    {
        $Skey = session()->get('skey');
        $data = $res->data;
        $loyalty_id = $data->details->LoyaltyId;
        $org_id = $data->details->OrgId;
        session()->put('user' . $Skey, $data);
        session()->put('is_login', true);
        session()->put('token', $data->token);
        session()->put('loyalty_id', $loyalty_id);
        session()->put('OrgId', $org_id);
        SettingsLib::UserTokens($loyalty_id);
    }

    public static function PinSession($res)
    {
        $Skey = session()->get('skey');
        $data = $res->data;
        session()->put('LoginRequestId' . $Skey, $data->RequestId);
        session()->put('LoginMobile' . $Skey, $data->MobileNumber);
        session()->put('LoginCountryCode' . $Skey, $data->CountryCode);
    }

    public static function LogOut()
    {
        $s_org_id = session()->get('OrgId');
        session()->flush();
        session()->save();
        session()->put('OrgId', $s_org_id);
        SettingsLib::CompanyChildren(true);
        return true;
    }

    public static function ResendPin()
    {
        $Skey = session()->get('skey');
        $post_array['RequestId'] = session()->get('LoginRequestId' . $Skey);
        $post_array['MobileNumber'] = session()->get('LoginMobile' . $Skey);
        $url = env('BASE_URL') . 'auth/ResendSMS';
        $query = Helper::postApi($url, $post_array, false);
        return $query;
    }

    public static function Register($array)
    {
        $Skey = session()->get('skey');
        $post_array['RequestId'] = $array['request_id' . $Skey];
        $post_array['MobileNumber'] = $array['mobile' . $Skey];
        $post_array['Email'] = $array['email' . $Skey];
        $post_array['FirstName'] = $array['first_name' . $Skey];
        $post_array['LastName'] = $array['family_name' . $Skey];
        $post_array['Gender'] = $array['gender' . $Skey];
        $post_array['Birthday'] = $array['dob' . $Skey];
        $url = env('BASE_URL') . 'auth/Register';
        $query = Helper::postApi($url, $post_array,false);
        return $query;
    }
}
