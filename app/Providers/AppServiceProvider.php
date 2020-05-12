<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http\Libraries\MenuLibrary;
use App\Extensions\MongoSessionHandler;
use Illuminate\Support\Facades\Session;
use App\Http\Libraries\SettingsLib;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        view()->composer('*', function ($view) {
            $_org=session()->get('_org');
            if(!isset($_org) or !isset($_org->currency) or !isset($_org->delivery_charge) or !isset($_org->country_code) or !isset($_org->country))
            {
                session()->flush();
                cache()->clear();
            }
            $this->currency=$_org->currency;
            $this->delivery_charge=$_org->delivery_charge;
            $this->country_code=$_org->country_code;
            $this->country=$_org->country;
            $view->with('currency',  $this->currency);
            $view->with('delivery_fees',$this->delivery_charge);
            $view->with('country_code',$this->country_code);
            $view->with('country',$this->country);
        });
        //
    }
}
