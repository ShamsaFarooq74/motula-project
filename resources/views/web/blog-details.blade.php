
@extends('web.layouts.master')
@section('content')
@include('web.layouts.partials.header')
<!-- Start Page Content here -->
<div class="content">
    <!-- start page title -->
    @section('title', '- Blog Detail - '. $blog->blog_title);
    <div class="container-fluid px-0">        
        <div class="blog-details-content">
            <img src="{{$blog->image != null ? asset('assets/blogs-attachments/'.$blog->image) : asset('assets/images/blog1.png')}}" class="w-100 border border-3" alt="" srcset="">
            <div class="mt-2">
                <span>{{$blog->blog_type}}</span>
            </div>
            <h1>{{$blog->blog_title}}</h1>
            <h6>{{$blog->date}}</h6>
            <!-- <div class="container"> -->
                {!! Blade::compileString($blog->description) !!}
            <!-- </div> -->
        </div>
    </div>
@endsection