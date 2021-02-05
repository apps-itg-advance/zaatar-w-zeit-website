<?php

namespace App\Http\Controllers;

use App\Http\Libraries\MenuLibrary as MenuLibrary;

class HomeController extends Controller
{

    public function index($id = null, $name = null)
    {
        $cat_id = ($id == null) ? session()->get('first_category') : $id;
        $_cat_title = ($name == null) ? session()->get('first_category_name') : str_replace('-', ' ', $name);
        $query = MenuLibrary::GetMenuItems($id, $this->_org->id, $this->_org->token);
        $array_name = json_decode($_cat_title, true);
        if (is_array($array_name) and isset($array_name['en'])) {
            $cat_title = str_replace(' ', '-', $array_name['en']);
        } elseif (is_array($array_name) and !isset($array_name['en'])) {
            $cat_title = str_replace(' ', '-', $array_name[0]);
        } else {
            $cat_title = str_replace(' ', '-', $_cat_title);
        }
        $page_title = 'Home';
        return view('home', compact('cat_id', 'cat_title', 'query', 'page_title'));
    }

    public function menu($id = null, $name = null)
    {
        if (!session()->has('cart')) {
            session()->put('cart', []);
        }
        $items_customized = session()->get('items_customized');
        $_org = session()->get('_org');
        $cat_id = ($id == null) ? session()->get('first_category_' . $_org->id) : $id;
        $_cat_title = ($name == null) ? session()->get('first_category_name_' . $_org->id) : str_replace('-', ' ', $name);
        $query = MenuLibrary::GetMenuItems($cat_id);
        $menu_items = session()->get('menu_data');
        session()->forget('menu_data');
        session()->save();
        $obj_merged = (object)array_merge((array)$menu_items, (array)$query);
        session()->put('menu_data', $obj_merged);
        $array_name = json_decode($_cat_title, true);
        if (is_array($array_name) and isset($array_name['en'])) {
            $cat_title = str_replace(' ', '-', $array_name['en']);
        } elseif (is_array($array_name) and !isset($array_name['en'])) {
            $cat_title = str_replace(' ', '-', $array_name[0]);
        } else {
            $cat_title = str_replace(' ', '-', $_cat_title);
        }
        $flag = true;
        $cart = session()->get('cart');
        $item_qty = array();
        $page_title = 'Home';
        return view('menu.index', compact('cat_id', 'cat_title', 'query', 'flag', '_cat_title', 'item_qty', 'items_customized', 'page_title', 'cart'));
    }
}
