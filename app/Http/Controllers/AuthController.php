<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\registerRequest;
use App\Http\Requests\loginRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Support\Facades\Validator;
use App\Models\AppUser;
use App\Models\CartData;
use App\Models\EmailSendData;
use App\Models\ActivationLink;
use DB;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Session;
use App\Http\Livewire\CartLiveWire;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Helpers\SessionHelper;
use Exception;
use Illuminate\Support\Facades\Cookie;
use PAY360\Config\config;

class AuthController extends Controller
{
  //
  /*public function register(){

        return view('authentication.auth-register');
    }*/


  //---------------------------------------------frontend login---------------------------------

  public function activate($token)
  {
    if ($token != null) {
      $activation = ActivationLink::where('token', $token)->first();
      if ($activation != null) {
        $customer = Customer::find($activation->cus_id);
        if ($customer != null) {
          if ($customer->is_active == 0) {
            $customer->is_active = 1;
            $customer->save();

            $activation->status = 'verify';
            $activation->save();

            Auth::login($customer);
            return redirect()->route('dashboard')->with(['success' => 'Your Account Is Activated']);
          } else {
            return redirect()->route('login')->withErrors(['login' => 'Your Account is Already Activated']);
          }
        }
      }
    }
    return redirect()->route('login')->withErrors(['login' => 'Error In Activating Account']);
  }

  public function register()
  {
    if (Auth::user()) {
      return redirect()->route('home');
    }
    return view('frontend.auth.register');
  }

  public function guestLogin()
  {
    if (Auth::user()) {
      return redirect()->route('home');
    }
    return view('frontend.auth.register');
  }

  public function login()
  {
    if (Auth::check()) {
      return redirect()->route('home');
    } else {
      return view('frontend.auth.login');
    }
  }

  public function forgetPassword(Request $request)
  {

    $request->validate([
      'email' => 'required|email|exists:customers',
    ], [
      'email.exists' => 'This Email Not Found',
    ]);
    $email = $request->email;
    $cus_data = Customer::getDatafromEmail($email);
    if ($cus_data != null) {
      $password = Str::random(10);

      $cus_data->password = Hash::make($password);
      $cus_data->save();

      $arr = array();
      $arr['cus_id'] = $cus_data->id;
      $arr['cus_name'] = $cus_data->username;
      $arr['cus_email'] = $cus_data->email;
      $arr['subject'] = config('email_message.forget_password');
      $arr['ord_id'] = null;
      $arr['type'] = 'forget_password';
      $arr['data'] = $password;
      $id = EmailSendData::insert_data($arr);
      if ($id != '') {
        EmailSendData::sendmail($id);
      }
      return redirect()->route('login')->with(['success' => 'We have Emailed your new password!']);
    } else {
      return redirect()->route('login')->withErrors(['login' => 'This Account Not Found']);
    }
  }

  //google login

  public function redirectToGoogle($page)
  {
    //dd(config('services.google.redirect'));
    if (Session::has('redirect_page')) {
      Session::forget('redirect_page');
      Session::save();
    }
    if ($page == 'home' || $page == 'payment') {
      Session::put('redirect_page', $page);
      Session::save();
    }
    return Socialite::driver('google')->redirect();
  }

  public function handleGoogleCallback()
  {

    try {

      $user = Socialite::driver('google')->stateless()->user();
      $finduser = Customer::where('email', $user->email)->first();
      if ($finduser != null) {
        $finduser->google_id = $user->id;
        $finduser->save();

        Auth::login($finduser);
      } else {
        $cus = new Customer;
        //$cus->username = $user->email;
        $password = Str::random(10);
        $cus->password =  Hash::make($password);
        $cus->email =  $user->email;
        $cus->google_id =  $user->id;
        $cus->is_active = 1;
        $cus->site_id = config('site_setting.site_id');
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

        Auth::login($cus);
      }

      if (Session::has('redirect_page')) {
        $page = Session::get('redirect_page');
      }
      if (isset($page) && $page == 'home' || $page == 'payment') {
        return redirect()->route($page);
      } else {
        return redirect()->route('home');
      }
    } catch (Exception $e) {
      return redirect()->route('dashboard')->withErrors(['error' => 'Error In Google Login']);
    }
  }

  //facebbook login

  public function redirectToFacebook($page)
  {
    //dd(config('services.google.redirect'));
    if (Session::has('redirect_page')) {
      Session::forget('redirect_page');
      Session::save();
    }
    if ($page == 'home' || $page == 'payment') {
      Session::put('redirect_page', $page);
      Session::save();
    }
    return Socialite::driver('facebook')->redirect();
  }

  public function handleFacebookCallback()
  {

    try {

      $user = Socialite::driver('facebook')->user();
      $finduser = Customer::where('email', $user->email)->first();
      if ($finduser != null) {
        if ($finduser->username == null) {
          $finduser->username = $user->name;
        }
        $finduser->facebook_id = $user->id;
        $finduser->save();

        Auth::login($finduser);
      } else {
        $cus = new Customer;
        if ($cus->username == null) {
          $cus->username = $user->name;
        }
        $password = Str::random(10);
        $cus->password =  Hash::make($password);
        $cus->email =  $user->email;
        $cus->facebook_id =  $user->id;
        $cus->site_id = config('site_setting.site_id');
        $cus->is_active = 1;
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

        Auth::login($cus);
      }

      if (Session::has('redirect_page')) {
        $page = Session::get('redirect_page');
      }
      if (isset($page) && $page == 'home' || $page == 'payment') {
        return redirect()->route($page);
      } else {
        return redirect()->route('home');
      }
    } catch (Exception $e) {
      return redirect()->route('dashboard')->withErrors(['error' => 'Error In Facebook Login']);
    }
  }

  public function doregister(Request $request)
  {
    $request->validate([
      'username' => 'required',
      'email' => 'required',
      'password' => 'required',
    ]);

    $customer = new Customer;
    $customer->username = $request->username;
    $customer->email = $request->email;
    $customer->password = Hash::make($request->password);
    $customer->is_active = 0;
    $customer->site_id = config('site_setting.site_id');
    $customer->referral_code = Customer::generateReferralcode();
    $customer->save();

    $arr = array();
    $arr['cus_id'] = $customer->id;
    $arr['cus_name'] = $customer->username;
    $arr['cus_email'] = $customer->email;
    $arr['subject'] = config('email_message.register');
    $arr['ord_id'] = null;
    $arr['type'] = 'new_user_register';
    $arr['data'] = $request->password;
    $id = EmailSendData::insert_data($arr);
    if ($id != '') {
      EmailSendData::sendmail($id);
      return redirect()->route('login')->withErrors(['login' => 'Your Account is Not Activate. Please Check Your Email']);
    } else {
      return redirect()->route('register')->withErrors(['error' => 'Register Error']);
    }
  }

  public function dologin(Request $request)
  {
    $request->validate([
      'email' => 'required|email',
      'password' => 'required',
    ]);

    $userdata = array(
      'email'     => $request->email,
      'password'  => $request->password,
      'site_id' => config('site_setting.site_id'),
    );

    $cart_data = SessionHelper::ReCreateCart();
    $age_popup = 0;
    if (Session::has('agepopup')) {
      $age_popup = Session::get('agepopup');
    }
    $ref_code = SessionHelper::getRefSession();

    if (Auth::attempt($userdata, true)) {
      $user_id = Auth::user()->id;
      if (Auth::user()->is_active == 1) {

        if (Session::has('Cart')) {
          Session::forget('Cart');
          Session::save();
        }

        if (isset($cart_data['session_data']) && count($cart_data['session_data'])) {
          foreach ($cart_data['session_data'] as $crt) {
            Session::push('Cart', $crt);
            Session::save();
          }
        }
        Session::put('agepopup', $age_popup);
        Session::save();

        if ($ref_code != null) {
          SessionHelper::setRefSession($ref_code);
        }

        return redirect()->route('dashboard');
      } else {
        Auth::logout();
        return redirect()->route('login')->withErrors(['login' => 'Your Account is Not Activate']);
      }
    } else {
      return redirect()->route('login')->withErrors(['login' => 'The email or password are incorrect']);
    }
  }

  public function dologout(Request $request)
  {
    Cookie::queue(Cookie::forget('login'));
    Cookie::queue(Cookie::forget('recentview'));
    Cookie::queue(Cookie::forget('laravel_session'));
    unset($_COOKIE['recentview']);
    unset($_COOKIE['login']);
    unset($_COOKIE['laravel_session']);
    Auth::guard('web')->logout();
    $request->session()->flush();
    return redirect()->route('login');
  }

  public function logout(Request $request)
  {
    if (Auth::check()) {
      $user_id = Auth::user()->id;
      if ($user_id != null) {
        Auth::logout();
      }
    }
    return redirect()->route('login');
  }

  public function success($access_token = null)
  {

    /* if($access_token == null){

            return redirect()->route('login');
        }else{

            $access_token = 'Authorization:Bearer '.$access_token;

            $auth_header = explode(' ', $access_token);
            $token = $auth_header[1];
            $token_parts = explode('.', $token);
            if(isset($token_parts[1]))
            {
                $token_header = $token_parts[1];
                $token_header_json = base64_decode($token_header);
                $token_header_array = json_decode($token_header_json, true);
                $token_id = $token_header_array['jti'];
                $user_id = DB::table('oauth_access_tokens')->where('id', $token_id)->value('user_id');

                $finduser = Customer::find($user_id);
                if($finduser != null){
                    Auth::login($finduser);
                    return redirect()->route('dashboard');
                }else{
                    return redirect()->route('login');
                }
            }else{
                return redirect()->route('login');
            }
        } */
  }
}
