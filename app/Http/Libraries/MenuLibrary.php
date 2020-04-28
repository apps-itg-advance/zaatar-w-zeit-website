<?php
/**
 * Created by PhpStorm.
 * User: MohammadMasarra
 * Date: 15/01/2020
 * Time: 3:14 PM
 */

namespace App\Http\Libraries;
use App\Http\Helpers\Helper;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class MenuLibrary
{
    public static function GetCategories($organization_id,$token)
    {
        $url=env('BASE_URL').'menu/GetCategories?token='.$token.'&organization_id='.$organization_id.'&channel_id=1';
        $query=Helper::getApi($url);
        return $query;
    }
    static function DownloadImg($img_url,$organization_id,$flag)
    {
        $array_name=explode('/',$img_url);
        $l=count($array_name);
        $img_name=$array_name[$l-1];
        $folder='/uploads/'.$organization_id.'/menu/';
        $path=$folder.$img_name;
        $img_array=explode('.',$img_name);

        if(count($img_array)>1) {
            if ($img_url != '') {
                if (!$flag) {
                    $content = @file_get_contents($img_url);
                    if($content!='')
                    {
                        Storage::put($path, $content);
                    }

                }
            }
        }


        return 'storage/'.$path;
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

        $url_img='';
        $url=env('BASE_URL').'menu/GetMenuItems?token='.$token.'&organization_id='.$organization_id.'&channel_id=1&category_id='.$cat_id.$extra;
        $query=Helper::getApi($url);
        foreach ($query->data as $item)
        {
            $id=$item->ID;
            $flag=false;
            $key=$organization_id.'-'.$id;
            if(isset($item->ThumbnailImg))
            {
                $url_img=$item->ThumbnailImg;

                if(cache()->has($key) and cache()->get($key)!='')
                {
                  $flag=true;

                }
            }
            $path=self::DownloadImg($url_img,$organization_id,$flag);
            if(!$flag)
            {
                cache()->add($key,1,now()->addMinutes(15));
            }

            $item->LocalThumbnailImg=$path;
            if(isset($item->Modifiers->details->MOrder))
            {
                usort($item->Modifiers, function($a, $b)
                {
                    return strcmp($a->details->MOrder, $b->details->MOrder);
                });
            }
        }
        //usort($modifiers, 'CompareOrder');
        return $query;
    }
    public static function GetMenuItemByPlu($plu)
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


        $url=env('BASE_URL').'menu/GetMenuItemByPlu?token='.$token.'&organization_id='.$organization_id.'&channel_id=1&Plu='.$plu.$extra;
        $query=Helper::getApi($url);
        return $query;
    }
    public static function GetMenuItemsByPlus($plu)
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


        $url=env('BASE_URL').'menu/GetMenuItemsByPlu?token='.$token.'&organization_id='.$organization_id.'&channel_id=1&Plu='.$plu.$extra;
        $query=Helper::getApi($url);
        return $query;
    }
public static function CompareOrder($a, $b)
    {
        return strnatcmp($a->details->MOrder, $b->details->MOrder);
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