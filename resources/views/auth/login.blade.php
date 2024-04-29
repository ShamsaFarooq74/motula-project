
<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from coderthemes.com/ubold/layouts/material/pages-login.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 04 Sep 2019 05:03:34 GMT -->

<head>
    <meta charset="utf-8" />
    <title>Cartify</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
    <meta content="Coderthemes" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">
    <!-- plugin css -->
    <link href="{{ asset('assets/css/custom_style.css') }}" rel="stylesheet" type="text/css" />
    <!-- App css -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />

</head>
<style>
    .form-control {
    text-transform: none !important;
    }
</style>
<body class="authentication-bg">

    <div class="account-pages mt-2 mb-2">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5 mt-5">
                    <div class="card bg-pattern">
                        <div class="card-body px-4 py-5">

                            <div class="logo_head_vt">
                                <a href="index.html">
                                    <span><img src="{{ asset('assets/images/logo_login.png') }}" alt="" height="60"></span>
                                </a>
                                <p>Welcome, Let's get Started!</p>
                            </div>

                            <div>
                                @include('layouts.admin.blocks.inc.responseMessage')
                            </div>

                            <form method="POST" action="{{ url('/login') }}">
                                @csrf
                                <div class="form-group mb-3">
                                    <label for="email">{{ __('Username') }}<span class="star_vt">*</span></label>
                                    <input id="username" type="text" placeholder="Username" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required autocomplete="username" autofocus>

                                    @error('username')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="password">{{ __('Password') }}<span class="star_vt">*</span></label>
                                    <input id="password" type="password" placeholder="********" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group form-check">
                                    <input type="checkbox" class="form-check-input" id="exampleCheck1">
                                    <label class="form-check-label" for="exampleCheck1">Remember me</label>
                                </div>

                                <div class="form_groupvt">
                                    <a href="{{route('password.request')}}" class="float-right">Forgot Password?</a>
                                </div>


                                <div class="form-group mb-0 logone_vt">
                                    <button type="submit" class="btn btn_btn_vt">
                                        {{ __('Login') }}
                                    </button>
                                </div>

                            </form>

                        </div> <!-- end card-body -->
                    </div>
                    <!-- end card -->
                    <!-- end row -->

                </div> <!-- end col -->
            </div>
            <!-- end row -->
        </div>
        <!-- end container -->
    </div>
    <!-- end page -->

    <!-- Vendor js -->
    <script src="{{ asset('assets/js/vendor.min.js') }}"></script>
    <!-- App js -->
    <script src="{{ asset('assets/js/app.min.js') }}"></script>

</body>
</html>


