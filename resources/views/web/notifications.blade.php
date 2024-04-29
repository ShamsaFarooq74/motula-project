@extends('web.layouts.master')
@section('content')
@include('web.layouts.partials.navbar')
<!-- Start Page Content here -->
<div class="content">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="banner_sub_vt">
                <div class="banner_sub_home mb-4">
                    <h2>Notifications</h2>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <!-- Start How Cartify Works ?-->
    <div class="container">
        <div class="p_notfic_bg">
            <div class="row">
                @foreach($notifications as $list)
                @if($list->notification_type == 'Lead')
                <div class="col-md-12"><a href="{{ route('buyer.requirement') }}">
                        <div class="notif_vt">
                            <div class="notif_img">
                                <img src="{{ asset('web/images/leadsnotification.png') }}" alt="">
                            </div>
                            <div class="notif_text_vt">
                                <h4>{{ isset($list->title) ? $list->title :'N\A' }}</h4>
                                <p>{{ isset($list->description) ? $list->description : 'N\A' }}</p>
                                <span>{{ isset($list->entry_date) ? $list->entry_date : 'N\A' }}</span>
                            </div>
                        </div>
                    </a></div>

                @endif
                @if($list->notification_type == 'Product')
                <div class="col-md-12"><a href="{{ route('product.detail') }}/{{ $list->type_id }}">
                        <div class="notif_vt">
                            <div class="notif_img">
                                <img src="{{ asset('web/images/productrating.png') }}" alt="">

                            </div>
                            <div class="notif_text_vt">
                                <h4>{{ isset($list->title) ? $list->title :'N\A' }}</h4>
                                <p>{{ isset($list->description) ? $list->description : 'N\A' }}</p>
                                <span>{{ isset($list->entry_date) ? $list->entry_date : 'N\A' }}</span>
                            </div>
                        </div>
                    </a></div>

                @endif
                @if($list->notification_type == 'UserInfo')
                <div class="col-md-12"><a href="{{ route('seller.detail') }}/{{ $list->type_id }}">
                        <div class="notif_vt">
                            <div class="notif_img">
                                <img src="{{ asset('web/images/productrating.png') }}" alt="">

                            </div>
                            <div class="notif_text_vt">
                                <h4>{{ isset($list->title) ? $list->title :'N\A' }}</h4>
                                <p>{{ isset($list->description) ? $list->description : 'N\A' }}</p>
                                <span>{{ isset($list->entry_date) ? $list->entry_date : 'N\A' }}</span>
                            </div>
                        </div>
                    </a></div>

                @endif
                @if($list->notification_type == 'Chat')
                <div class="col-md-12"><a href="{{ route('chats-details') }}/{{ $list->type_id }}">
                        <div class="notif_vt">
                            <div class="notif_img">
                                <img src="{{ asset('web/images/notificatiosimg.png') }}" alt="">

                            </div>
                            <div class="notif_text_vt">
                                <h4>{{ isset($list->title) ? $list->title :'N\A' }}</h4>
                                <p>{{ isset($list->description) ? $list->description : 'N\A' }}</p>
                                <span>{{ isset($list->entry_date) ? $list->entry_date : 'N\A' }}</span>
                            </div>
                        </div>
                    </a></div>

                @endif
                @endforeach
            </div>
            <div style="display: flex; justify-content: end; align-items: end; width: 100%;">
                {{ $notifications->links() }}
            </div>
        </div>

    </div>

</div>
@endsection
