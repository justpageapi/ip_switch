@extends('frontend.mainlayout')

@section('title',$seo_ttl ?: 'Order')
@section('description',$seo_discription)
@section('keyword', $seo_keyword)

@section('body')

@php $user_id = null @endphp
@if(Auth::check())
  @php $user_id = Auth::user()->id; @endphp
@endif

<body class="body-scroll" data-page="cart">
  <main class="h-100 has-header has-footer">
    <div class="main-container container">
      <div class="row mb-3">
        <div class="col align-self-center">
          <h5 class="mb-0">{{$no_of_order}} Order Placed</h5>
        </div>
        <div class="col-auto pe-0 align-self-center">
          <a href="{{ route('dashboard')}}" class="link text-color-theme">Back <i class="bi bi-chevron-right"></i></a>
        </div>
      </div>
      @if($user_id != '')
        <div class="row mb-2">
          @if($order->count() > 0)
            @foreach($order as $order)
            <div class="col-12 col-md-6 col-lg-4">
              <div class="card shadow-sm product mb-3">
                <div class="card-body">
                  <div class="row">
                    <div class="col align-self-center">
                      <div class="row">
                        <div class="col">
                          <p class="mb-0">
                            {{$order->formatdate}}
                          </p>
                        </div>
                        <div class="col-auto">
                            <a href="{{route('view-order',['id'=>Crypt::encrypt($order->id)])}}" target="blank" class="link text-color-theme px-0">View <i class="bi bi-chevron-right"></i></a>
                        </div>
                      </div>
                      <h6 class="text-color-theme">Invoice No : {{$order->order_no}}</h6>
                      <div class="row">
                        <div class="col">
                          @if($order->status != 'cancel')
                            @php
                            $date = \Carbon\Carbon::parse($order->created_at);
                            $now =  \Carbon\Carbon::now();

                            $diff = $date->diffInDays($now);
                            @endphp
                            @if($diff > 3)
                              <p class="text-primary"><b>Status : </b> {{$order->status}}</p>
                            @else
                              <p class="text-primary"><b>Status : </b> sent out</p>
                            @endif
                          @else
                            <p class="text-primary"><b>Status : </b> {{$order->status}}</p>
                          @endif
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-6">
                            @if($order->status == 'pending' || $order->status == 'process' || $order->status == 'collect')
                                <a href="{{route('cancel-order',['id'=>Crypt::encrypt($order->id)])}}" class="link text-color-theme px-0 text-start cancel_order_confirmation">Cancel Order</a>
                            @endif
                        </div>
                        <div class="col-6">
                          {{-- <a href="#" target="blank" class="link text-color-theme px-0 text-end">Track Order <i class="bi bi-chevron-right"></i></a>  --}}
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            @endforeach
          @else
            <div class="col-12">
              <div class="card shadow-sm product mb-3 bg-white">
                <div class="card-body mx-auto">
                  <img src="/assets/no_order_img.png">
                </div>
              </div>
            </div>          
          @endif
        </div>
      @else
          <div class="row mb-4">
            <div class="card bg-white p-3">
              <div class="row m-auto text-center">
                <div class="col-12 mb-2">
                  <h5>Please Login To See Your Orders</h5>                  
                </div>
                <div class="col-12">
                  <a href="{{route('login')}}" class="btn btn-theme">Login</a>                 
                </div>
              </div>
            </div>
          </div>     
        @endif
      @include('frontend.part.extra-footer')
    </div>
  </main>
</body>
@section('footer-script')
<script type="text/javascript">
  $('.cancel_order_confirmation').on('click', function () {
      return confirm('Are you sure you want to cancel this order ?');
  });
</script>
@stop
@endsection