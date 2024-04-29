<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Cartify</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta content="Buy and sell chemicals at low price from the comfort of your home" name="description" />
    <meta content="Coderthemes" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('web/images/favicon.png') }}">
    <link href="{{ asset('web/css/custom_style.css') }}" rel="stylesheet" type="text/css" />
    <!-- App css -->
    <link href="{{ asset('web/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('web/css/app.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('web/css/css/select2/select2-bootstrap4.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>

    <!-- CDN -->
</head>
<style>
    body {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }

</style>

<body class="login_bg">
    <!--Start wrapper -->
    <div id="wrapper">

        <!-- Start Page Content here -->
        <div class="content">
            <!-- start page title -->
            <div class="row">
                <div class="col-md-4"></div>

                <div class="col-md-4">
                    <div class="sellerbuyer_area_vt">
                        <div class="logo_head_vt">
                            <a href="{{ route('index') }}">
                                <span><img src="http://cartify.viion.net/assets/images/logo_login.png" alt=""
                                        height="60"></span>
                            </a>
                        </div>

                        @if (Session::has('error'))
                            <div class="alert alert-danger"> {{ Session::get('error') }}<br></div>
                        @endif

                        <div class="alert alert-danger" id="error" style="display: none;"></div>
                        <div class="alert alert-success" id="successAuth" style="display: none;"></div>
                        <!-- <div class="form-group mb-3">
                                            <h3>Sign up</h3>
                                    </div> -->

                        <div id="verify"></div>
                        <div id="place">
                            <div class="form-group mb-3">
                                <h4>Phone Number Verification</h4>
                            </div>
                            <div class="form-group mb-3">
                                <div class="alert alert-success" id="successAuth" style="display: none;"></div>
                            </div>
                            <div class="form-group mb-3">
                                <input id="phone" class="form-control pl-5" type="tel" name="phone"
                                    placeholder="Enter Phone Number" id="phone" required>
                            </div>
                            <div class="form-group mb-3 logone_vt">
                                <button type="button" class="btn_otp_vt" id="numverify">
                                    Send OTP
                                </button>
                            </div>
                        </div>
                        <div id="recaptcha-container"></div>
                        <div class="row" id="pass" style="display:none ">
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <input id="password" class="form-control" type="password" name="password"
                                        placeholder="Enter Password" required>
                                        <span class="p-viewer hidepasss" style="display: none">
                                            <img src="{{ asset('web/images/eye.svg') }}" width='70%' class="eyeicon">
                                        </span>
                                        <span class="p-viewer showpasss" >
                                            <img src="{{ asset('web/images/Icon_awesome-eye.svg') }}" width='70%' class="eyeicon">
                                        </span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <input id="c_password" class="form-control" type="password" name="c_password"
                                        placeholder="Enter Confirm Password" required>
                                        <span class="p-viewer hidepassss" style="display: none">
                                            <img src="{{ asset('web/images/eye.svg') }}" width='70%' class="eyeicon">
                                        </span>
                                        <span class="p-viewer showpassss">
                                            <img src="{{ asset('web/images/Icon_awesome-eye.svg') }}" width='70%' class="eyeicon">
                                        </span>
                                </div>
                            </div>
                            <div class="form-group mb-3 logone_vt"><button id="submit" type="submit"
                                    class="btn_style_vt">Reset Password</button><br>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4"></div>
            </div>
        </div>
    </div>
    <!-- END wrapper -->
    <!-- ENDwrapper -->
    <script src="http://cdn.datatables.net/1.10.23/css/jquery.dataTables.min.css"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
    <!-- App js-->
    <script src="{{ asset('web/js/popper.min.js') }}"></script>
    <script src="{{ asset('web/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('web/js/app.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- Firebase App (the core Firebase SDK) is always required and must be listed first -->
    <script src="https://www.gstatic.com/firebasejs/6.0.2/firebase.js"></script>
    {{-- <script type="module"> --}}
    <script>
        // Your web app's Firebase configuration
        // For Firebase JS SDK v7.20.0 and later, measurementId is optional
        const firebaseConfig = {
            apiKey: "AIzaSyCJAn7wP2hRi2vdjPYOJPfAJ72-JRhWxuc",
            authDomain: "cartify-3fabc.firebaseapp.com",
            projectId: "cartify-3fabc",
            storageBucket: "cartify-3fabc.appspot.com",
            messagingSenderId: "33438562430",
            appId: "1:33438562430:web:5b9ccd096aae2b568cc02f",
            measurementId: "G-2BMMYJCZT6"
        };




        firebase.initializeApp(firebaseConfig);
    </script>
    <script type="text/javascript">
        window.onload = function() {

            render();
        };

        function render() {
            window.recaptchaVerifier = new firebase.auth.RecaptchaVerifier('recaptcha-container');
            recaptchaVerifier.render();
        }

        function sendOTP() {
            const num = phoneInput.getNumber();
            console.log(num);
            firebase.auth().signInWithPhoneNumber(num, window.recaptchaVerifier).then(function(confirmationResult) {
                window.confirmationResult = confirmationResult;
                coderesult = confirmationResult;
                console.log(coderesult);
                $("#phone").val(num);
                $("#error").hide();
                $("#successAuth").hide();
                $("#successAuth").text("Message sent");
                $("#successAuth").show();
                $("#place").hide();
                $("#recaptcha-container").hide();

                $("#verify").append(
                    ' <h4>OTP Verification</h4><div class="form-group mb-3"><input id="verification" class="form-control" type="text" placeholder="Enter Verification Code"></div><div class="form-group mb-3 logone_vt"><button type="button" class="btn_otp_vt" onclick="verify()">Verify OTP Code</button><br></div>'
                );
            }).catch(function(error) {
                $("#error").hide();
                $("#error").text("INVALID PHONE NUMBER!!!");
                $("#error").show();
            });
        }

        function verify() {
            var code = $("#verification").val();
            coderesult.confirm(code).then(function(result) {
                var user = result.user;
                console.log(user);
                $("#successAuth").hide();
                $("#error").hide();
                $("#verify").empty();
                $("#pass").show();

                // $("#successOtpAuth").show();
                // $("#successOtpAuth").text("Auth is successful");
                // $("#successOtpAuth").show();
                // window.location = '/website-login';
            }).catch(function(error) {
                $("#successAuth").hide();
            });

        }
    </script>
    <script>
        const phoneInputField = document.querySelector("#phone");
        const phoneInput = window.intlTelInput(phoneInputField, {
            preferredCountries: ["pk", "co", "in", "de"],
            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
        });

        const info = document.querySelector(".alert-info");
    </script>
    <script>
        $("#numverify").click(function() {

            var num = phoneInput.getNumber();
            var phone = $("#phone").val();
            console.log(phone);
            console.log(num);
            if (phone == '') {
                $("#error").text('Please enter the number ');
                $("#error").show();
            } else {
                $.ajax({
                    url: "{{ route('verify.num') }}",
                    type: "GET",
                    data: {
                        phone: num,
                        _token: '{{ csrf_token() }}'

                    },
                    success: function(response) {
console.log('hi');
                        if (response.error) {
                            console.log(response.error);
                            // $("#error").text("this Phone number has already taken");
                            $("#error").text(response.error);
                            $("#error").show();
                        } else {
                            sendOTP();
                        }

                    }
                })
            }

        });

         $("#submit").click(function(){
    $("#error").hide();
    var password = $("#password").val();
    var c_password = $("#c_password").val();
    var number = $("#phone").val();
    // console.log(name,email,password,c_password,city,role,number,address,type);
    if(password != c_password){
    $("#error").text('Your Password or Confirm Password is not matching');
    $("#error").show();
    }
    $.ajax({
    url: "{{ route('reset.password') }}",
    method: "POST",
    data: {

    password: password,
    c_password: c_password,

    number: number,

    _token:'{{ csrf_token() }}'
    },
    success:function(response){
    if(response.success){
        Swal.fire({
                                icon: 'success',
                                title: 'Password Change Successfully',
                                showClass: {
                                    popup: 'animate__animated animate__fadeInDown'
                                },
                                hideClass: {
                                    popup: 'animate__animated animate__fadeOutUp'
                                }
                            });
    window.location = '/login';

    }
    else{

    $("#error").text(response.error);
    $("#error").show();
    }

    }
    });
    })

    $(".showpasss").click(function(){
console.log('hi');
$(".showpasss").hide();
$(".hidepasss").show();
if($('#password').attr("type") === "password"){
    $('#password').attr("type", "text");
}
});
$(".hidepasss").click(function(){
console.log('hi');
$(".hidepasss").hide();
$(".showpasss").show();
if($('#password').attr("type") === "text"){
    $('#password').attr("type", "password");
}
});

$(".showpassss").click(function(){
console.log('hi');
$(".showpassss").hide();
$(".hidepassss").show();
if($('#c_password').attr("type") === "password"){
    $('#c_password').attr("type", "text");
}
});
$(".hidepassss").click(function(){
console.log('hi');
$(".hidepassss").hide();
$(".showpassss").show();
if($('#c_password').attr("type") === "text"){
    $('#c_password').attr("type", "password");
}
});

        // </script>
</body>

</html>
