@extends('frontend.mainlayout')

@section('title','View Order')
@section('description','View Order')
@section('keyword', 'View Order')

@section('body')

<body class="body-scroll" data-page="cart">
  <main class="h-100 has-header has-footer">
    <div class="main-container container">
      <div class="row mb-3">
        <div class="col">
          <a href="{{ route('order')}}" class="text-start link text-color-theme">Back <i
              class="bi bi-chevron-right"></i></a>
        </div>
        <div class="col">
          <a href="{{ route('download_invoice',['order_id'=>Crypt::encrypt($order->id)])}}"
            class="text-end link text-color-theme"><i class="bi bi-download"></i> Download Invoice</a>
        </div>
      </div>

      <div class="row mb-2">
        <div class="col-lg-12 mx-auto">
          <div class="card">
            <div class="card-body">
              <div class="row">
                <div class="col-lg-6">
                  <h5>Name : {{$order->customer->username}}
                    {{-- @if($order->is_paid == 1)
                    (Invoice)
                    @else
                    (Packing List)
                    @endif --}}
                  </h5>

                </div>
                <div class="col-lg-6 text-end">
                  <h5>Email : {{$order->customer->email}}</h5>
                </div>
              </div>
              <hr class="py-0 mt-0" />
              <div class="row row-cols-3 d-flex justify-content-md-between">
                <div class="col-md-3 d-print-flex">
                  <div class="">
                    <h6><b>Seller Name : </b>{!!config('vatdata.SELLER_NAME') !!}</h6>
                    <h6><b>Address : </b>{!! config('vatdata.SELLER_ADDRESS') !!}</h6>
                    @php

                    $payment_status = $order->pay_data;
                    $status = $order->status;
                    $cls = '';
                    if($status == 'cancel'){
                    $cls = 'text-danger';
                    }

                    @endphp

                    <h6><b>Payment : </b>{{ $payment_status }}</h6>
                    @if($payment_status == 'paid')
                    <h6><b>VAT No. : </b>{!! config('vatdata.VAT_NO') !!}</h6>
                    <h6><b>Type Of Doc. : </b>Paid/invoice</h6>
                    @else
                    @if($status != 'cancel')
                    <h6><b>Type Of Doc. : </b>Unpaid/ Quote</h6>
                    @endif
                    @endif

                    <h6><b>Status : </b><span class="{{$cls}}">{{$status}}</span></h6>
                  </div>
                </div>
                <div class="col-md-3 d-print-flex">
                  <div class="">
                    <address class="font-13">
                      @php $billing = json_decode($order->billing_address); @endphp
                      <strong class="font-14">Billed To :</strong><br>
                      {{$billing[0]}}
                    </address>
                  </div>
                </div>
                <div class="col-md-3 d-print-flex">
                  <div class="">
                    <address class="font-13">
                      @php $shipping = json_decode($order->shipping_address); @endphp
                      <strong class="font-14">Shipped To:</strong><br>
                      {{$shipping[0]}}
                    </address>
                  </div>
                </div>
              </div>
              <br />

              <div class="row">
                <div class="col-lg-12">
                  <div class="table-responsive project-invoice">
                    <table class="table table-bordered mb-0">
                      <thead class="thead-light">
                        <tr>
                          <th>Image</th>
                          <th>Item</th>
                          <th>Price</th>
                          <th>Qty</th>
                          <th>Amount</th>
                        </tr>
                      </thead>
                      <tbody>
                        {{--@php $i=1; $subttl=0; @endphp
                        @foreach($order->orderitem as $item)
                        <tr>
                          <td><img src="{{ $item->variant->imagepath[0] }}" width="50"></td>
                          <td>{{ $item->variant->display_name }}</td>
                          <td>{!!env('currency')!!}{{ $item->price }}</td>
                          @if($order->status == 'pending' || $order->status == 'packing' || $order->status == 'cancel')
                          <td>{{ $item->qty }}</td>
                          <td>{!!env('currency')!!}{{ $item->price * $item->qty }}</td>
                          @else
                          <td>{{ $item->send_qty }}</td>
                          <td>{!!env('currency')!!}{{ $item->price * $item->send_qty }}</td>
                          @endif
                        </tr>
                        @endforeach --}}

                        @foreach($order->orderitem as $item)
                        <tr>
                          <td><img src="{{ $item->product->imagepath }}" width="50"></td>
                          <td>{{ $item->product->name }}</td>
                          <td>{!!config('currency.currency')!!}{{ $item->price }}</td>
                          <td>{{ $item->qty }}</td>
                          <td>{!!config('currency.currency')!!}{{ $item->price * $item->qty }}</td>
                        </tr>
                        @endforeach

                        <tr>
                          <td colspan="3" class="border-0"></td>
                          <td class="border-0 font-14 text-dark"><b>Sub Total</b></td>
                          <td class="border-0 font-14 text-dark"><b>{!!config('currency.currency')!!}{{
                              $order->subtotal_amount }}</b></td>
                        </tr>
                        <tr>
                          <th colspan="3" class="border-0"></th>
                          <td class="border-0 font-14 text-dark"><b>Discount</b></td>
                          <td class="border-0 font-14 text-dark"><b>{!!config('currency.currency')!!}{{
                              ($order->discount) != null ? $order->discount : '0' }}</b></td>
                        </tr>
                        <tr>
                          <th colspan="3" class="border-0"></th>
                          <td class="border-0 font-14 text-dark"><b>Extra Discount
                              ({{$order->extra_discount_rate}}%)</b></td>
                          <td class="border-0 font-14 text-dark"><b>{!!config('currency.currency')!!}{{
                              ($order->extra_discount) != null ?
                              $order->extra_discount : '0' }}</b></td>
                        </tr>
                        @if($order->referral_discount != 0)
                        <tr>
                          <th colspan="3" class="border-0"></th>
                          <td class="border-0 font-14 text-dark"><b>Affiliate Discount</b></td>
                          <td class="border-0 font-14 text-dark"><b>{!!config('currency.currency')!!}{{
                              $order->referral_discount }}</b></td>
                        </tr>
                        @endif
                        <tr>
                          <th colspan="3" class="border-0"></th>
                          <td class="border-0 font-14 text-dark"><b>Delivery Charge</b></td>
                          <td class="border-0 font-14 text-dark"><b>{!!config('currency.currency')!!}{{
                              $order->delivery_charge }}</b></td>
                        </tr>


                        <tr class="bg-black text-white">
                          <th colspan="3" class="border-0"></th>
                          @if($payment_status == 'paid')
                          <td class="border-0 font-14 text-dark"><b>total amount(Inc. VAT {!!
                              env('tax_percentage')!!}%)</b></td>
                          @else
                          <td class="border-0 font-14 text-dark"><b>Total Amount</b></td>
                          @endif
                          <td class="border-0 font-14 text-dark"><b>{!!config('currency.currency')!!}{{
                              $order->total_amount }}</b></td>
                        </tr>
                      </tbody>
                    </table>
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