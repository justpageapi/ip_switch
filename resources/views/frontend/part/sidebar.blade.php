@php
  $phone = App\Models\SiteSetting::getSitesetting('footer','footer_phone');
@endphp
<div class="sidebar-wrap sidebar-overlay">
  <div class="closemenu text-opac">Close Menu</div>
  <div class="sidebar">
    <div class="row mt-4 mb-3">
      <div class="col-auto">
        <figure class="avatar avatar-60 rounded mx-auto my-1">
          <img src="/assets/frontend_assets/img/user2.jpg" alt="">
        </figure>
      </div>
      @if(\Auth::check())
       @php 
        $username = \Auth()->user()->username;
        $email = \Auth()->user()->email;
        @endphp
      @endif
      <div class="col align-self-center ps-0">
        @if(isset($username) )        
          <h6 class="mb-0 wrap_text">{{$username}}</h6>
        @endif
        @if(isset($email))
          <p class="text-opac wrap_text">{{$email}}</p>
        @endif
        @if(\Auth::check() == false)
          <a href="{{route('login')}}" class="btn btn-default btn-sm btn-theme shadow-sm w-100">Login</a>
        @endif
      </div>
    </div>
    <hr/>
    <div class="row">
      <div class="col-12">
        <ul class="nav nav-pills">
            
          {{-- <li class="nav-item">
            <a class="nav-link" href="{{ route('dashboard') }}" tabindex="-1">
              <div class="avatar avatar-40 rounded icon"><i class="bi bi-shop"></i></div>
              <div class="col">Quick Order</div>
              <div class="arrow"><i class="bi bi-arrow-right"></i></div>
            </a>
            
          </li> --}}
          <li class="nav-item">
            <a class="nav-link" href="{{ route('account') }}" tabindex="-1">
              <div class="avatar avatar-40 rounded icon"><i class="bi bi-person-square"></i></div>
              <div class="col">My Account</div>
              <div class="arrow"><i class="bi bi-arrow-right"></i></div>
            </a>
          </li>
          
          <li class="nav-item">
            <a class="nav-link" href="{{ route('order')}}" tabindex="-1">
              <div class="avatar avatar-40 rounded icon"><i class="bi bi-cart"></i></div>
              <div class="col">My Orders</div>
              <div class="arrow"><i class="bi bi-arrow-right"></i></div>
            </a>
          </li>
          
          <li class="nav-item">
            <a class="nav-link" href="{{ route('cart') }}" tabindex="-1">
              <div class="avatar avatar-40 rounded icon"><i class="bi bi-bag"></i></div>
              <div class="col">Shopping Cart</div>
              <div class="arrow"><i class="bi bi-arrow-right"></i></div>
            </a>
          </li>
          
          {{--<li class="nav-item">
            <a class="nav-link" href="{{route('message')}}" tabindex="-1">
              <div class="avatar avatar-40 rounded icon"><i class="bi bi-chat-text"></i></div>
              <div class="col">Messages</div>
              <div class="arrow"><i class="bi bi-arrow-right"></i></div>
            </a>
          </li>--}}
          
          <li class="nav-item">
            <a class="nav-link" href="{{route('wishlist')}}" tabindex="-1">
              <div class="avatar avatar-40 rounded icon"><i class="bi bi-heart"></i></div>
              <div class="col">WishList</div>
              <div class="arrow"><i class="bi bi-arrow-right"></i></div>
            </a>
          </li>
          
          <li class="nav-item">
            <a class="nav-link" href="{{route('recent')}}" tabindex="-1">
              <div class="avatar avatar-40 rounded icon"><i class="bi bi-clock"></i></div>
              <div class="col">Recent View</div>
              <div class="arrow"><i class="bi bi-arrow-right"></i></div>
            </a>
          </li>
          
          <li class="nav-item">           
            <a class="nav-link" href="{{config('whatsappurl.whatsapp_url').$phone}}" target="_blank" tabindex="-1">
              <div class="avatar avatar-40 rounded icon"><i class="bi bi-whatsapp"></i></div>
              <div class="col">Messages</div>
              <div class="arrow"><i class="bi bi-arrow-right"></i></div>
            </a>
          </li>

          <li class="nav-item">
            @if(isset($email))
              <a class="nav-link" href="{{ route('dologout')}}" tabindex="-1">  
                <div class="avatar avatar-40 rounded icon"><i class="bi bi-box-arrow-right"></i></div>
                <div class="col">Logout</div>
                <div class="arrow"><i class="bi bi-arrow-right"></i></div>
              </a>          
            @endif              
          </li>
          
        </ul>
      </div>
    </div>
  </div>
</div>
