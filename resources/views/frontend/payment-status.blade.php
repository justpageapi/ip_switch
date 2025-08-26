@extends('frontend.mainlayout')

@section('title', 'Payment ' . $type)
@section('description', 'Order ' . $type)
@section('keyword', 'Order ' . $type)
<?php
if($type == 'success'){
  App\Helpers\SessionHelper::clearSessionData();
}
?>
@section('body')
    <!-- loader section ends -->

    <body class="body-scroll d-flex flex-column" data-page="landing">

        <!-- Begin page content -->
        <main class="h-100 has-header has-footer">
            <div class="main-container container mt-4">
                <div class="row h-100">
                    <div class="col-12 mx-auto text-center">
                        <div class="row h-100">
                            <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4 mx-auto align-self-center">
                                <div class="card card-custom shadow-sm mb-4">
                                    <div class="card-body">
                                        <div class="row h-100">
                                            <div class="col-12 mx-auto text-center mb-4">
                                                <div class="row h-100">
                                                  <div class="col-10 align-self-center mx-auto text-center">
                                                    <img src="/assets/frontend_assets/img/logo.png" alt="Logo" class="mb-4" style="width: 150px;">
                                                    <h2 class="text-center">Payment {{ ucwords($type) }}</h2>
                                                    <?php if($type == 'success'){?><p class=" mb-4">Your order completed, please check in your mailbox. your order processing as soon as possible.</p><?php }?>
                                                    <?php if($type != 'success'){?><p class=" mb-4">Your transaction cannot be completed, please try again thanks.</p><?php }?>
                                                  </div>
                                                </div>
                                            </div>
                                            <div class="col-12 text-center align-self-end py-2">
                                              <div class="row">
                                                <div class="col text-center">
                                                  <?php if($type == 'success'){?>
                                                  <a href="{{ route('dashboard') }}" class="btn btn-default btn-theme shadow-sm"><i class="fa fa-home"></i> Home</a>
                                                </div>
                                                <?php }?>
                                                <?php if($type != 'success'){?>
                                                <div class="col text-center">
                                                  <a href="{{ route('payment-complete') }}" class="btn btn-default btn-theme shadow-sm"><i class="fa fa-rotate-right"></i> Try Again</a>
                                                </div>
                                                <?php }?>
                                              </div>
                                            </div>
                                        </div>
                                    </div>
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
