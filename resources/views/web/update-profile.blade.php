@extends(Auth::user()->role == '2'? 'web.layouts.master' : 'admin_layouts.master')
@section('content')
@if(Auth::user()->role == '2')
    @include('web.layouts.partials.header')
    <div class="container-fluid px-0">
        <div class="update-page">
            <h1 class="update-title">Update Profile</h1>
            <div class="alert alert-danger" id="error" style="display: none;"></div>
                    @if (session()->has('success'))
                    <div class="alert alert-success">
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
            <div class="row">
                <div class="col-lg-4 col-md-4 mb-2">
                    <div class="update-widget">
                        <img id="image-selected" src="{{Auth::user()->image != null && file_exists(public_path().'/assets/profile-pic/'.Auth::user()->image) ? asset('assets/profile-pic/'.Auth::user()->image) : asset('assets/images/default-user.png')}}" alt="" class="update-left-img">
                        <h1>{{$userDetail->full_name}}</h1>
                        <p>{{$userDetail->email}}</p>
                    </div>

                </div>
                <div class="col-lg-8 col-md-8">
                    <div class="update-widget update-right-content">
                        <div>
                            <h1 class="update-title">Profile Details</h1>
                            <div class="mt-3">
                                <form action="{{route('save.profile')}}" method="POST" id="update-profile" enctype="multipart/form-data">
                                    @csrf()
                                    <img src="{{Auth::user()->image != null && file_exists(public_path().'/assets/profile-pic/'.Auth::user()->image) ? asset('assets/profile-pic/'.Auth::user()->image) : asset('assets/images/default-user.png')}}" alt="" id="img-preview" class="preview-img_vt">
                                   
                                    <label for="file" class="position-relative">
                                        <div class="camera-holder">
                                            <i class="fontello icon-camera1"></i>
                                        </div>
                                    <input type="file" id="file" style="display: none" name="image" accept="image/gif,image/jpeg,image/jpg,image/png" multiple="" data-original-title="upload photos">
                                    </label>
                                    <div class="form-group mt-3">
                                      <input type="text" name= "full_name" class="form-control" placeholder="First Name" value="{{$userDetail->full_name}}">
                                    </div>
                                    <!-- <div class="form-group mt-3">
                                        <input type="text" name= "username" class="form-control" placeholder="User Name" value="{{$userDetail->username}}">
                                    </div> -->
                                    <div class="form-group mt-3">
                                        <input type="email" name= "email" class="form-control" placeholder="Enter Email" value="{{$userDetail->email}}" disabled>
                                    </div>
                                    <div class="mt-3">
                                        <label class="checkbox-container">Change Password
                                            <input type="checkbox" id="myCheckbox">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div style="display:none" id="passwordDiv">
                                        <div class="row">
                                            <div class="col-lg-4 mt-3">
                                                <div class="form-group ">
                                                    <input type="password" name= "current_password" class="form-control" placeholder="Current Password" id="current_pass">
                                                </div>
                                            </div>
                                            <div class="col-lg-4 mt-3">
                                                <div class="form-group">
                                                    <input type="password" name= "new_password" class="form-control" placeholder="New Password" id="new_pass">
                                                </div>
                                            </div>
                                            <div class="col-lg-4 mt-3">
                                                <div class="form-group">
                                                    <input type="password" name= "c_password" class="form-control" placeholder="Confirm New Password" id="confirm_pass">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-end mt-3">
                                        <button type="button" class="btn btn-warning custom-btn text-capitalize" onclick="checkPassword()">Update Profile</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
{{--    <link rel="stylesheet" href="https://seru.viion.net/assets/css/style.css">--}}
    <style>
        .update-page {
            padding: 5%;
        }
        .update-title {
            font-family: var(--font-swiss);
            font-weight: 700;
            font-size: 15px;
            line-height: 19px;
            margin-bottom: 15px;
        }
        .update-widget {
            border: 1px solid #D2D4E5;
            padding: 50px 0;
            text-align: center;
        }
        .update-left-img {
            /* max-width: 20%; */
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #ffc107;
        }
        .update-widget h1 {
            font-family: var(--font-swiss);
            font-size: 15px;
            font-weight: 600;
            margin-top: 10px;
        }
        .update-widget p {
            font-family: var(--font-NHaas);
            font-size: 12px;
            line-height: 16px;
            font-weight: 600;
            color: #999999;
            margin-top: 3px;
        }
        .course-completion {
            padding: 10px 0;
        }
        .completion-link {
            margin-bottom: 10px;
        }
        .completion-link a {
            font-family: var(--font-NHaas);
            font-size: 13px;
            color: var(--sub-heading-color);
        }
        .update-right-content {
            padding: 25px;
        }
        .preview-img_vt {
            width: 130px;
            height: 130px;
            border-radius: 50%;
            object-fit: cover;
        }
        .camera-holder {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            border: 5px solid #ffffff;
            background-color: #B9C4E1;
            position: absolute;
            top: 0;
            left: -26px;
        }
        .camera-holder i {
            color: #ffffff;
        }
        .img-holder {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            border: 5px solid #ffffff;
            background-color: #B9C4E1;
            position: absolute;
              top: 33px;
              left: -27px;
        }
        /* .img-holder i {
        color: #ffffff;
        } */
        /* checkbox-styling */


        .checkbox-container {
            display: block;
            position: relative;
            padding-left: 35px;
            margin-bottom: 12px;
            cursor: pointer;
            font-size: 15px;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            text-align: initial;
            font-family: var(--font-NHaas);
            color: #999999;
        }

        /* Hide the browser's default checkbox */
        .checkbox-container input {
            position: absolute;
            opacity: 0;
            cursor: pointer;
            height: 0;
            width: 0;
        }

        /* Create a custom checkbox */
        .checkbox-container .checkmark {
            position: absolute;
            top: 0;
            left: 0;
            height: 22px;
            width: 22px;
            background-color: #eee;
            border-radius: 0;
            border: 0;
        }

        /* On mouse-over, add a grey background color */
        .checkbox-container:hover input ~ .checkmark {
            background-color: #ccc;
        }

        /* When the checkbox is checked, add a blue background */
        .checkbox-container input:checked ~ .checkmark {
            background-color: #FCBC45;
        }

        /* Create the checkmark/indicator (hidden when not checked) */
        .checkbox-container .checkmark:after {
            content: "";
            position: absolute;
            display: none;
        }

        /* Show the checkmark when checked */
        .checkbox-container input:checked ~ .checkmark:after {
            display: block;
        }

        /* Style the checkmark/indicator */
        .checkbox-container .checkmark:after {
            left: 9px;
            top: 5px;
            width: 5px;
            height: 10px;
            border: solid white;
            border-width: 0 3px 3px 0;
            -webkit-transform: rotate(45deg);
            -ms-transform: rotate(45deg);
            transform: rotate(45deg);
        }


        @media screen and (max-width:992px) {
            .left-sidebar-content{
                display: none;
                position: fixed;
                z-index: 9;
            }
            .right-content{
                width: 100%;
            }
            .toggle-btn_vt{
                display: block;
                z-index: 9;
            }
            .header-title{
                display: none;
            }
            .icon-tabs-view_vt{
                display: none;
            }
            .tabs-view-logo{
                visibility: hidden;
            }
        }
        @media screen and (max-width:576px) {
            .detail-head-arrea{
                display: block;
            }
            .multiptle-content-area{
                padding: 2%;
            }
        }
    </style>
    <div class="container-fluid px-0">
        <div class="update-page">
            <h1 class="update-title">Update Profile</h1>
            <div class="alert alert-danger" id="error" style="display: none;"></div>
                    @if (session()->has('success'))
                    <div class="alert alert-success">
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
            <div class="row">
                <div class="col-lg-4 col-md-4 mb-2">
                    <div class="update-widget">
                        <img id="image-selected" src="{{Auth::user()->image != null && file_exists(public_path().'/assets/profile-pic/'.Auth::user()->image) ? asset('assets/profile-pic/'.Auth::user()->image) : asset('assets/images/default-user.png')}}" alt="" class="update-left-img">
                        <h1>{{$userDetail->full_name}}</h1>
                        <p>{{$userDetail->email}}</p>
                    </div>
                </div>
                <div class="col-lg-8 col-md-8">
                    <div class="update-widget update-right-content">
                        <div>
                            <h1 class="update-title">Profile Details</h1>
                            <div class="mt-3">
                                <form action="{{route('save.profile')}}" method="POST" id="update-profile" enctype="multipart/form-data">
                                    @csrf()
                                    <img src="{{Auth::user()->image != null && file_exists(public_path().'/assets/profile-pic/'.Auth::user()->image) ? asset('assets/profile-pic/'.Auth::user()->image) : asset('assets/images/default-user.png')}}" alt="" id="img-preview" class="preview-img_vt">
                                    <label for="file" class="position-relative">
                                        <div class="camera-holder">
                                            <i class="fontello icon-camera1"></i>
                                        </div>
                                            <a class="text-danger"
                                                href="{{ route('delete.profile.image', ['id' => Auth::user()->id]) }}"><i
                                                    class="fontello icon-trash-1 img-holder"></i></a>
                                    <input type="file" id="file" style="display: none" name="image" accept="image/gif,image/jpeg,image/jpg,image/png" multiple="" data-original-title="upload photos">
                                    </label>
                                    <div class="form-group mt-3">
                                      <input type="text" name= "full_name" class="form-control" placeholder="First Name" value="{{$userDetail->full_name}}">
                                    </div>
                                    <!-- <div class="form-group mt-3">
                                        <input type="text" name= "username" class="form-control" placeholder="User Name" value="{{$userDetail->username}}">
                                    </div> -->
                                    <div class="form-group mt-3">
                                        <input type="email" name= "email" class="form-control" placeholder="Enter Email" value="{{$userDetail->email}}" disabled>
                                    </div>
                                    <div class="mt-3">
                                        <label class="checkbox-container">Change Password
                                            <input type="checkbox" id="myCheckbox">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div style="display:none" id="passwordDiv">
                                        <div class="row">
                                            <div class="col-lg-4 mt-3">
                                                <div class="form-group ">
                                                    <input type="password" name= "current_password" class="form-control" placeholder="Current Password" id="current_pass">
                                                </div>
                                            </div>
                                            <div class="col-lg-4 mt-3">
                                                <div class="form-group">
                                                    <input type="password" name= "new_password" class="form-control" placeholder="New Password" id="new_pass">
                                                </div>
                                            </div>
                                            <div class="col-lg-4 mt-3">
                                                <div class="form-group">
                                                    <input type="password" name= "c_password" class="form-control" placeholder="Confirm New Password" id="confirm_pass">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-end mt-3">
                                        <button type="button" class="btn btn-warning custom-btn text-capitalize" onclick="checkPassword()">Update Profile</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif



@section('title', '- Update Profile')


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
    $(document).ready(function (e) {
        $('#file').change(function() {
            let file = this.files[0];
            let reader = new FileReader();
            reader.onload = (e) => {
                $('#img-preview').attr('src', e.target.result);
            }
            reader.readAsDataURL(file);
        });
    });
</script>
<script type="text/javascript">
    setTimeout(function(){
        $('#alertID').hide('slow')
        }, 2000);
</script>
<script type="text/javascript">
    const myCheckbox = document.getElementById("myCheckbox");
    const myDiv = document.getElementById("passwordDiv");

    myCheckbox.addEventListener("change", function() {
    if(this.checked) {
        myDiv.style.display = "block";
    } else {
        myDiv.style.display = "none";
    }
    });
       var newpassword = document.getElementById("new_pass");
        // const image = document.getElementById("image-selected");

        newpassword.onclick = function() {
        var oldpassword = document.getElementById("current_pass").value;
        if(oldpassword == ""){
            $("#error").text("Current Password field is empty");
            $("#error").show();
        }
        else{
            $.ajax({
                url: "{{route('current.password')}}",
                type: "POST",
                data: {
                    current_pass: oldpassword,
                    _token: '{{ csrf_token() }}'

                },
                success: function(response) {
                    if (response.success) {

                    } else {

                        $("#error").text(response.message);
                        $("#error").show();
                    }

                }
            })
        }
    };
    function checkPassword(){
        var pass = document.getElementById("new_pass").value;
        var c_pass = document.getElementById("confirm_pass").value;
        if(pass == c_pass){
            var submitForm = document.getElementById("update-profile");
            submitForm.submit();
        }
        else{
            $("#error").text("Password and Confirm Password are not same");
            $("#error").show();
        }
    }
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#blah').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
