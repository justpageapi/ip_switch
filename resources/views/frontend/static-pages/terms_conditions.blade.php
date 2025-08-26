@php
$data = App\Models\Setting::getSettingByType('common_mail');
$address = $data[0]->value.' '.$data[1]->value;
$phone = null;
$email = null;
@endphp
@extends('frontend.mainlayout')
@section('title', $seo_ttl ?: 'Delivermyvape.co.uk Terms And Conditions')
@section('description', $seo_discription)
@section('keyword', $seo_keyword)

@section('body')
<style>
    ul {
        list-style-type: none;
    }
</style>

<body class="body-scroll" data-page="home">
    <main class="h-100 has-header has-footer">
        <div class="main-container container">
            <div class="row mb-4">
                <div class="card p-3">
                    <div class="col-12">
                        <h1> Delivermyvape.co.uk Terms And Conditions</h1>
                        <p><strong>YOU MUST BE OVER 18 TO PURCHASE GOODS FROM OUR SITE. PLEASE BE AWARE THAT WE VERIFY
                                YOUR AGE BEFORE WE FULFIL YOUR ORDER.</strong></p>
                        <ol>
                            <li class="text-danger">THESE TERMS</li>
                            <ul class="mb-3">
                                <li>1.1 <strong>What these terms cover.</strong> These are the terms and conditions on
                                    which we supply e-cigarette and related products to you.</li>
                                <li>1.2 <strong>Why you should read them.</strong> Please read these terms carefully
                                    before you submit your order to us. These terms tell you who we are, how we will
                                    provide products to you, how you and we may change or end the contract, what to do
                                    if there is a problem and other important information. If you think that there is a
                                    mistake in these terms, please contact us to discuss.</li>
                            </ul>


                            <li class="text-danger">INFORMATION ABOUT US AND HOW TO CONTACT US</li>
                            <ul class="mb-3">
                                <li>2.1 <strong>Who we are.</strong> We are <strong> DELIVERMYVAPE DOT CO DOT UK LIMITED
                                        (14943684) T/A DeliverMyVape.co.uk </strong> a company registered in
                                    England and Wales. Our registered office is at {{$address}} .</li>
                                <li>2.2 <strong>How we may contact you.</strong> If we have to contact you we will do so
                                    by telephone or by writing to you at the email address or postal address you
                                    provided to us in your order.</li>
                                <li>2.3 "Writing" includes emails. When we use the words "writing" or "written" in these
                                    terms, this includes emails.</li>
                            </ul>


                            <li class="text-danger">OUR CONTRACT WITH YOU</li>
                            <ul class="mb-3">
                                <li>3.1 <strong>How we will accept your order.</strong> Our acceptance of your order
                                    will take place when we email you to accept it, at which point a contract will come
                                    into existence between you and us.</li>
                                <li>3.2 <strong>If we cannot accept your order.</strong> If we are unable to accept your
                                    order, we will inform you of this by email and will not charge you for the product.
                                    This might be because the product is out of stock or because we have identified an
                                    error in the price or description of the product or because there is a safety issue
                                    or because we are unable to verify that you are aged over 18 years of age or because
                                    you are resident in a country that we can't supply to (see clause 3.4 below).</li>
                                <li>3.3 <strong>Your order number.</strong> We will assign an order number to your order
                                    and tell you what it is when we accept your order. It will help us if you can tell
                                    us the order number whenever you contact us about your order.</li>
                                <li>3.4 <strong>We cannot sell some goods to customer resident in certain
                                        countries.</strong> Due to country specific regulations (including the EU's
                                    Tobacco Products Directive (2014/40/EU) we cannot sell certain electronic cigarette
                                    products to consumers resident in the following countries:</li>
                                <li>
                                    <div class="row p-3">
                                        <div class="col-4 border">Afghanistan</div>
                                        <div class="col-4 border">Albania</div>
                                        <div class="col-4 border">Algeria</div>
                                        <div class="col-4 border">American Samoa</div>
                                        <div class="col-4 border">Angola</div>
                                        <div class="col-4 border">Anguilla</div>
                                        <div class="col-4 border">Argentina</div>
                                        <div class="col-4 border">Armenia</div>
                                        <div class="col-4 border">Austria</div>
                                        <div class="col-4 border">Azerbaijan</div>
                                        <div class="col-4 border">Bangladesh</div>
                                        <div class="col-4 border">Belgium (only applies to nicotine containing products)
                                        </div>
                                        <div class="col-4 border">Bolivia</div>
                                        <div class="col-4 border">Bosnia</div>
                                        <div class="col-4 border">Botswana</div>
                                        <div class="col-4 border">Brazil</div>
                                        <div class="col-4 border">Brunei Darussalam</div>
                                        <div class="col-4 border">Bulgaria</div>
                                        <div class="col-4 border">Burundi</div>
                                        <div class="col-4 border">Cambodia</div>
                                        <div class="col-4 border">Cameroon</div>
                                        <div class="col-4 border">Central African Republic</div>
                                        <div class="col-4 border">Chad</div>
                                        <div class="col-4 border">Chile</div>
                                        <div class="col-4 border">China</div>
                                        <div class="col-4 border">Colombia</div>
                                        <div class="col-4 border">Congo</div>
                                        <div class="col-4 border">Congo, Democratic Republic of the</div>
                                        <div class="col-4 border">Cote D'Ivoire</div>
                                        <div class="col-4 border">Cyrpus</div>
                                        <div class="col-4 border">Czech Republic</div>
                                        <div class="col-4 border">Denmark</div>
                                        <div class="col-4 border">Egypt</div>
                                        <div class="col-4 border">Estonia</div>
                                        <div class="col-4 border">Ethiopia</div>
                                        <div class="col-4 border">Finland</div>
                                        <div class="col-4 border">France</div>
                                        <div class="col-4 border">French Guiana</div>
                                        <div class="col-4 border">French Polynesia</div>
                                        <div class="col-4 border">French Southern Territories</div>
                                        <div class="col-4 border">Gambia</div>
                                        <div class="col-4 border">Georgia</div>
                                        <div class="col-4 border">Ghana</div>
                                        <div class="col-4 border">Greece</div>
                                        <div class="col-4 border">Guadeloupe</div>
                                        <div class="col-4 border">Guam</div>
                                        <div class="col-4 border">Guernsey</div>
                                        <div class="col-4 border">Haiti</div>
                                        <div class="col-4 border">Hong Kong</div>
                                        <div class="col-4 border">Hungary</div>
                                        <div class="col-4 border">Iceland</div>
                                        <div class="col-4 border">India</div>
                                        <div class="col-4 border">Indonesia</div>
                                        <div class="col-4 border">Iran</div>
                                        <div class="col-4 border">Iraq</div>
                                        <div class="col-4 border">Italy</div>
                                        <div class="col-4 border">Jamaica</div>
                                        <div class="col-4 border">Jordan</div>
                                        <div class="col-4 border">Kazakhstan</div>
                                        <div class="col-4 border">Korea, Democratic People's Republic Of</div>
                                        <div class="col-4 border">Kuwait</div>
                                        <div class="col-4 border">Latvia</div>
                                        <div class="col-4 border">Lebanon</div>
                                        <div class="col-4 border">Lesotho</div>
                                        <div class="col-4 border">Lithuania</div>
                                        <div class="col-4 border">Luxembourg</div>
                                        <div class="col-4 border">Macao</div>
                                        <div class="col-4 border">Malaysia</div>
                                        <div class="col-4 border">Mali</div>
                                        <div class="col-4 border">Martinique</div>
                                        <div class="col-4 border">Mayotte</div>
                                        <div class="col-4 border">Mexico</div>
                                        <div class="col-4 border">Moldova, Republic of</div>
                                        <div class="col-4 border">Mongolia</div>
                                        <div class="col-4 border">Morocco</div>
                                        <div class="col-4 border">Mozambique</div>
                                        <div class="col-4 border">New Caledonia</div>
                                        <div class="col-4 border">Niger</div>
                                        <div class="col-4 border">Nigeria</div>
                                        <div class="col-4 border">North Macedonia</div>
                                        <div class="col-4 border">Norway</div>
                                        <div class="col-4 border">Oman</div>
                                        <div class="col-4 border">Palestine</div>
                                        <div class="col-4 border">Paraguay</div>
                                        <div class="col-4 border">Philippines</div>
                                        <div class="col-4 border">Poland</div>
                                        <div class="col-4 border">Portugal</div>
                                        <div class="col-4 border">Puerto Rica</div>
                                        <div class="col-4 border">Qatar</div>
                                        <div class="col-4 border">Reunion</div>
                                        <div class="col-4 border">Romania</div>
                                        <div class="col-4 border">Russia</div>
                                        <div class="col-4 border">Rwanda</div>
                                        <div class="col-4 border">Saint Pierre &amp; Miquelon</div>
                                        <div class="col-4 border">Senegal</div>
                                        <div class="col-4 border">Sierra Leone</div>
                                        <div class="col-4 border">Singapore</div>
                                        <div class="col-4 border">Slovakia</div>
                                        <div class="col-4 border">Slovenia</div>
                                        <div class="col-4 border">Somalia</div>
                                        <div class="col-4 border">South Africa</div>
                                        <div class="col-4 border">Spain</div>
                                        <div class="col-4 border">Sudan</div>
                                        <div class="col-4 border">Swaziland</div>
                                        <div class="col-4 border">Syrian Arab Republic</div>
                                        <div class="col-4 border">Taiwan</div>
                                        <div class="col-4 border">Tajikstan</div>
                                        <div class="col-4 border">Tanzania</div>
                                        <div class="col-4 border">Thailand</div>
                                        <div class="col-4 border">Timor-Leste</div>
                                        <div class="col-4 border">Tunisia</div>
                                        <div class="col-4 border">Turkey</div>
                                        <div class="col-4 border">Turkmenistan</div>
                                        <div class="col-4 border">Uganda</div>
                                        <div class="col-4 border">Ukraine</div>
                                        <div class="col-4 border">United Arab Emirates</div>
                                        <div class="col-4 border">United States Minor Outlying Islands</div>
                                        <div class="col-4 border">United States of America</div>
                                        <div class="col-4 border">Uruguay</div>
                                        <div class="col-4 border">Uzbekistan</div>
                                        <div class="col-4 border">Venezuela</div>
                                        <div class="col-4 border">Vietnam</div>
                                        <div class="col-4 border">Virgin Islands US</div>
                                        <div class="col-4 border">Wallis and Futuna</div>
                                        <div class="col-4 border">Western Sahara</div>
                                        <div class="col-4 border">Yemen</div>
                                        <div class="col-4 border">Zambia</div>
                                        <div class="col-4 border">Zimbabwe</div>
                                    </div>
                                </li>
                                <li>We are however able to sell all of our products to consumers resident in the UK and
                                    to countries not listed above. If we are unable to sell to you a certain product
                                    we'll notify you of that during the check-out process.</li>
                                <li>3.5 <strong>For non UK residents.</strong> If you're resident outside of the UK then
                                    it is your responsibility to ensure that the products that you order from us comply
                                    with the local laws that apply in your country and to take responsibility for
                                    importing the goods into your country. We will have no responsibility for products
                                    which are stopped at customs or which do not meet the legislation which applies in
                                    your country.</li>
                                <li>3.6 <strong>We only sell to people over 18.</strong> By law we cannot sell
                                    e-cigarettes or related products to anyone who is under 18 years of age.</li>
                                <li>3.7 <strong>Verification of your identity.</strong> In order to comply with our
                                    legal obligations not to sell to people under 18 and to minimise the risk of fraud
                                    on your account we carry out electronic verification on our customers. We do this
                                    when you open an account and may do it again if your personal details change (e.g.
                                    your delivery address or email changes) to ensure that your account remains secure.
                                    We will share your name, address and date of birth with Experian who provide
                                    electronic verification services to us. (Please see our <a
                                        href="{{env('APP_URL')}}/privacy-policy/">privacy
                                        policy</a> for further information). If we are unable to verify your age through
                                    electronic verification, we will ask you to provide documentary evidence (e.g. a
                                    copy of your passport or driving licence) to prove that you are over 18. We will not
                                    despatch the products until we've verified your age. If we are unable to verify your
                                    age electronically and you do not provide documentary evidence to prove you are over
                                    18 we will cancel your order and refund the sums you've paid.</li>
                            </ul>


                            <li class="text-danger">OUR PRODUCTS</li>
                            <ul class="mb-3">
                                <li>4.1 <strong>Products may vary slightly from their pictures.</strong> The images of
                                    the products on our website are for illustrative purposes only. Although we have
                                    made every effort to display the colours accurately, we cannot guarantee that a
                                    device's display of the colours accurately reflects the colour of the products. Your
                                    product may vary slightly from those images.</li>
                                <li>4.2 <strong>Product packaging may vary.</strong> The packaging of the product may
                                    vary from that shown in images on our website. You may not return the product
                                    because the packaging does not match the image shown on our website.</li>
                                <li>4.3 <strong>Product safety.</strong> There are some safety risks associated with
                                    using e-cigarettes and we strongly recommend that you read the instructions for your
                                    product before you start using it.
                                </li>
                            </ul>


                            <li class="text-danger">YOUR RIGHTS TO MAKE CHANGES</li>
                            <ul class="mb-3">
                                <li>5.1 If you wish to make a change to the product you have ordered please contact us.
                                    We will let you know if the change is possible. If it is possible we will let you
                                    know about any changes to the price of the product, the timing of supply or anything
                                    else which would be necessary as a result of your requested change and ask you to
                                    confirm whether you wish to go ahead with the change. If we cannot make the change
                                    or the consequences of making the change are unacceptable to you, you may want to
                                    end the contract (see clause 8- Your rights to end the contract).</li>
                            </ul>


                            <li class="text-danger">OUR RIGHTS TO MAKE CHANGES</li>
                            <ul class="mb-3">
                                <li>6.1 <strong>Minor changes to the products.</strong> We may change the product:
                                    <ul class="mb-3">
                                        <li>(a) to reflect changes in relevant laws and regulatory requirements, for
                                            example, where it is necessary to changing packaging or change the design of
                                            products to meet changes in the law; and</li>
                                        <li>(b) to implement minor technical adjustments and improvements, for example
                                            to address a safety issue. These changes will not materially affect your use
                                            of the product.</li>
                                    </ul>
                                </li>
                            </ul>



                            <li class="text-danger">PROVIDING THE PRODUCTS</li>
                            <ul class="mb-3">
                                <li>7.1 <strong>Delivery costs.</strong> The costs of delivery will be as displayed to
                                    you on our website.</li>
                                <li>7.2 <strong>When we will provide the products.</strong> During the order process we
                                    will let you know when we will provide the products to you. We will deliver the
                                    goods to you as soon as reasonably possible and in any event within 30 days after
                                    the day on which we accept your order.</li>
                                <li>7.3 <strong>We are not responsible for delays outside our control.</strong> If our
                                    supply of the products is delayed by an event outside our control then we will
                                    contact you as soon as possible to let you know and we will take steps to minimise
                                    the effect of the delay. Provided we do this we will not be liable for delays caused
                                    by the event, but if there is a risk of substantial delay you may contact us to end
                                    the contract and receive a refund for any products you have paid for but not
                                    received.</li>
                                <li>7.4 <strong>If you are not at home when the product is delivered.</strong> If no one
                                    is available at your address to take delivery and the products cannot be posted
                                    through your letterbox, you will either be left a note informing you of how to
                                    rearrange delivery or collect the products from your local depot or the carrier will
                                    attempt delivery twice more after which the products will be returned to our depot.
                                </li>
                                <li>7.5 <strong>If you do not re-arrange delivery.</strong> If, after a failed delivery
                                    to you, you do not re-arrange delivery or collect the products from a delivery depot
                                    within 14 days or you are not at home when the carrier attempts redelivery the
                                    products will be returned to us. Once we receive the products back we will contact
                                    you for further instructions and may charge you for storage costs and any further
                                    delivery costs. If, despite our reasonable efforts, we are unable to contact you or
                                    re-arrange delivery or collection we may end the contract and clause 10 will apply.
                                </li>
                                <li>7.6 <strong>When you become responsible for the goods.</strong> The product will be
                                    your responsibility from the time we deliver the product to the address you gave us.
                                </li>
                                <li>7.7 <strong>When you own goods.</strong> You own the product when we dispatch it to
                                    you.</li>
                            </ul>


                            <li class="text-danger">YOUR RIGHTS TO END THE CONTRACT</li>
                            <ul class="mb-3">
                                <li>8.1 <strong>You can always end your contract with us.</strong> Your rights when you
                                    end the contract will depend on whether there is anything wrong with the product
                                    you've bought, how we are performing and when you decide to end the contract:
                                    <ul class="mb-3">
                                        <li>(a) If what you have bought is faulty or mis-described you may have a legal
                                            right to end the contract (or to get the product repaired or replaced or a
                                            service re-performed or to get some or all of your money back), see clause
                                            11;</li>
                                        <li>(b) If you want to end the contract because of something we have done or
                                            have told you we are going to do, see clause 8.2;</li>
                                        <li>(c) If you have just changed your mind about the product, see clause 8.3.
                                            You may be able to get a refund if you are within the cooling-off period,
                                            but this may be subject to deductions.</li>
                                    </ul>
                                </li>
                                <li>8.2 <strong>Ending the contract because of something we have done or are going to
                                        do.</strong> If you are ending a contract for a reason set out at (a) to (c)
                                    below the contract will end immediately and we will refund you in full for any
                                    products which have not been provided and you may also be entitled to compensation.
                                    The reasons are:
                                    <ul class="mb-3">
                                        <li>(a) we have told you about an error in the price or description of the
                                            product you have ordered and you do not wish to proceed;</li>
                                        <li>(b) there is a risk that supply of the products may be significantly delayed
                                            because of events outside our control;</li>
                                        <li>(c) you have a legal right to end the contract because of something we have
                                            done wrong.</li>
                                    </ul>
                                </li>
                                <li>8.3 <strong>Exercising your right to change your mind (Consumer Contracts
                                        Regulations 2013).</strong> For most products bought online if you are resident
                                    in the UK you have a legal right to change your mind within 14 days and receive a
                                    refund. These rights, under the Consumer Contracts Regulations 2013, are explained
                                    in more detail in these terms.</li>
                                <li>8.4 <strong>Our goodwill guarantee.</strong> Please note, these terms reflect the
                                    goodwill guarantee offered by Delivermyvape to its UK customers, which is more
                                    generous
                                    than your legal rights under the Consumer Contracts Regulations as it applies to all
                                    customers and allows you to return the goods within 30 days of delivery. This
                                    goodwill guarantee does not affect your legal rights in relation to faulty or
                                    misdescribed products (see clause 11.2):</li>
                                <li>8.5 <strong>When you don't have the right to change your mind.</strong> You do not
                                    have a right to change your mind in respect of products (e.g. vape kits) sealed for
                                    health protection or hygiene purposes, once these have been unsealed after you
                                    receive them.</li>
                                <li>8.6 <strong>How long do I have to change my mind?</strong> You have <strong>30
                                        days</strong> after the day you (or someone you nominate) receives the goods,
                                    unless your goods are split into several deliveries over different days. In this
                                    case you have until <strong>30 days</strong> after the day you (or someone you
                                    nominate) receives the last delivery to change your mind about the goods.</li>
                            </ul>


                            <li class="text-danger">HOW TO END THE CONTRACT WITH US (INCLUDING IF YOU HAVE CHANGED YOUR
                                MIND)</li>
                            <ul class="mb-3">
                                <li>9.1 <strong>Tell us you want to end the contract.</strong> To end the contract with
                                    us, please let us know by. Please provide
                                    your name and order number or your name and delivery or billing address.</li>
                                <li>9.2 <strong>Returning products after ending the contract.</strong> If you end the
                                    contract for any reason after products have been dispatched to you or you have
                                    received them, you must return them to us. You must return the goods by posting them
                                    back to us at 27 Greenhill Crescent, Watford, WD18 8YB. Please either include the
                                    returns slip or a note with your name and order number or, if you can't find your
                                    order number, your name and address and the reason why you're returning the
                                    products. If you are exercising your right to change your mind you must send off the
                                    products within 14 days of telling us you wish to end the contract. We pay the cost
                                    of returns through Royal Mail.<strong>We will not be able to refund
                                        you the price of the products until we receive the returned products.</strong>
                                </li>
                                <li>9.3 <strong>How we will refund you.</strong> We will refund you the price you paid
                                    for the products including delivery costs, by the method you used for payment.
                                    However, we may make deductions from the price, as described below.</li>
                                <li>9.4 <strong>Deductions from refunds if you are exercising your right to change your
                                        mind.</strong> If you are exercising your right to change your mind, we may
                                    reduce your refund of the price (excluding delivery costs) to reflect any reduction
                                    in the value of the goods, if this has been caused by your handling them in a way
                                    which would not be permitted in a shop. If we refund you the
                                    price paid before we are able to inspect the products and later discover you have
                                    handled them in an unacceptable way, you must pay us an appropriate amount.</li>
                                <li>9.5 <strong>When your refund will be made.</strong> We will make any refunds due to
                                    you as soon as possible. If you are exercising your right to change your mind then
                                    your refund will be made within 14 days from the day on which we receive the product
                                    back from you or, if earlier, the day on which you provide us with evidence that you
                                    have sent the product back to us. For information about how to return a product to
                                    us, see clause 9.2.</li>
                            </ul>


                            <li class="text-danger">OUR RIGHTS TO END THE CONTRACT</li>
                            <ul class="mb-3">
                                <li>10.1 <strong>We may end the contract if you break it.</strong> We may end the
                                    contract for a product at any time by writing to you if you do not, within a
                                    reasonable time, allow us to deliver the products to you or arrange collection of
                                    the products.</li>
                            </ul>


                            <li class="text-danger">IF THERE IS A PROBLEM WITH THE PRODUCT</li>
                            <ul class="mb-3">
                                <li>11.1 <strong>How to tell us about problems.</strong> If you have any questions or
                                    complaints about the product, please contact us. You can do this by telephoning our
                                    customer service team at 01923 479 992, using the live chat feature on our website.
                                </li>
                                <li>11.2 <strong>Summary of your legal rights.</strong> We are under a legal duty to
                                    supply products that are in conformity with this contract. See the box below for a
                                    summary of your key legal rights in relation to the product. Nothing in these terms
                                    will affect your legal rights.</li>
                                <li>11.3 <strong>Your obligation to return rejected products.</strong> If you wish to
                                    exercise your legal rights to reject products you must post them back to us. We will
                                    pay the costs of postage. Please see clause 9.2 on how to return the products to us.
                                </li>
                            </ul>


                            <li class="text-danger">PRICE AND PAYMENT</li>
                            <ul class="mb-3">
                                <li>
                                    <div style="border: 1px solid; padding: 5px;">
                                        <h4>Summary of your key legal rights if you are a UK resident</h4>
                                        <p>This is a summary of your key legal rights. These are subject to certain
                                            exceptions. </p>
                                        <p>If you've purchased goods (for example, vape kits or batteries) the Consumer
                                            Rights Act 2015 says goods must be as described, fit for purpose and of
                                            satisfactory quality. During the expected lifespan of your product your
                                            legal rights entitle you to the following:</p>
                                        <ul class="mb-3">
                                            <li>up to 30 days: if your goods are faulty, then you can get an immediate
                                                refund.</li>
                                            <li>up to six months: if your goods can't be repaired or replaced, then
                                                you're entitled to a full refund, in most cases.</li>
                                            <li>up to six years: if your goods do not last a reasonable length of time
                                                you may be entitled to some money back.</li>
                                        </ul>
                                    </div>
                                </li>
                                <li>12.1 <strong>Where to find the price for the product.</strong> The price of the
                                    product (which includes VAT) will be the price indicated on the order page when you
                                    placed your order. We take all reasonable care to ensure that the price of the
                                    product advised to you is correct. However please see clause 12.3 for what happens
                                    if we discover an error in the price of the product you order.</li>
                                <li>12.2 <strong>We will pass on changes in the rate of VAT.</strong> If the rate of VAT
                                    changes between your order date and the date we supply the product, we will adjust
                                    the rate of VAT that you pay, unless you have already paid for the product in full
                                    before the change in the rate of VAT takes effect.</li>
                                <li>12.3 <strong>What happens if we got the price wrong.</strong> It is always possible
                                    that, despite our best efforts, some of the products we sell may be incorrectly
                                    priced. We will normally check prices before accepting your order so that, where the
                                    product's correct price at your order date is less than our stated price at your
                                    order date, we will charge the lower amount. If the product's correct price at your
                                    order date is higher than the price stated to you, we will contact you for your
                                    instructions before we accept your order. If we accept and process your order where
                                    a pricing error is obvious and unmistakeable and could reasonably have been
                                    recognised by you as a mispricing, we may end the contract, refund you any sums you
                                    have paid and require the return of any goods provided to you.</li>
                                <li>12.4 <strong>When you must pay.</strong> You must pay for the products before we
                                    dispatch them.</li>
                                <li>12.5 <strong>What to do if you think an invoice is wrong.</strong> If you think an
                                    invoice is wrong please contact us promptly to let us know and we will not charge
                                    you interest until we have resolved the issue.</li>
                            </ul>



                            <li class="text-danger">OUR RESPONSIBILITY FOR LOSS OR DAMAGE SUFFERED BY YOU</li>
                            <ul class="mb-3">
                                <li>13.1 <strong>We are responsible to you for foreseeable loss and damage caused by
                                        us.</strong> If we fail to comply with these terms, we are responsible for loss
                                    or damage you suffer that is a foreseeable result of our breaking this contract or
                                    our failing to use reasonable care and skill, but we are not responsible for any
                                    loss or damage that is not foreseeable. Loss or damage is foreseeable if either it
                                    is obvious that it will happen or if, at the time the contract was made, both we and
                                    you knew it might happen, for example, if you discussed it with us during the sales
                                    process.</li>
                                <li>13.2 <strong>We do not exclude or limit in any way our liability to you where it
                                        would be unlawful to do so.</strong> This includes liability for death or
                                    personal injury caused by our negligence or the negligence of our employees, agents
                                    or subcontractors; for fraud or fraudulent misrepresentation; for breach of your
                                    legal rights in relation to the products as summarised at clause 11.2.</li>
                                <li>13.3 <strong>We are not liable for business losses.</strong> We only supply the
                                    products for domestic and private use. If you use the products for any commercial,
                                    business or re-sale purpose we will have no liability to you for any loss of profit,
                                    loss of business, business interruption, or loss of business opportunity.</li>
                            </ul>



                            <li class="text-danger">HOW WE MAY USE YOUR PERSONAL INFORMATION</li>
                            <ul class="mb-3">
                                <li>14.1 How we may use your personal information. We will only use your personal
                                    information as set out in our <a target="_blank"
                                        href="{{env('APP_URL')}}/privacy-policy/" rel="noopener">privacy policy</a></li>
                            </ul>


                            <li class="text-danger">OTHER IMPORTANT TERMS</li>
                            <ul class="mb-3">
                                <li>15.1 <strong>We may transfer this agreement to someone else.</strong> We may
                                    transfer our rights and obligations under these terms to another organisation. We
                                    will always tell you in writing if this happens and we will ensure that the transfer
                                    will not affect your rights under the contract.</li>
                                <li>15.2 <strong>You need our consent to transfer your rights to someone else.</strong>
                                    You may only transfer your rights or your obligations under these terms to another
                                    person if we agree to this in writing.</li>
                                <li>15.3 <strong>Nobody else has any rights under this contract.</strong> This contract
                                    is between you and us. No other person shall have any rights to enforce any of its
                                    terms.</li>
                                <li>15.4 <strong>If a court finds part of this contract illegal, the rest will continue
                                        in force.</strong> Each of the paragraphs of these terms operates separately. If
                                    any court or relevant authority decides that any of them are unlawful, the remaining
                                    paragraphs will remain in full force and effect.</li>
                                <li>15.5 <strong>Even if we delay in enforcing this contract, we can still enforce it
                                        later.</strong> If we do not insist immediately that you do anything you are
                                    required to do under these terms, or if we delay in taking steps against you in
                                    respect of your breaking this contract, that will not mean that you do not have to
                                    do those things and it will not prevent us taking steps against you at a later date.
                                </li>
                                <li>15.6 <strong>Which laws apply to this contract and where you may bring legal
                                        proceedings.</strong> These terms are governed by English law and if you live in
                                    England and Wales you can bring legal proceedings in respect of the products in the
                                    English or Welsh courts. If you live in Scotland you can bring legal proceedings in
                                    respect of the products in either the Scottish or the English courts. If you live in
                                    Northern Ireland you can bring legal proceedings in respect of the products in
                                    either the Northern Irish or the English courts.</li>
                            </ul>
                        </ol>
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