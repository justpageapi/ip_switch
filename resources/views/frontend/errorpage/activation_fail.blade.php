@extends('frontend.mainlayout')
@section('title','Activation')
@section('description','Activation')
@section('keyword', 'Activation')
@section('body')

<body class="body-scroll" data-page="">

    <!-- Begin page -->
    <main class="h-100 has-header">

        <!-- Header -->
        <header class="container-fluid header">
            <div class="row h-100">
                <div class="col-auto align-self-center">
                    <a href="profile.html" class="btn btn-link back-btn text-color-theme">
                        <i class="bi bi-arrow-left size-20"></i>
                    </a>
                </div>
                <div class="col text-center align-self-center">
                    <h5 class="mb-0">Something Wrong In Activation</h5>
                </div>
                <div class="col-auto align-self-center">
                    <a href="home.html" class="link text-color-theme">
                        <i class="bi bi-shop size-22"></i>
                    </a>
                </div>
            </div>
        </header>
        <!-- Header ends -->

        <!-- main page content -->
        <div class="main-container h-100 container">
            <div class="row h-100 ">
                <div class="col-12 col-md-6 col-lg-5 col-xl-3 mx-auto pt-4 text-center d-grid gap-2 align-self-center">
                    <figure class="mw-100 text-center mb-0">
                        <img src="assets/img/404.png" alt="" class="mw-100">
                    </figure>
                    <h1 class="mb-0 fw-bold text-color-theme">Oops!...</h1>
                    <h3 class="mb-3">Something Wrong In Activation</h3>
                    <p class="text-opac mb-4">The page you are looking for is not available. Please go to home page.</p>
    
                </div>
            </div>
        </div>
    </main>
</body>
@section('footer-script')
<script type="text/javascript">

</script>
@stop
@endsection