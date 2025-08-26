@php
$data = App\Models\Setting::getSettingByType('common_mail');
$address = $data[0]->value . ' ' . $data[1]->value;
$phone = null;
$email = null;
@endphp
@extends('frontend.mainlayout')
@section('title', $seo_ttl ?: ' Delivermyvape.co.uk Privacy Policy')
@section('description', $seo_discription)
@section('keyword', $seo_keyword)

@section('body')

<body class="body-scroll" data-page="home">
    <main class="h-100 has-header has-footer">
        <div class="main-container container">
            <div class="row mb-4">
                <div class="card p-3">
                    <div class="col-12">
                        <h1>Delivermyvape.co.uk Privacy Policy</h1>
                        <p>Welcome to Delivermyvape.co.uk privacy notice.</p>
                        <p>Delivermyvape.co.uk respects your privacy and is committed to protecting your personal data.
                            This privacy
                            notice will inform you as to how we look after your personal data when you visit our website
                            <a href="https://delivermyvape.co.uk/" target="_blank"
                                rel="noopener">www.delivermyvape.co.uk</a>
                            (regardless of where you visit it from) and tell you about your privacy rights and how the
                            law
                            protects you.
                        </p>
                        <p>This privacy notice is provided in a layered format so you can click through to the specific
                            areas set out below. Please also use the <a href="#glossary">Glossary</a> to understand the
                            meaning of some of the terms used in this privacy notice.</p>
                        <ol>
                            <li><a href="#important-information">Important Information And Who We Are</a></li>
                            <li><a href="#data-we-collect">The Data We Collect About You</a></li>
                            <li><a href="#how-data-is-collected">How Is Your Personal Data Collected</a></li>
                            <li><a href="#how-we-use-data">How We Use Your Personal Data</a></li>
                            <li><a href="#disclosure">Disclosures Of Your Personal Data</a></li>
                            <li><a href="#international-transfers">International Transfers</a></li>
                            <li><a href="#data-security">Data Security</a></li>
                            <li><a href="#data-retention">Data Retention</a></li>
                            <li><a href="#your-rights">Your Legal Rights</a></li>
                            <li><a href="#glossary">Glossary</a></li>
                        </ol>
                        <p><a name="improtant-information"></a></p>

                        <h3 class="text-danger">1. Important Information and Who We Are</h3>

                        <p><strong>Purpose of This Privacy Notice</strong></p>
                        <p>This privacy notice aims to give you information on how Delivermyvape.co.uk collects and
                            processes your
                            personal data through your use of this website, including any data you may provide through
                            this
                            website when you register on our website, sign up to our newsletter or purchase a product
                            from
                            us.</p>
                        <p><strong><a href="https://www.delivermyvape.co.uk">This website</a> is not intended for anyone
                                aged
                                under 18 and we do not knowingly collect data relating to persons under the age of
                                18.</strong></p>
                        <p>It is important that you read this privacy notice together with any other privacy notice or
                            fair
                            processing notice we may provide on specific occasions when we are collecting or processing
                            personal data about you so that you are fully aware of how and why we are using your data.
                            This
                            privacy notice supplements the other notices and is not intended to override them.</p>
                        <p><strong>Controller</strong></p>
                        <p>Delivermyvape.co.uk is the controller and responsible for your personal data (collectively
                            referred
                            to as "<strong>Delivermyvape.co.uk</strong>", "<strong>we</strong>", "<strong>us</strong>"
                            or
                            "<strong>our</strong>" in this privacy notice).</p>
                        <p>We have appointed a data privacy manager who is responsible for overseeing questions in
                            relation
                            to this privacy notice. If you have any questions about this privacy notice, including any
                            requests to exercise <a>your legal rights</a>, please contact the data privacy manager using
                            the
                            details set out below.</p>
                        <p><strong>Contact Details</strong></p>

                        <p>Full name of legal entity: <strong>DELIVERMYVAPE DOT CO DOT UK LIMITED
                                (14943684)</strong><br />
                            Name or title of data privacy
                            manager: <strong>Data Privacy Manager</strong><br />Postal address:
                            <strong>{{ $address }}</strong><br />Telephone number:
                            <strong>{{ $phone }}</strong>
                        </p>

                        <p>You have the right to make a complaint at any time to the Information Commissioner's Office
                            (<strong>ICO</strong>), the UK supervisory authority for data protection issues
                            (www.ico.org.uk). We would, however, appreciate the chance to deal with your concerns before
                            you
                            approach the ICO so please contact us in the first instance.</p>
                        <p><strong>Changes to the privacy notice and your duty to inform us of changes</strong></p>
                        <p>This version was last updated on 26th Oct 2024.</p>
                        <p>It is important that the personal data we hold about you is accurate and current. Please keep
                            us
                            informed if your personal data changes during your relationship with us.</p>
                        <p><strong>Third-party links</strong></p>
                        <p>This website may include links to third-party websites, plug-ins and applications (e.g.
                            Pay360 and Superpay). Clicking on those links or enabling those connections may allow third
                            parties to collect or share data about you. We do not control these third-party websites and
                            are
                            not responsible for their privacy statements. When you leave <a
                                href="https://www.delivermyvape.co.uk">our website</a>, we encourage you to read the
                            privacy
                            notice of every website you visit.</p>
                        <p><a name="data-we-collect"></a></p>





                        <h3 class="text-danger">2. The Data We Collect About You</h3>

                        <p>Personal data, or personal information, means any information about an individual from which
                            that
                            person can be identified. It does not include data where the identity has been removed
                            (<strong>anonymous data</strong>).</p>
                        <p>We may collect, use, store and transfer different kinds of personal data about you which we
                            have
                            grouped together follows:</p>
                        <ol>
                            <li><strong>Identity Data</strong> includes first name, last name, [username or similar
                                identifier], title , date of birth and age verification results.</li>
                            <li><strong>Contact Data</strong> includes billing address, delivery address, email address
                                and
                                telephone numbers.</li>
                            <li><strong>Transaction Data</strong> includes details about products you have purchased
                                from
                                us, returns, refunds and other relevant information to manage our customer relationship.
                            </li>
                            <li><strong>Technical Data</strong> includes internet protocol (IP) address, your login
                                information, browser type and version, browser plug-in types and versions, operating
                                system
                                and platform.</li>
                            <li><strong>Usage Data</strong> includes information about how you use our website.</li>
                            <li><strong>Marketing and Communications Data</strong> includes your preferences in
                                receiving
                                marketing from us and your communication preferences.</li>
                        </ol>
                        <p>We also collect, use and share <strong>Aggregated Data</strong> such as statistical or
                            demographic data for any purpose. Aggregated Data may be derived from your personal data but
                            is
                            not considered personal data in law as this data does not directly or indirectly reveal your
                            identity. For example, we may aggregate your Usage Data to calculate the percentage of users
                            accessing a specific website feature. However, if we combine or connect Aggregated Data with
                            your personal data so that it can directly or indirectly identify you, we treat the combined
                            data as personal data which will be used in accordance with this privacy notice.</p>
                        <p>We do not collect any <strong>Special Categories of Personal Data</strong> about you (this
                            includes details about your race or ethnicity, religious or philosophical beliefs, sex life,
                            sexual orientation, political opinions, trade union membership, information about your
                            health
                            and genetic and biometric data). Nor do we collect any information about criminal
                            convictions
                            and offences.</p>
                        <h4 class="text-danger">If you fail to provide personal data</h4>
                        <p>Where we need to collect personal data by law (e.g. to verify that you are over the age of
                            18)
                            and you fail to provide that data when requested, we will not be able to perform the
                            contract we
                            have or are trying to enter into with you (for example, to provide you with the products you
                            have ordered). In this case, we may have to cancel your order but we will notify you if this
                            is
                            the case at the time.</p>
                        <p>If you fail to provide personal data which we need to perform the contract then we may have
                            to
                            cancel the contract. In this case, we will cancel your order but we will notify you if this
                            is
                            the case at the time.</p>
                        <p><a name="how-data-is-collected"></a></p>





                        <h3 class="text-danger">3. How is your personal data collected?</h3>

                        <p>We use different methods to collect data from and about you including through:</p>
                        <ul>
                            <li><strong>Direct interactions.</strong> You may give us your Identity and Contact Data by
                                filling in forms or by corresponding with us by post, phone, email or otherwise. This
                                includes personal data you provide when you:</li>
                            <li><strong>Automated technologies or interactions.</strong> As you interact with our
                                website,
                                we may automatically collect Technical Data about your equipment, browsing actions and
                                patterns. We collect this personal data by using cookies, server logs and other similar
                                technologies.</li>
                            <li>Third parties or publicly available sources. We may receive personal data about you from
                                various third parties as set out below:</li>
                            <ul>
                                <li>Technical Data from the following parties:<br />a) analytics providers such as
                                    Google
                                    based outside the EU;<br />b) search information providers such as Google or Bing
                                    based
                                    outside the EU</li>
                                <li>Contact, Financial and Transaction Data from providers of technical, payment and
                                    delivery services such as Superpay based outside the EU.</li>
                                <li>Identity Data from Jumio, our provider of identity verification services based
                                    outside
                                    the EU. You can read Jumio's privacy policy by clicking <a target="_blank"
                                        href="https://www.jumio.com/legal-information/privacy-policy/jumio-corp-privacy-policy-for-online-services/"
                                        rel="noopener">here</a>.</li>
                                <li>Identity and Contact Data from data brokers or aggregators such as Experian Ltd our
                                    age
                                    verification provider based inside the EU.</li>
                                <li>Identity and Contact Data from publicly availably sources such as Companies House
                                    and
                                    the Electoral Register based inside the EU.</li>
                            </ul>
                        </ul>



                        <h3 class="text-danger">4. How We Use Your Personal Data</h3>

                        <p>We will only use your personal data when the law allows us to. Most commonly, we will use
                            your
                            personal data in the following circumstances:</p>
                        <ul>
                            <li>Where we need to perform the contract we are about to enter into or have entered into
                                with
                                you.</li>
                            <li>Where it is necessary for our legitimate interests (or those of a third party) and your
                                interests and fundamental rights do not override those interests.</li>
                            <li>Where we need to comply with a legal or regulatory obligation.</li>
                        </ul>
                        <p>Generally we do not rely on consent as a legal basis for processing your personal data other
                            than
                            in relation to sending direct marketing communications to you via email. You have the right
                            to
                            withdraw consent to marketing at any time by contacting us</p>
                        <p><strong>Purposes for which we will use your personal data</strong></p>
                        <p>We have set out below, in a table format, a description of all the ways we plan to use your
                            personal data, and which of the legal bases we rely on to do so. We have also identified
                            what
                            our legitimate interests are where appropriate.</p>
                        <p>Note that we may process your personal data for more than one lawful ground depending on the
                            specific purpose for which we are using your data. Please contact us if you need details
                            about
                            the specific legal ground we are relying on to process your personal data where more than
                            one
                            ground has been set out in the table below.</p>
                        <div class="container">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Purpose/Activity</th>
                                        <th>Type of data</th>
                                        <th>Lawful basis for processing including basis of legitimate interest</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>To register you as a new customer</td>
                                        <td>(a) Identity<br />(b) Contact</td>
                                        <td>Performance of a contract with you</td>
                                    </tr>
                                    <tr>
                                        <td>To process and deliver your order including:<br />(a) Manage payments (b)
                                            Collect and recover money owed to us</td>
                                        <td>(a) Identity<br />(b) Contact<br />(c) Financial<br />(d)
                                            Transaction<br />(e)
                                            Marketing and Communications</td>
                                        <td>(a) Performance of a contract with you<br />(b) Necessary for our legitimate
                                            interests (to recover debts due to us)</td>
                                    </tr>
                                    <tr>
                                        <td>To manage our relationship with you which will include:<br />(a) Notifying
                                            you
                                            about changes to our terms or privacy policy</td>
                                        <td>(a) Identity<br />(b) Contact<br />(c) Profile</td>
                                        <td>(a) Performance of a contract with you<br />(b) Necessary to comply with a
                                            legal
                                            obligation</td>
                                    </tr>
                                    <tr>
                                        <td>To manage our relationship with you which will include:<br />(a) Notifying
                                            you
                                            about changes to our terms or privacy policy</td>
                                        <td>(a) Identity<br />(b) Contact<br />(c) Profile</td>
                                        <td>(a) Performance of a contract with you<br />(b) Necessary to comply with a
                                            legal
                                            obligation</td>
                                    </tr>
                                    <tr>
                                        <td>To enable you to partake in a prize draw, competition or complete a survey
                                        </td>
                                        <td>(a) Identity<br />(b) Contact<br />(c) Profile<br />(d) Usage<br />(e)
                                            Marketing
                                            and Communications</td>
                                        <td>(a) Performance of a contract with you<br />(b) Necessary for our legitimate
                                            interests (to study how customers use our products/services, to develop them
                                            and
                                            grow our business)</td>
                                    </tr>
                                    <!--- Row --->
                                    <tr>
                                        <td>To administer and protect our business and this website (including
                                            troubleshooting, data analysis, testing, system maintenance, support,
                                            reporting
                                            and hosting of data)</td>
                                        <td>(a) Identity<br />(b) Contact<br />(c) Technical</td>
                                        <td>(a) Necessary for our legitimate interests (for running our business,
                                            provision
                                            of administration and IT services, network security, to prevent fraud and in
                                            the
                                            context of a business reorganisation or group restructuring
                                            exercise)<br />(b)
                                            Necessary to comply with a legal obligation</td>
                                    </tr>
                                    <!--- Row --->
                                    <tr>
                                        <td>To deliver relevant website content and advertisements to you and measure or
                                            understand the effectiveness of the advertising we serve to you</td>
                                        <td>(a) Identity<br />(b) Contact<br />(c) Profile<br />(d) Usage<br />(e)
                                            Marketing
                                            and Communications<br />(f) Technical</td>
                                        <td>Necessary for our legitimate interests (to study how customers use our
                                            products/services, to develop them, to grow our business and to inform our
                                            marketing strategy)</td>
                                    </tr>
                                    <!--- Row --->
                                    <tr>
                                        <td>To use data analytics to improve our website, products/services, marketing,
                                            customer relationships and experiences</td>
                                        <td>(a) Technical<br />(b) Usage</td>
                                        <td>Necessary for our legitimate interests (to define types of customers for our
                                            products and services, to keep our website updated and relevant, to develop
                                            our
                                            business and to inform our marketing strategy)</td>
                                    </tr>
                                    <!--- Row --->
                                    <tr>
                                        <td>To make suggestions and recommendations to you about goods or services that
                                            may
                                            be of interest to you</td>
                                        <td>(a) Identity<br />(b) Contact<br />(c) Technical<br />(d) Usage<br />(e)
                                            Profile
                                        </td>
                                        <td>Necessary for our legitimate interests (to develop our products/services and
                                            grow our business)</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <p><strong>Marketing</strong></p>
                        <p>We strive to provide you with choices regarding certain personal data uses, particularly
                            around
                            marketing and advertising.</p>
                        <p><strong>Promotional offers from us</strong></p>
                        <p>We may use your Identity, Contact, Technical, Usage and Profile Data to form a view on what
                            we
                            think you may want or need, or what may be of interest to you. This is how we decide which
                            products, services and offers may be relevant for you (we call this marketing).</p>
                        <p>You will receive marketing communications from us if you have requested information from us
                            or
                            purchased goods from us and you have not opted out of receiving that marketing.</p>
                        <p><strong>Opting out</strong></p>
                        <p>You can ask us to stop sending you marketing messages at any time by following the opt-out
                            links
                            on any marketing message sent to you or by contacting us at any time.</p>
                        <p>Where you opt out of receiving these marketing messages, this will not apply to personal data
                            provided to us as a result of a product you have purchased or other transactions.</p>
                        <p><strong>Cookies</strong></p>
                        <p>You can set your browser to refuse all or some browser cookies, or to alert you when websites
                            set
                            or access cookies. If you disable or refuse cookies, please note that some parts of this
                            website
                            may become inaccessible or not function properly.</p>
                        <p>The 'remember me' feature is an automatic login process which creates a cookie containing a
                            unique login ID, thereby avoiding the need to enter your login details upon subsequent
                            visits to
                            our site. <span style="text-decoration: underline;"><strong>Do not select this option if you
                                    share this computer with others since personal or member only information will be
                                    accessible by other users.</strong></span></p>
                        <p>The cookies we use are "analytical" cookies. They allow us to recognise and count the number
                            of
                            visitors and to see how visitors move around the site when they are using it. This helps us
                            to
                            improve the way our website works, for example by ensuring that users are finding what they
                            are
                            looking for easily.</p>
                        <p><strong>Change of purpose</strong></p>
                        <p>We will only use your personal data for the purposes for which we collected it, unless we
                            reasonably consider that we need to use it for another reason and that reason is compatible
                            with
                            the original purpose. If you wish to get an explanation as to how the processing for the new
                            purpose is compatible with the original purpose, please contact us.</p>
                        <p>If we need to use your personal data for an unrelated purpose, we will notify you and we will
                            explain the legal basis which allows us to do so.</p>
                        <p>Please note that we may process your personal data without your knowledge or consent, in
                            compliance with the above rules, where this is required or permitted by law.</p>
                        <p><a name="disclosure"></a></p>





                        <h2 class="text-danger">5. Disclosures of your personal data</h2>

                        <p>We may have to share your personal data with the parties set out below for the purposes set
                            out
                            in the table in paragraph 4 above.</p>
                        <ul>
                            <li>Internal Third Parties as set out in the <a href="#glossary">Glossary</a></li>
                            <li>External Third Parties as set out in the <a href="#glossary">Glossary</a>.</li>
                            <li>Third parties to whom we may choose to sell, transfer, or merge parts of our business or
                                our
                                assets. Alternatively, we may seek to acquire other businesses or merge with them. If a
                                change happens to our business, then the new owners may use your personal data in the
                                same
                                way as set out in this privacy notice.</li>
                        </ul>
                        <p>We require all third parties to respect the security of your personal data and to treat it in
                            accordance with the law. We do not allow our third-party service providers to use your
                            personal
                            data for their own purposes and only permit them to process your personal data for specified
                            purposes and in accordance with our instructions.</p>
                        <p><a name="international-transfers"></a></p>




                        <h2 class="text-danger">6. International Transfers</h2>

                        <p>We do not transfer your personal data outside the European Economic Area (EEA).</p>
                        <p><a name="data-security"></a></p>



                        <h2 class="text-danger">7. Data Security</h2>

                        <p>We have put in place appropriate security measures to prevent your personal data from being
                            accidentally lost, used or accessed in an unauthorised way, altered or disclosed. In
                            addition,
                            we limit access to your personal data to those employees, agents, contractors and other
                            third
                            parties who have a business need to know. They will only process your personal data on our
                            instructions and they are subject to a duty of confidentiality.</p>
                        <p>We have put in place procedures to deal with any suspected personal data breach and will
                            notify
                            you and the ICO of a breach where we are legally required to do so.</p>
                        <p><a name="data-retention"></a></p>



                        <h2 class="text-danger">8. Data Retention</h2>

                        <p>We will only retain your personal data for as long as necessary to fulfil the purposes we
                            collected it for, including for the purposes of satisfying any legal, accounting, or
                            reporting
                            requirements.</p>
                        <p>To determine the appropriate retention period for personal data, we consider the amount,
                            nature,
                            and sensitivity of the personal data, the potential risk of harm from unauthorised use or
                            disclosure of your personal data, the purposes for which we process your personal data and
                            whether we can achieve those purposes through other means, and the applicable legal
                            requirements.</p>
                        <p>By law we have to keep basic information about our customers (including Contact, Identity,
                            Financial and Transaction Data) for six years after they cease being customers for tax
                            purposes.
                        </p>
                        <p>In some circumstances you can ask us to delete your data: see Request erasure below for
                            further
                            information.</p>
                        <p>In some circumstances we may anonymise your personal data (so that it can no longer be
                            associated
                            with you) for research or statistical purposes in which case we may use this information
                            indefinitely without further notice to you.</p>
                        <p><a name="your-rights"></a></p>




                        <h2 class="text-danger">9. Your Legal Rights</h2>

                        <p>Under certain circumstances, you have rights under data protection laws in relation to your
                            personal data. Please click on the links below to find out more about these rights:</p>
                        <ul>
                            <li><a href="#glossary-request-access">Request
                                    access to your personal data</a></li>
                            <li><a href="#glossary-request-correction">Request
                                    correction of your personal data</a></li>
                            <li><a href="#glossary-request-erasure">Request
                                    erasure of your personal data</a></li>
                            <li><a href="#glossary-object-processing">Object
                                    to processing of your personal data</a></li>
                            <li><a href="#glossary-restrict-processing">Request
                                    restriction of processing your personal data</a></li>
                            <li><a href="#glossary-request-transfer">Request
                                    transfer of your personal data</a></li>
                            <li><a href="#glossary-withdraw-consent">Right
                                    to withdraw consent</a></li>
                        </ul>
                        <p>If you wish to exercise any of the rights set out above, please contact us.</p>
                        <p><strong>No fee usually required</strong></p>
                        <p>You will not have to pay a fee to access your personal data (or to exercise any of the other
                            rights). However, we may charge a reasonable fee if your request is clearly unfounded,
                            repetitive or excessive. Alternatively, we may refuse to comply with your request in these
                            circumstances.</p>
                        <p><strong>What we may need from you</strong></p>
                        <p>We may need to request specific information from you to help us confirm your identity and
                            ensure
                            your right to access your personal data (or to exercise any of your other rights). This is a
                            security measure to ensure that personal data is not disclosed to any person who has no
                            right to
                            receive it. We may also contact you to ask you for further information in relation to your
                            request to speed up our response.</p>
                        <p><strong>Time limit to respond</strong></p>
                        <p>We try to respond to all legitimate requests within one month. Occasionally it may take us
                            longer
                            than a month if your request is particularly complex or you have made a number of requests.
                            In
                            this case, we will notify you and keep you updated.</p>
                        <p><a name="glossary"></a></p>




                        <h2 class="text-danger">10. Glossary</h2>

                        <p><a name="glossary-legal-basis"></a></p>
                        <p><strong>LAWFUL BASIS</strong></p>
                        <p><strong>Legitimate Interest</strong> means the interest of our business in conducting and
                            managing our business to enable us to give you the best service/product and the best and
                            most
                            secure experience. We make sure we consider and balance any potential impact on you (both
                            positive and negative) and your rights before we process your personal data for our
                            legitimate
                            interests. We do not use your personal data for activities where our interests are
                            overridden by
                            the impact on you (unless we have your consent or are otherwise required or permitted to by
                            law). You can obtain further information about how we assess our legitimate interests
                            against
                            any potential impact on you in respect of specific activities by contacting us</p>
                        <p><strong>Performance of Contract</strong> means processing your data where it is necessary for
                            the
                            performance of a contract to which you are a party or to take steps at your request before
                            entering into such a contract.</p>
                        <p>Comply with a legal or regulatory obligation means processing your personal data where it is
                            necessary for compliance with a legal or regulatory obligation that we are subject to.</p>
                        <p><strong>THIRD PARTIES</strong></p>
                        <p><strong>External Third Parties</strong></p>
                        <ul>
                            <li>Service providers acting as processors based in the UK who provide cloud computing and
                                web
                                design and system administration services.</li>
                            <li>Professional advisers including lawyers, bankers, auditors and insurers based in the UK
                                who
                                provide consultancy, banking, legal, insurance and accounting services.</li>
                            <li>HM Revenue &amp; Customs, regulators and other authorities based in the United Kingdom
                                who
                                require reporting of processing activities in certain circumstances.</li>
                            <li>Service providers based outside of the EEA who provide email newsletter and other
                                marketing
                                services.</li>
                            <li>Service providers based inside the EU who provide age verification services.</li>
                            <li>Service providers based outside of the EEA who provide identity verification services.
                            </li>
                            <li>Payment providers including Superpay, Secure Trading, First Data and Amex who are based
                                outside of the EEA.</li>
                            <li>Couriers based in the UK who provide delivery services.</li>
                            <li>Service providers based in the UK who provide electronic verification services.</li>
                        </ul>
                        <p><a name="your-legal-rights"></a></p>
                        <p><strong>YOUR LEGAL RIGHTS</strong></p>
                        <p>You have the right to:</p>
                        <p><a name="glossary-request-access"></a></p>
                        <p><strong>Request access</strong> to your personal data (commonly known as a "data subject
                            access
                            request"). This enables you to receive a copy of the personal data we hold about you and to
                            check that we are lawfully processing it.</p>
                        <p><a name="glossary-request-correction"></a></p>
                        <p><strong>Request correction</strong> of the personal data that we hold about you. This enables
                            you
                            to have any incomplete or inaccurate data we hold about you corrected, though we may need to
                            verify the accuracy of the new data you provide to us.</p>
                        <p><a name="glossary-request-erasure"></a></p>
                        <p><strong>Request erasure</strong> of your personal data. This enables you to ask us to delete
                            or
                            remove personal data where there is no good reason for us continuing to process it. You also
                            have the right to ask us to delete or remove your personal data where you have successfully
                            exercised your right to object to processing (see below), where we may have processed your
                            information unlawfully or where we are required to erase your personal data to comply with
                            local
                            law. Note, however, that we may not always be able to comply with your request of erasure
                            for
                            specific legal reasons which will be notified to you, if applicable, at the time of your
                            request.</p>
                        <p><a name="glossary-object-processing"></a></p>
                        <p><strong>Object to processing</strong> of your personal data where we are relying on a
                            legitimate
                            interest (or those of a third party) and there is something about your particular situation
                            which makes you want to object to processing on this ground as you feel it impacts on your
                            fundamental rights and freedoms. You also have the right to object where we are processing
                            your
                            personal data for direct marketing purposes. In some cases, we may demonstrate that we have
                            compelling legitimate grounds to process your information which override your rights and
                            freedoms.</p>
                        <p><a name="glossary-restrict-processing"></a></p>
                        <p><strong>Request restriction of processing</strong> of your personal data. This enables you to
                            ask
                            us to suspend the processing of your personal data in the following scenarios: (a) if you
                            want
                            us to establish the data's accuracy; (b) where our use of the data is unlawful but you do
                            not
                            want us to erase it; (c) where you need us to hold the data even if we no longer require it
                            as
                            you need it to establish, exercise or defend legal claims; or (d) you have objected to our
                            use
                            of your data but we need to verify whether we have overriding legitimate grounds to use it.
                        </p>
                        <p><a name="glossary-request-transfer"></a></p>
                        <p><strong>Request the transfer</strong> of your personal data to you or to a third party. We
                            will
                            provide to you, or a third party you have chosen, your personal data in a structured,
                            commonly
                            used, machine-readable format. Note that this right only applies to automated information
                            which
                            you initially provided consent for us to use or where we used the information to perform a
                            contract with you.</p>
                        <p><a name="glossary-withdraw-consent"></a></p>
                        <p><strong>Withdraw consent at any time</strong> where we are relying on consent to process your
                            personal data. However, this will not affect the lawfulness of any processing carried out
                            before
                            you withdraw your consent. If you withdraw your consent, we may not be able to provide
                            certain
                            products or services to you. We will advise you if this is the case at the time you withdraw
                            your consent.</p>
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