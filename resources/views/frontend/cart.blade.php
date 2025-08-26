@extends('frontend.mainlayout')
@section('title', $seo_ttl ?: 'Cart')
@section('description', $seo_discription)
@section('keyword', $seo_keyword)
@section('body')

@php $user_id = null @endphp
@if(Auth::check())
@php $user_id = Auth::user()->id; @endphp
@endif

<body class="body-scroll" data-page="cart">
    <main class="h-100 has-header has-footer">
        <div class="main-container container top-40">
            <div class="row mb-3">
                <div class="col align-self-center">
                    <h5 class="mb-0">Cart Items</h5>
                </div>
                <div class="col-auto pe-0 align-self-center">
                    <a href="{{ route('dashboard') }}" class="link text-color-theme">Shop <i
                            class="bi bi-chevron-right"></i></a>
                </div>
            </div>

            @if($sub_ttl > 0)

            <div class="row mb-3">
                <div class="card" id="cart_data">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col align-self-center">
                    <h5 class="mb-0">Pricing</h5>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col">
                    <p>Items Amount</p>
                </div>
                <div class="col-auto">{{ config('currency.symbol') }}<label id="subtotal">{{ $sub_ttl }}</label>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col">
                    <p>Offer Applied
                        <small id="offer">
                            @php $i=0; @endphp
                            @if ($offers != null && !empty($offers))

                            @foreach ($offers as $getoffer)
                            @php $cnt=count($offers); @endphp
                            @foreach ($getoffer as $key => $offer)
                            @if ($offer != null)
                            {{ '(' . $key . ') x ' . $offer }}
                            @endif
                            @endforeach
                            @endforeach

                            @endif
                        </small>
                    </p>
                </div>
                <div class="col-auto">- {{ config('currency.symbol') }}<label id="discount">{{ $discount }}</label>
                </div>
            </div>
            @if($referral != null && $referral != 0 && $referral_percentage != null && $referral_percentage != 0)
            <div class="row mb-3" id="affiliate">
                <div class="col">
                    <p id="reffreal_perc">Affiliate Discount ({{$referral_percentage}}%)</p>
                </div>
                <div class="col-auto">- {{ config('currency.symbol') }} <label id="referral">{{ $referral }}</label>
                </div>
            </div>
            @endif
            @if($extra_discount != null && $extra_discount != 0)
            <div class="row mb-3">
                <div class="col">
                    <p id="reffreal_perc">Extra Discount ({{$extra_discount_percentage}}%)</p>
                </div>
                <div class="col-auto">- {{ config('currency.symbol') }} <label id="extra_discount">{{ $extra_discount
                        }}</label>
                </div>
            </div>
            @endif
            <div class="row mb-3">
                <div class="col">
                    <p>Delivery Cost <small>( Order Over 20 GBP Then Get Free Delivery )</small></p>
                </div>
                <div class="col-auto">{{ config('currency.symbol') }}<label id="shipping_cost">{{ $shipping }}</label>
                </div>
            </div>
            <div class="row fw-bold mb-4">
                <div class="mb-3 col-12">
                    <div class="dashed-line"></div>
                </div>
                <div class="col">
                    <p>Bill Amount</p>
                </div>
                <div class="col-auto">{{ config('currency.symbol') }}<label id="bill_amount">{{ $total }}</label>
                </div>
            </div>

            <div class="mb-3 col-12 cart_extra_discount">
                <div class="dashed-line"></div>
            </div>

            <div class="row mb-3 cart_extra_discount">
                <div class="col align-self-center">
                    <h5 class="mb-0">You can also add items</h5>
                </div>
                <div class="col-auto pe-0 align-self-center">
                    <h6>Avoid delivery cost add more {{ config('currency.symbol') }}<label id="more_cost">{{ $shipping
                            }}</label> product to cart</h6>
                </div>
            </div>

            <div class="row mb-3 cart_extra_discount">
                <div class="card" id="extra_item">
                </div>
            </div>

            @if($user_id != null)
            <div class="row mb-3 text-center mx-auto">
                <div class="col-12 col-md-12 col-lg-12">
                    <a href="{{ route('payment') }}" class="btn btn-default btn-theme shadow-sm">Process To CHECKOUT</a>
                </div>
            </div>
            @else
            <div class="row mb-3 text-center mx-auto">
                <div class="col-12 col-md-12 col-lg-12 mb-3">
                    <a href="{{ url('auth.google/payment') }}">
                        <img src="/assets/check out with goolge sign.png" style="width:210px;">
                    </a>
                </div>
                {{-- <div class="col-12 col-md-12 col-lg-12 mb-3">
                    <a href="{{ url('auth.facebook/payment') }}">
                        <img src="/assets/Checkout with Facebook.png" style="width:210px;">
                    </a>
                </div> --}}
                <div class="col-12 col-md-12 col-lg-12 mb-3">
                    <a href="{{ route('register') }}" class="btn btn-default btn-theme shadow-sm">CHECKOUT WITH
                        REGISTER</a>
                </div>

                <span id="guest_user_extra_discount_msg"></span>

                <div class="col-12 col-md-12 col-lg-12 mt-3">
                    <a href="{{ route('payment') }}" class="btn btn-default btn-theme shadow-sm">CHECKOUT WITH
                        GUEST</a>
                </div>
            </div>
            @endif
            {{-- <div class="row mb-4 text-center">
                <div class="col-12 col-md-12 col-lg-12">
                    <a href="{{ route('auth/google')}}" class="btn btn-default btn-theme shadow-sm w-60">SIGNIN TO
                        CHECKOUT</a>
                </div>
            </div> --}}
            <!-----------------------------------------------------------------Product display-------------------------------------->
            <div class="row home-pdr-box" id="prd_data">

            </div>
            @else
            <div class="row mb-4">
                <div class="card bg-white p-3">
                    <div class="row m-auto text-center">
                        <div class="col-12 mb-2">
                            <img src="/assets/cart_empty.jpg" class="w-65">
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
    $(document).ready(function() {
            cartcounter();
            getExtraCartData();
            @if($sub_ttl > 0)
                getCartData();
            @endif
        });
</script>
@stop
@endsection