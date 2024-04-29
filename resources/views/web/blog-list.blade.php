@extends('web.layouts.master')
@section('content')
@include('web.layouts.partials.header') 
<!-- Start Page Content here -->
<div class="content">
    <!-- start page title -->
    @section('title', '- Blog Lists');
    <div class="container-fluid px-0">        
        <div class="home-page-content">
            <div class="course-banner blog-banner">
                <div class="course-banner-text">
                    <h1>SERU Blogs</h1>
                    <p class="course-banner-wordsmarks">Home  >  Blogs</p>
                </div>
            </div>
            <div class="training-section">
                <div class="seru-training-detail">
                    <div class="row pt-4">
                        @forelse($getBlogs as $blog)
                        <div class="col-lg-4 col-md-4 my-2">
                            <div class="card card-content">
                                <div class="driving-card-img">
                                    <img src="{{$blog->image != null ? asset('assets/blogs-attachments/'.$blog->image) : asset('assets/images/blog1.png')}}" class="card-img-top w-100" alt="..." style="height:220px;">
                                    <button type="button" class="btn btn-warning custom-btn">{{$blog->blog_type}}</button>
                                </div>
                                <div class="card-body card-content-wrape">
                                    <div class="Driving-content">
                                        <h2 class="card-title">{{$blog->blog_title}}</h2>
                                        <small>{{$blog->date}}</small>
                                        <p class="card-text mb-2 pt-2">{{$blog->description }}</p>
                                        <div class="d-flex card-links align-items-center pt-3">
                                            <a href="{{ route('blog.detail', ['id' => $blog->id]) }}">Read More</a>
                                            <i class="fontello icon-angle-right"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="course-detail-img">
                                <img src="{{asset('assets/images/emptyBlogs.jpeg')}}" alt="">
                                <p>No Blogs Found!</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
@endsection