<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Address;
use App\Models\Card;
use App\Models\Order;
use App\Models\PaymentHistory;
use App\Models\PostCode;
use App\Helpers\SessionHelper;
use App\Helpers\Pay360Helper;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{

  public function index()
  {
    $total = 0;
    $shipping = 0;
    $total = SessionHelper::getCartTotalAmount();
    if ($total <= 0) {
      return redirect()->route('dashboard')->withErrors(['error' => 'The cart is empty please add item in cart to make payment']);
    }
    $discount = Order::getDiscountAmount();
    $shipping = sprintf('%.2f', SessionHelper::getShippingCharge($total));
    $total = $total + $shipping - $discount;

    $total = number_format((float)$total, 2, '.', '');
    return view('frontend.payment', compact('total'));
  }

  /*   public function Paynow(Request $request)
  {
    $out = [];
    $out['type']  = 'error';
    $error = 1;
    $validator = Validator::make($request->all(), [
      'email' => 'required|email',
      'phone' => 'required|numeric|digits:10',
      'card_no' => 'required|numeric|digits:16',
      'exp_date' => 'required|numeric|digits:4',
      'cvc' => 'required|numeric|digits:3',
    ]);

    $email = $request->email;
    $phone = $request->phone;
    $card_no = $request->card_no;
    $exp_date = $request->exp_date;
    $exp_date = substr_replace($exp_date, '/', 2, 0 );
    $cvc = $request->cvc;

    if($validator->fails()) {
      $out['data']  = $validator->messages()->first();
    }else{
      $error = 0;
    }

    $ship_adr_id = $request->ship_address_id;
    $bill_adr_id = $request->bill_address_id;
    $checkbox = $request->checkbox;

    if($error == 0){
      if($ship_adr_id == null){
        $ship_name = $request->ship_name;
        $ship_addr_line_1 = $request->ship_addr_line_1;
        $ship_addr_line_2 = $request->ship_addr_line_2;
        $ship_city = $request->ship_city;
        $ship_pin = $request->ship_pin;
        $ship_state =$request->ship_state;
        $ship_country = $request->ship_country;

        if($ship_name == null){
          $error = 1;
          $out['data']  = 'Shipping Name is Required';
        }
        elseif($ship_addr_line_1 == null){
          $error = 1;
          $out['data']  = 'Shipping Address 1 is Required';
        }
        elseif($ship_addr_line_2 == null){
          $error = 1;
          $out['data']  = 'Shipping Address 2 is Required';
        }
        elseif($ship_city == null){
          $error = 1;
          $out['data']  = 'Shipping City is Required';
        }
        elseif($ship_pin == null){
          $error = 1;
          $out['data']  = 'Shipping Pin is Required';
        }
        elseif($ship_state == null){
          $error = 1;
          $out['data']  = 'Shipping State is Required';
        }
        elseif($ship_country == null){
          $error = 1;
          $out['data']  = 'Shipping Country is Required';
        }else{
          $error = 0;
        }
      }

      if($checkbox == 'false' && $bill_adr_id == null){
        $bill_name = $request->bill_name;
        $bill_addr_line_1 = $request->bill_addr_line_1;
        $bill_addr_line_2 = $request->bill_addr_line_2;
        $bill_city = $request->bill_city;
        $bill_pin = $request->bill_pin;
        $bill_state = $request->bill_state;
        $bill_country = $request->bill_country;

        if($bill_name == null){
          $error = 1;
          $out['data']  = 'Billing Name is Required';
        }
        elseif($bill_addr_line_1 == null){
          $error = 1;
          $out['data']  = 'Billing Address 1 is Required';
        }
        elseif($bill_addr_line_2 == null){
          $error = 1;
          $out['data']  = 'Billing Address 2 is Required';
        }
        elseif($bill_city == null){
          $error = 1;
          $out['data']  = 'Billing City is Required';
        }
        elseif($bill_pin == null){
          $error = 1;
          $out['data']  = 'Billing Pin is Required';
        }
        elseif($bill_state == null){
          $error = 1;
          $out['data']  = 'Billing State is Required';
        }
        elseif($bill_country == null){
          $error = 1;
          $out['data']  = 'Billing Country is Required';
        }else{
          $error = 0;
        }
      }else{
        $error = 0;
      }
    }

    if($error == 0){

      $payService = new \PAY360\libraries\transactions();
      $custService = new \PAY360\libraries\customers();

      $username = 'RNWDUCYQFZHV7ACR2ABSGMD3PE';
      $password = 'EKKwyX18ZUcRxfb02Rve9w==';
      $cardLockId = 'vRwNtVOZSTyVpk5jWWQvAw';
      $hostedCashierId = '5310864';
      $cashierId = '5310865';
      $type = 0; //0: Test 1: Prod

      $payService->setUsername($username)
        ->setPassword($password)
        ->setCardLockId($cardLockId)
        ->setHostedCashierId($hostedCashierId)
        ->setCashierId($cashierId)
        ->setType($type); // 0:test 1:prod


      if($ship_adr_id != null){
        $ship_add = Address::find($ship_adr_id);
        if($ship_add != null){
          $ship_name = $ship_add->name;
          $ship_addr_line_1 = $ship_add->address_line_1;
          $ship_addr_line_2 = $ship_add->address_line_2;
          $ship_city = $ship_add->city;
          $ship_pin = $ship_add->postal_code;
          $ship_state = $ship_add->state;
          $ship_country = $ship_add->country;
        }
      }

      if($bill_adr_id != null){
        $bill_add = Address::find($bill_adr_id);
        if($bill_add != null){
          $bill_name = $bill_add->name;
          $bill_addr_line_1 = $bill_add->address_line_1;
          $bill_addr_line_2 = $bill_add->address_line_2;
          $bill_city = $bill_add->city;
          $bill_pin = $bill_add->postal_code;
          $bill_state = $bill_add->state;
          $bill_country = $bill_add->country;
        }
      }


      $p_data = '{
        "transaction": {
          "currency": "GBP",
          "amount": "1.0",
          "description": "First Product",
          "merchantRef": "mer_txn_1234556_ODR_NO",
          "commerceType": "ECOM",
          "channel": "WEB"
        },
        "paymentMethod": {
          "card": {
            "pan": '.$card_no.',
            "cv2": '.$cvc.',
            "expiryDate": '.$exp_date.',
            "startDate": "1111",
            "cardHolderName": "John Smith",
            "defaultCard": "false"
          },
          "billingAddress": {
            "line1": "28 Butter Market",
            "city": "Bury",
            "region": "Saint Edmunds",
            "postcode": "IP33 1DW",
            "countryCode": "GBR"
          }
        },
        "customer": {
          "merchantRef": "mer_cust_131241413",
          "displayName": "Mr O Whatasillyname",
          "billingAddress": {
            "line1": "28 Butter Market",
            "city": "Bury",
            "region": "Saint Edmunds",
            "postcode": "IP33 1DW",
            "countryCode": "GBR"
          },
          "email": '.$email.',
          "telephone": '.$phone.',
          "defaultCurrency": "GBP",
          "ip": "212.58.253.67"
        }
      }';
      $d = $payService->setPostData(json_decode($p_data))->payment(); // $p_data -> array data -> review documents
      //$status = $d['data']['outcome']['status'];
      $status = 'SUCCESS';
      if($status == 'SUCCESS'){

        //$email = 'vaghadiyabhavin61@gmail.com';
        $cus_id = Customer::GetCustomerId($email);
        if($cus_id != null){

          $card_arr=[];
          $card_arr['card_number'] = $card_no;
          $card_arr['card_expiry_date'] = $exp_date;
          $card_arr['card_holder_name'] = 'new name';

          $card_id = Card::InsertUpdateCard($cus_id,$card_arr);

          $arr['name'] = $ship_name;
          $arr['address_line_1'] = $ship_addr_line_1;
          $arr['address_line_2'] = $ship_addr_line_2;
          $arr['city'] = $ship_city;
          $arr['state'] = $ship_state;
          $arr['country'] = $ship_country;
          $arr['postal_code'] = $ship_pin;
          $shipping_addr_id = Address::InsertAddress($cus_id,$arr,'shipping');

          if($checkbox == 'true'){
            $billing_addr_id = Address::InsertAddress($cus_id,$arr,'billing');
          }else{
            $arr['name'] = $bill_name;
            $arr['address_line_1'] = $bill_addr_line_1;
            $arr['address_line_2'] = $bill_addr_line_2;
            $arr['city'] = $bill_city;
            $arr['state'] = $bill_state;
            $arr['country'] = $bill_country;
            $arr['postal_code'] = $bill_pin;
            $billing_addr_id = Address::InsertAddress($cus_id,$arr,'billing');
          }
          $ord_status = Order::MakeOrder($cus_id,$card_id,$shipping_addr_id,$billing_addr_id);
          if($ord_status == 1){
            //error
            $out['type']  = 'error';
            $out['data']  = 'Error in Payment';
          }elseif($ord_status == 0){
            //success
            $out['type']  = 'success';
            $out['data']  = 'Order Placed Successfull';
          }
        }

      }elseif($status == 'FAIL'){
        $out['type']  = 'error';
        $out['data']  = 'Error While in Payment';
      }
    }

    echo json_encode($out);
    die;
  } */


  public function Paynow(Request $request)
  {
    $out = [];
    $out['type']  = 'error';
    $out['data']  = 'Error While in Payment';

    $error = 1;
    $validator = Validator::make($request->all(), [
      'email' => 'required|email',
      'phone' => 'required',
    ]);

    $email = $request->email;
    $phone = $request->phone;

    if ($validator->fails()) {
      $out['data']  = $validator->messages()->first();
    } else {
      $error = 0;
    }

    $ship_adr_id = $request->ship_address_id;
    $bill_adr_id = $request->bill_address_id;
    $checkbox = $request->checkbox;
    $pay_type = $request->pay_type;

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
          $out['data']  = 'Shipping Name is Required';
        } elseif ($ship_addr_line_1 == null) {
          $error = 1;
          $out['data']  = 'Shipping Address 1 is Required';
        } elseif ($ship_city == null) {
          $error = 1;
          $out['data']  = 'Shipping Town is Required';
        } elseif ($ship_pin == null) {
          $error = 1;
          $out['data']  = 'Shipping Postcode is Required';
        } elseif ($ship_state == null) {
          $error = 1;
          $out['data']  = 'Shipping County is Required';
        } elseif ($ship_country == null) {
          $error = 1;
          $out['data']  = 'Shipping Country is Required';
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
          $out['data']  = 'Billing Name is Required';
        } elseif ($bill_addr_line_1 == null) {
          $error = 1;
          $out['data']  = 'Billing Address 1 is Required';
        } elseif ($bill_city == null) {
          $error = 1;
          $out['data']  = 'Billing Town is Required';
        } elseif ($bill_pin == null) {
          $error = 1;
          $out['data']  = 'Billing Postcode is Required';
        } elseif ($bill_state == null) {
          $error = 1;
          $out['data']  = 'Billing County is Required';
        } elseif ($bill_country == null) {
          $error = 1;
          $out['data']  = 'Billing Country is Required';
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
          $name = $ship_add->name;
          $line1 = $ship_add->address_line_1;
          $line2 = $ship_add->address_line_2;
          $city = $ship_add->city;
          $postcode = $ship_add->postal_code;
          $region = $ship_add->state;
          $countryCode = $ship_add->country;

          $ship_name = $ship_add->name;
          $ship_addr_line_1 = $ship_add->address_line_1;
          $ship_addr_line_2 = $ship_add->address_line_2;
          $ship_city = $ship_add->city;
          $ship_pin = $ship_add->postal_code;
          $ship_state = $ship_add->state;
          $ship_country = $ship_add->country;
        }
      } else {
        $name = $request->ship_name;
        $line1 = $request->ship_addr_line_1;
        $line2 = $request->ship_addr_line_2;
        $city = $request->ship_city;
        $postcode = $request->ship_pin;
        $region = $request->ship_state;
        $countryCode = $request->ship_country;

        $ship_name = $request->ship_name;
        $ship_addr_line_1 = $request->ship_addr_line_1;
        $ship_addr_line_2 = $request->ship_addr_line_2;
        $ship_city =  $request->ship_city;
        $ship_pin = $request->ship_pin;
        $ship_state = $request->ship_state;
        $ship_country = $request->ship_country;
      }

      if ($checkbox == 'false') {
        if ($bill_adr_id != null) {
          $bill_add = Address::find($bill_adr_id);
          if ($bill_add != null) {
            $name = $bill_add->name;
            $line1 = $bill_add->bill_addr_line_1;
            $line2 = $bill_add->bill_addr_line_2;
            $city = $bill_add->bill_city;
            $postcode = $bill_add->bill_pin;
            $region = $bill_add->bill_state;
            $countryCode = $bill_add->bill_country;

            $bill_name = $bill_add->name;
            $bill_addr_line_1 = $bill_add->address_line_1;
            $bill_addr_line_2 = $bill_add->address_line_2;
            $bill_city = $bill_add->city;
            $bill_pin = $bill_add->postal_code;
            $bill_state = $bill_add->state;
            $bill_country = $bill_add->country;
          }
        } else {
          $name = $request->bill_name;
          $line1 = $request->bill_addr_line_1;
          $line2 = $request->bill_addr_line_2;
          $city = $request->bill_city;
          $postcode = $request->bill_pin;
          $region = $request->bill_state;
          $countryCode = $request->bill_country;

          $bill_name = $request->bill_name;
          $bill_addr_line_1 =  $request->bill_addr_line_1;
          $bill_addr_line_2 = $request->bill_addr_line_2;
          $bill_city = $request->bill_city;
          $bill_pin =  $request->bill_pin;
          $bill_state = $request->bill_state;
          $bill_country = $request->bill_country;
        }
      } else {
        $name = $ship_name;
        $line1 = $ship_addr_line_1;
        $line2 = $ship_addr_line_2;
        $city =  $ship_city;
        $postcode = $ship_pin;
        $region = $ship_state;
        $countryCode = $ship_country;

        $bill_name = $ship_name;
        $bill_addr_line_1 = $ship_addr_line_1;
        $bill_addr_line_2 = $ship_addr_line_2;
        $bill_city = $ship_city;
        $bill_pin = $ship_pin;
        $bill_state = $ship_state;
        $bill_country = $ship_country;
      }


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
        $hostedCashierId =  $instid = config('pay360_config.hostedCashierId');
        $cashierId = config('pay360_config.cashierId');
        $type = config('pay360_config.type'); //0: Test 1: Prod

        if ($type == 1) {
          $host = config('pay360_config.live_host');
        } else {
          $host = config('pay360_config.test_host');
        }
        $order_id = Order::GetOrderId();


        //https://www.bronco.co.uk/our-ideas/integrating-pay360-advanced-hosted-in-php/
        //https://docs.pay360.com/cards/payments/
        /*
        9900000000005159

        https://api.mite.pay360.com/hosted/rest/sessions/5310864/SMjpldnyJTelJernPXZAEbB7P/status
        */

        $site_url = env('APP_URL');
        $total_amt = SessionHelper::getCartTotalAmount();
        $shipping = sprintf('%.2f', SessionHelper::getShippingCharge($total_amt));
        $discount = Order::getDiscountAmount();
        $total_amt = $total_amt + $shipping - $discount;


        $post = array(
          "session" => array(
            "returnUrl" => array("url" => $site_url . '/pay_return/' . $order_id . '/'),
            "transactionNotification" => array(
              "url" => $site_url . "/pay_callback",
              "format" => "REST_JSON"
            )
          ),
          "transaction" => array(
            "merchantReference" => $order_id,
            "money" => array("amount" => array("fixed" => $total_amt), "currency" => Config('currency.currency_text'))
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
              "defaultCurrency" =>  Config('currency.currency_text'),
            )
          )
        );

        $payment_his = new PaymentHistory;
        $payment_his->cus_id = null;
        $payment_his->ord_id = $order_id;
        $payment_his->type = 'init';
        $payment_his->data = base64_encode(json_encode($post));

        $response = Pay360Helper::CallUrl($host, $instid, $u, $p, $post);
        $obj = json_decode($response);
        $status = $obj->status;

        if ($status == 'SUCCESS') {
          if ($obj->redirectUrl) {

            $payment_his->status = 'SUCCESS';
            $out['type']  = 'success';
            $out['data']  = $obj->redirectUrl;
          }
        } else {
          $payment_his->status = 'FAIL';
          $out['type']  = 'error';
          $out['data']  = 'Error While in Payment';
        }
        $payment_his->save();
      } elseif ($pay_type == 'pay_at_door' || $pay_type == 'collect') {

        $rtn = $this->PayatDoor($pay_type);
        if ($rtn == null) {
          $out['type']  = 'error';
          $out['data']  = 'Error While in Order';
        } else {
          //return redirect()->route('dashboard')->withSuccess('Order Place successfully');
          $order_no = $rtn->order_no;
          $order_date = $rtn->formatdate;

          $url = route('order-success', ['order_no' => $order_no, 'order_date' => $order_date]);
          $out['type']  = 'success';
          $out['data'] = $url;
        }
      }
    }

    echo json_encode($out);
    die;
  }

  function Payreturn(Request $request)
  {

    $id = $request->id;
    $session_id = $request->sessionId;

    $out['data']  = 'error';

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
            $Paydata  = $Paydata[0];

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

              /*
              $card_no = 123;
              $exp_date = '20/12';

              $card_arr=[];
              $card_arr['card_number'] = $card_no;
              $card_arr['card_expiry_date'] = $exp_date;
              $card_arr['card_holder_name'] = 'new name';

              $card_id = Card::InsertUpdateCard($cus_id,$card_arr);

               */

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
                $out['data']  = 'Error in Payment';
              } elseif ($order != null) {
                //success
                $order_no = $order->order_no;
                $order_date = $order->formatdate;
                //return redirect()->view('frontend.order-success',compact(["order_no","order_date"]));
                return redirect()->route('order-success', ['order_no' => $order_no, 'order_date' => $order_date]);
              }
            }
          } else {
            $out['data']  = 'Please Add Item in Cart';
          }
        } else {
          $out['data']  = 'Error in Transaction...';
        }
      } else {
        $payment_his->status = $obj->status;
        $out['data']  = 'Error in Transaction..';
      }
      $payment_his->save();
    } else {
      $out['data']  = 'Error Ocurred';
    }

    return redirect()->route('payment')->withErrors(['error' => $out['data']]);
  }

  function Paycallback(Request $request)
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
      $Paydata  = $Paydata[0];

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

        /*
        $card_no = 123;
        $exp_date = '20/12';

        $card_arr=[];
        $card_arr['card_number'] = $card_no;
        $card_arr['card_expiry_date'] = $exp_date;
        $card_arr['card_holder_name'] = 'new name';

        $card_id = Card::InsertUpdateCard($cus_id,$card_arr);

         */

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
        $order = Order::MakeOrder($cus_id, $shipping_addr_id, $billing_addr_id, $obj, $session_id, $phone, $type);
      }
    }
    return $order;
  }

  public static function VerifyPostcode(Request $request)
  {


    // <div class="col-lg-6 align-self-center d-grid mb-3">
    //     <button class="btn btn-default btn-theme shadow-sm" onclick="pay_now(pay_type='pay_card')">Pay Now</button>
    // </div>
    // <div class="col-lg-12 align-self-center d-grid mb-3">
    //   <button class="btn btn-default btn-theme shadow-sm" onclick="pay_now(pay_type='pay_at_door')">Pay at Door</button>
    // </div>
    // <div class="col-lg-12 align-self-center d-grid mb-3">
    //   <button class="btn btn-default btn-theme shadow-sm" onclick="pay_now(pay_type='collect')">Collect Order</button>
    // </div>

    $postcode = $request->postcode;
    $dt_html = '';
    if ($postcode != null) {
      $shop_id = Setting::getShopId();
      $postcode_verify = PostCode::VerifyPostcode($shop_id, $postcode);

      ob_start();
      if ($postcode_verify == 'found') { ?>

        <div class="col-lg-12 align-self-center d-grid mb-3">
          <button class="btn btn-default btn-theme shadow-sm pay_now" data-type='pay_card' data-check="<?php echo Auth::check() ?>">Pay Now</button>
        </div>
        <div class="col-lg-12 align-self-center d-grid mb-3">
          <button class="btn btn-default btn-theme shadow-sm pay_now" data-type='pay_at_door' data-check="<?php echo Auth::check() ?>">Pay at Door</button>
        </div>
        <div class="col-lg-12 align-self-center d-grid mb-3">
          <button class="btn btn-default btn-theme shadow-sm pay_now" data-type='collect' data-check="<?php echo Auth::check() ?>">Collect Order</button>
        </div>

      <?php } else { ?>
        <div class="col-lg-12 align-self-center d-grid mb-3">
          <label>* This postcode Delivery By Post</label>
          <button class="btn btn-default btn-theme shadow-sm pay_now" data-type='pay_card' data-check="<?php echo Auth::check() ?>">Pay Now</button>
        </div>
<?php }
      $out['type']  = 'success';
    } else {
      $out['type']  = 'error';
    }

    $dt_html .= ob_get_contents();
    ob_end_clean();

    $out['html'] = $dt_html;

    echo json_encode($out);
  }
}
