@extends('frontend.mainlayout')
@section('title',$seo_ttl ?: 'Filter')
@section('description',$seo_discription)
@section('keyword', $seo_keyword)
@section('body')

@php $user_id = null @endphp
@if(Auth::check())
  @php $user_id = Auth::user()->id; @endphp
@endif

<body class="body-scroll" data-page="Model">
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
        
        <div class="row model_name_display_row">
            @if($models->count() > 0)
                @foreach($models as $model)
                    <div class="col col-xs-1 col-sm-1 col-md-3">
                        <div class="card shadow-sm product mb-3">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col align-self-center p-0 text-center">
                                        <b><a href="<?php echo route('Device',['name'=>$model->name]); ?>" class="text-normal d-block">{{$model->name}}</a></b>
                                    </div>
                                </div>
                            </div>                
                        </div>
                    </div>
                 @endforeach
            @endif
        </div>
       
    </div>
  </main>
</body>


@section('footer-script')
<script type="text/javascript">

  $(document).ready(function(){

  });
//filter
//for filter
  



  
</script>
@endsection
@endsection