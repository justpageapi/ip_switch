<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;


class ShopCurrentStock extends Model
{
  use HasFactory;
  protected $appends = ['imagepath'];
  protected $table = 'shop_current_stock';
  protected $primaryKey = 'id';

  function product()
  {
    return $this->belongsTo(Product::class, 'product_id', 'id');
  }

  function shop()
  {
    return $this->belongsTo(User::class, 'shop_id', 'user_id');
  }

  //get shop wise product
  public static function getStockData($shop_id)
  {
    $prds = ShopCurrentStock::with('shop', 'product')->where(array('shop_id' => $shop_id))->get();
    return $prds;
  }

  public static function getStock($company_id, $product_id)
  {
    $rtn = 0;
    $cr_stock = ShopCurrentStock::where(array('company_id' => $company_id, 'product_id' => $product_id))->first();
    if ($cr_stock != null) {
      $rtn = $cr_stock->qty;
    }
    return $rtn;
  }

  public static function updateStock($type, $product_id, $qty, $shop_id)
  {

    $crdata = ShopCurrentStock::where(array('shop_id' => $shop_id, 'product_id' => $product_id))->first();
    if ($crdata != null) {
      if ($type == 'add') {
        $crdata->qty = $crdata->qty + $qty;
        $crdata->save();
      } else if ($type == 'remove') {
        $crdata->qty = $crdata->qty - $qty;
        $crdata->save();
      }
    }
  }
}
