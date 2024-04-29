@extends('web.layouts.master')
@section('content')
@include('web.layouts.partials.header')
<!-- Start Page Content here -->
<div class="content">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="banner_sub_vt">
                <div class="banner_sub_home">
                    <title>Seru</title>
                </div>
            </div>
        </div>
</div>
    <!-- end page title -->
        <div class="home-page-content">
        <a class="button-go-top d-none training-para" onclick="scrollToTop()" id="back-to-up">
            GO TO TOP
        </a>
        <div class="alert-messages">
            <div class="alert alert-danger" id="error" style="display: none;"></div>
            <div class="alert alert-success" id="successAuth" style="display: none;"></div>
        </div>
        <div class="banner-content">
                <div class="position-relative">
                    <img src="{{asset('assets/images/'.$setting[22]['value'])}}" class="w-100 banner-main-img" alt="">
                    <img src="{{asset('assets/images/banner1.png')}}" class="w-100 tabs-view-img" alt="">
                    <div class="row banner-details_vt">
                        <div class="col-lg-5 col-md-7 col-sm-12">
                            <h1>Get Certified with SERU Assessment Online</h1>
                            <p class="banner-para mb-4 training-para">Elevate Your Skills with SERU Assessment - Available 24/7</p>
                            <div class="d-flex mobile-view-btn">
                                <button type="button" class="btn btn-warning custom-btn me-3">Try Free Questions</button>
                                <button type="button" class="btn btn-outline-warning custom-btn custom-btn-outline" onclick="window.location.href='{{route('all.courses')}}'">view courses</button>
                            </div>
                        </div>
                        <!-- <div class="col-lg-7 col-md-7 col-sm-7"></div> -->
                    </div>
                </div>
            </div>
            <div class="training-section">
                <div class="seru-training-detail">
                    <p class="training-para banner-para">Training Courses</p>
                    <h1>Online SERU Assessment Training Courses</h1>
                    <div class="training-btn-wrappe">
                        <p>100% Pass Rate | Real Questions | Bonus Access Included</p>
                        <button type="button" class="btn btn-warning custom-btn" onclick="window.location.href='{{route('all.courses')}}'">View Courses</button>
                    </div>
                    <div class="row pt-4">
                        @foreach($getCourses as $course)
                        <div class="col-lg-4 col-md-4 py-2">
                            <div class="card card-content">
                                <div class="card-img-wrape">
                                    <a href="{{route('course.detail' ,['id'=>$course->id])}}"><img src="{{$course->image != null && file_exists(public_path().'/assets/course-attachments/'.$course->image) ? asset('assets/course-attachments/'.$course->image) : asset('assets/images/emptyBlogs.jpeg')}}" class="card-img-top w-100" alt="..." style="height:220px;"></a>
                                    <!-- <button type="button" class="btn btn-warning custom-btn">${{$course->price}}</button> -->
                                    <div class="price-section"><p class="price-text_vt">{{$currencySymbol}}{{$course->price}}</p></div>
                                </div>
                                <div class="card-body">
                                    <div class="border-bottom" style="min-height:120px;">
                                        <span>{{$course->course_type}}</span>
                                        <a href="{{route('course.detail' ,['id'=>$course->id])}}"><h2 class="card-title">{{$course->course_title}}</h2></a>
                                        <p class="card-text mb-2">{{$course->sub_title}}</p>
                                    </div>
                                    <div class="d-flex card-duration">
                                        <i class="fontello icon-clock-1"></i>
                                        <p>Duration : {{$course->duration}} Weeks</p>
                                    </div>
                                    <div class="d-flex card-duration">
                                        <i class="fontello icon-dollar-1"></i>
                                        <p>Price : {{$currencySymbol}}{{$course->price}}</p>
                                    </div>
                                    <div class="pt-3">
                                        <a class="btn btn-warning w-100 custom-btn" onclick="window.location.href='{{route('course.detail' ,['id'=>$course->id])}}'">See more</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="yellow-detail-wrapper">
                <div class="row mx-0">
                    <div class="col-lg-12 px-0 position-relative">
                        <div class="yello-banner-bg"></div>
                        <div class="student-detail">
                            <div class="row">
                                <div class="col-lg-4 col-md-4 my-2">
                                    <div class="student-detail-widgit">
                                        <div class="d-flex">
                                            <div class="circle-vt">
                                                <i class="fontello icon-student-cap"></i>
                                            </div>
                                            <div class="student-data">
                                                <h3>{{$usersCount}}</h3>
                                                <p>REGISTERED STUDENTS</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 my-2">
                                    <div class="student-detail-widgit">
                                        <div class="d-flex">
                                            <div class="circle-vt">
                                                <i class="fontello icon-hours"></i>
                                            </div>
                                            <div class="student-data">
                                                <h3>24</h3>
                                                <p>HOURS ACCESS</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 my-2">
                                    <div class="student-detail-widgit">
                                        <div class="d-flex">
                                            <div class="circle-vt">
                                                <i class="fontello icon-passing-rate"></i>
                                            </div>
                                            <div class="student-data">
                                                <h3>100%</h3>
                                                <p>PASSING RATE</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="skill-wrapper">
                <div class="row mx-0">
                    <div class="col-lg-12 px-0">
                        <div class="skills">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 ">
                                    <img src="{{asset('assets/images/skill.png')}}" class="w-100" alt="">
                                </div>
                                <div class="col-lg-6 col-md-6">
                                    <div class="drive-content">
                                        <p class="training-para banner-para">Pass and Start Driving</p>
                                        <h3>Acquire Essential Skills for Today's Success.</h3>
                                        <p class="drive-detail">Maximize your assessment success with SERU's online training. Access real exam questions, mock tests, and free taster questions anytime, anywhere.</p>
                                        <div class="skills-widget">
                                            <div class="d-flex align-items-center skill-widget-section">
                                                <div class="circle-vt skill-circle">
                                                    <i class="fontello icon-check"></i>
                                                </div>
                                                <div class="skills-detail">
                                                    <p>24/7 Access from all devices</p>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center skill-widget-section">
                                                <div class="circle-vt skill-circle">
                                                    <i class="fontello icon-check"></i>
                                                </div>
                                                <div class="skills-detail">
                                                    <p>Real Exam Questions</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="skills-widget">
                                            <div class="d-flex align-items-center skill-widget-section">
                                                <div class="circle-vt skill-circle">
                                                    <i class="fontello icon-check"></i>
                                                </div>
                                                <div class="skills-detail">
                                                    <p>Free Taster Questions</p>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center skill-widget-section">
                                                <div class="circle-vt skill-circle">
                                                    <i class="fontello icon-check"></i>
                                                </div>
                                                <div class="skills-detail">
                                                    <p>Mock Test</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="skills-widget">
                                            <div class="d-flex align-items-center skill-widget-section">
                                                <div class="circle-vt skill-circle">
                                                    <i class="fontello icon-check"></i>
                                                </div>
                                                <div class="skills-detail">
                                                    <p>Additional FREE access before Actual Test</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="skills-btn">
                                            <button type="button" class="btn btn-warning custom-btn" onclick="window.location.href='{{route('all.courses')}}'">start now for free</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mx-0">
                <div class="col-lg-12 px-0">
                    <div class="bonus-wrapper">
                        <div class="row align-items-center">
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="bonus-content">
                                    <p class="training-para banner-para">Additional Access</p>
                                    <h1>Get Free Training Bonus Access</h1>
                                    <p class="bonus-detail">We offer free additional access with all our courses. Regardless of the course length you choose, we provide an extra 4-week access before your TfL SERU assessment. To request more access, simply fill out the form in your profile section. This benefit is available to all course purchasers.</p>
                                    <div class="bonus-btn pt-4">
                                        <button type="button" class="btn btn-warning custom-btn" onclick="window.location.href='{{route('all.courses')}}'">See All Courses</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="Registration-form registration-mobile-view">
                                    <div class="contact-details mb-4">
                                        <h2>Keep In Touch</h2>
                                        <p>How Can We Help Your Business To Grow?</p>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 mb-3">
                                            <input type="name" name="name" id="full_name" class="form-control" placeholder="Full Name" required>
                                        </div>
                                        <div class="col-lg-12 mb-3">
                                            <input type="email" name="email" id="email" class="form-control" placeholder="Email Address" required>
                                        </div>
{{--                                        <div class="col-lg-12 mb-3">--}}
{{--                                            <input type="number" name="phone" id="phone" class="form-control" placeholder="Phone Number" required>--}}
{{--                                        </div>--}}
                                        <div class="col-lg-12 mb-3">
                                            <textarea class="form-control" name="text" id="textarea1" placeholder="Your Message" required row=5  style="resize:none; height:100px;"></textarea>
                                        </div>
                                        <div class="bonus-btn pt-4 d-flex">
                                            <button type="submit" class="btn btn-warning custom-btn" id="submiitt">Submit message</button>
                                            <div id="loader" style="display: none;" class="loader">
                                            <!-- Your loader HTML here -->
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="training-section">
                <div class="seru-training-detail">
                    <p class="training-para banner-para">Our Blogs</p>
                    <h1>Every Single day update here About Driving</h1>
                    <div class="training-btn-wrappe">
                        <p>Every Single day update here About Driving" very short description based on the mentioned title</p>
                        <button type="button" class="btn btn-warning custom-btn" onclick="window.location.href='{{route('all.blogs')}}'">See All Blogs</button>
                    </div>
                    <div class="row pt-4">
                        @foreach($getBlogs as $blog)
                        <div class="col-lg-4 col-md-4 my-2">
                            <div class="card card-content">
                                <div class="driving-card-img">
                                    <a href="{{ route('blog.detail', ['id' => $blog->id]) }}"><img src="{{$blog->image != null ? asset('assets/blogs-attachments/'.$blog->image) : asset('assets/images/blog1.png')}}" class="card-img-top w-100" alt="..." style="height:220px;">
                                    <button type="button" class="btn btn-warning custom-btn" style="border-right:3px solid #fff; border-bottom:3px solid #fff; cursor:text;">{{$blog->blog_type}}</button>
                                </div>
                                <div class="card-body">
                                    <div class="Driving-content">
                                        <a href="{{ route('blog.detail', ['id' => $blog->id]) }}"><h2 class="card-title">{{$blog->blog_title}}</h2></a>
                                        <small>{{$blog->date}}</small>
                                        <p class="card-text mb-2 pt-2">{{ $blog->description }}</p>
                                        <div class="d-flex card-links align-items-center pt-3">
                                            <a href="{{ route('blog.detail', ['id' => $blog->id]) }}">Read More</a>
                                            <i class="fontello icon-angle-right"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.onscroll = () => {
            toggleTopButton();
            }
            function scrollToTop(){
            window.scrollTo({top: 0, behavior: 'smooth'});
            }

            function toggleTopButton() {
            if (document.body.scrollTop > 500 ||
                document.documentElement.scrollTop > 500) {
                document.getElementById('back-to-up').classList.remove('d-none');
            } else {
                document.getElementById('back-to-up').classList.add('d-none');
            }
        }
    </script>
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
                        if (response.message) {
                            $('#loader').hide();
                            $("#successAuth").text('Your Message has been sent');
                            $("#successAuth").show();
                            // setTimeout(function(){
                            // $('#error').hide('slow')
                            // }, 2000);
                        setTimeout(function(){
                            $('#successAuth').hide('slow')
                            }, 2000);

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

@endsection
