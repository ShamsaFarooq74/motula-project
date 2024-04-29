@extends('web.layouts.master')
@section('content')
        <!-- Start Page Content here -->
        <div class="content">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="banner_sub_vt">
                        <div class="banner_sub_home">
                            <h2>Settings</h2>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Start Cate itme ?-->
            <div class="container my-4">
                <div class="row">
                    <div class="col-md-3">
                        <div class="left_menu_vt">
                            <ul>
                                <li><a href="#"><img src="{{ asset('web/images/flask_01.png') }}" alt="">
                                        Imporetd Products <i class="fa fa-angle-right"></i></a></li>
                                <li><a href="#"><img src="{{ asset('web/images/flask_02.png') }}" alt=""> Favorite
                                        Products <i class="fa fa-angle-right"></i></a></li>
                                <li><a href="#"><img src="{{ asset('web/images/flask_03.png') }}" alt="">
                                        Buyer
                                        Requirements Mgr <i class="fa fa-angle-right"></i></a></li>
                                <li><a href="#" class="active"><img src="{{ asset('web/images/flask_04.png') }}" alt="">
                                        Setting <i class="fa fa-angle-right"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="update_profile_setting">
                            <h3>Update Profile</h3>
                            <form>
                                <div class="form-group">
                                    <input type="text" class="form-control" id="exampleInputEmail1"
                                        aria-describedby="emailHelp" placeholder="Natasha">
                                </div>
                                <div class="form-group">
                                    <input type="email" class="form-control" id="exampleInputEmail1"
                                        aria-describedby="emailHelp" placeholder="natasha@gmail.com">
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control" id="exampleInputPassword1"
                                        placeholder="*********">
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control" id="exampleInputPassword1"
                                        placeholder="*********">
                                </div>
                                <div class="form-group">
                                    <select class="form-control" id="exampleFormControlSelect1">
                                        <option>Lahore</option>
                                        <option>2</option>
                                        <option>3</option>
                                        <option>4</option>
                                        <option>5</option>
                                    </select>
                                </div>
                                <button class="order_next_vt" type="submit" class="btn btn-primary">Update Profile</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>


        </div>
@endsection