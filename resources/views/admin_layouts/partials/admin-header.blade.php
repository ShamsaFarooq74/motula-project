@php
    $currenturl =Request::segment(1);
@endphp
<div class="admin-header-area">
    <div class="admin-header">
        <div class="header-title">
            <h1>@if ($currenturl == 'features')
            Modules
            @elseif (request()->is('category'))
            Pillars
            @elseif (request()->is('sub-category'))
            Sub Pillars
            @elseif (request()->is('sub-catetogery-child'))
            Product Type
            @elseif (request()->is('sub-catetogery-sub-child'))
            Product Family
            @elseif (request()->is('view/file/*'))
             
            <a href="{{ url()->previous() }}">
                <img src="{{ asset('assets/images/back.png') }}" style="margin-top: -4px" alt="">
            </a>Files
            @elseif (request()->is('admins'))
               Admin
            @elseif (request()->is('users'))
              User
            @elseif (request()->is('editor'))
              Editor
            @elseif (request()->is('regions'))
               Regions
            @elseif (request()->is('files-type'))
               File Type
            @elseif (request()->is('files'))
               Files
            @else
                Motul
            @endif</h1>
        </div>
        <div class="toggle-btn_vt image-menu">
            <i class="fontello icon-article-alt2"></i>
        </div>
        <div class="admin-header-right">
            <div class="position-relative">
                <div class="icons-area_vt">
                     {{-- <a href="#" class="icon-widgit">
                        <i class="fontello icon-mail"></i>
                    </a>
                    <a href="#" class="icon-widgit">
                        <i class="fontello icon-bell"></i>
                    </a>  --}}
                </div>
            </div>
            <div>
                <div class="d-flex align-items-center">
                    <img src="{{Auth::user()->image != null && file_exists(public_path().'/assets/profile-pic/'.Auth::user()->image) ? asset('assets/profile-pic/'.Auth::user()->image) : asset('assets/images/default-user.png')}}" class="admin-header-img" alt="">
                    <div class="profile-name pl-10">
                        <div class="d-flex align-items-center">
                            <span class="title-name">{{Auth::user()->full_name}}</span>
                            <div class="dropdown">
                                <button class="btn header-btn-arrow dropdown-toggle p-0 header-btn-arrow" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                </button>
                                <ul class="dropdown-menu header-menu" aria-labelledby="dropdownMenuButton1">
                                <li><a class="dropdown-item" href="{{route('update.profile')}}"><i class="fontello icon-users pr-10"></i>My Profile</a></li>
                                <li><a class="dropdown-item text-danger" href="{{route('logout')}}"><i class="fontello icon-logout pr-10"></i>Logout</a></li>
                                </ul>
                            </div>
                        </div>
                        <p class="name-sub-title">Super Admin</p>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementsByClassName('image-menu')[0].addEventListener("click", evt => {

   if( $('.mobile_vt').hasClass('d-none')){
    // let image = '{{asset('assets/img/lines.svg')}}';
    $('.mobile_vt').css('display','block')
    $('.mobile_vt').addClass('d-block')
    $('.mobile_vt').removeClass('d-none')
    // $('.image-menu').attr('src',image)

   }else{
    // let image = '{{asset('assets/img/lines.svg')}}'
    $('.mobile_vt').css('display','none')
    $('.mobile_vt').addClass('d-none')
    $('.mobile_vt').removeClass('d-block')
    // $('.image-menu').attr('src',image)
   }



})
</script>