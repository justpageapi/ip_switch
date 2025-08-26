<?php

namespace App\Http\Controllers;

use App\Helpers\Pay360Helper;
use App\Helpers\SessionHelper;
use App\Models\Address;
use App\Models\Customer;
use App\Models\Onlineorder;
use App\Models\Order;
use App\Models\PaymentHistory;
use App\Models\PostCode;
use App\Models\Seo;
use App\Models\Setting;
use App\Models\SiteSetting;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use phpseclib3\Crypt\RC2;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Srmklive\PayPal\Services\ExpressCheckout;
use Srmklive\PayPal\Services\AdaptivePayments;


class PaymentController extends Controller
{

    public function index()
    {
        $total = 0;
        $shipping = 0;

        $sesssion_cart_data = SessionHelper::ReCreateCart();
        $sub_ttl = $sesssion_cart_data['sub_ttl'];
        $total = $sesssion_cart_data['total'];

        if ($sub_ttl <= 0) {
            return redirect()->route('dashboard')->withErrors(['error' => 'The cart is empty please add item in cart to make payment']);
        }

        $total = number_format((float) $total, 2, '.', '');
        return view('frontend.payment', compact('total'));
    }

    public function paypal(Request $request)
    {

        $sesssion_cart_data = SessionHelper::ReCreateCart();
        $session_data = $sesssion_cart_data['session_data'];
        $sub_ttl = $sesssion_cart_data['sub_ttl'];
        $total_amt = $sesssion_cart_data['total'];

        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $paypalToken = $provider->getAccessToken();
        $responce = $provider->createOrder([
            "intent" => 'CAPTURE',

            "application_context" => [
                "return_url" => route('paypal-success'),
                "cancel_url" => route('paypal-cancel'),
            ],
            "purchase_units" => [
                [
                    "reference_id" => "order_" . rand('1111', '9999'),
                    "amount" => [
                        "currency_code" => "GBP",
                        "value" => $total_amt,

                    ],
                ],
            ]
        ]);

        if (isset($responce['id']) && $responce['id'] != null) {
            foreach ($responce['links'] as $link) {
                if ($link['rel'] == 'approve') {
                    return redirect()->away($link['href']);
                }
            }
        }
    }

    public function paypalSuccess(Request $request)
    {
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $paypalToken = $provider->getAccessToken();
        $responce = $provider->capturePaymentOrder($request->token);
        dd($responce);
    }

    public function paypalCancel() {}

    public function paymentComplete()
    {

        $total = 0;
        $sub_ttl = 0;
        $shipping = 0;

        $sesssion_cart_data = SessionHelper::ReCreateCart();
        $session_data = $sesssion_cart_data['session_data'];
        $sub_ttl = $sesssion_cart_data['sub_ttl'];
        $total = $sesssion_cart_data['total'];

        if ($sub_ttl <= 0) {
            return redirect()->route('dashboard')->withErrors(['error' => 'The cart is empty please add item in cart to make payment']);
        }
        if (!Session::has('AddressData')) {
            return redirect()->route('payment')->withErrors(['error' => 'Please Fill Address Properly']);
        }

        $total = number_format((float) $total, 2, '.', '');
        return view('frontend.payment_complete', ['total' => $total]);
    }

    public function Verifynow(Request $request)
    {
        $error = 1;
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'phone' => 'required',
        ]);

        $email = $request->email;
        $phone = $request->phone;

        if ($validator->fails()) {
            return Redirect::back()->withErrors(['error' => $validator->messages()->first()]);
        } else {
            $error = 0;
        }

        $ship_adr_id = $request->ship_address_id;
        $bill_adr_id = $request->bill_address_id;
        $checkbox = $request->checkbox;
        if ($checkbox == 'on') {
            $checkbox = 'true';
        } else {
            $checkbox = 'false';
        }
        if ($error == 0) {
            if ($ship_adr_id == null) {
                $ship_name = $request->ship_name;
                $ship_addr_line_1 = $request->ship_addr_line_1;
                $ship_addr_line_2 = $request->ship_addr_line_2;
                $ship_city = $request->ship_city;
                $ship_pin = $request->ship_pin;
                $ship_state = $request->ship_state;
                $ship_country = $request->ship_country;

                if ($ship_name == null) {
                    $error = 1;
                    //$out['data']  = 'Shipping Name is Required';
                    return Redirect::back()->withErrors(['ship_name' => 'Shipping Name is Required']);
                } elseif ($ship_addr_line_1 == null) {
                    $error = 1;
                    //$out['data']  = 'Shipping Address 1 is Required';
                    return Redirect::back()->withErrors(['ship_addr_line_1' => 'Shipping Address 1 is Required']);
                } elseif ($ship_city == null) {
                    $error = 1;
                    //$out['data']  = 'Shipping Town is Required';
                    return Redirect::back()->withErrors(['ship_city' => 'Shipping Town is Required']);
                } elseif ($ship_pin == null) {
                    $error = 1;
                    //$out['data']  = 'Shipping Postcode is Required';
                    return Redirect::back()->withErrors(['ship_pin' => 'Shipping Postcode is Required']);
                } elseif ($ship_state == null) {
                    $error = 1;
                    //$out['data']  = 'Shipping County is Required';
                    return Redirect::back()->withErrors(['ship_state' => 'Shipping County is Required']);
                } elseif ($ship_country == null) {
                    $error = 1;
                    //$out['data']  = 'Shipping Country is Required';
                    return Redirect::back()->withErrors(['ship_country' => 'Shipping Country is Required']);
                } else {
                    $error = 0;
                }
            }
            if ($checkbox == 'false' && $bill_adr_id == null) {
                $bill_name = $request->bill_name;
                $bill_addr_line_1 = $request->bill_addr_line_1;
                $bill_addr_line_2 = $request->bill_addr_line_2;
                $bill_city = $request->bill_city;
                $bill_pin = $request->bill_pin;
                $bill_state = $request->bill_state;
                $bill_country = $request->bill_country;

                if ($bill_name == null) {
                    $error = 1;
                    //$out['data']  = 'Billing Name is Required';
                    return Redirect::back()->withErrors(['bill_name' => 'Billing Name is Required']);
                } elseif ($bill_addr_line_1 == null) {
                    $error = 1;
                    //$out['data']  = 'Billing Address 1 is Required';
                    return Redirect::back()->withErrors(['bill_addr_line_1' => 'Billing Address 1 is Required']);
                } elseif ($bill_city == null) {
                    $error = 1;
                    //$out['data']  = 'Billing Town is Required';
                    return Redirect::back()->withErrors(['bill_city' => 'Billing Town is Required']);
                } elseif ($bill_pin == null) {
                    $error = 1;
                    //$out['data']  = 'Billing Postcode is Required';
                    return Redirect::back()->withErrors(['bill_pin' => 'Billing Postcode is Required']);
                } elseif ($bill_state == null) {
                    $error = 1;
                    //$out['data']  = 'Billing County is Required';
                    return Redirect::back()->withErrors(['bill_state' => 'Billing County is Required']);
                } elseif ($bill_country == null) {
                    $error = 1;
                    //$out['data']  = 'Billing Country is Required';
                    return Redirect::back()->withErrors(['bill_country' => 'Billing Country is Required']);
                } else {
                    $error = 0;
                }
            } else {
                $error = 0;
            }
        }

        if ($error == 0) {

            if ($ship_adr_id != null) {
                $ship_add = Address::find($ship_adr_id);
                if ($ship_add != null) {

                    $ship_name = $ship_add->name;
                    $ship_addr_line_1 = $ship_add->address_line_1;
                    $ship_addr_line_2 = $ship_add->address_line_2;
                    $ship_city = $ship_add->city;
                    $ship_pin = $ship_add->postal_code;
                    $ship_state = $ship_add->state;
                    $ship_country = $ship_add->country;
                }
            } else {

                $ship_name = $request->ship_name;
                $ship_addr_line_1 = $request->ship_addr_line_1;
                $ship_addr_line_2 = $request->ship_addr_line_2;
                $ship_city = $request->ship_city;
                $ship_pin = $request->ship_pin;
                $ship_state = $request->ship_state;
                $ship_country = $request->ship_country;
            }

            if ($checkbox == 'false') {
                if ($bill_adr_id != null) {
                    $bill_add = Address::find($bill_adr_id);
                    if ($bill_add != null) {

                        $bill_name = $bill_add->name;
                        $bill_addr_line_1 = $bill_add->address_line_1;
                        $bill_addr_line_2 = $bill_add->address_line_2;
                        $bill_city = $bill_add->city;
                        $bill_pin = $bill_add->postal_code;
                        $bill_state = $bill_add->state;
                        $bill_country = $bill_add->country;
                    }
                } else {

                    $bill_name = $request->bill_name;
                    $bill_addr_line_1 = $request->bill_addr_line_1;
                    $bill_addr_line_2 = $request->bill_addr_line_2;
                    $bill_city = $request->bill_city;
                    $bill_pin = $request->bill_pin;
                    $bill_state = $request->bill_state;
                    $bill_country = $request->bill_country;
                }
            } else {

                $bill_name = $ship_name;
                $bill_addr_line_1 = $ship_addr_line_1;
                $bill_addr_line_2 = $ship_addr_line_2;
                $bill_city = $ship_city;
                $bill_pin = $ship_pin;
                $bill_state = $ship_state;
                $bill_country = $ship_country;
            }

            if (Session::has('AddressData')) {
                Session::forget('AddressData');
                Session::save();
            }

            $shop_id = Setting::getShopId();
            $shop_id = SiteSetting::where(['key' => 'order_shop', 'site_id' => config('site_setting.site_id')])->first()->value ?? 22;

            $postcode_verify = 'found';
            if (config('pay360_config.postcode_verify') == 1) {
                $postcode_verify = PostCode::VerifyPostcode($shop_id, $ship_pin);
            }

            Customer::GetCustomerId($email);
            $tmp = [];
            $tmp['email'] = $email;
            $tmp['phone'] = $phone;
            $tmp['ship_adr_id'] = $ship_adr_id;
            $tmp['bill_adr_id'] = $bill_adr_id;
            $tmp['checkbox'] = $checkbox;
            $tmp['ship_name'] = $ship_name;
            $tmp['ship_addr_line_1'] = $ship_addr_line_1;
            $tmp['ship_addr_line_2'] = $ship_addr_line_2;
            $tmp['ship_city'] = $ship_city;
            $tmp['ship_pin'] = $ship_pin;
            $tmp['ship_state'] = $ship_state;
            $tmp['ship_country'] = $ship_country;
            $tmp['bill_name'] = $bill_name;
            $tmp['bill_addr_line_1'] = $bill_addr_line_1;
            $tmp['bill_addr_line_2'] = $bill_addr_line_2;
            $tmp['bill_city'] = $bill_city;
            $tmp['bill_pin'] = $bill_pin;
            $tmp['bill_state'] = $bill_state;
            $tmp['bill_country'] = $bill_country;
            $tmp['postcode_verify'] = $postcode_verify;

            Session::push('AddressData', $tmp);
            Session::save();
        }
        return redirect()->route('payment-complete');
    }

    public function Paynow(Request $request)
    {
        // dd($request);

        $error = 1;
        $validator = Validator::make($request->all(), [
            'phone' => 'required',
        ]);

        $email = $request->email;
        $phone = $request->phone;

        $delivery_note = $request->delivery_note;

        if (Session::has('delivery_note')) {
            Session::forget('delivery_note');
            Session::save();
        }

        Session::push('delivery_note', $delivery_note);
        Session::save();

        if ($validator->fails()) {
            return Redirect::back()->withErrors(['error' => $validator->messages()->first()]);
        } else {
            $error = 0;
        }

        $ship_adr_id = $request->ship_address_id;
        $bill_adr_id = $request->bill_address_id;
        $checkbox = $request->checkbox;
        if ($checkbox == 'on') {
            $checkbox = 'true';
        } else {
            $checkbox = 'false';
        }
        if ($error == 0) {
            if ($ship_adr_id == null) {
                $ship_name = $request->ship_name;
                $ship_addr_line_1 = $request->ship_addr_line_1;
                $ship_addr_line_2 = $request->ship_addr_line_2;
                $ship_city = $request->ship_city;
                $ship_pin = $request->ship_pin;
                $ship_state = $request->ship_state;
                $ship_country = $request->ship_country;

                if ($ship_name == null) {
                    $error = 1;
                    //$out['data']  = 'Shipping Name is Required';
                    return Redirect::back()->withErrors(['ship_name' => 'Shipping Name is Required']);
                } elseif ($ship_addr_line_1 == null) {
                    $error = 1;
                    //$out['data']  = 'Shipping Address 1 is Required';
                    return Redirect::back()->withErrors(['ship_addr_line_1' => 'Shipping Address 1 is Required']);
                } elseif (
                    $ship_city == null
                ) {
                    $error = 1;
                    //$out['data']  = 'Shipping Town is Required';
                    return Redirect::back()->withErrors(['ship_city' => 'Shipping Town is Required']);
                } elseif ($ship_pin == null) {
                    $error = 1;
                    //$out['data']  = 'Shipping Postcode is Required';
                    return Redirect::back()->withErrors(['ship_pin' => 'Shipping Postcode is Required']);
                } elseif (
                    $ship_state == null
                ) {
                    $error = 1;
                    //$out['data']  = 'Shipping County is Required';
                    return Redirect::back()->withErrors(['ship_state' => 'Shipping County is Required']);
                } elseif ($ship_country == null) {
                    $error = 1;
                    //$out['data']  = 'Shipping Country is Required';
                    return Redirect::back()->withErrors(['ship_country' => 'Shipping Country is Required']);
                } else {
                    $error = 0;
                }
            }
            if (
                $checkbox == 'false' && $bill_adr_id == null
            ) {
                $bill_name = $request->bill_name;
                $bill_addr_line_1 = $request->bill_addr_line_1;
                $bill_addr_line_2 = $request->bill_addr_line_2;
                $bill_city = $request->bill_city;
                $bill_pin = $request->bill_pin;
                $bill_state = $request->bill_state;
                $bill_country = $request->bill_country;

                if ($bill_name == null) {
                    $error = 1;
                    //$out['data']  = 'Billing Name is Required';
                    return Redirect::back()->withErrors(['bill_name' => 'Billing Name is Required']);
                } elseif ($bill_addr_line_1 == null) {
                    $error = 1;
                    //$out['data']  = 'Billing Address 1 is Required';
                    return Redirect::back()->withErrors(['bill_addr_line_1' => 'Billing Address 1 is Required']);
                } elseif (
                    $bill_city == null
                ) {
                    $error = 1;
                    //$out['data']  = 'Billing Town is Required';
                    return Redirect::back()->withErrors(['bill_city' => 'Billing Town is Required']);
                } elseif ($bill_pin == null) {
                    $error = 1;
                    //$out['data']  = 'Billing Postcode is Required';
                    return Redirect::back()->withErrors(['bill_pin' => 'Billing Postcode is Required']);
                } elseif (
                    $bill_state == null
                ) {
                    $error = 1;
                    //$out['data']  = 'Billing County is Required';
                    return Redirect::back()->withErrors(['bill_state' => 'Billing County is Required']);
                } elseif ($bill_country == null) {
                    $error = 1;
                    //$out['data']  = 'Billing Country is Required';
                    return Redirect::back()->withErrors(['bill_country' => 'Billing Country is Required']);
                } else {
                    $error = 0;
                }
            } else {
                $error = 0;
            }
        }

        if ($error == 0) {

            if ($ship_adr_id != null) {
                $ship_add = Address::find($ship_adr_id);
                if ($ship_add != null) {

                    $ship_name = $ship_add->name;
                    $ship_addr_line_1 = $ship_add->address_line_1;
                    $ship_addr_line_2 = $ship_add->address_line_2;
                    $ship_city = $ship_add->city;
                    $ship_pin = $ship_add->postal_code;
                    $ship_state = $ship_add->state;
                    $ship_country = $ship_add->country;
                }
            } else {

                $ship_name = $request->ship_name;
                $ship_addr_line_1 = $request->ship_addr_line_1;
                $ship_addr_line_2 = $request->ship_addr_line_2;
                $ship_city = $request->ship_city;
                $ship_pin = $request->ship_pin;
                $ship_state = $request->ship_state;
                $ship_country = $request->ship_country;
            }

            if ($checkbox == 'false') {
                if ($bill_adr_id != null) {
                    $bill_add = Address::find($bill_adr_id);
                    if (
                        $bill_add != null
                    ) {

                        $bill_name = $bill_add->name;
                        $bill_addr_line_1 = $bill_add->address_line_1;
                        $bill_addr_line_2 = $bill_add->address_line_2;
                        $bill_city = $bill_add->city;
                        $bill_pin = $bill_add->postal_code;
                        $bill_state = $bill_add->state;
                        $bill_country = $bill_add->country;
                    }
                } else {

                    $bill_name = $request->bill_name;
                    $bill_addr_line_1 = $request->bill_addr_line_1;
                    $bill_addr_line_2 = $request->bill_addr_line_2;
                    $bill_city = $request->bill_city;
                    $bill_pin = $request->bill_pin;
                    $bill_state = $request->bill_state;
                    $bill_country = $request->bill_country;
                }
            } else {

                $bill_name = $ship_name;
                $bill_addr_line_1 = $ship_addr_line_1;
                $bill_addr_line_2 = $ship_addr_line_2;
                $bill_city = $ship_city;
                $bill_pin = $ship_pin;
                $bill_state = $ship_state;
                $bill_country = $ship_country;
            }

            if (Session::has('AddressData')) {
                Session::forget('AddressData');
                Session::save();
            }

            $shop_id = Setting::getShopId();
            $shop_id = SiteSetting::where(['key' => 'order_shop', 'site_id' => config('site_setting.site_id')])->first()->value ?? 22;
            $postcode_verify = 'found';
            if (config('pay360_config.postcode_verify') == 1) {
                $postcode_verify = PostCode::VerifyPostcode($shop_id, $ship_pin);
            }

            if ($email == null || $email == '') {
                Customer::GetCustomerIdByPhoneno($phone);
            } else {
                Customer::GetCustomerId($email);
            }

            $tmp = [];
            $tmp['email'] = $email;
            $tmp['phone'] = $phone;
            $tmp['ship_adr_id'] = $ship_adr_id;
            $tmp['bill_adr_id'] = $bill_adr_id;
            $tmp['checkbox'] = $checkbox;
            $tmp['ship_name'] = $ship_name;
            $tmp['ship_addr_line_1'] = $ship_addr_line_1;
            $tmp['ship_addr_line_2'] = $ship_addr_line_2;
            $tmp['ship_city'] = $ship_city;
            $tmp['ship_pin'] = $ship_pin;
            $tmp['ship_state'] = $ship_state;
            $tmp['ship_country'] = $ship_country;
            $tmp['bill_name'] = $bill_name;
            $tmp['bill_addr_line_1'] = $bill_addr_line_1;
            $tmp['bill_addr_line_2'] = $bill_addr_line_2;
            $tmp['bill_city'] = $bill_city;
            $tmp['bill_pin'] = $bill_pin;
            $tmp['bill_state'] = $bill_state;
            $tmp['bill_country'] = $bill_country;
            $tmp['postcode_verify'] = $postcode_verify;
            Session::push('AddressData', $tmp);
            Session::save();
        }



        //+++++++++++++++++++++++++++++++++++++++++++++++++====================
        $error = 1;
        if (!Session::has('AddressData') && Session::get('AddressData') == []) {
            return Redirect::route('payment')->withErrors(['error' => 'data not found']);
        } else {
            $pay_type = '';
            if ($request->payment == 'pay_card' || $request->payment == 'pay_at_door' || $request->payment == 'collect' || $request->payment == 'paypal') {
                $pay_type = $request->payment;
            }

            if ($pay_type != 'paypal') {

                $address_data = Session::get('AddressData');
                $address_data = $address_data[0];

                $email = $address_data['email'];
                $phone = $address_data['phone'];
                $ship_adr_id = $address_data['ship_adr_id'];
                $bill_adr_id = $address_data['bill_adr_id'];
                $checkbox = $address_data['checkbox'];
                $name = $ship_name = $address_data['ship_name'];
                $line1 = $ship_addr_line_1 = $address_data['ship_addr_line_1'];
                $line2 = $ship_addr_line_2 = $address_data['ship_addr_line_2'];
                $city = $ship_city = $address_data['ship_city'];
                $postcode = $ship_pin = $address_data['ship_pin'];
                $region = $ship_state = $address_data['ship_state'];
                $ship_country = $address_data['ship_country'];
                $bill_name = $address_data['bill_name'];
                $bill_addr_line_1 = $address_data['bill_addr_line_1'];
                $bill_addr_line_2 = $address_data['bill_addr_line_2'];
                $bill_city = $address_data['bill_city'];
                $bill_pin = $address_data['bill_pin'];
                $bill_state = $address_data['bill_state'];
                $bill_country = $address_data['bill_country'];

                if (Session::has('Paydata')) {
                    Session::forget('Paydata');
                    Session::save();
                }

                $tmp = [];
                $tmp['email'] = $email;
                $tmp['phone'] = $phone;
                $tmp['ship_adr_id'] = $ship_adr_id;
                $tmp['bill_adr_id'] = $bill_adr_id;
                $tmp['checkbox'] = $checkbox;
                $tmp['ship_name'] = $ship_name;
                $tmp['ship_addr_line_1'] = $ship_addr_line_1;
                $tmp['ship_addr_line_2'] = $ship_addr_line_2;
                $tmp['ship_city'] = $ship_city;
                $tmp['ship_pin'] = $ship_pin;
                $tmp['ship_state'] = $ship_state;
                $tmp['ship_country'] = $ship_country;
                $tmp['bill_name'] = $bill_name;
                $tmp['bill_addr_line_1'] = $bill_addr_line_1;
                $tmp['bill_addr_line_2'] = $bill_addr_line_2;
                $tmp['bill_city'] = $bill_city;
                $tmp['bill_pin'] = $bill_pin;
                $tmp['bill_state'] = $bill_state;
                $tmp['bill_country'] = $bill_country;

                Session::push('Paydata', $tmp);
                Session::save();

                if ($pay_type == 'pay_card') {

                    $payService = new \PAY360\libraries\transactions();
                    $custService = new \PAY360\libraries\customers();

                    $username = $u = config('pay360_config.username');
                    $password = $p = config('pay360_config.password');
                    $cardLockId = config('pay360_config.cardLockId');
                    $hostedCashierId = $instid = config('pay360_config.hostedCashierId');
                    $cashierId = config('pay360_config.cashierId');
                    $type = config('pay360_config.type'); //0: Test 1: Prod

                    if ($type == 1) {
                        $host = config('pay360_config.live_host');
                    } else {
                        $host = config('pay360_config.test_host');
                    }
                    $order_id = Order::GetOrderId();

                    $site_url = env('APP_URL');

                    $sesssion_cart_data = SessionHelper::ReCreateCart();
                    $session_data = $sesssion_cart_data['session_data'];
                    $sub_ttl = $sesssion_cart_data['sub_ttl'];
                    $total_amt = $sesssion_cart_data['total'];

                    $post = array(
                        "session" => array(
                            "returnUrl" => array("url" => $site_url . '/pay_return/' . $order_id . '/'),
                            "transactionNotification" => array(
                                "url" => $site_url . "/pay_callback",
                                "format" => "REST_JSON"
                            ),
                        ),
                        "transaction" => array(
                            "merchantReference" => $order_id,
                            "money" => array("amount" => array("fixed" => $total_amt), "currency" => Config('currency.currency_text')),
                        ),
                        "customer" => array(
                            "registered" => false,
                            "details" => array(
                                "name" => $name,
                                "address" => array(
                                    "line1" => $line1,
                                    "line2" => $line2,
                                    "city" => $city,
                                    "region" => $region,
                                    "postcode" => $postcode,
                                    "countryCode" => 'GBR',
                                ),
                                "telephone" => $phone,
                                "emailAddress" => $email,
                                "ipAddress" => $_SERVER['REMOTE_ADDR'],
                                "defaultCurrency" => Config('currency.currency_text'),
                            ),
                        ),
                    );

                    $payment_his = new PaymentHistory;
                    $payment_his->cus_id = null;
                    $payment_his->ord_id = $order_id;
                    $payment_his->type = 'init';
                    $payment_his->data = base64_encode(json_encode($post));

                    $response = Pay360Helper::CallUrl($host, $instid, $u, $p, $post);
                    $obj = json_decode($response);

                    if ($obj == null) {
                        return Redirect::route('payment')->withErrors(['error' => 'Payment Error']);
                    }

                    $status = $obj->status;

                    if ($status == 'SUCCESS') {
                        if ($obj->redirectUrl) {

                            $payment_his->status = 'SUCCESS';
                            $payment_his->save();

                            /* $out['type']  = 'success';
                            $out['data']  = $obj->redirectUrl; */
                            //dd($obj->redirectUrl);
                            return Redirect::to($obj->redirectUrl);
                        }
                    } else {
                        $payment_his->status = 'FAIL';
                        $payment_his->save();

                        return Redirect::route('payment')->withErrors(['error' => 'Error While in Payment']);
                    }
                } elseif ($pay_type == 'pay_at_door' || $pay_type == 'collect') {
                    $rtn = $this->PayatDoor($pay_type);
                    if ($rtn == null) {
                        return Redirect::route('payment')->withErrors(['error' => 'Error While in Place Order']);
                    } else {
                        //return redirect()->route('dashboard')->withSuccess('Order Place successfully');
                        $order_no = $rtn->order_no;
                        $order_date = $rtn->formatdate;

                        return redirect()->route('order-success', ['order_no' => $order_no, 'order_date' => $order_date]);
                    }
                }
            } elseif ($pay_type == 'paypal') {

                // $this->paypal($request);

                return Redirect::route('paypal');
            } else {
                return Redirect::route('payment')->withErrors(['error' => 'Payment Error']);
            }
        }
    }

    public function Payreturn(Request $request)
    {

        $id = $request->id;
        $session_id = $request->sessionId;

        $out['data'] = 'error';

        if ($id != null && $session_id != null) {
            $u = config('pay360_config.username');
            $p = config('pay360_config.password');
            $instid = config('pay360_config.hostedCashierId');
            $type = config('pay360_config.type'); //0: Test 1: Prod

            if ($type == 1) {
                $host = config('pay360_config.live_host');
            } else {
                $host = config('pay360_config.test_host');
            }

            $payment_his = new PaymentHistory;
            $payment_his->cus_id = null;
            $payment_his->ord_id = $id;
            $payment_his->type = 'return';
            $payment_his->data = base64_encode($id . '/?sessionId=' . $session_id);
            $payment_his->session_id = $session_id;
            $payment_his->save();

            $response = Pay360Helper::StatusCheckCallUrl($host, $instid, $u, $p, $session_id);
            $obj = json_decode($response);

            $payment_his = new PaymentHistory;
            $payment_his->cus_id = null;
            $payment_his->ord_id = $id;
            $payment_his->type = 'payment_status';
            $payment_his->data = base64_encode(json_encode($obj));
            $payment_his->session_id = $session_id;

            if ($obj->status == 'SUCCESS') {
                $payment_his->status = $obj->status;
                //dd($obj);
                $hostedSessionStatus = $obj->hostedSessionStatus;
                $transactionState = $hostedSessionStatus->transactionState;
                if ($transactionState->transactionState == 'SUCCESS') {

                    $transaction_id = $transactionState->id;
                    if (Session::has('Paydata')) {
                        $Paydata = Session::get('Paydata');
                        $Paydata = $Paydata[0];

                        $email = $Paydata['email'];
                        $phone = $Paydata['phone'];
                        $ship_adr_id = $Paydata['ship_adr_id'];
                        $bill_adr_id = $Paydata['bill_adr_id'];
                        $checkbox = $Paydata['checkbox'];
                        $ship_name = $Paydata['ship_name'];
                        $ship_addr_line_1 = $Paydata['ship_addr_line_1'];
                        $ship_addr_line_2 = $Paydata['ship_addr_line_2'];
                        $ship_city = $Paydata['ship_city'];
                        $ship_pin = $Paydata['ship_pin'];
                        $ship_state = $Paydata['ship_state'];
                        $ship_country = $Paydata['ship_country'];
                        $bill_name = $Paydata['bill_name'];
                        $bill_addr_line_1 = $Paydata['bill_addr_line_1'];
                        $bill_addr_line_2 = $Paydata['bill_addr_line_2'];
                        $bill_city = $Paydata['bill_city'];
                        $bill_pin = $Paydata['bill_pin'];
                        $bill_state = $Paydata['bill_state'];
                        $bill_country = $Paydata['bill_country'];

                        $cus_id = Customer::GetCustomerId($email);
                        if ($cus_id != null) {

                            $arr['name'] = $ship_name;
                            $arr['address_line_1'] = $ship_addr_line_1;
                            $arr['address_line_2'] = $ship_addr_line_2;
                            $arr['city'] = $ship_city;
                            $arr['state'] = $ship_state;
                            $arr['country'] = $ship_country;
                            $arr['postal_code'] = $ship_pin;
                            $shipping_addr_id = Address::InsertAddress($cus_id, $arr, 'shipping');

                            if ($checkbox == 'true') {
                                $billing_addr_id = Address::InsertAddress($cus_id, $arr, 'billing');
                            } else {
                                $arr['name'] = $bill_name;
                                $arr['address_line_1'] = $bill_addr_line_1;
                                $arr['address_line_2'] = $bill_addr_line_2;
                                $arr['city'] = $bill_city;
                                $arr['state'] = $bill_state;
                                $arr['country'] = $bill_country;
                                $arr['postal_code'] = $bill_pin;
                                $billing_addr_id = Address::InsertAddress($cus_id, $arr, 'billing');
                            }
                            $type = 'pay_card';
                            $order = Order::MakeOrder($cus_id, $shipping_addr_id, $billing_addr_id, $obj, $session_id, $phone, $type, $checkbox);
                            if ($order == null) {
                                //error
                                $out['data'] = 'Error in Payment';
                            } elseif ($order != null) {
                                //success
                                $order_no = $order->order_no;
                                $order_date = $order->formatdate;
                                return redirect()->route('order-success', ['order_no' => $order_no, 'order_date' => $order_date]);
                            }
                        }
                    } else {
                        $out['data'] = 'Please Add Item in Cart';
                    }
                } else {
                    $out['data'] = 'Error in Transaction...';
                }
            } else {
                $payment_his->status = $obj->status;
                $out['data'] = 'Error in Transaction..';
            }
            $payment_his->save();
        } else {
            $out['data'] = 'Error Ocurred';
        }

        return redirect()->route('payment')->withErrors(['error' => $out['data']]);
    }

    public function Paycallback(Request $request)
    {
        $data = json_decode(file_get_contents("php://input"));
        $payment_his = new PaymentHistory;
        $payment_his->type = 'payment_callback';
        $payment_his->data = base64_encode($data);
        $payment_his->save();
    }

    public static function PayatDoor($type)
    {
        $rtn = 1;
        $order = null;

        if (Session::has('Paydata')) {
            $Paydata = Session::get('Paydata');
            $Paydata = $Paydata[0];

            $email = $Paydata['email'];
            $phone = $Paydata['phone'];
            $ship_adr_id = $Paydata['ship_adr_id'];
            $bill_adr_id = $Paydata['bill_adr_id'];
            $checkbox = $Paydata['checkbox'];
            $ship_name = $Paydata['ship_name'];
            $ship_addr_line_1 = $Paydata['ship_addr_line_1'];
            $ship_addr_line_2 = $Paydata['ship_addr_line_2'];
            $ship_city = $Paydata['ship_city'];
            $ship_pin = $Paydata['ship_pin'];
            $ship_state = $Paydata['ship_state'];
            $ship_country = $Paydata['ship_country'];
            $bill_name = $Paydata['bill_name'];
            $bill_addr_line_1 = $Paydata['bill_addr_line_1'];
            $bill_addr_line_2 = $Paydata['bill_addr_line_2'];
            $bill_city = $Paydata['bill_city'];
            $bill_pin = $Paydata['bill_pin'];
            $bill_state = $Paydata['bill_state'];
            $bill_country = $Paydata['bill_country'];

            if ($email == null || $email == '') {
                $cus_id = Customer::GetCustomerIdByPhoneno($phone);
            } else {
                $cus_id = Customer::GetCustomerId($email);
            }

            if ($cus_id != null) {

                $arr['name'] = $ship_name;
                $arr['address_line_1'] = $ship_addr_line_1;
                $arr['address_line_2'] = $ship_addr_line_2;
                $arr['city'] = $ship_city;
                $arr['state'] = $ship_state;
                $arr['country'] = $ship_country;
                $arr['postal_code'] = $ship_pin;
                $shipping_addr_id = Address::InsertAddress($cus_id, $arr, 'shipping');



                if ($checkbox == 'true') {
                    $billing_addr_id = Address::InsertAddress($cus_id, $arr, 'billing');
                } else {
                    $arr['name'] = $bill_name;
                    $arr['address_line_1'] = $bill_addr_line_1;
                    $arr['address_line_2'] = $bill_addr_line_2;
                    $arr['city'] = $bill_city;
                    $arr['state'] = $bill_state;
                    $arr['country'] = $bill_country;
                    $arr['postal_code'] = $bill_pin;
                    $billing_addr_id = Address::InsertAddress($cus_id, $arr, 'billing');
                }
                $obj = array();
                $session_id = null;

                $order = Order::MakeOrder($cus_id, $shipping_addr_id, $billing_addr_id, $obj, $session_id, $phone, $type, $checkbox);
            }
        }
        return $order;
    }

    /*super pay start*/
    public static function payOffer()
    {
        $client = new Client();

        $response = $client->request('POST', 'https://api.superpayments.com/v2/offers', [
            'body' => '{"cart":{"items":[{"name":"Amazing boots","url":"https://delivermyvape.co.uk/products/crystal-fresh-menthol-mojito-2-or-20mg-nic","quantity":1,"minorUnitAmount":1000}],"id":"cart101"},"minorUnitAmount":1000,"page":"Checkout","output":"both","scheme":"black-white","test":true}',
            'headers' => [
                'accept' => 'application/json',
                'checkout-api-key' => 'PSK_BQ7Zz2LXoLzVQfUA5wL2N4jCVPw6h5YkD9-_R84b',
                'content-type' => 'application/json',
            ],
        ]);
        $result = $response->getBody();

        $result = json_decode($result);
        $id = $result->cashbackOfferId;

        $site_url = 'https://delivermyvape.co.uk';
        $s_url = $site_url . '/pay-success';
        $c_url = $site_url . '/pay-cancel';
        $f_url = $site_url . '/pay-fail';

        $client = new Client();

        $response = $client->request('POST', 'https://api.superpayments.com/v2/payments', [
            'body' => '{"currency":"GBP","cashbackOfferId":"' . $id . '","successUrl":"' . $s_url . '","cancelUrl":"' . $c_url . '","failureUrl":"' . $f_url . '","minorUnitAmount":1000,"externalReference":"order101","test":true}',
            'headers' => [
                'accept' => 'application/json',
                'checkout-api-key' => 'PSK_BQ7Zz2LXoLzVQfUA5wL2N4jCVPw6h5YkD9-_R84b',
                'content-type' => 'application/json',
            ],
        ]);

        $results = $response->getBody();

        $results = json_decode($results);

        //dd($result);

        $id = $results->transactionId;
        $red_url = $results->redirectUrl;

        //return redirect()->route('pay-payment', $id);
        return Redirect::to($red_url);
    }
    public static function payPayment($id)
    {
        $site_url = 'https://delivermyvape.co.uk';
        $s_url = $site_url . '/pay-success';
        $c_url = $site_url . '/pay-cancel';
        $f_url = $site_url . '/pay-fail';

        $client = new Client();

        $response = $client->request('POST', 'https://api.superpayments.com/v2/payments', [
            'body' => '{"currency":"GBP","cashbackOfferId":"' . $id . '","successUrl":"' . $s_url . '","cancelUrl":"' . $c_url . '","failureUrl":"' . $f_url . '","minorUnitAmount":1000,"externalReference":"order101","test":true}',
            'headers' => [
                'accept' => 'application/json',
                'checkout-api-key' => 'PSK_BQ7Zz2LXoLzVQfUA5wL2N4jCVPw6h5YkD9-_R84b',
                'content-type' => 'application/json',
            ],
        ]);

        $result = $response->getBody();

        $result = json_decode($result);

        //dd($result);

        $id = $result->transactionId;
        $red_url = $result->redirectUrl;
        //echo '<script>window.location.href = '.$red_url.';</script>';

        return Redirect::to($red_url);
    }

    public function PaySuperpay(Request $request)
    {
        $error = 1;
        $out['type'] = 'error';
        $session_cart_data = SessionHelper::ReCreateCart();
        if (!Session::has('AddressData') && Session::get('AddressData') == []) {
            $out['message'] = 'data not found';
        } elseif (!isset($session_cart_data['session_data']) && $session_cart_data['session_data'] == []) {
            $out['message'] = 'Cart is Empty';
        } else {

            $address_data = Session::get('AddressData');
            $address_data = $address_data[0];

            $email = $address_data['email'];
            $phone = $address_data['phone'];
            $ship_adr_id = $address_data['ship_adr_id'];
            $bill_adr_id = $address_data['bill_adr_id'];
            $checkbox = $address_data['checkbox'];
            $name = $ship_name = $address_data['ship_name'];
            $line1 = $ship_addr_line_1 = $address_data['ship_addr_line_1'];
            $line2 = $ship_addr_line_2 = $address_data['ship_addr_line_2'];
            $city = $ship_city = $address_data['ship_city'];
            $postcode = $ship_pin = $address_data['ship_pin'];
            $region = $ship_state = $address_data['ship_state'];
            $ship_country = $address_data['ship_country'];
            $bill_name = $address_data['bill_name'];
            $bill_addr_line_1 = $address_data['bill_addr_line_1'];
            $bill_addr_line_2 = $address_data['bill_addr_line_2'];
            $bill_city = $address_data['bill_city'];
            $bill_pin = $address_data['bill_pin'];
            $bill_state = $address_data['bill_state'];
            $bill_country = $address_data['bill_country'];

            $cus_id = Customer::GetCustomerId($email);
            if ($cus_id != null) {

                $arr['name'] = $ship_name;
                $arr['address_line_1'] = $ship_addr_line_1;
                $arr['address_line_2'] = $ship_addr_line_2;
                $arr['city'] = $ship_city;
                $arr['state'] = $ship_state;
                $arr['country'] = $ship_country;
                $arr['postal_code'] = $ship_pin;
                $shipping_addr_id = Address::InsertAddress($cus_id, $arr, 'shipping');

                if ($checkbox == 'true') {
                    $billing_addr_id = Address::InsertAddress($cus_id, $arr, 'billing');
                } else {
                    $arr['name'] = $bill_name;
                    $arr['address_line_1'] = $bill_addr_line_1;
                    $arr['address_line_2'] = $bill_addr_line_2;
                    $arr['city'] = $bill_city;
                    $arr['state'] = $bill_state;
                    $arr['country'] = $bill_country;
                    $arr['postal_code'] = $bill_pin;
                    $billing_addr_id = Address::InsertAddress($cus_id, $arr, 'billing');
                }

                $file_data = array(
                    'cus_id' => $cus_id,
                    'shipping_addr_id' => $shipping_addr_id,
                    'billing_addr_id' => $billing_addr_id,
                    'phone' => $phone,
                    'type' => 'pay_card',
                    'checkbox' => $checkbox,
                );

                $time = time();
                $rand_no = rand(111111, 999999);
                $fl_name = $rand_order_no = $time . '_' . $rand_no;

                /* $myfile = fopen(public_path().'/text_file/'.$fl_name.'.txt', "w") or die("Unable to open file!");

                $req_dump = base64_encode(json_encode($file_data));

                fwrite($myfile, $req_dump);
                fclose($myfile);
                dd($file_data); */

                $offer_url = config('superpay_config.offer_url');
                $payment_url = config('superpay_config.payment_url');
                $pay_success_url = config('superpay_config.pay-success_url');
                $pay_cancel_url = config('superpay_config.pay-cancel_url');
                $pay_fail_url = config('superpay_config.pay-fail_url');
                $api_key = config('superpay_config.api_key');
                $mode = config('superpay_config.mode');

                $cart_data = $session_cart_data['session_data'];
                $total_amount = (float) $session_cart_data['total'] * 100;
                //$total_amount = 100.00;

                $insert_cart_data = [];
                foreach ($cart_data as $cart) {
                    $tmp = [];
                    $seo_data = Seo::getSeoData('product', $cart['id']);
                    $tmp['name'] = $cart['name'];
                    $tmp['url'] = url('/') . '/products/' . $seo_data['seo_slug_url'];
                    $tmp['quantity'] = $cart['qty'];
                    $tmp['minorUnitAmount'] = (float) $cart['price'];
                    $insert_cart_data[] = $tmp;
                }

                $crt = array(
                    'cart' => array(
                        'items' => $insert_cart_data,
                        "id" => "cart101",
                    ),
                    "minorUnitAmount" => $total_amount,
                    "page" => "Checkout",
                    "output" => "both",
                    "scheme" => "black-white",
                    "test" => $mode,
                );
                $crt = json_encode($crt);

                //for create offer
                $client = new Client();
                $response = $client->request('POST', $offer_url, [
                    'body' => $crt,
                    'headers' => [
                        'accept' => 'application/json',
                        'checkout-api-key' => $api_key,
                        'content-type' => 'application/json',
                    ],
                ]);
                $result = $response->getBody();
                $result = json_decode($result);

                if ($result != null) {
                    $offer_id = $result->cashbackOfferId;
                    if ($offer_id != null) {
                        $body = array(
                            "currency" => "GBP",
                            "cashbackOfferId" => $offer_id,
                            "successUrl" => $pay_success_url,
                            "cancelUrl" => $pay_cancel_url,
                            "failureUrl" => $pay_fail_url,
                            "minorUnitAmount" => $total_amount,
                            "externalReference" => $rand_order_no,
                            "test" => $mode,
                        );
                        $body = json_encode($body);

                        $client = new Client();
                        $response = $client->request('POST', $payment_url, [
                            'body' => $body,
                            'headers' => [
                                'accept' => 'application/json',
                                'checkout-api-key' => $api_key,
                                'content-type' => 'application/json',
                            ],
                        ]);

                        $result = $response->getBody();
                        $result = json_decode($result);

                        $tran_id = $result->transactionId;

                        $ref_code = SessionHelper::getRefSession();
                        $shop_id = Setting::getShopId();
                        $shop_id = SiteSetting::where(['key' => 'order_shop', 'site_id' => config('site_setting.site_id')])->first()->value ?? 22;

                        $file_data['shop_id'] = $shop_id;
                        $file_data['ref_code'] = $ref_code;
                        $file_data['cart_dt'] = json_encode($session_cart_data);
                        $file_data['offer_id'] = $offer_id;
                        $file_data['tran_id'] = $tran_id;

                        $myfile = fopen(public_path() . '/text_file/' . $fl_name . '.txt', "w") or die("Unable to open file!");

                        $req_dump = base64_encode(json_encode($file_data));

                        fwrite($myfile, $req_dump);
                        fclose($myfile);

                        //insert into onlineorder table
                        $online_order = new Onlineorder;
                        $online_order->cus_id = $cus_id;
                        $online_order->order_no = $rand_order_no;
                        $online_order->shipping_addr_id = $shipping_addr_id;
                        $online_order->billing_addr_id = $billing_addr_id;
                        $online_order->phone = $phone;
                        $online_order->type = 'pay_card';
                        $online_order->checkbox = $checkbox;
                        $online_order->offer_id = $offer_id;
                        $online_order->transaction_id = $tran_id;
                        $online_order->cart_dt = json_encode($session_cart_data);
                        $online_order->ref_code = $ref_code;
                        $online_order->shop_id = $shop_id;
                        $online_order->payment_status = 'waiting_payment';
                        $online_order->save();

                        //dd($file_data);

                        $out['type'] = 'success';
                        $out['data'] = $result;
                    }
                }
            }
        }
        echo json_encode($out);
        die;
    }

    public static function paymentStatus($type)
    {
        return view('frontend.payment-status', ['type' => $type]);
    }

    public static function SuperpaySuccess(Request $request)
    {
        $jsonString = file_get_contents("php://input");
        $array = json_decode($jsonString, true);

        $pay_dt = array();
        $pay_dt['response'] = $array;
        $time = time();
        $fl_name = 'pay_data_' . $time;
        $myfile = fopen(public_path() . '/text_file/' . $fl_name . ".txt", "w") or die("Unable to open file!");
        $req_dump = base64_encode(json_encode($array));
        fwrite($myfile, $req_dump);
        fclose($myfile);

        if (isset($array) && $array['transactionAmount'] != '' && $array['transactionId'] != '' && $array['transactionStatus'] != '' && $array['eventType'] != '' && $array['transactionReference'] != '' && $array['externalReference'] != '') {
            $online_order = Onlineorder::where('order_no', $array['externalReference'])->first();
            if ($online_order != null) {
                $shop_id = $online_order->shop_id;
                $ref_code = $online_order->ref_code;
                $cart_dt = $online_order->cart_dt;
                $odr_status = $online_order->payment_status;
                $odr_data = $online_order->data;
                $odr_data = ($odr_data != null ? json_decode($odr_data) : array());

                $odr_data[] = $array;
                if ($array['transactionStatus'] == 'PaymentSuccess') {
                    if ($odr_status == 'waiting_payment') {
                        $pay_dt['msg'] = 'successfully payment received';
                        $odr_status = 'success_payment';

                        $cus_id = $online_order->cus_id;
                        $shipping_addr_id = $online_order->shipping_addr_id;
                        $billing_addr_id = $online_order->billing_addr_id;
                        $session_id = null;
                        $phone = $online_order->phone;
                        $type = $online_order->type;
                        $checkbox = $online_order->checkbox;

                        Order::MakeOrder($cus_id, $shipping_addr_id, $billing_addr_id, $odr_data, $session_id, $phone, $type, $checkbox, $cart_dt, $ref_code, $shop_id);
                    } else {
                        $pay_dt['msg'] = 'successfully payment received but order status in db mishmatch';
                        $odr_status = 'mishmatch_payment';
                    }
                } else {
                    $pay_dt['msg'] = 'transction status fail : ' . $array['transactionStatus'];
                    $odr_status = 'fail_payment';
                }

                $online_order->data = json_encode($odr_data);
                $online_order->payment_status = $odr_status;
                $online_order->save();
            } else {
                $pay_dt['msg'] = 'successfully payment received but order data not found in db';
            }
        } else {
            $pay_dt['msg'] = 'payment data not responde.';
        }

        $time = time();
        $fl_name = 'pay_data_with-msg' . $time;
        $myfile = fopen(public_path() . '/text_file/' . $fl_name . ".txt", "w") or die("Unable to open file!");
        $req_dump = base64_encode(json_encode($pay_dt));
        fwrite($myfile, $req_dump);
        fclose($myfile);
    }

    public static function SuperpaySuccesss(Request $request)
    {
        //$jsonString = file_get_contents("php://input");
        //$array = json_decode($jsonString, true);
        $arr_str = '{"transactionAmount":100,"transactionId":"e9138bb5-fe45-49fc-9167-61e703a961b2","transactionStatus":"PaymentSuccess","eventType":"PaymentStatus","transactionReference":"OB3GHUEJMC5CM2DYK7","externalReference":"1685786959_354585"}';
        $array = json_decode($arr_str, true);

        $pay_dt = array();
        $pay_dt['response'] = $array;
        $time = time();
        /*$fl_name = 'pay_data_'.$time;
        $myfile = fopen(public_path().'/text_file/'.$fl_name.".txt", "w") or die("Unable to open file!");
        $req_dump = base64_encode(json_encode($array));
        fwrite($myfile, $req_dump);
        fclose($myfile);
         */
        if (isset($array) && $array['transactionAmount'] != '' && $array['transactionId'] != '' && $array['transactionStatus'] != '' && $array['eventType'] != '' && $array['transactionReference'] != '' && $array['externalReference'] != '') {
            $online_order = Onlineorder::where('order_no', $array['externalReference'])->first();
            if ($online_order != null) {
                $shop_id = $online_order->shop_id;
                $ref_code = $online_order->ref_code;
                $cart_dt = $online_order->cart_dt;
                $odr_status = $online_order->payment_status;
                $odr_data = $online_order->data;
                $odr_data = ($odr_data != null ? json_decode($odr_data) : array());

                $odr_data[] = $array;
                if ($array['transactionStatus'] == 'PaymentSuccess') {
                    if ($odr_status == 'waiting_payment') {

                        $pay_dt['msg'] = 'successfully payment received';
                        $odr_status = 'success_payment';

                        $cus_id = $online_order->cus_id;
                        $shipping_addr_id = $online_order->shipping_addr_id;
                        $billing_addr_id = $online_order->billing_addr_id;
                        $session_id = null;
                        $phone = $online_order->phone;
                        $type = $online_order->type;
                        $checkbox = $online_order->checkbox;

                        Order::MakeOrder($cus_id, $shipping_addr_id, $billing_addr_id, $odr_data, $session_id, $phone, $type, $checkbox, $cart_dt, $ref_code, $shop_id);
                    } else {
                        $pay_dt['msg'] = 'successfully payment received but order status in db mishmatch';
                        $odr_status = 'mishmatch_payment';
                    }
                } else {
                    $pay_dt['msg'] = 'transction status fail : ' . $array['transactionStatus'];
                    $odr_status = 'fail_payment';
                }

                $online_order->data = json_encode($odr_data);
                $online_order->payment_status = $odr_status;
                $online_order->save();
            } else {
                $pay_dt['msg'] = 'successfully payment received but order data not found in db';
            }
        } else {
            $pay_dt['msg'] = 'payment data not responde.';
        }

        $time = time();
        $fl_name = 'pay_data_with-error' . $time;
        $myfile = fopen(public_path() . '/text_file/' . $fl_name . ".txt", "w") or die("Unable to open file!");
        $req_dump = base64_encode(json_encode($pay_dt));
        fwrite($myfile, $req_dump);
        fclose($myfile);
    }

    public static function SuperpayFail(Request $request)
    {
        return redirect()->route('payment-status', ['status' => 'Fail']);
    }

    public static function SuperpayRefund(Request $request)
    {
        return redirect()->route('payment-status', ['status' => 'Fail']);
    }
}
