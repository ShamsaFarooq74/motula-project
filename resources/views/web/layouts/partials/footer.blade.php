<div class="container-fluid px-0">
    <footer>
        <div class="footer-bg-wrappe position-relative">
            <div class="footer-bg"></div>
            <div class="footer-wraper">
                <div class="footer-head_vt">
                    <div class="row mx-0">
                        <div class="col-lg-6 col-md-6">
                            <div class="footer-right-content">
                                <a href="#"><img class="footer-logo-img" src="{{asset('assets/images/'.$setting[25]['value'])}}" alt="" srcset=""></a>
                                <p>{{$setting[39]['value']}}</p>
                                <div class="d-flex footer-mbl-view-img">
                                    @if($setting[4]['value'] != null)
                                    <a href="{{$setting[4]['value']}}" class="footer-circle" target="_blank">
                                        <i class="fontello icon-facebook"></i>
                                    </a>
                                    @endif
                                    @if($setting[1]['value'] != null)
                                    <a href="https://mail.google.com/mail/?view=cm&fs=1&to={{$setting[1]['value']}}" target="_blank" class="footer-circle">
                                        <i class="fontello icon-mail"></i>
                                    </a>
                                    @endif
                                    @if($setting[5]['value'] != null)
                                    <a href="{{$setting[5]['value']}}" class="footer-circle" target="_blank">
                                        <i class="fontello icon-instagram"></i>
                                    </a>
                                    @endif
                                    @if($setting[6]['value'] != null)
                                    <a href="{{$setting[6]['value']}}" class="footer-circle" target="_blank">
                                        <i class="fontello icon-twitter"></i>
                                    </a>
                                    @endif
                                    @if($setting[18]['value'] != null)
                                    <a href="{{$setting[18]['value']}}" class="footer-circle" target="_blank">
                                        <i class="fontello icon-whatsapp"></i>
                                    </a>
                                    @endif
                                    @if($setting[19]['value'] != null)
                                    <a href="{{$setting[19]['value']}}" class="footer-circle" target="_blank">
                                        <i class="fontello icon-linkedIn"></i>
                                    </a>
                                    @endif
{{--                                    <a href="{{$twitterURL->value}}" class="footer-circle">--}}
{{--                                        <i class="fontello icon-twitter"></i>--}}
{{--                                    </a>--}}
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-2 mobile-vew-links">
                            <div class="footer-widgit">
                                <h1>Courses</h1>
                                <ul>
                                    <li><a href="{{route('all.courses')}}">All Courses</a></li>
                                    <li><a href="{{route('all.courses')}}">SERU Trainings</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-2 mobile-vew-links">
                            <div class="footer-widgit">
                                <h1>Information</h1>
                                <ul>
                                    <li><a href="{{route('contact.us')}}">About Us</a></li>
                                    <li><a href="{{route('contact.us')}}">Contact</a></li>
                                    <li><a href="{{route('privacy.policy')}}">Privacy Policy</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-2 mobile-vew-links">
                            <div class="footer-widgit">
                                <h1>Resources</h1>
                                <ul>
                                    <li>SERU FAQs</li>
                                    <li>Glossary</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mx-0">
                    <div class="col-lg-12 px-0">
                        <div class="footer-details">
                            <p>{{$setting[7]['value']}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</div>

            </body>

            <!-- Mirrored from coderthemes.com/ubold/layouts/material/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 04 Sep 2019 05:02:48 GMT -->

            </html>
