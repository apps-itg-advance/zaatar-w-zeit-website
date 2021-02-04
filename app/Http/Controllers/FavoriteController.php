<?php

namespace App\Http\Controllers;

use App\Http\Libraries\MenuLibrary;
use App\Http\Libraries\CustomerLibrary;
use Illuminate\Http\Request;

class FavoriteController extends Controller
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

    public function getFavoriteOrdersIndex()
    {
        $loyalty_id = session()->get('loyalty_id');
        $orders = CustomerLibrary::GetOrdersHistory($loyalty_id, 0, $this->limit_order, true);
        $favouriteOrders = $orders['rows'];
        $row_total = $orders['total'];
        session()->put('orders_data', $favouriteOrders);
        $cart = session()->get('cart');
        $class_css = 'orders-wrapper';
        $flag = true;
        $sub_active = 'orders';
        $page_title = 'Favourites Orders';
        return view('customers.favorites.orders_index', compact('orders', 'cart', 'class_css', 'flag', 'sub_active', 'page_title', 'row_total'));  //
    }

    public function getFavoriteItemsIndex()
    {
        $class_css = 'favourites-wrapper';
        $flag = true;
        $query = MenuLibrary::GetFavouriteItems();
        $cart = session()->get('cart');
        $page_title = "Favorites";
        return view('customers.favorites.items_index', compact('query', 'class_css', 'flag', 'page_title', 'page_title', 'cart'));
    }

    public function getFavoriteOrders()
    {
        $loyalty_id = session()->get('loyalty_id');
        $response = CustomerLibrary::GetOrdersHistory($loyalty_id, 0, $this->limit_order, true);
        return response()->json($response);
    }

    public function getFavoriteItems()
    {
        $response = MenuLibrary::GetFavouriteItems();
        return response()->json($response);
    }

    public function setFavouriteItem(Request $request)
    {
        $itemId = $request->item_id;
        $favName = $request->favorite_name;
        $item = $request->item;
        $response = MenuLibrary::SetFavoriteItem($itemId, $favName, $item);
        return response()->json($response->message);
    }

    public function removeFavouriteItem(Request $request)
    {
        $item = json_decode($request->item);
        MenuLibrary::RemoveFavoriteItem($item->FavouriteId);
        $response = MenuLibrary::GetFavouriteItems();
        return response()->json($response->data);
    }

    public function setFavouriteOrder(Request $request)
    {
        $response = MenuLibrary::SetFavoriteOrder($request->order_id);
        return response()->json($response->message);
    }

    public function removeFavouriteOrder(Request $request)
    {
        $response = MenuLibrary::RemoveFavoriteOrder($request->order_id);
        return response()->json($response->message);
    }
}
