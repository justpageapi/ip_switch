<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ActivationLink extends Model
{
  use HasFactory;

  protected $table = 'activation_links';

  protected $hidden = [
    'created_at',
    'updated_at',
  ];

  public static function InsertData($cus)
  {
    if ($cus != null) {
      $act_link = ActivationLink::where('cus_email', $cus->email)->first();
      if ($act_link == null) {
        if ($cus->email != null) {
          $act_link = new  ActivationLink;
          $act_link->cus_id = $cus->id;
          $act_link->cus_email = $cus->email;
          $act_link->token = Str::random(10);
          $act_link->status = 'not_verify';
          $act_link->save();
        }
      }
    }
  }
}
