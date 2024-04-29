@extends('web.layouts.master')
@section('content')
@include('web.layouts.partials.navbar') 
<!-- Start Page Content here -->

@if(Session::get('currency') == "")
{{Session::put('currency', 'Pkr')}}
@endif
<div class="content">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="banner_sub_vt">
                <div class="banner_sub_home">
                    <h2>Order Summary</h2>
                </div>
            </div>
        </div>
    </div>
    <div class="container back_white_vt my-4">
        <form action="{{ route('best.priceStore') }}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <input type="hidden" name="quantity" value="{{ session()->get('quantity') }}">
            <input type="hidden" name="unit_id" value="{{ session()->get('unit_id') }}">
            @if( session()->has('bid'))
            <input type="hidden" name="bid" value="{{ session()->get('bid') }}">
            @endif
            <div class="row">
                <div class="col-md-12">
                    <div class="order_summary_vt">
                        <a class="edit_samry_vt" type="button" onclick="modal()">Edit</a>
                        <?php
                            if (empty( $product->image )) {
                            $sellerImage = 'xyz';
                        }
                        $file = public_path() . '/images/profile-pic/' . $sellerImage;
                        if (!empty( $product->image ) && file_exists($file)) {
                            $sellerImage = getImageUrl( $product->image , 'images');
                        } else {
                            $sellerImage = getImageUrl('profile.png', 'images12');
                        }
                            ?>
                        <div class="summary_img_vt">
                            <img src="{{ $sellerImage }}" alt="" class="img-fluid">
                        </div>
                        <div class="summery_text_vt">
                            {{--                            <button class="edit_summery" type="button"><i class="fa fa-edit"></i></button>--}}
                            <h3>{{ $product->products_name }}</h3>
                            <p>{{ $product->category }}</p>
                            <h5><img src="{{ asset('web/images/map.png') }}" alt="" class="img-fluid">
                                {{ $product->sellerLocation }}</h5>
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
                        </div>
                        <div class="text_summ_vt">
                            <p><samp>Quantity </samp><span>{{ session()->get('quantity') }}</span></p>
                            <p><samp>Quantity Unit
                                    :</samp><span>{{ $product->unit != "" ? $product->unit : 'N/A' }}</span></p>
                                    @if($product->currency == 'PKR')
                                        
                                        @if(Session::get('currency') == 'Pkr')
                                        <p><samp>Bid Price:</samp><span>PKR {{ session()->get('bid') ? session()->get('bid') : 0 }}</span></p>
                                        @else
                                        <p><samp>Bid Price:</samp><span>$ {{ session()->get('bid') ? round(session()->get('bid')/$globalCurrency,2) : 0 }}</span></p>
                                        @endif
                                    @else
                                        @if(Session::get('currency') == '$')
                                        <p><samp>Bid Price:</samp><span>$ {{ session()->get('bid') ? session()->get('bid') : 0 }}</span></p>
                                        @else
                                        <p><samp>Bid Price:</samp><span>PKR {{ session()->get('bid') ? round(session()->get('bid')*$globalCurrency,2) : 0 }}</span></p>
                                        @endif
                                    @endif
                            <p><samp>Category :</samp><span>{{ $product->category }}</span></p>
                            <p><samp>Sub Category:</samp><span>{{ $product->sub_category }}</span></p>
                            <p><span></span></p>
                            <p><span></span></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-9"></div>
                <div class="col-md-3 mt-3"><button class="order_next_vt" type="submit">Continue</button></div>
            </div>
        </form>
    </div>


</div>




{{--  --}}
<div class="modal fade" id="phone_" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">

        <div class="modal-content">
            <div class="modal-header headert_vt">
                <h5 class="modal-title" id="bestprice">Order Detail</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                {{-- {{ dd($item->sellerPhone) }} --}}

            </div>
            <div class="modal-body">
                <div class="comp_pro_vt">
                    <div class="comp_img_vt">
                        <img src="{{  $sellerImage }}" alt="">
                    </div>
                    <div class="comp_text_vt">
                        <h3>{{ $product->products_name }}</h3>
                        <p>{{ $product->category }}</p>
                        <div class="star-rating-area">
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
                        </div>
                    </div>
                </div>
                <div class="inp_vt">
                    <form action="{{ route('best.priceConfirmation') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <input type="hidden" class="form-control" name="product_id" value="{{ $product->id }}"
                                placeholder="Enter Quantity">
                            <input type="number" class="form-control" min="1" value="{{ session()->get('quantity') }}"
                                name="quantity" id="exampleFormControlInput1" placeholder="Enter Quantity" required>
                        </div>
                        <div class="form-group">
                            <select class="form-control" name='unit_id' id="exampleFormControlSelect1">
                                @if($product->unit == '')
                                <option value="">Select Unit</option>
                                {{-- @else
                                <option value="{{ session()->get('unit_id') }}">{{ $product->unit }}</option> --}}
                                @endif
                                @foreach($product->all_unit as $items)
                                <option value="{{ $items->id }}">{{ $items->unit }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input addCheckbox" name="bidCheckbox"
                                    id="exampleCheck1" onchange="addBid()">
                                <label class="form-check-label" for="flexRadioDefault1">
                                    Add Bid
                                </label>
                            </div>
                        </div><br>
                        <div class="bidFields" style="display: none">
                            <div class="form-group mt-3">
                                <input type="text" class="form-control" name="bid" id="exampleFormControlInput1"
                                    placeholder="Add Bid(Optional)" value="{{ session()->get('bid')}}">
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
@endsection
@section('js')
<script>
    function modal() {
        $("#phone_").modal('show');
    }

    function addBid() {
        if ($('.addCheckbox').is(":checked"))
            $(".bidFields").show();
        else
            $(".bidFields").hide();
    }

</script>
@endsection
