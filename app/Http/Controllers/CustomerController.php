<?php

namespace App\Http\Controllers;

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
        $vouchers = CustomerLibrary::GetVouchers(['LoyaltyId' => $loyalty_id]);
        $wallet_balance = $query->details->WalletAmountBalance;
        $page_title = 'Profile';
        $order_history = CustomerLibrary::GetOrdersHistory($loyalty_id, 0, $this->limit_order, false);
        $total_orders = $order_history['total'];
        $orders = $order_history['rows'];
        session()->put('orders_data', $orders);
        $cart = session()->get('cart');
        return view('customers.index', compact('cart', 'query', 'class_css', 'flag', 'type', 'Skey', 'cities', 'loyalty_levels', 'next_level', 'vouchers', 'wallet_balance', 'page_title', 'orders', 'total_orders'));  //
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

    public function update(Request $request)
    {
        $Skey = session()->get('skey');
        $first_name = $request->first_name;
        $last_name = $request->last_name;
        $mobile = $request->mobile;
        $email = $request->email;
        $customerData = [
            'Email' => $email,
            'MobileNumber' => $mobile,
            'FirstName' => $first_name,
            'LastName' => $last_name
        ];
        CustomerLibrary::UpdateCustomers($customerData);
        $name = $request->nick_name;
        $buildingName = $request->building_name;
        $buildingNumber = $request->building_number;
        $cityId = $request->city_id;
        $details = isset($request->details) ?? $request->details;
        $ext = $request->ext;
        $floorNumber = $request->floor_number;
        $street = $request->street;
        $addressTypeId = $request->type_id;
        $company = isset($request->company) ?? $request->company;
        $data = [
            'Name' => $name,
            'AptNumber' => $floorNumber . ' Ext: ' . $ext,
            'Line1' => 'Line1',
            'Line2' => $buildingName . ' Bldg ' . $buildingNumber,
            'PhoneCode' => '11',
            'Phone' => '111',
            'CityId' => $cityId,
            'PersonalInfo' => '11',
            'AddressType' => $addressTypeId,
            'CompanyName' => $company,
            'IsDefault' => 1,
            'ExtraAddress' => $details,
            'YLocation' => $request->y_location,
            'XLocation' => $request->x_location,
        ];
        if ($request->has('id')) {
            $data['ID'] = $request->id;
            CustomerLibrary::updateAddress($data);
        } else {
            CustomerLibrary::addAddress($data);
        }
        return response()->json("Customer updated successfully");

//        CustomerLibrary::UpdateSessionAddresses($loyalty_id);
//        $skey = session('skey');
//        $user = session("user{$skey}");
//        $user->details->FirstName = $first_name;
//        $user->details->LastName = $last_name;
//        session(["user.$skey" => $user]);
//        return redirect(route('customer.index'));
    }
}
