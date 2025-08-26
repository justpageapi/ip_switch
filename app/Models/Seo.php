<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seo extends Model
{
  use HasFactory;
  protected $table = 'seos';
  protected $primaryKey = 'id';

  public function Product()
  {
    return $this->belongsTo(Product::class, 'slug', 'id')->where('type', 'product');
  }

  public static function getData()
  {
    $site_id = config('site_setting.site_id');
    $seo = Seo::where(array('type' => 'product', 'site_id' => $site_id))->orwhere('type', 'page')->get();
    return $seo;
  }

  public static function getSeoData($type, $slug)
  {
    $site_id = config('site_setting.site_id');
    $seo_data = array();

    $seo_title = '';
    $seo_keyword = '';
    $seo_discription = '';
    $seo_slug_url = '';

    $seo = Seo::where(array('site_id' => $site_id, 'type' => $type, 'slug' => $slug))->first();
    if ($seo != null) {
      if ($seo->title != null) {
        $seo_title = $seo->title;
      }
      if ($seo->keyword != null) {
        $seo_keyword = $seo->keyword;
      }
      if ($seo->discription != null) {
        $seo_discription = $seo->discription;
      }
      if ($seo->slug_url != null) {
        $seo_slug_url = $seo->slug_url;
      }
    }

    $seo_data['seo_title'] = $seo_title;
    $seo_data['seo_keyword'] = $seo_keyword;
    $seo_data['seo_discription'] = $seo_discription;
    $seo_data['seo_slug_url'] = $seo_slug_url;

    return $seo_data;
  }

  public static function getSeodatafromslugurl($slug_url)
  {
    $site_id = config('site_setting.site_id');
    $seo = Seo::where(array('site_id' => $site_id, 'type' => 'product', 'slug_url' => $slug_url))->first();
    return $seo;
  }
}
