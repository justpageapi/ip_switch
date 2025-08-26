<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\StringOptimize;

class Address extends Model
{
    use HasFactory;

    protected $table = 'addresses';

    protected $hidden = [
      'created_at',
      'updated_at',
    ];

    protected $primaryKey = 'id';

    public static function InsertAddress($cus_id,$arr,$type){
      $id = null;
      if(count($arr) > 0){
        $address = Address::where(array('cus_id'=>$cus_id,'address_line_1'=>$arr['address_line_1'],
                                        'address_line_2'=>$arr['address_line_2'],
                                        'city'=>$arr['city'],
                                        'state'=>$arr['state'],
                                        'country'=>$arr['country'],
                                        'type'=>$type,
                                        ))->first();
        
        if($address == null){
          $address_line_2 = null;
          $name = StringOptimize::normalString($arr['name']);
          $address_line_1 = StringOptimize::normalString($arr['address_line_1']);
          $address_line_2 = StringOptimize::normalString($arr['address_line_2']);
          $city = StringOptimize::normalString($arr['city']);
          $state = StringOptimize::normalString($arr['state']);
          $country = StringOptimize::normalString($arr['country']);
        

          $address = new Address;
          $address->cus_id = $cus_id;
          $address->name = $name;
          $address->address_line_1 = $address_line_1;
          $address->address_line_2 = $address_line_2;
          $address->city = $city;
          $address->state = $state;
          $address->country = $country;
          $address->type = $type;
          $address->postal_code = $arr['postal_code'];
          $address->save();

          $id = $address->id;
        }else{

          $id = $address->id;

        }
      }
      return $id;
    }

    public static function getAddressByType($cus_id,$type){
      $addr = null;
      if($cus_id != null && $type != null){
        $addr = Address::where(array('cus_id'=>$cus_id,'type'=>$type))->orderBy('id','DESC')->get();
      }
      return $addr;
    }
}
