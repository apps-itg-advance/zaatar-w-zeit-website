<?php
/**
 * Created by PhpStorm.
 * User: MohammadMasarra
 * Date: 15/01/2020
 * Time: 3:14 PM
 */

namespace App\Http\Libraries;

use App;
use App\Http\Helpers\Helper;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class MenuLibrary
{
    public static function GetCategories($organization_id, $token)
    {
        $lang = session()->get('locale');
        $url = env('BASE_URL') . 'menu/GetCategories?token=' . $token . '&organization_id=' . $organization_id . '&channel_id=1&lang=' . $lang;
        $query = Helper::getApi($url);
        return $query;
    }

    static function DownloadImg($img_url, $organization_id, $flag)
    {
        $array_name = explode('/', $img_url);
        $l = count($array_name);
        $img_name = $array_name[$l - 1];
        $folder = '/uploads/' . $organization_id . '/menu/';
        $path = $folder . $img_name;
        $exists = Storage::disk('local')->exists($path);
        if (!$exists) {
            $img_array = explode('.', $img_name);
            if (count($img_array) > 1) {
                if ($img_url != '') {
                    if (!$flag) {
                        $content = @file_get_contents($img_url);
                        if ($content != '') {
                            Storage::put($path, $content);
                        }

                    }
                }
            }
        }
        return 'storage/' . $path;
    }

    public static function GetMenuItems($cat_id)
    {
        $extra = '';
        $s_org = session()->get('_org');
        $token = $s_org->token;
        if (session()->has('is_login')) {
            $loyalty_id = session()->get('loyalty_id');
            $extra = '&LoyaltyId=' . $loyalty_id;
            $token = session()->get('token');
        }
        $organization_id = $s_org->id;
        $url_img = '';
        $lang = session()->get('locale');
        $url = env('BASE_URL') . 'menu/GetMenuItems?token=' . $token . '&organization_id=' . $organization_id . '&channel_id=1&category_id=' . $cat_id . $extra . '&lang=' . $lang;
        $query = Helper::getApi($url);

        foreach ($query->Combo as $combo) {
            $combo->Quantity = 0;
            $combo->AppliedModifiers = [];
            $combo->AppliedMeal = [];
            $combo->TotalPrice = floatval($combo->Price);
        }

        foreach ($query->data as $item) {
            $item->Quantity = 0;
            $item->AppliedModifiers = [];
            $item->AppliedMeal = [];
            $item->TotalPrice = floatval($item->Price);
            $id = $item->ID;
            $flag = false;
            $key = $organization_id . '-' . $id;
            if (isset($item->ThumbnailImg)) {
                $url_img = $item->ThumbnailImg;
                if (cache()->has($key) and cache()->get($key) != '') {
                    $flag = true;
                }
            }
            $path = self::DownloadImg($url_img, $organization_id, $flag);
            if (!$flag) {
                cache()->add($key, 1, now()->addMinutes(1440));
            }
            $item->LocalThumbnailImg = $path;
            if (isset($item->Modifiers->details->MOrder)) {
                usort($item->Modifiers, function ($a, $b) {
                    return strcmp($a->details->MOrder, $b->details->MOrder);
                });
            }
        }
        return $query;
    }

    public static function GetMenuItemByPlu($plu)
    {
        $extra = '';
        $s_org = session()->get('_org');
        $token = $s_org->token;
        if (session()->has('is_login')) {
            $loyalty_id = session()->get('loyalty_id');
            $extra = '&LoyaltyId=' . $loyalty_id;
            $token = session()->get('token');
        }
        $organization_id = $s_org->id;
        $lang = session()->get('locale');
        $url = env('BASE_URL') . 'menu/GetMenuItemByPlu?token=' . $token . '&organization_id=' . $organization_id . '&channel_id=1&Plu=' . $plu . $extra . '&lang=' . $lang;
        $query = Helper::getApi($url);
        return $query;
    }

    public static function GetMenuItemsByPlus($plu)
    {
        $extra = '';
        $s_org = session()->get('_org');
        $token = $s_org->token;
        if (session()->has('is_login')) {
            $loyalty_id = session()->get('loyalty_id');
            $extra = '&LoyaltyId=' . $loyalty_id;
            $token = session()->get('token');
        }
        $organization_id = $s_org->id;
        $lang = session()->get('locale');
        $url = env('BASE_URL') . 'menu/GetMenuItemsByPlu?token=' . $token . '&organization_id=' . $organization_id . '&channel_id=1&Plu=' . $plu . $extra . '&lang=' . $lang;
        $query = Helper::getApi($url);
        return $query;
    }

    public static function CompareOrder($a, $b)
    {
        return strnatcmp($a->details->MOrder, $b->details->MOrder);
    }

    public static function RemoveFavoriteItem($itemId)
    {
        $post_array['favorites_item_id'] = $itemId;
        $url = env('BASE_URL') . 'items/DeleteFavoriteItem';
        $query = Helper::postApi($url, $post_array);
        return $query;
    }

    public static function SetFavoriteItem($itemId, $name = '', $data_string = '')
    {
        $post_array['item_id'] = $itemId;
        $post_array['name'] = $name;
        $post_array['item_data'] = $data_string;
        $url = env('BASE_URL') . 'items/SaveFavoriteItem';
        $query = Helper::postApi($url, $post_array);
        return $query;
    }

    public static function SetFavoriteOrder($orderId)
    {
        $post_array['OrderId'] = $orderId;
        $post_array['Status'] = 1;
        $url = env('BASE_URL') . 'orders/SetFavorite';
        $query = Helper::postApi($url, $post_array);
        return $query;
    }

    public static function RemoveFavoriteOrder($orderId)
    {
        $post_array['OrderId'] = $orderId;
        $post_array['Status'] = 0;
        $url = env('BASE_URL') . 'orders/SetFavorite';
        $query = Helper::postApi($url, $post_array);
        return $query;
    }

    public static function GetFavouriteItems()
    {
        $s_org = session()->get('_org');
        $loyalty_id = session()->get('loyalty_id');
        $token = session()->get('token');
        $organization_id = $s_org->id;
        $lang = session()->get('locale');
        $url = env('BASE_URL') . 'items/GetMenuFavorite?token=' . $token . '&organization_id=' . $organization_id . '&channel_id=1&LoyaltyId=' . $loyalty_id . '&lang=' . $lang;
        $query = Helper::getApi($url);
        foreach ($query->data as $item) {
            $id = $item->ID;
            $flag = false;
            $key = $organization_id . '-' . $id;
            if (isset($item->ThumbnailImg)) {
                $url_img = $item->ThumbnailImg;

                if (cache()->has($key) and cache()->get($key) != '') {
                    $flag = true;

                }
            }
            $path = self::DownloadImg($url_img, $organization_id, $flag);
            if (!$flag) {
                cache()->add($key, 1, now()->addMinutes(1440));
            }

            $item->LocalThumbnailImg = $path;
            if (isset($item->Modifiers->details->MOrder)) {
                usort($item->Modifiers, function ($a, $b) {
                    return strcmp($a->details->MOrder, $b->details->MOrder);
                });
            }
        }
        return $query;
    }

    public static function GetOrdersHistoryWithFav()
    {
        $s_org = session()->get('_org');
        $loyalty_id = session()->get('loyalty_id');
        $token = session()->get('token');
        $organization_id = $s_org->id;
        $lang = session()->get('locale');
        $url = env('BASE_URL') . 'orders/GetOrdersHistory?token=' . $token . '&organization_id=' . $organization_id . '&channel_id=1&LoyaltyId=' . $loyalty_id . '&lang=' . $lang;
        $query = Helper::getApi($url);
        return $query;
    }
}
