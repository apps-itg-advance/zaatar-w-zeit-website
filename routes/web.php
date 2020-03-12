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


Route::get('/menu/{id?}/{name?}', 'HomeController@menu')->name('home.menu');
Route::get('/', 'HomeController@menu')->name('home');
Route::get('/menu/details/{id}', function($id){
    dd($id);
})->name('menu.details');
Route::post('/cart/store', 'CartController@store')->name('carts.store');
Route::get('/cart', 'CartController@index')->name('carts.index');
Route::get('/cart/delete/{id?}', 'CartController@delete')->name('carts.delete');
Route::get('/cart/delete-meal/{id?}', 'CartController@delete_meal')->name('carts.delete.meal');
Route::get('/cart/copy-item/{id?}', 'CartController@copy_item')->name('carts.copy.item');
Route::get('/cart/add-qty/{id?}', 'CartController@add_qty')->name('carts.add_qty');
Route::get('/cart/edit/{id?}', 'CartController@edit')->name('carts.edit');
Route::post('/cart/update/{id?}', 'CartController@update')->name('carts.update');
Route::post('/cart/remove', 'CartController@remove')->name('carts.remove');

Route::get('/cart/count', 'CartController@cart_count')->name('carts.count');
Route::get('/cart/destroy', 'CartController@destroy')->name('carts.destroy');
Route::get('/checkout', 'CheckoutController@index')->name('checkout.summary');
Route::get('/login', 'Auth\LoginController@index')->name('auth.login');
Route::post('/signin', 'Auth\LoginController@signin')->name('auth.signin');
Route::post('/pin', 'Auth\LoginController@pin')->name('auth.pin');
Route::post('/pin-resend', 'Auth\LoginController@resend_pin')->name('auth.pinresend');
Route::post('/register', 'Auth\LoginController@register')->name('auth.register');
Route::get('/switch-org/{id?}', 'Controller@switch_organization')->name('switch.organization');


Route::group(['middleware' => ['checkLogin']], function () {
    Route::get('/customer/profile/{id?}', 'CustomerController@index')->name('customer.index');
    Route::post('/customer/edit', 'CustomerController@edit')->name('customer.edit');
    Route::post('/customer/store', 'CustomerController@store')->name('customer.store');
    Route::post('/customer/address-edit', 'CustomerController@address_edit')->name('customer.address.edit');
    Route::post('/customer/address-update', 'CustomerController@address_update')->name('customer.address.update');
    Route::post('/customer/address-add', 'CustomerController@address_add')->name('customer.address.add');
    Route::post('/customer/address-save', 'CustomerController@address_save')->name('customer.address.save');
    Route::get('/customer/address-delete/{id?}', 'CustomerController@address_delete')->name('customer.address.delete');
    Route::get('/logout', 'Auth\LoginController@logout')->name('logout');
    Route::get('/customer/order-history', 'CustomerController@orders')->name('customer.orders');
    Route::get('/customer/favourites', 'CustomerController@favourites')->name('customer.favourites');
    Route::get('/customer/order-details', 'CustomerController@order_details')->name('order.details');
    Route::get('/checkout/address', 'CheckoutController@address')->name('checkout.address');
    Route::post('/checkout/address-store', 'CheckoutController@address_store')->name('checkout.address.store');
    Route::post('/checkout/gift-store', 'CheckoutController@gift_store')->name('checkout.gift.store');
    Route::post('/checkout/gift-delete', 'CheckoutController@gift_delete')->name('checkout.gift.delete');
    Route::post('/checkout/green-store', 'CheckoutController@green_store')->name('checkout.green.store');
    Route::post('/checkout/payment-store', 'CheckoutController@payment_store')->name('checkout.payment.store');
    Route::post('/checkout/special-instructions-store', 'CheckoutController@special_instructions_store')->name('checkout.special.instructions.store');
    Route::post('/checkout/loyalty-store', 'CheckoutController@loyalty_store')->name('checkout.loyalty.store');

    Route::post('/checkout/store', 'CheckoutController@store')->name('checkout.store');




    Route::post('/customer/set-favourite', 'CustomerController@set_favourite')->name('customer.set.favourite');

    Route::get('/checkout/wallet', 'CheckoutController@wallet')->name('checkout.wallet');
    Route::get('/checkout/gift', 'CheckoutController@gift')->name('checkout.gift');
    Route::get('/checkout/green', 'CheckoutController@green')->name('checkout.green');
    Route::get('/checkout/payment', 'CheckoutController@payment')->name('checkout.payment');
    Route::get('/checkout/special-instructions', 'CheckoutController@special_instructions')->name('checkout.special_instructions');
});




//Route::get('/details/{id}', 'MenuController@details')->name('menu.details');