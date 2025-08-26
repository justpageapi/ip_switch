@php
  
  $address = App\Models\SiteSetting::getSitesetting('footer','footer_address');
  // $phone = App\Models\SiteSetting::getSitesetting('footer','footer_phone');
  $phone = '447481425845';
  $email = null;
@endphp

<div class="row footer-div bdr-top">
    <div class="col-12 p-0">     
      <ul class="footer-list nav justify-content-center border-bottom pt-2 pb-2 mb-3">
        <li class="py-1 font-weight-bold nav-item"><a class="nav-link px-3" href="{{url('coverage')}}">Coverage</a></li>
        <li class="py-1 font-weight-bold nav-item"><a class="nav-link px-3" href="{{url('refund-return')}}">Refunds &amp; Returns</a></li>
        <li class="py-1 font-weight-bold nav-item"><a class="nav-link px-3" href="{{url('delivery-information')}}">Delivery Information</a></li>
        <li class="py-1 font-weight-bold nav-item"><a class="nav-link px-3" href="{{url('privacy-policy')}}">Privacy Policy</a></li>
        <li class="py-1 font-weight-bold nav-item"><a class="nav-link px-3" href="{{url('terms-conditions')}}">Terms &amp; Conditions</a></li>
        <li class="py-1 font-weight-bold nav-item"><a class="nav-link px-3" href="{{url('about-us')}}">About Us</a></li>
        <li class="py-1 font-weight-bold nav-item"><a class="nav-link px-3" href="{{url('age-verification')}}">Age Verification</a></li>        
        <li class="py-1 font-weight-bold nav-item"><a class="nav-link px-3" href="{{url('login')}}">Login</a></li>
      </ul>
    </div>
</div>
<div class="row footer-div">    
    <div class="col-md-6 ">
        <div class="m-footer-box">
            <address>
                <h5 class="py-1 font-size-18 adr">4 Potter St, Bishop's Stortford CM23 3UL, United Kingdom</h5>
                <h5 class="py-1" class="m-call"><a class="text-black font-size-16" href="https://api.whatsapp.com/send/?phone=447481423280"><i class="nav-icon bi bi-whatsapp"></i> +44 7481 423280</a></h5>
            </address>    
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d153.96725899634643!2d0.15944364638420774!3d51.87052057347701!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47d885e025de9b07%3A0xf2d61efa5519bb8f!2sVape%20Shop%20-%20Bishop&#39;s%20Stortford!5e0!3m2!1sen!2suk!4v1681468612688!5m2!1sen!2suk" width="100%" height="150" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </div>
    <div class="col-md-6 ">
        <div class="m-footer-box">
            <address>
                <h5 class="py-1 font-size-18 adr">28-A Butter Market, Bury Saint Edmunds IP33 1DW, UK</h5>
                <h5 class="py-1" class="m-call"><a class="text-black font-size-16" href="https://api.whatsapp.com/send/?phone=447481423280"><i class="nav-icon bi bi-whatsapp"></i> +44 7481 423280</a></h5>
            </address>    
            <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d9771.532983205718!2d0.7131789!3d52.2455043!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47d84d9e5f832799%3A0xbc4fa9ff568931cb!2sDeliver%20My%20Vape%20CO%20UK%20-%20Free%20Home%20Delivery%20-%20Bury%20St%20Edmunds!5e0!3m2!1sen!2suk!4v1681468683946!5m2!1sen!2suk" width="100%" height="150" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </div>
</div>
<div class="row footer-div bdr-bottom">
    <div class="col-12 p-0 justify-content-center border-top text-center pt-2 mt-3 text-muted pb-2">      
      <p class="text-white">&copy; <?php echo date('Y');?> All Rights Reserved. </p>
    </div>
</div>
