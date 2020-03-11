<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Libraries\MenuLibrary as MenuLibrary;
use App\Extensions\MongoSessionHandler;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{

    public  function index($id = null,$name=null)
    {
        $cat_id=($id==null) ? session()->get('first_category'):$id;

        $_cat_title=($name==null) ? session()->get('first_category_name'):str_replace('-',' ',$name);
        $query=MenuLibrary::GetMenuItems($id,$this->_org->id,$this->_org->token);
        $array_name=json_decode($_cat_title,true);
        if(is_array($array_name) and isset($array_name['en']))
        {
            $cat_title=str_replace(' ','-',$array_name['en']);
        }
        elseif(is_array($array_name) and !isset($array_name['en']))
        {
            $cat_title=str_replace(' ','-',$array_name[0]);
        }
        else{
            $cat_title=str_replace(' ','-',$_cat_title);
        }
        return view('home',compact('cat_id','cat_title','query'));
    }
    public function menu($id = null,$name=null)
    {
        $cat_id=($id==null) ? session()->get('first_category'):$id;
        $_cat_title=($name==null) ? session()->get('first_category_name'):str_replace('-',' ',$name);
        $query=MenuLibrary::GetMenuItems($cat_id);
        $array_name=json_decode($_cat_title,true);
        if(is_array($array_name) and isset($array_name['en']))
        {
            $cat_title=str_replace(' ','-',$array_name['en']);
        }
        elseif(is_array($array_name) and !isset($array_name['en']))
        {
            $cat_title=str_replace(' ','-',$array_name[0]);
        }
        else
        {
            $cat_title=str_replace(' ','-',$_cat_title);
        }
//	    dd($_cat_title, $array_name, $cat_title);
        $flag=true;
        return view('menu',compact('cat_id','cat_title','query','flag','_cat_title'));
    }
    public  function favourites()
    {

        $flag=true;
        return view('menu',compact('cat_id','cat_title','query','flag'));
    }
}
