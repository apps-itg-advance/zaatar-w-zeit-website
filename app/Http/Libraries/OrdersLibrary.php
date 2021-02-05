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

class OrdersLibrary
{
    public static function SaveOrders($checkoutInfo)
    {
        $Skey = session()->get('skey');
        $_org = session()->get('_org');
        $user = session()->has('user' . $Skey) ? session()->get('user' . $Skey) : array();
        $timezone = $_org->timezone != null ? $_org->timezone : 'Asia/Beirut';
        $open_plu = isset($_org->open_plu) and $_org->open_plu != null ? $_org->open_plu : '6969';
        $datetime = Carbon::now($timezone)->toDateTimeString();

        $cartItems = session()->get('cart');

        $addressInfo = [];
        $walletInfo = [];
        $giftInfo = [];
        $greenInfo = [];
        $instructionsInfo = [];
        $paymentInfo = [];

        foreach ($checkoutInfo as $info) {
            if ($info['key'] == 'Addresses') {
                $addressInfo = $info;
            } else if ($info['key'] == 'Wallet') {
                $walletInfo = $info;
            } else if ($info['key'] == 'RealGreen') {
                $greenInfo = $info;
            } else if ($info['key'] == 'SpecialInstructions') {
                $instructionsInfo = $info;
            } else if ($info['key'] == 'PaymentMethods') {
                $paymentInfo = $info;
            } else if ($info['key'] == 'Gift') {
                $giftInfo = $info;
            } else {
            }
        }


        $deliveryCharge = $_org->delivery_charge;
        $currency = $_org->currency;
        $arrayItems = [];
        $arrayPayments = [];
        $total = $deliveryCharge;
        $discount = 0;
        foreach ($cartItems as $cartItem) {
            if (isset($cartItem->Components)) {
                foreach ($cartItem->Components as $component) {
                    if ($component->IsMain == "1") {
                        foreach ($component->AppliedItems as $appliedItem) {
                            $item = array(
                                'ItemPlu' => $appliedItem->PLU,
                                'GrossPrice' => $cartItem->Price,
                                'OrderItemId' => $appliedItem->ID,
                                'OpenName' => 0,
                                'ParentPLU' => 0,
                                'UnitPrice' => $cartItem->Price,
                                'Quantity' => 1,
                                'ItemName' => $appliedItem->Name,
                                'ItemType' => 1
                            );
                            array_push($arrayItems, $item);
                        }
                    }
                }

                foreach ($cartItem->AppliedModifiers as $modifier) {
                    $appliedModifiers = array(
                        'ItemPlu' => $modifier->PLU,
                        'GrossPrice' => $modifier->Price,
                        'OrderItemId' => $modifier->ID,
                        'OpenName' => 0,
                        'ParentPLU' => $cartItem->PLU,
                        'UnitPrice' => $modifier->Price,
                        'Quantity' => 1,
                        'ItemName' => $modifier->ModifierName,
                        'ItemType' => 3
                    );
                    $total += $modifier->Price;
                    array_push($arrayItems, $appliedModifiers);
                }


                foreach ($cartItem->Components as $component) {
                    if ($component->IsMain == "0") {
                        foreach ($component->AppliedItems as $appliedItem) {
                            $item = array(
                                'ItemPlu' => $appliedItem->PLU,
                                'GrossPrice' => $cartItem->Price,
                                'OrderItemId' => $appliedItem->ID,
                                'OpenName' => 0,
                                'ParentPLU' => 0,
                                'UnitPrice' => $cartItem->Price,
                                'Quantity' => 1,
                                'ItemName' => $appliedItem->Name,
                                'ItemType' => 1
                            );
                            array_push($arrayItems, $item);
                        }
                    }
                }
                $total += $cartItem->Price;
            } else {
                $item = array(
                    'ItemPlu' => $cartItem->PLU,
                    'GrossPrice' => $cartItem->Price,
                    'OrderItemId' => $cartItem->ID,
                    'OpenName' => 0,
                    'ParentPLU' => 0,
                    'UnitPrice' => $cartItem->Price,
                    'Quantity' => 1,
                    'ItemName' => $cartItem->ItemName,
                    'ItemType' => 1
                );
                $total += $cartItem->Price;
                array_push($arrayItems, $item);
                foreach ($cartItem->AppliedModifiers as $modifier) {
                    $appliedModifiers = array(
                        'ItemPlu' => $modifier->PLU,
                        'GrossPrice' => $modifier->Price,
                        'OrderItemId' => $modifier->ID,
                        'OpenName' => 0,
                        'ParentPLU' => $cartItem->PLU,
                        'UnitPrice' => $modifier->Price,
                        'Quantity' => 1,
                        'ItemName' => $modifier->ModifierName,
                        'ItemType' => 3
                    );
                    $total += $modifier->Price;
                    array_push($arrayItems, $appliedModifiers);
                }
            }

            if (isset($cartItem->AppliedMeal->AppliedItems) && count($cartItem->AppliedMeal->AppliedItems) > 0) {
                $mealHeader = array(
                    'ItemPlu' => $cartItem->AppliedMeal->PLU,
                    'GrossPrice' => $cartItem->AppliedMeal->Price,
                    'OrderItemId' => $cartItem->AppliedMeal->ID,
                    'OpenName' => 0,
                    'ParentPLU' => $cartItem->AppliedMeal->PLU,
                    'UnitPrice' => $cartItem->AppliedMeal->Price,
                    'Quantity' => 1,
                    'ItemName' => $cartItem->AppliedMeal->Title,
                    'ItemType' => 5,
                );
                array_push($arrayItems, $mealHeader);
                foreach ($cartItem->AppliedMeal->AppliedItems as $appliedItem) {
                    $mealItem = array(
                        'ItemPlu' => $appliedItem->PLU,
                        'GrossPrice' => $appliedItem->Price,
                        'OrderItemId' => $appliedItem->ID,
                        'OpenName' => 0,
                        'ParentPLU' => $cartItem->AppliedMeal->PLU,
                        'UnitPrice' => $appliedItem->Price,
                        'Quantity' => 1,
                        'ItemName' => $appliedItem->Name,
                        'ItemType' => 5,
                    );
                    array_push($arrayItems, $mealItem);
                }
                $total += $cartItem->AppliedMeal->Price;
            }
        }

        if (count($greenInfo) > 0 && count(get_object_vars($greenInfo['green_option'])) > 0) {
            $greenData = array(
                'ItemPlu' => $greenInfo['green_option']->PLU,
                'GrossPrice' => 0,
                'OrderItemId' => $greenInfo['green_option']->ID,
                'OpenName' => 1,
                'ParentPLU' => 0,
                'UnitPrice' => 0,
                'Quantity' => 1,
                'ItemName' => $greenInfo['green_option']->Title,
                'ItemType' => 1
            );
            array_push($arrayItems, $greenData);
        }


        if (count($instructionsInfo) > 0 && isset($instructionsInfo['instructions'])) {
            foreach ($instructionsInfo['instructions'] as $instruction) {
                $instructionData = array(
                    'ItemPlu' => $instruction->PLU,
                    'GrossPrice' => 0,
                    'OrderItemId' => 0,
                    'OpenName' => 1,
                    'ParentPLU' => $instruction->PLU,
                    'UnitPrice' => 0,
                    'Quantity' => 1,
                    'ItemName' => $instruction->Title,
                    'ItemType' => 1
                );
                array_push($arrayItems, $instructionData);
            }
        }


        if (count($giftInfo) > 0 && count(get_object_vars($giftInfo['gift_option'])) > 0) {
            $giftData1 = array(
                'ItemPlu' => $giftInfo['gift_option']->PLU,
                'GrossPrice' => 0,
                'OrderItemId' => $giftInfo['gift_option']->ID,
                'OpenName' => 1,
                'ParentPLU' => 0,
                'UnitPrice' => 0,
                'Quantity' => 1,
                'ItemName' => 'Gift From ' . $giftInfo['gift_from'],
                'ItemType' => 1
            );
            $giftData2 = array(
                'ItemPlu' => $giftInfo['gift_option']->PLU,
                'GrossPrice' => 0,
                'OrderItemId' => $giftInfo['gift_option']->ID,
                'OpenName' => 1,
                'ParentPLU' => 0,
                'UnitPrice' => 0,
                'Quantity' => 1,
                'ItemName' => 'Gift To ' . $giftInfo['gift_to'],
                'ItemType' => 1
            );
            $giftData3 = array(
                'ItemPlu' => $giftInfo['gift_option']->PLU,
                'GrossPrice' => 0,
                'OrderItemId' => $giftInfo['gift_option']->ID,
                'OpenName' => 1,
                'ParentPLU' => 0,
                'UnitPrice' => 0,
                'Quantity' => 1,
                'ItemName' => $giftInfo['gift_option']->Title,
                'ItemType' => 1
            );
            array_push($arrayItems, $giftData1);
            array_push($arrayItems, $giftData2);
            array_push($arrayItems, $giftData3);
        }


        if (count($walletInfo) > 0 && isset($walletInfo['amount'])) {
            if ($walletInfo['amount'] > 0) {
                $walletData = array(
                    'Settlement' => $walletInfo['amount'],
                    'Currency' => $currency,
                    'Category' => 'wallet',
                    'PaymentTypeId' => session()->get('cart_wallet_id'),
                    'ConfirmationCode' => 0
                );
                array_push($arrayPayments, $walletData);
            }
        }

        if (count($paymentInfo) > 0 && $paymentInfo['payment_method']) {
            if (isset($paymentInfo['currency']) && count(get_object_vars($paymentInfo['currency'])) > 0) {
                $currency = $paymentInfo['currency']->Currency;
            }
            $payments = [
                'Settlement' => $total - $discount,
                'Currency' => $currency,
                'Category' => $paymentInfo['payment_method']->Name,
                'SaveCard' => isset($paymentInfo['new_card']) ? $paymentInfo['new_card'] : '0',
                'PaymentTypeId' => $paymentInfo['payment_method']->POSCode
            ];
            if (isset($paymentInfo['card']) && count(get_object_vars($paymentInfo['card'])) > 0 && $paymentInfo['payment_method']->Name == 'credit') {
                $payments['CardToken'] = $paymentInfo['card']->Token;
            }
            array_push($arrayPayments, $payments);
        }

        $post_array['token'] = session()->get('token');
        $post_array['OrderId'] = '';
        $post_array['OrderDate'] = $datetime;
        $post_array['CardNumber'] = '';
        $post_array['TotalPrice'] = $total;
        $post_array['DepartmentId'] = 0;
        $post_array['DepartmentName'] = 'Default';
        $post_array['AddressId'] = $addressInfo['address']->ID;
        $post_array['FirstName'] = $user->details->FirstName;
        $post_array['LastName'] = $user->details->LastName;
        $post_array['Mobile'] = $user->details->FullMobile;
        $post_array['Line1'] = $addressInfo['address']->Line1;
        $post_array['Line2'] = $addressInfo['address']->Line2;
        $post_array['City'] = $addressInfo['address']->CityName;
        $post_array['Province'] = $addressInfo['address']->ProvinceCountry;
        $post_array['Apartment'] = $addressInfo['address']->AptNumber;
        $post_array['XLocation'] = '';
        $post_array['YLocation'] = '';
        $post_array['AddressType'] = $addressInfo['address']->TypeID;
        $post_array['CompanyName'] = $addressInfo['address']->CompanyName;
        $post_array['Instructions'] = '';
        $post_array['PaymentType'] = $paymentInfo['payment_method']->Name;
        $post_array['Status'] = 'pending';
        $post_array['DeliveryCharge'] = $deliveryCharge;
        $post_array['paymentParts'] = $arrayPayments;
        $post_array['Items'] = $arrayItems;
        $post_array['ReferralSource'] = 'web';
        $post_array['OnlineCurrency'] = $currency;
        if ($addressInfo['scheduled'] == '1') {
            $post_array['ScheduleOrder'] = $addressInfo['scheduled_on'];
            $post_array['OrderDate'] = $addressInfo['scheduled_at'];
        }
        return $post_array;
        $url = env('BASE_URL') . 'orders/Save';
        $query = Helper::postApi($url, $post_array);
        return $query;
    }
}
