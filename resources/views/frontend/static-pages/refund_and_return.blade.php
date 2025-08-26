@php
  $data = App\Models\Setting::getSettingByType('common_mail');      
  $address = $data[0]->value.' '.$data[1]->value;
  $phone = null;
  $email = null;
@endphp
@extends('frontend.mainlayout')
@section('title', $seo_ttl ?: 'Refund And Return')
@section('description', $seo_discription)
@section('keyword', $seo_keyword)

@section('body')

    <body class="body-scroll" data-page="home">
        <main class="h-100 has-header has-footer">
            <div class="main-container container">
                <div class="row mb-4">
                    <div class="card p-3">
                        <div class="col-12">
                            <h1>REFUNDS &amp; RETURNS</h1>

                            <h2 class="text-danger">Can I return something <span style="text-decoration: underline;">from the UK</span>?
                            </h2>
                            <p>We&rsquo;ll be happy to replace or refund anything unopened and unwanted within 30 days of
                                receiving your purchase! We&rsquo;re sorry to say that for hygiene reasons, we&rsquo;re not
                                able to accept any item that has been opened.</p>


                            <h2 class="text-danger">What about returning <span style="text-decoration: underline;">from outside of the UK</span>?
                            </h2>
                            <p>If there&rsquo;s an issue with your order, please contact us and we&rsquo;ll be able to
                                advise you on the best way to get it resolved.</p>
                            <p>Phone: {{$phone}}<br />Email: {{$email}}<br />Whatsapp Chat: Click the chat
                                icon in the bottom right corner of this page</p>


                            <h2 class="text-danger">How do I return something?</h2>
                            <p>To make a return, we&rsquo;d recommend using your local Post Office&rsquo;s service. Don&rsquo;t forget to ask for a proof of postage/receipt when you do -
                                this is just so that if anything happens (which is rare), it can be chased up.</p>
                            <p>We encourage you to double check that your return is packed securely and the address is clear
                                and visible on the outside of the parcel. Please also include your name, order number, and
                                the reason why you are returning your product on a note and pop that in the return&rsquo;s
                                parcel. This&rsquo;ll mean we&rsquo;ll be able to handle your return as efficiently as
                                possible.</p>
                            <p><span style="text-decoration: underline;">Our returns address is:</span><br />{{$address}}</p>


                            <h2 class="text-danger">Can I return a faulty item?</h2>
                            <p>You certainly can, as long as it&rsquo;s within the 6 month warranty period!</p>
                            <p>However, just in case the fault can be identified without having to send anything back,
                                we&rsquo;d suggest getting in touch via email, live chat or phone first. We&rsquo;ll then do
                                our best to identify any faults and suggest possible fixes.</p>
                            <p>If it&rsquo;s returned and a fault is found, we&rsquo;ll refund your postage cost (up to
                                &pound;5) and either replace or refund your item for you. If it turns out your returned item
                                isn&rsquo;t faulty, we&rsquo;ll explain what&rsquo;s happened and return your item to you
                                free of charge.</p>
                            <p>Phone: {{$phone}}<br />Email: {{$email}}<br />Whatsapp Chat: Click the chat
                                icon in the bottom right corner of this page</p>


                            <h2 class="text-danger">What if I&rsquo;ve received an incorrect item?</h2>
                            <p>If you could please get in touch with a picture of anything incorrect and of your receipt,
                                ensuring that it's showing the 'picker and packer' details on the top-right, we&rsquo;ll aim
                                to get back to you with a resolution within 24 hours.</p>
                            <p>Phone: {{$phone}}<br />Email: {{$email}}<br />Whatsapp Chat: Click the chat
                                icon in the bottom right corner of this page</p>
                            <h2 class="text-danger">How does it work if I want a refund?</h2>
                            <p>We&rsquo;ll always refund you to the payment method used at checkout. Once a refund has been
                                issued, it can take up to 5 working days (excluding bank holidays and weekends) for your
                                bank or PayPal to put the funds back into your account, but it's usually the same day.</p>
                        </div>
                    </div>
                </div>
            @include('frontend.part.extra-footer')
            </div>
        </main>
    </body>


@section('footer-script')
    <script type="text/javascript">
        $(document).ready(function() {


        });
    </script>

@endsection
@endsection
