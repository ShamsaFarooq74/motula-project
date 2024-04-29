@extends('web.layouts.master')
@section('content')
<style>
    #alertID{
        position:absolute;
        right:0;
        padding:10px;
        top:6px;
    }
    .course-detail-holder-img{
        width: 100%;
        height: 350px;
        object-fit: cover;
    }
</style>
@include('web.layouts.partials.header')
<!-- tabs-links -->
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <!-- <link rel="stylesheet" href="/resources/demos/style.css"> -->
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<!-- Start Page Content here -->
<div class="content">
    <!-- start page title -->
@section('title', "- Course Detail - $getCourses->course_title");
        <div class="home-page-content">
            <div class="course-banner course-banner-detail">
                <div class="course-banner-text">
                    <h1>TFL SERU Training Course</h1>
                    <p class="course-banner-wordsmarks">Home  >  Courses  >  {{$getCourses->course_title}}</p>
                </div>
            </div>
            <div class="position-relative">
                @if (session()->has('success'))
                <div class="alert alert-success" id="alertID">
                    <a href="#" class="close" data-dismiss="alert"
                        aria-label="close"></a> {{ session('success') }}
                </div>
                @endif
                @if (session()->has('error'))
                    <div class="alert alert-danger" id="alertID">
                        <a href="#" class="close" data-dismiss="alert"
                            aria-label="close"></a> {{ session('error') }}
                    </div>
                @endif
                </div>
            <div class="course-detail-section">
                <div class="course-wrapper">
                    <div class="row">
                        <div class="col-lg-8 col-md-8 py-2">
                            <h1 class="course-title">{{$getCourses->course_title}}</h1>
                            <img src="{{$getCourses->image != null && file_exists(public_path().'/assets/course-attachments/'.$getCourses->image) ? asset('assets/course-attachments/'.$getCourses ->image) : asset('assets/images/emptyBlogs.jpeg')}}" alt="" class="course-detail-holder-img">
                            <div id="tabs" class="p-0 mt-3">
                                <ul>
                                    <li><a href="#tabs-1">Course overview</a></li>
                                    <li><a href="#tabs-2">Course Content</a></li>
                                </ul>
                                <div id="tabs-1">
                                    <div class="course-overview">
                                        <div class="views-detail">
                                            {!! Blade::compileString($getCourses->description) !!}
                                            <!-- {{$getCourses->description}} -->
                                        </div>
                                        <div class="seru-course-detail">
                                            <h2>What you’ll get from this course</h2>
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="circle-vt skill-circle">
                                                    <i class="fontello icon-check"></i>
                                                </div>
                                                <div class="skills-detail">
                                                    <p>Real TfL SERU Questions</p>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="circle-vt skill-circle">
                                                    <i class="fontello icon-check"></i>
                                                </div>
                                                <div class="skills-detail">
                                                    <p>All SERU Modules Covered</p>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="circle-vt skill-circle">
                                                    <i class="fontello icon-check"></i>
                                                </div>
                                                <div class="skills-detail">
                                                    <p>24/7 Access</p>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="circle-vt skill-circle">
                                                    <i class="fontello icon-check"></i>
                                                </div>
                                                <div class="skills-detail">
                                                    <p>Works on PC or Mobile Device</p>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="circle-vt skill-circle">
                                                    <i class="fontello icon-check"></i>
                                                </div>
                                                <div class="skills-detail">
                                                    <p>Learning Materials</p>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="circle-vt skill-circle">
                                                    <i class="fontello icon-check"></i>
                                                </div>
                                                <div class="skills-detail">
                                                    <p>Online SERU Mock Test</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="tabs-2">
                                    <div class="course-content">
                                        <h3>Course Content</h3>
                                        <div class="accordion" id="accordionExample">
                                            @foreach($getSessions as $key=>$session)
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingOne">
                                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{$key}}" aria-expanded="true" aria-controls="collapseOne">
                                                        <div class="d-flex align-items-center mb-1">
                                                            <div class="content-circle">
                                                                <i class="fontello icon-book-open "></i>
                                                            </div>
                                                            <div class="content-intro">
                                                                <h4>Section {{$key + 1}}</h4>
                                                                <p>{{$session->session_name}}</p>
                                                                <div class="d-flex mt-2">
                                                                    <div class="topics_vt d-flex topic-first-vt">
                                                                        <i class="fontello icon-book-alt"></i>
                                                                        <p>{{$session->totalTopics}} Topic</p>
                                                                    </div>
                                                                    <div class="topics_vt d-flex">
                                                                        <i class="fontello icon-lightbulb"></i>
                                                                        <p>{{$session->totalQuizes}} Quiz</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </button>
                                                </h2>
                                                <div id="collapse{{$key}}" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="preview_vt">
                                                            <div class="d-flex expectation_vt align-items-center">
                                                                <div class="accordian-circle"></div>
                                                                <p>SERU Assessment – what to expect</p>
                                                            </div>
                                                            <div class="">
                                                                <button type="button" class="btn btn-warning custom-btn preview-btn" onclick="window.location.href='{{route('course.sessions' ,['id'=>$getCourses->id])}}'">Preview</button>
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
                        <div class="col-lg-4 col-md-4 py-2">
                            <div class="course-fee-details">
                                @if($is_joined == 0)
                                <p><bold>{{$currencySymbol}} {{$getCourses->price}}</bold> /{{$getCourses->duration}} Weeks Access</p>
                                @endif
                                <div class="pt-3 ">
                                    @if($is_joined == 0)
                                    <button type="button" class="btn btn-warning w-100 custom-btn" onclick="window.location.href='{{route('stripe',['id'=>$getCourses->id])}}'">Join Course</button>
                                    @else
                                    <button type="button" class="btn btn-warning w-100 custom-btn" disabled>Joined</button>
                                    @endif
                                </div>
                                <h3>This Course Included</h3>
                                <div class="d-flex align-items-center mb-1">
                                    <div class="fee-icons">
                                        <i class="fontello icon-duration "></i>
                                    </div>
                                    <div class="skills-detail">
                                        <p>Duration: {{$getCourses->duration}} weeks</p>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center mb-1">
                                    <div class="fee-icons">
                                        <i class="fontello icon-access "></i>
                                    </div>
                                    <div class="skills-detail">
                                        <p>Access: 24/7 Access</p>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center mb-1">
                                    <div class="fee-icons">
                                        <i class="fontello icon-modules "></i>
                                    </div>
                                    <div class="skills-detail">
                                        <p>Modules: All TfL SERU Sections Covered</p>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center mb-1">
                                    <div class="fee-icons">
                                        <i class="fontello icon-mocks "></i>
                                    </div>
                                    <div class="skills-detail">
                                        <p>Includes: SERU Mock Test</p>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center mb-1">
                                    <div class="fee-icons">
                                        <i class="fontello icon-bonus "></i>
                                    </div>
                                    <div class="skills-detail">
                                        <p>Bonus: Drag & Drop Questions</p>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center mb-1">
                                    <div class="fee-icons">
                                        <i class="fontello icon-device "></i>
                                    </div>
                                    <div class="skills-detail">
                                        <p>Device: PC & Mobile Friendly</p>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4">
                                <div class="questioning-section">
                                    <div class="email-text">
                                        <h5>Have Any Question?</h5>
                                        <p>If you encounter any issues regarding the Training Course, please do not hesitate to contact us. We would be happy to assist you in any way we can.</p>
                                        <div class="d-flex align-items-center mb-1">
                                            <div class="fee-icons">
                                                <i class="fontello icon-mail "></i>
                                            </div>
                                            <div class="skills-detail">
                                                @php
                                                    $mail = DB::table('settings')->where('perimeter','company_email')->first();
                                                @endphp
                                                <p>{{$mail->value}}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script>
    $( function() {
      $( "#tabs" ).tabs();
    } );
    </script>
    <script type="text/javascript">
    setTimeout(function(){
        $('#alertID').hide('slow')
        }, 2000);
</script>

</html>
@endsection
