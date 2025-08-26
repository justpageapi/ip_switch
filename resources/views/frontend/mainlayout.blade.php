<!doctype html>
<html lang="en">
@include('frontend.part.head')
@include('frontend.part.header')

@yield('body')

@if (Auth::user())
<canvas style="position: fixed;top: 0;left: 0;width: 100%;z-index: -1;" id="custom_canvas"></canvas>
@endif

@include('frontend.part.cookie')
@include('frontend.part.loader')
@include('frontend.part.sidebar')
@include('frontend.part.footer')
@include('frontend.part.filter')
@include('frontend.part.script')
@include('frontend.part.agepopup')
@include('frontend.part.geopopup')
@include('frontend.part.namepopup')
@include('frontend.part.emailpopup')
@include('frontend.part.bannerpopup')
@include('frontend.part.discountpopup')
@include('frontend.part.guestpopup')


</html>