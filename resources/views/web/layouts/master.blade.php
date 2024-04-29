<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{$setting[17]['value']}} @yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="SERU" name="description" />
    <meta content="Coderthemes" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="google-site-verification" content="o7wCWYdGSjCUQTvxaMLH0z4BXJMq1Nowy6r0bJTBVF4" />
    <link rel="shortcut icon" href="{{ asset('assets/images/'.$setting[20]['value'])}}">
    <!-- <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@300;500;700;800;900&display=swap" rel="stylesheet"> -->
    <link rel="stylesheet" href="{{asset('assets/bootstrap/css/bootstrap.css')}}">
    <link rel="stylesheet" href="{{asset('assets/fonts/fontello/css/fontello.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/style.css')}}">
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</head>

<body>
    <!--Start wrapper -->
    <div id="wrapper">
        <!-- end Topbar -->
        @yield('content')
    </div>
    @include('web.layouts.partials.footer')
    <!-- END wrapper -->
    <!-- ENDwrapper -->
    @yield('js')
</body>

</html>
