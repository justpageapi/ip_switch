@extends('frontend.mainlayout')
<?php /*?>
 @section('title',$seo_ttl ?: 'Product Detail')
 @section('description',$seo_discription)
 @section('keyword', $seo_keyword):
 <?php */ ?>
@section('body')
<body class="body-scroll" data-page="product">
  <main class="h-100 has-header has-footer">
    <div class="main-container container top-40">
      <div class="row">
        <div class="col-12">
            <div class="card shadow-sm mb-4">
            <div class="card-body pb-0 position-relative">
              <div class="position-absolute top-0 end-0 m-3 z-index-9">
                <button class="btn btn-sm avatar avatar-30 p-0 rounded-circle btn-outline-info me-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                  <i class="bi bi-share"></i>
                </button>
              </div>
              <div class="swiper-container imageswipers">
                <div class="swiper-wrapper">
                  <div class="swiper-slide text-center mb-0">
                    <img src="https://pos.delivermyvape.co.uk/uploads/63d16912ea368.jpg" alt="" class="h-190 mb-2">
                  </div>
                  <div class="swiper-slide text-center mb-0">
                    <img src="https://pos.delivermyvape.co.uk/uploads/63d16912ea368.jpg" alt="" class="h-190 mb-2">
                  </div>
                  <div class="swiper-slide text-center mb-0">
                    <img src="https://pos.delivermyvape.co.uk/uploads/63d16912ea368.jpg" alt="" class="h-190 mb-2">
                  </div>
                </div>
                <div class="swiper-pagination imageswiper-pagination "></div>
              </div>
            </div>
            <div class="collapse" id="collapseExample">
              <div class="card-footer justify-content-center text-center">
                <p class="mb-1 text-opac">Share product with</p>
                <a href="#" class="btn btn-link text-color-theme"><i class="bi bi-twitter"></i></a>
                <a href="#" class="btn btn-link text-color-theme"><i class="bi bi-facebook"></i></a>
                <a href="#" class="btn btn-link text-color-theme"><i class="bi bi-linkedin"></i></a>
                <a href="#" class="btn btn-link text-color-theme"><i class="bi bi-google"></i></a>
              </div>
            </div>
          </div>
            <a href="https://delivermyvape.co.uk/product/Disposable Vapes/Crystal/Crystal - Strawberry Watermelon Bubblegum 2% Or 20mg Nic" class="text-normal">
                <h4 class="text-dark mb-3">Crystal - Strawberry Watermelon Bubblegum 2% Or 20mg Nic</h4>
            </a>
            <div class="row">
                <div class="col-12">
                    <div class="card card-light shadow-sm mb-4">
                      <div class="card-body">
                        <div class="row justify-content-end align-self-center">
                            <div class="col">
                                <div class="m-left-wrap">
                                    <div class="price_box">
                                        <div class="b-tag">Mix &amp; Match</div>
                                        <div class="b-price">1 for £5</div>
                                    </div>
                                    <div class="price_box">
                                        <div class="b-tag">Mix &amp; Match</div>
                                        <div class="b-price">10 for £38</div>
                                    </div>
                                    <div class="price_box">
                                        <div class="b-tag">Mix &amp; Match</div>
                                        <div class="b-price">5 for £20</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto ">
                                <div class="m-right-wrap d-flex" id="cart_btn_dspl_338">
                                    <div class="m-btns">
                                        <button class="btn btn-xs btn-theme shadow-sm mb-0 m-btn cart_btn add_to_cart_btn" onclick="return addToCart('add', '338',1,'home');">ADD</button>
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
      <div class="row mb-3"><div class="col"><h5 class="mb-0">Product details</h5></div></div>
      <div class="row mb-4">
        <div class="col-12">
          <p class="text-opac">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque sollicitudin dignissim nisi, eget malesuada ligula ultricies sit amet. Suspendisse efficitur ex eu est placerat mattis.</p>
          <p class="text-opac">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque sollicitudin dignissim nisi, eget malesuada ligula ultricies sit amet. Suspendisse efficitur ex eu est placerat mattis.</p>
        </div>
      </div>
      <div class="row mb-3"><div class="col"><h5 class="mb-0">Related Items</h5></div></div>
      <div class="swiper-container trendingslides">
        <div class="swiper-wrapper">
            <?php for($i=0;$i<8;$i++){?>
          <div class="swiper-slide">
            <div class="card border border-dark product mb-4 releted-pdr">
              <figure class="text-center mb-0 bg-light-warning">
                <img src="https://pos.delivermyvape.co.uk/uploads/63d16912ea368.jpg" class="w-100" alt="">
              </figure>
              <div class="card-body " style="background: #F8EBEE;">
                <a href="#" class="text-normal text-dark">Crystal - Strawberry Watermelon Bubblegum 2% Or 20mg Nic</a>
              </div>
            </div>
          </div>
          <?php }?>
        </div>
      </div>
    </div>
  </main>
</body>


@section('footer-script')
@stop
@endsection