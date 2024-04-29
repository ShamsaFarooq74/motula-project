@extends('admin_layouts.master')
@section('content')
@section('title', '- Course Plans')
<style>
    .course-alert{
        position: absolute;
        right: 0;
        top: 0;
    }
</style>
<div>
    <div class="lesson-page-content">
         <div class="course-alert">
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
        <div class="lesson-wrapper">
            <div class="detail-head-arrea border-bottom mb-2">
                @if($getBlogs == null)
                <div>
                    <h1>Add Blog</h1>
                </div>
                @else
                <div>
                    <h1>Update Blog</h1>
                </div>
                @endif
            </div>
            <div>
                <form action="{{route('save.blog')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @if($getBlogs != null)
                        <input type="hidden" name="blog_id" value="{{$getBlogs->id}}">
                    @endif
                    <div class="row mb-2">
                        <div class="col-lg-12 col-md-6 mb-2">
                            <label for="basic-url" class="form-label">Title</label>
                            <div class="input-group">
                                <input type="text" name="blog_title" value="{{$getBlogs != null ? $getBlogs->blog_title : ''}}" class="form-control" id="" placeholder="Enter Blog Title">
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-6 mb-2">
                            <label for="basic-url" class="form-label">Blog Type</label>
                            <div class="input-group">
                                <input type="text" name="blog_type" value="{{$getBlogs != null ? $getBlogs->blog_type : ''}}" class="form-control" id="" placeholder="Enter Blog Title">
                            </div>
                        </div>
                        
                        <div class="col-lg-6 col-md-6 mb-2">
                            <label for="basic-url" class="form-label">Blog Image</label>
                            <div class="input-group">
                                <input type="file" name="blog_image" class="form-control">
                                @if($getBlogs != null)
                                    <img class="blog-inner-img" src="{{$getBlogs != null ? asset('assets/blogs-attachments/'.$getBlogs->image) : ''}}">
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 mb-2">
                            <label class="form-label" for="textarea">Description</label>
                            <textarea class="summernote" name="content" placeholder="Enter Description" id="edit_summernote"></textarea>
                        </div>
                        <div class="d-flex align-items-center mb-2 mt-3">
                                <label for="basic-url" class="form-label mb-0 pe-2">Active Status</label>
                                <label class="switch">
                                    <input type="checkbox" name="status" {{$getBlogs != null && $getBlogs->is_active == '1' ? 'checked' : ''}}>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                    </div>
                    <div class="mt-3 mb-2">
                        @if($getBlogs == null)
                        <button type="submit" class="btn btn-warning custom-btn">Add</button>
                        @else
                        <button type="submit" class="btn btn-warning custom-btn">Update</button>

                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>


<script>
    $(document).ready(function() {
        $('.summernote').summernote({
            callbacks: {
                onPaste: function(e) {
                    var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData)
                        .getData('Text');

                    // Insert the pasted content as plain text without filtering
                    e.preventDefault();
                    document.execCommand('insertText', false, bufferText);
                }
            },
            height: 100,
        });

    });
</script>
<script type="text/javascript">
    var addSummernotDescription = {!! $getBlogs !== null ? $getBlogs : 'null' !!};
    if(addSummernotDescription !== null){
        var summernoteInstance = $('#edit_summernote').summernote();
        summernoteInstance.summernote('code',addSummernotDescription['description']);
    }
    setTimeout(function(){ 
        $('#alertID').hide('slow')
        }, 2000);
</script>
@endsection