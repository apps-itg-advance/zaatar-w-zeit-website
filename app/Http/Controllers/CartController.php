<?php

namespace App\Http\Controllers;

use App\Http\Libraries\SettingsLib;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends BaseController
{
    public function saveCart(Request $request)
    {
        try {
            $cartItems = json_decode($request->cart_items);
            session()->put('cart', $cartItems);
            $cart = session()->get('cart');
            return response()->json($cart);
        } catch (\Exception $e) {
            return response()->json($e);
        }
    }

    public function getCart()
    {
        $cart = session()->get('cart');
        return response()->json($cart);
    }

    public function clearCart()
    {
        $cart = session()->forget('cart');
        if (session()->has('is_login')) {
            $query = SettingsLib::GetDeliveryScreenDataSteps(true);
            foreach ($query->Steps as $myStep) {
                if (session()->has('checkout_info_' . $myStep->ArrayName)) {
                    session()->forget('checkout_info_' . $myStep->ArrayName);
                    session()->save();
                }
            }
        }
        return response()->json($cart);
    }

    public function index()
    {
        $cart = session()->get('cart');
        if (is_array($cart)) {
            ksort($cart);
        }
        $menu = array();
        $fav = request('fav');
        return view('partials._cart', compact('cart', 'menu', 'fav'));
    }

    public function cart_count()
    {
        if (session::has('cart')) {
            $cart = Session::get('cart');
            $_ctotal = count($cart);
        } else {
            $_ctotal = 0;
        }
        return $_ctotal;
    }


    public function store(Request $request)
    {
        $item_id = $request->input('ItemId');
        $quick_order = $request->input('QuickOrder' . $item_id);
        $qty = $request->input('SQty');
        $item_modify = $request->input('ItemModify');
        $_name = $request->input('ItemsName');
        $_plu = $request->input('ItemsPLU');
        $_amounts = $request->input('TotalAmounts');
        $origin_amount = $_amounts;
        //$_amounts=$items_amounts[$item_id];
        $modifiers = array();
        if ($quick_order == '1') {
            $make_meals = $request->input('make_meal');
        } else {
            $make_meals = $request->input('make_meal_d');
            $modifiers = $request->input('modifiers' . $item_id);
        }
        $cart = session()->get('cart');
        $_modifiers = array();
        $_make_meal = array();
        $qty = 1;
        if ($quick_order == '0') {
            if ($modifiers != null) {
                foreach ($modifiers as $key => $value) {
                    for ($i = 0; $i < count($value); $i++) {
                        $modifier_array = explode('-', $value[$i]);
                        $_amounts = $_amounts + $modifier_array[2];
                        array_push($_modifiers, ['id' => $modifier_array[0], 'plu' => $modifier_array[1], 'name' => $modifier_array[3], 'price' => $modifier_array[2]]);
                    }
                }
            }
        }
        if (isset($make_meals[$item_id])) {
            $make_meal = $make_meals[$item_id];
            if ($make_meal != null) {
                $_mk2 = $make_meal['Title'];
                $_mk2_array = explode('-', $_mk2);
                $_amounts = $_amounts + $_mk2_array[1];
                $_make_meal['id'] = $_mk2_array[0];
                $_make_meal['price'] = str_replace(',', '', $_mk2_array[1]);
                $_make_meal['name'] = $_mk2_array[2];
                $_make_meal['plu'] = isset($_mk2_array[3]) ? $_mk2_array[3] : 0;
                $_itm = array();
                if (isset($make_meal['Items'])) {
                    $_mk = $make_meal['Items'];
                    foreach ($_mk as $ky => $vl) {
                        $meal_array = explode('-', $vl);
                        array_push($_itm, ['id' => $meal_array[0], 'plu' => $meal_array[1], 'name' => $meal_array[3], 'details' => $meal_array[2], 'price' => 0]);
                    }
                }
                $_make_meal['items'] = $_itm;
            }
        }
        $cart[] = [
            'id' => $item_id,
            'name' => $_name,
            'quantity' => $qty,
            'price' => $_amounts,
            'origin_price' => $origin_amount,
            'plu' => $_plu,
            'item_modify' => $item_modify,
            'modifiers' => $_modifiers,
            'meal' => $_make_meal
        ];
        session()->put('cart', $cart);
        $qty = 0;
        $cart_n = session()->get('cart');
        for ($j = 0; $j < count($cart_n); $j++) {
            if ($cart[$j]['plu'] == $_plu) {
                $qty++;
            }
        }
        $custom = 0;
        if (isset($modifiers) and count($modifiers) > 0) {
            $items_customized[$_plu] = 1;
            session()->put('items_customized', $items_customized);
        }
        $customized = session()->get('items_customized');
        if (isset($customized[$_plu])) {
            $custom = 1;
        }
        echo $qty . '-' . $custom;
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Cart $cart
     * @return \Illuminate\Http\Response
     */
    public function RemoveCustomized($plu)
    {
        $flag = false;
        $items_customized = session()->get('items_customized');
        $cart = session()->get('cart');
        foreach ($cart as $crt) {
            if ($crt['plu'] == $plu) {
                $flag = true;
                break;
            }

        }
        if (!$flag) {
            unset($items_customized[$plu]);
            session()->forget('items_customized');
            session()->save();
            session()->put('items_customized', $items_customized);
        }
    }

    public function remove(Request $request)
    {
        $id = $request->input('id');
        $cart = session()->get('cart');

        if (isset($cart)) {
            for ($i = count($cart) - 1; $i >= 0; $i--) {
                if ($cart[$i]['plu'] == $id) {
                    unset($cart[$i]);

                    break;
                }
            }
            session()->forget('cart');
            session()->save();
            session()->put('cart', array_values($cart));

        }
        $qty = 0;
        $cart_n = session()->get('cart');
        for ($j = 0; $j < count($cart_n); $j++) {
            if ($cart_n[$j]['plu'] == $id) {
                $qty++;
            }
        }
        $custom = 0;
        self::RemoveCustomized($id);
        $customized = session()->get('items_customized');
        if (isset($customized[$id])) {
            $custom = 1;
        }
        echo $qty . '-' . $custom;
    }

    public function delete($id)
    {
        $cart = session()->get('cart');
        $item_id = '';
        $plu = 0;
        if (isset($cart[$id])) {
            $item_id = $cart[$id]['id'];
            $plu = $cart[$id]['plu'];
            unset($cart[$id]);
            session()->put('cart', array_values($cart));
        }
        $qty = 0;
        $cart_n = session()->get('cart');
        for ($j = 0; $j < count($cart_n); $j++) {
            if ($cart_n[$j]['id'] == $item_id) {

                $qty++;
            }
        }
        $custom = 0;
        self::RemoveCustomized($plu);
        $customized = session()->get('items_customized');
        if (isset($customized[$plu])) {
            $custom = 1;
        }
        echo $qty . '-' . $item_id . '-' . $custom;
    }

    public function delete_meal($id)
    {
        $cart = session()->get('cart');

        if (isset($cart[$id])) {

            $new_total = $cart[$id]['price'] - $cart[$id]['meal']['price'];
            unset($cart[$id]['meal']);
            $cart[$id]['price'] = $new_total;
            session()->put('cart', $cart);
        }
    }

    public function copy_item($id)
    {
        $item_id = '';
        $cart = session()->get('cart');
        if (isset($cart[$id])) {
            $item_id = $cart[$id]['id'];
            $cart[] = $cart[$id];
            session()->put('cart', $cart);
        }
        $qty = 0;
        $cart_n = session()->get('cart');
        for ($j = 0; $j < count($cart_n); $j++) {
            if ($cart_n[$j]['id'] == $item_id) {
                $qty++;
            }
        }

        echo $qty . '-' . $item_id;
    }

    public function add_qty($id)
    {
        $_org = session()->get('_org');
        $cart = session()->get('cart');
        $qty = 0;
        $total = 0;
        if (isset($cart[$id])) {
            $item = $cart[$id];
            $qty = $item['quantity'] + 1;
            $item['quantity'] = $qty;

            session()->put('cart.' . $id, $item);
        }
        $cart_n = session()->get('cart');
        foreach ($cart_n as $v) {
            $total += ($v['price'] * $v['quantity']);
        }
        $total = ($total + $_org->delivery_charge) . '' . $_org->currency;
        return json_encode(array('qty' => $qty, 'total' => $total));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Cart $cart
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $item_id = $request->input('item_id');
        $key_item = $request->input('key');
        $qty = $request->input('qty');
        $_name = $request->input('item_name');
        $_plu = $request->input('plu');
        $_amounts = str_replace(',', '', $request->input('TotalAmount'));
        $modifiers = $request->input('modifiers');
        $make_meal = $request->input('make_meal');

        $_modifiers = array();
        $qty = 1;
        if ($modifiers != null) {
            foreach ($modifiers as $key => $value) {
                for ($i = 0; $i < count($value); $i++) {
                    $modifier_array = explode('-', $value[$i]);
                    array_push($_modifiers, ['id' => $modifier_array[0], 'plu' => $modifier_array[1], 'name' => $modifier_array[3], 'price' => $modifier_array[2]]);

                }
            }
        }
        $_make_meal = array();

        if (isset($make_meal[$item_id])) {
            $value1 = $make_meal[$item_id];
            // foreach ($make_meal as $key1 => $value1) {
            $_mk2 = $value1['Title'];

            $_mk = isset($value1['Items']) ? $value1['Items'] : array();

            $_mk2_array = explode('-', $_mk2);
            //$_amounts=$_amounts+$_mk2_array[1];
            $_make_meal['id'] = $_mk2_array[0];
            $_make_meal['price'] = $_mk2_array[1];
            $_make_meal['name'] = $_mk2_array[2];
            $_itm = array();
            foreach ($_mk as $ky => $vl) {

                $meal_array = explode('-', $vl);
                array_push($_itm, ['id' => $meal_array[0], 'plu' => $meal_array[1], 'name' => $meal_array[3], 'details' => $meal_array[2], 'price' => 0]);

            }
            $_make_meal['items'] = $_itm;
            //  }
        }

        $cart = session()->get('cart');
        unset($cart[$key_item]);
        $origin_amount = $_amounts;
        $cart[$key_item] = [
            'id' => $item_id,
            'name' => $_name,
            'quantity' => $qty,
            'price' => $_amounts,
            'origin_price' => $origin_amount,
            'plu' => $_plu,
            'modifiers' => $_modifiers,
            'meal' => $_make_meal
        ];

        ksort($cart);
        session()->forget('items_customized');
        session()->forget('cart');
        session()->save();
        session()->put('cart', $cart);
        return back();
    }
}
