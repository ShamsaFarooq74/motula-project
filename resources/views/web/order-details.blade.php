@extends('web.layouts.master')
@section('content')
    @include('web.layouts.partials.navbar')
    <!-- Start Page Content here -->
    <div class="content">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="banner_sub_vt">
                    <div class="banner_sub_home">
                        <h2>Order Detail</h2>
                    </div>
                </div>
            </div>
        </div>
        <!-- Start How Cartify Works ?-->
{{--        <div class="container mt-4">--}}
{{--            <div class="row">--}}
{{--                <div class="col-md-12">--}}
{{--                    <div class="pages-number_vt">--}}
{{--                        <ul>--}}
{{--                            <li><a href="#">Categories </a></li>--}}
{{--                            <li> > </li>--}}
{{--                            <li><a href="#">Adhesive &Sealants</a></li>--}}
{{--                            <li> > </li>--}}
{{--                            <li><a href="#">All Adhesive &Sealants</a></li>--}}
{{--                            <li> > </li>--}}
{{--                            <li><a href="#">Chemical 1</a></li>--}}
{{--                            <li> > </li>--}}
{{--                            <li>Message</li>--}}
{{--                        </ul>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
        <!-- Start Cate itme ?-->
        <div class="container my-4">
            <div class="row">
                <div class="col-md-12">
                    <div class="order_area_vt mt-2">
                        <h3>{{ $product->products_name }} </h3>
                        <p>{{ $product->category }}</p>
                        <h5><img src="{{ asset('web/images/map.png') }}" alt="" class="img-fluid"> {{ $product->sellerLocation }}</h5>
                        <div class="order_card star-rating-area">
                            <div class="rating-static clearfix " rel="{{ $product->rating }}">
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
                            <div class="ratilike ng-binding">{{ $product->rating }}</div>
                        </div>
                        <form action="{{ route('best.priceConfirmation') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="search_order">
                                        <input type="number" min="1" placeholder="Enter quantity" class="form-control" name="quantity" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="search_order">
                                        <select class="form-control" id="exampleFormControlSelect1" name="unit_id" >
                                            @foreach($unit as $item)
                                                <option value="{{ $item->id }}">{{ $item->unit }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12 my-2">
                                    <h4>Additional Details</h4>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input addCheckbox" name="bidCheckbox" id="exampleCheck1" onchange="addBid()">
                                        <label class="form-check-label" for="exampleCheck1">Add Bid</label>
                                    </div>
                                </div>
                                <div class="bidFields" style="display: none">
                                    <div class="col-md-12 mb-3">
                                        <input type="number" name = "bid" placeholder="Enter bid" min="1" name="bid" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <button type="submit" class="order_next_vt">Next</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


    </div>
@endsection
@section('js')
    <script>
        function addBid()
        {
            if($('.addCheckbox').is(":checked"))
                $(".bidFields").show();
            else
                $(".bidFields").hide();
        }
    </script>
@endsection
