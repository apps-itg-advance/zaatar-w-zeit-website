<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Libraries\SettingsLib;

Route::get('/menu/{id?}/{name?}', 'HomeController@menu')->name('home.menu');
Route::get('/', 'HomeController@menu')->name('home');
Route::get('/menu/details/{id}', function ($id) {
})->name('menu.details');

//Route::get('/switch-org/{id?}', 'Controller@switch_organization')->name('switch.organization');
//Route::get('/details/{id}', 'MenuController@details')->name('menu.details');
//Route::get('lang/{locale}', 'LocalizationController@index');

Route::get('/switch-org/{id?}/{lang?}', function ($id, $lang) {
    App::setLocale($lang);
    session()->put('locale', $lang);
    SettingsLib::SwitchOrganization($id);
    return redirect(route('home'));
})->name('switch.organization');


Route::prefix('cart')->group(function () {
    Route::post('/store', 'CartController@store')->name('carts.store');
    Route::get('/', 'CartController@index')->name('carts.index');
    Route::get('/delete/{id?}', 'CartController@delete')->name('carts.delete');
    Route::get('/delete-meal/{id?}', 'CartController@delete_meal')->name('carts.delete.meal');
    Route::get('/copy-item/{id?}', 'CartController@copy_item')->name('carts.copy.item');
    Route::get('/add-qty/{id?}', 'CartController@add_qty')->name('carts.add_qty');
    Route::get('/edit/{id?}', 'CartController@edit')->name('carts.edit');
    Route::post('/update/{id?}', 'CartController@update')->name('carts.update');
    Route::post('/remove', 'CartController@remove')->name('carts.remove');
    Route::get('/count', 'CartController@cart_count')->name('carts.count');
    Route::get('/destroy', 'CartController@destroy')->name('carts.destroy');
    Route::get('/show', 'CartController@show')->name('carts.show');

    //New
    Route::post('/save-cart', 'CartController@saveCart')->name('cart.save');
    Route::get('/session-cart', 'CartController@getCart')->name('cart.get');
    Route::get('/clear-cart', 'CartController@clearCart')->name('cart.clear');
});

Route::get('/checkout', 'CheckoutController@index')->name('checkout.summary');
Route::get('/login', 'Auth\LoginController@index')->name('auth.login');
Route::post('/signin', 'Auth\LoginController@signin')->name('auth.signin');
Route::post('/pin', 'Auth\LoginController@pin')->name('auth.pin');
Route::post('/pin-resend', 'Auth\LoginController@resend_pin')->name('auth.pinresend');
Route::post('/register', 'Auth\LoginController@register')->name('auth.register');
Route::get('/clear-cache', 'Auth\LoginController@clear_cache')->name('clear.cache');

Route::group(['middleware' => ['checkLogin']], function () {

    Route::prefix('customer')->group(function () {
        Route::get('/profile', 'CustomerController@index')->name('customer.index');
        Route::post('/edit', 'CustomerController@edit')->name('customer.edit');
        Route::post('/update', 'CustomerController@update')->name('customer.update');
        Route::get('/credit-card-delete/{id?}', 'CustomerController@credit_cards_delete')->name('credit.cards.delete');
        Route::post('/delete-card', 'CustomerController@deleteCreditCard')->name('customer.delete.card');
        Route::get('/get-card', 'CustomerController@getPaymentCards')->name('customer.cards');
        Route::prefix('favourite')->group(function () {
            Route::post('/set-favourite', 'CustomerController@set_favourite')->name('customer.set.favourite');
            Route::post('/remove-favourite', 'CustomerController@remove_favourite')->name('customer.remove.favourite');
            Route::post('/set-favourite-order', 'CustomerController@set_favourite_order')->name('customer.set.favourite-order');
            Route::post('/remove-favourite-order', 'CustomerController@remove_favourite_order')->name('customer.remove.favourite-order');
        });
    });

    Route::prefix('favorite')->group(function () {
        Route::get('/items', 'FavoriteController@getFavoriteItemsIndex')->name('favorite.items');
        Route::get('/orders', 'FavoriteController@getFavoriteOrdersIndex')->name('favorite.orders');
        Route::get('/get-orders', 'FavoriteController@getFavoriteOrders');
        Route::get('/get-items', 'FavoriteController@getFavoriteItems');
        Route::post('/set-favourite', 'FavoriteController@setFavouriteItem')->name('favorite.set.favourite');
        Route::post('/remove-favourite', 'FavoriteController@removeFavouriteItem')->name('favorite.remove.favourite');
        Route::post('/set-favourite-order', 'FavoriteController@setFavouriteOrder')->name('favorite.set.favourite-order');
        Route::post('/remove-favourite-order', 'FavoriteController@removeFavouriteOrder')->name('favorite.remove.favourite-order');
    });

    Route::prefix('orders')->group(function () {
        Route::get('/all', 'OrderController@getAllOrders')->name('orders.all');
        Route::post('/repeat', 'OrderController@order_repeat')->name('orders.order.repeat');
        Route::get('/order-history', 'OrderController@orderHistory')->name('orders.order-history');
        Route::get('/more', 'OrderController@GetOrderRows')->name('orders.more');
        Route::get('/order-details', 'OrderController@order_details')->name('orders.details');
        Route::get('/get-items-by-plus', 'OrderController@getItemsByPlus')->name('orders.get-items-by-plus');
    });

    Route::prefix('general')->group(function () {
        Route::get('/cities', 'GeneralController@getCities')->name('general.cities');
    });

    Route::prefix('address')->group(function () {
        Route::get('/all', 'AddressController@getAllAddresses')->name('address.all');
        Route::post('/save', 'AddressController@addEditAddress')->name('address.save');
        Route::delete('/delete/{id}', 'AddressController@deleteAddress')->name('address.delete');
        Route::get('/check-zone', 'AddressController@checkZone')->name('address.check.zone');
        Route::get('/addresses-types', 'AddressController@getAddressesTypes')->name('address.addresses-types');
    });

    Route::prefix('checkout')->group(function () {
        Route::get('/', 'CheckoutController@index')->name('checkout.index');
        Route::post('/confirm-step', 'CheckoutController@confirmStep')->name('checkout.confirm-step');
        Route::post('/skip-step', 'CheckoutController@skipStep')->name('checkout.skip-step');
        Route::get('/info', 'CheckoutController@info')->name('checkout.info');
        Route::post('/order/submit', 'CheckoutController@submitOrder')->name('checkout.submit.order');
        Route::get('/payment-cards', 'CheckoutController@getPaymentCards')->name('checkout.payment.cards');
        Route::get('/payment/status/{status?}/{id?}', 'CheckoutController@flushSession')->name('checkout.flush.session');
        Route::get('/get-datetime', 'CheckoutController@getDateTime')->name('checkout.datetime');
        Route::get('/get-available-schedule-dates', 'CheckoutController@getAvailableScheduleDates')->name('checkout.available-schedule-dates');
    });

    Route::prefix('loyalty')->group(function () {
        Route::get('/loyalty-corner', 'LoyaltyController@getLoyaltyCorner')->name('loyalty.corner');
    });

    Route::get('/checkout/address', 'CheckoutController@address')->name('checkout.address');
    Route::post('/checkout/payment-store', 'CheckoutController@payment_store')->name('checkout.payment.store');
    Route::post('/checkout/loyalty-store', 'CheckoutController@loyalty_store')->name('checkout.loyalty.store');
    Route::get('/checkout/delete/{step?}', 'CheckoutController@delete')->name('checkout.delete');
    Route::get('/checkout/payment/online', 'CheckoutController@payment_online')->name('checkout.online');
    Route::get('/checkout/payment/cards', 'CheckoutController@payment_cards')->name('checkout.payment.cards');
    Route::post('/checkout/payment/card/store', 'CheckoutController@payment_card_save')->name('checkout.card.store');
    Route::get('/checkout/payment', 'CheckoutController@payment')->name('checkout.payment');

    Route::get('/settings/calender', 'SettingsController@calender')->name('checkout.calender');

    Route::get('/logout', 'Auth\LoginController@logout')->name('logout');
});
