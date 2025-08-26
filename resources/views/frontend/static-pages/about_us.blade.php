@extends('frontend.mainlayout')
@section('title',$seo_ttl ?: 'About Delivermyvape.co.uk')
@section('description',$seo_discription)
@section('keyword', $seo_keyword)

@section('body')

<body class="body-scroll" data-page="home">
  <main class="h-100 has-header has-footer">
    <div class="main-container container">
      <div class="row mb-4">
        <div class="card p-3">
          <div class="col-12">
            <h1>About Delivermyvape.co.uk</h1>
            <p>
              Delivermyvape.co.uk was born out of a desire to provide an informative and efficient online shopping
              experience for smokers looking to make the switch to vaping. We understand that vaping may seem like a
              confusing world of acronyms and geeky toys; since day one we made it our mission to help educate consumers
              with simple to digest information and a well curated range of products which we know you can trust.
            </p>
            <p>
              At Delivermyvape.co.uk we pride ourselves on having not only the largest online range in the UK, but also
              the best curated choice of vape products. From the most basic starter kits to the most complicated mods,
              from the simplest single flavour eliquids to the most complex blends of premium juice - and everything in
              between! Every product we stock goes through a strict due diligence process to ensure it is of the highest
              quality and safety. We don't stock products we wouldn't be happy to use ourselves.
            </p>
            <p>
              Vaping is all about choice. When a smoker makes the decision to switch to vaping they are choosing a far
              less harmful alternative to smoking. It is not always an easy transition to make and choosing the product
              that will work best for you can be mind boggling. There are literally hundreds of different types of
              vaping device on the market, and thousands of e-liquid flavours - and what one person loves doesn't
              necessarily work for the next.
            </p>
            <p>
              And this is where Delivermyvape.co.uk is different from other retailers - we're not just an online shop,
              we're here to help. We spend a lot of time creating videos, blog articles and guides which we hope will
              give both smokers and vapers a huge array of information and education about vaping itself and the many
              different types of products available. All designed to help you find the right vape for you.
            </p>
            <p class="font-size-20">
              DELIVERMYVAPE DOT CO DOT UK LIMITED (14943684) T/A DeliverMyVape.co.uk, <br>
              4 Potter St,<br>
              Bishop's Stortford, <br>
              England, CM23 3UL
            </p>

          </div>
        </div>
      </div>
      @include('frontend.part.extra-footer')
    </div>
  </main>
</body>


@section('footer-script')
<script type="text/javascript">
  $(document).ready(function(){


  });

</script>

@endsection
@endsection