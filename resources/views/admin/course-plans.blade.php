@extends('admin_layouts.master')
@section('content')
@section('title', '- Course Plans')
<style>
    .course-alert{
        position: absolute;
        right: 0;
        top: 0;
    }
    .note-editor.fullscreen {
        background-color: #f2f2f2; 
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
            <div class="detail-head-arrea">
                <div>
                    <h1>Course Plans</h1>
                </div>
                <div class="d-flex">
                    <div>
                        <button type="button" class="btn btn-warning custom-btn" data-bs-toggle="modal" data-bs-target="#CoursePlanModal">Create New</button>
                    </div>
                </div>
            </div>
            <div class="table-holder_vt">
                <table id="example" class="table table-striped bg-white pt-3 w-100">
                    <thead class="tableOfhead">
                        <tr>
                        <th scope="col">Sr</th>
                        <th scope="col">Course Title</th>
                        <th scope="col">Type</th>
                        <th scope="col">Duration</th>
                        <th scope="col">Price</th>
                        <th scope="col">Status</th>
                        <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody class="tableOfContent">
                        @foreach($getCourses as $key=>$course)
                        <tr>
                            <td>{{$key + 1}}</td>
                            <td>{{$course->course_title}}</td>
                            <td>{{$course->course_type}}</td>
                            <td>{{$course->duration}} Weeks</td>
                            <td>{{$currencySymbol}}{{$course->price}}</td>
                            <td class="{{$course->is_active == '1' ? 'success_vt' : 'pending_vt'}}">{{$course->is_active == '1' ? 'Active' : 'Inactive'}}</td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn header-btn-arrow dropdown-toggle p-0 table-action-icon" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                    </button>
                                    <ul class="dropdown-menu header-menu" aria-labelledby="dropdownMenuButton1">
                                    <li><a class="dropdown-item" href="#" onclick="getCourseData('{{$course->id}}')"><i class="fontello icon-edit2 pr-10"></i>Edit</a></li>
                                    <!-- <li><a class="dropdown-item" href="#" onclick="getCourseData('{{$course->id}}')" data-bs-toggle="modal" data-bs-target="#EditCoursePlanModal"><i class="fontello icon-edit2 pr-10"></i>Edit</a></li> -->
                                    <li><a class="dropdown-item text-danger" href="{{route('delete.course',['id'=>$course->id])}}"><i class="fontello icon-trash-1 pr-10"></i>Delete</a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="CoursePlanModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog right-modal_vt">
            <div class="modal-content right-modal-content_vt" style="height:100%;">
            <div class="modal-header right-modal-head">
                <h5 class="modal-title" id="exampleModalLabel">New Course Plan</h5>
                <div class="close_vt" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fontello icon-cancel text-white"></i>
                    <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
                </div>
            </div>
            <div class="modal-body">
                <div class="right-modal-content">
                    <form action="{{route('save.course')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                        <label for="basic-url" class="form-label">Course Title</label>
                        <div class="input-group mb-2">
                            <input type="text" name="course_title" value="{{ old('course_title') }}" class="form-control modal-input" id="" placeholder="Enter Course Title">
                        </div>
                        <label for="basic-url" class="form-label">Course Subtitle</label>
                        <div class="input-group mb-2">
                            <input type="text" name="subtitle" value="{{ old('subtitle') }}" class="form-control modal-input" id="" placeholder="Enter Course Subtitle">
                        </div>
                        <label for="basic-url" class="form-label">Duration <span class="light-gray">(Weeks)</span></label>
                        <div class="input-group mb-2">
                            <input type="number" name="duration" value="{{ old('duration') }}" class="form-control modal-input" id="" placeholder="Enter Course Duration">
                        </div>
                        <label for="basic-url" class="form-label">Course Type</label>
                        <div class="input-group mb-2">
                        <input type="text" name="course_type" value="{{ old('course_type') }}" class="form-control modal-input" id="" placeholder="Enter Course Type">
                        </div>
                        <label for="basic-url" class="form-label">Image</label>
                        <div class="input-group mb-2">
                            <input type="file" name="image" value="{{ old('image') }}" class="form-control modal-input" accept="image/gif,image/jpeg,image/jpg,image/png">
                        </div>
                        <label for="basic-url" class="form-label">Price</label>
                        <div class="input-group mb-2">
                        <input type="number" step="0.01" name="price" value="{{ old('price') }}" class="form-control modal-input" id="" placeholder="Enter Course Price">
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label for="basic-url" class="form-label mb-0">Active Status</label>
                            <label class="switch">
                                <input type="checkbox" name="status">
                                <span class="slider round"></span>
                            </label>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Description</label>
                            <textarea id="summernote" name="description" value="{{ old('description') }}" placeholder="Enter Description">{{ old('description') }}</textarea>
                            <!-- <div id="summernote"></div> -->
                        </div>
                        <div>
                            <button type="submit" class="btn btn-warning custom-btn">Add Course</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        </div>
    </div>

    <div class="modal fade" id="EditCoursePlanModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog right-modal_vt">
            <div class="modal-content right-modal-content_vt">
            <div class="modal-header right-modal-head">
                <h5 class="modal-title" id="exampleModalLabel">Update New Course Plan</h5>
                <div class="close_vt" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fontello icon-cancel text-white"></i>
                    <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
                </div>
            </div>
            <div class="modal-body">
                <div class="right-modal-content">
                    <form action="{{route('save.course')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="course_id" id="edit_course_id">
                        <label for="basic-url" class="form-label">Course Title</label>
                        <div class="input-group mb-2">
                            <input type="text" name="course_title" class="form-control modal-input" id="edit_course_title" placeholder="Enter Course Title">
                        </div>
                        <label for="basic-url" class="form-label">Course Subtitle</label>
                        <div class="input-group mb-2">
                            <input type="text" name="subtitle" class="form-control modal-input" id="edit_subtitle" placeholder="Enter Course Subtitle">
                        </div>
                        <label for="basic-url" class="form-label">Duration <span class="light-gray">(Weeks)</span></label>
                        <div class="input-group mb-2">
                            <input type="text" name="duration" class="form-control modal-input" id="edit_duration" placeholder="Enter Course Duration">
                        </div>
                        <label for="basic-url" class="form-label">Course Type</label>
                        <div class="input-group mb-2">
                        <input type="text" name="course_type" class="form-control modal-input" id="edit_course_type" placeholder="Enter Course Type">
                        </div>
                        <label for="basic-url" class="form-label">Image</label>
                        <div class="input-group mb-2">
                            <input type="file" name="image" id="edit_image"class="form-control modal-input">
                        </div>
                        <label for="basic-url" class="form-label">Price</label>
                        <div class="input-group mb-2">
                        <input type="text" name="price" class="form-control modal-input" id="edit_price" placeholder="Enter Course Price">
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label for="basic-url" class="form-label mb-0">Active Status</label>
                            <label class="switch">
                                <input type="checkbox" id="edit_status" name="status">
                                <span class="slider round"></span>
                            </label>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Description</label>
                        <textarea class="summernote" name="description" id="edit_summernote" placeholder="Enter Description"></textarea>
                        <!-- <div id="edit_summernote"></div> -->
                        </div>
                        <!-- <div class="mb-4">
                            <label class="form-label" for="textarea">Description</label>

                        <div> -->
                            <button type="submit" class="btn btn-warning custom-btn">Update</button>
                    </form>
                </div>
            </div>
            </div>

            </div>
        </div>
    </div>





<script>
    $('#summernote').summernote({
        placeholder: 'Enter Course Description',
        tabsize: 2,
        height: 120,
        toolbar: [
          ['style', ['style']],
          ['font', ['bold', 'underline', 'clear']],
          ['color', ['color']],
          ['para', ['ol', 'paragraph']],
          ['table', ['table']],
          ['insert', ['link', 'picture', 'video']],
          ['view', ['fullscreen', 'codeview', 'help']]
        ]
      });

    //   $('#edit_summernote').summernote({
    //     placeholder: 'Enter Description',
    //     tabsize: 2,
    //     height: 100,
    //   });

    // $(document).ready(function() {
    //   $('.summernote').summernote(
    //     {
    //         height:100,
    //         toolbar: [
    //         [ 'style', [ 'style' ] ],
    //         [ 'font', [ 'bold', 'italic', 'underline', 'strikethrough'] ],
    //         [ 'fontname', [ 'fontname' ] ],
    //         [ 'fontsize', [ 'fontsize' ] ],
    //         [ 'color', [ 'color' ] ],
    //         [ 'para', [ 'ol', 'ul', 'paragraph', 'height' ] ],
    //         [ 'table', [ 'table' ] ],
    //         [ 'insert', [ 'link'] ],
    //         [ 'view', [ 'codeview', 'help' ] ]
    //     ]
    //     });

    // });
</script>
<script type="text/javascript">
         $('#example').DataTable( {
        dom: 'Bfrtip',
        buttons: [
            // {
            //     extend: 'copyHtml5',
            //     exportOptions: {
            //         columns: [ 0, ':visible' ]
            //     }
            // },
            {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: 'th:not(:last-child)'
                }
            },
            {
                extend: 'pdfHtml5',
                exportOptions: {
                    columns: 'th:not(:last-child)'
                }
            },
              {
                extend: 'csvHtml5',
                exportOptions: {
                    columns: 'th:not(:last-child)'
                }
            },
            'colvis'
        ]
    } );
    setTimeout(function(){
        $('#alertID').hide('slow')
        }, 2000);

        function getCourseData(id){
            $.ajax({
            url: "{{route('edit.course')}}",
            type: "POST",
            data: {
                course_id: id,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {

                    var model = new bootstrap.Modal(document.getElementById("EditCoursePlanModal"));
                    model.show();
                    document.getElementById('edit_course_id').value = response.data.id;
                    document.getElementById('edit_course_title').value = response.data.course_title;
                    document.getElementById('edit_subtitle').value = response.data.sub_title;
                    document.getElementById('edit_duration').value = response.data.duration;
                    document.getElementById('edit_course_type').value = response.data.course_type;
                    // document.getElementById('edit_image').innerHTML = response.data.course_title;
                    document.getElementById('edit_price').value = response.data.price;
                    document.getElementById('edit_status').checked = response.data.is_active == '1' ? true: false;
                    var summernoteInstance = $('#edit_summernote').summernote({
        placeholder: 'Enter Course Description',
        tabsize: 2,
        height: 120,
        toolbar: [
          ['style', ['style']],
          ['font', ['bold', 'underline', 'clear']],
          ['color', ['color']],
          ['para', ['ol', 'paragraph']],
          ['table', ['table']],
          ['insert', ['link', 'picture', 'video']],
          ['view', ['fullscreen', 'codeview', 'help']]
        ]
      });
                    summernoteInstance.summernote('code',response.data.description);

                }
            }
        })
        }
</script>
</div>
@endsection
