@extends('web.layouts.master')
@section('content')
@include('web.layouts.partials.header')     
<div class="content">
    <!-- start page title -->
@section('title', '- Course List');
        <div class="home-page-content">
            <div class="course-banner">
                <div class="course-banner-text">
                    <h1>TFL SERU Training Course</h1>
                    <p class="course-banner-wordsmarks">Home  >  Courses</p>
                </div>
            </div>
            <div class="training-section">
                <div class="seru-training-detail"> 
                    <div class="row">
                        @forelse($getCourses as $course)
                        <div class="col-lg-4 col-md-4 py-2">
                            <div class="card card-content">
                                <div class="card-img-wrape">
                                    <img src="{{$course->image != null && file_exists(public_path().'/assets/course-attachments/'.$course->image) ? asset('assets/course-attachments/'.$course->image) : asset('assets/images/emptyBlogs.jpeg')}}" class="card-img-top w-100" alt="..." style="height:220px;">
                                    <button type="button" class="btn btn-warning custom-btn" style="cursor:text;">{{$currencySymbol}} {{$course->price}}</button>
                                </div>
                                <div class="card-body d-flex flex-column">
                                    <div class="border-bottom" style="min-height:120px;">
                                        <span>{{$course->course_type}}</span>
                                        <h2 class="card-title">{{$course->course_title}}</h2>
                                        <p class="card-text mb-2">{{$course->sub_title}}</p>
                                    </div>
                                    <div class="d-flex card-duration">
                                        <i class="fontello icon-clock-1"></i>
                                        <p><bold class="text-dark fw-bold">Duration :</bold> {{$course->duration}} Weeks</p>
                                    </div>
                                    <div class="d-flex card-duration">
                                        <i class="fontello icon-dollar-1"></i>
                                        <p ><bold class="text-dark fw-bold">Price :</bold> {{$currencySymbol}}{{$course->price}}</p>
                                    </div>
                                    <div class="pt-3">
                                        <a href="{{route('course.detail' ,['id'=>$course->id])}}" class="btn btn-warning w-100 custom-btn">See More</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                            <div class="course-detail-img">
                                <img src="{{asset('assets/images/No-course.png')}}" alt="">
                                <p>No Course Found!</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
            {{ $getCourses->onEachSide(1)->links()}}
        </div>
    </div>
@endsection
