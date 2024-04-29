@extends('web.layouts.master')
@section('content')
@include('web.layouts.partials.navbar')
<!-- Start Page Content here -->
<div class="content new_page_area_vt">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="banner_sub_vt">
                <div class="banner_sub_home">
                    <h2>Search Products</h2>
                </div>
            </div>
        </div>
    </div>
    {{-- {{ dd($products) }} --}}
    <!-- Start Cate itme ?-->
    <div class="container my-4 back_white_vt">
        <div class="row">
            @if(empty($products))
            <div class="jumbotron w-100 mb-0 bg-color">
                <h1 class="display-4 text-center w-100 f-size">No Product Found</h1>
            </div>
            @endif
            {{-- @endif --}}
            {{-- {{ dd($popularProducts) }} --}}
            @if($products)
            <div class="col-md-12 _prod_area_vt">
            @foreach($products as $key => $item)
            {{-- {{ dd($item) }} --}}
            <!-- <div class="my_card_vt">
                <div class="card">
                    <div class="img_dil_vt">
                        <img src="{{ $item->attachments }}" alt="">
                        {{-- @if($item->is_favorite == 'Y')
                        <button onclick="favoriteProduct({{$item->id}},'N')" type="button">
                        <img src="{{ asset('web/images/imgheart.png') }}" alt="">
                        </button>
                        @else
                        <button onclick="favoriteProduct({{$item->id}},'Y')" type="button">
                            <img src="{{ asset('web/images/outline_heart.png') }}" alt="">
                        </button>
                        @endif --}}
                    </div>
                    <a href="{{ route('product.detail') }}/{{ $item->id }}">
                        <h4>{{ $item->products_name }}</h4>
                    </a>
                    <p><a href="{{ route('seller.detail') }}/{{ $item->user_id }}">{{ $item->sellerName }}</a></p>
                    <h5><img src="{{ asset('web/images/card_map.png') }}" alt="" class="img-fluid">
                        {{ $item->sellerLocation }}
                    </h5>
                    <h2>{{ $item->currency }}{{ $item->price }}@if($item->unit)/{{ $item->unit }}@endif
                        <div class="next_card star-rating-area">
                            <div class="ratilike ng-binding"><i class="fa fa-star"></i> {{ $item->rating }}</div>
                        </div>
                    </h2>

                    @if(auth()->user())
                    <button type="button" class="call_primary_vt" data-toggle="modal" onclick="phonecall({{ json_encode($item->user_id) }},{{ json_encode($key)}})"><img src="{{ asset('web/images/phone-call.png') }}" alt=""> Call</button>
                    <button type="button" onclick="getchat({{ json_encode($item->user_id) }},{{ json_encode($item->id)}},{{ json_encode($key)}})" class="best_price_vt best-price" data-toggle="modal">Best Price</button>
                    @else
                    <button type="button" class="call_primary_vt" data-toggle="modal" data-target="#phone_card_pop_vt_{{$key}}"><img src="{{ asset('web/images/phone-call.png') }}" alt=""> Call</button>
                    <button type="button" onclick="getchhat()" class="best_price_vt best-price" data-toggle="modal">Best
                        Price</button>

                    @endif
                </div>
            </div> -->
            <div class="new_cat_big_vt">
                @if(auth()->user())
                    @if($item->is_favorite == 'Y')
                    <!-- <button onclick="favoriteProduct({{$item->id}},'N')" type="button"> -->
                    <div class="heart_vt" onclick="favoriteProduct({{$item->id}},'N')"><i class="fa fa-heart"></i></div>
                    <!-- </button> -->
                    @else
                    <!-- <button onclick="favoriteProduct({{$item->id}},'Y')" type="button"> -->
                    <div class="heart_vt" onclick="favoriteProduct({{$item->id}},'Y')"><i class="fa fa-heart-o"></i></div>
                    <!-- </button> -->
                    @endif
                @endif
                    <div class="img_car_vt"><img src="{{ $item->attachments }}" alt=""></div>
                    <div class="teat_area_vt">
                        <a class="h4_vt" href="{{ route('product.detail') }}/{{ $item->id }}">
                            {{ $item->products_name }}
                        </a>
                        <p><a href="{{ route('seller.detail') }}/{{ $item->user_id }}">{{ $item->sellerName }}</a></p>
                        <h6><img src="{{ asset('web/images/card_map.png') }}" alt=""> {{ $item->sellerLocation }}
                            <span>
                                 @if($item->price)
                                    {{ $item->currency }} {{ $item->price }}
                                {{-- @if($item->unit)/{{ $item->unit }}--}}
{{--                                 @endif @else Contact for best Price --}}
                                 @endif
                            </span>
                        </h6>
                        <span>MOQ: {{$item->minimum_order??1}}</span><span> {{$item->unit?? ""}}</span>
                        <div class="btn_add_vt">
                            <button type="button" class="call_primary_vt" data-toggle="modal" data-target="#phone_{{$key}}"><img src="{{ asset('web/images/phone-call.png') }}" alt=""> Call</button>
                            <button type="button" onclick="getchat({{ json_encode($item->user_id) }},{{ json_encode($item->id)}},{{ json_encode($key)}})" class="best_price_vt best-price" data-toggle="modal">Best
                                Price</button>
                        </div>

                    </div>
                </div>
            <div class="modal fade" id="phone_card_pop_vt_{{$key}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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

                            <p id="phoneNumber"><img src="{{ asset('web/images/call.png') }}" alt="">{{$item->sellerPhone}}</p>

                            <a href="tel:{{$item->sellerPhone}}">Call Now</a>
                        </div>
                    </div>
                </div>
            </div>


            <div class="modal fade" id="bestprice_{{ $key }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
                                    <img src="{{ $item->attachments }}" alt="">
                                </div>
                                <div class="comp_text_vt">
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
                                </div>
                            </div>
                            <div class="inp_vt">
                                <form action="{{ route('best.priceConfirmation') }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <input type="hidden" class="form-control" name="product_id" value="{{ $item->id }}" placeholder="Enter Quantity">
                                        <input type="number" class="form-control" name="quantity" min="{{$item->minimum_order??1}}" id="exampleFormControlInput1" placeholder="Enter Quantity" required>
                                    </div>
                                    <div class="form-group">
                                        <select class="form-control" name='unit_id' id="exampleFormControlSelect1">
                                            <option>Select Unit</option>
                                            @foreach($item->all_unit as $items)
                                            <option value="{{ $items->id }}">{{ $items->unit }}</option>
                                            @endforeach
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
                                            <input type="text" name="bid" class="form-control" id="exampleFormControlInput1" placeholder="Add Bid(Optional)">
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


            @endforeach
            </div>
            @endif
        </div>
{{--        @if($products)--}}
{{--        <div class="col-md-12">--}}
{{--            {{ $products->links() }}--}}
{{--        </div>--}}
{{--        @endif--}}
    </div>
</div>




@endsection
@section('js')
<script>
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
            success: function(response) {
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
            success: function(response) {
                $("#phone_card_pop_vt_" + key).modal().show();

            }

        });

    }

    function getchhat() {
        window.location = '/website-login';
    }
</script>

@endsection
