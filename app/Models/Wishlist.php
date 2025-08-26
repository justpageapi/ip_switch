<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Wishlist extends Model
{
    use HasFactory;
    protected $table = 'wishlists';

    protected $hidden = [
      'created_at',
      'updated_at',
    ];

    protected $primaryKey = 'id';

    public static function checkinlist($prd_id){
      $user_id = '';
      $is_set = 0;
      if(Auth::check()){
        $user_id = Auth::user()->id;
      }

      if($user_id != ''){
        $wishlist = Wishlist::where(array('cus_id'=>$user_id,'product_id'=>$prd_id))->first();
        if($wishlist != null){
          $is_set = $wishlist->is_active;
        }
      }
      return $is_set;
    }

    public static function getWishlistProductid(){
      $user_id = '';
      $prd_ids = [];
      if(Auth::check()){
        $user_id = Auth::user()->id;
      }

      if($user_id != ''){
        $prd_ids = Wishlist::where(array('cus_id'=>$user_id,'is_active'=>1))->pluck('product_id');       
      }
      return $prd_ids;
    }
}
