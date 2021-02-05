<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Http\Libraries\AuthLibrary;
use App\Http\Libraries\MenuLibrary;
use App\Http\Libraries\SettingsLib;
use App\Http\Libraries\CustomerLibrary;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->limit_order = 3;
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

    public function getAddressesTypes(Request $request)
    {
        $response = SettingsLib::GetDeliveryScreenDataSteps();
        $addressesTypes = [];
        if (isset($response->Addresses)) {
            $addresses = $response->Addresses ?? [];
            $availableAddressesTypes = $response->AddressTypes;
            foreach ($availableAddressesTypes as $addressType) {
                $addressType->used = false;
                if (count($addresses) > 0) {
                    foreach ($addresses as $address) {
                        if ($address->TypeID == $addressType->ID) {
                            $addressType->used = true;
                        }
                    }
                }
                array_push($addressesTypes, $addressType);
            }
        }
        return response()->json($addressesTypes);
    }

    public function getAllAddresses()
    {
        $response = CustomerLibrary::getAllAddresses();
        return response()->json($response);
    }

    public function addEditAddress(Request $request)
    {

//        $response = CustomerLibrary::checkZone($request->y_location, $request->x_location);
//        if (count($response) == 0) {
//            return parent::respondWithError('Not in zone', 500);
//        }

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
            'Phone' => '11',
            'CityId' => $cityId,
            'PersonalInfo' => '11',
            'AddressType' => $addressTypeId,
            'CompanyName' => $company,
            'IsDefault' => $request->is_default == 1 ? 1 : 0,
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
        return response()->json('Address successfully saved');
    }

    public function deleteAddress($id)
    {
        CustomerLibrary::deleteAddress($id);
        return response()->json('Address successfully deleted');
    }

    public function checkZone(Request $request)
    {
        $response = CustomerLibrary::checkZone($request->lat, $request->lng);
        if (count($response) == 0) {
            return parent::respondWithError('Not in zone', 500);
        }
        return response()->json('In zone');
    }
}
