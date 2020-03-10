<?php
/**
 * Created by PhpStorm.
 * User: MohammadMasarra
 * Date: 27/02/2020
 * Time: 9:14 AM
 */

namespace App\Http\Libraries;
use App\Http\Helpers\Helper;
use Carbon\Carbon;

class OrdersLibrary{

    public static function SaveOrders()
    {
        $_org=session()->get('_org');
        $timezone=$_org->timezone!=null? $_org->timezone: 'Asia/Beirut';
        $open_plu=isset($_org->open_plu) and $_org->open_plu!=null? $_org->open_plu: '6969';
        $datatime=Carbon::now($timezone)->toDateTimeString();
        $cart=session()->get('cart');
        $cart_info=session()->get('cart_info');
        $cart_gift=session()->get('cart_gift');
        $cart_payment=session()->get('cart_payment');
        $cart_sp_instructions=session()->get('cart_sp_instructions');

        $cart_green=session()->get('cart_green');
        $cart_vouchers=session()->get('cart_vouchers');

        $delivery_charge=$_org->delivery_charge;
        $currency=$_org->currency;

        $s_org=session()->get('_org');

        $array_items=array();
        $array_payments=array();
        $_total=$delivery_charge;
        $discount=0;
        foreach($cart as $itm)
        {
            $_items=array(
                'ItemPlu'=>$itm['plu'],
                'GrossPrice'=>$itm['price']*$itm['quantity'],
                'OrderItemId'=>0,
                'OpenName'=>0,
                'ParentPLU'=>0,
                'UnitPrice'=>$itm['price'],
                'Quantity'=>$itm['quantity'],
                'ItemName'=>$itm['name'],
                'ItemType'=>1
            );
            $_total+=$itm['price']*$itm['quantity'];
            array_push($array_items,$_items);
            $modifiers=$itm['modifiers'];
            $md_array=array();
            for($i=0;$i<count($modifiers);$i++)
            {
                $_mod=array(
                    'ItemPlu'=>$modifiers[$i]['plu'],
                    'GrossPrice'=>$modifiers[$i]['price']*$modifiers[$i]['quantity'],
                    'OrderItemId'=>0,
                    'OpenName'=>0,
                    'ParentPLU'=>0,
                    'UnitPrice'=>$modifiers[$i]['price'],
                    'Quantity'=>$modifiers[$i]['quantity'],
                    'ItemName'=>$modifiers[$i]['name'],
                    'ItemType'=>1
                );
                $_total+=$modifiers[$i]['price']*$modifiers[$i]['quantity'];

                array_push($md_array,$_mod);
            }

        }
        if(isset($cart_green) and $cart_green!='')
        {
            $_green_array=array(
                'ItemPlu'=>$open_plu,
                'GrossPrice'=>0,
                'OrderItemId'=>0,
                'OpenName'=>0,
                'ParentPLU'=>0,
                'UnitPrice'=>0,
                'Quantity'=>1,
                'ItemName'=>$cart_green,
                'ItemType'=>1
            );
            array_push($array_items,$_green_array);
        }
        if(isset($cart_sp_instructions) and $cart_sp_instructions->ID!=null)
        {
             $_sp_array=array(
                    'ItemPlu'=>$open_plu,
                    'GrossPrice'=>0,
                    'OrderItemId'=>0,
                    'OpenName'=>0,
                    'ParentPLU'=>0,
                    'UnitPrice'=>0,
                    'Quantity'=>1,
                    'ItemName'=>$cart_sp_instructions->Title,
                    'ItemType'=>1
                );
                array_push($array_items,$_sp_array);


        }
        if(isset($cart_gift->GiftOpenItem) and $cart_gift->GiftOpenItem!=null)
        {
            $_gift_array_1=array(
                'ItemPlu'=>$open_plu,
                'GrossPrice'=>0,
                'OrderItemId'=>0,
                'OpenName'=>0,
                'ParentPLU'=>0,
                'UnitPrice'=>0,
                'Quantity'=>1,
                'ItemName'=>$cart_gift->GiftFrom,
                'ItemType'=>1
            );
            $_gift_array_2=array(
                'ItemPlu'=>$open_plu,
                'GrossPrice'=>0,
                'OrderItemId'=>0,
                'OpenName'=>0,
                'ParentPLU'=>0,
                'UnitPrice'=>0,
                'Quantity'=>1,
                'ItemName'=>$cart_gift->GiftTo,
                'ItemType'=>1
            );
            $_gift_array_3=array(
                'ItemPlu'=>$open_plu,
                'GrossPrice'=>0,
                'OrderItemId'=>0,
                'OpenName'=>0,
                'ParentPLU'=>0,
                'UnitPrice'=>0,
                'Quantity'=>1,
                'ItemName'=>$cart_gift->GiftOpenItem,
                'ItemType'=>1
            );
            array_push($array_items,$_gift_array_1);
            array_push($array_items,$_gift_array_2);
            array_push($array_items,$_gift_array_3);
        }
        if(isset($cart_vouchers['Id']) and $cart_vouchers['Id']!=null)
        {
            if($cart_vouchers['ValueType']=='percentage')
            {
                $discount=$_total*$cart_vouchers['Value']/100;
            }
            elseif($cart_vouchers['ValueType']=='flat_rate')
            {
                $discount=$cart_vouchers['Value'];
            }

            $_v=array(
                'Settlement'=>$discount,
                'Currency'=>$currency,
                'Category'=>'Vouchers',
                'PaymentTypeId'=>255
            );
            array_push($array_payments,$_v);
        }
        if(isset($cart_payment->PaymentId) and $cart_payment->PaymentId!=null)
        {
            $_payments=array(
                'Settlement'=>$_total-$discount,
                'Currency'=>$currency,
                'Category'=>$cart_payment->Name,
                'PaymentTypeId'=>$cart_payment->POSCode
            );
            array_push($array_payments,$_payments);
        }

        $post_array['token']=$s_org->token;
        $post_array['organization_id']=$s_org->id;
        $post_array['channel_id']=1;
        $post_array['LoyaltyId']=session()->get('loyalty_id');
        $post_array['OrderId']='';
        $post_array['OrderDate']=$datatime;
        $post_array['CardNumber']='';
        $post_array['TotalPrice']=$_total;
        $post_array['DepartmentId']=0;
        $post_array['DepartmentName']='Default';


        $post_array['AddressId']=$cart_info->AddressId;
        $post_array['FirstName']=$cart_info->FirstName;
        $post_array['LastName']=$cart_info->LastName;
        $post_array['Mobile']=$cart_info->Mobile;
        $post_array['Line1']=$cart_info->Line1;
        $post_array['Line2']=$cart_info->Line2;
        $post_array['City']=$cart_info->City;
        $post_array['Province']=$cart_info->Province;
        $post_array['Apartment']=$cart_info->Apartment;
        $post_array['XLocation']=$cart_info->XLocation;
        $post_array['YLocation']=$cart_info->YLocation;
        $post_array['AddressType']=$cart_info->AddressType;
        $post_array['Instructions']='';
        $post_array['PaymentType']='cash';
        $post_array['Status']='pending';
        $post_array['DeliveryCharge']=$delivery_charge;
        $post_array['paymentParts']=$array_payments;
        $post_array['Items']=$array_items;

        $url=env('BASE_URL').'orders/Save';
        $query=Helper::postApi($url,$post_array);
        return $query;
    }

}
