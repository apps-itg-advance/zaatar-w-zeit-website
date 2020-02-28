<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use App\Models\Cart;
use Illuminate\Http\Request;
use App\Extensions\MongoSessionHandler;
use Illuminate\Support\Facades\Session;
use App\Http\Libraries\MenuLibrary;

class CartController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cart = Session::get('cart');
        //$menu=MenuLibrary::GetMenuItems('');
        $menu=array();
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $item_id=$request->input('SItemId');
        $qty=$request->input('SQty');
        $item_modify=$request->input('ItemModify');
        $items_name=$request->input('ItemsName');
        $items_plu=$request->input('ItemsPLU');
        $items_amounts=$request->input('TotalAmount');
        $_name=$items_name[$item_id];
        $_plu=$items_plu[$item_id];
        $_amounts=$items_amounts[$item_id];
        $modifiers=$request->input('modifiers'.$item_id);
        $make_meal=$request->input('make_meal');

        $cart = session()->get('cart');
        $_modifiers=array();
        $_make_meal=array();
        if($qty==0)
        {
            $qty=$qty+1;
        }
        $qty=1;
        if($modifiers!=null) {
            foreach ($modifiers as $key => $value) {
                for ($i = 0; $i < count($value); $i++) {
                    $modifier_array = explode('-', $value[$i]);
                    array_push($_modifiers, ['id' => $modifier_array[0], 'plu' => $modifier_array[1], 'name' => $modifier_array[3], 'price' => $modifier_array[2]]);

                }
            }
        }
        if($make_meal!=null) {
            foreach ($make_meal as $key1 => $value1) {
                $_mk=$value1['Items'];
                $_mk2=$value1['Title'];
                $_mk2_array=explode('-',$_mk2);
                $_amounts=$_amounts+$_mk2_array[1];
                $_make_meal['id']=$_mk2_array[0];
                $_make_meal['price']=$_mk2_array[1];
                $_make_meal['name']=$_mk2_array[2];
                $_itm=array();
                foreach ($_mk as $ky=>$vl) {

                    $meal_array = explode('-', $vl);
                    array_push($_itm, ['id' => $meal_array[0], 'plu' => $meal_array[1], 'name' => $meal_array[3], 'details' => $meal_array[2], 'price' => 0]);

                }
                $_make_meal['items']=$_itm;
            }
        }
        if(!$cart) {
            $cart = [
                 [
                    'name' => $_name,
                    'quantity' => $qty,
                    'price' => $_amounts,
                    'plu' => $_plu,
                    'item_modify' => $item_modify,
                    'modifiers'=>$_modifiers,
                    'meal'=>$_make_meal
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
                'item_modify' => $item_modify,
                'modifiers'=>$_modifiers,
                'meal'=>$_make_meal
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
    public function delete_meal($id)
    {
        $cart = session()->get('cart');

        if(isset($cart[$id]))
        {

            $new_total=$cart[$id]['price']-$cart[$id]['meal']['price'];
            unset($cart[$id]['meal']);
            $cart[$id]['price']=$new_total;
            session()->put('cart', $cart);
        }
    }
    public function copy_item($id)
    {
        $cart = session()->get('cart');
        if(isset($cart[$id]))
        {
            $cart[]=$cart[$id];
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
        $make_meal=$request->input('make_meal');
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
        $_make_meal=array();
        if($make_meal!=null) {
            foreach ($make_meal as $key1 => $value1) {
                $_mk2=$value1['Title'];

                $_mk=$value1['Items'];

                $_mk2_array=explode('-',$_mk2);
                $_amounts=$_amounts+$_mk2_array[1];
                $_make_meal['id']=$_mk2_array[0];
                $_make_meal['price']=$_mk2_array[1];
                $_make_meal['name']=$_mk2_array[2];
                $_itm=array();
                foreach ($_mk as $ky=>$vl) {

                    $meal_array = explode('-', $vl);
                    array_push($_itm, ['id' => $meal_array[0], 'plu' => $meal_array[1], 'name' => $meal_array[3], 'details' => $meal_array[2], 'price' => 0]);

                }
                $_make_meal['items']=$_itm;
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
            'modifiers'=>$_modifiers,
            'meal'=>$_make_meal
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
