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
            if(!isset($_org))
            {
                SettingsLib::CompanyChildren();
                $_org=session()->get('_org');
            }
            $this->currency=$_org->currency;
            $this->delivery_charge=$_org->delivery_charge;
            $view->with('currency',  $this->currency);
            $view->with('delivery_fees',$this->delivery_charge);
        });
        //
    }
}
