@extends('frontend.mainlayout')
@section('title', 'Search')

@section('body')
@livewireStyles

<body class="body-scroll" data-page="home">
@livewire('searchpage',['ids' => $id,'type'=>$type])
</body>

@livewireScripts
<!--- modal--->
 <div class="modal fade" id="variant_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title" id="model_name">Modal title</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      	<div id="carouselExampleControls" class="carousel carousel-dark slide" data-bs-ride="carousel">
				  <div class="carousel-inner" id="slider_data">
				    
       		</div>
       		<button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
				    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
				    <span class="visually-hidden">Previous</span>
				  </button>
				  <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
				    <span class="carousel-control-next-icon" aria-hidden="true"></span>
				    <span class="visually-hidden">Next</span>
				  </button>
       	</div>
      </div>
    </div>
  </div> 
</div>

@section('footer-script')
<script type="text/javascript">

	$(document).on('click','.imgmodal',function(){
		$('#slider_data').html('');
		var id = $(this).data('id');
    console.log(id);
		$.ajax({
        url : '{{ route('getvariantimages') }}',
        type : 'POST',
        data: {
            _token : '{{ csrf_token() }}',
            variant_id : id,
        },
        success:function(data){
          data = JSON.parse(data);
          if(data.type == 'success'){
            $('#slider_data').html(data.data); 
            $('#model_name').html(data.name); 
            $('#variant_modal').modal('toggle');          
          }
        }
     });
	});
</script>
@endsection
@endsection

