@include('frontend.part.head')
@section('title',$seo_ttl == null ? 'Message' : $seo_ttl)
@section('description',$seo_discription)
@section('keyword', $seo_keyword)

@include('frontend.part.loader')
@include('frontend.part.sidebar')
@yield('body')
@livewireStyles
@livewire('chat-frontend')

@include('frontend.part.script')
@livewireScripts
<script type="text/javascript">

</script>
