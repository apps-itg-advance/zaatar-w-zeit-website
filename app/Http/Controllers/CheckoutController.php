<?php

namespace App\Http\Controllers;

use App\Model\Checkout;
use Illuminate\Http\Request;
use App\Extensions\MongoSessionHandler;
use Illuminate\Support\Facades\Session;
use App\Http\Libraries\SettingsLib;

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
        $cart = Session::get('cart');
        $_active_css='wallet';
        $class_css='checkout-wrapper';
        return view('checkouts.wallet',compact('cart','class_css','_active_css'));  //
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
        $_org=session()->get('_org');
        $delivery_charge=$_org->delivery_charge;
        $currency=$_org->currency;
       return view('checkouts._order_summary',compact('cart','cart_info','cart_gift','cart_payment','cart_sp_instructions','cart_green','delivery_charge','currency'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
