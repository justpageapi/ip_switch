<?php

namespace App\Helpers;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon;
use Cookie;
use Illuminate\Support\Facades\Session;

class Pay360Helper{

  public static function CallUrl($host,$instid,$u,$p,$post){
    $ch = curl_init("$host/hosted/rest/sessions/$instid/payments");
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post));

    curl_setopt($ch, CURLOPT_USERPWD, "$u:$p");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type:application/json"));

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);

    return $response;
  }

  public static function StatusCheckCallUrl($host,$instid,$u,$p,$session_id){
    $ch = curl_init("$host/hosted/rest/sessions/$instid/$session_id/status");
    //curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post));

    curl_setopt($ch, CURLOPT_USERPWD, "$u:$p");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type:application/json"));

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);

    return $response;
  }
}
