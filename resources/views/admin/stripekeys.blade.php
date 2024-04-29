@extends('admin_layouts.master')
@section('content')
@section('title', '- Course Plans')
<style>
    .course-alert{
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
                <a href="#" class="close" data-dismiss="alert"
                    aria-label="close"></a> {{ session('success') }}
            </div>
            @endif
            @if (session()->has('error'))
                <div class="alert alert-danger" id="alertID">
                    <a href="#" class="close" data-dismiss="alert"
                        aria-label="close"></a> {{ session('error') }}
                </div>
            @endif 
        </div>
        <div class="lesson-wrapper">
            <div class="detail-head-arrea border-bottom mb-2">
                <div>
                    <h1>Strip Settings</h1>
                </div>
            </div>
            <div>
                <form action="{{route('update.stripe')}}" method="POST">
                    @csrf
                    <div class="row mb-2">
                        <div class="col-lg-6 col-md-6 mb-2">
                            <label for="basic-url" class="form-label">App Security</label>
                            <div class="input-group">
                                <input type="text" name="Stripe_Secret" value="{{$getStripeSecret}}" class="form-control" id="" placeholder="App Security">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 mb-2">
                            <label for="basic-url" class="form-label">App Key</label>
                            <div class="input-group">
                                <input type="text" name="Stripe_Key" value="{{$getStripeKeys}}" class="form-control" id="" placeholder="App Security">
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
    setTimeout(function(){ 
        $('#alertID').hide('slow')
        }, 2000);
</script>
@endsection