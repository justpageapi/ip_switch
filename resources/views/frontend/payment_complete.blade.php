@php
if(Session::has('AddressData')){
  $session_data = Session::get('AddressData');
  if(isset($session_data[0]) && count($session_data[0]) > 0){
    $session_data = $session_data[0];
    $email = $session_data['email'];
    $phone = $session_data['phone'];
    $ship_adr_id = $session_data['ship_adr_id'];
    $bill_adr_id = $session_data['bill_adr_id'];
    $checkbox = $session_data['checkbox'];
    $ship_name = $session_data['ship_name'];
    $ship_addr_line_1 = $session_data['ship_addr_line_1'];
    $ship_addr_line_2 = $session_data['ship_addr_line_2'];
    $ship_city = $session_data['ship_city'];
    $ship_pin = $session_data['ship_pin'];
    $ship_state = $session_data['ship_state'] ;
    $ship_country = $session_data['ship_country'];
    $bill_name = $session_data['bill_name'];
    $bill_addr_line_1 = $session_data['bill_addr_line_1'];
    $bill_addr_line_2 = $session_data['bill_addr_line_2'];
    $bill_city = $session_data['bill_city'];
    $bill_pin = $session_data['bill_pin'];
    $bill_state = $session_data['bill_state'];
    $bill_country = $session_data['bill_country'];
    $postcode_verify = $session_data['postcode_verify'];    
  }
}
@endphp

@extends('frontend.mainlayout')

@section('title','Payment')
@section('description','discription')
@section('keyword','keyword')

@section('body')

    @if (Auth::check())
        @php $cus_id = Auth::user()->id; @endphp
    @endif

    <body class="body-scroll" data-page="cart">
      <form method="post" action="{{route('paynow')}}">
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
                          <input type="text" class="form-control p-2"  id="email" name="email" placeholder="E-mail * " value="{{$email}}" required readonly/>
                        </div>
                        <div class="form-floating m-field mb-2">
                          <input type="text" class="form-control p-2" id="phone" name="phone" placeholder="Contact Number * " value="{{$phone}}" required readonly/>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>              
                

                {{-- shipping address data --}}
                <div class="row">
                  <div class="col-12 col-md-6 col-lg-4 mx-auto">
                    <div class="card shadow-sm product mb-3">
                      <div class="card-header p-2">
                        <div class="row">
                          <div class="col align-self-center">
                            <h5 class="mb-0">Shipping Address<br></h5>
                          </div>
                          <div class="col-auto align-self-center">
                            <a class="btn btn-link text-color-theme py-0" href="{{route('payment')}}">
                              <i class="bi bi-pencil "></i>
                            </a>
                          </div>
                        </div>
                      </div>
                      <div class="card-body">
                        <div class="form-floating m-field mb-2">
                          <input type="text" class="form-control p-2" name="ship_name" id="ship_name" value="{{$ship_name}}" placeholder="Name" required readonly/>
                        </div>
                        <div class="form-floating m-field mb-2">
                          <input type="text" class="form-control p-2" name="ship_addr_line_1" id="ship_addr_line_1" value="{{$ship_addr_line_1}}" placeholder="Address Line 1" required readonly/>
                        </div>
                        <div class="form-floating m-field mb-2">
                          <input type="text" class="form-control p-2" name="ship_addr_line_2" id="ship_addr_line_2" value="{{$ship_addr_line_2}}" placeholder="Address Line 2" readonly/>
                        </div>
                        <div class="row">
                          <div class="col-6">
                            <div class="form-floating m-field mb-2">
                              <input type="text" class="form-control p-2" name="ship_city" id="ship_city" value="{{$ship_city}}" placeholder="Town" required readonly/>
                            </div>
                          </div>
                          <div class="col-6">
                            <div class="form-floating m-field mb-2">
                              <input type="text" class="form-control p-2" name="ship_pin" id="ship_pin" value="{{$ship_pin}}" placeholder="Postcode" required readonly/>
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-6">
                            <div class="form-floating m-field mb-0">
                              <input type="text" class="form-control p-2" name="ship_state" id="ship_state" value="{{$ship_state}}" placeholder="County" required readonly/>
                            </div>
                          </div>
                          <div class="col-6">
                            <div class="form-floating m-field mb-0">
                              <input type="text" class="form-control p-2" name="ship_country" id="ship_country" value="{{$ship_country}}" placeholder="Country" value="United Kingdom" required readonly/>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                @if($checkbox == 'true')
                    <div class="row mb-1">
                      <div class="col-12 col-md-6 col-lg-4 mx-auto mb-3">
                          <div class="form-floating m-field mb-0 ps-2">
                              <input name="checkbox" type="checkbox" <?php echo $checkbox == 'true' ? 'checked' : '';?> disabled/> Billing address is
                              same as shipping
                          </div>
                      </div>
                    </div>
                @endif
                
                
                @if($checkbox == 'false')

                    {{-- billing address data --}}               
                    <div class="row mb-2">
                      <div class="col-12 col-md-6 col-lg-4 mx-auto">
                        <div class="card shadow-sm product mb-3">
                          <div class="card-header p-2">
                            <div class="row">
                              <div class="col align-self-center">
                                <h5 class="mb-0">Billing Address<br></h5>
                              </div>
                              <div class="col-auto align-self-center">
                                <a class="btn btn-link text-color-theme py-0" href="{{route('payment')}}">
                                  <i class="bi bi-pencil "></i>
                                </a>
                              </div>
                            </div>
                          </div>
                          <div class="card-body">
                            <div class="form-floating m-field mb-2">
                              <input type="text" class="form-control p-2" name="bill_name" id="bill_name" value="{{$bill_name}}" placeholder="Name" required readonly/>
                            </div>
                            <div class="form-floating m-field mb-2">
                              <input type="text" class="form-control p-2" name="bill_addr_line_1" value="{{$bill_addr_line_1}}" id="bill_addr_line_1" placeholder="Address Line 1" required readonly/>
                            </div>
                            <div class="form-floating m-field mb-2">
                              <input type="text" class="form-control p-2" name="bill_addr_line_2" value="{{$bill_addr_line_2}}" id="bill_addr_line_2" placeholder="Address Line 2" readonly/>
                            </div>
                            <div class="row">
                              <div class="col-6">
                                <div class="form-floating m-field mb-2">
                                  <input type="text" class="form-control p-2" name="bill_city" value="{{$bill_city}}" id="bill_city" placeholder="Town" required readonly/>
                                </div>
                              </div>
                              <div class="col-6">
                                <div class="form-floating m-field mb-2">
                                  <input type="text" class="form-control p-2" name="bill_pin" value="{{$bill_pin}}" id="bill_pin" placeholder="Postcode" required readonly/>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-6">
                                <div class="form-floating m-field mb-0">
                                  <input type="text" class="form-control p-2" name="bill_state" value="{{$bill_state}}" id="bill_state" placeholder="County" required readonly/>
                                </div>
                              </div>
                              <div class="col-6">
                                <div class="form-floating m-field mb-0">
                                  <input type="text" class="form-control p-2" name="bill_country" value="{{$bill_country}}" id="bill_country" value="United Kingdom" placeholder="Country" required readonly/>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                
                @endif

                <div class="row d-nones">
                  <div class="col-12 col-md-6 col-lg-4 mx-auto">
                      <div class="row" id="payment_btn_div">

                        @if(config('pay360_config.enable') == 1)
                          <div class="col-lg-12 align-self-center d-grid mb-3">
                            <button class="btn btn-default btn-theme shadow-sm" name="payment" value="pay_card">Pay Now</button>
                          </div>
                        @endif
                        @if(config('superpay_config.enable') == 1)
                          <div class="col-lg-12 align-self-center d-grid mb-3">
                            <button type="button" class="btn btn-default btn-theme shadow-sm" id="pay_superpay">Pay by bank app</button>
                            <small>*We do not deliver to the postcode you have provided so we will deliver to you by post if possible.</small>
                          </div>   
                        @endif


                          @if($postcode_verify == null)

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
                              <small>*We do not deliver to the postcode you have provided so we will deliver to you by post if possible.</small>                     
                            </div>

                          @elseif($postcode_verify == 'found')
                            
                            {{-- <div class="col-lg-12 align-self-center d-grid mb-3">
                              <button class="btn btn-default btn-theme shadow-sm" name="payment" value="pay_at_door">Pay at Door</button>
                            </div> --}}

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
                            <div class="col-lg-12 align-self-center d-grid mb-3">
                              <button class="btn btn-default btn-theme shadow-sm" name="payment" value="collect">Collect Order</button>
                            </div>
                            
                          @endif   
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
    
    
    $(document).on('click','#pay_superpay',function(){
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
        $.ajax({
          url : '/PaySuperpay',
          type : 'POST', 
          success:function(data){    
            data = JSON.parse(data);
            if(data.type == 'success'){
                if(data.data){
                    //window.location.href = data.data.redirectUrl;                    
                    window.open(data.data.redirectUrl, "_blank");
                }else{
                    toastr.error('Payment Error'); 
                }
            }else{
                toastr.error(data.message); 
            }
          }
        });
    });
    
  </script>
@stop
@endsection
