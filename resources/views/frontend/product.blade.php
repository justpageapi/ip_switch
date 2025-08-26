<?php

$id = $product->id;
$name = $product->name;
$price = $product->productpricebyshop; 
$image = $product->imagepath;
$discription = $product->long_discription;
$category_name = $product->category->name;
$brand_name = $product->brand->name;
$group_id = $product->group_id;
$images = $product->allimage;

$slug_url = '#';
$seo = App\Models\Seo::getSeoData('product',$id);
$seo_ttl = $seo['seo_title'];
$seo_keyword = $seo['seo_keyword'];
$seo_discription = $seo['seo_discription'];
$slug_url = $seo['seo_slug_url'];



?>
@extends('frontend.mainlayout')

@section('title',$seo_ttl ?: 'Product Detail')
@section('description',$seo_discription)
@section('keyword', $seo_keyword)

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
                  @foreach($images as $img)
                    <div class="swiper-slide text-center mb-0">
                      <img src="{{$img}}" alt="" class="h-190 mb-2">
                    </div>                    
                  @endforeach
                </div>
                <div class="swiper-pagination imageswiper-pagination "></div>
              </div>
            </div>
            <div class="collapse" id="collapseExample">
              <div class="card-footer justify-content-center text-center">
                <p class="mb-1 text-opac">Share product with</p>
                <a target="_blank" href="<?php echo config('socialurls.twitter_url').env('APP_URL').'/products/'.$slug_url; ?>" class="btn btn-link text-color-theme"><i class="bi bi-twitter"></i></a>
                <a target="_blank" href="<?php echo config('socialurls.facebook_url').env('APP_URL').'/products/'.$slug_url; ?>" class="btn btn-link text-color-theme"><i class="bi bi-facebook"></i></a>
                <a target="_blank" href="<?php echo config('socialurls.linkedin_url').env('APP_URL').'/products/'.$slug_url; ?>" class="btn btn-link text-color-theme"><i class="bi bi-linkedin"></i></a>
                <a href="#" class="btn btn-link text-color-theme"><i class="bi bi-google"></i></a>
              </div>
            </div>
          </div>
            <a href="<?php echo url('products/'.$slug_url); ?>" class="text-normal">
                <h4 class="text-dark mb-3">{{$name}}</h4>
            </a>
            <div class="row">
                <div class="col-12">
                    <div class="card card-light shadow-sm mb-4">
                      <div class="card-body">
                        <div class="row justify-content-end align-self-center">
                            <div class="col">
                                <div class="m-left-wrap">
                                  <?php echo base64_decode(App\Http\Controllers\HomePageController::getOffers($group_id,$price)); ?>   
                                </div>
                            </div>
                            <div class="col-auto ">
                                <div class="m-right-wrap d-flex" id="cart_btn_dspl_<?php echo $id; ?>">
                                    <div class="m-btns">
                                        <?php echo base64_decode(App\Http\Controllers\HomePageController::getHtmlCartButton($id,'home')); ?>
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

      @if(isset($discription) && $discription != null )
        <div class="row mb-3"><div class="col"><h5 class="mb-0">Product details</h5></div></div>
        <div class="row mb-4">
          <div class="col-12">
            <p class="text-opac">{{$discription}}</p>
          </div>
        </div>
      @endif

      <div class="row mb-3"><div class="col"><h5 class="mb-0">Related Items</h5></div></div>
      <div class="swiper-container trendingslides">
        <div class="swiper-wrapper">
          @if(isset($relative_product) && $relative_product != null)
            @foreach($relative_product as $products)
              @php
                $slug_url = '#';
                $seo = App\Models\Seo::getSeoData('product',$products->id);
                $slug_url = $seo['seo_slug_url'];    
              @endphp

            <div class="swiper-slide">
              <div class="card border border-dark product mb-4 releted-pdr">
                <figure class="text-center mb-0 bg-light-warning">
                  <img src="{{$products->imagepath}}" class="w-100" alt="">
                </figure>
                <div class="card-body " style="background: #F8EBEE;">
                  <a href="<?php echo url('products/'.$slug_url); ?>" class="text-normal text-dark">{{$products->name}}</a>
                </div>
              </div>
            </div>

            @endforeach
          @endif
        </div>
      </div>
      @include('frontend.part.extra-footer')
    </div>
  </main>
</body>


@section('footer-script')
@stop
@endsection