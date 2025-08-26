@extends('frontend.mainlayout')

@section('title', $seo_ttl ?: 'Account')
@section('description', $seo_discription)
@section('keyword', $seo_keyword)

@section('body')

    <body class="body-scroll" data-page="cart">
        <main class="h-100 has-header has-footer">
            <div class="main-container container">
                <div class="row">
                    <div class="col-12">
                        <div class="card card-theme shadow-sm mb-4">
                            <div class="card-body">
                                <div class="card card-light mb-2">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-auto">
                                                <figure class="avatar avatar-60 rounded mx-auto">
                                                    <img src="assets/frontend_assets/img/user2.jpg" alt="">
                                                </figure>
                                            </div>
                                            <div class="col align-self-center">
                                                <h5 class="mb-0">{{ Auth::user()->username }}</h5>
                                                <p class="text-opac">{{ Auth::user()->email }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <div class="col-auto align-self-center ml-3">
                                        <p>Share Then You Get {{ Auth::user()->referral_percentage }}% Discount on
                                            Order *</p>
                                    </div>
                                </div>
                                <div class="card card-light mb-2">
                                    <div class="card-body p-1">
                                        <div class="row m-0">
                                            <div class="col-auto align-self-center">                                                
                                              <button class="btn btn-sm avatar avatar-30 p-0 rounded-circle me-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                                                <i class="bi bi-share"></i>
                                              </button>
                                              <button class="btn btn-sm avatar avatar-30 p-0 rounded-circle me-2" type="button" onclick="copyElementText('{{env('APP_URL')}}/refcode/{{$customer->referral_code}}')" >
                                                <i  id="btn_Copy"  class="fa fa-clone"></i>
                                              </button>
                                            </div>
                                            <div class="col align-self-center">
                                                <h5 class="text-dark mb-0">{{ $customer->referral_code }}</h5>
                                            </div>
                                        </div>
                                        <div class="row m-0">
                                          <div class="col align-self-center">
                                            <div class="collapse" id="collapseExample">
                                              <div class="card-footer justify-content-center text-center">
                                                <p class="mb-1 text-opac">Share with</p>
                                                <a target="_blank" href="<?php echo config('socialurls.twitter_url').env('APP_URL').'/refcode/'.$customer->referral_code; ?>" class="btn btn-link text-color-theme"><i class="bi bi-twitter"></i></a>
                                                <a target="_blank" href="<?php echo config('socialurls.facebook_url').env('APP_URL').'/refcode/'.$customer->referral_code; ?>" class="btn btn-link text-color-theme"><i class="bi bi-facebook"></i></a>
                                                <a target="_blank" href="<?php echo config('socialurls.linkedin_url').env('APP_URL').'/refcode/'.$customer->referral_code; ?>" class="btn btn-link text-color-theme"><i class="bi bi-linkedin"></i></a>
                                                <a href="#" class="btn btn-link text-color-theme"><i class="bi bi-google"></i></a>
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
                <div class="row mb-3">
                    <div class="col">
                        <h5 class="mb-0">My Referrales </h5>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 ">
                        <div class="card shadow-sm">
                            <div class="card-body product">

                                @if ($customer->referrals->count() > 0)
                                    @foreach ($customer->referrals as $ref)
                                        <div class="row mb-2">
                                            <div class="col align-self-center">
                                                <p>{{ $ref->customer->username }}<br>
                                                  <small class="text-opac">{{ $ref->formatdate }}</small></p>
                                            </div>
                                            <div class="col-auto align-self-center">
                                                <p>Order No. {{ $ref->order->order_no }}</p>

                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="row">
                                        <div class="col align-self-center text-center">
                                            <p>No Referrals Found</p>
                                        </div>
                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </body>

    <!-- Modal -->


@section('footer-script')
    <script type="text/javascript">
        $(document).ready(function() {
          cartcounter();
        });

        function copyElementText(text) {
            $('#btn_Copy').addClass('text-white');
            toastr.success("Link Copied!");
            var elem = document.createElement("textarea");
            document.body.appendChild(elem);
            elem.value = text;
            elem.select();
            document.execCommand("copy");
            document.body.removeChild(elem);

            setTimeout(
            function() 
            {
              $('#btn_Copy').removeClass('text-white');
            }, 2000);
        }
    </script>
@stop
@endsection
