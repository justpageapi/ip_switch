<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    use HasFactory;

    protected $table = 'cards';

    protected $hidden = [
      'created_at',
      'updated_at',
    ];

    protected $primaryKey = 'id';

    public static function InsertUpdateCard($cus_id,$arr){
      $rtn_id = null;
      if($cus_id != null && count($arr) > 0 ){
        $card = Card::where(array('cus_id'=>$cus_id,'card_number'=>$arr['card_number'],
                        'card_expiry_date'=>$arr['card_expiry_date'],'card_holder_name'=>$arr['card_holder_name']))->first();
        if($card != null){
          $rtn_id = $card->id;
        }else{
          $card = new Card;
          $card->cus_id = $cus_id;
          $card->card_number = $arr['card_number'];
          $card->card_expiry_date = $arr['card_expiry_date'];
          $card->card_holder_name = $arr['card_holder_name'];
          $card->save();
          $rtn_id = $card->id;
        }
      }
      return $rtn_id;
    }
}
