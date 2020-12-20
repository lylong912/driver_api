<?php
use App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These 
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


//******auth routes******
//login route
Route::post('login', 'App\Http\Controllers\UserController@index');

//login route 3rd party
Route::group(['middleware' => ['web']], function () {
// login with fb routes here
Route::get('login/facebook', 'App\Http\Controllers\UserController@redirectToProvider');
Route::get('login/facebook/callback', 'App\Http\Controllers\UserController@handleProviderCallback');

// login with gmail routes here
Route::get('login/gmail', 'App\Http\Controllers\UserController@redirectToProviderGmail');
Route::get('login/gmail/callback', 'App\Http\Controllers\UserController@handleProviderCallbackGmail');
});

//register route
Route::post('register', 'App\Http\Controllers\UserController@register');
//reset route
Route::post('reset', 'App\Http\Controllers\UserController@resetPassword');

//verify route
Route::post('verify', 'App\Http\Controllers\UserController@verify');
Route::post('login/gmail/verify', 'App\Http\Controllers\UserController@verifygmail');
Route::post('login/facebook/verify', 'App\Http\Controllers\UserController@verifyfacebook');

//resend otp route
Route::post('auth/resend', 'App\Http\Controllers\UserController@resendOtp');

//******public routes******

//location route
Route::get('provinces', 'App\Http\Controllers\LocationController@getProvince');
Route::get('communes/', 'App\Http\Controllers\LocationController@getCommune');
Route::get('districts/', 'App\Http\Controllers\LocationController@getDistrict');   


//******user routes******
Route::group(['middleware'=>'auth:sanctum'],function()
{
//change phone or update phone
Route::put('auth/phone', 'App\Http\Controllers\UserController@changePhone');

//change password route
Route::put('auth/change', 'App\Http\Controllers\UserController@changePassword');
//user location route
Route::get('profile/location', 'App\Http\Controllers\UserController@getLocation');
Route::delete('profile/location/delete/{id}', 'App\Http\Controllers\UserController@removeLocation');
Route::post('profile/location/add', 'App\Http\Controllers\UserController@addLocation');

//pick address
Route::post('profile/location/{id}', 'App\Http\Controllers\UserController@selectAddress');

//user route
Route::get('profile', 'App\Http\Controllers\UserController@getProfile');
Route::put('profile/update', 'App\Http\Controllers\UserController@updateProfile');
//checkout route
// Route::post('order', 'App\Http\Controllers\CheckoutController@Checkout');
Route::get('order/detail', 'App\Http\Controllers\OrderController@getOrderDetail');
Route::get('order/detail/delivery', 'App\Http\Controllers\OrderController@needToDelivery');
Route::get('order/detail/pickup', 'App\Http\Controllers\OrderController@needToPickup');
Route::get('order/detail/deliver', 'App\Http\Controllers\OrderController@getDeliveredList');

Route::post('order/pickup', 'App\Http\Controllers\OrderController@Pickup');
Route::post('order/deliver', 'App\Http\Controllers\OrderController@Delivered');


Route::apiResource("menber",'App\Http\Controllers\MenberController@index');

});

