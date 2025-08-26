<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;


class Discount extends Model
{
  use HasFactory;

  protected $table = 'discount';
  protected $primaryKey = 'id';

  function group()
  {
    return $this->belongsTo(Category::class, 'group_id', 'id');
  }
  public static function getDiscountByCat($group_id)
  {
    $list = null;
    $shop_id = Setting::getShopId();
    $shop_id = SiteSetting::where(['key' => 'order_shop', 'site_id' => config('site_setting.site_id')])->first()->value ?? 22;

    // $list = Discount::where(array('user_id' => $shop_id, 'group_id' => $group_id, 'is_active' => 1, 'is_check' => 1))->orderBy('price', 'desc')->get();

    $list = SiteOffer::where(array('site_id' => config('site_setting.site_id'), 'group_id' => $group_id, 'is_active' => 1, 'is_check' => 1))->orderBy('price', 'desc')->get();



    return $list;
  }

  public static function clcDiscount($total_qty, $unit_price, $discount_list, $is_return_offer = null)
  {
    $offer = array();
    foreach ($discount_list as $d) {
      $qty = $d['qty'];
      $price = $d['price'];
      $offer[$qty] = $price;
    }

    /* $offer = [
        '10' => '75',
        '3' => '25'
    ];
    $total_qty = (isset($_GET['qty'])) ? $_GET['qty'] : 0;
    $unit_price = 10; */

    ksort($offer);

    //$minimal_qty = array_key_first($offer);
    //$firstValue = reset($colors); //blue
    $minimal_qty = key($offer); //2

    $reverse_offer = array_reverse($offer, true);

    $calculate_qty = $total_qty;
    $calculate_price = 0;

    $apply_offer = [];

    while ($calculate_qty >= $minimal_qty) {

      $match = false;

      foreach ($reverse_offer as $key => $value) {
        if ($match == false) {
          if ($calculate_qty >= $key) {
            $match = true;
            $calculate_price = $calculate_price + $value;
            $calculate_qty = $calculate_qty - $key;

            $apply_offer[] = 'Offer ' . $key . ' for ' . config('currency.symbol') . $value;
          }
        }
      }
    }
    if ($calculate_qty != 0) {
      $calculate_price = $calculate_price + ($calculate_qty * $unit_price);
    }
    /* echo 'qty : '.$total_qty.' = price : '.$calculate_price;

    echo '<hr>';
    echo '<pre>';
    print_r($apply_offer); */
    /* print_r($apply_offer);
    die; */
    $rtn = 0;
    if ($is_return_offer == null) {
      $rtn = $calculate_price;
    } else {
      $rtn = $apply_offer;
      $rtn = array_count_values($apply_offer);
    }
    return $rtn;
  }
}
