<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;
use Cookie;
use App\Models\UserDevice;
use BackendHelper;
use App\Models\UserProfile;
use Session;

class checkLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
      if(Auth::guard('web')->check()) {
          return $next($request);
        }else{
            return redirect()->route('login');
        } 
    }
}
