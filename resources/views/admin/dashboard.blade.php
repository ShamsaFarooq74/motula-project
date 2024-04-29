@extends('layouts.admin.master')
@section('content')


    <!-- Topbar Start -->
    @include('layouts.admin.blocks.inc.topnavbar')
    <!-- end Topbar -->

    <!-- Start Content-->
    <div class="container-fluid">

        <div class="row">
            <div class="col-md-12 mt-3">
                <div class="widget_area_vt">
                    <ul>
                        <li>
                            <h6>Pending Products</h6>
                            <h4>{{$totalProducts}}</h4>
                            <small>Pending Products </small>
                            <div class="a_1"><img src="{{asset('assets/images/Group_1.png')}}" alt=""></div>
                        </li>
                        <li>
                            <h6>Approved Products</h6>
                            <h4>{{$activeProduct}}</h4>
                            <small>Total Products</small>
                            <div class="a_1"><img src="{{asset('assets/images/Group_2.png')}}" alt=""></div>
                        </li>
                        <li>
                            <h6>Sellers</h6>
                            <h4>{{$sellers}}</h4>
                            <small>Total Sellers</small>
                            <div class="a_1"><img src="{{asset('assets/images/Group_5.png')}}" alt=""></div>
                        </li>
                        <li>
                            <h6>Buyers</h6>
                            <h4>{{$buyers}}</h4>
                            <small>Total Buyers</small>
                            <div class="a_1"><img src="{{asset('assets/images/Group_4.png')}}" alt=""></div>
                        </li>
                        <li>
                            <h6>Installs</h6>
                       
                            <div class="dash_install_section"> 
                                <div class="down_icon">
                                    <div class="total_number">{{$install}}</div>
                                    <img src="{{asset('assets/images/dashboard/total.png')}}" alt="">
                                </div>
                                <span>Total Installs</span>
                                <div class="upper_icon">
                                    <div class="left_data">
                                        <img src="{{asset('assets/images/dashboard/android.png')}}" alt="">
                                        <div class="number">{{$android}}</div>
                                    </div>
                                    <div class="right_data">
                                        <img src="{{asset('assets/images/dashboard/ios.png')}}" alt="">
                                        <div class="number">{{$ios}}</div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div> <!-- end col-->
        </div>
        <!-- end row-->

        <div class="row">
            <div class="col-md-12 home_custome_table mb-3">
                <div class="card-box">
                    <h4 class="header-title_vt mb-3 pl-2">Pending Products</h4>
                    <div class="table-responsive">
                        <table class="table table-borderless table-hover table-centered m-0">
                            <thead class="thead-light">
                            <tr>
                                <th>Sr</th>
                                <th>Product Name</th>
                                <th>Industry</th>
                                <th>Category</th>
                                <th>Sub-Category</th>
                                <th>Price /Unit</th>
                                <th>Images</th>
                                <th>Rating</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($products as $key => $product)
                                @if($product->products_name && $product->sellerName)
                                    <tr>
                                        <td>
                                            {{$key + 1}}
                                        </td>
                                        <td style="cursor:pointer" onclick="window.location='{{route('company.product.detail', ['id' => $product->id])}}'">
                                            {{$product->products_name}}
                                        </td>
                                        <td style="cursor:pointer" onclick="window.location='{{route('company.product.detail', ['id' => $product->id])}}'">
                                            {{$product->sellerName}}
                                        </td>
                                        <td style="cursor:pointer" onclick="window.location='{{route('company.product.detail', ['id' => $product->id])}}'">
                                            {{$product->category}}
                                        </td>
                                        <td style="cursor:pointer" onclick="window.location='{{route('company.product.detail', ['id' => $product->id])}}'">
                                            {{$product->sub_category}}
                                        </td>
                                        <td style="cursor:pointer" onclick="window.location='{{route('company.product.detail', ['id' => $product->id])}}'">
                                        {{$product->currency}} {{$product->price}}/{{$product->unit}}
                                        </td>
                                        <td style="cursor:pointer" onclick="window.location='{{route('company.product.detail', ['id' => $product->id])}}'">
                                            <div class="img_table">
                                                @if($product->attachments)
{{--                                                    @foreach($product->attachments as $attachment)--}}
                                                        <img src="{{$product['attachments']}}" alt="">
{{--                                                    @endforeach--}}
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="star-rating-area">
                                                <div class="rating-static clearfix " rel="{{$product->rating}}">
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
                                                    {{--                                                    <a class="dropdown-item" href="#">Approve</a>--}}
                                                    <a class="dropdown-item" onclick="approveProducts({{$product->id}})">Approve</a>
                                                    <a class="dropdown-item" onclick="window.location='{{route('company.add.product', ['id' => $product->user_id,'productId' => $product->id])}}'">Edit</a>
                                                     <button type="button" class="dropdown-item deleteProducts" data-toggle="modal" data-target=".bs-example-center" data-id="{{$product->id}}">Delete</button>
                                                </div>
                                            </div><!-- /btn-group -->
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- end row -->
        <div class="modal fade bs-example-center deleteModal" tabindex="-1" role="dialog" aria-labelledby="myCenterModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-dialog-centered">
                <input type="hidden" id="dltUserID">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">
                        <i class="fas fa-exclamation"></i>
                        <h4 class="model-heading-vt">Are you sure to delete <br>this Products ?</h4>
                        <div class="modal-footer">
                            <button type="button" class="btn_create_vt deleteProductConfirm">Yes, Delete</button>
                            <button type="button" class="btn_close_vt" data-dismiss="modal" id="lead-cancel">Close</button>
                        </div>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        <div class="row">
            <div class="col-md-12 home_custome_table mb-3">
                <div class="card-box home_table">
                    <h4 class="header-title_vt mb-3 pl-2">Sourcing Leads</h4>
                    <div class="form_search_vt">
                        <input type="text" placeholder="Search by product name" onkeyup="searchData(this.value);" class="form-control">
                    </div>
                    <div class="card_tabs_vt">
                        <ul class="nav nav-tabs nav-bordered" id="dashboardSearch">
                            <li class="nav-item">
                                <a href="#home-b1" data-toggle="tab" aria-expanded="true" class="nav-link active" id="activeTab" onclick="searchLeadStatus('pending')">
                                    Pending
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#profile-b1" data-toggle="tab" aria-expanded="false" class="nav-link "  onclick="searchLeadStatus('active')">
                                    Active
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane show active" id="home-b1">
                                <div class="table-responsive">
                                    <table class="table table-borderless table-hover table-centered m-0">
                                        <thead class="thead-light">
                                        <tr>
                                            <th>Sr</th>
                                            <th>Buyer name</th>
                                            <th>Product name</th>
                                            <th>Product Type</th>
                                            <th>Category</th>
                                            <th>Sub-Category</th>
                                            <th>Quantity /Unit</th>
                                            <th>Date</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody id="pendingProducts">
                                        @foreach($sourcingLeads as $key => $sourcingLead)
                                            @if($sourcingLead->quantity && $sourcingLead->buyerName)
                                                <tr>
                                                    <td>
                                                        {{$key+1}}
                                                    </td>
                                                    <td>
                                                        {{$sourcingLead->buyerName}}
                                                    </td>
                                                    <td>
                                                        {{isset($sourcingLead->product['products_name']) ? $sourcingLead->product['products_name'] : $sourcingLead->product_name}}
                                                    </td>
                                                    <td>
                                                        {{$sourcingLead->type}}
                                                    </td>
                                                    <td>
                                                        {{isset($sourcingLead->product['category']) ? $sourcingLead->product['category'] : $sourcingLead->categories}}
                                                    </td>
                                                    <td>
                                                        {{isset($sourcingLead->product['sub_category']) ? $sourcingLead->product['sub_category'] : 'N/A'}}
                                                    </td>
                                                    <td>
                                                        {{isset($sourcingLead->quantity) ? $sourcingLead->quantity : 0}} {{isset($sourcingLead->unit) ? $sourcingLead->unit : $sourcingLead->units}}
                                                    </td>
                                                    <td>
                                                        <?php $date = date('Y-m-d',strtotime($sourcingLead->created_at)) ?>
                                                        {{$date}}
                                                    </td>
                                                    <td>
                                                        <div class="btn-group mb-2">
                                                            <button type="button" class="btn btn_info dropdown-toggle"
                                                                    data-toggle="dropdown" aria-haspopup="true"
                                                                    aria-expanded="false">Action <i
                                                                    class="mdi mdi-chevron-down"></i></button>
                                                            <div class="dropdown-menu">
                                                                <a class="dropdown-item" onclick="approveLeads({{$sourcingLead->id}})">Approve</a>
                                                                 <button type="button" class="dropdown-item deleteProduct" data-toggle="modal" data-target=".example-modal-center122" data-id="{{$sourcingLead->id}}">Delete</button>
                                                            </div>
                                                        </div><!-- /btn-group -->
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @if($sourcingLeads)
                                    {!! $sourcingLeads->render() !!}
                                @endif
                            </div>

                            <div class="tab-pane" id="profile-b1">
                                <div class="table-responsive">
                                    <table class="table table-borderless table-hover table-centered m-0">
                                        <thead class="thead-light">
                                        <tr>
                                            <th>Sr</th>
                                            <th>Buyer name</th>
                                            <th>Product name</th>
                                            <th>Product Type</th>
                                            <th>Category</th>
                                            <th>Sub-Category</th>
                                            <th>Quantity /Unit</th>
                                            <th>Date</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody id="activeProducts">
                                        @foreach($activeProducts as $key => $activeProduct)
                                            @if($activeProduct->quantity && $activeProduct->buyerName)
                                                <tr>
                                                    <td>
                                                        {{$key+1}}
                                                    </td>
                                                    <td>
                                                        {{$activeProduct->buyerName}}
                                                    </td>
                                                    <td>
                                                        {{isset($activeProduct->product['products_name']) ? $activeProduct->product['products_name'] : $activeProduct->product_name}}
                                                    </td>
                                                    <td>
                                                        {{$activeProduct->type}}
                                                    </td>
                                                    <td>
                                                        {{isset($activeProduct->product['category']) ? $activeProduct->product['category'] : $activeProduct->categories}}
                                                    </td>
                                                    <td>
                                                        {{ isset($activeProduct->product['sub_category']) ? $activeProduct->product['sub_category'] : 'N/A'}}
                                                    </td>
                                                    <td>
                                                        {{ isset($activeProduct->quantity) ? $activeProduct->quantity : 0}} {{ isset($activeProduct->unit) ? $activeProduct->unit : $activeProduct->units}}
                                                    </td>
                                                    <td>
                                                        <?php $date = date('Y-m-d',strtotime($activeProduct->created_at)) ?>
                                                        {{$date}}
                                                    </td>
                                                    <td>
                                                        <div class="btn-group mb-2">
                                                            <button type="button" class="btn btn_info dropdown-toggle"
                                                                    data-toggle="dropdown" aria-haspopup="true"
                                                                    aria-expanded="false" >Action <i
                                                                    class="mdi mdi-chevron-down"></i></button>
                                                            <div class="dropdown-menu">
                                                                 <button type="button" class="dropdown-item deleteProduct" data-toggle="modal" data-target=".example-modal-center122" data-id="{{$activeProduct->id}}">Delete</button>
                                                            </div>
                                                        </div><!-- /btn-group -->
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @if($activeProducts)
                                    {!! $activeProducts->render() !!}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end row -->

        </div> <!-- container -->

<div class="modal fade example-modal-center122 deleteModal" tabindex="-1" role="dialog" aria-labelledby="myCenterModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered">
        <input type="hidden" id="dltUserID">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <i class="fas fa-exclamation"></i>
                <h4 class="model-heading-vt">Are you sure to delete <br>this Lead ?</h4>
                <div class="modal-footer">
                    <button type="button" class="btn_create_vt deleteConfirm">Yes, Delete</button>
                    <button type="button" class="btn_close_vt" data-dismiss="modal" id="lead-cancel">Close</button>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


        <img id="mapMarkerIcon" src="{{ asset('assets/images/map_marker.svg')}}" alt="setting" style="display: none;">

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
        <script>
            function approveProducts(id)
            {
                $.ajax({
                    url: '{{route('products.approve')}}',
                    type: 'post',
                    data: {"_token": "{{csrf_token()}}",'id' : id},
                    dataType: 'json',
                    success: function (response) {
                        if(response.status) {
                            swal("Products approved Successfully!");
                            setTimeout(function(){ window.location.reload() }, 2000);
                        }
                        else
                        {
                            swal("Products could not be approved!");
                        }
                    },

                });
            }
            let searchStatus = 'pending';
            function searchLeadStatus(status)
            {
                searchStatus = status;
            }
            console.log(searchStatus);
            function searchData(value)
            {
                if(value) {
                    let type = '';
                    $.ajax({
                        url: 'search-leads',
                        type: 'get',
                        data: {"_token": "{{csrf_token()}}", 'value': value, 'type': searchStatus},
                        dataType: 'json',
                        success: function (response) {
                            // let table = document.getElementById('activeProducts');
                            if(searchStatus === 'active') {
                                document.getElementById('activeProducts').innerHTML = '';
                                for (let i = 0; i < response.data.length; i++) {
                                    if (response.data[i]['product']) {
                                        let id = response.data[i]['id'];
                                        let tableBody = "<tr>" +
                                            "<td>"
                                            +
                                            i + 1
                                            +
                                            "</td>" +
                                            "<td>"
                                            +
                                            response.data[i]['buyerName']
                                            +
                                            "</td>" +
                                            "<td>"
                                            +
                                            response.data[i]['product']['products_name']
                                            +
                                            "</td>"
                                            +
                                            "<td>"
                                            +
                                            response.data[i]['product']['category']
                                            +
                                            "</td>"
                                            +
                                            "<td>"
                                            +
                                            response.data[i]['product']['sub_category']
                                            +
                                            "</td>"
                                            +
                                            "<td>" +
                                            response.data[i]['quantity'] + ' ' + response.data[i]['unit'] +
                                            "</td>"
                                            +
                                            "<td>"
                                            +
                                            response.data[i]['date']
                                            +
                                            "</td>"
                                            +
                                            "<td>"
                                            +
                                            "<div class='btn-group mb-2'>" +
                                            "<button type='button' class='btn btn_info dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false' >" + 'Action' +
                                            "<i class='mdi mdi-chevron-down'> " + "</i>" + "</button>" +
                                            "<div class='dropdown-menu'>" +
                                            `<button type='button' class='dropdown-item deleteProductId' data-toggle='modal' data-target='.example-modal-center122' data-id='${response.data[i]['id']}'  id='deleteTable'>` + 'Delete' + "</button>" +
                                            "</div>" +
                                            "</div>" +
                                            "</td>" +
                                            "</tr>"
                                        $("#activeProducts").append(tableBody);
                                        $('.deleteProductId').click(function () {
                                            var data = $(this).attr('data-id');
                                            $('.deleteModal #dltUserID').val(data);

                                        });
                                    }
                                }
                            }
                            else
                            {

                                document.getElementById('pendingProducts').innerHTML = '';
                                for (let i = 0; i < response.data.length; i++) {
                                    if (response.data[i]['product']) {
                                        let id = response.data[i]['id'];
                                        let tableBody = "<tr>" +
                                            "<td>"
                                            +
                                            i + 1
                                            +
                                            "</td>" +
                                            "<td>"
                                            +
                                            response.data[i]['buyerName']
                                            +
                                            "</td>" +
                                            "<td>"
                                            +
                                            response.data[i]['product']['products_name']
                                            +
                                            "</td>"
                                            +
                                            "<td>"
                                            +
                                            response.data[i]['product']['category']
                                            +
                                            "</td>"
                                            +
                                            "<td>"
                                            +
                                            response.data[i]['product']['sub_category']
                                            +
                                            "</td>"
                                            +
                                            "<td>" +
                                            response.data[i]['quantity'] + ' ' + response.data[i]['unit'] +
                                            "</td>"
                                            +
                                            "<td>"
                                            +
                                            response.data[i]['date']
                                            +
                                            "</td>"
                                            +
                                            "<td>"
                                            +
                                            "<div class='btn-group mb-2'>" +
                                            "<button type='button' class='btn btn_info dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false' >" + 'Action' +
                                            "<i class='mdi mdi-chevron-down'> " + "</i>" + "</button>" +
                                            "<div class='dropdown-menu'>" +
                                            `<a class="dropdown-item" onclick="approveLeads(${response.data[i]['id']})">` + 'Approve' + "</a>" +
                                            `<button type='button' class='dropdown-item deleteProductId' data-toggle='modal' data-target='.example-modal-center122' data-id='${response.data[i]['id']}'  id='deleteTable'>` + 'Delete' + "</button>" +
                                            "</div>" +
                                            "</div>" +
                                            "</td>" +
                                            "</tr>"
                                        $("#pendingProducts").append(tableBody);
                                        $('.deleteProductId').click(function () {
                                            var data = $(this).attr('data-id');
                                            $('.deleteModal #dltUserID').val(data);

                                        });
                                    }
                                }
                            }
                        },

                    });
                }
                else
                {
                    window.location.reload()
                }
            }
            function approveLeads(id)
            {
                $.ajax({
                    url: 'approve-leads',
                    type: 'post',
                    data: {"_token": "{{csrf_token()}}",'id' : id},
                    dataType: 'json',
                    success: function (response) {
                        if(response.status) {
                            swal("Leads approved Successfully!");
                           setTimeout(function(){ window.location.reload() }, 2000);
                        }
                        else
                        {
                            swal("Leads could not be approved!");
                        }
                    },

                });
            }
            $('.deleteProduct').click(function () {
                var data = $(this).attr('data-id');
                $('.deleteModal #dltUserID').val(data);

            });

            $('.deleteConfirm').click(function() {

                var id = $('.deleteModal #dltUserID').val();
                $.ajax({
                    url: 'delete-leads',
                    type: 'post',
                    data: {"_token": "{{csrf_token()}}",'id' : id},
                    dataType: 'json',
                    success: function (response) {
                        if(response.status)
                        {
                            swal("Sourcing leads Deleted Successfully!");
                            document.getElementById('lead-cancel').click();
                            setTimeout(function(){ window.location.reload() }, 2000);
                        }else
                        {
                            swal("Sourcing leads could not be  deleted!");
                            document.getElementById('lead-cancel').click();
                            setTimeout(function(){ window.location.reload() }, 2000);
                        }

                    }
                });
            });
            $('.deleteProducts').click(function () {
                var data = $(this).attr('data-id');
                $('.deleteModal #dltUserID').val(data);

            });

            $('.deleteProductConfirm').click(function() {

                var id = $('.deleteModal #dltUserID').val();
                $.ajax({
                    url: 'delete-pending-products',
                    type: 'post',
                    data: {"_token": "{{csrf_token()}}",'id' : id},
                    dataType: 'json',
                    success: function (response) {
                        if(response.status)
                        {
                            swal("Products Deleted Successfully!");
                            document.getElementById('lead-cancel').click();
                            setTimeout(function(){ window.location.reload() }, 2000);
                        }else
                        {
                            swal("Products could not be  deleted!");
                            document.getElementById('lead-cancel').click();
                            setTimeout(function(){ window.location.reload() }, 2000);
                        }

                    }
                });
            });
        </script>
        <script async defer
                src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB12hWno8_DIMqw7xCV1QeqYn6I8FiIxVw&callback=initMap">
        </script>

@endsection
