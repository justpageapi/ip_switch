<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostCode extends Model
{

  use HasFactory;
  protected $table = 'post_code';
  protected $primaryKey = 'id';

  protected $hidden = [
    'created_at',
    'updated_at',
  ];

  public static function Getpostcodebyshopid($shop_id){  
    return PostCode::where(array('shop_id'=>$shop_id))->get();
  }

  public static function VerifyPostcode($shop_id,$postcode){   
    $rtn = null;
    $postcode = PostCode::where(array('shop_id'=>$shop_id,'post_code'=>$postcode,'is_active'=>1))->first();   
    if($postcode != null){
        $rtn = 'found';
    }
    return $rtn;
  }


}
