@extends('admin_layouts.master')
@section('content')
@section('title', '- SMTP')
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
                    <h1>SMTP Settings</h1>
                </div>
            </div>
            <div>
                <form action="{{ url('add-smtp') }}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="row mb-2">
                        <div class="col-lg-6 col-md-6 mb-2">
                            <label for="basic-url" class="form-label">From Email<span class="text-danger fs-5">*</span></label>
                            <div class="input-group">
                                <input type="email" name="email" value="{{ $smtp_from_email->value }}"
                                    class="form-control" id="" placeholder="From Email" required>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 mb-2">
                            <label for="basic-url" class="form-label">From Name<span class="text-danger fs-5">*</span></label>
                            <div class="input-group">
                                <input type="name" name="formname" value="{{ $smtp_from_name->value }}"
                                    class="form-control" id="" placeholder="From Name" required>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 mb-2">
                            <label for="basic-url" class="form-label">Encryption Type<span class="text-danger fs-5">*</span></label>
                            <select class="form-select" aria-label="Default select example" name="encryption">
                                <option value="Tls" {{ $smtp_encryption->value == 'TLS' ? 'selected' : '' }}>Tls
                                </option>
                                <option value="SSL" {{ $smtp_encryption->value == 'SSL' ? 'selected' : '' }}>SSL
                                </option>
                            </select>
                        </div>
                        <div class="col-lg-6 col-md-6 mb-2">
                            <label for="basic-url" class="form-label">User Name<span class="text-danger fs-5">*</span></label>
                            <div class="input-group">
                                <input type="text" name="username" value="{{ $smtp_user_name->value }}"
                                    class="form-control" id="" placeholder="User Name" required>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 mb-2">
                            <label for="basic-url" class="form-label">SMTP Host<span class="text-danger fs-5">*</span></label>
                            <div class="input-group">
                                <input type="text" name="smtphost" value="{{ $smtp_host->value }}"
                                    class="form-control" id="" placeholder="SMTP Host" required>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 mb-2">
                            <label for="basic-url" class="form-label">Password<span class="text-danger fs-5">*</span></label>
                            <div class="input-group">
                                <input type="password" name="password" value="{{ $smtp_password->value }}" class="form-control"
                                    id="" placeholder="Password" required>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 mb-2">
                            <label for="basic-url" class="form-label">Port<span class="text-danger fs-5">*</span></label>
                            <div class="input-group">
                                <input type="number" name="port" value="{{ $smtp_port->value }}"
                                    class="form-control" id="" placeholder="Port" required>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3 mb-2">
                        <button type="submit" class="btn btn-warning custom-btn">Add</button>
                    </div>
            </div>
            </form>
        </div>
    </div>

</div>
<script type="text/javascript">
setTimeout(function() {
            $('#alertID').hide('slow')
        }, 2000);
</script>
@endsection
