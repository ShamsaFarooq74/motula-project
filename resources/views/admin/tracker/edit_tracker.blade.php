@extends('layouts.admin.master')
@section('content')

    <!-- Topbar Start -->
    @include('layouts.admin.blocks.inc.topnavbar')
    <!-- end Topbar -->

    <!-- Start Content-->
    <div class="container-fluid mt-3">


        <div class="row">
            @include('admin.tracker.tracker_side_bar')

            <div class="col-xl-9">
                <form method="POST" action="{{ route('update.tracker') }}" class="card p-2" enctype='multipart/form-data'>
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <h3 class="page_head_vt">Edit Tracker</h3>
                        </div>
                        <div class="col-md-12">
                            @include('layouts.admin.blocks.inc.responseMessage')
                        </div>
                        <input type="hidden" name="role" value="2">
                        <input type="hidden" name="tracker_id" value="{{$tracker['id']}}">
                        <div class="col-md-6 form-group">
                            <label for="">Username <small class="text-danger">*</small></label>
                            <input id="username" type="text" class="form-control @error('username') is-invalid @enderror"
                                   name="username"  placeholder="Enter Username"
                                   required autocomplete="username" autofocus value="{{$tracker['username']}}">
                            @error('username')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="">Full Name <small class="text-danger">*</small></label>
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                                   value="{{$tracker['name']}}" placeholder="Enter Full Name" required autocomplete="name">
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="">Email <small class="text-danger">*</small></label>
                            <input id="email" type="text" class="form-control @error('email') is-invalid @enderror" name="email"
                                   value="{{$tracker['email']}}" placeholder="Enter Email" required autocomplete="email">
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="">Contact Number <small class="text-danger">*</small></label>
                            <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" name="phone"
                                   value="{{$tracker['phone']}}" onkeypress="return isNumberKey(event)" onkeydown="return event.keyCode !== 69" placeholder="Enter Contact Number" required autocomplete="phone">
                            @error('phone')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="">Password <small class="text-danger"></small></label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                                   name="password"  placeholder="Enter Password"  autocomplete="password">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="">Confirm Password <small class="text-danger"></small></label>
                            <input id="password_confirmation" type="password" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" value="{{ old('password_confirmation') }}" placeholder="Confirm Password"  autocomplete="password_confirmation">
                            @error('password_confirmation')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-12 form-group">
                            <label class="control-label">Add Image<span class="text-primary"> *</span></label>
                            <br>
                            <input type="file" class="dropify  " data-height="300" name="file" />
                            <small><br>{{$tracker['image']}}</small>
                        </div>
                        <div class="col-md-6"></div>
                        <div class="col-md-6">
                            <div class="form-group mb-0 text-center">
                                <a href="{{ route('tracker.list') }}" class="btn btn_cancel_vt"> Cancel </a>
                                <button class="btn btn_btn_vt" type="submit"> Update </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div> <!-- end col -->
        </div>
        <!-- end row -->

    </div> <!-- container -->
    <script>
        function isNumberKey(evt) {
            var charCode = (evt.which) ? evt.which : event.keyCode
            if (charCode > 31 && (charCode < 48 || charCode > 57  ) && charCode != 43)
                return false;
            return true;
        }
    </script>
@endsection
