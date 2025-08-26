@php
$email = '';
$phone = '';
if(Session::has('AddressData')){
$session_data = Session::get('AddressData');
if(isset($session_data[0]) && count($session_data[0]) > 0){
$session_data = $session_data[0];
$email = $session_data['email'];
$phone = $session_data['phone'];
}
}else{
if(Auth::check()){
$email = Auth::user()->email;
$phone = App\Models\Order::getLastContactno();
}
}
@endphp

@extends('frontend.mainlayout')

@section('title',$seo_ttl ?: 'Payment')
@section('description',$seo_discription)
@section('keyword', $seo_keyword)

@section('body')

@if (Auth::check())
@php $cus_id = Auth::user()->id; @endphp
@endif

<body class="body-scroll" data-page="cart">
  <form method="post" action="{{route('paynow')}}" id="verify_form">
    @csrf
    <main class="h-100 has-header has-footer">
      <div class="main-container container mt-4">
        <div class="row">
          <div class="col-12 col-md-6 col-lg-4 mx-auto">
            <div class="card mb-3 shadow-sm">
              <div class="card-body text-center">
                <p class="mb-0 text-color-theme text-center text-uppercase">Payment :
                  {{ config('currency.symbol') . $total }}</p>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-12 col-md-6 col-lg-4 mx-auto">
            <div class="card shadow-sm product mb-3">
              <div class="card-header p-2">
                <div class="row">
                  <div class="col align-self-center">
                    <h5 class="mb-0">Shipping information<br></h5>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <div class="form-floating m-field mb-2">
                  <input type="text" class="form-control p-2" value="{{$email}}" id="email" name="email"
                    placeholder="E-mail" {{$email !=null ? 'readonly' : '' }} />
                </div>
                <div class="form-floating m-field mb-2">
                  <input type="text" class="form-control p-2" value="{{$phone}}" id="phone" name="phone"
                    placeholder="Contact Number * " required />
                </div>
                <div class="card-header p-2">
                  <div class="row">
                    <div class="col align-self-center">
                      <h5 class="mb-0">Any Note For Delivery Driver<br></h5>
                    </div>
                  </div>
                </div>
                <div class="form-floating m-field mb-2">
                  <textarea rows="3" class="form-control p-2" id="delivery_note" name="delivery_note"
                    placeholder="Any Note For Delivery Driver"></textarea>
                </div>
              </div>
            </div>
          </div>
        </div>

        {{-- shipping address data --}}
        <div class="row" id="ship_div">
        </div>

        <div class="row mb-1">
          <div class="col-12 col-md-6 col-lg-4 mx-auto">
            <div class="form-floating m-field mb-0 ps-2">
              <input id="same_as_ship_checkbox" name="checkbox" type="checkbox" /> Billing address is
              same as shipping
            </div>
          </div>
        </div>

        {{-- billing address data --}}
        <div class="row mb-2" id="bill_div">
        </div>
        <div class="row d-nones">
          <div class="col-12 col-md-6 col-lg-4 mx-auto">
            <div class="row" id="payment_btn_div">
              @if (Auth::check())
              @if(config('pay360_config.enable') == 1)
              <div class="col-lg-12 align-self-center d-grid mb-3">
                <button class="btn btn-default btn-theme shadow-sm" name="payment" value="pay_card">Pay Now</button>
              </div>
              @endif
              @if(config('superpay_config.enable') == 1)
              <div class="col-lg-12 align-self-center d-grid mb-3">
                <button type="button" class="btn btn-default btn-theme shadow-sm" id="pay_superpay">Pay by bank
                  app</button>
                <small>*We do not deliver to the postcode you have provided so we will deliver to you by post if
                  possible.</small>
              </div>
              @endif
              @endif
              <div class="col-lg-12 align-self-center d-grid mb-3">
                <button class="btn btn-default btn-theme shadow-sm btn-pay-at-door" name="payment" value="pay_at_door">
                  <div class="card-img-div">
                    <img src="/assets/contact_less.png">
                  </div>
                  <div class="btn-right-text">
                    <div>Pay at Door</div>
                    <div class="btn-right-name"> contactless card</div>
                  </div>
                </button>
              </div>
              @if (Auth::check())
              <div class="col-lg-12 align-self-center d-grid mb-3">
                <button class="btn btn-default btn-theme shadow-sm" name="payment" value="collect">Collect
                  Order</button>
              </div>
              @endif
              @if (Auth::check())
              {{-- <div class="col-lg-12 align-self-center d-grid mb-3">
                <button class="btn btn-default btn-theme shadow-sm" name="payment" value="paypal">Pay Using
                  Paypal</button>
              </div> --}}
              @endif
            </div>
          </div>
        </div>
      </div>
      @include('frontend.part.extra-footer')
      </div>
    </main>
  </form>
</body>
@section('footer-script')
<script>
  $(document).ready(function() {
            $('#same_as_ship_checkbox').prop('checked',true)
            getShipingAddress();
            cartcounter();
        });

        // for error message toast
        @if (count($errors) > 0)
          @foreach ($errors->all() as $error)
            toastr.error("{{ $error }}");
          @endforeach
        @endif

        // for success message toast
        @if (session('success'))
          toastr.success("{{ session('success') }}");
        @endif


</script>
@stop
@endsection