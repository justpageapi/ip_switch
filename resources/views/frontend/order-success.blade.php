@extends('frontend.mainlayout')

@section('title','Order Success')
@section('description','Order')
@section('keyword', 'Order')

@section('body')
    <!-- loader section ends -->
    @php $user_id = null @endphp
  @if(Auth::check())
    @php $user_id = Auth::user()->id; @endphp
  @endif

    <body class="body-scroll d-flex flex-column" data-page="landing">

      <!-- Begin page content -->
        <main class="h-100 has-header has-footer">
          <div class="main-container container">
            <div class="row h-100">
                <div class="col-12 mx-auto text-center">
                    <div class="row h-100">
                        <div class="col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4 mx-auto align-self-center">
                            <div class="card card-custom shadow-sm mb-4">
                                <div class="card-body">
                                  <div class="row h-100">
                                    
                                    <div class="col-12 mx-auto text-center mb-3">
                                      <div class="row h-100">
                                        <div class="col-10 col-sm-8 col-md-8 col-lg-8 col-xl-8 mx-auto align-self-center">
                                          <h3 class="text-center">Order Placed Successfully!</h3>  
                                          <p class="mb-4">Thanks for your Kind order. If we have any issue with the order we will conatct with you. Many thanks</p>                     
                                        </div>
                                      </div>
                                    </div>

                                    <div class="col-12 mx-auto text-center mb-3">
                                      <div class="row h-100">
                                        <div class="col-6 col-sm-6 col-md-6 col-lg-6 col-xl-6  mx-auto align-self-center">
                                          <p class="mb-0 text-center">Order No.<br>{{$order_no}}</p>                    
                                        </div>
                                        <div class="col-6 col-sm-6 col-md-6 col-lg-6 col-xl-6  mx-auto align-self-center">
                                          <p class="mb-0 text-center">Order Date <br>{{$order_date}}</p>                     
                                        </div>
                                      </div>
                                    </div>
                        
                                    <div class="col-12 text-center align-self-end py-2">
                                      <div class="row">
                                        <div class="col text-center">
                                          @if($user_id == null)
                                            <a href="{{route('login')}}" class="btn btn-default btn-theme shadow-sm">Login</a>
                                          @else
                                            <a href="{{route('order')}}" class="btn btn-default btn-theme shadow-sm">My Order</a>
                                          @endif
                                        </div>
                                        <div class="col text-center">
                                          <a href="{{route('dashboard')}}" class="btn btn-default btn-theme shadow-sm">Back</a>
                                        </div>
                                      </div>
                                    </div>

                                  </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @include('frontend.part.extra-footer')
          </div>
      </main>
  </body>
  @section('footer-script')
  @stop
  @endsection