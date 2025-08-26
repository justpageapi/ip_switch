<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Referral extends Model
{
    use HasFactory;
    protected $table = 'referrals';

    protected $hidden = [
      'created_at',
      'updated_at',
    ];

    protected $primaryKey = 'id';

    public function customer(){
        return $this->belongsTo(Customer::class,'used_referral_cus_id','id');
    }

    public function order(){
        return $this->belongsTo(Order::class,'order_id','id');
    }

    public function getformatdateAttribute(){
      return Carbon::parse($this->created_at)->format('M d Y');
    }

    public static function GetReferralById($id){
      $reff = Referral::find($id);
      return $reff;
    }

    public static function InsertData($cus_id,$order_id,$ref_code){
      $customer = Customer::getCusByRef($ref_code);
      if($customer != null){
        $owner_id = $customer->id;
        $percentage = $customer->referral_percentage;
        $ref = new Referral;
        $ref->owner_referral_cus_id = $owner_id;
        $ref->used_referral_cus_id = $cus_id;
        $ref->order_id = $order_id;
        $ref->percentage = $percentage;
        $ref->save();
      }
    }

}
