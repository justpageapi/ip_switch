<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Customer;
use App\Models\EmailSendData;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;




class AccountController extends Controller
{
  //---------------------------------- category----------------------------------
  public function index()
  {
    /*Session::forget('cart');
      Session::save();
      die;*/
    if (Auth::check()) {
      $cus_id = Auth::user()->id;

      $customer = Customer::with('referrals')->find($cus_id);
      return view('frontend.account', compact('customer'));
    }
    return redirect()->back();
  }

  public function get_wallet_data(Request $request)
  {
    $out['type']      = 'error';
    $out['msg']      = 'Error Accoured';

    $id = $request->id;
    $cus_id = $request->cus_id;
    $ttl = 0;

    if (isset($id)) {
      if ($cus_id == null) {
        $cus_id = Auth::user()->id;
      }
      $wallet = Wallet::with('messages')->where('cus_id', $cus_id)->where('id', $id)->first();
      $data = '';
      if ($wallet) {
        if ($wallet->message == 'new order') {
          $details = json_decode($wallet->details);
          $order = Order::where('order_no', $details[0])->first();
          ob_start();

          $data .= '<table class="table">
                  <thead>
                  <tr>
                    <th scope="col">Item name</th>
                    <th scope="col">Qty</th>
                    <th scope="col">Price</th>
                  </tr>
                </thead>
            <tbody>';
          foreach ($order->orderitem as $item) {
            $ttl += ($item->price * $item->qty);
            $data .= '
              <tr>
                <td>' . $item->variant->name . '</td>
                <td>' . $item->qty . '</td>
                <td>' . env('symbol') . $item->price . '</td>
              </tr>
              ';
          }
          $data .=
            '
            <tr>
              <td><b>Total Amount</b></td>
              <td></td>
              <td>' . env('symbol') . $ttl + $order->delivery_charge . '</td>
            </tr>
            ';
          $data .= '</tbody></table>';
          $out['type'] = 'success';
          $out['title'] = 'Order no : ' . $order->order_no;
          $out['msg']      = 'data get successfully';
        } elseif ($wallet->message == 'refund') {
          $details = json_decode($wallet->details);
          $data .= '
            <div class="col">
            <p>' . $details[0] . '<p>
            </div>
            ';
          $out['type'] = 'success';
          $out['title'] = 'Payment Recived';
          $out['msg']      = 'data get successfully';
        } elseif ($wallet->message == 'return & refund') {
          $details = json_decode($wallet->details);
          $data .= '
            <div class="col">
            <p class="mb-1">' . $details[0] . '<p>';
          if (isset($details[1])) {

            $data .= '<p>' . $details[1] . '<p>';
          }
          $data .= '</div>
            ';
          $out['type'] = 'success';
          $out['title'] = 'Refund';
          $out['msg']      = 'data get successfully';
        } elseif ($wallet->message == 'add_invoice') {
          $details = json_decode($wallet->details);
          $data .= '
            <div class="col">
            <p class="mb-1">' . $details[0] . '<p>';
          if (isset($details[1])) {

            $data .= '<p>' . $details[1] . '<p>';
          }
          $data .= '</div>
            ';
          $out['type'] = 'success';
          $out['title'] = 'Old Invoice Credit';
          $out['msg']      = 'data get successfully';
        } elseif ($wallet->message == 'order payment') {
          $details = json_decode($wallet->details);
          $pay_data = explode(',', $details[0]);
          $data .= '
            <div class="col">
            <li>Pay with : ' . $pay_data[1] . '</li>
            <li>Pay ID : ' . $pay_data[2] . '</li>
            <li>Pay Amount : ' . env('symbol') . $wallet->amount . '</li>
            </div>
            ';
          $out['type'] = 'success';
          $out['title'] = 'Order Payment';
          $out['msg']      = 'data get successfully';
        } elseif ($wallet->message == 'funds of invoice') {
          $details = json_decode($wallet->details);
          $order = Order::with('remaining_item')->where('order_no', $details[0])->first();
          $data .= '
            <table class="table">
                  <thead>
                  <tr>
                    <th scope="col">Item name</th>
                    <th scope="col">Qty</th>
                    <th scope="col">Price</th>
                  </tr>
                </thead>
            <tbody>';
          foreach ($order->remaining_item as $item) {
            $price = $item->variant->get_price($cus_id, $item->variant->id);
            $data .= '
              <tr>
                <td>' . $item->variant->name . '</td>
                <td>' . $item->remaining_qty . '</td>
                <td>' . env('symbol') . $price . '</td>
              </tr>
              ';
          }
          $data .= '</tbody></table>';
          $out['type'] = 'success';
          $out['title'] = 'item not send in order no : ' . $order->order_no;
          $out['msg']      = 'data get successfully';
        }
      }
    }
    $out['data'] = $data;
    echo json_encode($out);
    die;
  }

  public static function sendExtraDiscountEmail()
  {
    $cust_list = Customer::get();
    if (count($cust_list) > 0) {
      foreach ($cust_list as $c) {
        $chk_repeat = Order::with('customer')->where('cus_email', $c->email)->first();
        if ($chk_repeat != null) {
          $arr = array();
          $arr['cus_id'] = $c->id;
          $arr['cus_name'] = $c->username;
          $arr['cus_email'] = $c->email;
          $arr['subject'] = config('email_message.extra_discount_reminder');
          $arr['ord_id'] = '';
          $arr['type'] = 'extra_discount_reminder';
          $arr['data'] = '';
          EmailSendData::insert_data($arr);
        }
      }
    }
  }
  public static function sendEmail()
  {
    $id = 4108;
    EmailSendData::sendmail($id);
    //   $email_data = EmailSendData::find($id);
    // $startWeek = Carbon::now()->startOfWeek();
    // $endWeek = Carbon::now()->endOfWeek();
    // $email_data = EmailSendData::where('is_send', 0)->where('type', 'extra_discount_reminder')->whereBetween('created_at', [$startWeek, $endWeek])->take(5)->get();
    // if (count($email_data) > 0) {
    //   foreach ($email_data as $e) {
    //     EmailSendData::sendmail($e->id);
    //   }
    // }
  }
}
