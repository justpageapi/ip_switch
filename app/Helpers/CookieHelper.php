<?php

namespace App\Helpers;

use Illuminate\Support\Str;
use App\Models\AppUser;
use App\Models\UserProfile;
use App\Models\SiteSetting;
use Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon;
use App\Models\UserDevice;
use App\Models\Package;
use Cookie;
use Illuminate\Http\Request;

class CookieHelper
{

  public static function checkCookieSet()
  {
    // dd($_COOKIE);
    if (!isset($_COOKIE['recentview'])) {
      return 0;
    } else {
      $cookie_data = $_COOKIE['recentview'];
      $cookie_data = json_decode($cookie_data);
      if ($cookie_data->is_set == 1) {
        return 1;
      } else {
        return 0;
      }
    }
  }

  public static function setCookie()
  {
    //$cookieTime = time()+3600;
    $data = [];
    $data['is_set'] = 1;

    setcookie('recentview', json_encode($data), time() + (10 * 365 * 24 * 60 * 60), "/"); // 86400 = 1 day

  }

  public static function getCookie()
  {
    /*  $d = Cookie::get('recentview');
      $data = json_decode($d);
      return $data; */

    $cookie_data = $_COOKIE['recentview'];
    $cookie_data = json_decode($cookie_data);
    return $cookie_data;
  }

  public static function setCookieData($p_id)
  {
    /*  unset($_COOKIE['recentview']);
      dd('unset'); */

    $d = $_COOKIE['recentview'];
    $cookie_data = json_decode($d, true);
    //dd($cookie_data->prd_id);
    if (isset($cookie_data['prd_id']) && count($cookie_data['prd_id']) > 0) {
      if (($key = array_search($p_id, $cookie_data['prd_id'])) !== false) {
        unset($cookie_data['prd_id'][$key]);
      }
      $cookie_data['prd_id'][] = $p_id;
      $cookie_data['prd_id'] = array_values($cookie_data['prd_id']);
      setcookie('recentview', json_encode($cookie_data), time() + (10 * 365 * 24 * 60 * 60), "/");
    } else {
      $cookie_data['prd_id'][] = $p_id;
      setcookie('recentview', json_encode($cookie_data), time() + (10 * 365 * 24 * 60 * 60), "/");
    }

    // $d = Cookie::get('recentview');
    // $cookie_data = json_decode($d);
    // dd($cookie_data);
  }



  public static function checkLoginCookieSet()
  {
    if (!isset($_COOKIE['login'])) {
      return 0;
    } else {
      $cookie_data = $_COOKIE['login'];
      $cookie_data = json_decode($cookie_data);
      if ($cookie_data->id != null) {
        return 1;
      } else {
        return 0;
      }
    }
  }

  public static function setLoginCookie($id)
  {
    //$cookieTime = time()+3600;
    $data = [];
    $data['id'] = $id;

    setcookie('login', json_encode($data), time() + (10 * 365 * 24 * 60 * 60)); // 86400 = 1 day

  }

  public static function getLoginCookie()
  {
    /*  $d = Cookie::get('recentview');
      $data = json_decode($d);
      return $data; */

    $cookie_data = $_COOKIE['login'];
    $cookie_data = json_decode($cookie_data);
    return $cookie_data;
  }
}
