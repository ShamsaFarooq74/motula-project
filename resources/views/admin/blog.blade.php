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
            <div class="detail-head-arrea">
                <div>
                    <h1>Blog Listing</h1>
                </div>
                <div class="d-flex">
                    <div>
                        <a href="{{route('add.blog')}}"><button type="button" class="btn btn-warning custom-btn" data-bs-toggle="modal"
                            data-bs-target="#quizModal">Add New</button>
                        </a>
                    </div>
                </div>
            </div>
            <div class="table-holder_vt">
                <table id="example" class="table table-striped bg-white pt-3 w-100">
                    <thead class="tableOfhead">
                        <tr>
                            <th scope="col">Sr</th>
                            <th scope="col">Blog Title</th>
                            <th scope="col">Image</th>
                            <th scope="col">Date</th>
                            <th scope="col">Status</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody class="tableOfContent">
                        @foreach($getBlogs as $key=>$blog)
                        <tr>
                            <td>{{$key + 1}}</td>
                            <td>{{$blog->blog_title}}</td>
                            <td><img class="blog-table-img" src="{{asset('assets/blogs-attachments/'.$blog->image)}}" alt=""></td>
                            <td>{{$blog->created_at}}</td>
                            <td class="{{$blog->is_active == '1' ? 'success_vt' : 'pending_vt'}}">{{$blog->is_active == '1' ? 'Active' : 'Inactive'}}</td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn header-btn-arrow dropdown-toggle p-0 table-action-icon"
                                        type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                    </button>
                                    <ul class="dropdown-menu header-menu" aria-labelledby="dropdownMenuButton1">
                                        <li>
                                            <a class="dropdown-item" href="{{route('edit.blog',['id'=>$blog->id])}}">
                                            <i class="fontello icon-edit2 pr-10"></i>Edit</a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item text-danger" href="{{route('delete.blog',['id'=>$blog->id])}}">
                                            <i class="fontello icon-trash-1 pr-10"></i>Delete</a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-end">
            </div>
        </div>
    </div>

</div>
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
       
</script>
<script type="text/javascript">
    setTimeout(function(){ 
        $('#alertID').hide('slow')
        }, 2000);
</script>

@endsection