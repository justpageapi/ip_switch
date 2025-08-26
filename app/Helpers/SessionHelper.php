<?php

namespace App\Helpers;

use App\Models\CartEmail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon;
use Cookie;
use Illuminate\Support\Facades\Session;
use App\Models\Setting;
use App\Models\SiteSetting;
use App\Models\Product;
use App\Models\Order;
use App\Models\Customer;


class SessionHelper
{

  public static function getSessionKey($prd_id)
  {
    /* Session::forget('Cart');
    Session::save();
    dd('forget'); */
    $sesssion_data = Session::get('Cart');

    $chk_arrr = -1;
    if (isset($sesssion_data) && $sesssion_data != '') {
      foreach ($sesssion_data as $key => $val) {
        if ($val['id'] == $prd_id) {
          $chk_arrr = $key;
        }
      }
    }
    return $chk_arrr;
  }

  public static function getSessionCartQty($prd_id)
  {
    $key = SessionHelper::getSessionKey($prd_id);
    $sesssion_qty = 0;

    if ($key >= 0) {
      $sesssion_qty = Session::get('Cart.' . $key . '.qty');
    }

    return $sesssion_qty;
  }

  public static function getSessionData($prd_id)
  {
    $key = SessionHelper::getSessionKey($prd_id);
    $data = null;

    if ($key >= 0) {
      $data = Session::get('Cart.' . $key);
    }
    return $data;
  }

  public static function clearSessionData()
  {
    Session::forget('Cart');
    Session::save();

    if (Session::has('Paydata')) {
      Session::forget('Paydata');
      Session::save();
    }

    if (Session::has('AddressData')) {
      Session::forget('AddressData');
      Session::save();
    }

    if (Session::has('RefSession')) {
      Session::forget('RefSession');
      Session::save();
    }
  }

  public static function getCartTotalAmount()
  {
    $sub_ttl = 0;
    $shipping = 0;
    $total = 0;
    if (Session::has('Cart')) {
      $sesssion_cart_data = Session::get('Cart');
      if (isset($sesssion_cart_data) && count($sesssion_cart_data) > 0) {
        if (count($sesssion_cart_data) > 0) {
          foreach ($sesssion_cart_data as $cartdata) {
            $cs = Product::getCurrentStock($cartdata['id']);
            $key = SessionHelper::getSessionKey($cartdata['id']);
            if ($cartdata['qty'] > $cs) {
              $new_qty = ($cartdata['qty'] - $cs);
              if ($new_qty > 0) {
                Session::put('Cart.' . $key . '.qty', $new_qty);
                Session::save();
              } elseif ($new_qty <= 0) {
                Session::forget('Cart.' . $key);
                Session::save();
              }
            }
            if ($key >= 0) {
              $sub_ttl += (Session::get('Cart.' . $key . '.price') * Session::get('Cart.' . $key . '.qty'));
            }
          }
        }
      }
    }
    $total += $sub_ttl + $shipping;

    return sprintf('%.2f', $total);
  }

  public static function getShippingCharge($sub_ttl)
  {
    $shipping = 0;
    $min_order_limit = SiteSetting::getSitesetting('web_popup', 'amount');

    if ($min_order_limit != null) {
      if ($sub_ttl < $min_order_limit) {
        //$shipping = config('shipping_charge.shipping_charge');
        $shipping =  $min_order_limit - $sub_ttl;
      }
    }
    return $shipping;
  }

  public static function getRefDiscount($total)
  {
    $discount = ['percentage' => 0, 'ref_dicount' => 0, 'ref_id' => 0];
    $ref = Customer::getFirstRef();
    if ($ref != null) {
      $discount['percentage'] = $ref->percentage;
      $discount['ref_dicount'] = ($ref->percentage / 100) * $total;
      $discount['ref_id'] = $ref->id;
    }
    return $discount;
  }

  public static function getExtraDiscount($total)
  {
    $discount = ['percentage' => 0, 'amount' => 0];
    if (Auth::check()) {
      $customer_id = auth()->user()->id;
      $chk_repeat = Order::where('customer_id', $customer_id)->get();
      if (count($chk_repeat) > 0) {
        // $discount['percentage'] = 10;
        $value = SiteSetting::where(['site_id' => config('site_setting.site_id'), 'key' => 'extra_discount_per'])->first()->value;
        $discount['percentage'] = $value;
        $discount['amount'] = sprintf('%.2f', ($total * $value) / 100);
      } else {
        $discount['percentage'] = 15;
        $discount['amount'] = sprintf('%.2f', ($total * 15) / 100);
      }
    } else {
      $discount['percentage'] = 0;
      $discount['amount'] = sprintf('%.2f', ($total * 0) / 100);
    }

    return $discount;
  }

  public static function ReCreateCart()
  {
    $sub_ttl = 0;
    $shipping = 0;
    $total = 0;
    $discount = 0;
    $total_qty = 0;
    $extra_discount = 0;
    $offers = [];
    $sesssion_cart_data = array();
    $sesssion_cart_data = Session::get('Cart');
    $ref_discount = ['percentage' => 0, 'ref_dicount' => 0, 'ref_id' => 0];
    $extra_discount = ['percentage' => 0, 'amount' => 0];

    if (isset($sesssion_cart_data) && count($sesssion_cart_data) > 0) {
      if (count($sesssion_cart_data) > 0) {
        foreach ($sesssion_cart_data as $cartdata) {
          $cs = Product::getCurrentStock($cartdata['id']);
          $key = SessionHelper::getSessionKey($cartdata['id']);
          if ($cartdata['qty'] > $cs) {
            $new_qty = ($cartdata['qty'] - $cs);
            if ($new_qty > 0) {
              Session::put('Cart.' . $key . '.qty', $new_qty);
              Session::save();
            } elseif ($new_qty <= 0) {
              Session::forget('Cart.' . $key);
              Session::save();
            }
          }
          if ($key >= 0) {
            $sub_ttl += (Session::get('Cart.' . $key . '.price') * Session::get('Cart.' . $key . '.qty'));
            $total_qty += Session::get('Cart.' . $key . '.qty');
          }
        }
      }
      $discount = Order::getDiscountAmount();
      $offer_applied = SiteSetting::where(['site_id' => config('site_setting.site_id'), 'key' => 'repeat_offer'])->first();
      if ($offer_applied != null) {
        if ($offer_applied->value == 1) {
          $extra_discount = SessionHelper::getExtraDiscount($sub_ttl - $discount);
        } else {
          $extra_discount = ['percentage' => 0, 'amount' => 0];
        }
      } else {
        $extra_discount = ['percentage' => 0, 'amount' => 0];
      }
      $total += ($sub_ttl - $discount) - $extra_discount['amount'];

      $ref_discount = SessionHelper::getRefDiscount($total);
      $total -= $ref_discount['ref_dicount'];
      $shipping = SessionHelper::getShippingCharge($total);
      $total += $shipping;
      $offers = Order::appliedDiscount($sesssion_cart_data);
    }

    $latest_cart_dt = ['session_data' => $sesssion_cart_data, 'ref_id' => $ref_discount['ref_id'], 'ref_dicount' => $ref_discount['ref_dicount'], 'ref_percentage' => $ref_discount['percentage'], 'sub_ttl' => $sub_ttl, 'discount' => $discount, 'shipping' => $shipping, 'total' => $total, 'offers' => $offers, 'total_qty' => $total_qty, 'extra_discount_percentage' => $extra_discount['percentage'], 'extra_discount' => $extra_discount['amount']];
    // dd($latest_cart_dt);


    if (Auth::check()) {
      $customer = Auth::user()->id;
      $email = Auth::user()->email;
      $site_id = config('site_setting.site_id');
      $cart_dt = json_encode($latest_cart_dt);

      $chkCart = CartEmail::where('cus_id', $customer)->where('is_send', 0)->where('site_id', $site_id)->first();
      if ($latest_cart_dt['session_data'] != null) {
        if ($chkCart != null) {
          $chkCart->data = $cart_dt;
          $chkCart->save();
        } else {
          $cart = new CartEmail();
          $cart->cus_id = $customer;
          $cart->email = $email;
          $cart->site_id = $site_id;
          $cart->data = $cart_dt;
          $cart->save();
        }
      } else {
        if ($chkCart != null) {
          $chkCart->delete();
        }
      }
    }

    return ['session_data' => $sesssion_cart_data, 'ref_id' => $ref_discount['ref_id'], 'ref_dicount' => $ref_discount['ref_dicount'], 'ref_percentage' => $ref_discount['percentage'], 'sub_ttl' => $sub_ttl, 'discount' => $discount, 'shipping' => $shipping, 'total' => $total, 'offers' => $offers, 'total_qty' => $total_qty, 'extra_discount_percentage' => $extra_discount['percentage'], 'extra_discount' => $extra_discount['amount']];
  }

  public static function setRefSession($refcode)
  {

    if (!Session::has('RefSession')) {
      Session::put('RefSession', $refcode);
      Session::save();
    }
  }
  public static function getRefSession()
  {
    $refcode = '';
    if (Session::has('RefSession')) {
      $refcode = Session::get('RefSession');
    }
    return $refcode;
  }
}
