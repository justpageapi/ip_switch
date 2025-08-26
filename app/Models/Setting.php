<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
  use HasFactory;
  protected $table = 'settings';
  protected $primaryKey = 'id';


  public static function getSettingByType($type)
  {
    if ($type != null) {
      $setting = Setting::where('type', $type)->get();
      return $setting;
    }
    return null;
  }

  public static function getShopId()
  {
    if (isset($_COOKIE['geolocation'])) {
      unset($_SESSION['shop_id']);
      $cookie_data = $_COOKIE['geolocation'];
      $cookie_data = json_decode($cookie_data);
      $get_shop_id = PostCodeForShop::where('post_code', $cookie_data)->where('is_active', 1)->first()->shop_id ?? null;
      if ($get_shop_id != null) {
        $_SESSION['shop_id'] = $get_shop_id;
      }
      return $get_shop_id;
    } else {
      return null;
    }
  }
}
