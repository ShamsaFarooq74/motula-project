
@extends('web.layouts.master')
@section('content')
@include('web.layouts.partials.header')
@section('title', '- Privacy Policy');
<body>
    <div class="container-fluid px-0">
        <div class="contact-us-page-content">
            <div class="banner-contact-us">
                <div class="course-banner-text">
                    <h1 style="color: #000000;">Privacy Policy</h1>
                    <p class="contact-us-wordmarks">E-course Terms of Use Agreement and Liability Disclaimer</p>
                </div>
            </div>
            <div class="contact-us-section">
                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        <div class="contact-right-details">
                            <h3>E-course Terms of Use Agreement and Liability Disclaimer</h3>
                            <p style="color: black;"> This E-course and its online Mock test course, documents and other associated content (hereinafter inclusively referred to as “E-course”) has been produced by seruonlinetest.co.uk  When you purchase our E-course, you agree to this Terms of Use and Liability , the term of use when you attempt the Mock test then the payment will not be refundable,
                                All sales are final for online E-courses. No refunds are issued for online courses once a sale is completed. The supplies and props used for any exercises instructed are not included in the purchasing price of the E-course.
                                The information in our E-course is for educational Mock test  purposes only and is not actual test,
                                However, we make no representations, guarantees or warranties that the information or exercises in our E-course is appropriate for you and you will pass a test 100% or will result in improvement of your skills and knowledge.  The information in our E-course is by no means complete or exhaustive and therefore does not apply to all conditions,
                                The information and instruction in this E-course is not intended to be “classroom base teaching” it is a material based study, and Mock Test.
                                <br>
                                <br>
                                Before you begin the Mock test  in the E-course, you should get read all the term and conditions to understand when you attempt the test the payment will not be refundable,
                                <br>
                                <br>

                                By purchasing this E-course, revocable license to access and use our given package with the given period of time scale,
                                copyrighted E-course and any associated materials solely for your own personal and non-commercial use.  Our E-course is protected under foreign copyrights. The copying, redistribution, use or publication by you of any of the content within our E-course is strictly prohibited.  Your purchase of our E-course does not grant you any ownership rights to our E-course.  Any breach in the terms of this agreement may result in termination of your access to the E-course materials.
                                Our E-course may contain references or links to materials from third-parties.  Reference to any third-party products, services, processes or other information, by trade name, trademark, manufacturer, supplier or otherwise does not constitute or imply endorsement, sponsorship or recommendation thereof, or any affiliation with us.  We are not responsible for examining or evaluating the content or accuracy and we do not warrant and will not have any liability or responsibility for any third-party materials or websites, or for any other materials, products, or services of third-parties.  We are not liable for any harm or damages related to the purchase or use of goods, services, resources, content, or any other transactions made in connection with any third-party websites. Complaints, claims, concerns, or questions regarding third-party products should be directed to the third-party.
                                Our E-course is intended solely for Users who are at least age 18 years of age or older.
                                Any use of or access to our E-course by anyone under such, is unauthorized, unlicensed and in violation of these Terms of Use. By purchasing our E-course, you represent and warrant that you are 18 years or older and that you agree to and to abide by all of the terms and conditions of this Agreement. www.seruonlinetest.co.uk  has sole right and discretion to determine whether to sell our E-course to any individual and may reject a purchase by any individual with or without explanation.
                                <br>
                                <br>

                                We will respond quickly to claims of copyright infringement as found in our E-course, according to the terms of the Digital law,
                                <br>
                                <br>

                                As a content provided in our E-Courses is provided as available with possible mistake so there is no liability on content, content may contains bugs, errors, problems, or other limitations, www.seruonlinetest.co.uk cannot guarantee and does not promise any specific results from use of our websites or content, www.seruonlinetest.co.uk does not warrant that our content or online Mock test give you 100% result,
                                kindly reads through all these term and condition before buying a course or Mock test.
                                <br>
                                <br>

                                This Terms of Service Agreement shall be governed and construed in accordance with website term and conditions
                                To the extent that any Content is in conflict or inconsistent with this Agreement, this Agreement shall take precedence. Our failure to enforce any provision of this Agreement shall not be deemed a waiver of such provision nor of the right to enforce such provision. Our rights under this Agreement shall survive any termination of this Agreement. will result to block your access to account.
                            </p>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript">
    $("#submiitt").click(function() {
        var email = $("#email").val();
        var text = $("#textarea1").val();
        var name = $("#full_name").val();
        var phone = $("#phone").val();


        console.log(email, text,name, phone);
        if (email === '') {
            $("#error").text('Please provide your Email Address');
            $("#error").show();
        } else if (phone === '') {
            $("#error").text('Please provide your Phone Number');
            $("#error").show();
        } else if (text === '') {
            $("#error").text('Please add your Message');
            $("#error").show();
        }  else {
            $('#loader').show();
            $.ajax({
                url: "{{route('contact.detail')}}",
                method: 'POST',
                data: {
                    email: email,
                    text: text,
                    name: name,
                    phone: phone,
                    _token: '{{ csrf_token() }}',
                },
                success: function(response) {
                    $('#loader').hide();
                    if (response.message) {
                        $("#successAuth").text('Your Message has been sent');
                        $("#successAuth").show();

                    } else {
                        $("#error").text('There was some Issue in sending your Message');
                        $("#error").show();
                        setTimeout(function(){
        $('#error').hide('slow')
        }, 2000);
                    }
                }
            })
        }
    });
</script>
<script>
    setTimeout(function(){
        $('#error').hide('slow')
        }, 2000);
    setTimeout(function(){
        $('#successAuth').hide('slow')
        }, 2000);
</script>
</html>
@endsection
