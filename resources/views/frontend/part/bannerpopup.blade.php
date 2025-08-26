<div class="modal fade" id="bannerpopup" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <!--<button type="button" class="btn btn-secondary banner_btn px-3" data-dismiss="modal">Close</button>-->
    <div class="modal-content border-0 shadow-sm py-1">

      <div class="modal-body" id="banner_popup">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
          onclick="OpneDiscountBanner();" style="
    position: absolute;
    right: 0;
    background: #fff;
    opacity: 1;
    top: 0;
    padding: 10px 15px;
    font-size: 16px;
    font-weight: bold;
    line-height: 18px;
">X</button>
        @php
        $banner_images = App\Models\BannerImage::getImageByShop();
        @endphp
        @if(isset($banner_images))
        @foreach($banner_images as $image)
        <img src="{{$image->banner_image}}" style="width:100%">
        @endforeach
        @endif
      </div>
    </div>
  </div>
</div>