<?php

use Illuminate\Support\Facades\Route;

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
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\HomePageController;
use App\Http\Controllers\FilterController;
use App\Http\Controllers\WishListController;
use App\Http\Controllers\RecentViewController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SitemapController;

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

Route::get('/sitemap.xml', [SitemapController::class, 'index']);
Route::get('/sendExtraDiscountEmail', [AccountController::class, 'sendExtraDiscountEmail'])->name('sendExtraDiscountEmail');
Route::get('/sendEmail', [AccountController::class, 'sendEmail'])->name('sendEmail');
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::get('/guestLogin', [AuthController::class, 'guestLogin'])->name('guestLogin');
Route::post('/doregister', [AuthController::class, 'doregister'])->name('doregister');
Route::post('/dologin', [AuthController::class, 'dologin'])->name('dologin');
Route::get('/dologout', [AuthController::class, 'dologout'])->name('dologout');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('activate/{token}', [AuthController::class, 'activate'])->name('activate');
Route::post('forgetpassword', [AuthController::class, 'forgetPassword'])->name('forgetpassword');

//google login
Route::get('auth.google/{page?}', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('auth/google/callback');

//facebbook login
Route::get('auth.facebook/{page?}', [AuthController::class, 'redirectToFacebook'])->name('auth.facebook');
Route::get('auth/facebook/callback', [AuthController::class, 'handleFacebookCallback'])->name('auth/facebook/callback');

Route::post('checkUsernameset', [HomePageController::class, 'checkUsernameset'])->name('checkUsernameset');

Route::post('checkEmailset', [HomePageController::class, 'checkEmailset'])->name('checkEmailset');

//for home page
Route::post('getHomeData', [HomePageController::class, 'getHomeData'])->name('getHomeData');

Route::post('getExtraCartData', [HomePageController::class, 'getExtraCartData'])->name('getExtraCartData');

Route::post('addCart', [HomePageController::class, 'addCart'])->name('addCart');

Route::post('checkagesessionset', [HomePageController::class, 'checkagesessionset'])->name('checkagesessionset');
Route::post('checkgeosessionset', [HomePageController::class, 'checkgeosessionset'])->name('checkgeosessionset');


Route::post('agesessionset', [HomePageController::class, 'agesessionset'])->name('agesessionset');

Route::post('geosessionset', [HomePageController::class, 'geosessionset'])->name('geosessionset');


//for banner image
Route::post('checkbannersessionset', [HomePageController::class, 'Checkbannersessionset'])->name('checkbannersessionset');
Route::post('bannersessionset', [HomePageController::class, 'Bannersessionset'])->name('bannersessionset');


Route::get('cart_btn/{id}/{page}', [HomePageController::class, 'getHtmlCartButton'])->name('cart_btn');
Route::get('order-success/{order_no}/{order_date}', [HomePageController::class, 'ordersuccess'])->name('order-success');
Route::get('product/{category}/{brand}/{product}', [HomePageController::class, 'product_detail'])->name('product');
Route::get('products/{slug_url}', [HomePageController::class, 'product_details'])->name('products');

//for counter
Route::post('cartcounter', [HomePageController::class, 'cartcounter'])->name('cartcounter');

//----------------------------------------------search suggetion---------------------------------------------
Route::post('searchSuggetion', [HomePageController::class, 'searchSuggetion'])->name('searchSuggetion');

//for cart page
Route::post('getCartData', [CartController::class, 'getCartData'])->name('getCartData');
Route::post('applycoupon', [CartController::class, 'Applycoupon'])->name('applycoupon');

//for payment page
Route::post('verifynow', [PaymentController::class, 'Verifynow'])->name('verifynow');
Route::get('payment-complete', [PaymentController::class, 'paymentComplete'])->name('payment-complete');
Route::post('paynow', [PaymentController::class, 'Paynow'])->name('paynow');
Route::get('pay_return/{id}/{session_id?}', [PaymentController::class, 'Payreturn'])->name('pay_return');
Route::get('pay_callback', [PaymentController::class, 'Paycallback'])->name('pay_callback');

//super pay routes
Route::post('PaySuperpay', [PaymentController::class, 'PaySuperpay'])->name('PaySuperpay');
Route::get('pay-offer', [PaymentController::class, 'payOffer'])->name('pay-offer');
Route::get('pay-payment/{id}', [PaymentController::class, 'payPayment'])->name('pay-payment');
Route::get('payment-status/{type}', [PaymentController::class, 'paymentStatus'])->name('payment-status');

//Route::get('superpay-successs',[PaymentController::class,'SuperpaySuccesss'])->name('superpay-successs');

Route::post('superpay-success', [PaymentController::class, 'SuperpaySuccess'])->name('superpay-success');
Route::post('superpay-fail', [PaymentController::class, 'SuperpayFail'])->name('superpay-fail');
Route::post('superpay-refund', [PaymentController::class, 'SuperpayRefund'])->name('superpay-refund');

//address
Route::post('getShipingAddress', [AddressController::class, 'getShipingAddress'])->name('getShipingAddress');
Route::post('getBillingAddress', [AddressController::class, 'getBillingAddress'])->name('getBillingAddress');
Route::post('addNewAddress', [AddressController::class, 'addNewAddress'])->name('addNewAddress');

//PayPal Payment Getway
Route::get('paypal', [PaymentController::class, 'paypal'])->name('paypal');
Route::get('paypal-success', [PaymentController::class, 'paypalSuccess'])->name('paypal-success');
Route::get('paypal-cancel', [PaymentController::class, 'paypalCancel'])->name('paypal-cancel');

//recent view
//---------------------------------------- recent view-----------------
Route::post('getRecentData', [RecentViewController::class, 'getRecentData'])->name('getRecentData');

//set cookie
Route::post('set_cookie', [HomePageController::class, 'set_cookie'])->name('set_cookie');


Route::middleware(['seo'])->group(function () {
  Route::get('/login', [AuthController::class, 'login'])->name('login');
  Route::view('forget-password', 'frontend.auth.forget_password');

  Route::get('home', [HomePageController::class, 'dashboard'])->name('home');
  Route::get('/', [HomePageController::class, 'dashboard'])->name('dashboard');
  Route::get('refcode/{ref_code?}', [HomePageController::class, 'refcode'])->name('refcode');
  Route::get('cart', [CartController::class, 'index'])->name('cart');
  Route::get('payment', [PaymentController::class, 'index'])->name('payment');
  Route::get('recent', [RecentViewController::class, 'index'])->name('recent');
  Route::get('wishlist', [WishListController::class, 'index'])->name('wishlist');
  Route::get('order', [OrderController::class, 'index'])->name('order');


  //static pages
  Route::view('coverage', 'frontend.static-pages.coverage');
  Route::view('privacy-policy', 'frontend.static-pages.privacy-policy');
  Route::view('about-us', 'frontend.static-pages.about_us');
  Route::view('age-verification', 'frontend.static-pages.age-verification');
  Route::view('terms-conditions', 'frontend.static-pages.terms_conditions');
  Route::view('delivery-information', 'frontend.static-pages.delivery_information');
  Route::view('refund-return', 'frontend.static-pages.refund_and_return');
});


//filter controller
Route::get('Filter/{name}', [FilterController::class, 'index'])->name('Filter');
Route::post('get_filter_data', [FilterController::class, 'get_filter_data'])->name('get_filter_data');

Route::middleware(['checklogin', 'seo'])->group(function () {

  Route::view('order-success', 'frontend.order-success');

  //----------------------------------------wishlist--------------------------------------------
  Route::post('getWishlistData', [WishListController::class, 'getWishlistData'])->name('getWishlistData');
  Route::post('addWishlist', [HomePageController::class, 'addWishlist'])->name('addWishlist');

  //------------------------------------- without stripe-------------------------------------
  Route::get('address', [AddressController::class, 'address'])->name('address');
  //----------------------------------------------- orders ---------------------------------
  Route::get('view-order/{id}', [OrderController::class, 'view_order'])->name('view-order');
  Route::get('cancel-order/{id}', [OrderController::class, 'cancel_order'])->name('cancel-order');
  Route::get('invoice/{order_id}', [OrderController::class, 'download_invoice'])->name('download_invoice');
  Route::get('checkout', [OrderController::class, 'final_checkout'])->name('final_checkout');
  //-------------------------------------------------- account ----------------------
  Route::get('account', [AccountController::class, 'index'])->name('account');

  //-------------------------------------------------- chat ----------------------
  Route::get('message', [ChatController::class, 'index'])->name('message');

  //---------------------------------------- for set usernmae-------------
  Route::post('setUsername', [HomePageController::class, 'setUsername'])->name('setUsername');
  Route::post('setemail', [HomePageController::class, 'setemail'])->name('setemail');
});
