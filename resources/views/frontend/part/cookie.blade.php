@php
$set_cookie = App\Helpers\CookieHelper::checkCookieSet();
@endphp

@if($set_cookie == 0)
<div class="alert alert-warning alert-dismissible fade show cookie_class" id="cookie_alert" role="alert">
  <p class="mb-1">This website collects cookies to deliver better user experience.</p>
  <button type="button" class="btn btn-xs btn-danger" data-bs-dismiss="alert" style="font-size: 10px;">Close</button>
  <button type="button" class="btn btn-xs btn-primary" onclick="set_cookie()" style="font-size: 10px;">Accept</button>
</div>
@endif