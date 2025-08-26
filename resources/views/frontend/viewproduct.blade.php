@extends('frontend.mainlayout')
@section('title', 'View Product')
@section('body')

<body class="body-scroll" data-page="cart">
  <main class="h-100 has-header has-footer">
    <div class="main-container container">
      <div class="row mb-3">
        <div class="col align-self-center">
          <h5 class="mb-0"></h5>
        </div>
        <div class="col-auto pe-0 align-self-center">
          <a href="{{ route('dashboard')}}" class="link text-color-theme">Go Back <i class="bi bi-chevron-right"></i></a>
        </div>
      </div>
      <div class="row mb-2">
        <div class="card shadow-sm product mb-3">
          <div class="card-body">

            <div class="row mb-2">
              
              <div class="col-6 col-md-6 col-lg-6">                
                <div class="row">
                  <div class="col align-self-center">
                    Left One
                  </div>
                </div>                 
              </div>

              <div class="col-6 col-md-6 col-lg-6">
                <div class="row mb-2">
                  <div class="col align-self-center">
                    TYSOE High Brightness LCD - OLED Samsung Galaxy S22 Ultra BLACK
                  </div>
                </div>   

                <div class="row mb-2">
                  <div class="col align-self-center">
                    <label class="fw-bold">Model</label> : Samsung Galaxy S22 Ultra
                  </div>
                </div>

                <div class="row mb-2">
                  <div class="col align-self-center">
                    <label class="fw-bold">Color</label> : BLACK
                  </div>
                </div>
                
                <div class="row mb-2">
                  <div class="col align-self-center">
                    <label class="fw-bold">Price</label> : £5.00
                  </div>
                </div>
                <div class="row">
                  <div class="col align-self-center">
                    <button class="btn btn-xs btn-default btn-xs btn-theme shadow-sm mb-3 cart_btn">ADD ITEM</button>
                  </div>
                </div>
              </div>
            </div>
      </div>
    </div>
  </main>
</body>
@section('footer-script')
@stop
@endsection