@extends('layouts.admin.master')
@section('content')

<!-- Topbar Start -->
@include('layouts.admin.blocks.inc.topnavbar')
<!-- end Topbar -->

<!-- Start Content-->
<div class="container-fluid mt-3">
    <div class="row">

    @include('admin.tracker.tracker_side_bar')
        <div class="col-xl-9 home_custome_table profile_vt">
            <div class="card p-3">
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="page_head_vt_profiler">Profile</h3>
                    </div>
                    <div class="custm_profile_to">

                        <div class="profile">
                            <img src="{{ asset('assets/trackers/'.($tracker->image ?  $tracker->image: 'images.png'))}}" alt=""  class="user_image_detail">

                            <div class="test_profile_vt">
                                <h2>{{$tracker->name}}</h2>
                                <h5>Email : {{$tracker->email}}</h5>
                                <h6>Contact number : {{$tracker->phone}}</h6>
                                {{--<h6>Position : {{$tracker->name}}</h6>--}}
                            </div>
                        </div>
                        <div class="web_vt mt-2">
                            <p class="w-100 text-right">Username : <span>{{$tracker->username}}</span> </p>
                            <div class="form_group_vt">
                                <button class="btn btn_cancel_vt unpublish" data-attr-id="{{$tracker->id}}" data-attr-status="{{$tracker->is_active}}">
                                    {{  $tracker->is_active == 'Y' ? "Block" : "Approve" }}
                                    {{--Delete--}}
                                </button>
                                <a class="btn btn_btn_vt" href="{{url("edit-tracker?info=".$tracker->id)}}">Edit</a>


                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="myTable" class="table table-borderless table-hover table-centered m-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>City</th>
                                    <th>Location</th>
                                    <th>Region</th>
                                    <th>Client</th>
                                    <th>Images</th>
                                    <th>Comments</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            @if(count($trackings))
                                @foreach($trackings as $track)
                                <tr>
                                    <td>
                                        {{  !$track['city_name'] ? "-" : $track['city_name'] }}
                                    </td>
                                    <td>
                                        {{  !$track['asset_address'] ? "-" : $track['asset_address'] }}
                                    </td>
                                    <td>
                                        {{  !$track['asset_region'] ? "-" : $track['asset_region'] }}
                                    </td>
                                    <td>
                                        {{  !$track['company_name'] ? "-" : $track['company_name'] }}
                                    </td>
                                    <td>
                                        <div class="img_table">

                                            @if($track['attachments'])
                                            @php $i=0; @endphp
                                                @foreach($track['attachments'] as $attachment_name)
                                                    @php $type= pathinfo($attachment_name->file, PATHINFO_EXTENSION); @endphp
                                                    @if($type != 'mp3' || $type != 'mp4' || $i < 4)
                                                        @php $i=$i+1; @endphp

                                                        <img src="{{ asset('assets/tracking_files/'.$attachment_name->file)}}" alt="">
                                                    @endif
                                                @endforeach
                                            @else
                                                {{'-'}}
                                            @endif


                                        </div>
                                    </td>
                                    <td>
                                        {{  !$track['comments'] ? "-" : $track['comments'] }}

                                    </td>
                                    <td>
                                        {{  !$track['status_name'] ? "-" : $track['status_name'] }}

                                    </td>
                                    <td>
                                        <div class="btn-group mb-2">
                                            <button type="button" class="btn btn_info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action <i class="mdi mdi-chevron-down"></i></button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item delete_track" data-attr-id="{{$track->id}}" data-attr-status="{{$track->is_active}}">Delete</a>
                                                <a class="dropdown-item approve_track" data-attr-id="{{$track->id}}" data-attr-status="{{$track->is_active}}">
                                                    {{  $track->is_approved == 'Y' ? "Disapprove" : "Approve" }}
                                                    </a>
                                                <a class="dropdown-item" href="{{url("edit-tracker?info=".$track->id)}}">Edit</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td>

                                    </td>
                                    <td>
                                        No trackings added by this tracker
                                    </td>
                                </tr>
                                @endif

                            </tbody>
                        </table>
                        {{--<div class="col-md-12">--}}
                            {{--<nav aria-label="Page navigation_vt">--}}
                                {{--<ul class="pagination">--}}
                                    {{--<li class="page-item">--}}
                                        {{--<a class="page-link" href="#" aria-label="Previous">--}}
                                            {{--<span aria-hidden="true">&laquo;</span>--}}
                                            {{--<span class="sr-only">Previous</span>--}}
                                        {{--</a>--}}
                                    {{--</li>--}}
                                    {{--<li class="page-item"><a class="page-link" href="#">1</a></li>--}}
                                    {{--<li class="page-item"><a class="page-link" href="#">2</a></li>--}}
                                    {{--<li class="page-item"><a class="page-link" href="#">3</a></li>--}}
                                    {{--<li class="page-item">--}}
                                        {{--<a class="page-link" href="#" aria-label="Next">--}}
                                            {{--<span aria-hidden="true">&raquo;</span>--}}
                                            {{--<span class="sr-only">Next</span>--}}
                                        {{--</a>--}}
                                    {{--</li>--}}
                                {{--</ul>--}}
                            {{--</nav>--}}
                        {{--</div>--}}
                    </div>
                </div>
            </div>
        </div> <!-- end col -->
    </div>
    <!-- end row -->

</div> <!-- container -->
<script
        src="https://code.jquery.com/jquery-3.5.1.min.js"
        integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
    $(".unpublish").click(function(e) {
        m_id = parseInt($(this).attr('data-attr-id'));

        status = $(this).attr('data-attr-status');
        if (status == 'N') {
            $sure = '{{ Session::get('publish_tracker')}}';
        } else {
            $sure = '{{ Session::get('Unpublish_tracker')}}';
        }
        e.preventDefault();
        Swal.fire({
            title: $sure,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: "No",
            confirmButtonText: "Yes"
        }).then((result) => {

            if (result.isConfirmed) {
                $.ajax({
                    url: '{{route('update.tracker.status')}}',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        id: m_id,
                        status:status
                    },
                    type: 'post',
                    dataType: 'json',
                    success: function(res) {
                        console.log(res);
                        if (res.msg == 'success') {
                            Swal.fire({
                                icon: res.icon,
                                title: res.response,
                                showConfirmButton: true
                            }).then(function() {
                                window.location.href = "{{route('tracker.list')}}";
                            })

                        } else {
                            Swal.fire(
                                res.icon,
                                res.response,
                                'error'
                            )

                        }
                    }
                });
            } else if (result.isDenied) {

            }
        })

    });


    $(".approve_track").click(function(e) {
        m_id = parseInt($(this).attr('data-attr-id'));
        status =$(this).attr('data-attr-status');
        console.log(m_id);console.log(status);
        e.preventDefault();
        Swal.fire({
            title: '{{ Session::get('sure_approve_track')}}',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: "No",
            confirmButtonText: "Yes"
        }).then((result) => {

            if (result.isConfirmed) {
                $.ajax({
                    url: '{{route('update.tracking.status')}}',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        id: m_id,
                        status:status
                    },
                    type: 'post',
                    dataType: 'json',
                    success: function(res) {
                        console.log(res);
                        if (res.msg == 'success') {
                            Swal.fire({
                                icon: res.icon,
                                title: res.response,
                                showConfirmButton: true
                            }).then(function() {
                                window.location.reload(true);
                            })

                        } else {
                            Swal.fire(
                                res.icon,
                                res.response,
                                'error'
                            )

                        }
                    }
                });
            } else if (result.isDenied) {

            }
        })

    });


    $(".delete_track").click(function(e) {
        m_id = parseInt($(this).attr('data-attr-id'));
        status =$(this).attr('data-attr-status');
        console.log(m_id);console.log(status);
        e.preventDefault();
        Swal.fire({
            title: '{{ Session::get('sure_delete_track')}}',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: "No",
            confirmButtonText: "Yes"
        }).then((result) => {

            if (result.isConfirmed) {
                $.ajax({
                    url: '{{route('delete.track')}}',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        id: m_id,
                        status:status
                    },
                    type: 'post',
                    dataType: 'json',
                    success: function(res) {
                        console.log(res);
                        if (res.msg == 'success') {
                            Swal.fire({
                                icon: res.icon,
                                title: res.response,
                                showConfirmButton: true
                            }).then(function() {
                                window.location.reload(true);
                            })

                        } else {
                            Swal.fire(
                                res.icon,
                                res.response,
                                'error'
                            )

                        }
                    }
                });
            } else if (result.isDenied) {

            }
        })

    });
</script>
@endsection