@extends('frontend.mainlayout')
@section('title',$seo_ttl ?: 'WishList')
@section('description',$seo_discription)
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
            <h5 class="mb-0">WishList</h5>
          </div>
          <div class="col-auto pe-0 align-self-center">
            <a href="{{ route('dashboard')}}" class="link text-color-theme">Shop <i class="bi bi-chevron-right"></i></a>
          </div>
        </div>
        
        @if($user_id != null)
          <div class="row mb-4">
              <div class="card bg-white" id="wishlist_data">
                  
              </div>
          </div>     
          
          <!----------------------------------------------------modal for show product details-------------------------------------------->
          <div class="modal fade" id="viewproductdetails" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-md modal-dialog-centered">
              <div class="modal-content product border-0 shadow-sm" id="prd_dtl">
                <div class="card shadow-sm">
                    <div class="card-body pb-0 position-relative">
                        <div class="swiper-container imageswiper">
                            <div class="swiper-wrapper" id="prd_img_data">

                            </div>
                            <div class="swiper-pagination imageswiper-pagination "></div>
                        </div>
                    </div>
                    <div class="collapse" id="collapseExample">
                        <div class="card-footer justify-content-center text-center" id="share_urls">
                            
                        </div>
                    </div>
                </div>

                  <div class="cls_icon">
                      <a class="btn btn-link text-color-theme p-1 py-0 " data-bs-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i></a>
                      <div class="px-1" id="btn_share" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample" ><i class="fa fa-share-alt"></i></div>
                  </div>
                  
                  <div class="modal-body" id="prd_details">
                  </div>
                  <div class="modal-footer justify-content-center p-1">
                      <button type="button" class="btn btn-link text-color-theme m-0 p-1" data-bs-dismiss="modal">Done</button>
                  </div>
              </div>
            </div>
          </div>
        @else
          <div class="row mb-4">
            <div class="card bg-white p-3">
              <div class="row m-auto text-center">
                <div class="col-12 mb-2">
                  <h5>Please Login To See Your Wishlist Products</h5>                  
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

  $(document).ready(function() {
    @if(Auth::check())
     getWishlistData();
    @endif
   
  });

  </script>

@stop
@endsection

