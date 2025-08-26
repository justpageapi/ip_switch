<?php
namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class ShopProductPrice extends Model
{
  
  use HasFactory;
    protected $table = 'shop_product_price';
    protected $primaryKey = 'id';


  function product(){
    return $this->belongsTo(Product::class,'product_id','id');
  }

  function shop(){
    return $this->belongsTo(User::class,'shop_id','user_id');
  }

  public static function getShopProductPrice($shop_id){
    $rtn = 0;
    $shop_product_price = ShopProductPrice::where(array('shop_id'=>$shop_id))->get();    
    return $shop_product_price;
  }
  
 }
 ?>