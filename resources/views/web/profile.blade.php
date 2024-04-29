@extends(Auth::user()->role == '2'? 'web.layouts.master' : 'admin_layouts.master')
@section('content')
@if(Auth::user()->role == '2')
    @include('web.layouts.partials.header')
@endif
@section('title', '- Profile')

    <div class="container-fluid px-0">    
        
        <div class="container-fluid">
            <div class="profile-page-content">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="profile-details text-center mb-2">
                            <div class="img-holder_vt">
                                @if(Auth::user()->role == '2')
                                    <img src="{{Auth::user()->image != null && file_exists(public_path().'/assets/profile-pic/'.Auth::user()->image) ? asset('assets/profile-pic/'.Auth::user()->image) : asset('assets/images/default-user.png')}}" alt="" srcset="" class="img-circle-profile">
                                @else
                                    <img src="{{$userDetail->image != null && file_exists(public_path().'/assets/profile-pic/'.$userDetail->image) ? asset('assets/profile-pic/'.$userDetail->image) : asset('assets/images/default-user.png')}}" alt="" srcset="" class="img-circle-profile">

                                @endif
                                    <!-- <label>
                                    <input type="file" name="myImage" accept="image/*" />
                                </label> -->
                            </div>
                            <h1>{{$userDetail->full_name}}</h1>
                            <p>{{$userDetail->email}}</p>
                            
                            <div class="widget-overviews-area">
                                <div class="widget-overviews-box">
                                    <h2>{{count($userCourses)}}</h2>
                                    <p>Enrolled Courses</p>
                                </div>
                                <div class="widget-overviews-box">
                                    <h2>{{$userCertificates}}</h2>
                                    <p>Completed</p>
                                </div>
                                <!-- <div class="widget-overviews-box">
                                    <h2>{{$userCertificates}}</h2>
                                    <p>Certificates</p>
                                </div> -->
                                <div class="widget-overviews-box">
                                    <h2>{{$course_points}}</h2>
                                    <p>Points</p>
                                </div>
                            </div>
                            @if(Auth::user()->role == '2')
                            <div class="mt-3">
                                <a href="{{route('update.profile')}}"><button type="button" class="btn btn-warning custom-btn profile-btn w-100">Edit Profile</button></a>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="quiz-taken-area">
                            <h3>Your Course</h3>
                            <div class="quiz-content">
                                @forelse($userCourses as $course)
                                <div class="py-3 border-bottom">
                                    <div class="quiz-taken-btn">
                                    <div>
                                        <h4>{{$course->courseDetail->course_title}}</h4>
                                    </div>
                                    <div class="d-flex mt-2 mb-2">
                                        @if(Auth::user()->role == '2')
                                            <a href="{{route('user.profile.view',['id'=>$course->courseDetail->id])}}"><button type="button" class="btn btn-warning custom-btn profile-btn custom-btn-outline me-2">View Details</button></a>
                                        @else
                                            <a href="{{route('users.profile.detail',['id'=>$course->courseDetail->id,'user_id'=>$userDetail->id])}}"><button type="button" class="btn btn-warning custom-btn profile-btn custom-btn-outline me-2">View Details</button></a>
                                        @endif
                                        </div>
                                    </div>
                                    <p>{{$course->courseDetail->sub_title}}</p>
                                    <div class="d-flex align-items-center time-schedule mt-2">
                                        <i class="fontello icon-clock-1"></i>
                                        <p><bold style="color: #1B1B1E; font-weight: 700;">Duration :</bold> {{$course->courseDetail->duration}} Weeks</p>
                                    </div>
                                    <div class="d-flex align-items-center progress-section mt-2">
                                        <h5>Course Progress</h5>
                                        <p>{{count($course->userAttemptedQuizes)}} Sections Completed</p>
                                    </div>
                                    <div class="row mt-3 align-items-center">
                                        <div class="col-lg-9 col-md-9 col-sm-8">
                                            <div class="progress quiz-progress_vt">
                                                <div class="progress-bar quiz-progress-bar_vt" role="progressbar" style="width: {{ round((count($course->userAttemptedQuizes) / count($course->getQuizes)) * 100) }}%" aria-valuenow="{{ round((count($course->userAttemptedQuizes) / count($course->getQuizes)) * 100) }}" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-4">
                                            <p style="font-weight: 700;">{{round((count($course->userAttemptedQuizes) / count($course->getQuizes)) * 100)}}% Completed</p>
                                        </div>
                                    </div>
                                    
                                </div>
                                @empty<div class="course-detail-img">
                                        <img src="{{asset('assets/images/emptyBlogs.jpeg')}}" style="height:100px; width:100px"alt="">
                                        <p>No Courses Found!</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                        <!-- <div class="quiz-taken-area">
                            <h3>You have taken the following quizzes</h3>
                            <div class="quiz-content">
                                @foreach($userCourses as $course)
                                    @foreach($course->userAttemptedQuizes as $quiz)
                                        <div class="quiz-taken-btn">
                                            <h4>{{$quiz->quiz_title}}</h4>
                                            <button type="button" class="btn btn-warning custom-btn profile-btn" data-bs-toggle="modal" data-bs-target="#exampleModal">View Details</button>
                                        </div>
                                        <p>Score 5 out of 6 question(s) on 8 April  2023 9:22 am</p>
                                        <div class="d-flex align-items-center time-schedule mt-2">
                                            <i class="fontello icon-statistics"></i>
                                            <p style="color: #00E540;"><bold style="color: #1B1B1E; font-weight: 700;">Statistics :</bold> {{$quiz->stats}}%</p>
                                        </div>
                                        <div class="d-flex align-items-center time-schedule mt-2">
                                            <i class="fontello icon-point"></i>
                                            <p><bold style="color: #1B1B1E; font-weight: 700;">Points :</bold> 7/10</p>
                                        </div>
                                    @endforeach
                                @endforeach
                            </div>
                        </div> -->
                    </div>
                </div>
            </div>
            
        </div>

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal_vt">
            <div class="modal-content">
                <div class="modal-header">
                <div class="modal-head">
                    <h5 class="modal-title" id="exampleModalLabel">Statistics For Licensing Requirements Quiz</h5>
                    <p>April 8, 2023 9:22 am</p>
                </div>
                <button type="button" class="btn btn-warning custom-btn profile-btn" data-bs-dismiss="modal" aria-label="Close">Close</button>
                </div>
                <div class="modal-body">
                    <div style="overflow:auto;">         
                        <table class="table">
                            <thead class="profile-popup-head">
                                <tr>
                                    <th>Question</th>
                                    <th>Question Status</th>
                                    <th>Points</th>
                                    <th>Time Taken (hh:mm:ss)</th>
                                    <th>Points Scored</th>
                                </tr>
                            </thead>
                            <tbody class="profile-popup-content">
                                <tr>
                                    <td>1. If you want to be a London PHV driver and want to carry out bookings from a licensed operator. Then you must</td>
                                    <td class="text-danger">Incorrect</td>
                                    <td>1</td>
                                    <td>00:00:20</td>
                                    <td>1</td>
                                </tr>
                                <tr>
                                    <td>1. If you want to be a London PHV driver and want to carry out bookings from a licensed operator. Then you must</td>
                                    <td class="text-green">Correct</td>
                                    <td>1</td>
                                    <td>00:00:20</td>
                                    <td>1</td>
                                </tr>
                                <tr>
                                    <td>1. If you want to be a London PHV driver and want to carry out bookings from a licensed operator. Then you must</td>
                                    <td><p class="text-green">Correct</p></td>
                                    <td>1</td>
                                    <td>00:00:20</td>
                                    <td>1</td>
                                </tr>
                            </tbody>
                            <tfoot class="profile-popup-content tfoot_vt">
                                <tr>
                                    <td>Final Result</td>
                                    <td class="d-flex"><p class="text-green pr-10">87%</p> | <p class="text-danger pl-10">13%</p></td>
                                    <td>1</td>
                                    <td>00:00:20</td>
                                    <td>1</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
</body>

</html>
@endsection