@php
  
  $address = App\Models\SiteSetting::getSitesetting('footer','footer_address');
  $phone = App\Models\SiteSetting::getSitesetting('footer','footer_phone');
  $email = null;
@endphp
  <div class="row footer-div p-3">
    <div class="col-6 col-lg-3 p-3">     
      <ul class="list-grp list-group-flush footer-list">
        <li class="py-1 font-weight-bold"><a href="{{url('refund-return')}}">Refunds &amp; Returns</a></li>
        <li class="py-1 font-weight-bold"><a href="{{url('delivery-information')}}">Delivery Information</a></li>
        <li class="py-1 font-weight-bold"><a href="{{url('privacy-policy')}}">Privacy Policy</a></li>
        <li class="py-1 font-weight-bold"><a href="{{url('terms-conditions')}}">Terms &amp; Conditions</a></li>
      </ul>
    </div>
    <div class="col-6 col-lg-3 p-3">
      <ul class="list-grp list-group-flush footer-list">                
        <li class="py-1 font-weight-bold"><a href="{{url('about-us')}}">About Us</a></li>
        <li class="py-1 font-weight-bold"><a href="{{url('age-verification')}}">Age Verification</a></li>        
        <li class="py-1 font-weight-bold"><a href="{{url('login')}}">Login</a></li>
      </ul>
    </div>
    <div class="col-12 col-lg-6 p-1">
      <hr class="d-lg-none mt-4 bg-white">
      <h4 class="py-1 font-size-20">Address</h4>      
      <address>
        <h5 class="py-1 font-size-20">{{$address}}</h5>
        <h5 class="py-1"><a class="text-black font-size-20" href="{{config('whatsappurl.whatsapp_url').$phone}}"><i class="nav-icon bi bi-whatsapp" style="color:white"></i> {{$phone}}</a></h5>
      </address>     
    </div>
    <div class="col-12 p-1">      
      <iframe class="border-radius-5" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2463.6262436388333!2d0.1589364!3d51.8677803!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47d8853b8ba3d385%3A0x435527494dc125a0!2s22%20Newtown%20Rd%2C%20Bishop&#39;s%20Stortford%20CM23%203SD%2C%20UK!5e0!3m2!1sen!2sin!4v1680176904073!5m2!1sen!2sin" width="100%" height="200" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>   
    </div>
  </div>
