<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Meeting Room Booking System') }}</title>
    <link rel="icon" href="{{ asset('images/logo/finallogo.png') }}">
    <link rel="stylesheet" href="{{ asset('admin/style.css') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <div class="sidebar">
        <div class="sidebar_header">
            <div id="logo_content">
                <img src="{{ asset('images/logo/finallogo.png') }}" id="logo">
            </div>
        </div>
        <nav class="sidebar_body">
            <ul class="nav_link">
                <li class="link_item {{ request()->is('home')? 'active' : '' }}" onclick="this.childNodes[1].click()">
                    <a href="{{ route('home') }}" class="">
                        <i class="material-symbols-outlined text-white mt-3 text-md">dashboard</i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="link_item {{ request()->is('admin/booking*')? 'active' : '' }}" onclick="this.childNodes[1].click()">
                    <a href="{{ route('admin#booking') }}" class="">
                        <i class="material-symbols-outlined text-white mt-3 text-md -translate-x-1">lists</i>
                        <span class="-translate-x-3">Booking</span>
                    </a>
                </li>
                <li class="link_item {{ request()->is('admin/user*')? 'active' : '' }}" onclick="this.childNodes[1].click()">
                    <a href="{{ route('admin#user') }}" class="">
                        <i class="material-symbols-outlined text-white mt-3 text-md" style="transform:translateX(-10px)">person_add</i>
                        <span class="-translate-x-7">User</span>
                    </a>
                </li>
                <li class="link_item {{ request()->is('admin/room*')? 'active' : '' }}" onclick="this.childNodes[1].click()">
                    <a href="{{ route('admin#room') }}">
                        <i class="material-symbols-outlined text-white mt-3 text-md -translate-x-2">meeting_room</i>
                        <span class="-translate-x-5">Room</span>
                    </a>
                </li>
                <li class="link_item " onclick="this.childNodes[1].click()">
                    <a href="javascript:{}">
                        <i class="material-symbols-outlined text-white mt-3 text-md -translate-x-1">book_online</i>
                        <span class="-translate-x-2">Booking</span>
                    </a>
                </li>
                {{-- <li class="link_item">
                    <a href="#">
                        <span>Post</span>
                    </a>
                </li>
                <li class="link_item">
                    <a href="#">
                        <span>Trend Post</span>
                    </a>
                </li> --}}
            </ul>
        </nav>

    </div>
    <div class="main_content">
        <div class="header flex justify-between px-4 z-10 ">
            <div class="">
                <i class="material-symbols-outlined cursor-pointer text-2xl pl-3 select-none" id="toggle_btn" style="line-height: 65px;">menu</i>

            </div>
            <div class="w-20 relative ">
                <img src="{{ asset('images/user_image/user(male).jpeg') }}" class="object-cover cursor-pointer drop_icon" id="profile_icon"  data-dropdown-toggle="dropdown">
                <div class=" inline-block text-left">

                    <div class="absolute  right-0 z-10 w-60 origin-top-right -translate-y-4 divide-y divide-gray-100 rounded-md bg-white shadow-xl ring-1 ring-black ring-opacity-5 focus:outline-none hidden" id="auth_drop" role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1" style="z-index: 9999">
                      <div class="pt-1" role="none">
                        <!-- Active: "bg-gray-100 text-gray-900", Not Active: "text-gray-700" -->
                        <span href="#" class="text-gray-700 text-xl justify-center group flex items-center px-4 py-2 text-sm" role="menuitem" tabindex="-1" id="menu-item-0">
                          Admin
                        </span>
                        <form action="{{ route('logout') }}" method="post" id="logout">
                            @csrf
                        <a href="javascript:{}" onclick="$('#logout').submit()" class=" text-gray-700 group flex items-center px-4 py-2 text-sm hover:bg-slate-200" role="menuitem" tabindex="-1" id="menu-item-1">
                                <i class="material-symbols-outlined mr-5">logout</i>
                              Log Out
                            </a>
                        </form>
                      </div>
                    </div>
                  </div>
            </div>
        </div>
        <div class="content">
            @yield('content')
        </div>
    </div>
</body>
@stack('js')

<script>
    $(document).ready(function(e){

        $(document).on('click','#toggle_btn',function(e){
            e.preventDefault();
            $('body').toggleClass('toggle');
        })

        $(document).on('click',document,function (e) {

            $link = e.target.matches('.drop_icon');
            if($link)
            {
                if($('#auth_drop').hasClass('hidden')){
                    $('#auth_drop').removeClass('hidden')
                }else{
                    $('#auth_drop').addClass('hidden')
                }
            }else{
                if(!$('#auth_drop').hasClass('hidden'))
                {
                    $('#auth_drop').addClass('hidden')
                }
            }

        })
    })
</script>

</html>
