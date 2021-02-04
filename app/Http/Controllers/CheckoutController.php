<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Libraries\SettingsLib;
use App\Http\Libraries\OrdersLibrary;
use App\Http\Libraries\CustomerLibrary;
use Illuminate\Support\Carbon;

class CheckoutController extends Controller
{
    public function __construct()
    {
        if (!session()->has('is_login') or session()->get('is_login') == false) {
            return redirect(route('auth.login'));
        }
        $this->query = SettingsLib::GetDeliveryScreenDataSteps(true);
        $this->Steps = array();
        $i = 1;
        foreach ($this->query->Steps as $row) {
            $this->Steps[$i] = $row;
            $this->Steps[$i]->step = $i;
            $stepVar = '';
            $cssClass = '';
            if ($row->ArrayName === "Addresses") {
                $stepVar = 'address';
                $cssClass = '_css_address_active';
            } else if ($row->ArrayName === "Wallet") {
                $stepVar = 'wallet';
                $cssClass = '_css_wallet_active';
            } else if ($row->ArrayName === "Gift") {
                $stepVar = 'gift';
                $cssClass = '_css_gift_active';
            } else if ($row->ArrayName === "RealGreen") {
                $stepVar = 'green';
                $cssClass = '_css_green_active';
            } else if ($row->ArrayName === "SpecialInstructions") {
                $stepVar = 'special_instructions';
                $cssClass = '_css_special_ins_active';
            } else if ($row->ArrayName === "PaymentMethods") {
                $stepVar = 'payment';
                $cssClass = '_css_payment_active';
            }
            $this->Steps[$i]->LocalStepName = $stepVar;
            $this->Steps[$i]->CssClass = $cssClass;
            $i++;
        }
        $i = 1;
        foreach ($this->query->Steps as $row) {
            $nextRoute = '';
            $nextRouteObj = null;
            if ($i !== count($this->query->Steps)) {
                $nextRoute = $this->Steps[$i + 1]->LocalStepName;
                $nextRouteObj = $this->Steps[$i + 1];
            }
            $this->Steps[$i]->NextRoute = $nextRoute;
            $this->Steps[$i]->NextRouteObj = $nextRouteObj;
            $i++;
        }
        $this->level_id = '';
        session()->put('checkoutSteps', $this->Steps);
        $this->skey = session()->get('skey');
        if ($this->skey != '') {
            $key = 'user' . $this->skey;
            $user = session()->has($key) ? session()->get($key) : array();
            $this->level_id = (isset($user->details->LevelId) and !is_null($user->details->LevelId)) ? $user->details->LevelId : '';
        }
        view()->composer('*', function ($view) {
            $view->with('delivery_info', $this->query);
            $view->with('skey', $this->skey);
            $view->with('page_title', 'Checkout');
            $view->with('LEVEL_ID', $this->level_id);
        });
    }

    public function index(Request $request)
    {
        $Skey = session()->get('skey');
        $user = session()->has('user' . $Skey) ? session()->get('user' . $Skey) : array();
        $checkoutData = $this->query;
        $steps = $this->Steps;
        $step = null;
        $requestedStep = $request->has('step') ? $request->input('step') : null;
        foreach ($this->Steps as $myStep) {
            if ($myStep->ArrayName == $requestedStep) {
                $step = $myStep;
            }
        }

        if (($requestedStep == null || $step == null) && ($requestedStep != 'CreditCards')) {
            return redirect(route('checkout.index', ['step' => 'Addresses']));
        }

        $checkoutInfo = session()->has('checkout_info_' . $requestedStep) ? session()->get('checkout_info_' . $requestedStep) : (object)[];

        $selectedSteps = [];
        foreach ($steps as $s) {
            if ($s->ArrayName == $requestedStep) {
                break;
            }
            array_push($selectedSteps, $s->ArrayName);
        }
        $cart = session()->get('cart');
        //$timezone = (isset($org->timezone) and $org->timezone != '') ? $org->timezone : 'Asia/Beirut';
        return view('checkouts.index', compact('cart', 'checkoutData', 'steps', 'selectedSteps', 'step', 'user', 'checkoutInfo'));
    }

    public function confirmStep(Request $request)
    {
        $key = $request->key;
        if (session()->has('checkout_info_' . $key)) {
            session()->forget('checkout_info_' . $key);
        }
        $data = [];
        foreach ($request->request as $property => $value) {
            $obj = $value;
            if (!is_array($value)) {
                $obj = json_decode($value);
            }
            if (is_object($obj)) {
                $data[$property] = $obj;
            } else if (is_array($value)) {
                $finalDataArray = [];
                $dataArray = $value;
                foreach ($dataArray as $d) {
                    array_push($finalDataArray, json_decode($d));
                }
                $data[$property] = $finalDataArray;
            } else {
                $data[$property] = $value;
            }
        }
        session()->put('checkout_info_' . $key, $data);
        return response()->json(session()->get('checkout_info_' . $key));
    }

    public function info()
    {
        $cartItems = session()->get('cart');
        $checkoutInfo = [];
        foreach ($this->Steps as $myStep) {
            if (session()->has('checkout_info_' . $myStep->ArrayName)) {
                array_push($checkoutInfo, session()->get('checkout_info_' . $myStep->ArrayName));
            }
        }
        $data = [
            'cart_items' => $cartItems,
            'checkout_info' => $checkoutInfo,
        ];
        return response()->json($data);
    }

    public function submitOrder()
    {
        $checkoutInfo = [];
        foreach ($this->Steps as $myStep) {
            if (session()->has('checkout_info_' . $myStep->ArrayName)) {
                array_push($checkoutInfo, session()->get('checkout_info_' . $myStep->ArrayName));
            }
        }
        $response = OrdersLibrary::SaveOrders($checkoutInfo);
//        return response()->json($response);

        $status = 'error';
        $msg = 'Error while submitting the order';
        $url = 'home';

        if (isset($response->PaymentURL) && $response->PaymentURL != null) {
            $url = 'payment';
            session()->put('onlinePaymentUrl', $response->PaymentURL);
        }

        if ($response->message == 'success') {
            $status = 'success';
            $msg = 'Order Inserted';
            session()->forget('cart');
            foreach ($this->Steps as $myStep) {
                if (session()->has('checkout_info_' . $myStep->ArrayName)) {
                    session()->forget('checkout_info_' . $myStep->ArrayName);
                    session()->save();
                }
            }
        }

        $data = [
            'url' => $url,
            'status' => $status,
            'message' => $msg,
            'OrderId' => (isset($response->OrderId) ? $response->OrderId : 0)
        ];
        return response()->json($data);
    }

    public function getDateTime()
    {
        $time = 'error';
        $org = session()->get('_org');
        if (isset($org->timezone)) {
            $current_date = Carbon::now($org->timezone);
            $time = $current_date->format('H:i:s');
        }
        echo $time;
    }

    public function getAvailableScheduleDates(Request $request)
    {
        $times = [];
        $openTime = $request->open_time;
        $closeTime = $request->close_time;
        $scheduledOn = $request->scheduled_on;
        $eta = $request->eta;

        $time = '';
        $org = session()->get('_org');
        if (isset($org->timezone)) {
            $current_date = Carbon::now($org->timezone);
            $time = $current_date->format('H:i:s');
        }
        // $scheduleDate = $request->schedule_date;
        $scheduleDate = '';

        $now = strtotime($time);
        $open_time = strtotime($openTime);
        $close_time = strtotime($closeTime);
        $eta = $eta * 60;
        $selected = '';
        if ($scheduledOn == 'today') {
            $selected = '';
            $date = $current_date->format('Y-m-d');
            for ($i = strtotime('00:00:00'); $i <= $close_time + $eta; $i += 900) {
                if ($scheduleDate == $date . " " . date("H:i", $i)) {
                    $selected = 'selected';
                } else {
                    $selected = '';
                }
                if ($i > $now + $eta and $now >= $open_time) {
                    $t1 = [
                        'label' => date("H:i", $i),
                        'value' => $date . " " . date("H:i", $i)
                    ];
                    array_push($times, (object)$t1);
                } else if ($i > $open_time + $eta and $now < $open_time) {
                    $t2 = [
                        'label' => date("H:i", $i),
                        'value' => $date . " " . date("H:i", $i),
                    ];
                    array_push($times, (object)$t2);
                }
            }
        } elseif ($scheduledOn == 'tomorrow') {
            $selected = '';
            $date = date('Y-m-d', strtotime($current_date->format('Y-m-d') . "+1 days"));
            for ($i = strtotime('00:00:00') + $eta; $i <= $close_time + $eta; $i += 900) {
                if ($scheduleDate == $date . " " . date("H:i", $i)) {
                    $selected = 'selected';
                } else {
                    $selected = '';
                }
                if ($i >= $open_time + $eta) {
                    $t = [
                        'label' => date("H:i", $i),
                        'value' => $date . " " . date("H:i", $i)
                    ];
                    array_push($times, (object)$t);
                }
            }
        }

        return response()->json($times);
    }

    public function gift_store(Request $request)
    {
        $go = explode('-:', $request->input('gift_value'));
        $array = array(
            'OpenItemId' => isset($go[1]) ? $go[1] : 0,
            'OpenItemPlu' => isset($go[2]) ? $go[2] : 0,
            'GiftTo' => $request->input('gift_to'),
            'GiftFrom' => $request->input('gift_from'),
            'GiftOpenItem' => isset($go[0]) ? $go[0] : ''
        );
        session()->forget('cart_gift');
        session()->put('checkout_steps', 'gift');
        session()->save();
        session()->put('cart_gift', (object)$array);
        return 'true';
    }

    public function payment_card_save(Request $request)
    {
        $card = $request->input('card');
        $flag = false;
        session()->forget('cart_payment_token');
        session()->save();
        if ($card != '') {
            $cards = $this->query->GatewayToken;
            foreach ($cards as $crd) {
                if ($crd->Token == $card) {
                    $flag = true;
                    break;
                }
            }
            if ($flag) {
                session()->put('cart_payment_token', $card);
            }
        }
        if ($card != '' and $flag = false) {
            echo 'error';
        } else {
            $cart = session()->get('cart');
            $cart_info = session()->get('cart_info');
            $cart_gift = session()->get('cart_gift');
            $cart_payment = session()->get('cart_payment');
            $cart_sp_instructions = session()->get('cart_sp_instructions');
            $cart_green = session()->get('cart_green');
            $cart_vouchers = session()->get('cart_vouchers');
            $cart_wallet = session()->get('cart_wallet');
            $_org = session()->get('_org');
            $delivery_charge = $_org->delivery_charge;
            $currency = $_org->currency;
            $order_schedule = session()->get('order_schedule');
            $schedule_date = session()->get('schedule_date');
            return view('checkouts._order_summary', compact('cart', 'cart_info', 'cart_gift', 'cart_payment', 'cart_sp_instructions', 'cart_green', 'delivery_charge', 'currency', 'cart_vouchers', 'cart_wallet', 'order_schedule', 'schedule_date'));
        }
    }

    public function payment_online()
    {
        $url = session()->get('onlinePaymentUrl');
        return view('checkouts.payment_online', compact('url'));
    }

    public function flushSession()
    {
        //todo remove all checkout sessions and related to cart session
        return redirect('/customer/profile');
    }


    //todo to be remove or refactored
    public function loyalty_store(Request $request)
    {
        $voucher_id = $request->input('vid');
        $wallet_amount = $request->input('wallet_amount');
        $vouchers = session()->get('vouchers');
        $v = array();
        $s_vouchers = array();
        foreach ($vouchers as $voucher) {
            if ($voucher->Id == $voucher_id) {
                $v = $voucher;
                break;
            }
        }
        if (is_object($v) and count((array)$v) > 0) {
            $_v_cards = $v->Vouchers;
            if ($v->VoucherType == 'discount_check') {
                foreach ($_v_cards as $_v_card) {
                    if ($_v_card->ExpiryDate > date('Y-m-d')) ;
                    {
                        $s_vouchers = $_v_card;
                        $s_vouchers->PLU = 0;
                        break;
                    }
                }
            } elseif ($v->VoucherType == 'free_item') {
                $cart = session()->get('cart');
                if (count($cart) > 0) {
                    foreach ($cart as $c) {
                        foreach ($_v_cards as $vcard) {
                            if (count($vcard->FreeItems) > 0) {
                                $free_items = $vcard->FreeItems;
                                foreach ($free_items as $free_item) {
                                    if ($c['plu'] == $free_item->PLU) {
                                        $s_vouchers = $vcard;
                                        $s_vouchers->PLU = $c['plu'];
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        if (count((array)$s_vouchers) > 0) {
            $array_voucher = array(
                'Id' => $s_vouchers->Id,
                'Value' => $s_vouchers->Value,
                'ValueType' => $s_vouchers->ValueType,
                'Category' => $s_vouchers->Category,
                'ItemPlu' => $s_vouchers->PLU,
                'ExpiryDate' => $s_vouchers->ExpiryDate,
                'VParentId' => $voucher_id
            );
            session()->forget('cart_vouchers');
            session()->save();
            session()->put('cart_vouchers', $array_voucher);
        }
        if ($wallet_amount != '') {
            session()->forget('cart_wallet');
            session()->save();
            session()->put('cart_wallet', $wallet_amount);
        }
        session()->put('checkout_steps', 'wallet');
        session()->save();
        return 'true';
    }
}
