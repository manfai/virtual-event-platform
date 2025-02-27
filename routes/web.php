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

use AshAllenDesign\LaravelExchangeRates\Classes\ExchangeRate;
use Carbon\Carbon;

Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath' ]
    ], function(){

    Route::get('/', 'ProductController@welcome')->name('welcome');

    Auth::routes(['verify' => true]);

    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/about-us', 'HomeController@about')->name('about-us');
    Route::get('/news', 'HomeController@news')->name('news');
    Route::get('/events', 'HomeController@events')->name('events');
    Route::get('/faqs', 'HomeController@faqs')->name('faqs');
    Route::get('/blog', 'HomeController@blog')->name('blog');
    Route::get('/contact', 'HomeController@contact')->name('contact');
    Route::get('/careers', 'HomeController@careers')->name('careers');

    Route::get('/adoption', 'AdoptionController@index')->name('adoption');
    Route::get('/adoption/stories', 'AdoptionController@stories')->name('adoption.stories');
    Route::get('/adoption/{adoptionNo}', 'AdoptionController@show')->name('adoption.show');

    Route::get('/category', 'ProductController@category')->name('category');
    Route::get('/product', 'ProductController@index')->name('product');
    Route::get('/product/favorites', 'ProductController@favorites')->name('product.favorites');
    Route::get('/product/{productId}-{productTitle}', 'ProductController@show')->name('product.show');
    Route::post('/product/{productId}/favorite', 'ProductController@favor')->name('product.favor');
    Route::delete('/product/{productId}/favorite', 'ProductController@disfavor')->name('product.disfavor');

    Route::get('/checkout', 'CheckoutController@index')->name('checkout');

    Route::get('/cart', 'CartController@index')->name('cart');
    Route::post('/cart', 'CartController@add')->name('cart.add');
    Route::delete('/cart/{sku}', 'CartController@remove')->name('cart.remove');
    Route::get('/cart/added/{cartItem}', 'CartController@added')->name('cart.added');

    Route::get('orders', 'OrdersController@index')->name('orders');
    Route::get('orders/{orderId}', 'OrdersController@show')->name('orders.show');
    Route::post('orders/step-1', 'OrdersController@step_1')->name('orders.step.1');
    Route::post('orders/step-2', 'OrdersController@step_2')->name('orders.step.2');
    Route::post('orders/step-3', 'OrdersController@store')->name('orders.store');

    Route::get('payment/{order}/paypal', 'PaymentController@payByPaypal')->name('pay.paypal');
    Route::get('payment/{order}/paypal/return', 'PaymentController@paypalReturn')->name('paypalReturn');

    Route::get('/address', 'UserAddressController@index')->name('address');
    Route::get('/address/create', 'UserAddressController@create')->name('address.create');
    Route::post('/address/create', 'UserAddressController@store')->name('address.store');
    Route::get('/address/{addressId}', 'UserAddressController@edit')->name('address.edit');
    Route::put('/address/{addressId}', 'UserAddressController@update')->name('address.update');
    Route::delete('/address/{addressId}', 'UserAddressController@destroy')->name('address.destroy');

    Route::get('coupon/codes/{code}', 'CouponCodesController@show')->name('coupon.codes.show');

    Route::get('testing', function(){
        $exchangeRates = new ExchangeRate();
        $result = $exchangeRates->convert(100000, 'HKD', 'HKD', Carbon::now());
        return $result;
    });

});