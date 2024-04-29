
@extends('web.layouts.master')
@section('content')
@include('web.layouts.partials.header')
@section('title', '- Contact Us');
<body>
    <div class="container-fluid px-0">
        <div class="contact-us-page-content">
            <div class="banner-contact-us">
                <div class="course-banner-text">
                    <h1 style="color: #000000;">Contact Us</h1>
                    <p class="contact-us-wordmarks">Let’s  start a conversation, Ask how can we help you</p>
                </div>
            </div>
            <div class="contact-us-section">
                <div class="row">
                    <div class="col-lg-7 col-md-7 col-sm-12 mb-2">
                        <div class="contact-form registration-mobile-view">
                            <div class="contact-details">
                                <div class="alert-messages">
                                    <div class="alert alert-danger" id="error" style="display: none;"></div>
                                    <div class="alert alert-success" id="successAuth" style="display: none;"></div>
                                </div>
                                <h2>Keep In Touch</h2>
                                <p>How Can We Help Your Business To Grow?</p>
                            </div>
                            <div class="row">
                                <div class="form-group col-lg-12 mb-3">
                                    <label for="exampleInputEmail1">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" id="full_name" class="form-control" placeholder="Enter Your Full Name" required>
                                </div>
                                <div class="col-lg-12 mb-3">
                                    <label for="exampleInputEmail1">Email <span class="text-danger">*</span></label>
                                    <input type="email" id="email" class="form-control" placeholder="Enter Your Email">
                                </div>
{{--                                <div class="col-lg-12 mb-3">--}}
{{--                                    <label for="exampleInputEmail1">Phone Number <span class="text-danger">*</span></label>--}}
{{--                                    <input type="number" id="phone" class="form-control" placeholder="Enter Your Number">--}}
{{--                                </div>--}}
                                <div class="col-lg-12 mb-3">
                                    <label for="textarea1">Your Message</label>
                                    <textarea class="form-control" id="textarea1" rows="3" style="resize: none;"></textarea>
                                </div>
                                <div class="bonus-btn pt-4 d-flex">
                                    <button type="button" class="btn btn-warning custom-btn" id="submiitt">Submit Message</button>
                                    <div id="loader" style="display: none;" class="loader">
                                    <!-- Your loader HTML here -->
                                    </div>
                                </div>
                                <div id="loader" style="display: none;" class="loader">
                                <!-- Your loader HTML here -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5 col-md-5">
                        <div class="contact-right-details">
                            <h3>Get in touch</h3>
                            <p>We're here to help! If you have any questions, please don't hesitate to get in touch. You can reach us by email or by filling out the form on our contact page. We look forward to hearing from you!</p>
                            <div class="mt-4">
                                @if($getCompanyAddress != null)
                               <div class="contact-widgit">
                                    <div class="contact-widgit-circle">
                                       <i class="fontello icon-location-outline"></i>
                                    </div>
                                    <div class="contact-widgit-right">
                                       <h5>Address</h5>
                                       <p>{{$getCompanyAddress}}</p>
                                   </div>
                               </div>
                               @endif
                               @if($getCompanyEmail != null)
                                <div class="contact-widgit">
                                    <div class="contact-widgit-circle">
                                        <i class="fontello icon-mail"></i>
                                    </div>
                                    <div class="contact-widgit-right">
                                        <h5>Email</h5>
                                        <p>{{$getCompanyEmail}}</p>
                                    </div>
                                </div>
                                @endif
                                @if($getCompanyPhone !=null)
                                <div class="contact-widgit">
                                   <div class="contact-widgit-circle">
                                       <i class="fontello icon-phone"></i>
                                    </div>
                                    <div class="contact-widgit-right">
                                      <h5>Phone</h5>
                                        <p>{{$getCompanyPhone}}</p>
                                    </div>
                               </div>
                               @endif
                            </div>
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
