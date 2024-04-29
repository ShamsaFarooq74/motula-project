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

<body class="authentication-bg">

    <div class="account-pages mt-4 mb-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5 mt-5">
                    <div class="card bg-pattern">
                        <div class="card-body px-4 py-5">

                            <div class="logo_head_vt">
                                <a href="index.html">
                                    <span><img src="{{ asset('assets/images/logo_login.png') }}" alt="" height="60"></span>
                                </a>
                            </div>

                            <div>
                                @include('layouts.admin.blocks.inc.responseMessage')
                            </div>

                            <form method="POST" action="{{ route('password.email') }}">
                                @csrf
                                <div class="form-group mb-3 text_text">
                                    <p>Enter your email address below and we will send you instruction on how to
                                        change your password</p>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="email">{{ __('Email') }}</label>
                                    <input id="email" type="text" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required placeholder="Enter Email Address" autocomplete="email" autofocus>

                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group mb-0 logone_vt">
                                    <button type="submit" class="btn btn_btn_vt">
                                        {{ __('Send') }}
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