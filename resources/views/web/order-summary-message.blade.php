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
                            <h2>{{ $product->products_name }}</h2>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Start How Cartify Works ?-->
{{--            <div class="container mt-4">--}}
{{--                <div class="row">--}}
{{--                    <div class="col-md-12">--}}
{{--                        <div class="pages-number_vt">--}}
{{--                            <ul>--}}
{{--                                <li><a href="#">Categories </a></li>--}}
{{--                                <li> > </li>--}}
{{--                                <li><a href="#">Adhesive &Sealants</a></li>--}}
{{--                                <li> > </li>--}}
{{--                                <li><a href="#">All Adhesive &Sealants</a></li>--}}
{{--                                <li> > </li>--}}
{{--                                <li><a href="#">Chemical 1</a></li>--}}
{{--                                <li> > </li>--}}
{{--                                <li>Order Detail</li>--}}
{{--                            </ul>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
            <!-- Start Cate itme ?-->
            <div class="container my-4">
                <div class="row">
                    <div class="col-md-12">
                        <div class="order_summary_vt">
                            <div class="summary_img_vt">
                                <img src="{{ $product->image }}" alt="" class="img-fluid">
                            </div>
                            <div class="summery_text_vt">
                                <!-- <button class="edit_summery" type="button"><i class="fa fa-edit"></i></button> -->
                                <h3>{{ $product->products_name }}</h3>
                                <p>{{ $product->category }}</p>
                                <h5><img src="{{ asset('web/images/map.png') }}" alt="" class="img-fluid">{{ $product->sellerLocation }}</h5>
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
                                <p>Quantity :<span>{{ $product->quantity }}</span></p>
                                <p>Quantity Unit :<span>{{ $product->unit }}</span></p>
                                <p>Bid Price :<span>{{ $product->bid }}</span></p>
                                <p>Category :<span>{{ $product->category }}</span></p>
                                <p>Sub Category:<span>{{ $product->sub_category }}</span></p>
                                <p><span></span></p>
                                <p><span></span></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8 mt-3">
                        <div class="show_vel_vt">
                            @if (!$chatMessage)
                            <ul>
                                <a href="#"><li>Are you free today?</li></a>
                                <a href="#"><li>Are you free today?</li></a>
                                <a href="#"><li>Are you free today?</li></a>
                                <a href="#"><li>Are you free today?</li></a>
                                <a href="#"><li>Are you free today?</li></a>
                                <a href="#"><li>Are you free today?</li></a>
                            </ul>
                            @endif
                        </div>
                        <div class="chat_area_summry_vt">
                            <div class="massage_area_scree_vt">
                                <ul id="messages_ul">
                                    @if(isset($chatMessage) && count($chatMessage))
                                        @foreach($chatMessage as $item)
                                            <li>{{ $item->message }}</li>
                                        @endforeach
                                    @endif
                                </ul>
                            </div>
                                <input type="hidden" id="chat_id" name="chat_id" value="{{ $chatId }}">
                                <div class="input_area_img_file">
                                    <div class="file_area_vt"><input type="file"><img src="{{ asset('web/images/summery_file.png') }}" alt=""></div>
                                    <!-- <button type="file"> </button> -->
                                    <div class="input_send_vt">
                                        <input type="text" placeholder="Enter" id="message" name="message" required>
                                        <button type="submit" class="send_btn_vt"><img src="{{ asset('web/images/summery_btn.png') }}" alt=""></button>
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
@endsection
@section('js')
    <script>
        $('.send_btn_vt').click(function(){
            var message = document.getElementById('message').value;
            if (message === '') {
                Swal.fire({
                    icon: 'error',
                    title: 'please enter message',
                    showClass: {
                        popup: 'animate__animated animate__fadeInDown'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__fadeOutUp'
                    }
                });
                return false;
            }else {
                var chatId = document.getElementById('chat_id').value;
                $("#messages_ul").append(`<li>${message}</li>`);
                $.ajax({
                    url: "{{ route('send.message') }}",
                    type: "POST",
                    data: {
                        _token: "{{csrf_token()}}",
                        message: message,
                        chat_id: chatId
                    },
                    cache: false,
                    success: function (response) {
                        if (response.status) {
                            $('input[name=message]').val('');

                        } else {
                            $('input[name=message]').val('');
                        }
                    }
                });
            }
        });
    </script>
@endsection
