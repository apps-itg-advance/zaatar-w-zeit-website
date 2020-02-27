<?php

namespace App\Http\Controllers;

use App\Model\Checkout;
use Illuminate\Http\Request;
use App\Extensions\MongoSessionHandler;
use Illuminate\Support\Facades\Session;
use App\Http\Libraries\SettingsLib;
use App\Http\Libraries\OrdersLibrary;

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
        $addresses = session()->get('addresses'.$skey);
        $cities=SettingsLib::GetCities();
        //dump($addresses);
       //die;
        $_active_css='address';
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
        $v=$user->vouchers;
        $array_keys=array();
        $vouchers=array();
        for ($i=0;$i<count($v);$i++)
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
                array_push($vouchers,array('Qty'=>$qty,'Value'=>$v[$i]->Value,'ValueType'=>$v[$i]->ValueType,'ExpiryDates'=>$array_exp,'Ids'=>$array_keys));

            }

        }
        $cart = Session::get('cart');

        $_active_css='wallet';
        $class_css='checkout-wrapper';
        return view('checkouts.wallet',compact('cart','class_css','_active_css','vouchers'));  //
        //return view('checkouts.test',compact('cart','class_css','_active_css'));  //
    }
    public function gift()
    {
        $cart = Session::get('cart');
        $_active_css='gift';
        $class_css='checkout-wrapper';
        return view('checkouts.gift',compact('cart','class_css','_active_css'));  //
        //return view('checkouts.test',compact('cart','class_css','_active_css'));  //
    }
    public function gift_store(Request $request)
    {
        $array=array(
            'GiftTo'=>$request->input('gift_to'),
            'GiftFrom'=>$request->input('gift_from'),
            'GiftOpenItem'=>$request->input('gift_value')
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
        $query=$request->input('query');
        $_array=explode('-',$query);
        $type=$_array[1];
        $value=$_array[0];

        $skey=session()->get('skey');
        $user=session()->get('user'.$skey);
        $v=$user->vouchers;
        $voucher_id=0;
        for ($i=0;$i<count($v);$i++)
        {
            $voucher_id=$v[$i]->Id;
            if($i>1)
            {
                if($v[$i]->Value==$value and $v[$i]->ValueType==$type)
                {
                    for ($j=$i+1;$j<count($v);$j++)
                    {
                        if($v[$j]->Value==$value and $v[$j]->ValueType==$type) {
                            if($v[$j]->ExpiryDate < $v[$i]->ExpiryDate)
                            {
                                $voucher_id=$v[$j]->Id;
                            }
                        }
                    }
                    break;

                }
            }



        }
        if($voucher_id>0)
        $array_voucher=array(
            'Id'=>$voucher_id,
            'Value'=>$value,
            'ValueType'=>$type,
        );
        session()->forget('cart_vouchers');
        session()->save();
        session()->put('cart_vouchers',$array_voucher);
        return 'true';
    }

    public function green()
    {
        $cart = Session::get('cart');
        $_active_css='green';
        $class_css='checkout-wrapper';
        return view('checkouts.green',compact('cart','class_css','_active_css'));  //
        //return view('checkouts.test',compact('cart','class_css','_active_css'));  //
    }
    public function green_store(Request $request)
    {
        session()->forget('cart_green');
        session()->save();
        session()->put('cart_green',$request->input('query'));
        return 'true';

    }

    public function payment()
    {
        $cart = Session::get('cart');
        $_active_css='payment';
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
        $cart = Session::get('cart');
        $_active_css='special_instructions';
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
        $_org=session()->get('_org');
        $delivery_charge=$_org->delivery_charge;
        $currency=$_org->currency;
       return view('checkouts._order_summary',compact('cart','cart_info','cart_gift','cart_payment','cart_sp_instructions','cart_green','delivery_charge','currency','cart_vouchers'));
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
        $_org=session()->get('_org');
        $delivery_charge=$_org->delivery_charge;
        $currency=$_org->currency;
      if($query->message=='success')
      {
          session()->forget('cart_sp_instructions');
          session()->forget('cart');
          session()->forget('cart_info');
          session()->forget('cart_gift');
          session()->forget('cart_payment');
          session()->forget('cart_sp_instructions');
          session()->forget('cart_green');
          session()->forget('cart_vouchers');
          session()->save();
      }

        return view('checkouts.order_response',compact('query','cart','cart_info','cart_gift','cart_payment','cart_sp_instructions','cart_green','delivery_charge','currency','cart_vouchers'));


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
