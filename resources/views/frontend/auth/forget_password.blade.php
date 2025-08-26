@extends('frontend.auth.authlayout')

@section('title',$seo_ttl ?: 'Forget Password')
@section('description',$seo_discription)
@section('keyword', $seo_keyword)

@section('custom-meta')
@stop

@section('style-head')
    <style>
        /* style inputs and link buttons */
        input,
        .btn {
            width: 100%;
            padding: 20px 10px;
            border: none;
            border-radius: 10px;
            margin: 5px 0;
            opacity: 1;
            display: inline-block;
            font-size: 17px;
            line-height: 20px;
            text-decoration: none;
            /* remove underline from anchors */
            text-align: left;
        }

        .btn:hover {
            opacity: 1;
            color: white;
        }

        /* add appropriate colors to fb, twitter and google buttons */
        .fb {
            background-color: #3B5998;
            color: white;
        }

        .twitter {
            background-color: #55ACEE;
            color: white;
        }

        .google {
            background-color: #C94130;
            color: white;
            font-weight: bold;
            letter-spacing: 0.5px;
            text-transform: capitalize;
            font-size: 18px;
        }
    </style>
@stop
    @section('body')

    <body class="body-scroll" data-page="signin">

        <!-- Begin page content -->
        <main class="container-fluid main-container">
            <div class="row h-100">
                <div class="col-12 text-center mb-5">
                    <div class="logo-small">
                        <a href="{{route('dashboard')}}" ><img src="/assets/frontend_assets/img/logo.png" alt="" class="img"></a>
                    </div>
                </div>
                <div class="col-12 mx-auto text-center">
                    <div class="row h-100">
                        <div class="col-10 col-sm-8 col-md-6 col-lg-4 col-xl-3 mx-auto align-self-center">
                            <div class="card card-custom shadow-sm mb-4">
                                <div class="card-body">
                                  
                                      <div class="logo-md mb-2">
                                        <img src="assets/forget_img.png" alt="" class="img"><br>
                                    </div>

                                    <form action="{{route('forgetpassword')}}" method="post" class="was-validated">
                                      @csrf

                                        <div class="form-floating mb-3">
                                          <input type="text" class="form-control @error('email') is-invalid @enderror"  id="email" name="email"
                                            placeholder="name@example.com" value="{{ old('email') }}"/>
                                          <label for="email">Email</label>
                                          @error('email')
                                            <div class="invalid-feedback text-dark">{{$errors->first('email')}}</div>
                                          @enderror
                                        </div>

                                        <div class="align-self-center d-grid">
                                          <button type="submit" class="btn btn-default btn-theme shadow-sm text-center">
                                            Forget Password
                                          </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>        
    </body>    
    @section('footer-script')
        <script type="text/javascript">
          @error('login')
            toastr.error("{{$errors->first('login')}}");      
          @endif
        </script>
    @stop
@endsection
