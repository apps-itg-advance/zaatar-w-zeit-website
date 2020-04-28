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


class SettingsLib
{
    public function __construct()
    {
        //$this->_org=session()->get('_org');
    }
    public static function UserTokens($loyalty_id)
    {
        $url=env('BASE_URL').'settings/CompanyChildren';
        $array=array(
            'organization_id'=>env('ORG_ID'),
            'channel_id'=>env('CH_ID'),
            'token'=>env('TOKEN'),
            'loyalty_id'=>$loyalty_id
        );

        if (!session::has('user_tokens') ) {
            $query=Helper::postApi($url,$array);
            $res=$query->data;
            session::put('user_tokens',$res);
        }

        else{

            $res=session::get('user_tokens');
        }

        if(count($res)>1)
        {

                foreach ($res as $re)
                {
                    if($re->parent_id!=0)
                    {
                        self::SetUserOrganization($re->id) ;
                        break;
                    }
                }
            }



        return $res;
    }
  //  public static function
    public static function CompanyChildren()
    {
        $key='settings';
        if (Cache::has($key) and Cache::get($key)!=null) {
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
            $query = Helper::postApi($url, $array);
            $res = $query->data;
            $client_geo=$query->client_geo;

           // echo 'from API';
            Cache::put($key, $res, 150000);
            Cache::put('geo_location', $client_geo, 150000);
        }
        if (!session()->has('organizations')) {

            session()->put('organizations', $res);
        } else {
            $res = session()->get('organizations');
        }
        $flag=false;
        if(!session::has('_org'))
        {
            foreach ($res as $re) {
                if ($re->parent_id != 0 and strtolower($re->country_code)==strtolower($client_geo)) {
                    $_org_id=$re->id;
                    $flag=true;
                    break;
                }
            }
            if(!$flag)
            {
                foreach ($res as $re) {
                    if ($re->parent_id != 0) {
                        self::SetOrganization($re->id);
                        break;
                    }
                }
            }
            else{
                self::SetOrganization($_org_id);
            }

        }
        /*
        dump($query);

        if (!session::has('organizations') ) {
            echo 'test';
            $query=Helper::postApi($url,$array);
            $res=$query->data;
            session()->put('organizations',$res);
            session()->forget('_org');
            session()->save();

        }

        else{

            $res=session()->get('organizations');
        }
        foreach ($res as $re)
        {
            if($re->parent_id!=0)
            {
                echo $re->id;
                self::SetOrganization($re->id) ;
                break;
            }
        }
*/

        return $res;
    }

    public static function SetOrganization($organization_id)
    {
       // $res=session()->get('organizations');
       // die;
        $res=Cache::get('settings');
       // dump($res);
        $res_user=session()->get('user_tokens');
        $_org=array();

     /*   if(!empty($res_user))
        {
            foreach($res_user as $re_u)
            {
                if($re_u->id==$organization_id)
                {
                    $_org=$re_u;
                    break;
                }
            }
        }
        else{
            foreach($res as $re)
            {
                if($re->id==$organization_id)
                {
                    $_org=$re;
                    break;
                }
            }
        } */
        foreach($res as $re)
        {
            if($re->id==$organization_id)
            {
                $_org=$re;
                break;
            }
        }
        $old_org=session()->get('_org');
        if(isset($old_org->id) and $organization_id!=$old_org->id)
        {
            $key=session()->get('skey');
            session()->forget('cart');
            session()->forget('LoginRequestId'.$key);
            session()->forget('LoginMobile'.$key);
            session()->forget('LoginCountryCode'.$key);
            session()->forget('user'.$key);
            session()->forget('addresses'.$key);
            session()->forget('loyalty_id');
            session()->forget('is_login');
            session()->forget('_org');

        }
        session()->put('_org',$_org);
        session()->save();


    }

    public static function SetUserOrganization($organization_id)
    {
        $res=session::get('user_tokens');
        $_org=array();
        foreach($res as $re)
        {
            if($re->id==$organization_id)
            {
                $_org=$re;
                break;
            }
        }
        session::forget('_org');
        session::put('_org',$_org);
    }
    public static function GetSelectedCompany()
    {
        $_org=session()->get('_org');
        if(is_null($_org))
        {

           $OrgId=session()->get('OrgId');
           if($OrgId!=''){
               self::SetOrganization($OrgId);
               $_org=session()->get('_org');
           }

        }
        return $_org;
    }
    public static function GetCities()
    {
        $_org=self::GetSelectedCompany();
        $loyalty_id=session()->get('loyalty_id');
        $url=env('BASE_URL').'geo/GetCities?token='.$_org->token.'&organization_id='.$_org->id.'&channel_id=1&LoyaltyId='.$loyalty_id;
        if (!session::has('cities')) {
            $query=Helper::getApi($url);
            $res=$query->data;
            session::put('cities',$res);
        }
        else{
            $res=session::get('cities');
        }
        return $res;
    }
    public static function GetDeliveryScreenDataSteps()
    {
        $_org=self::GetSelectedCompany();
        $loyalty_id=session()->get('loyalty_id');
        $url=env('BASE_URL').'settings/GetDeliveryScreenDataSteps?token='.$_org->token.'&organization_id='.$_org->id.'&channel_id=1&LoyaltyId='.$loyalty_id;
        $query=Helper::getApi($url);
        $res=$query->data;
        return $res;
    }
    public static function GetLoyaltyLevels()
    {
        $_org=self::GetSelectedCompany();
        $loyalty_id=session()->get('loyalty_id');
        $url=env('BASE_URL').'LoyaltiesApi/GetLoyaltyLevel?token='.$_org->token.'&organization_id='.$_org->id.'&channel_id=1&LoyaltyId='.$loyalty_id;
        $query=Helper::getApi($url);
        $res=$query->data;
        return $res;
    }


}