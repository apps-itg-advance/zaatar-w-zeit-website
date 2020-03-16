<?php

namespace App\Http\Controllers;

use App\Model\Checkout;
use Illuminate\Http\Request;
use App\Extensions\MongoSessionHandler;
use Illuminate\Support\Facades\Session;
use App\Http\Libraries\SettingsLib;
use App\Http\Libraries\OrdersLibrary;
use App\Http\Libraries\CustomerLibrary;

class CheckoutController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->query=SettingsLib::GetDeliveryScreenDataSteps();
        $this->skey = session()->get('skey');
        view()->composer('*', function ($view) {
            $view->with('delivery_info',$this->query);
            $view->with('skey',$this->skey);
        });
    }

    public function index()
    {
        $cart = Session::get('cart');
        $class_css='checkout-wrapper';
        $_active_css='';
        return view('checkouts.summary',compact('cart','class_css','_active_css'));  //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function address()
    {
        $skey = session()->get('skey');
        $cart = Session::get('cart');
        $addresses = session()->get('addresses'.$skey) ?? [];
        $cities=SettingsLib::GetCities();
        //dump($addresses);
       //die;
	    $_active_css='';
        $class_css='checkout-wrapper';
        return view('checkouts.address',compact('cart','class_css','_active_css','addresses','skey','cities'));  //
    }
    public function address_store(Request $request)
    {
        $skey = session()->get('skey');
        $query=session()->has('user'.$skey) ? session()->get('user'.$skey):array();
        $details=$query->details;
        $address=$request->input('data');
        $_data=json_decode($address);
        $array=array(
            'FirstName'=>$details->FirstName,
            'LastName'=>$details->LastName,
            'Mobile'=>$details->FullMobile,
            'AddressId'=>$_data->ID,
            'Line1'=>$_data->Line1,
            'Line2'=>$_data->Line2,
            'Apartment'=>$_data->AptNumber,
            'XLocation'=>$_data->XLocation,
            'YLocation'=>$_data->YLocation,
            'AddressType'=>$_data->TypeID,
            'City'=>$_data->CityName,
            'Province'=>$_data->ProvinceName
        );

        session()->forget('cart_info');
        session()->save();
        session()->put('cart_info',(object)$array);
        return 'true';

    }
    public function wallet()
    {
        $skey=session()->get('skey');
        $user=session()->get('user'.$skey);
        $loyalty_id=$user->details->LoyaltyId;
        $wallet_balance=$user->details->WalletAmountBalance;
        $vouchers=CustomerLibrary::GetVouchers(['LoyaltyId'=>$loyalty_id]);
        session()->put('vouchers',$vouchers);
       // dump($vouchers);
       /* for ($i=0;$i<count($v);$i++)
        {
            $qty=1;
            $array_exp=array($v[$i]->ExpiryDate=>1);
            $array_ids=array($v[$i]->Id);
            if($i>1)
            {
                for ($j=$i+1;$j<count($v);$j++)
                {
                    if($v[$i]->Value==$v[$j]->Value and $v[$i]->ValueType==$v[$j]->ValueType)
                    {
                        $qty++;
                        if(isset($array_exp[$v[$j]->ExpiryDate]))
                        {
                            $array_exp[$v[$j]->ExpiryDate]=$array_exp[$v[$j]->ExpiryDate]+1;
                        }
                        else{
                            $array_exp[$v[$j]->ExpiryDate]=1;
                        }
                        $array_ids[]=$v[$j]->Id;
                        $array_keys[]=$v[$j]->Id;
                    }


                }
            }

            if(!in_array($v[$i]->Id,$array_keys))
            {
                $array_keys[]=$v[$i]->Id;
                array_push($vouchers,array('Id'=>$v[$i]->Id,'Qty'=>$qty,'Value'=>$v[$i]->Value,'ValueType'=>$v[$i]->ValueType,'Category'=>$v[$i]->Category,'ExpiryDates'=>$array_exp,'Ids'=>$array_keys));

            }

        }
       */
        $cart = Session::get('cart');
	    $_active_css='address';
        $class_css='checkout-wrapper';
        return view('checkouts.wallet',compact('cart','class_css','_active_css','vouchers','wallet_balance'));  //
        //return view('checkouts.test',compact('cart','class_css','_active_css'));  //
    }
    public function gift()
    {
        $cart = Session::get('cart');
	    $_active_css='wallet';
	    $class_css='checkout-wrapper';
        return view('checkouts.gift',compact('cart','class_css','_active_css'));  //
        //return view('checkouts.test',compact('cart','class_css','_active_css'));  //
    }
    public function gift_store(Request $request)
    {
        $go=explode('-:',$request->input('gift_value'));
        $array=array(
            'OpenItemId'=>isset($go[1])?$go[1]:0,
            'OpenItemPlu'=>isset($go[2])?$go[2]:0,
            'GiftTo'=>$request->input('gift_to'),
            'GiftFrom'=>$request->input('gift_from'),
            'GiftOpenItem'=>isset($go[0])?$go[0]:''
        );

        session()->forget('cart_gift');
        session()->save();
        session()->put('cart_gift',(object)$array);
        return 'true';

    }
    public function gift_delete()
    {
        session()->forget('cart_gift');
        session()->save();
        return 'true';

    }
    public function loyalty_store(Request $request)
    {
        $voucher_id=$request->input('vid');
        $wallet_amount=$request->input('wallet_amount');
        $vouchers=session()->get('vouchers');
        $v=array();
        $s_vouchers=array();
        foreach ($vouchers as $voucher)
        {
            if($voucher->Id==$voucher_id)
            {
                $v=$voucher;
                break;
            }
        }
        if(is_object($v) and count((array)$v)>0)
        {
            $_v_cards=$v->Vouchers;
            if($v->VoucherType=='discount_check')
            {
                foreach ($_v_cards as $_v_card)
                {
                    if($_v_card->ExpiryDate>date('Y-m-d'));
                    {
                        $s_vouchers= $_v_card;
                        $s_vouchers->PLU=0;
                        break;
                    }
                }
            }
            elseif($v->VoucherType=='free_item')
            {
                $cart=session()->get('cart');
                if(count($cart)>0)
                {
                    foreach ($cart as $c)
                    {
                        foreach ($_v_cards as $vcard)
                        {
                            if(count($vcard->FreeItems)>0)
                            {
                                $free_items=$vcard->FreeItems;
                                foreach ($free_items as $free_item)
                                {
                                 if($c['plu']==$free_item->PLU)
                                 {
                                     $s_vouchers=$vcard;
                                     $s_vouchers->PLU=$c['plu'];
                                 }
                                }
                            }
                        }
                    }
                }
            }
        }
        if(count((array)$s_vouchers)>0) {
            $array_voucher = array(
                'Id' => $s_vouchers->Id,
                'Value' => $s_vouchers->Value,
                'ValueType' => $s_vouchers->ValueType,
                'Category' => $s_vouchers->Category,
                'ItemPlu' => $s_vouchers->PLU,
                'ExpiryDate' => $s_vouchers->ExpiryDate,
            );
            session()->forget('cart_vouchers');
            session()->forget('cart_wallet');
            session()->save();
            session()->put('cart_wallet', $wallet_amount);
            session()->put('cart_vouchers', $array_voucher);
        }
        return 'true';
    }

    public function green()
    {
        $cart = Session::get('cart');
	    $_active_css='gift';
        $class_css='checkout-wrapper';
        return view('checkouts.green',compact('cart','class_css','_active_css'));  //
    }
    public function green_store(Request $request)
    {
        $green_array=explode('-:',$request->input('query'));
        $green_name=isset($green_array[0])?$green_array[0]:'';
        $green_id=isset($green_array[1])?$green_array[1]:'';
        $green_plu=isset($green_array[2])?$green_array[2]:'';
        $_array=array(
            'Id'=>$green_id,
            'Title'=>$green_name,
            'PLU'=>$green_plu,
        );
        session()->forget('cart_green');
        session()->save();
        session()->put('cart_green',$_array);
        return 'true';

    }

    public function payment()
    {
        $cart = Session::get('cart');
	    $_active_css='green';
        $class_css='checkout-wrapper';
        return view('checkouts.payment',compact('cart','class_css','_active_css'));  //
        //return view('checkouts.test',compact('cart','class_css','_active_css'));  //
    }
    public function payment_store(Request $request)
    {
        $query=$request->input('query');
        $_array=json_decode($query);
        session()->forget('cart_payment');
        session()->save();
        session()->put('cart_payment',$_array);
        return 'true';

    }
    public function special_instructions()
    {
        //dump(session()->all());
        $cart = Session::get('cart');
	    $_active_css='payment';
        $class_css='checkout-wrapper';
        return view('checkouts.special_instructions',compact('cart','class_css','_active_css'));  //
        //return view('checkouts.test',compact('cart','class_css','_active_css'));  //
    }
    public function special_instructions_store(Request $request)
    {

        $query=$request->input('query');
        $_array=json_decode($query);
        session()->forget('cart_sp_instructions');
        session()->save();
        session()->put('cart_sp_instructions',$_array);
        $cart=session()->get('cart');
        $cart_info=session()->get('cart_info');
        $cart_gift=session()->get('cart_gift');
        $cart_payment=session()->get('cart_payment');
        $cart_sp_instructions=session()->get('cart_sp_instructions');
        $cart_green=session()->get('cart_green');
        $cart_vouchers=session()->get('cart_vouchers');
        $cart_wallet=session()->get('cart_wallet');
        $_org=session()->get('_org');
        $delivery_charge=$_org->delivery_charge;
        $currency=$_org->currency;
       return view('checkouts._order_summary',compact('cart','cart_info','cart_gift','cart_payment','cart_sp_instructions','cart_green','delivery_charge','currency','cart_vouchers','cart_wallet'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
      $query=OrdersLibrary::SaveOrders();
        $cart=session()->get('cart');
        $cart_info=session()->get('cart_info');
        $cart_gift=session()->get('cart_gift');
        $cart_payment=session()->get('cart_payment');
        $cart_sp_instructions=session()->get('cart_sp_instructions');
        $cart_green=session()->get('cart_green');
        $cart_vouchers=session()->get('cart_vouchers');
        $cart_wallet=session()->get('cart_wallet');
        $_org=session()->get('_org');
        $delivery_charge=$_org->delivery_charge;
        $currency=$_org->currency;
//	    $_active_css='special_instructions';
      if($query->message=='success')
      {
          session()->forget('cart_sp_instructions');
          session()->forget('cart');
          session()->forget('cart_info');
          session()->forget('cart_gift');
          session()->forget('cart_payment');
          session()->forget('cart_green');
          session()->forget('cart_vouchers');
          session()->forget('cart_wallet');
          session()->forget('items_customized');
          session()->save();

      }
        return redirect(route('home.menu'));
        //return view('checkouts.order_response',compact('query','cart','cart_info','cart_gift','cart_payment','cart_sp_instructions','cart_green','delivery_charge','currency','cart_vouchers','cart_wallet'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Checkout  $checkout
     * @return \Illuminate\Http\Response
     */
    public function show(Checkout $checkout)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Checkout  $checkout
     * @return \Illuminate\Http\Response
     */
    public function edit(Checkout $checkout)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Checkout  $checkout
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Checkout $checkout)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Checkout  $checkout
     * @return \Illuminate\Http\Response
     */
    public function destroy(Checkout $checkout)
    {
        //
    }
}
