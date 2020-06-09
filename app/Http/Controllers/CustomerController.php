<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Http\Libraries\AuthLibrary;
use App\Http\Libraries\MenuLibrary;
use App\Http\Libraries\SettingsLib;
use App\Http\Libraries\CustomerLibrary;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->limit_order=3;
        $this->skey = session()->get('skey');
        if($this->skey!='')
        {
            $key='user'.$this->skey;
            $user=session()->has($key)? session()->get($key): array();
            $this->level_id=(isset($user->details->LevelId) and !is_null($user->details->LevelId))? $user->details->LevelId: '';
        }
        view()->composer('*', function ($view) {
            $view->with('limit',$this->limit_order);
            $view->with('LEVEL_ID',$this->level_id);
        });
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($type=null)
    {

        $Skey=session()->get('skey');
        $type=$type ? $type : 'login';
        $class_css='profile-wrapper';
        $flag=true;
        $cities=SettingsLib::GetCities();

        $loyalty_levels=SettingsLib::GetLoyaltyLevels();

        $query=session()->has('user'.$Skey) ? session()->get('user'.$Skey) : array();
        if(isset($query->details->LoyaltyId))
        {
            $loyalty_id=$query->details->LoyaltyId;
        }
        else{
          //  return redirect(route('logout'));
        }

        $data_all=SettingsLib::GetDeliveryScreenDataSteps();
        $addresses_types=$data_all->AddressTypes;



     /*   $v=isset($query->vouchers)  ? $query->vouchers : array();
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
     */
        $current_max=$query->details->LevelMaxCollection;
        $next_level=array();
        if(count($loyalty_levels)>0)
        {
            foreach ($loyalty_levels as $loyalty_level)
            {
                if($loyalty_level->MinYearlyCollection==$current_max+1)
                {
                    $next_level=$loyalty_level;
                    $next_level->NeededPoints=$loyalty_level->MinYearlyCollection-$query->details->TierBalance;
                    break;
                }
            }
        }
        $addresses=session()->has('addresses'.$Skey) ? session()->get('addresses'.$Skey) : array();
        $address_types=array();
        if(count($addresses)>0)
        {
            foreach ($addresses as $addr)
            {
                array_push($address_types,$addr->TypeID);
            }
        }
        $vouchers=CustomerLibrary::GetVouchers(['LoyaltyId'=>$loyalty_id]);
        $wallet_balance=$query->details->WalletAmountBalance;
        $page_title='Profile';

        $order_history=CustomerLibrary::GetOrdersHistory($loyalty_id,0,$this->limit_order,false);
        $total_orders=$order_history['total'];
        $orders=$order_history['rows'];
        session()->put('orders_data',$orders);

        return view('customers.details',compact('query','addresses','class_css','flag','type','Skey','cities','loyalty_levels','next_level','vouchers','wallet_balance','addresses_types','address_types','page_title','orders','total_orders'));  //
    }

    public function orders()
    {
        $loyalty_id=session()->get('loyalty_id');
        $res=CustomerLibrary::GetOrdersHistory($loyalty_id,0,$this->limit_order,true);
	    $favouriteOrders=$res['rows'];
        $row_total=$res['total'];
	    session()->put('orders_data',$favouriteOrders);
        $class_css='orders-wrapper';
        $flag=true;
        $sub_active='orders';
        $page_title='Favourites Orders';
        return view('customers.orders_favourite',compact('favouriteOrders','class_css','flag','sub_active','page_title','row_total'));  //
    }
    public function order_repeat(Request $request)
    {

        $cart = session()->get('cart');
        $order_id=$request->input('order_id');
        $orders=session()->get('orders_data');
        $arr_itm=array();
        foreach ($orders as $order)
        {
            if($order->OrderId==$order_id)
            {
                $sorder=$order;
            }
        }
        $items=$sorder->Items;
        if(count((array)$items)>0)
        {
            foreach ($items as $itm)
            {
                if($itm->OpenItem==0 and $itm->MenuType==1) {
                    array_push($arr_itm,$itm->PLU);
                    }
            }
        }
        $query=MenuLibrary::GetMenuItemsByPlus(implode(',',$arr_itm));
        if(count((array)$items)>0)
        {
            for ($i=0;$i<count($items);$i++)
            {
                if($items[$i]->OpenItem==0 and $items[$i]->MenuType==1)
                {
                    $_modifiers=array();
                    $_make_meal=array();
                    $_itm=array();
                    $amount=0;
                    foreach ($query->data as $value)
                    {
                        if($value->PLU==$items[$i]->PLU)
                        {
                            $amount=$value->Price;
                            for ($j=$i+1;$j<count($items);$j++)
                            {
                                if($items[$j]->MenuType!='1')
                                {
                                    if($items[$j]->MenuType!='5' and $items[$j]->MenuType!='6')
                                    {
                                        array_push($_modifiers, ['id' => $items[$j]->ItemId, 'plu' => $items[$j]->PLU, 'name' => $items[$j]->ItemName, 'price' => $items[$j]->GrossPrice]);
                                    }
                                    elseif($items[$j]->MenuType=='5')
                                    {
                                        $_make_meal['id'] = $items[$j]->ItemId;
                                        $_make_meal['price'] =  $items[$j]->GrossPrice;
                                        $_make_meal['name'] = $items[$j]->ItemName;
                                        $_make_meal['plu'] = $items[$j]->PLU;

                                    }
                                    elseif($items[$j]->MenuType=='6')
                                    {
                                        array_push($_itm, ['id' => $items[$j]->ItemId, 'plu' => $items[$j]->PLU, 'name' => $items[$j]->ItemName, 'details' => $items[$j]->ItemName, 'price' => 0]);

                                    }
                                    $amount+=$items[$j]->GrossPrice;
                                }
                                else{
                                    break;
                                }
                                $_make_meal['items'] = $_itm;

                            }
                            $cart[]= [
                                'id' => $value->ID,
                                'name' =>  $items[$i]->ItemName,
                                'quantity' => 1,
                                'price' => $amount,
                                'origin_price'=>$value->Price,
                                'plu' => $value->PLU,
                                'item_modify' => 0,
                                'modifiers'=>$_modifiers,
                                'meal'=>$_make_meal
                            ];

                            session()->put('cart', $cart);
                        }
                    }

                }

            }
        }


        //
    }
    public function GetOrderRows(Request $request)
    {
        //'query'=>$query,'favourite'=>false

        $row=$request->get('row');
        $favourite=$request->get('fav')=='0'? false : true;
        $loyalty_id=session()->get('loyalty_id');
        $res=CustomerLibrary::GetOrdersHistory($loyalty_id,$row,$this->limit_order,$favourite);
        $query=$res['rows'];
        $orders=session()->get('orders_data');
        session()->forget('orders_data');
        session()->save();
        $obj_merged = (object) array_merge((array) $orders, (array) $query);
        session()->put('orders_data',$obj_merged);

        return view('customers._order_grid_row',compact('query','favourite'));
    }
    public function orderHistory()
    {
        $loyalty_id=session()->get('loyalty_id');

        $res=CustomerLibrary::GetOrdersHistory($loyalty_id,0,$this->limit_order,false);
        $row_total=$res['total'];
        $query=$res['rows'];
        session()->put('orders_data',$query);
        $class_css='orders-wrapper';
        $flag=true;
        $sub_active='orders';
        $page_title=app('translator')->get('order_history');;
        return view('customers.order_history',compact('query','class_css','flag','sub_active','page_title','row_total'));
//	        'cart','cart_info','cart_gift','cart_payment','cart_sp_instructions','cart_green','cart_vouchers','cart_wallet','delivery_charge','currency'));  //
    }

    public function order_details()
    {
        $query=array();
        //$cart = Session::get('cart');
        $class_css='orders-wrapper';
        $flag=true;
        return view('customers.order_details',compact('query','class_css','flag'));  //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function favourites()
    {
        $items_customized=session()->get('items_customized');
        $class_css='favourites-wrapper';
        $flag=true;
        $query=MenuLibrary::GetFavouriteItems();
        //print_r($query);
        $cart=session()->get('cart');
        $item_qty=array();
        if(isset($cart))
        {

            for($i=0;$i<count($cart);$i++)
            {
                $qty=1;
                $plu=$cart[$i]['plu'];
                if(!isset($item_qty[$plu]))
                {
                    for($j=$i+1;$j<count($cart);$j++)
                    {
                        if($cart[$i]['plu']==$cart[$j]['plu'])
                        {
                            $qty++;
                        }
                    }
                    $item_qty[$plu]=$qty;
                }


            }
        }
        $page_title='Favourites Items';
        foreach ($query->data as $item) {
            $favItemTotal = 0;
            if ($item->FavoriteData) {
                $favData = json_decode($item->FavoriteData);
                if (isset($favData->Modifiers)) {
                    foreach ($favData->Modifiers as $ModItem) {
                        if (isset($ModItem->details)) {
                            $ModItemDetail = $ModItem->details;
                            if (isset($ModItemDetail->items)) {
                                foreach ($ModItemDetail->items as $ModItemDetailItem)
                                    if (isset($ModItemDetailItem)) {
                                        if (isset($ModItemDetailItem->isSelected)) {
                                            $isSelected = $ModItemDetailItem->isSelected;
                                            if ($isSelected === true) {
                                                $ModPrice = $ModItemDetailItem->Price;
                                                $favItemTotal += $ModPrice;
                                            }
                                        }
                                    }
                            }
                        }
                    }
                }
            }
            $item->FavItemTotalPrice = $favItemTotal + $item->Price;
            //$item->Price = $favItemTotal + $item->Price;
        }

//        print_r($query);
//        die();
        return view('menu.favourites',compact('query','class_css','flag','item_qty','items_customized','page_title'));  //
    }
    public function set_favourite(Request $request)
    {
        $plu=$request->input('ItemsPLU');
        $item_id=$request->input('ItemId');
        $favourite_name=$request->input('favourite_name');
        $item_id=$request->input('ItemId');
        $query=MenuLibrary::GetMenuItemByPlu($plu);
        //$m_item->ID.'-'.$m_item->PLU.'-'.str_replace(',','',$m_item->Price).'-'.$category_name.' '.$m_item->ModifierName
        $array_modifiers=$request->input('modifiers'.$item_id);
        $array_s_modifiers=array();
        if(is_array($array_modifiers))
        {
            foreach ($array_modifiers as $key=>$value)
            {
                if(is_array($value))
                {
                    foreach ($value as $_v)
                    {
                        $_array_row=explode('-',$_v);
                        array_push($array_s_modifiers,$_array_row[0]);
                    }

                }

            }
        }

        $data=array();
        if(isset($query->data))
        {
            $data=$query->data;
            if(isset($data->Modifiers))
            {

                $modifiers=$data->Modifiers;
                $data->customName=$request->input('favourite_name');
                $data->isCustomized=count($array_s_modifiers)>0 ? true :false;
                foreach ($modifiers as $modifier)
                {
                    $row_m=$modifier->details;
                    $_count=(isset($array_modifiers[$row_m->ID]) ? count($array_modifiers[$row_m->ID]):0);
                    $row_m->isSelected=$_count>0 ? true:false;
                    $row_m->NoOfSelectedCategory=$_count;
                    if(isset($row_m->items))
                    {
                        $m_items=$row_m->items;
                        foreach ($m_items as $m_item)
                        {
                            //isSelected
                            $m_item->isSelected=in_array($m_item->ID,$array_s_modifiers) ? true: false;
                        }
                    }

                }
            }
        }
        $query=MenuLibrary::SetFavoriteItem($item_id,$favourite_name,json_encode($data));
        echo json_encode($query);
    }

	public function remove_favourite(Request $request)
	{
		$itemId=$request->item_id;
		$query=MenuLibrary::RemoveFavoriteItem($itemId);
		echo $query->message;
	}


	public function set_favourite_order(Request $request)
	{
		$orderId=$request->input('order_id');
		$query=MenuLibrary::SetFavoriteOrder($orderId);
		echo $query->message;
	}
	public function remove_favourite_order(Request $request)
	{
		$orderId=$request->input('order_id');
		$query=MenuLibrary::RemoveFavoriteOrder($orderId);
		echo $query->message;
	}

	/**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $Skey=session()->get('skey');
        $is_default=$request->input('is_default'.$Skey);
        $loyalty_id=$request->input('LoyaltyId');
        $first_name=$request->input('first_name'.$Skey);
        $last_name=$request->input('last_name'.$Skey);
        $mobile=$request->input('mobile'.$Skey);
        $email=$request->input('email'.$Skey);
        $address_name=$request->input('name'.$Skey);
        $address_type=$request->input('address_type'.$Skey);
        $geo=$request->input('geo'.$Skey);
        $line1=$request->input('line1'.$Skey);
        $building_name=$request->input('building_name'.$Skey);
        $building_nbr=$request->input('building_nbr'.$Skey);
        $floor=$request->input('floor'.$Skey);
        $phone=$request->input('phone'.$Skey);
        $ext=$request->input('ext'.$Skey);
        $xLocation=$request->input('x_location'.$Skey);
        $yLocation=$request->input('y_location'.$Skey);
        $Company=$request->input('company'.$Skey);

        $line2=$building_name.' Bldg '.$building_nbr;

        $apartment=$floor.' Ext: '.$ext;
        $geo_array=explode('-',$geo);
        $city_id=@$geo_array[0];
        $province_id=@$geo_array[1];

        $array_customer=array(
            'LoyaltyId'=>$loyalty_id,
            'Email'=>$email,
            'MobileNumber'=>$mobile,
            'FirstName'=>$first_name,
            'LastName'=>$last_name
        );
        $query=CustomerLibrary::UpdateCustomers($array_customer);

        $array_address=array(
            'LoyaltyId'=>$loyalty_id,
            'AddressType'=>$address_type,
            'Name'=>$address_name,
            'AptNumber'=>$apartment,
            'Line1'=>$line1,
            'Line2'=>$line2,
            'PhoneCode'=>$loyalty_id,
            'Phone'=>$phone,
            'CityId'=>$city_id,
            'XLocation'=>$xLocation,
            'YLocation'=>$yLocation,
            'PersonalInfo'=>'',
            'Company'=>$Company,
            'IsDefault'=>$is_default==1 ? 1:0,
        );
        if ($request->has('address_id'.$Skey))
        {
           // echo 'adddresss update';
            $array_address['ID']=$request->input('address_id'.$Skey);
            $address=CustomerLibrary::UpdateAddress($array_address);
        }
        else{
           $address=CustomerLibrary::AddAddress($array_address);
        }
        CustomerLibrary::UpdateSessionAddresses($loyalty_id);

	    $skey = session('skey');
	    $user = session("user{$skey}");
	    $user->details->FirstName = $first_name;
	    $user->details->LastName = $last_name;
	    session(["user.$skey"=>$user]);
	    return redirect(route('customer.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function address_edit(Request $request)
    {
        $data_all=SettingsLib::GetDeliveryScreenDataSteps();
        $addresses_types=$data_all->AddressTypes;
        $address=(object)$request->input('data');
        $selected_id=$request->input('checked_id');
        $cities=SettingsLib::GetCities();
        $skey = session()->get('skey');
        $customer=session()->has('user'.$skey) ? session()->get('user'.$skey) : array();

       $mobile=$customer->details->LoyaltyCardCode;
        $addresses=session()->has('addresses'.$skey) ? session()->get('addresses'.$skey) : array();
        $address_types=array();
        if(count($addresses)>0)
        {
            foreach ($addresses as $addr)
            {
                array_push($address_types,$addr->TypeID);
            }
        }
        $query=$customer->details;
        return view('customers._edit_address',compact('query','addresses_types','address','address_types','skey','cities','mobile','selected_id'));
    }
    public function address_add()
    {
        $data_all=SettingsLib::GetDeliveryScreenDataSteps();
        $addresses_types=$data_all->AddressTypes;
        $cities=SettingsLib::GetCities();
        $skey = session()->get('skey');
        $customer=session()->has('user'.$skey) ? session()->get('user'.$skey) : array();
        $addresses=session()->has('addresses'.$skey) ? session()->get('addresses'.$skey) : array();
        $address_types=array();
        if(count($addresses)>0)
        {
            foreach ($addresses as $addr)
            {
                array_push($address_types,$addr->TypeID);
            }
        }
        $query=$customer->details;
        return view('customers._add_address',compact('query','addresses_types','address_types','skey','cities'));
    }
    public function address_delete($id)
    {
        $skey = session()->get('skey');
        $query=session()->has('user'.$skey) ? session()->get('user'.$skey) : array();
        $loyalty_id=$query->details->LoyaltyId;
        $res=CustomerLibrary::DeleteAddress($id,$loyalty_id);
        return back();
    }
    public function credit_cards_delete($id)
    {
        $skey = session()->get('skey');
        $query=session()->has('user'.$skey) ? session()->get('user'.$skey) : array();
        $loyalty_id=$query->details->LoyaltyId;
        $res=CustomerLibrary::DeleteCreditCards($id,$loyalty_id);
        return back();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function address_update(Request $request)
    {
        $Skey = session()->get('skey');

        $loyalty_id=$request->input('LoyaltyId');
        $name=$request->input('name'.$Skey);
        $geo=$request->input('geo'.$Skey);
        $line1=$request->input('line1'.$Skey);
        $building_name=$request->input('building_name'.$Skey);
        $building_nbr=$request->input('building_nbr'.$Skey);
        $floor=$request->input('floor'.$Skey);
        $phone=$request->input('phone'.$Skey);
        $ext=$request->input('ext'.$Skey);
        $line2=$building_name.' Bldg '.$building_nbr;
        $x_location=$request->input('x_location'.$Skey);
        $y_location=$request->input('y_location'.$Skey);
        $more_details=$request->input('more_details'.$Skey);
        $is_default=$request->input('is_default'.$Skey);
        $address_id=$request->input('address_id'.$Skey);
        $address_type=$request->input('address_type'.$Skey);
	    $company=$request->input('company'.$Skey);
        $selected_id=$request->input('selected_id'.$Skey);

	    $apartment=$floor.' Ext: '.$ext;
        $geo_array=explode('-',$geo);
        $city_id=@$geo_array[0];
        $province_id=@$geo_array[1];

        $array=array(
            'ID'=>$address_id,
            'LoyaltyId'=>$loyalty_id,
            'Name'=>$name,
            'AptNumber'=>$apartment,
            'Line1'=>$line1,
            'Line2'=>$line2,
            'PhoneCode'=>$loyalty_id,
            'Phone'=>$phone,
            'CityId'=>$city_id,
            'XLocation'=>$x_location,
            'YLocation'=>$y_location,
            'PersonalInfo'=>'',
            'AddressType'=>$address_type,
            'Company'=>$company,
            'IsDefault'=>$is_default==1 ? 1:0,
            'ExtraAddress'=>$more_details,
        );
        $address=CustomerLibrary::UpdateAddress($array);
        CustomerLibrary::UpdateSessionAddresses($loyalty_id);
        session()->put('s_address',$selected_id);
        return back();
    }

    public function address_save(Request $request)
    {
        $Skey = session()->get('skey');

        $loyalty_id=$request->input('LoyaltyId');
        $name=$request->input('name'.$Skey);
        $geo=$request->input('geo'.$Skey);
        $line1=$request->input('line1'.$Skey);
        $building_name=$request->input('building_name'.$Skey);
        $building_nbr=$request->input('building_nbr'.$Skey);
        $floor=$request->input('floor'.$Skey);
        $phone=$request->input('phone'.$Skey);
        $ext=$request->input('ext'.$Skey);
        $line2=$building_name.' Bldg '.$building_nbr;
        $x_location=$request->input('x_location'.$Skey);
        $y_location=$request->input('y_location'.$Skey);
        $more_details=$request->input('more_details'.$Skey);
        $is_default=$request->input('is_default'.$Skey);
        $address_type=$request->input('address_type'.$Skey);
        $company=$request->input('company'.$Skey);


        $apartment=$floor.' Ext: '.$ext;
        $geo_array=explode('-',$geo);
        $city_id=@$geo_array[0];
        $province_id=@$geo_array[1];

        $array=array(
            'LoyaltyId'=>$loyalty_id,
            'Name'=>$name,
            'AptNumber'=>$apartment,
            'Line1'=>$line1,
            'Line2'=>$line2,
            'PhoneCode'=>$loyalty_id,
            'Phone'=>$phone,
            'CityId'=>$city_id,
            'XLocation'=>$x_location,
            'YLocation'=>$y_location,
            'PersonalInfo'=>'',
            'AddressType'=>$address_type,
            'Company'=>$company,
            'IsDefault'=>$is_default==1 ? 1:0,
            'ExtraAddress'=>$more_details,
        );
        $address=CustomerLibrary::AddAddress($array);
        CustomerLibrary::UpdateSessionAddresses($loyalty_id);
       return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */

}
