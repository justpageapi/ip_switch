<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Seo;
use App\Helpers\CookieHelper;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;

class SeoData
{
  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
   * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
   */
  public function handle(Request $request, Closure $next)
  {
    $is_set = CookieHelper::checkCookieSet();
    $path = $request->path();
    $seo['seo_title'] = '';
    $seo['seo_keyword'] = '';
    $seo['seo_discription'] = '';

    if ($path == '/') {
      $seo = Seo::getSeoData('Page', 'Home');
    } else {
      $path = explode('/', $path);
      if ($path[0] != null && !isset($path[1])) {
        $seo = Seo::getSeoData('Page', ucwords($path[0]));
      }
    }


    if ($is_set == 1) {
      $login_cookie_check = CookieHelper::checkLoginCookieSet();
      if ($login_cookie_check == 1) {
        if (!Auth::check()) {
          //dd('need check login');
          $data = CookieHelper::getLoginCookie();
          if (isset($data->id) && $data != null) {
            $cus = Customer::find($data->id);
            Auth::login($cus);
          }
        }
      } else {
        if (Auth::check()) {
          $user_id = Auth::user()->id;
          CookieHelper::setLoginCookie($user_id);
        }
      }
    }


    view()->share('seo_ttl', $seo['seo_title']);
    view()->share('seo_keyword', $seo['seo_keyword']);
    view()->share('seo_discription', $seo['seo_discription']);
    view()->share('set_cookie', $is_set);

    return $next($request);
  }
}
