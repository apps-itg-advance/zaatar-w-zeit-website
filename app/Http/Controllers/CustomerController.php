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
        return view('customers.profile',compact('query','addresses','class_css','flag','type','Skey','cities','loyalty_levels','next_level'));  //
    }

    public function orders()
    {
        $loyalty_id=session()->get('loyalty_id');
        $query=CustomerLibrary::GetOrdersHistory($loyalty_id);
        $class_css='orders-wrapper';
        $flag=true;
        $sub_active='orders';
        return view('customers.orders',compact('query','class_css','flag','sub_active'));  //
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
        $class_css='favourites-wrapper';
        $flag=true;
        $query=MenuLibrary::GetFavouriteItems();
        return view('customers.favourites',compact('query','class_css','flag'));  //
    }
    public function set_favourite(Request $request)
    {
        $item=$request->input('item');
        //echo $item;
        //$_array=json_decode($item);
        //dump($item);
       // echo $item['ID'];
        $query=MenuLibrary::SetFavoriteItem($item);
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
        $geo=$request->input('geo'.$Skey);
        $line1=$request->input('line1'.$Skey);
        $building_name=$request->input('building_name'.$Skey);
        $building_nbr=$request->input('building_nbr'.$Skey);
        $floor=$request->input('floor'.$Skey);
        $phone=$request->input('phone'.$Skey);
        $ext=$request->input('ext'.$Skey);
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
      //  var_dump($query);
        $array_address=array(
            'LoyaltyId'=>$loyalty_id,
            'AddressType'=>1,
            'Name'=>'Main Address',
            'AptNumber'=>$apartment,
            'Line1'=>$line1,
            'Line2'=>$line2,
            'PhoneCode'=>$loyalty_id,
            'Phone'=>$phone,
            'CityId'=>$city_id,
            'XLocation'=>'',
            'YLocation'=>'',
            'PersonalInfo'=>'',
            'Company'=>'',
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
        $address=(object)$request->input();
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
        return view('customers._edit_address',compact('query','addresses_types','address','address_types','skey','cities'));
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
            'Company'=>'',
            'IsDefault'=>$is_default==1 ? 1:0,
            'ExtraAddress'=>$more_details,
        );
        $address=CustomerLibrary::UpdateAddress($array);
        CustomerLibrary::UpdateSessionAddresses($loyalty_id);
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
            'Company'=>'',
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
    public function destroy(Customer $customer)
    {
        //
    }
}
