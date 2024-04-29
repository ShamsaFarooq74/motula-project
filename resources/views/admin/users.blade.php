@extends('admin_layouts.master')
@section('content')
@section('title', '- Users')
<head>
    <style>
        .course-alert{
            position: absolute;
            right: 0;
            top: 0;
        }
        .profile-link {
            cursor: pointer;
        }
        #example_filter {
        padding-right: 5px;
    }

    .d-flex {
        padding-right: 0px;
        padding-left: 5px;
        padding-bottom: 8px;
    }
    .dt-button {
        margin-top: 1px;
    }
    @media screen and (min-width: 360px) and (max-width: 480px) {
        .custom-btn {
            width: 100px;
        }
        .d-flex{
            flex-flow: wrap;
        }
    }
    </style>   
</head>
<style></style>
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
        <div class="lesson-wrapper" style="padding-bottom:0px;">
            <div class="table-holder_vt">
                <table id="example" class="table table-striped bg-white" style="width:100%;">
                    <thead class="tableOfhead">
                        <tr>
                        <th scope="col">Sr</th>
                        <th scope="col">User Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Region</th>
                        <th scope="col">Status</th>
                        <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody class="tableOfContent">
                        @foreach($allUsers as $key=>$users)
                            <tr>
                                <td>{{$key + 1}}</td>
                                <td onclick="location.href='{{route('users.profile',['id'=>$users->id])}}'" class="profile-link">{{$users->full_name}}</td>
                                <td onclick="location.href='{{route('users.profile',['id'=>$users->id])}}'" class="profile-link">{{$users->email}}</td>
                                <td>{{$users->regions->region_name ?? ''}}</td>
                                <td class="{{$users->is_active == '1' ? 'success_vt' : 'pending_vt'}}">{{$users->is_active == '1' ? 'Active' : 'Inactive'}}</td>
                                <td>
                                    <div class="dropdown">
                                       <button class="btn header-btn-arrow dropdown-toggle p-0 table-action-icon" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                       </button>
                                        <ul class="dropdown-menu header-menu" aria-labelledby="dropdownMenuButton1">
                                            <li><a class="dropdown-item" href="#" onclick="getUsers({{$users->id}})"><i class="fontello icon-edit2 pr-10"></i>Edit</a></li>
                                            <li><a class="dropdown-item text-danger" href="{{route('delete.user',['id'=>$users->id])}}"><i class="fontello icon-trash-1 pr-10"></i>Delete</a></li>
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
    
    <div class="modal fade" id="userEdit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog right-modal_vt">
            <div class="modal-content right-modal-content_vt">
            <div class="modal-header right-modal-head">
                <h5 class="modal-title" id="exampleModalLabel">Edit User</h5>
                <div class="close_vt" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fontello icon-cancel text-white"></i>
                </div>
            </div>
            <div class="modal-body">
                <div class="right-modal-content">
                    <form action="{{route('save.user')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="edit_user_id" id="edit_user_id">
                        <label for="basic-url" class="form-label">Full Name</label>
                        <div class="input-group mb-2">
                            <input type="text" id="edit_userfull_name" value="{{ old('user_full_name') }}" name="user_full_name" class="form-control modal-input" placeholder="Enter Your Full-Name">
                        </div>
                        <!-- <label for="basic-url" class="form-label">User Name</label>
                        <div class="input-group mb-2">
                            <input type="text" id="edit_user_name" name="user_name" value="{{ old('user_name') }}" class="form-control modal-input" placeholder="Enter Your Name">
                        </div> -->
                        <label for="basic-url" class="form-label">User Email</label>
                        <div class="input-group mb-2">
                            <input type="text" id="edit_email" name="user_email" value="{{ old('user_email') }}" class="form-control modal-input" placeholder="Enter Your Email">
                        </div>
                        <label for="basic-url" class="form-label">Password</label>
                        <div class="mb-2 input-group password-section">
                          <input type="password" name="password" autocomplete="current-password" value="{{ old('user_email') }}" id="edit_password" class="form-control modal-input"  placeholder="Enter User Password">
                          <div class="input-group-append">
                            <span class="input-group-text">
                              <i id="eye" class="fontello icon-eye-off field_icon toggle-password btn_eyes_vt cursor-pointer"></i>
                            </span>
                          </div>
                        </div>
                        <label for="basic-url" class="form-label">Icon Image</label>
                            <div class="mb-2 input-group">
                                <input type="file" name="image" value="" id="myImage"
                                    class="form-control modal-input">
                                <img id="imageElementId" src="" class="blog-inner-img">
                            </div>
                        <label for="basic-url" class="form-label">Region</label>
                        <div class="mb-2 input-group">
                            <select class="form-select modal-select mb-2" aria-label=""  style="width:100%;" aria-label="" id="SelectElement" name="region_id" data-live-search="true">
                                <option selected disabled>Select Region</option>
                            </select>
                        </div>
                          <label for="basic-url" class="form-label">Select Role</label>
                          <select class="form-select modal-select mb-2" id="edit_role" style="width:100%;"
                              name="user_role">
                              <option selected disabled>Select Role</option>
                              <option value="1">Admin</option>
                              <option value="2">User</option>
                              <option value="3">Editor</option>
                          </select>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label for="basic-url" class="form-label mb-0">Active Status</label>
                            <label class="switch">
                                <input type="checkbox" id="edit_status" name="status">
                                <span class="slider round"></span>
                            </label>
                        </div>
                        <div>
                            <button type="submit" class="btn btn-warning custom-btn">Update</button>
                        </div>
                    </form>
                </div>
            </div>

            </div>
        </div>
    </div>
</div>
    <script>
        $(document).on('click', '.toggle-password', function() {
            $(this).toggleClass("icon-eye-off icon-eye-1");
            var input = $("#edit_password");
            input.attr('type') === 'password' ? input.attr('type','text') : input.attr('type','password')
        });
    </script>
    <script type="text/javascript">
    var customhead = `
        <div style="margin-right: auto;">
                    <h3>User</h3>
        </div>
        `;
    var customCss = `
            <style>
                .dt-buttons .buttons-pdf,
                .dt-buttons .buttons-csv,
                .dt-buttons .buttons-print {
                    background-color: white !important;
                    color: black !important;
                }
            </style>
        `;

    $('head').append(customCss);
    $('#example').DataTable({
        dom: '<"d-flex justify-content-end"fB>rtip',
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search..."
        },
        buttons: [{
                extend: 'csvHtml5',
                text: '<img src="' + "{{ asset('assets/images/csv.png') }}" + '" />',
                exportOptions: {
                    columns: ':not(:last-child):not(:last-child-1)'
                }
            },
            {
                extend: 'print',
                text: '<img src="' + "{{ asset('assets/images/print.png') }}" + '" />',
                exportOptions: {
                    columns: ':not(:last-child):not(:last-child-1)'
                }
            },
            {
                extend: 'pdfHtml5',
                text: '<img src="' + "{{ asset('assets/images/pdf.png') }}" + '" />',
                exportOptions: {
                    columns: ':not(:last-child):not(:last-child-1)'
                }
            },
        ]
    });
    $('#example_filter').before(customhead);
        setTimeout(function(){
            $('#alertID').hide('slow')
            }, 2000);
    
            function getUsers(id){

                $.ajax({
                url: "{{route('edit.user')}}",
                type: "get",
                data: {
                    user_id: id,
                },
                success: function(response) {
                    if (response.success) {
                        var data = response.data;
                        var region = response.region;
                        var model = new bootstrap.Modal(document.getElementById("userEdit"));
                        model.show();
                        var imageSrc = data.image;
                        var imageElement = document.getElementById("imageElementId");
                        if (imageSrc) {
                            imageElement.src = 'assets/images/' + imageSrc;
                        } else {
                            imageElement.src = 'assets/images/dummy.png';
                        }
                        $('#edit_userfull_name').val(data.full_name);
                        $('#edit_user_name').val(data.username);
                        $('#edit_email').val(data.email);
                        
                        $('#edit_user_id').val(data.id);
                        $('#SelectElement').select2({
                        dropdownParent: $('#userEdit')
                        });
                        $('#SelectElement').html('')
                            html = '<option selected disabled>Select Region</option>';
                            for (i = 0; i < region.length; ++i) {
                                var selected = (data.region_id == region[i].id) ? 'selected' : '';
                                html += `<option  ${selected} value="${region[i].id}">${region[i].region_name}</option>`;
                            }
                        $('#SelectElement').append(html)
                        document.getElementById('edit_status').checked = response.data.is_active == '1' ? true: false;
                        document.getElementById('edit_role').value = data['role'];
                    }
                }
            })
            }
    </script>
@endsection
 