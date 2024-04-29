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
                <form method="POST" action="{{ route('register') }}" class="card p-2" enctype='multipart/form-data'>
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <h3 class="page_head_vt">Create Tracker</h3>
                        </div>
                        <div class="col-md-12">
                            @include('layouts.admin.blocks.inc.responseMessage')
                        </div>
                        <input type="hidden" name="role" value="2">
                        <div class="col-md-6 form-group">
                            <label for="">Username <small class="text-danger">*</small></label>
                            <input id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" placeholder="Enter Username" required autocomplete="username" autofocus>
                            @error('username')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="">Full Name <small class="text-danger">*</small></label>
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" placeholder="Enter Full Name" required autocomplete="name">
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="">Email <small class="text-danger">*</small></label>
                            <input id="email" type="text" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="Enter Email" required autocomplete="email">
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="">Contact Number <small class="text-danger">*</small></label>
                            <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" name="phone"
                                   value="{{ old('phone') }}" onkeypress="return isNumberKey(event)" onkeydown="return event.keyCode !== 69" placeholder="Enter Contact Number" required autocomplete="phone">
                            @error('phone')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="">Password <small class="text-danger">*</small></label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" value="{{ old('password') }}" placeholder="Enter Password" required autocomplete="password">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="">Confirm Password <small class="text-danger">*</small></label>
                            <input id="password_confirmation" type="password" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" value="{{ old('password_confirmation') }}" placeholder="Confirm Password" required autocomplete="password_confirmation">
                            @error('password_confirmation')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-12 form-group">
                            <label class="control-label">Add Image<span class="text-primary"> *</span></label>
                            <br>
                            <input type="file" class="dropify @error('file') is-invalid @enderror" data-height="300" name="file" />
                            @error('file')
                            <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="col-md-6"></div>
                        <div class="col-md-6">
                            <div class="form-group mb-0 text-center">
                                <button class="btn btn_cancel_vt"> Cancel </button>
                                <button class="btn btn_btn_vt" type="submit"> Create </button>
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
