@extends('frontend.mainlayout')
@section('title',$seo_ttl ?: 'Delivermyvape.co.uk Customer Age Verification')
@section('description',$seo_discription)
@section('keyword', $seo_keyword)

@section('body')

<body class="body-scroll" data-page="home">
  <main class="h-100 has-header has-footer" >
    <div class="main-container container">
      <div class="row mb-4">
        <div class="card p-3">
          <div class="col-12">
            <h1 class="mb-3">Delivermyvape.co.uk Customer Age Verification</h1>
            <h4 class="text-danger">Why Do We Need To Verify Your Age</h4>          
              <p>
                Simply put, because it's the law! Since 2015 it has been illegal to sell vape products to anybody under the age of 18, so we must ensure that all customers are age checked. In store this is easy to handle as the staff can apply the same challenge 25 procedures that everybody is used to with tobacco and alcohol, but when you shop online we can't see your face so we have to conduct a digital age verification on every new customer.
              </p>

            <h4 class="text-danger">How Does Digital Age Verification Work?</h4>          
              <p>
                When you make your first order on Delivermyvape.co.uk, we will check your details (name, date of birth and billing address) using a third party digital age verification provider Experian Ltd. This method simply tries to match your details against official records such as the electoral register or credit records, which will confirm your age.
              </p>
              <p>
                Once we have verified your age we will flag your member account as Age Verified and you will not have to go through the process again. The only exception is when you change any of your personal details, where we will conduct another check to ensure the security of your account.              
              </p>
              <p>
                Around 85% of customers can be age verified seamlessly in the background, and it requires no input from you at all. However there are a few occasions where it is not possible to digitally verify your age, even though you may be over 18.
              </p>
              <p>
                For example, if you have recently moved house and you are not yet on the electoral register or you have not updated your new address with credit providers. If you have recently married or changed your name it may take some time for records to update. Sometimes it may even be as simple as you not entering your full name e.g. you have typed Ben rather than Benjamin.
              </p>

            <h4 class="text-danger">What Happens If My Age Can't Be Verified Automatically?</h4>          
              <p>
                If we are unable to verify you automatically we do have a backup option for you. We will ask you to show us your official ID so we can verify your are over 18, in the same way you may be asked to show ID at a supermarket or bar.
              </p>
              <p>
                To complete this online, we will send you a link which will take you to a secure site where you will be asked to take a photo of your ID and a selfie - so we can be sure it is your ID and not somebody else's.
              </p>
              <p>
                The process takes less than a minute, and can be completed using any smartphone, tablet or laptop that has a camera.
              </p>
              
            <h4 class="text-danger">Why is Age Verification Important?</h4>          
              <p>
                Vaping is not intended for non-smokers or children. We do not want people who don't smoke to start using nicotine - that's not why vaping exists. Vaping is intended solely for adult smokers looking to switch away from combustible tobacco, to a hugely reduced risk alternative - which Public Health England state is 95% less harmful than smoking.
              </p>
              <p>
                We've seen what happened in the USA where certain brands marketed their products to children. It triggered a large youth uptake which was so concerning that many states have imposed flavour bans and online sales bans. This is a catastrophe for the millions of adult smokers who no longer have access to these much safer alternatives, and we do not ever want to see this repeated in the UK.
              </p>
              <p>
                It is also the right thing to do.
              </p>

            <h4 class="text-danger">Does Age Verification Cost Me Anything?</h4>          
              <p>
                No, age verification does not cost the customer anything, it is solely our responsibility as a retailer and in the majority of cases it will occur in the background and require no input from you at all.
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