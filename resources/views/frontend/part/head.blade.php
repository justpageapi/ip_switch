<head>
  <meta charset="utf-8">
  <title>@yield('title')</title>
  <!--<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">-->
  <meta name="description" content="@yield('description')">
  <meta name="keyword" content="@yield('keyword')">
  <meta name="author" content="">
  <meta name="generator" content="">
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <meta name="google-site-verification" content="6H7Gpyd_7zOVVmoKoXjY0z3zy6EZKPXLmx1QRSkUo3M" />

  @yield('custom-meta')

  <!-- Google tag (gtag.js) -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=G-R40P4VFJRV"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-R40P4VFJRV');
  </script>

  <!-- manifest meta -->
  <!--<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, viewport-fit=cover, shrink-to-fit=no">-->
  <!--<meta name = "viewport" content = "width=device-width, minimum-scale=1.0, maximum-scale = 1.0, user-scalable = no">-->
  <!--<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">-->
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />

  <!-- <link rel="manifest" href="./manifest.json" />
 -->
  <!-- Favicons -->
  <link rel="apple-touch-icon" href="/assets/frontend_assets/img/favicon180.png" sizes="180x180">
  <link rel="icon" href="/assets/frontend_assets/img/favicon32.png" sizes="32x32" type="image/png">
  <link rel="icon" href="/assets/frontend_assets/img/favicon16.png" sizes="16x16" type="image/png">

  <!-- Google fonts-->
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700&amp;display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=PT+Sans:ital,wght@0,400;0,700;1,400&amp;display=swap"
    rel="stylesheet">

  <!-- bootstrap icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

  <!-- nouislider CSS -->
  <link href="/assets/frontend_assets/vendor/nouislider/nouislider.min.css" rel="stylesheet">

  <!-- swiper css -->
  <link rel="stylesheet" href="/assets/frontend_assets/vendor/swiperjs-6.6.2/swiper-bundle.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

  <!--- cool-share button--->
  <link href="/assets/cool-share/cool-share/plugin.css" media="all" rel="stylesheet" />

  <!-- style css for this template -->
  <link href="/assets/frontend_assets/css/style.css" rel="stylesheet" id="style">
  <link href="{{ URL::asset('plugins/sweet-alert2/sweetalert2.min.css')}}" rel="stylesheet" type="text/css">

  <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" rel="stylesheet" type="text/css">

  <script src="https://cdn.jsdelivr.net/npm/js-confetti@latest/dist/js-confetti.browser.js"></script>
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA_9JzMJMc4AAY4Aj07sFgpu4e65NlEEvo&libraries=places">
  </script>
  <style>
    /* Chrome, Safari, Edge, Opera */
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
      -webkit-appearance: none;
      margin: 0;
    }

    /* Firefox */
    input[type=number] {
      -moz-appearance: textfield;
    }
  </style>


  @yield('style-head')
</head>