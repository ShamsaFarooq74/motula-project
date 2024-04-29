@extends('layouts.admin.master')
@section('content')

    <!-- Topbar Start -->
    @include('layouts.admin.blocks.inc.topnavbar')
    <!-- end Topbar -->

    <!-- Start Content-->
    <div class="container-fluid mt-3">

        <div class="row">
            <div class="col-xl-12 home_custome_table">
                <div class="card-box home_table">
                    <h4 class="header-title_vt mb-3 pl-2">Sellers Listing</h4>
                    <div class="form_search_vt">
                        <input type="text" placeholder="Search by business name" class="form-control"
                               onkeyup="searchData(this.value);">
                    </div>
                    <div class="card_tabs_vt">
                        <ul class="nav nav-tabs nav-bordered">
                            <li class="nav-item">
                                <a href="#home-b1" data-toggle="tab" aria-expanded="false" class="nav-link" id="link1"
                                   onclick="status('local')">
                                    Local Seller
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#profile-b1" data-toggle="tab" aria-expanded="true" class="nav-link active" id="link2"
                                   onclick="status('global')">
                                    Global Seller
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane" id="home-b1">
                                <div class="table-responsive">
                                    <table class="table table-borderless table-hover table-centered m-0">
                                        <thead class="thead-light">
                                        <tr>
                                            <th>Sr</th>
                                            <th>Business Name</th>
                                            <th>Phone Number</th>
                                            <th>Address</th>
                                            <th>No of Products</th>
                                            <th>Membership</th>
                                            <th>Rating</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody id="localSeller">
                                        @foreach($users as $key => $user)
                                            @if($user->username)
                                                <tr>
                                                    <td>
                                                        {{$key + $users->firstItem()}}
                                                    </td>
                                                    <td>
                                                        <a href="{{route('company.profile',['id'=>$user->id])}}">
                                                            <img src="{{$user->image}}" alt="">

                                                            {{$user->username}}
                                                        </a>
                                                    </td>
                                                    <td>
                                                        {{$user->phone}}
                                                    </td>
                                                    <td>
                                                        @if ($user->address != [])
                                                            {{$user->address}}
                                                        @else
                                                            --
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{$user->product}}
                                                    </td>
                                                    <td>
                                                        {{$user->membership}}
                                                    </td>
                                                    <td>
                                                        <div class="star-rating-area">
                                                            <div class="rating-static clearfix "
                                                                 rel="{{$user->rating}}">
                                                                <label class="full" title="Awesome - 5 stars"></label>
                                                                <label class="half"
                                                                       title="Pretty good - 4.5 stars"></label>
                                                                <label class="full"
                                                                       title="Pretty good - 4 stars"></label>
                                                                <label class="half" title="Meh - 3.5 stars"></label>
                                                                <label class="full" title="Meh - 3 stars"></label>
                                                                <label class="half"
                                                                       title="Kinda bad - 2.5 stars"></label>
                                                                <label class="full" title="Kinda bad - 2 stars"></label>
                                                                <label class="half" title="Meh - 1.5 stars"></label>
                                                                <label class="full"
                                                                       title="Sucks big time - 1 star"></label>
                                                                <label class="half"
                                                                       title="Sucks big time - 0.5 stars"></label>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="btn-group mb-2">
                                                            <button type="button" class="btn btn_info dropdown-toggle"
                                                                    data-toggle="dropdown" aria-haspopup="true"
                                                                    aria-expanded="false">Action <i
                                                                    class="mdi mdi-chevron-down"></i></button>
                                                            <div class="dropdown-menu">
                                                                {{--                                                            <a class="dropdown-item" href="#">Block</a>--}}
                                                                <a class="dropdown-item"
                                                                   onclick="window.location='{{route('company.user', ['id' => $user->id])}}'">Edit</a>
                                                                <button type="button" class="dropdown-item deleteUser"
                                                                        data-toggle="modal"
                                                                        data-target=".tracker-list-8767"
                                                                        data-id="{{$user->id}}">Block
                                                                </button>
                                                            </div>
                                                        </div><!-- /btn-group -->
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                        </tbody>
                                    </table>
                                    @if($users)
                                        {!! $users->render() !!}
                                    @endif
                                </div>
                            </div>
                            <div class="tab-pane show active" id="profile-b1">
                                <div class="table-responsive">
                                    <table class="table table-borderless table-hover table-centered m-0">
                                        <thead class="thead-light">
                                        <tr>
                                            <th>Sr</th>
                                            <th>Business Name</th>
                                            <th>Phone Number</th>
                                            <th>Address</th>
                                            <th>No of Products</th>
                                            <th>Membership</th>
                                            <th>Rating</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody id="globalSeller">
                                        @foreach($globalSellers as $key => $globalSeller)
                                            @if($globalSeller->username)
                                                <tr>
                                                    <td>
                                                        {{$key + 1}}
                                                    </td>
                                                    <td>
                                                        <a href="{{route('company.profile',['id'=>$globalSeller->id])}}">
                                                            <img src="{{$globalSeller->image}}" alt="">

                                                            {{$globalSeller->username}}
                                                        </a>
                                                    </td>
                                                    <td>
                                                        {{$globalSeller->phone}}
                                                    </td>
                                                    <td>
                                                        @if ($globalSeller->address != [])
                                                            {{$globalSeller->address}}
                                                        @else
                                                            --
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{$globalSeller->product}}
                                                    </td>
                                                    <td>
                                                        {{$globalSeller->membership}}
                                                    </td>
                                                    <td>
                                                        <div class="star-rating-area">
                                                            <div class="rating-static clearfix "
                                                                 rel="{{$globalSeller->rating}}">
                                                                <label class="full" title="Awesome - 5 stars"></label>
                                                                <label class="half"
                                                                       title="Pretty good - 4.5 stars"></label>
                                                                <label class="full"
                                                                       title="Pretty good - 4 stars"></label>
                                                                <label class="half" title="Meh - 3.5 stars"></label>
                                                                <label class="full" title="Meh - 3 stars"></label>
                                                                <label class="half"
                                                                       title="Kinda bad - 2.5 stars"></label>
                                                                <label class="full" title="Kinda bad - 2 stars"></label>
                                                                <label class="half" title="Meh - 1.5 stars"></label>
                                                                <label class="full"
                                                                       title="Sucks big time - 1 star"></label>
                                                                <label class="half"
                                                                       title="Sucks big time - 0.5 stars"></label>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="btn-group mb-2">
                                                            <button type="button" class="btn btn_info dropdown-toggle"
                                                                    data-toggle="dropdown" aria-haspopup="true"
                                                                    aria-expanded="false">Action <i
                                                                    class="mdi mdi-chevron-down"></i></button>
                                                            <div class="dropdown-menu">
                                                                {{--                                                                <a class="dropdown-item" href="#">Block</a>--}}
                                                                <a class="dropdown-item"
                                                                   onclick="window.location='{{route('company.user', ['id' => $globalSeller->id])}}'">Edit</a>
                                                                <button type="button" class="dropdown-item deleteUser"
                                                                        data-toggle="modal"
                                                                        data-target=".tracker-list-8767"
                                                                        data-id="{{$globalSeller->id}}">Block
                                                                </button>
                                                            </div>
                                                        </div><!-- /btn-group -->
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @if($globalSellers)
                                    {!! $globalSellers->render() !!}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end row -->

    </div> <!-- container -->

    <div class="modal fade tracker-list-8767 deleteModal" tabindex="-1" role="dialog"
         aria-labelledby="myCenterModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-dialog-centered">
            <input type="hidden" id="dltUserID">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <i class="fas fa-exclamation"></i>
                    <h4 class="model-heading-vt">Are you sure to block <br>this Sellers ?</h4>
                    <div class="modal-footer">
                        <button type="button" class="btn_create_vt deleteConfirm">Yes, Block</button>
                        <button type="button" class="btn_close_vt" data-dismiss="modal" id="user-cancel">Close</button>
                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>

        if(window.location.search == "?local_seller_page=2" || window.location.search =="?local_seller_page=1" || window.location.search =="?local_seller_page=3"){
           document.getElementById('link1').className ="nav-link active";
           document.getElementById('link2').className ="nav-link";
           var x = "true";
           var y = "false"
           document.getElementById('link1').setAttribute("aria-expanded",x);
           document.getElementById('link2').setAttribute('aria-expanded',y);
           $('#home-b1').addClass("show active");
           $('#profile-b1').removeClass("show active");
        //    document.getElementById('home-b1').classList.add("show active");
        //    document.getElementById('profile-b1').classList.remove("show active")

        }


        $('.deleteUser').click(function () {
            var data = $(this).attr('data-id');
            $('.deleteModal #dltUserID').val(data);

        });

        $('.deleteConfirm').click(function () {

            var id = $('.deleteModal #dltUserID').val();
            $.ajax({
                url: 'delete-users',
                type: 'post',
                data: {"_token": "{{csrf_token()}}", 'id': id},
                dataType: 'json',
                success: function (response) {
                    if (response.status) {
                        swal("User Blocked Successfully!");
                        document.getElementById('user-cancel').click();
                        setTimeout(function () {
                            window.location.reload()
                        }, 2000);
                    } else {
                        swal("User could not be  blocked!");
                        document.getElementById('user-cancel').click();
                        setTimeout(function () {
                            window.location.reload()
                        }, 2000);
                    }

                }
            });
        });
        let searchStatus = 'global';

        function status(status) {
            searchStatus = status;
        }

        function searchData(value) {
            if (value) {
                let type = '';
                $.ajax({
                    url: '{{route('seller.search')}}',
                    type: 'get',
                    data: {'value': value, 'type': searchStatus},
                    dataType: 'json',
                    success: function (response) {
                        let data = response.data;
                        if (searchStatus === 'global') {
                            document.getElementById('globalSeller').innerHTML = '';
                            for (let i = 0; i < data.length; i++) {
                                var companyProfile = '{{ route("company.profile", ":id") }}';
                                companyProfile = companyProfile.replace(':id', data[i]['id']);
                                var companyUser = '{{ route("company.user", ":id") }}';
                                companyUser = companyUser.replace(':id', data[i]['id']);
                                let sellersMembership = data[i]['membersip'];
                                if(!sellersMembership)
                                {
                                    sellersMembership = '';
                                }
                                if (data[i]['username']) {
                                    let id = data[i]['id'];
                                    let tableBody = `<tr>
                                                <td>
                                                    ${id + 1}
                                    </td>
                                    <td>
                                        <a href="${companyProfile}">
                                                        <img src="${data[i]['image']}" alt="">

                                                        ${data[i]['username']}
                                    </a>
                                </td>
                                <td>
                                     ${data[i]['phone']}
                                    </td>
                                    <td>
                                     ${data[i]['address']}
                                    </td>
                                    <td>
                                      ${data[i]['product']}
                                    </td>
                                    <td>
                                      ${sellersMembership}
                                    </td>
                                    <td>
                                        <div class="star-rating-area">
                                            <div class="rating-static clearfix " rel="${data[i]['rating']}">
                                                            <label class="full" title="Awesome - 5 stars"></label>
                                                            <label class="half" title="Pretty good - 4.5 stars"></label>
                                                            <label class="full" title="Pretty good - 4 stars"></label>
                                                            <label class="half" title="Meh - 3.5 stars"></label>
                                                            <label class="full" title="Meh - 3 stars"></label>
                                                            <label class="half" title="Kinda bad - 2.5 stars"></label>
                                                            <label class="full" title="Kinda bad - 2 stars"></label>
                                                            <label class="half" title="Meh - 1.5 stars"></label>
                                                            <label class="full" title="Sucks big time - 1 star"></label>
                                                            <label class="half" title="Sucks big time - 0.5 stars"></label>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="btn-group mb-2">
                                                        <button type="button" class="btn btn_info dropdown-toggle"
                                                                data-toggle="dropdown" aria-haspopup="true"
                                                                aria-expanded="false">Action <i
                                                                class="mdi mdi-chevron-down"></i></button>
                                                        <div class="dropdown-menu">
                                    <a class="dropdown-item" onclick="window.location='${companyUser}'">Edit</a>
                                                             <button type="button" class="dropdown-item deleteUser" data-toggle="modal" data-target=".tracker-list-8767" data-id="${data[i]['id']}">Block</button>
                                                        </div>
                                                    </div><!-- /btn-group -->
                                                </td>
                                            </tr>`
                                    $("#globalSeller").append(tableBody);
                                    $('.deleteUser').click(function () {
                                        var data = $(this).attr('data-id');
                                        $('.deleteModal #dltUserID').val(data);
                                    });
                                }
                            }
                        } else {
                            document.getElementById('localSeller').innerHTML = '';
                            for (let i = 0; i < data.length; i++) {
                                companyProfile = '{{ route("company.profile", ":id") }}';
                                companyProfile = companyProfile.replace(':id', data[i]['id']);
                                companyUser = '{{ route("company.user", ":id") }}';
                                companyUser = companyUser.replace(':id', data[i]['id']);
                                let sellersMembership = data[i]['membersip'];
                                if(!sellersMembership)
                                {
                                    sellersMembership = '--';
                                }
                                if (data[i]['username']) {
                                    let id = data[i]['id'];
                                    let tableBody = `<tr>
                                                <td>
                                                    ${id + 1}
                                    </td>
                                    <td>
                                        <a href="${companyProfile}">
                                                        <img src="${data[i]['image']}" alt="">

                                                        ${data[i]['username']}
                                    </a>
                                </td>
                                <td>
                                   ${data[i]['phone']}
                                    </td>
                                    <td>
                                   ${data[i]['address']}
                                    </td>
                                    <td>
                                    ${data[i]['product']}
                                    </td>
                                    <td>
                                    ${sellersMembership}
                                    </td>
                                    <td>
                                        <div class="star-rating-area">
                                            <div class="rating-static clearfix " rel="${data[i]['rating']}">
                                                            <label class="full" title="Awesome - 5 stars"></label>
                                                            <label class="half" title="Pretty good - 4.5 stars"></label>
                                                            <label class="full" title="Pretty good - 4 stars"></label>
                                                            <label class="half" title="Meh - 3.5 stars"></label>
                                                            <label class="full" title="Meh - 3 stars"></label>
                                                            <label class="half" title="Kinda bad - 2.5 stars"></label>
                                                            <label class="full" title="Kinda bad - 2 stars"></label>
                                                            <label class="half" title="Meh - 1.5 stars"></label>
                                                            <label class="full" title="Sucks big time - 1 star"></label>
                                                            <label class="half" title="Sucks big time - 0.5 stars"></label>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="btn-group mb-2">
                                                        <button type="button" class="btn btn_info dropdown-toggle"
                                                                data-toggle="dropdown" aria-haspopup="true"
                                                                aria-expanded="false">Action <i
                                                                class="mdi mdi-chevron-down"></i></button>
                                                        <div class="dropdown-menu">
                                    <a class="dropdown-item" onclick="window.location='${companyUser}'">Edit</a>
                                                             <button type="button" class="dropdown-item deleteUser" data-toggle="modal" data-target=".tracker-list-8767" data-id="${data[i]['id']}">Block</button>
                                                        </div>
                                                    </div><!-- /btn-group -->
                                                </td>
                                            </tr>`
                                    $("#localSeller").append(tableBody);
                                    $('.deleteUser').click(function () {
                                        var data = $(this).attr('data-id');
                                        $('.deleteModal #dltUserID').val(data);
                                    });
                                }
                            }
                        }
                    },

                });
            } else {
                window.location.reload()
            }
        }

    </script>

@endsection
