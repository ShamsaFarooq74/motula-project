@extends('admin_layouts.master')
@section('content')
@section('title', '- Theme Settings')
<style>
    .course-alert {
        position: absolute;
        right: 0;
        top: 0;
    }
</style>
<div>
    <div class="lesson-page-content">
        <div class="course-alert">
            @if (session()->has('success'))
                <div class="alert alert-success" id="alertID">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close"></a> {{ session('success') }}
                </div>
            @endif
            @if (session()->has('error'))
                <div class="alert alert-danger" id="alertID">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close"></a> {{ session('error') }}
                </div>
            @endif
        </div>
        <div class="lesson-wrapper">
            <div class="detail-head-arrea border-bottom mb-2">
                <div>
                    <h1>Theme Settings</h1>
                </div>
            </div>
            <div>
                <h2 class="theme-title">Web App Settings</h2>
                <form action="{{ route('update.theme') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row mb-2">
                        <div class="col-lg-12 col-md-6 mb-2">
                            <label for="basic-url" class="form-label">App Tab Name</label>
                            <div class="input-group">
                                <input type="text" name="appname" value="{{$appName->value}}" class="form-control" id=""
                                    placeholder="App Name" required>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 mb-2">
                            <label for="basic-url" class="form-label">App Favicon <small>(Recommended Favicon Icon Size
                                    16*16)</small></label>
                            <div class="input-group">
                                <input type="file" name="favicon" value="{{ $AppFaviconL->value }}" class="form-control">
                                @if($AppFaviconL->value != null)
                                    <img class="blog-inner-img" src="{{$AppFaviconL->value != null ? asset('assets/images/'.$AppFaviconL->value) : ''}}">
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 mb-2">
                            <label for="basic-url" class="form-label">App Logo <small>(Recommended Logo Icon Size
                                    16*16)</small></label>
                            <div class="input-group">
                                <input type="file" name="applogo" value="" class="form-control">
                                @if( $AppLogo->value != null)
                                    <img class="blog-inner-img" src="{{ $AppLogo->value != null ? asset('assets/images/'. $AppLogo->value) : ''}}">
                                @endif
                            </div>
                        </div>
                    </div>
                    <h2 class="theme-title">Website Settings</h2>
                    <div class="row mb-2">
                        <div class="col-lg-6 col-md-6 mb-2">
                            <label for="basic-url" class="form-label">Website Hero Banner</label>
                            <div class="input-group">
                                <input type="file" name="herobanner" value="{{ $WebsiteHeroBanner->value }}" class="form-control">
                                @if( $WebsiteHeroBanner->value != null)
                                    <img class="blog-inner-img" src="{{ $WebsiteHeroBanner->value != null ? asset('assets/images/'.$WebsiteHeroBanner->value) : ''}}">
                                @endif
                            </div>
                        </div>
                        <!-- <div class="col-lg-6 col-md-6 mb-2">
                            <label for="basic-url" class="form-label">Web Navbar Logo</label>
                            <div class="input-group">
                                <input type="file" name="navlogo" value="" class="form-control">
                                {{-- <img src="assets/images/{{ $WebNavbarLogo->value }}" width="300px"> --}}
                            </div>
                            {{ $WebNavbarLogo->value }}
                        </div> -->
                        <div class="col-lg-6 col-md-6 mb-2">
                            <label for="basic-url" class="form-label">Footer Banner</label>
                            <div class="input-group">
                                <input type="file" name="footerbanner" value="" class="form-control">
                                @if( $FooterBanner->value != null)
                                    <img class="blog-inner-img" src="{{ $FooterBanner->value != null ? asset('assets/images/'.$FooterBanner->value) : ''}}">
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 mb-2">
                            <label for="basic-url" class="form-label">Footer Logo</label>
                            <div class="input-group">
                                <input type="file" name="footerlogo" value="" class="form-control">
                                @if( $FooterLogo->value != null)
                                    <img class="blog-inner-img" src="{{ $FooterLogo->value != null ? asset('assets/images/'.$FooterLogo->value) : ''}}">
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 mb-2">
                            <label for="basic-url" class="form-label">Footer Text</label>
                            <div class="input-group">
                                <input type="text" name="footerText" value="{{$setting[7]['value']}}" class="form-control">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 mb-2">
                            <label for="basic-url" class="form-label">Footer Description</label>
                            <div class="input-group">
                                <textarea name="footerDescription" rows="3" cols="50" class="form-control">{{$setting[39]['value']}}</textarea>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 mb-2">
                            <label for="basic-url" class="form-label">Currency Symbol</label>
                            <div class="input-group">
                                <input type="text" name="base_currency" value="{{$setting[16]['value']}}" class="form-control" placeholder="Enter Currency">
                            </div>
                        </div>
                        <!-- <div class="col-lg-6 col-md-6 mb-2">
                            <label for="basic-url" class="form-label">Company Address</label>
                            <div class="input-group">
                                <input type="text" name="company_address" value="{{$setting[0]['value']}}" class="form-control" placeholder="Enter Company Address">
                            </div>
                        </div> -->
                    </div>
                    <h2 class="theme-title">Social Media Links</h2>
                    <div class="row mb-2">
                        <div class="col-lg-6 col-md-6 mb-2">
                            <label for="basic-url" class="form-label">Facebook Link</label>
                            <div class="input-group">
                                <input type="url" name="facebooklink" value="{{$facebookURL->value}}" class="form-control" id=""
                                    placeholder="Enter Facebook Link">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 mb-2">
                            <label for="basic-url" class="form-label">Twitter Link</label>
                            <div class="input-group">
                                <input type="url" name="twitterlink" value="{{$twitterURL->value}}" class="form-control" id=""
                                    placeholder="Enter Twitter Link">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 mb-2">
                            <label for="basic-url" class="form-label">Instagram Link</label>
                            <div class="input-group">
                                <input type="url" name="instangramlink" value="{{$instagramURL->value}}" class="form-control"
                                    id="" placeholder="Enter Instagram Link">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 mb-2">
                            <label for="basic-url" class="form-label">WhatsApp Link</label>
                            <div class="input-group">
                                <input type="url" name="whatsapplink" value="{{$whatsappURL->value}}" class="form-control"
                                    id="" placeholder="Enter Whatsapp Link">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 mb-2">
                            <label for="basic-url" class="form-label">LinkedIn Link</label>
                            <div class="input-group">
                                <input type="url" name="linkedin" value="{{$linkedInURL->value}}" class="form-control"
                                    id="" placeholder="Enter LinkedIn Link">
                            </div>
                        </div>
                    </div>
                    
                    <h2 class="theme-title">Contact Us Details</h2>
                    <div class="row mb-2">
                        <div class="col-lg-6 col-md-6 mb-2">
                            <label for="basic-url" class="form-label">Email Address</label>
                            <div class="input-group">
                                <input type="email" name="emaillink" value="{{$mail->value}}" class="form-control" id=""
                                    placeholder="Enter Email Link">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 mb-2">
                            <label for="basic-url" class="form-label">Phone Number</label>
                            <div class="input-group">
                                <input type="number" name="phoneNumber" value="{{$setting[2]['value']}}" class="form-control" id=""
                                    placeholder="Enter Email Link">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 mb-2">
                            <label for="basic-url" class="form-label">Address</label>
                            <div class="input-group">
                                <input type="text" name="company_address" value="{{$setting[0]['value']}}" class="form-control" placeholder="Enter Company Address">
                            </div>
                        </div>
                    </div>
                    <div class="mt-3 mb-2">
                        <button type="submit" class="btn btn-warning custom-btn">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
<script type="text/javascript">
setTimeout(function() {
            $('#alertID').hide('slow')
        }, 2000);
</script>
@endsection
