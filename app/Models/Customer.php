<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class Customer extends Authenticatable
{
  use HasFactory, HasApiTokens;
  protected $table = 'customers';

  protected $hidden = [
    'created_at',
    'updated_at',
  ];

  protected $primaryKey = 'id';

  public function activationlink()
  {
    return $this->belongsTo(ActivationLink::class, 'cus_id', 'id');
  }

  public function referrals()
  {
    return $this->hasMany(Referral::class, 'owner_referral_cus_id', 'id');
  }

  public static function ValidateCode($code)
  {
    $cus_id =  '';
    if (Auth::check()) {
      $cus_id = Auth::user()->id;
    }
    $reff = Customer::where('referral_code', $code);
    if ($cus_id != null) {
      $reff = $reff->where('id', '<>', $cus_id);
    }
    $reff = $reff->first();
    return $reff;
  }

  public static function GenerateRemainCustomerCode()
  {
    $cus = Customer::where('referral_code', NULL)->get();
    if (count($cus) > 0) {
      foreach ($cus as $cs) {
        $cs->referral_code = Customer::generateReferralcode();
        $cs->save();
      }
    }
  }

  public static function generateReferralcode()
  {
    $random_code = 'DMV' . Str::random(10);
    $check_code = Customer::CheckCodeExists($random_code);
    if ($check_code == 0) {
      return $random_code;
    } else {
      Customer::generateReferralcode();
    }
  }

  public static function CheckCodeExists($code)
  {
    $rtn = 1;
    $cus = Customer::getCusByRef($code);
    if ($cus == null) {
      $rtn = 0;
    }
    return $rtn;
  }

  public static function GetCustomerId($email)
  {
    $id = null;
    if ($email != null) {
      $id = Customer::InsertCustomer($email);
    }
    return $id;
  }

  public static function GetCustomerIdByPhoneno($phone)
  {
    $id = null;
    if ($phone != null) {
      $id = Customer::InsertCustomerByPhone($phone);
    }
    return $id;
  }

  public static function getDatafromEmail($email)
  {
    $cus = Customer::where('email', $email)->first();
    return $cus;
  }

  public static function getCusByRef($ref)
  {
    $cus = Customer::where('referral_code', $ref)->first();
    return $cus;
  }

  public static function InsertCustomer($email)
  {
    if ($email != null) {
      $cus = Customer::where('email', $email)->first();
      if ($cus) {
        return $cus->id;
      } else {
        $cus = new Customer;
        //$cus->username = $email;
        $password = Str::random(10);
        $cus->password =  Hash::make($password);
        $cus->email = $email;
        $cus->is_active = 0;
        $cus->referral_code = Customer::generateReferralcode();
        $cus->save();

        //send mail table insert data
        $arr = array();
        $arr['cus_id'] = $cus->id;
        $arr['cus_name'] = $cus->username;
        $arr['cus_email'] = $cus->email;
        $arr['subject'] = config('email_message.register');
        $arr['ord_id'] = null;
        $arr['type'] = 'new_user_register';
        $arr['data'] = $password;
        $id = EmailSendData::insert_data($arr);
        if ($id != '') {
          EmailSendData::sendmail($id);
        }
        return $cus->id;
      }
    }
  }

  public static function InsertCustomerByPhone($phone)
  {

    if ($phone != null) {
      $cus = Customer::where('phone_no', $phone)->first();
      if ($cus) {
        return $cus->id;
      } else {
        $cus = new Customer;
        $cus->phone_no = $phone;
        $cus->register_type = 'guest';
        $cus->is_active = 0;
        $cus->save();
        return $cus->id;
      }
    }
  }

  public static function getFirstRef()
  {
    $ref = null;
    if (Auth::check()) {
      $customer = Auth::user();
      if ($customer->referrals != null && count($customer->referrals) > 0) {
        $ref = $customer->referrals->where('is_used', 0)->first();
      }
    }
    return $ref;
  }
}
