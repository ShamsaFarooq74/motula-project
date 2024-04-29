
@php
    $current_route = Route::currentRouteName();
@endphp
    <div class="container-fluid px-0">      
        <header>
            <nav class="navbar navbar-expand-lg navbar-dark bg-white shadow-sm nav-bar_vt">
                <div class="container-fluid header-wrapper_vt">
                    <div class="d-flex align-items-center">
                        <a class="navbar-brand" href="{{ url('/') }}"><img class="logo-img" src="{{asset('assets/images/'.$setting[21]['value'])}}" alt="" srcset=""></a>
                        @if($current_route == 'course.sessions')
                        <a class="tabs-view_vt toggle-btn_vt image-menu" href="#"><i class="fontello icon-book-icon"></i> Course Details</a>
                        @endif
                    </div>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                            <li class="nav-item">
                                <a class="{{ ($current_route == 'index') ? 'nav-active' : 'nav-data' }}" href="{{route('index')}}">Home<i class="{{ ($current_route == 'index') ? 'fontello icon-down-open pl-5' : 'fontello icon-angle-right pl-5' }} "></i></a>
                            </li>
                            <li class="nav-item">
                                <a class="{{ ($current_route == 'all.courses' || $current_route == 'course.detail' || $current_route == 'course.sessions') ? 'nav-active' : 'nav-data' }}" href="{{route('all.courses')}}">Courses<i class="{{ ($current_route == 'all.courses' || $current_route == 'course.detail' || $current_route == 'course.sessions') ? 'fontello icon-down-open pl-5' : 'fontello icon-angle-right pl-5' }} "></i></a>
                            </li>
                            <li class="nav-item">
                                <a class="{{ ($current_route == 'all.blogs' || $current_route == 'blog.detail') ? 'nav-active' : 'nav-data' }}" href="{{route('all.blogs')}}">blogs<i class="{{ ($current_route == 'all.blogs' || $current_route == 'blog.detail') ? 'fontello icon-down-open pl-5' : 'fontello icon-angle-right pl-5' }} "></i></a>
                            </li>
                            <li class="nav-item">
                                <a class="{{ ($current_route == 'contact.us') ? 'nav-active' : 'nav-data' }}" href="{{route('contact.us')}}">Contact<i class="{{ ($current_route == 'contact.us') ? 'fontello icon-down-open pl-5' : 'fontello icon-angle-right pl-5' }} "></i></a>
                            </li>
                        </ul>
                        <div class="header-btn-widgit">
                            <a><i class="fontello icon-menu-icon"></i></a>
                            @if(!Auth::user())
                            <a href="{{route('web.login')}}" class="btn btn-warning header-btn_vt rounded-0">Get Started</a>
                            @else
                            <div class="d-flex align-items-center">
                                <img src="{{Auth::user()->image != null && file_exists(public_path().'/assets/profile-pic/'.Auth::user()->image) ? asset('assets/profile-pic/'.Auth::user()->image) : asset('assets/images/default-user.png')}}" class="header-img" alt="">
                                <div class="profile-name pl-10">
                                    <span>{{Auth::user()->full_name}}</span>
                                    <p>{{Auth::user()->email}}</p>
                                </div>
                                <div class="dropdown">
                                    <button class="btn header-btn-arrow dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                    </button>
                                    <ul class="dropdown-menu header-menu" aria-labelledby="dropdownMenuButton1">
                                      <li><a class="dropdown-item" href="{{route('user.profile')}}"><i class="fontello icon-users pr-10"></i>My Profile</a></li>
                                      <li><a class="dropdown-item text-danger" href="{{route('logout')}}"><i class="fontello icon-logout pr-10"></i>Logout</a></li>
                                    </ul>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    
                </div>
            </nav>
            <style type="text/css">
                :root{
  --bs-warning: #69AFC5;
  --bs-green: #00E540;
  --bs-gray: #D2D4E5;
  --bs-orange: #E56E00;
/* --shadow-color: 74, 208, 35; */

  --btn-bg-color: {{ $config[1]['value'] }};
  --shadow-color: {{ $config[8]['value'] }};
  /* --shadow-color: {{ $setting[9]['value'] }} */
  --btn-color:{{ $config[0]['value'] }};
  --sub-heading-color: {{ $config[4]['value'] }};
  --text-color :  {{ $config[7]['value'] }};
  --heading-color: {{ $config[5]['value'] }};
  --bg-color: {{ $config[3]['value'] }};
  --icon-color: {{ $config[6]['value'] }};
  --font-swiss: Swiss;
  --font-NHaas: NHaas;
  --radio-bg-color: {{ $config[2]['value'] }};
  --footer-bg-img: url(../images/{{$setting[24]['value']}}) ;
}
                </style>
        </header>
    </div>

    <script>
document.getElementsByClassName('image-menu')[0].addEventListener("click", evt => {

if( $('.mobile-1').hasClass('d-none')){
 // let image = '{{asset('assets/img/lines.svg')}}';
 $('.mobile-1').css('display','block')
 $('.mobile-1').addClass('d-block')
 $('.mobile-1').removeClass('d-none')
 // $('.image-menu').attr('src',image)

}else{
 // let image = '{{asset('assets/img/lines.svg')}}'
 $('.mobile-1').css('display','none')
 $('.mobile-1').addClass('d-none')
 $('.mobile-1').removeClass('d-block')
 // $('.image-menu').attr('src',image)
}



})
</script>


