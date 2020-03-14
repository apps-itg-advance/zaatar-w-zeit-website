<?php
/**
 * Created by PhpStorm.
 * User: MohammadMasarra
 * Date: 15/01/2020
 * Time: 3:14 PM
 */

namespace App\Http\Libraries;
use App\Http\Helpers\Helper;

class MenuLibrary
{
    public static function GetCategories($organization_id,$token)
    {
        $url=env('BASE_URL').'menu/GetCategories?token='.$token.'&organization_id='.$organization_id.'&channel_id=1';
        $query=Helper::getApi($url);
        return $query;
    }
    public static function GetMenuItems($cat_id)
    {
        $extra='';
        $s_org=session()->get('_org');
        if(session()->has('is_login'))
        {
            $loyalty_id=session()->get('loyalty_id');
            $extra='&LoyaltyId='.$loyalty_id;
        }


        $token=$s_org->token;
        $organization_id=$s_org->id;


        $url=env('BASE_URL').'menu/GetMenuItems?token='.$token.'&organization_id='.$organization_id.'&channel_id=1&category_id='.$cat_id.$extra;
        $query=Helper::getApi($url);
        return $query;
    }
    public static function RemoveFavoriteItem($itemId){
	    $s_org=session()->get('_org');

	    $post_array['token']=$s_org->token;
	    $post_array['organization_id']=$s_org->id;
	    $post_array['channel_id']=1;
	    $post_array['LoyaltyId']=session()->get('loyalty_id');
	    $post_array['favorites_item_id']=$itemId;
//	    $post_array['item_data']=json_encode($array);

	    $url=env('BASE_URL').'items/DeleteFavoriteItem';
	    $query=Helper::postApi($url,$post_array);
	    return $query;
    }
    public static function SetFavoriteItem($itemId)
    {
        $s_org=session()->get('_org');

        $post_array['token']=$s_org->token;
        $post_array['organization_id']=$s_org->id;
        $post_array['channel_id']=1;
        $post_array['LoyaltyId']=session()->get('loyalty_id');
        $post_array['item_id']=$itemId;
        $post_array['item_data']=null;

        $url=env('BASE_URL').'items/SaveFavoriteItem';
        $query=Helper::postApi($url,$post_array);
        return $query;
    }
    public static function SetFavoriteOrder($orderId)
    {
        $s_org=session()->get('_org');

        $post_array['token']=$s_org->token;
        $post_array['organization_id']=$s_org->id;
        $post_array['channel_id']=1;
        $post_array['LoyaltyId']=session()->get('loyalty_id');
        $post_array['OrderId']=$orderId;
        $post_array['Status']=1;

        $url=env('BASE_URL').'orders/SetFavorite';
        $query=Helper::postApi($url,$post_array);
        return $query;
    }
    public static function RemoveFavoriteOrder($orderId)
    {
        $s_org=session()->get('_org');

        $post_array['token']=$s_org->token;
        $post_array['organization_id']=$s_org->id;
        $post_array['channel_id']=1;
        $post_array['LoyaltyId']=session()->get('loyalty_id');
        $post_array['OrderId']=$orderId;
        $post_array['Status']=0;

        $url=env('BASE_URL').'orders/SetFavorite';
        $query=Helper::postApi($url,$post_array);
        return $query;
    }
    public static function GetFavouriteItems()
    {
        $s_org=session()->get('_org');
        $loyalty_id=session()->get('loyalty_id');
        $token=$s_org->token;
        $organization_id=$s_org->id;

        $url=env('BASE_URL').'items/GetMenuFavorite?token='.$token.'&organization_id='.$organization_id.'&channel_id=1&LoyaltyId='.$loyalty_id;
        $query=Helper::getApi($url);
        return $query;
    }
    public static function GetOrdersHistoryWithFav()
    {
        $s_org=session()->get('_org');
        $loyalty_id=session()->get('loyalty_id');
        $token=$s_org->token;
        $organization_id=$s_org->id;

        $url=env('BASE_URL').'orders/GetOrdersHistory?token='.$token.'&organization_id='.$organization_id.'&channel_id=1&LoyaltyId='.$loyalty_id;
        $query=Helper::getApi($url);
        return $query;
    }
}