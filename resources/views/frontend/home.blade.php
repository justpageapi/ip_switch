@extends('frontend.mainlayout')
@section('title',$seo_ttl ?: 'Home')
@section('description',$seo_discription)
@section('keyword', $seo_keyword)

@section('body')

@php $user_id = null @endphp
@if(Auth::check())
  @php $user_id = Auth::user()->id; @endphp
@endif


<body class="body-scroll" data-page="home">
  <main class="h-100 has-header has-footer" >
    <div class="main-container container">
        <div class="row mb-4">
          <div class="col-12">
            <div class="form-floating">
              <input type="text" class="form-control is-valid typeahead" id="search" placeholder="Search">
              <label for="search">Search</label>
              <button type="button" class="btn btn-link tooltip-btn d-block text-color-theme">
                <i class="bi bi-search"></i>
              </button>
            </div>
          </div>
          <div class="col-12">
              <div id="result">
              </div>
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
    <!-----------------------------------------------------------------Product display-------------------------------------->
      <div class="row home-pdr-box" id="prd_data">

      </div>

      @include('frontend.part.extra-footer')

    </div>
  </main>
</body>


@section('footer-script')
<script type="text/javascript">


@if (count($errors) > 0)
  @foreach ($errors->all() as $error)
    toastr.error("{{ $error }}");
  @endforeach
@endif

@if (\Session::has('success'))
  toastr.success("{!! \Session::get('success') !!}");
@endif


  $(document).ready(function(){

    $('#viewproductdetails').modal({
      backdrop: 'static',
      keyboard: false
    })

    var data = {};
    data["type"] = 'category';
    getHomeData(data);

  });

  $(document).on('keyup blur','#search',function(){
    var data = $(this).val();
    if(!data){
      $("#result").html("");
    }
  });


</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.1/bootstrap3-typeahead.min.js"></script>
<script>
 var path = "{{ route('searchSuggetion') }}";
    $('input.typeahead').typeahead({
      delay:1000,
      source: function (search_data) {
        if(search_data != null){
          return $.post(path, { search_data : search_data }, function (data) {
            data = JSON.parse(data);
            $("#result").html(data.html);

          });
        }else{
          $("#result").html("");
        }
      }
    });
</script>
@endsection
@endsection