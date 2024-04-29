@php
    $currenturl = Request::segment(1);
    $current_route = Route::currentRouteName();
    $user = Auth::user();
@endphp
<style>
    .level {
        padding-left: 10px;
    }

    .subcategory-link {
        color: #2c3e50;
    }

    .nav .curved-box_vt {
        margin-right: 10px;
        padding-left: 15px;
    }

    .nav .active .level {
        color: red;
    }

    .nav .active .svg {
        color: red;
    }

    .subcategory-link:hover {
        color: red !important;
    }

    /* .active1{
        color: var(--sidebar-hover) !important;
        background-color: #DDEDFF;
    } */
</style>

<div class="left-sidebar-content mobile_vt">
    <div class="d-flex flex-column flex-shrink-0 p-3 bg-white navbar-wrapper h-100">
        <a href="/features" class="tabs-view-logo d-flex justify-content-center mb-4">
            <img src="{{ asset('assets/images/' . $setting[21]['value']) }}" alt="">
        </a>
        <ul class="nav nav-pills flex-column mb-auto left-nav-bar">
            <h1>Content Management</h1>
            @if (Auth::user()->role == 1)
                <li>
                    <a href="{{ route('lesson.bank') }}"
                        class="{{ $current_route == 'lesson.bank' ? 'nav-link active' : 'nav-link' }}">
                        <svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24"
                            stroke-linecap="round" stroke-linejoin="round" height="18px" width="18px"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M14 4h6v6h-6z"></path>
                            <path d="M4 14h6v6h-6z"></path>
                            <path d="M17 17m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0"></path>
                            <path d="M7 7m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0"></path>
                        </svg>
                        Modules
                    </a>
                </li>
            @endif
            <li>
                <a href="{{ route('lesson.topic') }}"
                    class="{{ $current_route == 'lesson.topic' ? 'nav-link active' : 'nav-link' }}">
                    <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" height="18px"
                        width="18px" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M10 3H4a1 1 0 0 0-1 1v6a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1zM9 9H5V5h4v4zm11-6h-6a1 1 0 0 0-1 1v6a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1zm-1 6h-4V5h4v4zm-9 4H4a1 1 0 0 0-1 1v6a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1v-6a1 1 0 0 0-1-1zm-1 6H5v-4h4v4zm8-6c-2.206 0-4 1.794-4 4s1.794 4 4 4 4-1.794 4-4-1.794-4-4-4zm0 6c-1.103 0-2-.897-2-2s.897-2 2-2 2 .897 2 2-.897 2-2 2z">
                        </path>
                    </svg>
                    Pillars
                </a>
            </li>
            <li>
                <a href="#" data-bs-toggle="collapse" data-bs-target="#employeeDropdown"
                    class="{{ request()->is('sub-category*') || request()->is('sub-catetogery-child') || request()->is('sub-catetogery-sub-child') ? 'nav-link active' : 'nav-link' }}">
                    <span>
                        <svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24"
                            stroke-linecap="round" stroke-linejoin="round" height="18px" width="18px"
                            xmlns="http://www.w3.org/2000/svg">
                            <line x1="8" y1="6" x2="21" y2="6"></line>
                            <line x1="8" y1="12" x2="21" y2="12"></line>
                            <line x1="8" y1="18" x2="21" y2="18"></line>
                            <line x1="3" y1="6" x2="3.01" y2="6"></line>
                            <line x1="3" y1="12" x2="3.01" y2="12"></line>
                            <line x1="3" y1="18" x2="3.01" y2="18"></line>
                        </svg>
                    </span>
                    <span style="padding-left: 2px;">Pillar Family</span>
                </a>
                <div class="collapse {{ request()->is('sub-category') || request()->is('sub-catetogery-child') || request()->is('sub-catetogery-sub-child') ? 'show' : '' }}"
                    id="employeeDropdown">
                    <ul class="nav flex-column" style="padding-left: 10px">
                        <li class="border-left d-flex">
                            <div class="curved-box_vt">
                                <a href="{{ route('show.tester.quiz') }}"
                                    class="{{ request()->is('sub-category') ? 'active' : '' }} subcategory-link">
                                    <span class="svg">
                                        <svg stroke="currentColor" fill="currentColor" stroke-width="0"
                                            viewBox="0 0 256 256" height="20px" width="20px"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M128,120a44,44,0,1,1,44-44A44.05,44.05,0,0,1,128,120Zm60,8a44,44,0,1,0,44,44A44.05,44.05,0,0,0,188,128ZM68,128a44,44,0,1,0,44,44A44.05,44.05,0,0,0,68,128Z">
                                            </path>
                                        </svg>
                                    </span>
                                    <span class="level">Sub-Pillars</span>
                                </a>
                            </div>
                        </li>
                        <li class="border-left d-flex">
                            <div class="curved-box_vt">
                                <a href="{{ route('sub_cate.child') }}"
                                    class="{{ request()->is('sub-catetogery-child') ? 'active' : '' }} subcategory-link">
                                    <span class="svg">
                                        <svg stroke="currentColor" fill="currentColor" stroke-width="0"
                                            viewBox="0 0 256 256" height="20px" width="20px"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M128,120a44,44,0,1,1,44-44A44.05,44.05,0,0,1,128,120Zm60,8a44,44,0,1,0,44,44A44.05,44.05,0,0,0,188,128ZM68,128a44,44,0,1,0,44,44A44.05,44.05,0,0,0,68,128Z">
                                            </path>
                                        </svg>
                                    </span>
                                    <span class="level">Product Type</span>
                                </a>
                            </div>
                        </li>
                        <li class="border-left d-flex">
                            <div class="curved-box_vt">
                                <a href="{{ route('sub_cate.sub_child') }}"
                                    class="{{ request()->is('sub-catetogery-sub-child') ? 'active' : '' }} subcategory-link">
                                    <span class="svg">
                                        <svg stroke="currentColor" fill="currentColor" stroke-width="0"
                                            viewBox="0 0 256 256" height="20px" width="20px"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M128,120a44,44,0,1,1,44-44A44.05,44.05,0,0,1,128,120Zm60,8a44,44,0,1,0,44,44A44.05,44.05,0,0,0,188,128ZM68,128a44,44,0,1,0,44,44A44.05,44.05,0,0,0,68,128Z">
                                            </path>
                                        </svg>
                                    </span>
                                    <span class="level">Product Family</span>
                                </a>
                            </div>
                        </li>
                    </ul>
                </div>
            </li>
            <h1>Manage Files</h1>
            <li>
                <a href="{{ route('file.type') }}"
                    class="{{ $current_route == 'file.type' ? 'nav-link active' : 'nav-link' }}">
                    <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 384 512"
                        height="18px" width="18px" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M288 248v28c0 6.6-5.4 12-12 12H108c-6.6 0-12-5.4-12-12v-28c0-6.6 5.4-12 12-12h168c6.6 0 12 5.4 12 12zm-12 72H108c-6.6 0-12 5.4-12 12v28c0 6.6 5.4 12 12 12h168c6.6 0 12-5.4 12-12v-28c0-6.6-5.4-12-12-12zm108-188.1V464c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V48C0 21.5 21.5 0 48 0h204.1C264.8 0 277 5.1 286 14.1L369.9 98c9 8.9 14.1 21.2 14.1 33.9zm-128-80V128h76.1L256 51.9zM336 464V176H232c-13.3 0-24-10.7-24-24V48H48v416h288z">
                        </path>
                    </svg>
                    File Type
                </a>
            </li>
            <li>
                <a href="{{ route('files') }}"
                    class="{{ request()->is('files') || request()->is('view/file*') ? 'nav-link active' : 'nav-link' }}">
                    <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24"
                        height="18px" width="18px" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M17.5 0h-9L7 1.5V6H2.5L1 7.5v15.07L2.5 24h12.07L16 22.57V18h4.7l1.3-1.43V4.5L17.5 0zm0 2.12l2.38 2.38H17.5V2.12zm-3 20.38h-12v-15H7v9.07L8.5 18h6v4.5zm6-6h-12v-15H16V6h4.5v10.5z">
                        </path>
                    </svg>
                    Files
                </a>
            </li>
            @if (Auth::user()->role == 1)
                <h1>Role Management</h1>
                <li>
                    <a href="{{ route('admins') }}"
                        class="{{ $current_route == 'admins' ? 'nav-link active' : 'nav-link' }}">
                        <i class="fontello icon-account-Users me-1"></i>
                        Admin
                    </a>
                </li>
                <li>
                    <a href="{{ route('users') }}"
                        class="{{ $current_route == 'users' ? 'nav-link active' : 'nav-link' }}">
                        <i class="fontello icon-account-Users me-1"></i>
                        Users
                    </a>
                </li>
                <li>
                    <a href="{{ route('editor') }}"
                        class="{{ $current_route == 'editor' ? 'nav-link active' : 'nav-link' }}">
                        <i class="fontello icon-account-Users me-1"></i>
                        Editor
                    </a>
                </li>
                <li>
                    <a href="{{ route('regions') }}"
                        class="{{ $current_route == 'regions' ? 'nav-link active' : 'nav-link' }}">
                        <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24"
                            height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                            <path fill="none" d="M0 0h24v24H0V0z"></path>
                            <path
                                d="M17 7h2v2h-2zM17 11h2v2h-2zM17 15h2v2h-2zM1 11v10h6v-5h2v5h6V11L8 6l-7 5zm12 8h-2v-5H5v5H3v-7l5-3.5 5 3.5v7z">
                            </path>
                            <path d="M10 3v1.97l2 1.43V5h9v14h-4v2h6V3z"></path>
                        </svg>
                        Region
                    </a>
                </li>
            @endif
        </ul>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const employeeDropdown = document.getElementById('employeeDropdown');
        if (employeeDropdown.classList.contains('show')) {
            const parentLink = document.querySelector('.active1');
            if (parentLink) {
                parentLink.classList.add('active1');
            }
        }
    });
    $(document).ready(function() {
        // Add click event handler for the subcategory links
        $('.subcategory-link').on('click', function() {
            // Remove the active class from all subcategory links
            $('.subcategory-link').removeClass('active');

            // Add the active class to the clicked subcategory link
            $(this).addClass('active');

            // Remove the active class from all icons
            $('.subcategory-icon').removeClass('active');

            // Add the active class to the icon associated with the clicked subcategory link
            $(this).siblings('.subcategory-icon').addClass('active');
        });
    });
</script>
