
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="shortcut icon" href="{{ asset('assets/images/'.$setting[20]['value']) }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{asset('assets/bootstrap/css/bootstrap.css')}}">
    <link rel="stylesheet" href="{{asset('assets/fonts/fontello/css/fontello.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/style.css')}}">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <title>MOTUL - Login</title>
</head>
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
<body>

    <div class="box-form">
        <div class="left">
            <div class="overlay">
                <h1><i class="fontello icon-quote-left pr-10"></i>MOTUL</h1>

                <!-- <p> Register an account and commence your learning journey on the SERU online learning platform. <i class="fontello icon-quote-right pl-10"></i></p> -->
            </div>
        </div>
            <div class="right">
                <h2><img src="{{asset('assets/images/'.$setting[21]['value'])}}" alt=""></h2>
                <h3>Hello! Welcome Back</h3>
                <p>Please enter your credentials below to get full access to your MOTUL Portal.</p>
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
                <form action="{{ route('login.client') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="input-section">
                        <div class="mb-4">
                            <input type="email" name="email" class="form-control" id="" placeholder="Email Address">
                        </div>
                        <div class="mb-4 password-section">
                            <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                            <i class="fontello icon-eye-1" id="eye"></i>
                        </div>
                    </div>
                    <div class="remember-me--forget-password">
                        <label>
                            <input type="checkbox" name="remember_token" checked="">
                            <span class="text-checkbox">Remember Credentials</span>
                        </label>
{{--                        <a href="javascript:void(0)">Forgot Password?</a>--}}
                    </div>
                    <div class="pt-4">
                        <button type="submit" class="btn btn-warning w-100 custom-btn login-btn">Login</button>
                    </div>
                </form>
                <!-- <div class="accounts_vt pt-4">
                    <span>New On SERU Assessment?<a href="{{route('web.signup')}}"> Create One</a></span>
                </div> -->
                <!-- <div class="accounts_vt terms_vt pt-4">
                    <span>By clicking “Log in” you agree to SERU’s User <bold><a href="javascript:void(0)"> Terms of Service</a> </bold> and <a href="javascript:void(0)"> Privacy Policy </a></span>
                </div> -->
            </div>
    </div>
</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script type="text/javascript">
    setTimeout(function(){
        $('#alertID').hide('slow')
        }, 2000);
</script>
<script>
    const passwordInput = document.querySelector("#password")
const eye = document.querySelector("#eye")

eye.addEventListener("click", function(){
  this.classList.toggle("icon-eye-off")
  const type = passwordInput.getAttribute("type") === "password" ? "text" : "password"
  passwordInput.setAttribute("type", type)
})

</script>

</html>
