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
                session()->flush();
                cache()->clear();
                return redirect(route('home.menu'));
            }

            $this->currency=isset($_org->currency)? $_org->currency:'';
            $this->delivery_charge=isset($_org->delivery_charge) ? $_org->delivery_charge:'lebanon';
            $this->country_code=isset($_org->country_code) ? $_org->country_code:'lb';
            $this->country=isset($_org->country) ? $_org->country:'lebanon';
            $view->with('currency',  $this->currency);
            $view->with('delivery_fees',$this->delivery_charge);
            $view->with('country_code',$this->country_code);
            $view->with('country',$this->country);
        });
        //
    }
}
