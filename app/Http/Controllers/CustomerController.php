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
        $this->limit_order = 2;
        $this->skey = session()->get('skey');
        if ($this->skey != '') {
            $key = 'user' . $this->skey;
            $user = session()->has($key) ? session()->get($key) : array();
            $this->level_id = (isset($user->details->LevelId) and !is_null($user->details->LevelId)) ? $user->details->LevelId : '';
        }
        view()->composer('*', function ($view) {
            $view->with('limit', $this->limit_order);
            $view->with('LEVEL_ID', $this->level_id);
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index($type = null)
    {
        $Skey = session()->get('skey');
        $type = $type ? $type : 'login';
        $class_css = 'profile-wrapper';
        $flag = true;
        $cities = SettingsLib::GetCities();
        $loyalty_levels = SettingsLib::GetLoyaltyLevels();
        $query = session()->has('user' . $Skey) ? session()->get('user' . $Skey) : array();
        if (isset($query->details->LoyaltyId)) {
            $loyalty_id = $query->details->LoyaltyId;
        } else {
            //  return redirect(route('logout'));
        }
        $data_all = SettingsLib::GetDeliveryScreenDataSteps();
        $addresses_types = $data_all->AddressTypes;
        $current_max = $query->details->LevelMaxCollection;
        $next_level = array();
        if (count($loyalty_levels) > 0) {
            foreach ($loyalty_levels as $loyalty_level) {
                if ($loyalty_level->MinYearlyCollection == $current_max + 1) {
                    $next_level = $loyalty_level;
                    $next_level->NeededPoints = $loyalty_level->MinYearlyCollection - $query->details->TierBalance;
                    break;
                }
            }
        }
        $addresses = session()->has('addresses' . $Skey) ? session()->get('addresses' . $Skey) : array();
        $address_types = array();
        if (count($addresses) > 0) {
            foreach ($addresses as $addr) {
                array_push($address_types, $addr->TypeID);
            }
        }
        $vouchers = CustomerLibrary::GetVouchers(['LoyaltyId' => $loyalty_id]);
        $wallet_balance = $query->details->WalletAmountBalance;
        $page_title = 'Profile';
        $order_history = CustomerLibrary::GetOrdersHistory($loyalty_id, 0, $this->limit_order, false);
        $total_orders = $order_history['total'];
        $orders = $order_history['rows'];
        session()->put('orders_data', $orders);
        $cart = session()->get('cart');
        return view('customers.index', compact('cart', 'query', 'addresses', 'class_css', 'flag', 'type', 'Skey', 'cities', 'loyalty_levels', 'next_level', 'vouchers', 'wallet_balance', 'addresses_types', 'address_types', 'page_title', 'orders', 'total_orders'));  //
    }


    public function getPaymentCards()
    {
        $response = SettingsLib::GetDeliveryScreenDataSteps();
        return response()->json($response->GatewayToken);
    }

    public function deleteCreditCard(Request $request)
    {
        CustomerLibrary::DeleteCreditCards($request->id);
        return response()->json("Card successfully deleted");
    }

    //todo update customer
    public function store(Request $request)
    {
        $Skey = session()->get('skey');
        $is_default = $request->input('is_default' . $Skey);
        $loyalty_id = $request->input('LoyaltyId');
        $first_name = $request->input('first_name' . $Skey);
        $last_name = $request->input('last_name' . $Skey);
        $mobile = $request->input('mobile' . $Skey);
        $email = $request->input('email' . $Skey);
        $address_name = $request->input('name' . $Skey);
        $address_type = $request->input('address_type' . $Skey);
        $geo = $request->input('geo' . $Skey);
        $line1 = $request->input('line1' . $Skey);
        $building_name = $request->input('building_name' . $Skey);
        $building_nbr = $request->input('building_nbr' . $Skey);
        $floor = $request->input('floor' . $Skey);
        $phone = $request->input('phone' . $Skey);
        $ext = $request->input('ext' . $Skey);
        $xLocation = $request->input('x_location' . $Skey);
        $yLocation = $request->input('y_location' . $Skey);
        $Company = $request->input('company' . $Skey);
        $line2 = $building_name . ' Bldg ' . $building_nbr;
        $apartment = $floor . ' Ext: ' . $ext;
        $geo_array = explode('-', $geo);
        $city_id = @$geo_array[0];
        $province_id = @$geo_array[1];
        $array_customer = array(
            'LoyaltyId' => $loyalty_id,
            'Email' => $email,
            'MobileNumber' => $mobile,
            'FirstName' => $first_name,
            'LastName' => $last_name
        );
        $query = CustomerLibrary::UpdateCustomers($array_customer);
        $array_address = array(
            'LoyaltyId' => $loyalty_id,
            'AddressType' => $address_type,
            'Name' => $address_name,
            'AptNumber' => $apartment,
            'Line1' => $line1,
            'Line2' => $line2,
            'PhoneCode' => $loyalty_id,
            'Phone' => $phone,
            'CityId' => $city_id,
            'XLocation' => $xLocation,
            'YLocation' => $yLocation,
            'PersonalInfo' => '',
            'Company' => $Company,
            'IsDefault' => $is_default == 1 ? 1 : 0,
        );
        if ($request->has('address_id' . $Skey)) {
            // echo 'adddresss update';
            $array_address['ID'] = $request->input('address_id' . $Skey);
            $address = CustomerLibrary::UpdateAddress($array_address);
        } else {
            $address = CustomerLibrary::AddAddress($array_address);
        }
        CustomerLibrary::UpdateSessionAddresses($loyalty_id);
        $skey = session('skey');
        $user = session("user{$skey}");
        $user->details->FirstName = $first_name;
        $user->details->LastName = $last_name;
        session(["user.$skey" => $user]);
        return redirect(route('customer.index'));
    }
}
