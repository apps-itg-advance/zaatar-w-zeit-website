<?php
/**
 * Created by PhpStorm.
 * User: MohammadMasarra
 * Date: 06/02/2020
 * Time: 3:31 PM
 */


namespace App\Http\Libraries;

use App\Http\Helpers\Helper;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Carbon;
use App;


class SettingsLib
{
    public function __construct()
    {
    }

    public static function UserTokens($loyalty_id)
    {
        $organization_id = session()->get('_org');
        $url = env('BASE_URL') . 'settings/CompanyChildren';
        $array = array(
            'organization_id' => env('ORG_ID'),
            'channel_id' => env('CH_ID'),
            'token' => env('TOKEN'),
            'loyalty_id' => $loyalty_id
        );

        if (!session::has('user_tokens')) {
            $query = Helper::postApi($url, $array);
            $res = $query->data;
            session::put('user_tokens', $res);
        } else {

            $res = session::get('user_tokens');
        }
        self::SetUserOrganization($organization_id);
        return $res;
    }

    //  public static function
    public static function CompanyChildren_old($refresh = false)
    {
        $expiresAt = Carbon::now()->addMinutes(15);
        $key = 'settings';
        if ((Cache::has($key) and Cache::get($key) != null) and !$refresh) {
            $res = Cache::get($key);
            $client_geo = Cache::get('geo_location');
            echo 'cache';

        } else {
            $url = env('BASE_URL') . 'settings/CompanyChildren';

            $array = array(
                'organization_id' => env('ORG_ID'),
                'channel_id' => env('CH_ID'),
                'token' => env('TOKEN'),
                'ip' => request()->ip(),
            );
            if (session()->has('loyalty_id') and !empty(session()->get('loyalty_id'))) {
                $array['loyalty_id'] = session()->get('loyalty_id');
            }
            $query = Helper::postApi($url, $array);
            $res = $query->data;

            $client_geo = $query->client_geo;

            // echo 'from API';
            Cache::put($key, $res, $expiresAt);
            Cache::put('geo_location', $client_geo, $expiresAt);
        }
        if (!session()->has('organizations')) {
            session()->put('organizations', $res);
        } else {
            session()->forget('organizations');
            session()->put('organizations', $res);
        }
        $flag = false;
        if (!$refresh) {
            if (!session::has('_org') or empty(session()->get('_org'))) {
                foreach ($res as $re) {
                    if ($re->parent_id != 0 and strtolower($re->country_code) == strtolower($client_geo)) {
                        $_org_id = $re->id;
                        $flag = true;
                        break;
                    }
                }
                if (!$flag) {
                    foreach ($res as $re) {
                        if ($re->parent_id != 0) {
                            self::SetOrganization($re->id);
                            break;
                        }
                    }
                } else {
                    self::SetOrganization($_org_id);
                }
            }
        } else {
            self::SetOrganization(session()->get('OrgId'));
        }
        return $res;
    }

    public static function CompanyChildren($refresh = false)
    {
        $expiresAt = Carbon::now()->addMinutes(15);
        $key = 'settings';
        if ((Cache::has($key) and Cache::get($key) != null) and !$refresh) {
            $res = Cache::get($key);
            $client_geo = Cache::get('geo_location');
        } else {
            $url = env('BASE_URL') . 'settings/CompanyChildren';
            $array = array(
                'organization_id' => env('ORG_ID'),
                'channel_id' => env('CH_ID'),
                'token' => env('TOKEN'),
                'ip' => request()->ip(),
            );
            if (session()->has('loyalty_id') and !empty(session()->get('loyalty_id'))) {
                $array['loyalty_id'] = session()->get('loyalty_id');
            }
            $query = Helper::postApi($url, $array, false);
            $res = $query->data;
            $client_geo = $query->client_geo;
            Cache::put($key, $res, $expiresAt);
            Cache::put('geo_location', $client_geo, $expiresAt);
        }
        if (!session()->has('organizations')) {
            session()->put('organizations', $res);
        } else {
            session()->forget('organizations');
            session()->put('organizations', $res);
        }
        $flag = false;
        if (!$refresh) {
            if (!session::has('_org') or empty(session()->get('_org'))) {
                foreach ($res as $re) {
                    if ($re->parent_id != 0 and strtolower($re->country_code) == strtolower($client_geo)) {
                        $_org_id = $re->id;
                        $flag = true;
                        break;
                    }
                }
                if (!$flag) {
                    foreach ($res as $re) {
                        if ($re->parent_id != 0) {
                            self::SetOrganization($re->id);
                            break;
                        }
                    }
                } else {
                    self::SetOrganization($_org_id);
                }
            }
        } else {
            self::SetOrganization(session()->get('OrgId'));
        }
        session()->save();
        return $res;
    }

    public static function SwitchOrganization($organization_id)
    {
        $old_org = session()->get('_org');
        if (isset($old_org->id) and $organization_id != $old_org->id) {
            $key = session()->get('skey');
            session()->forget('cart');
            session()->forget('LoginRequestId' . $key);
            session()->forget('LoginMobile' . $key);
            session()->forget('LoginCountryCode' . $key);
            session()->forget('user' . $key);
            session()->forget('addresses' . $key);
            session()->forget('loyalty_id');
            session()->forget('is_login');
            session()->forget('_org');
            session()->forget('OrgId');
            session()->forget('organizations');
            $expiresAt = Carbon::now()->addMinutes(15);
            $key = 'settings';
            $url = env('BASE_URL') . 'settings/CompanyChildren';
            $array = array(
                'organization_id' => env('ORG_ID'),
                'channel_id' => env('CH_ID'),
                'token' => env('TOKEN'),
                'ip' => request()->ip(),
            );
            $query = Helper::postApi($url, $array, false);
            $res = $query->data;
            Cache::put($key, $res, $expiresAt);
            session()->put('organizations', $res);
            foreach ($res as $re) {
                if ($re->id == $organization_id) {
                    $_org = $re;
                    break;
                }
            }
            session()->put('_org', $_org);
            session()->put('OrgId', $organization_id);
            session()->save();
        }
    }

    public static function SetOrganization($organization_id)
    {
        $res = Cache::get('settings');
        if (is_null($res)) {
            $res = session()->get('organizations');
        }
        $res_user = session()->get('user_tokens');
        $_org = array();
        foreach ($res as $re) {
            if ($re->id == $organization_id) {
                $_org = $re;
                break;
            }
        }
        $old_org = session()->get('_org');
        if (isset($old_org->id) and $organization_id != $old_org->id) {
            $key = session()->get('skey');
            session()->forget('cart');
            session()->forget('LoginRequestId' . $key);
            session()->forget('LoginMobile' . $key);
            session()->forget('LoginCountryCode' . $key);
            session()->forget('user' . $key);
            session()->forget('addresses' . $key);
            session()->forget('loyalty_id');
            session()->forget('is_login');
            session()->forget('_org');
            session()->forget('OrgId');
            session()->forget('token');
            // session()->forget('_org');
        }

        session()->put('OrgId', $organization_id);
        // self::CompanyChildren(true);
        session()->put('_org', $_org);
        session()->save();


    }

    public static function SetUserOrganization($organization_id)
    {
        $res = session::get('user_tokens');
        $_org = array();
        foreach ($res as $re) {
            if ($re->id == $organization_id) {
                $_org = $re;
                break;
            }
        }
        session::forget('_org');
        session::put('_org', $_org);
    }

    public static function GetSelectedCompany()
    {
        $_org = session()->get('_org');
        if (!isset($_org->id)) {
            //  echo 'Null';
            $OrgId = session()->get('OrgId');
            if ($OrgId != '') {
                self::SetOrganization($OrgId);
                $_org = session()->get('_org');
            }

        }
        return $_org;
    }

    public static function GetCities()
    {
        $_org = self::GetSelectedCompany();
        $loyalty_id = session()->get('loyalty_id');
        $token = session()->get('token');
        $lang = session()->get('locale');
        $url = env('BASE_URL') . 'geo/GetCities?token=' . $token . '&organization_id=' . $_org->id . '&channel_id=1&LoyaltyId=' . $loyalty_id . '&lang=' . $lang;
        if (!session::has('cities' . $_org->id)) {
            $query = Helper::getApi($url);
            $res = $query->data;
            session::put('cities' . $_org->id, $res);
        } else {
            $res = session::get('cities' . $_org->id);
        }
        return $res;
    }

    public static function GetDeliveryScreenDataSteps($flag = true)
    {
        if (!$flag and session()->has('delivery_screens')) {
            $res = session()->get('delivery_screens');
        } else {
            $_org = self::GetSelectedCompany();
            $loyalty_id = session()->get('loyalty_id');
            $token = session()->get('token');
            $lang = session()->get('locale');
            $url = env('BASE_URL') . 'settings/GetDeliveryScreenDataSteps?token=' . $token . '&organization_id=' . $_org->id . '&channel_id=1&LoyaltyId=' . $loyalty_id . '&lang=' . $lang;
            $query = Helper::getApi($url);
            $res = $query->data;
            session()->forget('delivery_screens');
            session()->put('delivery_screens', $res);
        }

        return $res;
    }

    public static function GetLoyaltyLevels()
    {
        $_org = self::GetSelectedCompany();
        $loyalty_id = session()->get('loyalty_id');
        $token = session()->get('token');
        $lang = session()->get('locale');
        $url = env('BASE_URL') . 'LoyaltiesApi/GetLoyaltyLevel?token=' . $token . '&organization_id=' . $_org->id . '&channel_id=1&LoyaltyId=' . $loyalty_id . '&lang=' . $lang;
        //  echo $url;
        $query = Helper::getApi($url);

        $res = isset($query->data) ? $query->data : array();
        return $res;
    }


}
