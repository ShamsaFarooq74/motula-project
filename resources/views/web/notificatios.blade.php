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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- CDN -->
</head>

<body>
    <!--Start wrapper -->
    <div id="wrapper">
        <!-- Topbar Start -->
        <div class="navbar-custom">
                <div class="container-fluid">
                    <ul class="list-unstyled topnav-menu float-right mb-0">
                        <li class="dropdown d-lg-inline-block">
                            <a class="nav-link dropdown-toggle arrow-none waves-effect waves-light" data-toggle="fullscreen" href="{{ url('/website/seller-types') }}">
                                <img src="{{ asset('web/images/sell.png') }}" alt="">
                                Sell
                            </a>
                        </li>
                        <li class="dropdown d-lg-inline-block">
                            <a class="nav-link dropdown-toggle arrow-none waves-effect waves-light" data-toggle="fullscreen" href="{{ url('/website/seller-types') }}">
                                <img src="{{ asset('web/images/help.png') }}" alt="">
                                Help
                            </a>
                        </li>
                        <li class="dropdown d-lg-inline-block">
                            <a class="nav-link dropdown-toggle arrow-none waves-effect waves-light" data-toggle="fullscreen" href="{{ url('/website/messages-chat') }}">
                                <img src="{{ asset('web/images/messages.png') }}" alt="">
                                Messages
                            </a>
                        </li>
                        <li class="dropdown d-lg-inline-block">
                            <a class="nav-link dropdown-toggle arrow-none waves-effect waves-light" data-toggle="fullscreen" href="{{ url('/website/sign-up') }}">
                                <img src="{{ asset('web/images/sign-up.png') }}" alt="">
                                Sign up
                            </a>
                        </li>
                        <li class="dropdown d-lg-inline-block">
                            <a class="nav-link dropdown-toggle arrow-none waves-effect waves-light" data-toggle="fullscreen" href="{{ url('/website/login') }}">
                                <img src="{{ asset('web/images/login.png') }}" alt="">
                                login
                            </a>
                        </li>
                    </ul>
    
                    <!-- LOGO -->
                    <div class="logo-box">
                        <a href="{{ url('/website/welcome_screen') }}" class="logo logo-dark text-center">
                            <span class="logo-lg">
                                <img src="{{ asset('web/images/Cartify_approved.png') }}" alt="" height="60">
                            </span>
                        </a>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>

        <!-- Start Page Content here -->
        <div class="content">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="banner_sub_vt">
                        <div class="banner_sub_home">
                            <h2>Notificatios</h2>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <!-- Start How Cartify Works ?-->
            <div class="p_notfic_bg py-5">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="notif_vt">
                                <div class="notif_img">
                                    <img src="{{ asset('web/images/notificatiosimg.png') }}" alt="">
                                </div>
                                <div class="notif_text_vt">
                                    <h4>MNK chemical sent you a message</h4>
                                    <p>MNK chemical sent you a message MNK chemical sent you a message</p>
                                    <span>Mon 12:21</span>
                                </div>
                            </div>
                            <div class="notif_vt">
                                <div class="notif_img">
                                    <img src="{{ asset('web/images/notificatiosimg.png') }}" alt="">
                                </div>
                                <div class="notif_text_vt">
                                    <h4>MNK chemical sent you a message</h4>
                                    <p>MNK chemical sent you a message MNK chemical sent you a message</p>
                                    <span>Mon 12:21</span>
                                </div>
                            </div>
                            <div class="notif_vt">
                                <div class="notif_img">
                                    <img src="{{ asset('web/images/notificatiosimg.png') }}" alt="">
                                </div>
                                <div class="notif_text_vt">
                                    <h4>MNK chemical sent you a message</h4>
                                    <p>MNK chemical sent you a message MNK chemical sent you a message</p>
                                    <span>Mon 12:21</span>
                                </div>
                            </div>
                            <div class="notif_vt">
                                <div class="notif_img">
                                    <img src="{{ asset('web/images/notificatiosimg.png') }}" alt="">
                                </div>
                                <div class="notif_text_vt">
                                    <h4>MNK chemical sent you a message</h4>
                                    <p>MNK chemical sent you a message MNK chemical sent you a message</p>
                                    <span>Mon 12:21</span>
                                </div>
                            </div>
                            <div class="notif_vt">
                                <div class="notif_img">
                                    <img src="{{ asset('web/images/notificatiosimg.png') }}" alt="">
                                </div>
                                <div class="notif_text_vt">
                                    <h4>MNK chemical sent you a message</h4>
                                    <p>MNK chemical sent you a message MNK chemical sent you a message</p>
                                    <span>Mon 12:21</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>

        <!-- Footer Start -->
        <footer class="footer mt-3 mt-md-5">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-3 foot_logo_text_vt">
                        <div class="footer_logo">
                            <img src="{{ asset('web/images/footer_logo.png') }}" alt="" height="60">
                        </div>
                        <p>Vivamus eget aliquam dui. <br>
                            Integer eu arcu vel arcu suscipit ultrices quis<br>
                            non mauris. Aenean scelerisque, sem<br>
                            eu dictum commodo, </p>
                        <a href="#"><i class="fa fa-phone"></i> +923154562380</a><br>
                        <a href="#"><i class="fa fa-envelope"></i> info@cartify.com</a>
                    </div>
                    <div class="col-md-3">
                        <h3>Links</h3>
                        <div class="footer_links">
                            <a href="javascript:void(0);">About Us</a>
                            <a href="javascript:void(0);">Help</a>
                            <a href="javascript:void(0);">Contact Us</a>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <h3>Legal</h3>
                        <div class="footer_links">
                            <a href="{{ url('/website/privacy-policy') }}">Privacy Policy</a>
                            <a href="{{ url('/website/feedback') }}">Feedback</a>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <h3>Contact us </h3>
                        <form>

                            <div class="form-group mb-3">
                                <input type="email" id="example-email" name="example-email" class="form-control"
                                    placeholder="Your Email address">
                            </div>
                            <div class="form-group mb-3">
                                <textarea class="form-control" id="example-textarea" rows="5"
                                    placeholder="A quick message"></textarea>
                            </div>

                        </form>
                        <div class="social_icon_vt">
                            <a href="javascript:void(0);"><i class="fa fa-facebook-f"></i></a>
                            <a href="javascript:void(0);"><i class="fa fa-twitter"></i></a>
                            <a href="javascript:void(0);"><i class="fa fa-instagram"></i></a>
                            <a href="javascript:void(0);"><i class="fa fa-google-plus"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <p class="text-center mb-0">All rights reserved @ 2021</p>
        </footer>

    </div>
    <!-- END wrapper -->
    <!-- ENDwrapper -->
    <script src="http://cdn.datatables.net/1.10.23/css/jquery.dataTables.min.css"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
    <!-- App js-->
    <script src="{{ asset('web/js/popper.min.js') }}"></script>
    <script src="{{ asset('web/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('web/js/app.min.js') }}"></script>
</body>

</html>