@php
  $phone = App\Models\SiteSetting::getSitesetting('footer','footer_phone');
@endphp
<footer class="footer">
  <div class="container">
    <ul class="nav nav-pills nav-justified">
      <li class="nav-item">
        <a class="nav-link" href="{{ route('wishlist') }}">
          <span>
            <i class="nav-icon bi bi-heart font-wishlist" ></i><br><p class="footer-txt font-wishlist">WishList</p>
          </span>
        </a>
      </li>
      <li class="nav-item center-item crt_btn">
        <a class="nav-link" href="{{ route('cart') }}" >
          <span>
            <i class="nav-icon bi bi-bag"></i>
            <span class="nav-text">Cart</span>
            <span id="cartcounter" class="countercart">0</span>
          </span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="{{config('whatsappurl.whatsapp_url').$phone}}" target="_blank">
          <span>
              <i class="nav-icon bi bi-whatsapp font-chat" ></i><br><p class="footer-txt font-chat">Chat</p>
          </span>
        </a>
      </li>
    </ul>
  </div>
</footer>