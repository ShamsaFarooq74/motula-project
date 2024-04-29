@extends('web.layouts.master')
@section('content')

<!-- Start Page Content here -->
<div class="content">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="banner_sub_vt">
                <div class="banner_sub_home">
                    <h2>Change Password</h2>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <!-- Start How Cartify Works ?-->
    <div class="container prof_area_wi_vt">
        <div class="row">
            <div class="col-md-12">
                <h4>Password Details:</h4>
            </div>
            <div class="col-md-3"><label>Current Password<span class="star_inp_vt">*</span></label></div>
            <div class="col-md-9">
                <div class="form-group">
                    <input type="password" id="currentpass" class="form-control" value="" id="exampleFormControlInput1"
                        placeholder="Current Password" required>
                        <span class="p-viewer hidepass"  style="display: none">
                            <img src="{{ asset('web/images/eye.svg') }}" width='70%' class="eyeicon">
                        </span>
                        <span class="p-viewer showpass" >
                            <img src="{{ asset('web/images/Icon_awesome-eye.svg') }}" width='70%' class="eyeicon">
                        </span>
                </div>
            </div>
            <div class="col-md-3"><label>New Password<span class="star_inp_vt">*</span></label></div>
            <div class="col-md-9">
                <div class="form-group">
                    <input type="password" id="newpass" class="form-control" value="" id="exampleFormControlInput1"
                        placeholder="New Password" required>
                        <span class="p-viewer hidepasss" style="display: none">
                            <img src="{{ asset('web/images/eye.svg') }}" width='70%' class="eyeicon">
                        </span>
                        <span class="p-viewer showpasss" >
                            <img src="{{ asset('web/images/Icon_awesome-eye.svg') }}" width='70%' class="eyeicon">
                        </span>
                </div>
            </div>
            <div class="col-md-3"><label>Confirm Password<span class="star_inp_vt">*</span></label></div>
            <div class="col-md-9">
                <div class="form-group">
                    <input type="password" id="confirmpass" class="form-control" value="" id="exampleFormControlInput1"
                        placeholder="confirm Password" required>
                        <span class="p-viewer hidepassss" style="display: none">
                            <img src="{{ asset('web/images/eye.svg') }}" width='70%' class="eyeicon">
                        </span>
                        <span class="p-viewer showpassss">
                            <img src="{{ asset('web/images/Icon_awesome-eye.svg') }}" width='70%' class="eyeicon">
                        </span>
                </div>
            </div>
            <div class="col-md-12">
                <button class="edit_bt_vt" id="save_password">Save</button>
                <button class="close_bt_vt">Cancel</button>
            </div>

        </div>
    </div>


</div>
@endsection
@section('js')
<script>
    $("#save_password").click(function () {
        var currentpass = $("#currentpass").val();
        var newpass = $("#newpass").val();
        var confirmpass = $("#confirmpass").val();
        if (newpass != confirmpass) {
            Swal.fire({
                icon: 'error',
                title: 'password and confirm password is not matching',
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                }
            });
        } else {
            console.log(currentpass, newpass, confirmpass);
            $.ajax({
                url: "{{ route('change.password') }}",
                type: 'POST',
                data: {
                    password: currentpass,
                    new_password: newpass,
                    _token: '{{ csrf_token() }}',
                },
                success: function (response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Password has been change successfully',
                            showClass: {
                                popup: 'animate__animated animate__fadeInDown'
                            },
                            hideClass: {
                                popup: 'animate__animated animate__fadeOutUp'
                            }
                        });
                        $("#currentpass").val('');
                        $("#newpass").val('');
                        $("#confirmpass").val('');
                    }
                    else{
                        Swal.fire({
                            icon: 'error',
                            title: 'Your current Password is not Matching',
                            showClass: {
                                popup: 'animate__animated animate__fadeInDown'
                            },
                            hideClass: {
                                popup: 'animate__animated animate__fadeOutUp'
                            }
                        });
                        $("#currentpass").val('');
                        $("#newpass").val('');
                        $("#confirmpass").val('');
                    }

                }


            })
        }
        // console.log(currentpass, newpass, confirmpass);
   

    });
    $(".showpass").click(function(){
console.log('hi');
$(".showpass").hide();
$(".hidepass").show();
if($('#currentpass').attr("type") === "password"){
    $('#currentpass').attr("type", "text");
}
});
$(".hidepass").click(function(){
console.log('hi');
$(".hidepass").hide();
$(".showpass").show();
if($('#currentpass').attr("type") === "text"){
    $('#currentpass').attr("type", "password");
}
});



$(".showpasss").click(function(){
console.log('hi');
$(".showpasss").hide();
$(".hidepasss").show();
if($('#newpass').attr("type") === "password"){
    $('#newpass').attr("type", "text");
}
});
$(".hidepasss").click(function(){
console.log('hi');
$(".hidepasss").hide();
$(".showpasss").show();
if($('#newpass').attr("type") === "text"){
    $('#newpass').attr("type", "password");
}
});

$(".showpassss").click(function(){
console.log('hi');
$(".showpassss").hide();
$(".hidepassss").show();
if($('#confirmpass').attr("type") === "password"){
    $('#confirmpass').attr("type", "text");
}
});
$(".hidepassss").click(function(){
console.log('hi');
$(".hidepassss").hide();
$(".showpassss").show();
if($('#confirmpass').attr("type") === "text"){
    $('#confirmpass').attr("type", "password");
}
});

</script>
@endsection
