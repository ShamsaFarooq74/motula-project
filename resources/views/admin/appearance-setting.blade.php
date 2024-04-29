@extends('admin_layouts.master')
@section('content')
<style>
    .course-alert{
        position: absolute;
        right: 0;
        top: 0;
    }
</style>
@section('title', '- Course Plans')
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
            <div class="detail-head-arrea">
                <div>
                    <h1>Interface Appearance</h1>
                    <p class="head-subtitle">Select And Customize Interface Appearance</p>
                </div>
                <div class="d-flex"> 
                    <div class="detail-input">
                        {{-- <input type="search" class="form-control" id="search-text" name="search-area" placeholder="Search here...">
                        <i class="fontello icon-search"></i> --}}
                    </div>
                    <div>
                        <a href="{{route('new.appearance.setting')}}"><button type="button" class="btn btn-warning custom-btn">Add Theme</button></a>
                    </div>
                </div>
            </div>
            <div>
                <div class="row">
                    @foreach($getAllThemes as $theme)
                    <div class="col-lg-3">
                        <div class="card-holder position-relative">
                            <div class="card-bg-color-area">
                                <div class="card-inner-bg">
                                    <div class="first-width-div"></div>
                                    <div class="first-width-div second-width-div"></div>
                                </div>
                            </div>
                            <div class="dropdown card-dropdown-btn">
                                <button class="btn header-btn-arrow dropdown-toggle p-0 table-action-icon card-icon-right" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                </button>
                                <ul class="dropdown-menu header-menu" aria-labelledby="dropdownMenuButton1">
                                <li><a class="dropdown-item" href="{{route('set.default.theme',['id'=>$theme->id])}}">Apply</a></li>
                                @if($theme->is_editable == 'Y')
                                    <li><a class="dropdown-item" href="{{route('update.appearance',['id'=>$theme->id])}}">Update</a></li>
                                    <li><a class="dropdown-item" href="{{route('delete.theme',['id'=>$theme->id])}}">Delete</a></li>
                                @endif
                                </ul>
                            </div>
                            <div class="login-content-checkbox">
                                <label class="container-checkbox_vt">
                                    <input type="radio" name="radio" {{$theme->id == $getActiveTheme ? 'checked' : ''}}>
                                    <span class="checkmark" style="border-radius:50%;"></span>
                                </label>
                            </div>
                        </div>
                        <h1 class="card-title_vt">{{$theme->theme_name}}</h1>
                    </div>
                    @endforeach
                </div>
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