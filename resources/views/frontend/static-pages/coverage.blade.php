@php
  $image = App\Models\SiteSetting::getSitesetting('delivery_coverage','image');
  $description = App\Models\SiteSetting::getSitesetting('delivery_coverage','description');
  $image_path = config('imageurl.product_img_url').$image;  
@endphp

@extends('frontend.mainlayout')
@section('title',$seo_ttl ?: 'Coverage Delivermyvape.co.uk')
@section('description',$seo_discription)
@section('keyword', $seo_keyword)

@section('body')

<body class="body-scroll" data-page="home">
  <main class="h-100 has-header has-footer" >
    <div class="main-container container">
      <div class="row mb-4">
        <div class="card p-3">
        <div class="col-12">
          <h1>Coverage Delivermyvape.co.uk</h1> 
          @if(isset($image) && $image != null)      
            <div class="row mb-4">
              <div class="card bg-white p-3">
                <div class="row m-auto text-center">
                    <div class="col-12 mb-2 mx-auto">
                      <img class="text-center" src="{{$image_path}}">          
                    </div>
                </div>
              </div>
            </div>             
          @endif
          @if(isset($description) && $description != null) 
            {!! base64_decode($description)!!}
          @endif
        </div> 
        </div>
      </div>
      @include('frontend.part.extra-footer')
    </div>    
  </main>
</body>


@section('footer-script')
<script type="text/javascript">

  $(document).ready(function(){ 


  });

</script>

@endsection
@endsection