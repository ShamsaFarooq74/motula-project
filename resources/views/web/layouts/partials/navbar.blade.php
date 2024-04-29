<!-- end Topbar
@php($route = Route::current()->getName())

<style>
    #navbarSupportedContent .active{
        background-color:#eaf4ff;  
    }
    /* .link_vt:active{
        background-color:#eaf4ff; 
    } */

</style>
<div class="container-fluid px-0">      
        <header>
            <nav class="navbar navbar-expand-lg navbar-dark bg-white shadow-sm">
                <div class="container-fluid header-wrapper_vt">
                    <a class="navbar-brand" href="#"><img class="logo-img" src="{{asset('images/seru-logo.png')}}" alt="" srcset=""></a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                            <li class="nav-item">
                                <a class="nav-data">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-data" href="#">Courses</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-data">blogs</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-data">Contact</a>
                            </li>
                        </ul>
                        <div class="header-btn-widgit">
                            <a><i class="fontello icon-menu-icon"></i></a>
                            <a href="#" class="btn btn-warning header-btn_vt">Get Started</a>
                        </div>
                    </div>
                    
                </div>
            </nav>
        </header>
    </div>
<script>
    $(document).ready(function(){
    $('.dropdown-toggle').click(function(){
        $('li .link_vt').removeClass("active");
        $('.dropdown-toggle').removeClass("active");
        $(this).addClass("active");
    });
});
    </script> -->