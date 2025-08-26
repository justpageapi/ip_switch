@php
  $msg = App\Models\Setting::getSettingByType('min_order_limit');      
  $msg = $msg[0];  
@endphp
@extends('frontend.mainlayout')
@section('title',$seo_ttl ?: 'Delivermyvape.co.uk Delivery Information')
@section('description',$seo_discription)
@section('keyword', $seo_keyword)

@section('body')

<body class="body-scroll" data-page="home">
  <main class="h-100 has-header has-footer" >
    <div class="main-container container">
      <div class="row mb-4">
        <div class="card p-3">
          <div class="col-12">
            <h1 class="mb-3">Delivermyvape.co.uk Delivery Information</h1>
            @if(isset($msg))
              <p>{{$msg->key}}</p>
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