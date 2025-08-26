@extends('frontend.mainlayout')

@section('title',$seo_ttl ?: 'Device')
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
      <!----------------------------------  Product Section ------------------------------------->
      <input type="hidden" id="model_name" name="model_name" value="{{$name}}">
      
        <div class="row mb-3">
          <div class="col align-self-center">
            <h5 class="mb-0">{{$name}}</h5>
          </div>
          <div class="col-auto pe-0 align-self-center">
            <a href="{{ route('dashboard')}}" class="link text-color-theme">Shop <i class="bi bi-chevron-right"></i></a>
          </div>
        </div>
        
        <div class="row" id="model_data">
          
        </div>

         <!----------------------------------------------------modal for show product details-------------------------------------------->
         <div class="modal fade" id="viewproductdetails" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-md modal-dialog-centered">
              <div class="modal-content product border-0 shadow-sm">
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



        
       
    </div>
  </main>
</body>


@section('footer-script')
<script type="text/javascript">

  $(document).ready(function(){
    load_model_data();
    
    $('#viewproductdetails').modal({
        backdrop: 'static',
        keyboard: false
    })
  });
//filter
//for filter

$(document).on('click','.view_product_detail',function(){
    $('#btn_share').hide();
    $('#prd_img_data').html("");
    $('#share_urls').html("");
    var id = $(this).data('id');
    $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url : '/getProductDetails',
        type : 'POST',
        data : {
            id : id,
        },
        success:function(data){
            data = JSON.parse(data);
    
          if(data.type == 'success'){
            $('#prd_details').html(data.data.html);  
            if(data.data.urls.length > 0){
                $('#btn_share').show();
                $('#share_urls').html(data.data.urls);    
            }
            
            if(data.img_data.length > 0){
              for(var i=0;i<data.img_data.length;i++) { 
                $('#prd_img_data').append('<div class="swiper-slide text-center mb-0 px-5 py-3"><img src="'+data.img_data[i]+'" alt="" class="mw-100"></div>');
              }
              $('#viewproductdetails').modal('toggle');
            }
          }else{
              
          }
        }
    });
});
  
  $( document ).ajaxComplete(function() {
  var swiper5 = new Swiper(".imageswiper", {
      slidesPerView: "1",
      spaceBetween: 12,
      pagination: {
          el: ".imageswiper-pagination",
      },
  });    
});

var model_id = {{$model_id}};

// add Item button
function addItems(data,cart='',recent=''){
  var method = 'add';
  var qty = 1;
  if(cart == '' && recent == ''){
    add_to_cart_device(data, method, qty);
  }else if(cart != ''){
    add_to_cart_device(data, method, qty,'','cart','');
  }
  else if(recent != ''){
    add_to_cart_device(data, method, qty,'','','recent');
  }
  return false;
}

function plus_minusItems(type, data, cart='',recent=''){
  var dt = JSON.parse(atob(data));
  
  var variant_id = dt.variant_id;
  var total_qty = parseInt(dt.total_qty);
  var cart_qty = parseInt(dt.cart_qty);
  var unit_price = parseInt(dt.unit_price);
  var qty = $('#count_'+variant_id).val();
  if(cart == ''){
    var cart = '';
  }else{
    var cart = 'cart';
  }

  if(recent == ''){
    var recent = '';
  }else{
    var recent = 'recent';
  }

  var stop_labl = 'show';
  if(type == 'plus'){
    var new_qty = (parseInt(qty)+1);
    if(total_qty < new_qty){

      $('#max_'+variant_id).removeAttr('hidden');
      stop_labl = 'hide';
      $('.btn-plus').removeClass('disabled');
    }else{
      //call api for plus
      $('.btn-plus').addClass('disabled');
      var method = type;    //plus
      add_to_cart_device(data, method, new_qty,'hide_loader',cart,recent);
    }
  }
  if(type == 'minus'){
    var new_qty = (qty>1 ? (parseInt(qty)-1) : 0);
    if(new_qty < 0 && new_qty != 0){
      stop_labl = 'hide';
      $('.btn-minus').removeClass('disabled');
    }else{
      //call api for minus
      $('.btn-minus').addClass('disabled');
      var method = type;    //minus
      add_to_cart_device(data, method, new_qty,'hide_loader',cart,recent);
    }
  }
  if(type == 'qtyInput'){
    //console.log('on change');
    var new_qty = qty;
    if(qty > total_qty){
      $('#max_'+variant_id).removeAttr('hidden');
      new_qty = total_qty;
    }else if(qty == null || qty <= 0){
      new_qty = 0;
      stop_labl = 'hide';
    }   
    //call api for qty input
    var method = type;    //qtyInput
    add_to_cart_device(data, method, new_qty,'',cart,recent);      
  }

  if(type == 'remove'){
      new_qty = 0;
      var method = 'remove_cart';    //remove from cart,
      add_to_cart_device(data, method, new_qty,'',cart,recent);  
    }

  if(stop_labl == 'show'){
    //console.log('inside show');
    var new_amount = (parseInt(unit_price) * new_qty).toFixed(2);
    var price_lbl = `&pound;${unit_price} x ${new_qty} = &pound;${new_amount}`;
    $('#count_'+variant_id).val(new_qty);
    $('.price_block_'+variant_id + ' p').html(price_lbl);
  }
}


//----------------------------------functions------------------------------------

function load_model_data(){

  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });
  $.ajax({
    url : '/load_model_data',
    type : 'POST',
    data: {
      id : model_id,
    },
    success:function(data){
      data = JSON.parse(data);
      if(data.type == 'success'){
        $('#model_data').html(data.data.html);  
        $('.countercart').html(data.data.cart_count); 

      }
    }
  }); 
}

function add_to_cart_device(data, method, qty, loader_hide = '',cart='',recent=''){
  if(loader_hide == ''){
    $('.loader-wrap').show();
  }
  var datas = data;
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });
  $.ajax({      
    url : '/add_to_cart',
    async : true, 
    type : 'POST',
    data : {
      data : data,
      method : method,
      qty : qty,
    },      
    success:function(result){
      result = JSON.parse(result);
      //console.log(result);
      if(result.type == 'success'){
        if(cart == '' && recent == ''){
            
          var brand_id = result.data.brand_id;
          var product_id = result.data.product_id;
          var variant_id = result.data.variant_id;                                            
          var cart_count = result.data.cart_count;
          load_model_data();
          //console.log(cart_count);
          $('.countercart').html(cart_count);

          var modal = $('#viewproductdetails').is(':visible');
            if(modal){
                product_view_modal(variant_id);   
            }
        }    
      }
    },
    complete: function(){
      $('.loader-wrap').hide();
    }
  });
}

function add_to_wishlist(data,recent=''){
  var dt = JSON.parse(atob(data));

  var variant_id = dt.variant_id;
  var product_id = parseInt(dt.product_id);
  var brand_id = parseInt(dt.brand_id);
  var in_whishlist = parseInt(dt.in_whishlist);

  $('.loader-wrap').show();
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });
  $.ajax({
    url : '/add_to_wishlist',
    type : 'POST',
    data : {
      data:dt,
    },   
    success:function(){
      load_model_data();    
    },
    complete: function(){
      $('.loader-wrap').hide();
    }
 });
}

function product_view_modal(id){
  $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    $.ajax({
      url : '/getProductDetails',
      type : 'POST',
      data : {
          id : id,
      },
      success:function(data){
          data = JSON.parse(data);

        if(data.type == 'success'){
          $('#prd_details').html(data.data.html);     
        }else{
            
        }
      }
  });
}



  
</script>
@endsection
@endsection