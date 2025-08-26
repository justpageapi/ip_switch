<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class BannerImage extends Model
{

  use HasFactory;

  protected $table = 'banner_image';
  protected $hidden = [
    'created_at',
    'updated_at',
  ];

  protected $primaryKey = 'id';
  protected $appends = ['banner_image'];

  function GetbannerImageAttribute()
  {
    $out = null;
    if ($this->image != null) {
      $out = config('imageurl.banner_img_url') . $this->image;
    } else {
      $out = config('imageurl.banner_img_url') . 'dummyproduct.jpg';
    }
    return $out;
  }

  public static function getImageByShop()
  {
    $images = [];
    $shop_id = Setting::getShopId();
    $shop_id = SiteSetting::where(['key' => 'order_shop', 'site_id' => config('site_setting.site_id')])->first()->value ?? 22;
    if (isset($shop_id) && $shop_id != null) {
      $images = BannerImage::where(array('shop_id' => $shop_id, 'is_active' => 1))->get();
    }
    return $images;
  }
}
