<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;
use App\Helpers\SessionHelper;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Http\Controllers\fpdf\PDF_MC_Table_new;
use Illuminate\Support\Facades\Storage;
use File;
use Illuminate\Support\Facades\Auth;

class Order extends Model
{
  use HasFactory;
  protected $table = 'order';
  protected $primaryKey = 'id';
  protected $hidden = [
    'created_at',
    'updated_at',
  ];
  protected $appends = ['formatdate'];


  public function orderitem()
  {
    return $this->hasMany(OrderItem::class, 'order_id', 'id');
  }

  public function customer()
  {
    return $this->belongsTo(Customer::class, 'customer_id', 'id');
  }

  public function getformatdateAttribute()
  {
    return Carbon::parse($this->created_at)->format('M d Y');
  }

  public static function getVeifyPhone($cus_id, $phone)
  {
    $is_verify = 0;
    $order = Order::where(array('customer_id' => $cus_id, 'contact' => $phone))->latest()->first();
    if ($order != null) {
      $is_verify = $order->contact_verify;
    }
    return $is_verify;
  }

  public static function MakeOrder($cus_id, $shipping_addr_id, $billing_addr_id, $obj, $session_id, $phone, $type, $checkbox, $cart_dt = null, $ref_codes = null, $shop_id = null)
  {
    $order = null;
    $is_guest_order = 1;
    if (Auth::user()) {
      $cus_id = Auth()->id();
      $is_guest_order = 0;
    }
    $cus_data = Customer::find($cus_id);
    $shipping_addr_data = Address::find($shipping_addr_id);
    $billing_addr_data = Address::find($billing_addr_id);
    //$sesssion_cart_data = Session::get('Cart');

    $sub_total = 0;
    $total = 0;
    $shipping = 0;
    $total_qty = 0;
    $discount = 0;
    $referral = $referral_percentage = $referral_id = 0;

    if ($ref_codes != null) {
      $ref_code = $ref_codes;
    } else {
      $ref_code = SessionHelper::getRefSession();
    }

    if ($cart_dt != null) {
      $session_data = json_decode($cart_dt, true);
    } else {
      $session_data = SessionHelper::ReCreateCart();
    }
    $sesssion_cart_data = $session_data['session_data'];
    $sub_total = $session_data['sub_ttl'];
    $discount = $session_data['discount'];
    $shipping = $session_data['shipping'];
    $total = $session_data['total'];
    $total_qty = $session_data['total_qty'];
    $referral = $session_data['ref_dicount'];
    $referral_percentage = $session_data['ref_percentage'];
    $referral_id = $session_data['ref_id'];
    $extra_discount = $session_data['extra_discount'];
    $extra_discount_percentage = $session_data['extra_discount_percentage'];


    $error = 1;
    $order_status = 'pending';

    $discount = Order::getDiscountAmount($session_data);

    $session_id = $session_id;
    $pay_response = json_encode($obj);

    if ($type == 'pay_card') {
      $payment_status = 'paid';
      $order_type = 'post';
    } elseif ($type == 'pay_at_door') {
      $payment_status = 'unpaid';
      $order_type = 'delivery';
    } elseif ($type == 'collect') {
      $payment_status = 'unpaid';
      $order_status = 'collect';
      $pay_response = 'Collect Order';
      $order_type = 'collect';
    }
    $p_data = $payment_status;
    if ($cus_data != null && isset($sesssion_cart_data) && count($sesssion_cart_data) > 0) {

      $ship_address[] = $shipping_addr_data->address_line_1 . ',' . $shipping_addr_data->address_line_2 . ',' . $shipping_addr_data->city . ',' . $shipping_addr_data->state . ',' . $shipping_addr_data->country . ',' . $shipping_addr_data->postal_code;
      $bill_address[] = $billing_addr_data->address_line_1 . ',' . $billing_addr_data->address_line_2 . ',' . $billing_addr_data->city . ',' . $billing_addr_data->state . ',' . $billing_addr_data->country . ',' . $billing_addr_data->postal_code;


      $delivery_note = '';

      if (Session::has('delivery_note')) {
        $delivery_note = Session::get('delivery_note');
        $delivery_note =  $delivery_note[0];
      }

      // dd($delivery_note);


      $shop_id = Setting::getShopId();
      if ($shop_id == null) {
        $shop_id = SiteSetting::where(['key' => 'stock_shop', 'site_id' => config('site_setting.site_id')])->first()->value ?? 22;
      }


      $order = new Order;
      $order->order_no = $order->generate_orderno();
      $order->customer_id = $cus_id;
      $order->contact = $phone;
      $order->total_qty = 0;
      $order->subtotal_amount = $sub_total;
      $order->total_amount = $total;
      $order->delivery_charge = $shipping;
      $order->billing_address_id = $billing_addr_data->id;
      $order->billing_address = json_encode($bill_address);
      $order->shipping_address_id = $shipping_addr_data->id;
      $order->shipping_address = json_encode($ship_address);
      $order->pay_data = $p_data;
      $order->pass_code = Str::random(6);
      $order->session_id = $session_id;
      $order->pay_response = $pay_response;
      $order->status = $order_status;
      $order->delivery_note = $delivery_note;
      $order->is_guest_order = $is_guest_order;
      $order->site_id = config('site_setting.site_id');
      $order->contact_verify = Order::getVeifyPhone($cus_id, $phone);
      if ($checkbox == 'true') {
        $order->is_both_same = 1;
      }
      $order->order_type = $order_type;
      $order->save();

      foreach ($sesssion_cart_data as $cartdata) {
        $order_item = new OrderItem;
        $order_item->order_id = $order->id;
        $order_item->product_id = $cartdata['id'];
        $order_item->qty = $cartdata['qty'];
        $order_item->price = $cartdata['price'];
        $order_item->save();




        if ($shop_id == null) {
          Product::updateStock($method = 'warehouse_stock', $type = 'remove', $order_item->product_id, $order_item->qty);
        } else {
          ShopCurrentStock::updateStock('remove', $order_item->product_id, $order_item->qty, $shop_id);
        }
      }
      $order->cus_email = $cus_data->email;
      $order->shop_id = $shop_id;
      $order->total_qty = $total_qty;
      $order->subtotal_amount = sprintf('%.2f', $sub_total);
      $order->discount = sprintf('%.2f', $discount);
      $order->extra_discount = sprintf('%.2f', $extra_discount);
      $order->extra_discount_rate = $extra_discount_percentage;
      $order->referral_discount = sprintf('%.2f', $referral);
      $order->referral_percentage = $referral_percentage;
      $order->delivery_charge = sprintf('%.2f', $shipping);
      $order->total_amount = sprintf('%.2f', $total);
      //Order::update_retailer_order_tax($order['id']);
      if ($type == 'collect') {
        $order->shop_id = SiteSetting::where(['key' => 'order_shop', 'site_id' => config('site_setting.site_id')])->first()->value ?? 22;
      }
      $order->save();


      if ($ref_code != null) {
        if ($cus_data->referral_code != $ref_code) {
          Referral::InsertData($cus_id, $order->id, $ref_code);
        }
      }


      if ($referral != 0 && $referral_percentage != 0 && $referral_id != 0) {
        $reff = Referral::GetReferralById($referral_id);
        if ($reff != null) {
          $reff->is_used = 1;
          $reff->save();
        }
      }

      SessionHelper::clearSessionData();

      //send mail table insert data

      if ($cus_data->register_type == 'email') {
        $arr = array();
        $arr['cus_id'] = $cus_id;
        $arr['cus_name'] = $cus_data->username;
        $arr['cus_email'] = $cus_data->email;
        $arr['subject'] = config('email_message.new_order');
        $arr['ord_id'] = $order->id;
        $arr['type'] = 'new_order_placed';
        $arr['data'] = $order->pass_code;
        //dd($arr);
        $id = EmailSendData::insert_data($arr);

        if ($id != '') {
          EmailSendData::sendmail($id);
        }
        $arr = array();
        if ($extra_discount_percentage == 15) {
          $arr['cus_id'] = $cus_id;
          $arr['cus_name'] = $cus_data->username;
          $arr['cus_email'] = $cus_data->email;
          $arr['subject'] = config('email_message.extra_discount_15');
          $arr['ord_id'] = $order->id;
          $arr['type'] = 'extra_discount';
          $arr['data'] = '15 % discount on ' . $order->id;
        } else {
          $arr['cus_id'] = $cus_id;
          $arr['cus_name'] = $cus_data->username;
          $arr['cus_email'] = $cus_data->email;
          $arr['subject'] = config('email_message.extra_discount');
          $arr['ord_id'] = $order->id;
          $arr['type'] = 'extra_discount';
          $arr['data'] = '10 % discount on ' . $order->id;
        }
        $discount_mail = EmailSendData::insert_data($arr);
        if ($discount_mail != '') {
          EmailSendData::sendmail($discount_mail);
        }
        $arr = array();
        $arr['cus_id'] = $cus_id;
        $arr['cus_name'] = $cus_data->username;
        $arr['cus_email'] = $cus_data->email;
        $arr['subject'] = config('email_message.passcode');
        $arr['ord_id'] = $order->id;
        $arr['type'] = 'passcode_mail';
        $arr['data'] = $order->pass_code;
        //dd($arr);
        $id = EmailSendData::insert_data($arr);
        if ($id != '') {
          EmailSendData::sendmail($id);
        }
      }

      $error = 0;
    }
    return $order;
  }

  public static function generate_orderno()
  {
    $order = Order::latest()->first();
    if ($order) {
      $new_order_no = $order->order_no + 1;
      $order = Order::where('order_no', $new_order_no)->first();
      if ($order) {
        $new_order_no = $order->order_no + 1;
        return str_pad($new_order_no, 8, "0", STR_PAD_LEFT);
      } else {
        return str_pad($new_order_no, 8, "0", STR_PAD_LEFT);
      }
    } else {
      return '00000001';
    }
  }

  public static function GetOrderId()
  {
    $order = Order::latest()->first();
    if ($order) {
      $new_order_id = $order->id + 1;
      return $new_order_id;
    } else {
      return 1;
    }
  }

  public static function CartToGroupByGroup($cart)
  {
    $cart = collect($cart);
    $final_data = $cart->groupBy('group_id');
    $out = $final_data->map(function ($row) {
      return [
        'group_id' => $row->first()['group_id'],
        'qty' => $row->sum('qty'),
        'price' => $row->first()['price'],
      ];
    });
    return $out;
  }

  public static function discountCalculate($cart)
  {
    $final_pay_amount = 0;
    $cart_cat_group = Order::CartToGroupByGroup($cart);

    foreach ($cart_cat_group as $crt) {
      $group_id = $crt['group_id'];
      $price = $crt['price'];
      $qty = $crt['qty'];

      //get cat discount list
      $discount_list = Discount::getDiscountByCat($group_id);
      if ($discount_list != null && $discount_list->count() > 0) {
        $discount_list = $discount_list->toArray();
        $csl_discount = Discount::clcDiscount($qty, $price, $discount_list);
        $final_pay_amount = ($final_pay_amount + $csl_discount);
      } else {
        //for no any discount on group
        $crt_amnt = ($price * $qty);
        $final_pay_amount = ($final_pay_amount + $crt_amnt);
      }
    }
    return $final_pay_amount;
  }

  public static function appliedDiscount($cart)
  {
    $applied_discount = array();
    $cart_cat_group = Order::CartToGroupByGroup($cart);

    foreach ($cart_cat_group as $crt) {
      $group_id = $crt['group_id'];
      $price = $crt['price'];
      $qty = $crt['qty'];

      //get cat discount list
      $discount_list = Discount::getDiscountByCat($group_id);

      if ($discount_list != null && $discount_list->count() > 0) {
        $discount_list = $discount_list->toArray();
        $applied_discount[] = Discount::clcDiscount($qty, $price, $discount_list, $is_return_offer = 1);
        //$applied_discount = array_count_values($applied_discount);
      }
    }
    return $applied_discount;
  }

  public static function getDiscountAmount($sesssion_cart_data = null)
  {
    if ($sesssion_cart_data != null) {
      $cart_dt = $sesssion_cart_data['session_data'];
      $cart_amt = $sesssion_cart_data['sub_ttl'];
    } else {
      $cart_dt = Session::get('Cart');
      $cart_amt = SessionHelper::getCartTotalAmount();
    }
    $final_pay_amount = Order::discountCalculate($cart_dt);
    $discount = 0;
    //dd($final_pay_amount);
    if ($final_pay_amount > 0) {
      $discount = abs($final_pay_amount - $cart_amt);
    }
    return $discount;
  }

  public static function cancelOrder($ord_id)
  {
    $order = Order::with('orderitem')->find($ord_id);
    if ($order != null) {
      $old_status = $order->status;
      $order->status = 'cancel';
      if ($order->pay_data == 'paid') {
        Order::RefundPayment($order);
      }
      $order->save();

      if ($old_status != 'process') {
        if ($order->orderitem->count() > 0) {
          foreach ($order->orderitem as $itm) {
            if ($order->shop_id == null) {
              Product::updateStock($method = 'warehouse_stock', $type = 'add', $itm->product_id, $itm->qty);
            } else {
              ShopCurrentStock::updateStock('add', $itm->product_id, $itm->qty, $order->shop_id);
              Product::updateStock($method = 'current_stock', $type = 'add', $itm->product_id, $itm->qty);
            }
          }
        }
      }

      //send mail table insert data
      $shipping_addr_data = Address::find($order->shipping_address_id);
      $arr = array();
      $arr['cus_id'] = $order->customer_id;
      $arr['cus_name'] = $shipping_addr_data->name;
      $arr['cus_email'] = $order->cus_email;
      $arr['subject'] = config('email_message.cancel_order');
      $arr['ord_id'] = $order->id;
      $arr['type'] = 'cancel_order';
      $arr['data'] = '';
      //dd($arr);
      $id = EmailSendData::insert_data($arr);
      if ($id != '') {
        EmailSendData::sendmail($id);
      }
    }
  }

  public static function RefundPayment($order) {}

  public static function generateinvoice($id, $save = '', $final_save = '')
  {
    $symbol = utf8_decode(config('currency.symbol'));
    $order = Order::with('customer', 'orderitem')->find($id);
    if ($save == '') {
      if ($order->is_paid == 1) {
        $order->status = 'delivered';
      }
    }
    $order->save();

    $cus_id = $order->customer->id;
    //$remaining_item = RemainingOrderItem::active()->where('cus_id',$cus_id)->get();

    $billing = json_decode($order->billing_address);
    $shipping = json_decode($order->shipping_address);

    $pdf = new PDF_MC_Table_new('P', 'mm', 'A4');
    $pdf->AddPage();
    $pdf->SetMargins(10, 10, 10, 10);
    $pdf->SetAutoPageBreak(0);

    $pdf->SetFont('Arial', 'B', 10);
    $pdf->SetWidths(array(130, 100));
    $pdf->Row(array('Name : ' . $order->customer->username . '', 'Email : ' . $order->customer->email . ''));
    $pdf->Ln(1);

    $pdf->SetFont('Arial', 'B', 10);
    $pdf->SetWidths(array(70, 60, 60));
    $pdf->Row(array('Order Date : ' . $order->formatdate . '', 'Billed To :', 'Shipped To :'));
    $pdf->Ln(1);

    $pdf->SetFont('Arial', 'B', 10);
    $pdf->SetWidths(array(70, 60, 60));
    $pdf->Row(array('Order Id : ' . $order->order_no . '', $billing[0], $shipping[0]));
    $pdf->Ln(10);

    $pdf->SetFont('Arial', 'B', 10);
    $pdf->SetWidths(array(85, 55, 30, 20));
    $pdf->SetAligns(array('C', 'C', 'C', 'C'));
    $pdf->Row(array('item', 'price', 'qty', 'amount'), true);
    $pdf->SetWidths(array(30));

    $subttl = 0;
    foreach ($order->orderitem as $item) {

      $pdf->SetFont('Arial', '', 10);
      $pdf->SetWidths(array(85, 55, 30, 20));
      $pdf->SetAligns(array('', 'C', 'C', 'C'));
      $pdf->Row(array($item->product->name, $symbol . $item->price, $item->qty, $symbol . $item->price * $item->qty), true);
      $pdf->SetWidths(array(30));
      $subttl += $item->price * $item->qty;
    }

    $pdf->Ln(5);

    $pdf->SetAligns(array(''));
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->SetWidths(array(135, 40, 20));
    $pdf->Row(array('', 'subtotal', $symbol . $order->subtotal_amount));
    $pdf->Ln(2);


    $pdf->SetFont('Arial', 'B', 10);
    $pdf->SetWidths(array(135, 40, 20));
    $pdf->Row(array('', 'Discount', $order->discount == null ? $symbol . '0' : $symbol . $order->discount));
    $pdf->Ln(2);

    $pdf->SetFont('Arial', 'B', 10);
    $pdf->SetWidths(array(135, 40, 20));
    $pdf->Row(array('', 'Extra Discount (' . $order->extra_discount_rate . '%)', $order->extra_discount == null ? $symbol . '0' : $symbol . $order->extra_discount));
    $pdf->Ln(2);

    $pdf->SetFont('Arial', 'B', 10);
    $pdf->SetWidths(array(135, 40, 20));
    $pdf->Row(array('', 'Delivery Charge', $symbol . $order->delivery_charge));
    $pdf->SetWidths(array(30));
    $pdf->Ln(2);

    $pdf->SetFont('Arial', 'B', 10);
    $pdf->SetWidths(array(135, 40, 20));
    $pdf->Row(array('', 'Total Amount', $symbol . $order->total_amount));
    $pdf->SetWidths(array(30));
    $pdf->Ln(1);

    if ($save != null && $final_save != null) {
      $pdf->Output('D', $order->customer->username . '_' . $order->order_no . '.pdf');
      exit;
    } elseif ($save != '') {
      $path = Storage::path('public/invoice');
      if (!File::isDirectory($path)) {
        File::makeDirectory($path, 0777, true, true);
      }
      $storage = Storage::path('public/invoice/' . 'invoice_' . $order->order_no . '.pdf');
      $pdf->Output($storage, 'F');
    } else {
      $pdf->Output('D', $order->customer->username . '_' . $order->order_no . '.pdf');
      exit;
    }
  }

  public static function getLastContactno()
  {
    $phone = '';
    if (Auth::check()) {
      $order = Order::where('customer_id', Auth::user()->id)->latest()->first();
      if ($order != null) {
        $phone = $order->contact;
      }
    }
    return $phone;
  }
}
