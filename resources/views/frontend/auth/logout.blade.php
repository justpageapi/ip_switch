@extends('frontend.auth.authlayout')

@section('title', 'Login')
@section('description','discription')
@section('keyword', 'seo_keyword')


@section('custom-meta')

<meta name="appleid-signin-client-id" content="[CLIENT_ID]">
  <meta name="appleid-signin-scope" content="[SCOPES]">
  <meta name="appleid-signin-redirect-uri" content="[REDIRECT_URI]">
  <meta name="appleid-signin-state" content="[STATE]">

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
  text-decoration: none; /* remove underline from anchors */
  text-align: left;
}

input:hover,
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
<body class="body-scroll d-flex flex-column h-100 dark-bg bg1" data-page="landing">
  <main class="container-fluid h-100 main-container">
    <div class="row h-100">
      <div class="col-12 mx-auto text-center">
        <div class="row h-100">
          <div class="col-10 col-sm-8 col-md-6 col-lg-4 col-xl-3 mx-auto align-self-center">
            <!-- <h2 class="text-center mb-4">Sign in</h2> -->
            <div class="card card-light shadow-sm mb-4">
              <div class="card-body">
                {{--<div class="d-grid">
                  <a href="{{ route('auth/google')}}" class="google btn"><i class="fa fa-google fa-fw">
                    </i> Sign with Google+
                  </a>
                  <hr/>
                  <div id="appleid-signin" class="signin-button" data-color="white" data-border="false" data-type="sign in"></div>
                  <script type="text/javascript" src="https://appleid.cdn-apple.com/appleauth/static/jsapi/appleid/1/en_US/appleid.auth.js"></script>
                </div> --}}
                
                You are logout
                
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
  @section('footer-script')
    <script type="text/javascript">
      
    </script>
  @stop
</body>
@endsection
</html>