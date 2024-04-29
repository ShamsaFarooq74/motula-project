
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('assets/images/fav_icon.png') }}">
    <link rel="stylesheet" href="{{asset('assets/bootstrap/css/bootstrap.css')}}">
    <link rel="stylesheet" href="{{asset('assets/fonts/fontello/css/fontello.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/style.css')}}">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <title>Create Account</title>

<style>
    .otp-heading{
        font-family: var(--font-swiss);
        font-weight: 600;
        font-size: 18px;
        line-height: 22px;
        margin-bottom: 5px;
        text-align: center;
    }
    .loader {
        border: 4px solid #f3f3f3; /* Light gray border */
        border-top: 4px solid #3498db; /* Blue border on top */
        border-radius: 50%;
        width: 30px;
        height: 30px !important;
        animation: spin 1s linear infinite; /* Spin animation */
        margin: 0 auto;
        margin-top: 15px;
    }

    @keyframes spin {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
    }
</style>
<style type="text/css">
        :root{
            --bs-warning: #69AFC5;
            --bs-green: #00E540;
            --bs-gray: #D2D4E5;
            --bs-orange: #E56E00;
            /* --shadow-color: 74, 208, 35; */

            --btn-bg-color: {{ $config[1]['value'] }};
            --shadow-color: {{ $config[8]['value'] }};
            /* --shadow-color: {{ $setting[9]['value'] }} */
            --btn-color:{{ $config[0]['value'] }};
            --sub-heading-color: {{ $config[4]['value'] }};
            --text-color :  {{ $config[7]['value'] }};
            --heading-color: {{ $config[5]['value'] }};
            --bg-color: {{ $config[3]['value'] }};
            --icon-color: {{ $config[6]['value'] }};
            --font-swiss: Swiss;
            --font-NHaas: NHaas;
            --radio-bg-color: {{ $config[2]['value'] }};
        }
</style>
</head>
<body>
    <div class="box-form">        
        <div class="left">
            <div class="overlay">
                <h1><i class="fontello icon-quote-left pr-10"></i>SERU Assessment Online Training</h1>
                
                <p> Register an account and commence your learning journey on the SERU online learning platform. <i class="fontello icon-quote-right pl-10"></i></p>
            </div>
        </div>
        <div class="right">
            <div class="right-area-content">
                <h2><img src="{{asset('assets/images/'.$setting[21]['value'])}}" alt=""></h2>
                @if(Session::has('error'))
                    <div class="alert alert-danger"> {{Session::get('error') }}<br></div>
                @endif
                
                <div class="alert alert-danger" id="error" style="display: none;"></div>
                <div class="alert alert-success" id="successAuth" style="display: none;"></div>

                <div id="verify"></div>
                <div id="place">
                    <h3>Create Account</h3>
                    <p>Please enter your credentials below to get full access to your SERU Assessment online learning course.</p>
                    <div id="loader" style="display: none;" class="loader">
                    <!-- Your loader HTML here -->
                    </div>
                    <div class="input-section">
                        <div class="mb-4">
                            <input type="text" name="username" class="form-control" id="username" placeholder="User Name" require>
                        </div>
                        <div class="mb-4">
                            <input type="text" name="full_name" class="form-control" id="full_name" placeholder="Full Name" require>
                        </div>
                        <div class="mb-4">
                            <input type="email" name="email" class="form-control" id="email" placeholder="Email Address" require>
                        </div>
                        <div class="mb-4 password-section">
                            <input type="password" name="password"class="form-control" id="password" placeholder="Password" require>
                            <i class="fontello icon-eye-1" id="eye"></i>
                        </div>
                        <div class="mb-4 password-section">
                            <input type="password" name="c_password" class="form-control" id="c_password" placeholder="Confirm Password" require>
                            <i class="fontello icon-eye-1" id="eye1"></i>
                        </div>
                    </div>
                    <div class="pt-4">
                        <button type="button" class="btn btn-warning w-100 custom-btn login-btn" id="numverify">Send OTP</button>
                    </div>
                    <div id="recaptcha-container"></div>
                    <div class="accounts_vt pt-4">
                        <span>Already have SERU Assessment Account?<a href="{{route('web.login')}}"> Login</a></span>
                    </div>
                    <div class="accounts_vt terms_vt pt-4">
                        <span>By clicking “Create Account” you agree to SERU’s User <bold><a href="javascript:void(0)"> Terms of Service</a> </bold> and <a href="javascript:void(0)"> Privacy Policy </a></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://www.gstatic.com/firebasejs/6.0.2/firebase.js"></script>
<script type="text/javascript">
    var otp;
    var otpemail;

    $("#numverify").click(function() {
        var password = document.getElementById('password').value;
        var c_password = document.getElementById('c_password').value;
        if(password == "" || c_password == ""){
            $("#error").text('Please add your password and confirm password');
            $("#error").show();
        }
        else if (password != c_password) {
                $("#error").text('Your Password or Confirm Password does not match');
                $("#error").show();
        }
        else{
            var email = document.getElementById('email').value;
            if (email == '') {
                $("#error").text('Please enter the Email');
                $("#error").show();
            } else {
                $.ajax({
                    url: "signupverify",
                    type: "POST",
                    data: {
                        email: email,
                        _token: '{{ csrf_token() }}'

                    },
                    success: function(response) {
                        if (response.error) {
                            console.log(response.error);
                            $("#error").text(response.error);
                            $("#error").show();
                        } else if (response.email) {
                                $("#numverify").prop('disabled',true);
                                $('#loader').show();

                            $.ajax({
                                url: "{{route('signup.email.verify')}}",
                                type: "POST",
                                data: {
                                    email: response.email,
                                    _token: '{{ csrf_token() }}'
                                },
                                success: function(response){
                                    $("#numverify").prop('disabled',false);
                                    $('#loader').hide();
                                    otpemail = email;
                                    otp = response.otp;
                                    $("#error").hide();
                                    $("#successAuth").hide();
                                    $("#successAuth").text("Message sent");
                                    $("#successAuth").show();
                                    $("#place").hide();
                                    // $("#recaptcha-container").hide();
                                    $("#verify").empty();
                                    $("#verify").append(' <h3 class="otp-heading">OTP Verification</h3><div class="form-group mb-3"><input id="verification" class="form-control" type="text" placeholder="Enter Verification Code"></div><div class="form-group mb-3 logone_vt"><button type="button" class="btn btn-warning w-100 custom-btn" onclick="verifyemailOTP()">Verify OTP Code</button><br></div>');
                                }
                            })
                        }
                        else {
                            // sendOTP();
                        }

                    }
                })
            }
        }

        });
    

        function verifyemailOTP(){
            var code = $("#verification").val();
            if(otp == code){
                $("#successAuth").hide();
                $("#error").hide();
                $("#verify").empty();
                var username = document.getElementById('username').value;
                var full_name = document.getElementById('full_name').value;
                var password = document.getElementById('password').value;
                $.ajax({
                    url: "{{route('signup.client')}}",
                    type: "POST",
                    data: {
                        username: username,
                        full_name: full_name,
                        password: password,
                        email: otpemail,
                        _token: '{{ csrf_token() }}'

                    },
                    success: function(response) {
                    if (response.success) {
                        window.location.href = "{{route('web.login')}}";
                        location.reload;

                    } else {

                        $("#error").text(response.error);
                        $("#error").show();
                    }

                }
                })
                
            }
            else{
                $("#successAuth").hide();
                $("#error").text("INVALID OTP");
                $("#error").show();
            }
        }
</script>

<script >
    const passwordInput = document.querySelector("#password")
    const eye = document.querySelector("#eye")
    const passwordInput1 = document.querySelector("#c_password")
    const eye1 = document.querySelector("#eye1")

    eye.addEventListener("click", function(){
    this.classList.toggle("icon-eye-off")
    const type = passwordInput.getAttribute("type") === "password" ? "text" : "password"
    passwordInput.setAttribute("type", type)
    })

    eye1.addEventListener("click", function(){
    this.classList.toggle("icon-eye-off")
    const type = passwordInput1.getAttribute("type") === "password" ? "text" : "password"
    passwordInput1.setAttribute("type", type)
    })
</script>

</html>