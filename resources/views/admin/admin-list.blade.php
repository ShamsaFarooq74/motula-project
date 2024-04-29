@extends('admin_layouts.master')
@section('content')
@section('title', '- Admin')
<style>
    .course-alert {
        position: absolute;
        right: 0;
        top: 0;
    }
    #example_filter {
        padding-right: 5px;
    }
    .smaller-logo {
        width: 50px;
        height: auto;
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
<div>
    <div class="lesson-page-content">
        <div class="course-alert">
            @if (session()->has('success'))
                <div class="alert alert-success" id="alertID">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close"></a> {{ session('success') }}
                </div>
            @endif
            @if (session()->has('error'))
                <div class="alert alert-danger" id="alertID">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close"></a> {{ session('error') }}
                </div>
            @endif
        </div>
        <div class="lesson-wrapper">
            <div class="table-holder_vt">
                <table id="example" class="table table-striped bg-white w-100">
                    <thead class="tableOfhead">
                        <tr>
                            <th scope="col">Sr</th>
                            <th scope="col">Admin Name</th>
                            <th scope="col">Region</th>
                            <th scope="col">Email</th>
                            <th style="height:auto; width: 0px;">Image</th>
                            <th scope="col">Status</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody class="tableOfContent">
                        @foreach ($allUsers as $key => $users)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $users->full_name }}</td>
                                <td>{{ $users->regions->region_name ?? '' }}</td>
                                <td>{{ $users->email }}</td>
                                <td style="text-align: center;">
                                    @if ($users->image != null)
                                        <img class="smaller-logo"
                                            src="{{ $users->image != null ? asset('assets/images/' . $users->image) : '' }}">
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td class="{{ $users->is_active == '1' ? 'success_vt' : 'pending_vt' }}">
                                    {{ $users->is_active == '1' ? 'Active' : 'Inactive' }}</td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn header-btn-arrow dropdown-toggle p-0 table-action-icon"
                                            type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                        </button>
                                        <ul class="dropdown-menu header-menu" aria-labelledby="dropdownMenuButton1">
                                            <li><a class="dropdown-item" href="#"
                                                    onclick="getUsers({{ $users->id }})"><i
                                                        class="fontello icon-edit2 pr-10"></i>Edit</a></li>
                                            <li><a class="dropdown-item text-danger"
                                                    href="{{ route('delete.user', ['id' => $users->id]) }}"><i
                                                        class="fontello icon-trash-1 pr-10"></i>Delete</a></li>
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

    <div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog right-modal_vt">
            <div class="modal-content right-modal-content_vt">
                <div class="modal-header right-modal-head">
                    <h5 class="modal-title" id="exampleModalLabel">New User</h5>
                    <div class="close_vt" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fontello icon-cancel text-white"></i>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="right-modal-content">
                        <form id="addForm" enctype="multipart/form-data">
                            @csrf
                            <label for="basic-url" class="form-label">Full Name</label>
                            <div class="input-group mb-2">
                                <input type="text" name="full_name" value="{{ old('full_name') }}" class="form-control modal-input"
                                    placeholder="Enter Full Name">
                            </div>
                            <!-- <label for="basic-url" class="form-label">User Name</label>
                            <div class="input-group mb-2">
                                <input type="text" name="user_name" value="{{ old('user_name') }}" class="form-control modal-input"
                                    placeholder="Enter User Name">
                            </div> -->
                            <label for="basic-url" class="form-label">Email</label>
                            <div class="mb-2 input-group">
                                <input type="email" name="user_email" value="{{ old('user_email') }}" class="form-control modal-input"
                                    placeholder="Enter User Email">
                            </div>
                            <label for="basic-url" class="form-label">Password</label>
                            <div class="mb-2 input-group password-section">
                                <input type="password" name="password" value="{{ old('password') }}" id="edit_password"
                                    class="form-control modal-input" placeholder="Enter User Password">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i id="eye"
                                            class="fontello icon-eye-off field_icon toggle-password btn_eyes_vt cursor-pointer"></i>
                                    </span>
                                </div>
                            </div>
                            <label for="basic-url" class="form-label">User Image</label>
                            <div class="mb-2 input-group">
                                <input type="file" name="image" class="form-control modal-input">
                            </div>
                            
                            <label for="basic-url" class="form-label">Region</label>
                            <select class="selectpicker mb-2 w-100" data-live-search="true" name="region_id">
                                <option selected disabled>Select Region</option>
                                @foreach ($regions as $item)
                                    <option value="{{ $item->id }}" {{ old('region_id') == $item->id ? 'selected' : '' }}>
                                        {{ $item->region_name }}
                                    </option>
                                @endforeach
                            </select>
                            
                            <label for="basic-url" class="form-label">Select Role</label>
                            <select class="form-select modal-select mb-2" style="width:100%;" name="user_role">
                                <option selected disabled>Select Role</option>
                                @foreach ([1 => 'Admin', 2 => 'User', 3 => 'Editor'] as $value => $label)
                                    <option value="{{ $value }}" {{ old('user_role') == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label for="basic-url" class="form-label mb-0">Active Status</label>
                                <label class="switch">
                                    <input type="checkbox" name="status" {{ old('status') ? 'checked' : '' }}>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                             <div id="error-container"></div>
                            <div>
                                <button type="submit" class="btn btn-warning custom-btn" data-bs-toggle="modal"
                                    data-bs-target="#exampleModal">Add User</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="userEdit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog right-modal_vt">
            <div class="modal-content right-modal-content_vt">
                <div class="modal-header right-modal-head">
                    <h5 class="modal-title" id="exampleModalLabel">Update User</h5>
                    <div class="close_vt" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fontello icon-cancel text-white"></i>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="right-modal-content">
                        <form action="{{ route('save.users') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="edit_user_id" id="edit_user_id">
                            <label for="basic-url" class="form-label">Full Name</label>
                            <div class="input-group mb-2">
                                <input type="text" name="full_name" value=""
                                    class="form-control modal-input" id="edit_userfull_name"
                                    placeholder="Enter Full Name">
                            </div>
                            <!-- <label for="basic-url" class="form-label">User Name</label>
                            <div class="input-group mb-2">
                                <input type="text" name="user_name" value=""
                                    class="form-control modal-input" id="edit_user_name"
                                    placeholder="Enter User Name">
                            </div> -->
                            <label for="basic-url" class="form-label">Email</label>
                            <div class="mb-2 input-group">
                                <input type="email" name="user_email" value=""
                                    class="form-control modal-input" id="edit_email" placeholder="Enter User Email">
                            </div>
                            <label for="basic-url" class="form-label">Password</label>
                            <div class="mb-2 input-group password-section">
                                <input type="password" name="password" autocomplete="current-password"
                                    value="" id="edit_password" class="form-control modal-input"
                                    placeholder="Enter User Password">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i id="eye"
                                            class="fontello icon-eye-off field_icon toggle-password btn_eyes_vt cursor-pointer"></i>
                                    </span>
                                </div>
                            </div>
                            <label for="basic-url" class="form-label">User Image</label>
                            <div class="mb-2 input-group">
                                <input type="file" name="image" value="" id="myImage"
                                    class="form-control modal-input">
                                <img id="imageElementId" src="" class="blog-inner-img">
                            </div>
                            <label for="basic-url" class="form-label">Region</label>
                            <div class="mb-2 input-group">
                                <select class="form-select modal-select mb-2" aria-label="" style="width:100%;"
                                    aria-label="" id="SelectElement" name="region_id" data-live-search="true">
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

    <!-- Modal Popup -->
    <div class="modal" id="emailConfirmationModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Email Confirmation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p id="emailConfirmationMessage"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmEmailActivation">Confirm</button>
                </div>
            </div>
        </div>
    </div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>


    <script>
        document.getElementById('edit_password').setAttribute('autocomplete', 'off');
        $(document).on('click', '.toggle-password', function() {
            $(this).toggleClass("icon-eye-off icon-eye-1");
            var input = $("#edit_password");
            input.attr('type') === 'password' ? input.attr('type', 'text') : input.attr('type', 'password')
        });
    </script>
</div>
<script type="text/javascript">
   var customButton = `
                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-warning custom-btn" data-bs-toggle="modal"
                            data-bs-target="#userModal">Add User</button>
        `;
    var customhead = `
        <div style="margin-right: auto;">
                    <h3>Admin</h3>
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
        buttons: [
            {
                extend: 'csvHtml5',
                text: '<img src="' + "{{ asset('assets/images/csv.png') }}" + '" />',
                exportOptions: {
                    columns: ':not(:last-child):not(:last-child-1):not(:eq(3))'
                }
            },
            {
                extend: 'print',
                text: '<img src="' + "{{ asset('assets/images/print.png') }}" + '" />',
                exportOptions: {
                    columns: ':not(:last-child):not(:last-child-1):not(:eq(3))'
                }
            },
            {
                extend: 'pdfHtml5',
                text: '<img src="' + "{{ asset('assets/images/pdf.png') }}" + '" />',
                exportOptions: {
                    columns: ':not(:last-child):not(:last-child-1):not(:eq(3))'
                }
            }
        ]
    });
    $('#example_filter').before(customhead);
    $('.dt-buttons').after(customButton);
    setTimeout(function() {
        $('#alertID').hide('slow')
    }, 2000);

    function getUsers(id) {
        $.ajax({
            url: "{{ route('edit.users') }}",
            type: "get",
            data: {
                user_id: id,
                '_token': "{{ csrf_token() }}",
            },
            success: function(response) {
                if (response.success) {
                    var data = response.data;
                    var region = response.region;

                    var modal = new bootstrap.Modal(document.getElementById("userEdit"));
                    modal.show();

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
                        html +=
                            `<option  ${selected} value="${region[i].id}">${region[i].region_name}</option>`;
                    }
                    $('#SelectElement').append(html)
                    document.getElementById('edit_role').value = data['role'];
                    document.getElementById('edit_status').checked = data.is_active == '1' ? true : false;
                }
            }


        });
    }
</script>
<script>
   $(document).ready(function () {
   $('#addForm').submit(function (e) {
        e.preventDefault(); 
        var formData = $(this).serialize();
        console.log(formData);
        $.ajax({
        url: '{{ route("save.users") }}',
        method: 'POST',
        data: formData, 
        success: function (response) {
            if (response.success == 'deletedAcount') {
                console.log(response.message.id);
                    Swal.fire({
                        text: "Account is already registered with this email id. Do you want to reactivate this account?",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonText: "OK",
                        cancelButtonText: "Cancel", 
                        dangerMode: true,
                    }).then(function(result) {
                        if (result.isConfirmed) {
                            if (response.message.id) {
                                console.log(response.message.id);
                                activateUser(response.message.id);
                            } else {
                                console.error("User ID not found in response");
                            }
                        } else {
                            console.error("somthing wrong!");
                        }
                    });
            }
            else if (response.success == 'userAdded') {
                Swal.fire({
                    text: "User Added Successfully",
                    icon: "success",
                    confirmButtonText: "OK",
                });
            }
            else{
           Swal.fire({
           text: "Failed to save user. Please try again.",
           icon: "error",
           confirmButtonText: "OK",
           });
        }
        },
        error: function (xhr, status, error) {
                console.error(xhr, status, error);
                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    $('.error-message').remove();
                    $.each(errors, function (key, value) {
                        
                       $('#error-container').append('<div class="error-message text-danger">' + value[0] + '</div>');
                    });
                } else {
                    Swal.fire({
                        text: "Failed to save user. Please try again.",
                        icon: "error",
                        confirmButtonText: "OK",
                    });
                }
            }
        });
        });

   function activateUser(userId) {
    $.ajax({
         url: '{{ route("save.users") }}',
         method: 'POST',
        headers: {
             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            confirm_user_id: userId,
        },
        success: function (data) {
            if(data.success){
                Swal.fire({
                    text: "User Activated Successfully",
                    confirmButtonText: "OK",
                })
            }
        },
        error: function (xhr, status, error) {
            Swal.fire({
                text: "User Not Activated",
                confirmButtonText: "OK",
            })
            console.error(xhr, status, error);
        }
    });
}

        
   });

</script>

@endsection
