@extends('web.layouts.master')
@section('content')
    @include('web.layouts.partials.navbar')
    <style>
        #loading-indicator {
            position: absolute;
            left: 850px;
            top: 250px;
            z-index: 9999;
            /* background: url('pageloader.gif') 50% 50% no-repeat rgb(172, 169, 169); */
        }
    </style>
    @if(Session::get('currency') == "")
    {{Session::put('currency', 'Pkr')}}
    @endif
    <!-- Start Page Content here -->
    <div class="content new_page_area_vt">
        <img src="{{ asset('web/images/loadinggreyicon.gif') }}" id="loading-indicator" style="display: none"/>
    {{-- {{ dd($products) }} --}}
    <!-- start page title -->
        <!-- <div class="row">
            <div class="col-12">
                <div class="banner_sub_vt">
                    <div class="banner_sub_home">
                        <h2>Products</h2>
                    </div>
                </div>
            </div>
        </div> -->
        <!-- end page title -->

        <!-- Start How Cartify Works ?-->
        <div class="container mt-3">
            <div class="row">
                <div class="col-md-12">
                    <!-- <div class="adhesive_search_vt">
                        <input type="text" id ='search' placeholder="Search">
                        <button type="submit" id="searchs"><i class="fa fa-search"></i></button>
                    </div> -->
                    {{--<h3 class="text-center">Agro Chemicals</h3>--}}
                </div>
            </div>
        </div>
    {{-- {{ dd($types) }} --}}
    <!-- Start Cate manu ?-->
        <div class=" mt-3 py-2">
            <div class="container">
                <div class="row">
                    <div class=" col-md-12">
                        <div class=" cat_manu_bg">
                            <div class=" row">
                                <div class="col-md-4">
                                    <div class="search_prod_vt">
                                        <h5><img src="{{ asset('web/images/cat_location.png') }}" alt=""> Location:</h5>
                                        <div class="search_area_prod_vt">
                                            <select onchange=" product()" class="form-control"
                                                    id="exampleFormControlSelect1">
                                                <option value="" disabled selected>Select City...</option>
                                                @foreach($city as $item)

                                                    <option value="{{ $item}}">{{ $item }}</option>

                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="cate_menu_vt">
                                        <ul>
                                            <li><a id="all" href="{{route('city.product',['all','verified'])}}"
                                                   class="{{ $types == 'all' ? 'active' : '' }}">All</a></li>
                                            <li><a a id="Lahore" href="{{route('city.product',['lahore','verified'])}}"
                                                   class="{{ $types == 'lahore' ? 'active' : '' }}">Lahore</a></li>
                                            <li><a a id="Karachi" href="{{route('city.product',['karachi','verified'])}}"
                                                   class="{{ $types == 'karachi' ? 'active' : '' }}">Karachi</a></li>
                                            <li><a a id="Hyderabad" href="{{route('city.product',['hyderabad','verified'])}}"
                                                   class="{{ $types == 'hyderabad' ? 'active' : '' }}">Hyderabad</a>
                                            </li>
                                            <li><a a id="Faisalabad" href="{{route('city.product',['Faisalabad','verified'])}}"
                                                   class="{{ $types == 'Faisalabad' ? 'active' : '' }}">Faisalabad</a>
                                            </li>
                                            <li><a a id="Islamabad" href="{{route('city.product',['Islamabad','verified'])}}"
                                                   class="{{ $types == 'Islamabad' ? 'active' : '' }}">Islamabad</a>
                                            </li>
                                            <li><a a id="Multan" href="{{route('city.product',['Multan','verified'])}}"
                                                   class="{{ $types == 'Multan' ? 'active' : '' }}">Multan</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> 
        <!-- Start Cate itme ?-->
        <div class="container mt-3">
            <div class="row">

                <div class="col-md-2 left_add_cat_bor">
                    <div class="left_categories_vt">
                        <h2>Categories</h2>
                        <ul>
                            @foreach($category as $item)
                                <li>
                                    <a class="{{ request()->segment(2) == 'Clothes' && $item->category == 'Clothes'
                                        || request()->segment(2) == 'Electronic' && $item->category == 'Electronic'
                                        || request()->segment(2) == 'Chemicals, Dyes, Solvents & Allied Products' && $item->category == 'Chemicals, Dyes, Solvents & Allied Products'
                                        || request()->segment(2) == 'Machinery' && $item->category == 'Machinery'
                                        ? 'active' : '' }}"
                                       href="{{ route('product.list') }}/{{ $item->category }}/verified">{{ $item->category }}</a>
                                </li>
                            @endforeach
                            <li><a href="{{ route('product.categorys') }}" class="ml-2">View All</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-10 _prod_area_vt">
                    @if(empty($products))
                        <div class="jumbotron w-100 mb-0 bg-color">
                            <h1 class="display-4 text-center w-100 f-size">No Product Found</h1>
                        </div>
                    @endif
                    <div class="row pt-3" id="product">
                        {{-- {{ dd($products) }} --}}
                        @if(isset($products) && count($products)>0)
                            @foreach($products as $key=> $item)
                                <input type="hidden" name="sellerPhone" id="sellerPhone" value="{{$item->sellerPhone}}">
                                <div class="new_cat_big_vt">
                                    {{--                        @if($item->is_favorite == 'Y')--}}
                                    {{--                        <!-- <button onclick="favoriteProduct({{$item->id}},'N')" type="button"> -->--}}
                                    {{--                        <div class="heart_vt" onclick="favoriteProduct({{$item->id}},'N')"><i class="fa fa-heart"></i></div>--}}
                                    {{--                        <!-- </button> -->--}}
                                    {{--                        @else--}}
                                    {{--                        <!-- <button onclick="favoriteProduct({{$item->id}},'Y')" type="button"> -->--}}
                                    {{--                        <div class="heart_vt" onclick="favoriteProduct({{$item->id}},'Y')"><i class="fa fa-heart-o"></i></div>--}}
                                    {{--                        <!-- </button> -->--}}
                                    {{--                        @endif--}}
                                    <div class="img_car_vt"><img src="{{ $item->attachments }}" alt=""></div>
                                    <div class="teat_area_vt">
                                        <a class="h4_vt" href="{{ route('product.detail') }}/{{ $item->id }}">
                                            {{ $item->products_name }}
                                        </a>
                                        <p>
                                            <a href="{{ route('seller.detail') }}/{{ $item->user_id }}">{{ $item->sellerName }}</a>
                                        </p>
                                        <h6><img src="{{ asset('web/images/card_map.png') }}"
                                                 alt=""> {{ $item->sellerLocation }}
                                            <span>
                                                @if($item->price)
                                                    @if($item->currency == 'PKR')
                                                        @if(Session::get('currency') == 'Pkr')
                                                            PKR{{ $item->price }}
                                                        @else
                                                            ${{round(($item->price)/$globalCurrency,2) }}
                                                        @endif
                                                    @else
                                                        @if(Session::get('currency') == '$')
                                                            ${{ $item->price }}
                                                        @else
                                                            PKR{{round(($item->price)*$globalCurrency,2) }}
                                                        @endif
                                                    @endif
                                                    <!-- {{ $item->currency }} {{ $item->price }} -->
                                                    {{-- @if($item->unit)/{{ $item->unit }}--}}
                                                    {{--                                     @endif @else Contact for best Price --}}
                                                @endif
                                            </span>
                                        </h6>
                                        <div class="btn_add_vt">
                                            <button type="button" class="call_primary_vt" data-toggle="modal"
                                                    data-target="#phone_{{$key}}"><img
                                                    src="{{ asset('web/images/phone-call.png') }}" alt=""> Call
                                            </button>
                                            <button type="button"
                                                    onclick="getchat({{ json_encode($item->user_id) }},{{ json_encode($item->id)}},{{ json_encode($key)}})"
                                                    class="best_price_vt best-price" data-toggle="modal">Best
                                                Price
                                            </button>
                                        </div>
                                    </div>
                                </div>

                            <!-- <div class="my_card_vt">
                        <div class="card">
                            <div class="img_dil_vt"><img src="{{ $item->attachments }}" alt="" class="card_img img-fluid">
                                @if (auth()->user())

                                    @if($item->is_favorite == 'Y')
                                    <button onclick="favoriteProduct({{$item->id}},'N')" type="button">
                                    <img src="{{ asset('web/images/imgheart.png') }}" alt="" width="22">
                                </button>
                                @else
                                    <button onclick="favoriteProduct({{$item->id}},'Y')" type="button">
                                    <img src="{{ asset('web/images/outline_heart.png') }}" alt="" width="22">
                                </button>
                                @endif
                                @endif
                                </div>
                                <a href="{{ route('product.detail') }}/{{ $item->id }}">
                                <h4>{{ $item->products_name }}</h4>
                            </a>
                            <p><a href="{{ route('seller.detail') }}/{{ $item->user_id }}">{{ $item->sellerName }}</a>
                            </p>
                            <h5><img src="{{ asset('web/images/map.png') }}" alt="" class="img-fluid">
                                {{ $item['sellerLocation'] }} <span>@if($item->price){{ $item->currency }}{{ $item->price }}@if($item->unit)/{{ $item->unit }}@endif
                                @else Contact for Best Price @endif</span>
                            </h5>


                            @if(auth()->user())
                                <button type="button" class="call_primary_vt" data-toggle="modal" onclick="phonecall({{ json_encode($item->user_id) }},{{ json_encode($key)}})"><img src="{{ asset('web/images/phone-call.png') }}" alt=""> Call</button>
                            <button type="button" onclick="getchat({{ json_encode($item->user_id) }},{{ json_encode($item->id)}},{{ json_encode($key)}})" class="best_price_vt best-price" data-toggle="modal">Best Price</button>
                            @else
                                <button type="button" class="call_primary_vt" data-toggle="modal" data-target="#phone__{{$key}}"><img src="{{ asset('web/images/phone-call.png') }}" alt=""> Call</button>
                            <button type="button" onclick="getchhat()" class="best_price_vt best-price" data-toggle="modal">Best
                                Price</button>

                            @endif
                                </div>
                            </div> -->
                                {{-- {{ dd($item->sellerPhone) }} --}}
                            <!-- Modal -->
                                <div class="modal fade" id="bestprice_{{ $key }}" tabindex="-1" role="dialog"
                                     aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">

                                        <div class="modal-content">
                                            <div class="modal-header headert_vt">
                                                <h5 class="modal-title" id="bestprice">Order Detail</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                                {{-- {{ dd($item->sellerPhone) }} --}}

                                            </div>
                                            <div class="modal-body">
                                                <div class="comp_pro_vt">
                                                    <div class="comp_img_vt">
                                                        <img src="{{ $item->attachments }}" alt="">
                                                    </div>
                                                <!-- <div class="comp_text_vt">
                                            <h3>{{ $item->products_name }}</h3>
                                            <p>{{ $item->sellerName }}</p>
                                            <div class="star-rating-area">
                                                <div class="rating-static clearfix " rel="{{ $item->rating }}">
                                                    <label class="full" title="5 stars"></label>
                                                    <label class="half" title="4.5 stars"></label>
                                                    <label class="full" title="4 stars"></label>
                                                    <label class="half" title="3.5 stars"></label>
                                                    <label class="full" title="3 stars"></label>
                                                    <label class="half" title="2.5 stars"></label>
                                                    <label class="full" title="2 stars"></label>
                                                    <label class="half" title="1.5 stars"></label>
                                                    <label class="full" title="1 star"></label>
                                                    <label class="half" title="0.5 stars"></label>
                                                </div>
                                            </div>
                                        </div> -->
                                                </div>
                                                <div class="inp_vt">
                                                    <form action="{{ route('best.priceConfirmation') }}" method="POST">
                                                        @csrf
                                                        <div class="form-group">
                                                            <input type="hidden" class="form-control" name="product_id"
                                                                   value="{{ $item->id }}" placeholder="Enter Quantity">
                                                            <input type="number" class="form-control" min="1"
                                                                   name="quantity" id="exampleFormControlInput1"
                                                                   placeholder="Enter Quantity" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <select class="form-control" name='unit_id'
                                                                    id="exampleFormControlSelect1">
                                                                <option value="">Select Unit</option>
                                                                @foreach($item->all_unit as $items)
                                                                    <option
                                                                        value="{{ $items->id }}">{{ $items->unit }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="form-group mb-3">
                                                            <div class="form-check">
                                                                <input type="checkbox"
                                                                       class="form-check-input addCheckbox"
                                                                       name="bidCheckbox" id="exampleCheck1"
                                                                       onchange="addBid()">
                                                                <label class="form-check-label" for="flexRadioDefault1">
                                                                    Add Bid
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <br>
                                                        <div class="bidFields" style="display: none">
                                                            <div class="form-group mt-3">
                                                                <input type="text" name="bid" class="form-control"
                                                                       id="exampleFormControlInput1"
                                                                       placeholder="Add Bid(Optional)">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <button type="submit">Next</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- {{ dd($item) }} --}}
                            <!-- Modal -->
                                <div class="modal fade" id="phone_{{$key}}" tabindex="-1" role="dialog"
                                     aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header headert_vt">
                                                <h5 class="modal-title" id="exampleModalLongTitle">Call Us</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body pop_cal_area_vt">
                                                <img src="{{ asset('web/images/call-icon-call.png') }}" alt=""
                                                     width="100px">
                                                {{-- --}}
                                                <p id="phoneNumber"><img src="{{ asset('web/images/call.png') }}"
                                                                         alt="">
                                                    {{$item->sellerPhone}}
                                                </p>

                                                <a href="tel:{{$item->sellerPhone}}">Call Now</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal fade" id="phone__{{$key}}" tabindex="-1" role="dialog"
                                     aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header headert_vt">
                                                <h5 class="modal-title" id="exampleModalLongTitle">Call Us</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body pop_cal_area_vt">
                                                <img src="{{ asset('web/images/call-icon-call.png') }}" alt=""
                                                     width="100px">
                                                {{-- --}}
                                                <p id="phoneNumber"><img src="{{ asset('web/images/call.png') }}"
                                                                         alt="">
                                                    {{$item->sellerPhone}}
                                                </p>

                                                <a href="tel:{{$item->sellerPhone}}">Call Now</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Modal -->
                            @endforeach
                        @else
                            {{-- {{ dd('huit') }} --}}
                            <div class="jumbotron w-100 mb-0 bg-color">
                                <h1 class="display-4 text-center w-100 f-size">No Product Found</h1>
                            </div>
                        @endif
                    </div>
{{--                            <div class="btn_text_aree_lodmore_vt">--}}
{{--                                <h2>You Can get Verified Sellers</h2>--}}
{{--                                <button type="button">Get Verified Sellers</button>--}}
{{--                            </div>--}}
                    <div class="col-md-12" id="hid">
                        {{ $products->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>




@endsection
@section('js')
    <script type="text/javascript">
        $(document).ready(function () {

            $("#exampleFormControlSelect1").select2();
        });
    </script>
    <script>
        $(document).ready(function () {
            var num = $("#sellerPhone").val();
            console.log(num);
        });

        function favoriteProduct(productId, type) {
            $.ajax({
                url: `{{route('favourite.product')}}`,
                type: 'get',
                dataType: 'json',
                data: {
                    type: type,
                    product_id: productId
                },

                success: function (response) {
                    if (response.status) {
                        window.location.reload();
                    } else {
                        swal("Seller could not be marked as Inactive!");
                    }


                }
            });
        }

        function product(pagenum = '') {
            if (pagenum) {
                var pageNumber = pagenum;
            } else {
                var pageNumber = 1;
            }
            var location = $("#exampleFormControlSelect1").val();
            $("#loading-indicator").show();
            $.ajax({
                url: "{{route('verified.seller.search.products')}}",
                type: "GET",
                data: {
                    pageNumber: pageNumber,
                    location: location,
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    $("#Lahore").attr('class', '');
                    $("#all").attr('class', '');
                    $("#Karachi").attr('class', '');
                    $("#Faisalabad").attr('class', '');
                    $("#Hyderabad").attr('class', '');
                    if (location == 'Lahore') {
                        $("#Lahore").attr('class', 'active');
                        $("#all").attr('class', '');
                        $("#Karachi").attr('class', '');
                        $("#Faisalabad").attr('class', '');
                        $("#Hyderabad").attr('class', '');
                    }
                    if (location == 'Karachi') {
                        $("#Karachi").attr('class', 'active');
                        $("#all").attr('class', '');
                        $("#Lahore").attr('class', '');
                        $("#Faisalabad").attr('class', '');
                        $("#Hyderabad").attr('class', '');
                    }
                    if (location == 'Faisalabad') {
                        $("#Faisalabad").attr('class', 'active');
                        $("#all").attr('class', '');
                        $("#Lahore").attr('class', '');
                        $("#Karachi").attr('class', '');
                        $("#Hyderabad").attr('class', '');
                    }
                    if (location == 'Hyderabad') {
                        $("#Hyderabad").attr('class', 'active');
                        $("#all").attr('class', '');
                        $("#Lahore").attr('class', '');
                        $("#Karachi").attr('class', '');
                        $("#Faisalabad").attr('class', '');
                    }
                    $("#product").empty();

                    console.log(response);
                    if (response.success) {

                        $("#hid").empty();

                        for (var i = 0; i < response.products.length; i++) {
                            var attachments = response.products[i]['attachments'];
                            var is_favorite = response.products[i]['is_favorite'];
                            var id = response.products[i]['id'];
                            var user_id = response.products[i]['user_id'];
                            var products_name = response.products[i]['products_name'];
                            var rating = response.products[i]['rating'];
                            var unit = response.products[i]['unit'];
                            var price = response.products[i]['price'];
                            var currency = response.products[i]['currency'];
                            var sellerLocation = response.products[i]['sellerLocation'];
                            var sellerName = response.products[i]['sellerName'];
                            var category = response.products[i]['category'];
                            var sub_category = response.products[i]['sub_category'];
                            var phone = response.products[i]['sellerPhone'];
                            var all_unit = response.products[i]['all_unit'];
                            console.log(all_unit.length);

                            $("#product").append(
                                ` <input type="hidden" name="sellerPhone" id="sellerPhone" value ="${phone}">
                                    <div class="new_cat_big_vt">
                                            @if (auth()->user())
                                                ${is_favorite == 'Y' ?
                                                    `<div class="heart_vt" onclick="favoriteProduct(${id},'N')"><i class="fa fa-heart"></i></div>`
                                                    :
                                                    `<div class="heart_vt" onclick="favoriteProduct(${id},'Y')"><i class="fa fa-heart-o"></i></div>`
                                                }
                                            @endif
                                        <div class="img_car_vt"><img src="${attachments}" alt=""></div>
                                        <div class="teat_area_vt">
                                            <a class="h4_vt" href="{{ route('product.detail') }}/${id}">
                                                ${products_name}
                                            </a>
                                            <p><a href="{{ route('seller.detail') }}/${user_id}">${sellerName}</a></p>
                                            <h6>
                                                <img src="{{ asset('web/images/map.png') }}" alt="" class="img-fluid">${sellerLocation}
                                                <span>${price != null ? currency : ''}${price != null ? price : ''}
                                                </span>
                                            </h6>
                                            <div class="btn_add_vt">
                                                <button type="button" class="call_primary_vt" onclick="phonecalls(${user_id},${i})"><img src="{{ asset('web/images/phone-call.png') }}" alt=""> Call</button>
                                                <button type="button"  onclick="getchats(${user_id},${id},${i})" class="best_price_vt best-price" data-toggle="modal" >Best Price</button>
                                            </div>
                                        </div>
                                    </div>

<!-- Modal -->
<div class="modal fade" id="bestprices_${i}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                            <div class="modal-header headert_vt">
                                <h5 class="modal-title" id="bestprice">Order Detail</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="comp_pro_vt">
                                    <div class="comp_img_vt">
                                        <img src="${attachments}" alt="">
                                    </div>
                                </div>
                                <div class="inp_vt">
                                    <form action="{{ route('best.priceConfirmation') }}" method="POST">
                                        @csrf
                                <div class="form-group">
                                    <input type="hidden" class="form-control" name="product_id" value="{{ $item->id }}" placeholder="Enter Quantity">
                                            <input type="number" class="form-control" min="1" name="quantity" id="exampleFormControlInput1" placeholder="Enter Quantity" required>
                                        </div>
                                        <div class="form-group">
                                            <select class="form-control" name='unit_id' id="exampleFormControlSelect1unit_${i}">
                                            <option value="">Select Unit</option>



                                            </select>
                                        </div>
                                        <div class="form-group mb-3">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input addCheckbox" name="bidCheckbox" id="exampleCheck1" onchange="addBid()">
                                                <label class="form-check-label" for="flexRadioDefault1">
                                                Add Bid
                                                </label>
                                            </div>
                                        </div><br>
                                        <div class="bidFields" style="display: none">
                                        <div class="form-group mt-3">
                                            <input type="text" name = "bid" class="form-control" id="exampleFormControlInput1" placeholder="Add Bid(Optional)">
                                        </div>
                                        </div>
                                        <div class="form-group">
                                            <button type="submit">Next</button>
                                        </div>
                                        {{-- <h5>Your Contact Information<h5>
                                        <p>Zara</p>
                                        <p>abc@gmail.com</p> --}}
                                </form>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>

<!-- Modal -->
<div class="modal fade" id="phone_card_pop_vt_${i}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header headert_vt">
                <h5 class="modal-title" id="exampleModalLongTitle">Call Us</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pop_cal_area_vt">
                <img src="{{ asset('web/images/call-icon-call.png') }}" alt="" width="100px">

                <p id="phoneNumber"><img src="{{ asset('web/images/call.png') }}" alt=""> ${phone}</p>

                <a href="tel:${phone}">Call Now</a>
            </div>
        </div>
    </div>
</div>
`);
                            for (let k = 0; k < all_unit.length; k++) {
                                // console.log('hi');
                                var all_units = all_unit[k]['unit'];
                                var all_units_id = all_unit[k]['id'];
                                // console.log(all_units,all_units_id);
                                $("#exampleFormControlSelect1unit_" + i).append(`
<option value="${all_units_id}">${all_units}</option>
`);
                            }
                        }
                        if (response.paginate > 1) {
                            $("#hid").show();
                            console.log(response.pagenum);
                            console.log(response.pagenum, '<=', response.paginate);
                            if (response.pagenum <= response.paginate) {
                                var nextPage = parseInt(response.pagenum);
                            } else {
                                var nextPage = parseInt(response.pagenum) + 1;

                            }
                            var nextPage = parseInt(response.pagenum) + 1;
                            var perviousPage = response.pagenum - 1;
                            console.log(nextPage, 'nextPage');
                            console.log(perviousPage, 'perviousPage');
                            $("#hid").append(` <nav>
        <ul class="pagination">
${response.pagenum == 1 ? `` : `<li class="page-item" "aria-label="« Previous" >
                                <a class="page-link "   onclick="product(${perviousPage})" rel="next" aria-label="« Previous">‹</a>
                </li>`}

                <div id ='paginate'></div>
                ${response.pagenum == response.paginate ? `` : `<li class="page-item">
                    <a class="page-link "  onclick="product(${nextPage})" rel="next" aria-label="Next »">›</a>
                </li>`}

                    </ul>
    </nav>`);
                            for (let i = 1; i <= response.paginate; i++) {
                                $("#paginate").append(`<li style="float:left" class="page-item ${i == response.pagenum ? 'active' : ``}"><a class="page-link"onclick="product(${i})">${i}</a></li>`);

                            }
                        }
                    } else {
                        $("#hid").empty();
                        $("#product").append(`<div class="jumbotron w-100 mb-0 bg-color">
                            <h1 class="display-4 text-center w-100 f-size">No Product Found</h1>
                          </div>`);
                    }
                },
                complete: function () {
                    $('#loading-indicator').hide();
                }
            });

        }

        $("#searchs").click(function () {
            var product_name = $("#search").val();
            console.log(product_name);
            $.ajax({
                url: "{{route('search.bar')}}",
                type: "GET",
                data: {
                    product_name: product_name,
                },
                success: function (response) {
                    $("#product").empty();
                    console.log(response);
                    console.log(response.products.length);
                    for (let i = 0; i < response.products.length; i++) {
                        console.log(response.products)
                        var attachments = response.products[i]['attachments'];
                        var is_favorite = response.products[i]['is_favorite'];
                        var id = response.products[i]['id'];
                        var products_name = response.products[i]['products_name'];
                        var rating = response.products[i]['rating'];
                        var unit = response.products[i]['unit'];
                        var price = response.products[i]['price'];
                        var currency = response.products[i]['currency'];
                        var sellerLocation = response.products[i]['sellerLocation'];
                        var category = response.products[i]['category'];


                        $("#product").append(
                            `<div class="my_card_vt">
                        <div class="card">
                            <div class="img_dil_vt">
                                <img src="${attachments}" alt="">
                                @if (auth()->user())
                            ${is_favorite == 'Y' ?
                                `<button onclick="favoriteProduct(${id},'N')" type="button">
                                    <img src="{{ asset('web/images/imgheart.png') }}" alt="" width="22">
                                </button>`
                                :
                                ` <button onclick="favoriteProduct(${id},'Y')" type="button">
                                    <img src="{{ asset('web/images/outline_heart.png') }}" alt="" width="22">
                                </button>`
                            }
                                @endif
                            </div>
                            <a href="product/${id}">
                                <h4>${products_name}</h4>
                            </a>
                            <p>${category}</p>
                            <h5><img src="{{ asset('web/images/map.png') }}" alt="" class="img-fluid">
                                ${sellerLocation} <span>${currency}${price}/${unit}</span></h5>
                        </div>
                    </div>`
                        );
                    }
                }
            })
        })


        function getchat(sellerId, productId, key) {
            console.log(sellerId, productId);
            var product_id = productId;
            var key = key;
            $.ajax({
                url: "{{ route('best.prices') }}",
                type: "POST",
                data: {
                    _token: '{{ csrf_token() }}',
                    product_id: product_id,
                    // product_id:id,

                },
                success: function (response) {
                    console.log(response);
                    if (response.success) {
                        // $(".modal").hide();
                        window.location = "{{url('/chats-details/')}}" +"/"  + response.chatId;
                    } else {
                        console.log('hi');
                        $("#bestprice_" + key).modal().show();
                    }
                }

            })

        }

        function getchats(sellerId, productId, key) {

            $.ajax({
                url: "{{ route('best.prices') }}",
                type: "POST",
                data: {
                    _token: '{{ csrf_token() }}',
                    product_id: productId,
                    // product_id:id,

                },
                success: function (response) {
                    console.log(response);
                    if (response.success) {
                        // $(".modal").hide();
                        window.location = "{{url('/chats-details/')}}" +"/"  + response.chatId;
                    } else {
                        console.log('hi');
                        $("#bestprices_" + key).modal().show();
                    }
                }

            })

        }


        function addBid() {
            if ($('.addCheckbox').is(":checked"))
                $(".bidFields").show();
            else
                $(".bidFields").hide();
        }


        function phonecall(sellerId, key) {
            $.ajax({
                url: "{{ route('phone.call') }}",
                type: 'GET',
                data: {
                    seller_id: sellerId,
                },
                success: function (response) {
                    $("#phone_" + key).modal().show();

                }

            });

        }

        function phonecalls(sellerId, key) {
            $.ajax({
                url: "{{ route('phone.call') }}",
                type: 'GET',
                data: {
                    seller_id: sellerId,
                },
                success: function (response) {
                    $("#phone_card_pop_vt_" + key).modal().show();

                }

            });

        }

        function getchhat() {
            window.location = '/website-login';
        }


        // ajax pagination


        //endpagination
    </script>
@endsection
