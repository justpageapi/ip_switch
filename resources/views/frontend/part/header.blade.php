<header class="container-fluid header">
  <div class="row">

    <div class="col text-left">
      <div class="logo-small">
        <a href="{{route('dashboard')}}"><img src="/assets/frontend_assets/img/logo.png" alt="" class="img"></a>
        <!-- <h6>OUR<br><small>BULK STORE</small></h6> -->
      </div>
    </div>
    <div class="col-auto align-self-center">
      <button type="button" class="btn btn-link geo-btn text-color-theme">
        <i class="bi bi-geo-alt size-22"></i><span class="size-22">
          @php
          $cookie_data = '';
          if(isset($_COOKIE['geolocation'])){
          $cookie_data = $_COOKIE['geolocation'];
          $cookie_data = json_decode($cookie_data);
          }

          echo $cookie_data;
          @endphp
        </span>
      </button>
      <button type="button" class="btn btn-link menu-btn text-color-theme">
        <i class="bi bi-person-circle size-22"></i>
      </button>
    </div>
  </div>
</header>



<script type="text/javascript">



</script>