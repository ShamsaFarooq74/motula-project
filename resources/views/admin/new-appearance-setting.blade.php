@extends('admin_layouts.master')
@section('content')

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/2.5.1/css/bootstrap-colorpicker.min.css"
        rel="stylesheet">

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
                @if($getThemeData == null)
                <div>
                    <h1>New Interface Appearance</h1>
                </div>
                @else
                <div>
                    <h1>Update Interface Appearance</h1>
                </div>
                @endif
            </div>
            <div>
                <form action="{{route('save.appearance')}}" method="POST">
                    @csrf
                <div class="row">
                    @if($getThemeData != null)
                    <input type="hidden" name="theme_id" value="{{$getThemeData->id}}">
                    @endif
                    <div class="col-lg-12 col-md-6 col-sm-6 mb-2">
                        <div class="form-group_vt">
                            <div class="" class="col-sm-12"
                                class="input-group colorpicker-component">
                                <label for="title">Appearance Name</label>
                                <input type="text" name="theme_name" class="form-control demo"
                                    data-control="hue" value="{{$getThemeData == null ? '' : $getThemeData->theme_name}}">
                                <span class="input-group-addon"><i></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 mb-2">
                        <div class="form-group_vt">
                            <div class="cl-picker" class="col-sm-12"
                                class="input-group colorpicker-component">
                                <label for="title">Button Color</label>
                                <input type="text" name="BTN_BG_COLOR" class="form-control demo"
                                    data-control="hue" value="{{$getThemeData == null ? '#ed6868' : $getThemeData->BTN_BG_COLOR}}">
                                <span class="input-group-addon"><i></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 mb-2">
                        <div class="form-group_vt">
                            <div class="cl-picker" class="col-sm-12"
                                class="input-group colorpicker-component">
                                <label for="title">Button Text Color</label>
                                <input type="text" name="BTN_COLOR" class="form-control demo"
                                    data-control="hue" value="{{$getThemeData == null ? '#ed6868' : $getThemeData->BTN_COLOR}}">
                                <span class="input-group-addon"><i></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 mb-2">
                        <div class="form-group_vt">
                            <div class="cl-picker" class="col-sm-12"
                                class="input-group colorpicker-component">
                                <label for="title">Button Shadow Color</label>
                                <input type="text" name="BTN_SHADOW_COLOR" class="form-control demo"
                                    data-control="hue" value="{{$getThemeData == null ? '#ed6868' : $getThemeData->BTN_SHADOW_COLOR}}">
                                <span class="input-group-addon"><i></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 mb-2">
                        <div class="form-group_vt">
                            <div class="cl-picker" class="col-sm-12"
                                class="input-group colorpicker-component">
                                <label for="title">Heading Color</label>
                                <input type="text" name="HEADING_COLOR" class="form-control demo"
                                    data-control="hue" value="{{$getThemeData == null ? '#ed6868' : $getThemeData->HEADING_COLOR}}">
                                <span class="input-group-addon"><i></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 mb-2">
                        <div class="form-group_vt">
                            <div class="cl-picker" class="col-sm-12"
                                class="input-group colorpicker-component">
                                <label for="title">Text Color</label>
                                <input type="text" name="TEXT_COLOR" class="form-control demo"
                                    data-control="hue" value="{{$getThemeData == null ? '#ed6868' : $getThemeData->TEXT_COLOR}}">
                                <span class="input-group-addon"><i></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 mb-2">
                        <div class="form-group_vt">
                            <div class="cl-picker" class="col-sm-12"
                                class="input-group colorpicker-component">
                                <label for="title">Sub-Heading Color</label>
                                <input type="text" name="SUB_HEADING_COLOR" class="form-control demo"
                                    data-control="hue" value="{{$getThemeData == null ? '#ed6868' : $getThemeData->SUB_HEADING_COLOR}}">
                                <span class="input-group-addon"><i></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 mb-2">
                        <div class="form-group_vt">
                            <div class="cl-picker" class="col-sm-12"
                                class="input-group colorpicker-component">
                                <label for="title">Icon Background Color</label>
                                <input type="text" name="BG_COLOR" class="form-control demo"
                                    data-control="hue" value="{{$getThemeData == null ? '#ed6868' : $getThemeData->BG_COLOR}}">
                                <span class="input-group-addon"><i></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 mb-2">
                        <div class="form-group_vt">
                            <div class="cl-picker" class="col-sm-12"
                                class="input-group colorpicker-component">
                                <label for="title">Icon Color</label>
                                <input type="text" name="ICON_COLOR" class="form-control demo"
                                    data-control="hue" value="{{$getThemeData == null ? '#ed6868' : $getThemeData->ICON_COLOR}}">
                                <span class="input-group-addon"><i></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 mb-2">
                        <div class="form-group_vt">
                            <div class="cl-picker" class="col-sm-12"
                                class="input-group colorpicker-component">
                                <label for="title">Radio Background Color</label>
                                <input type="text" name="RADIO_BG_COLOR" class="form-control demo"
                                    data-control="hue" value="{{$getThemeData == null ? '#ed6868' : $getThemeData->RADIO_BG_COLOR}}">
                                <span class="input-group-addon"><i></i></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-3 mb-2">
                    @if($getThemeData == null)
                        <button type="submit" class="btn btn-warning custom-btn">Save</button>
                        <button type="submit" class="btn btn-warning custom-btn" name="apply">Save and  Apply</button>
                    @else
                    <button type="submit" class="btn btn-warning custom-btn">Update</button>
                    <button type="submit" class="btn btn-warning custom-btn" name="apply">Update and  Apply</button>
                    @endif
                </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/2.5.1/js/bootstrap-colorpicker.min.js">
    </script>
    <script type="text/javascript">
        $('.cl-picker').colorpicker();
        setTimeout(function(){
        $('#alertID').hide('slow')
        }, 2000);
    </script>

</div>
@endsection