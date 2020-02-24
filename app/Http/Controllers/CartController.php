<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use App\Extensions\MongoSessionHandler;
use Illuminate\Support\Facades\Session;
use App\Http\Libraries\MenuLibrary;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cart = Session::get('cart');
        $menu=MenuLibrary::GetMenuItems('');
        return view('partials._cart',compact('cart','menu'));
    }
    public function cart_count()
    {
        if (session::has('cart')) {
            $cart = Session::get('cart');
            $_ctotal=count($cart);
        }
        else{
            $_ctotal=0;

        }
        return $_ctotal;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $item_id=$request->input('SItemId');
        $qty=$request->input('SQty');
        $items_name=$request->input('ItemsName');
        $items_plu=$request->input('ItemsPLU');
        $items_amounts=$request->input('TotalAmount');
        $_name=$items_name[$item_id];
        $_plu=$items_plu[$item_id];
        $_amounts=$items_amounts[$item_id];
        $modifiers=$request->input('modifiers'.$item_id);
        $cart = session()->get('cart');
        $_modifiers=array();
        if($qty==0)
        {
            $qty=$qty+1;
        }
        if($modifiers!=null) {
            foreach ($modifiers as $key => $value) {
                for ($i = 0; $i < count($value); $i++) {
                    $modifier_array = explode('-', $value[$i]);
                    array_push($_modifiers, ['id' => $modifier_array[0], 'plu' => $modifier_array[1], 'name' => $modifier_array[3], 'price' => $modifier_array[2]]);

                }
            }
        }
        if(!$cart) {
            $cart = [
                 [
                    'name' => $_name,
                    'quantity' => $qty,
                    'price' => $_amounts,
                    'plu' => $_plu,
                    'modifiers'=>$_modifiers
                ]
            ];

            session()->put('cart', $cart);
        }
        else{
            if(isset($cart[$item_id]))
            {
                unset($cart[$item_id]);

            }
            $cart[]= [
                'name' => $_name,
                'quantity' => $qty,
                'price' => $_amounts,
                'plu' => $_plu,
                'modifiers'=>$_modifiers
            ];
            session()->put('cart', $cart);
        }
        var_dump($cart);
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $cart = session()->get('cart');
        if(isset($cart[$id]))
        {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }
    }
    public function add_qty($id)
    {
        $_org=session()->get('_org');
        $cart = session()->get('cart');
        $qty=0;
        $total=0;
        if(isset($cart[$id]))
        {
            $item=$cart[$id];
            $qty=$item['quantity']+1;
            $item['quantity']=$qty;

            session()->put('cart.'.$id, $item);
        }
        $cart_n=session()->get('cart');
        foreach ($cart_n as $v)
        {
            $total+=($v['price']*$v['quantity']);
        }
        $total=($total+$_org->delivery_charge).''.$_org->currency;
        return json_encode(array('qty'=>$qty,'total'=>$total));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function edit($key)
    {
        $cart = session()->get('cart');
        $item=$cart[$key];
        $plu=$item['plu'];
        $query=MenuLibrary::GetMenuItems('');
        $menu=$query->data;
        $row=array();
        foreach ($menu as $m)
        {
            if($m->PLU==$plu)
            {
                $row=$m;
                break;
            }
        }
        return view('carts.edit',compact('item','row','key'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        $item_id=$request->input('item_id');
        $key_item=$request->input('key');
        $qty=$request->input('qty');
        $_name=$request->input('item_name');
        $_plu=$request->input('plu');
        $_amounts=$request->input('amount');
        $modifiers=$request->input('modifiers');
      //  echo $key_item;
        $_modifiers=array();
        if($qty==0)
        {
            $qty=$qty+1;
        }
        if($modifiers!=null) {
            foreach ($modifiers as $key => $value) {
                for ($i = 0; $i < count($value); $i++) {
                    $modifier_array = explode('-', $value[$i]);
                    array_push($_modifiers, ['id' => $modifier_array[0], 'plu' => $modifier_array[1], 'name' => $modifier_array[3], 'price' => $modifier_array[2]]);

                }
            }
        }

        $cart = session()->get('cart');
       // dump($cart);
      //  dump($cart[$key_item]);
        unset($cart[$key_item]);

        $cart[$key_item]=[
            'name' => $_name,
            'quantity' => $qty,
            'price' => $_amounts,
            'plu' => $_plu,
            'modifiers'=>$_modifiers
        ];
       // dump($cart);

        session()->forget('cart');
        session()->save();
        session()->put('cart', $cart);
       // dump(session()->get('cart'));
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        session()->forget('cart');
        session()->save();
    }
}
