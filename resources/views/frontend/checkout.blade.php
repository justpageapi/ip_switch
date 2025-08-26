
@extends('frontend.mainlayout')

@section('title',$seo_ttl ?: 'Checkout')
@section('description',$seo_discription)
@section('keyword', $seo_keyword)

@section('body')
<body class="body-scroll" data-page="cart">
    <main class="h-100 has-header has-footer">
      <div class="main-container container top-40">
        <div class="row mb-3">
          <div class="col align-self-center">
            <h5 class="mb-0">{{ $qty }} products in cart</h5>
          </div>
          <div class="col-auto pe-0 align-self-center">
            <a href="{{ route('dashboard')}}" class="link text-color-theme p-0">Shop more <i class="bi bi-chevron-right"></i></a>
          </div>
        </div>
        <div class="row mb-3">
          <div class="col">
            <p>Subtotal</p>
          </div>
          <div class="col-auto">{!! env('currency') !!}{{ $subttl }}</div>
        </div>
        <div class="row mb-3">
          <div class="col">
            <p>Shipping Cost</p>
          </div>
          <div class="col-auto">{!! env('currency') !!}{{ $shipping_cost }}</div>
        </div>
        <div class="row fw-bold mb-4">
          <div class="mb-3 col-12">
            <div class="dashed-line"></div>
          </div>
          <div class="col">
            <p>Bill Amount</p>
          </div>
          <div class="col-auto">{!! env('currency') !!}{{ $total }}</div>
        </div>
        @php $final_amount = 0 @endphp
        @if($total > $wallet_amount)

        <div class="row fw-bold mb-4">
          <div class="mb-3 col-12">
            <div class="dashed-line"></div>
          </div>
          <div class="col">
            <p>Wallet Amount</p>
          </div>
          @php $final_amount = $total - $wallet_amount @endphp
          <div class="col-auto">{!! env('currency') !!}{{ $wallet_amount }}</div>
        </div>

        <div class="row fw-bold mb-4">
          <div class="mb-3 col-12">
            <div class="dashed-line"></div>
          </div>
          <div class="col">
            <p>Total Amount</p>
          </div>
          @php $final_amount = sprintf('%.2f', $final_amount); @endphp
          <div class="col-auto">{!! env('currency') !!}{{ $final_amount }}</div>
        </div>

        @elseif($total < $wallet_amount)

          @php $final_amount = $total @endphp
         
        @else

          @php $final_amount = $total @endphp
          <div class="row fw-bold mb-4">
          <div class="mb-3 col-12">
            <div class="dashed-line"></div>
          </div>
          <div class="col">
            <p>Total Amount</p>
          </div>
          
          <div class="col-auto">{!! env('currency') !!}{{ $final_amount }}</div>
        </div>
  
        @endif

        
        <div class="row mb-3">
          <div class="col align-self-center d-grid">
            @if($type == 'wholeseller')
              @if($qty > 0)
                <a href="{{route('address')}}" class="btn btn-default btn-lg btn-theme shadow-sm mb-3">ORDER NOW</a>
              @endif
            @else
              @if($total < $wallet_amount)
                <a href="{{route('address')}}" class="btn btn-default btn-lg btn-theme shadow-sm mb-3">ORDER NOW</a>
              @else
                <a id="pay_with_card" class="btn btn-default btn-lg btn-theme shadow-sm">PAY WITH CARDS</a>
              @endif
            @endif
          </div>
        </div>
      </div>
    </main>
  </body>
@section('footer-script')
<script src="https://js.stripe.com/v3/"></script>
<script src=""></script>
<script type="text/javascript">
  // Create an instance of the Stripe object with your publishable API key
  var stripe = Stripe('{!! env('STRIPE_KEY') !!}');
  $(document).on('click','#pay_with_card',function(){
   
      fetch('{{ route('pay_with_card') }}', {
        headers: {
        "Content-Type": "application/json",
        "Accept": "application/json",
        "X-Requested-With": "XMLHttpRequest",
        "X-CSRF-Token":'{{ csrf_token() }}'
      },
        method: "POST",
        body:JSON.stringify({discount:'{{$wallet_amount}}' }),
  
      }).then(function (response) {
        return response.json();
      }).then(function (session) {
        return stripe.redirectToCheckout({ sessionId: session.id });
      }).then(function (result) {
        // If redirectToCheckout fails due to a browser or network
        // error, you should display the localized error message to your
        // customer using error.message.
        if (result.error) {
          alert(result.error.message);
        }
      }).catch(function (error) {
        console.error("Error:", error);
      });
  });

  
  </script>
@stop
@endsection