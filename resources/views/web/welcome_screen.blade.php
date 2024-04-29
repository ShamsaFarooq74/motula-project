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
    <script src = "https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
</head>
<style>
    body{
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
                        <h2>How do you want to use Cartify?</h2>
                        <h6>We will create your setup accordingly..</h6>
                        <div class="seller_buyer_vt">
                            <form action="{{ route('web.selectRole') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="role" value="5">

                                <button type="submit" class="seller new" style="background: #FEE0E3 !important;"><img src="{{ asset('web/images/man.png') }}" alt=""> Seller</button>
                            </form>
                            <form action="{{ route('web.selectRole') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="role" value="4">
                                <button type="submit" class="buyer new " style="background: #DAE8F7 !important;"><img src="{{ asset('web/images/programmer.png') }}" alt=""> Buyer</button>
                            </form>
                        </div>
                        <button type="button" class="mt-4 px-5 btn_style_vt">Continue</button>
                    </div>
                </div>
                <div class="col-md-4"></div>
            </div>
            <!-- end page title -->


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

    {{-- <script>
        $(document).ready(function(){
            $(".seller").click(function(){
                $.ajax({
                type:'POST',
                url:"{{ route('web.selectRole') }}",
                data:{"_token": "{{csrf_token()}}",role:'seller'},
                success:function(data){

                    // alert(data.success);
                }
                });
            });
            $(".buyer").click(function(){
                $.ajax({
                type:'POST',
                url:"{{ route('web.selectRole') }}",
                data:{"_token": "{{csrf_token()}}",role:'buyer'},
                success:function(data){

                    // alert(data.success);
                }
                });
            });
        });
    </script> --}}

</body>

</html>
