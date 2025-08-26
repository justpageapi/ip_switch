<!-- Required jquery and libraries -->
<script src="/assets/frontend_assets/js/jquery-3.3.1.min.js"></script>
<script src="/assets/frontend_assets/js/popper.min.js"></script>
<script src="/assets/frontend_assets/vendor/bootstrap-5/js/bootstrap.bundle.min.js"></script>

<!-- cookie js -->
<script src="/assets/frontend_assets/js/jquery.cookie.js"></script>

<!-- PWA app service registration and works -->
<!-- <script src="assets/js/pwa-services.js"></script> -->

<!-- cool-share -->
<script src="/assets/cool-share/cool-share/plugin.js"></script>

<!-- swiper script -->
<script src="/assets/frontend_assets/vendor/swiperjs-6.6.2/swiper-bundle.min.js"></script>

<!-- nouislider js -->
<script src="/assets/frontend_assets/vendor/nouislider/nouislider.min.js"></script>

<!-- Customized jquery file  -->
<script src="/assets/frontend_assets/js/main.js"></script>
<script src="/assets/frontend_assets/js/color-scheme.js"></script>

<!-- page level custom script -->
<script src="/assets/frontend_assets/js/app.js"></script>
<script src="{{ URL::asset('plugins/sweet-alert2/sweetalert2.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-V3V6NCZJT7"></script>
<script>
  window.dataLayer = window.dataLayer || [];
     function gtag(){dataLayer.push(arguments);}
     gtag('js', new Date());

     gtag('config', 'G-V3V6NCZJT7');
</script>
@yield('footer-script')