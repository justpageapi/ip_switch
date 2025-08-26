@extends('frontend.mainlayout')
@section('title',$seo_ttl ?: 'Address')
@section('description',$seo_discription)
@section('keyword', $seo_keyword)
@section('body')
<body class="body-scroll" data-page="blank">    
    <!-- Begin page -->
   <main class="h-100 has-header has-footer">
      <div class="main-container container">

    <!------------------------ shipping address ---------------------------->
    <div class="row mb-3">
        <div class="col align-self-center">
            <h5 class="mb-0">Shipping Address</h5>
        </div>
        <div class="col-auto pe-0 align-self-center">
          <a id="open_add_modal" class="link text-color-theme">Add<i class="bi bi-chevron-right"></i></a>
        </div>
    </div>
    <div class="row mb-2">
    @foreach($cus_address->where('type','shipping') as $address)
        <div class="col-12 col-md-6">
            <div class="card shadow-sm product mb-3">
                <div class="card-header">
                    <div class="row">
                      <div class="col-auto align-self-center">
                            <div class="form-check">
                              <input class="form-check-input" data-id="{{$address->id}}" type="radio" name="shipping_address" id="shipping_address" {{$address->is_primary == 1 ? 'checked' : '' }}>
                            </div>
                        </div>
                        <div class="col align-self-center ps-0">
                            <h5 class="mb-0">{{$address->city}}<br>
                                @if($address->is_primary == 1)
                                  <span class="text-opac small">Primary</span>
                                @else
                                  <span class="text-opac small">Secondary</span>
                                @endif
                            </h5>
                        </div>
                        <div class="col-auto align-self-center">
                          <a id="edit" data-id="{{$address->id}}" class="btn btn-link text-color-theme"><i class="bi bi-pencil "></i></a>
                          <a id="delete" data-id="{{$address->id}}" data-type="shipping" class="btn btn-link text-color-theme"><i class="bi bi-trash "></i></a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            {{$address->address_line_1}} , {{$address->address_line_2}}<br>{{$address->state}} , {{$address->city}} ,{{$address->postal_code}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    </div>

    @if($cus_address->where('type','shipping')->count() <= 0)
    <div class="row mb-3">
      <div class="col align-self-center">
        Please Add Shipping Address
      </div>
    </div>
    @endif


    <!-- --------------------------- billing address------------------------------ -->
    <div class="row mb-3">
      <div class="col align-self-center">
          <h5 class="mb-0">Billing Address</h5>
      </div>
      <div class="col-auto pe-0 align-self-center">
        <a id="open_bill_address_modal" class="link text-color-theme">Add<i class="bi bi-chevron-right"></i></a>
      </div>
    </div>
    <div class="row mb-2">
     @foreach($cus_address->where('type','billing') as $address)
          <div class="col-12 col-md-6">
              <div class="card shadow-sm product mb-3">
                  <div class="card-header">
                      <div class="row">
                        <div class="col-auto align-self-center">
                              <div class="form-check">                                
                                  <input class="form-check-input" data-id="{{$address->id}}" type="radio" name="billing_address" id="billing_address" {{$address->is_primary == 1 ? 'checked' : '' }}>                                  
                              </div>
                          </div>
                          <div class="col align-self-center ps-0">
                              <h5 class="mb-0">{{$address->city}}<br>
                                @if($address->is_primary == 1)
                                  <span class="text-opac small">Primary</span>
                                @else
                                  <span class="text-opac small">Secondary</span>
                                @endif
                              </h5>
                          </div>
                          <div class="col-auto align-self-center">
                              <a id="edit" data-id="{{$address->id}}" class="btn btn-link text-color-theme"><i class="bi bi-pencil"></i></a>
                              <a id="delete" data-type="billing" data-id="{{$address->id}}" class="btn btn-link text-color-theme"><i class="bi bi-trash "></i></a>
                          </div>
                      </div>
                  </div>
                  <div class="card-body">
                      <div class="row">
                          <div class="col">
                              {{$address->address_line_1}} , {{$address->address_line_2}}<br>{{$address->state}} , {{$address->city}} ,{{$address->postal_code}}
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      @endforeach
      </div>

      @if($cus_address->where('type','billing')->count() <= 0)
      <div class="row mb-3">
        <div class="col align-self-center">
          Please Add Billing Address
        </div>
      </div>
      @endif

    <!-------------------------------------pricing ----------------------------->

            <div class="row mb-3">
                <div class="col align-self-center">
                    <h5 class="mb-0">Pricing</h5>
                </div>
            </div>

             <div class="row mb-3">
                <div class="col">
                    <p>Subtotal</p>
                </div>
                <div class="col-auto">{!! env('currency') !!}{{ $subttl }}</div>
            </div>
            
            <div class="row mb-3">
                <div class="col">
                    <p>Shipping Cost</p>
                </div>
                <div class="col-auto">{!! env('currency') !!}{{ $shippingcost }}</div>
            </div>

           

            <div class="row fw-bold mb-4">
                <div class="mb-3 col-12">
                    <div class="dashed-line"></div>
                </div>
                <div class="col">
                    <p>Total</p>
                </div>
                <div class="col-auto">{!! env('currency') !!}{{ $ttl }}</div>
            </div>

            <!-- Button -->
            <div class="row mb-3">
                <div class="col align-self-center d-grid">
                    <a id="make_payment" class="btn btn-default btn-theme btn-lg shadow-sm">Payment</a>
                </div>
            </div>

        </div>
        <!-- main page content ends -->
      </div>
    </main>
    <!-- Page ends-->
</body>

<!-- Modal -->
<div class="modal fade" id="add_address_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="title">Add Address</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="main-container container">
            <div class="row">
              <div class="col-12 mx-auto">
                <div class="card card-light shadow-sm mb-4">
                  <div class="card-body">
                    <form id="my_form">
                      @csrf
                        <input type="hidden" name="type" id="type" value="">
                        <input type="hidden" name="id" id="id" value="">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="country" placeholder="Country" name="country" required>
                            <label for="country">Country</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="postalcode" placeholder="Pincode" name="postalcode" required>
                            <label for="username">Pincode</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="state" name="state" placeholder="State" required>
                            <label for="state">State</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="city" placeholder="City" name="city" required>
                            <label for="city">City</label>
                        </div>
                        <div class="form-floating mb-3">
                            <textarea type="text" class="form-control" id="address_line_1" placeholder="Address line 1" name="address_line_1" required></textarea>
                            <label for="address_line_1">Address line 1</label>
                        </div>
                        <div class="form-floating mb-3">
                            <textarea type="text" class="form-control" id="address_line_2" placeholder="Address line 2" name="address_line_2"></textarea>
                            <label for="address_line_2">Address line 2</label>
                        </div>
                        <div class="form-floating mb-3" id="both_same_check">
                          <div class="row">
                            <div class="col-12">
                              <input type="checkbox" id="check" name="both_are_same"> Billing Address Same as Shipping Address
                            </div>                                                          
                          </div>                                
                        </div>
                        <div class="row mb-2">
                          <button type="button" class="btn btn-default btn-theme shadow-sm" data-bs-dismiss="modal">Close</button>
                        </div>
                        <div class="row mb-2">
                          <button id="btn_submit" type="button" class="btn btn-default btn-theme shadow-sm">Save changes</button>
                        </div>
                        <div class="row mb-2">
                          <button id="btn_update" type="button" class="btn btn-default btn-theme shadow-sm" >Update changes</button>
                        </div>
                    </form>
                  </div>
                </div>
              </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</form>
</div>

@section('footer-script')
<script type="text/javascript">
  $(document).on('click','#open_add_modal',function(){
            clear_data();

    $('#title').html('Add Shipping Address');
    $('#type').attr('value','shipping');
    $('#btn_update').css('display','none');
    $('#btn_submit').css('display','block');
    $('#both_same_check').css('display','block');
    $('#add_address_modal').modal('toggle');
  });

  $(document).on('click','#open_bill_address_modal',function(){
        clear_data();
    $('#title').html('Add Billing Address');
    $('#type').val('billing');
    $('#btn_update').css('display','none');
    $('#both_same_check').css('display','none');
    $('#btn_submit').css('display','block');
    $('#add_address_modal').modal('toggle');
  });

  $(document).on('click','#btn_submit',function(){
    var form = $('#my_form').serialize();
    $.ajax({
      headers: {
          "X-CSRF-Token":'{{ csrf_token() }}'
        },
      url : '{{ route('add-address') }}',
      type : 'POST',
      data: $('#my_form').serialize(),
      success:function(data){
        data = JSON.parse(data);
        if(data.type == 'success'){
          clear_data();
          location.reload();
        }
        if(data.type == 'error'){
          var Toast = Swal.mixin({
              toast: true,
              position: 'top-end',
              showConfirmButton: false,
              timer: 2000,
              timerProgressBar: true,
            });

            Toast.fire({
              icon: 'error',
              title: data.msg,
            });
        }
      },
    });
  });

  $(document).on('click','#edit',function(){
    $('#both_same_check').css('display','none');
    var id = $(this).data('id');
    console.log(id); 
      $.ajax({
      url : '{{ route('edit-address') }}',
      type : 'POST',
      data: {
          _token : '{{ csrf_token() }}',
          id : id,
      },
      success:function(data){
        data = JSON.parse(data);
        if(data.type == 'success'){
          $('#type').val(data.data.type);
          if(data.data.type == 'shipping'){
            $('#title').html('Edit Shipping Address');
          }
          else{
            $('#title').html('Edit Billing Address');
          }
          $('#id').val(data.data.id);
          $('#postalcode').val(data.data.postal_code);
          $('#city').val(data.data.city);
          $('#state').val(data.data.state);
          $('#country').val(data.data.country);
          $('#address_line_1').val(data.data.address_line_1);
          $('#address_line_2').val(data.data.address_line_2);
          $('#btn_update').css('display','block');
          $('#btn_submit').css('display','none');
          $('#add_address_modal').modal('toggle');
        }
      }
    });
  });

  function clear_data(){
    $('#id').val('');
    $('#type').val('');
    $('#postalcode').val('');
    $('#city').val('');
    $('#state').val('');
    $('#country').val('');
    $('#address_line_1').val('');
    $('#address_line_2').val('');
  }

  $(document).on('click','#btn_update',function(){
    var form = $('#my_form').serialize();
    $.ajax({
      headers: {
          "X-CSRF-Token":'{{ csrf_token() }}'
        },
      url : '{{ route('update-address') }}',
      type : 'POST',
      data: $('#my_form').serialize(),
      success:function(data){
        data = JSON.parse(data);
        if(data.type == 'success'){
          clear_data();
          location.reload();
        }
        if(data.type == 'error'){
          var Toast = Swal.mixin({
              toast: true,
              position: 'top-end',
              showConfirmButton: false,
              timer: 2000,
              timerProgressBar: true,
            });

            Toast.fire({
              icon: 'error',
              title: data.msg,
            });
        }
      },
    });
  });

  $(document).on('click','#make_payment',function(){
    var shipping_id = $('#shipping_address:checked').data('id');
    var billing_id = $('#billing_address:checked').data('id');

    if(shipping_id && billing_id){
      $.ajax({      
        url : '{{ route('create_order') }}',
        type : 'POST',
        data: {
          '_token' : '{{ csrf_token() }}',
          'shipping_id':shipping_id,
          'billing_id':billing_id
        },
        success:function(data){
          data = JSON.parse(data);
          if(data.type == 'success'){
            var Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true,
              });

              Toast.fire({
                icon: 'success',
                title: data.msg,
              });
              clear_data();
              window.location.href = "{{ url('order-success')}}";
          }
          if(data.type == 'error'){
            var Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true,
              });

              Toast.fire({
                icon: 'error',
                title: data.msg,
              });
          }
        },
      });
    }
    else{
      var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 2000,
        timerProgressBar: true,
      });

      Toast.fire({
        icon: 'error',
        title: 'please select the address',
      });
    }
  });

  $(document).on('click','#delete',function(){
    var id = $(this).data('id');
    var type = $(this).data('type');
    if(id && type){
    console.log(id); 
      $.ajax({
      url : '{{ route('delete-address') }}',
      type : 'POST',
      data: {
          _token : '{{ csrf_token() }}',
          id : id,
          type : type,
      },
      success:function(data){
        data = JSON.parse(data);
        if(data.type == 'success'){
           var Toast = Swal.mixin({
              toast: true,
              position: 'top-end',
              showConfirmButton: false,
              timer: 2000,
              timerProgressBar: true,
            });

            Toast.fire({
              icon: 'success',
              title: data.msg,
            });

            location.reload();
        }
        else{
           var Toast = Swal.mixin({
              toast: true,
              position: 'top-end',
              showConfirmButton: false,
              timer: 2000,
              timerProgressBar: true,
            });

            Toast.fire({
              icon: 'error',
              title: data.msg,
            });
        }
      }
    });
  }
  });

</script>
@stop
@endsection

