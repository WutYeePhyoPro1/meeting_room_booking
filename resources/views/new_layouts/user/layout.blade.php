<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="__token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('images/logo/finallogo.png') }}">
    <title>{{ config('app.name','Meeting Room Booking System') }}</title>
    <link rel="stylesheet" href="{{ asset('user/style.css') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    {{-- <link rel="stylesheet" href="{{ asset('css/fullcalendar.min.css') }}"> --}}
    <script src="{{ asset('js/index.global.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI=" crossorigin="anonymous"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('css')
</head>
<body style="">
    <nav class="w-full h-16 flex justify-between" style="box-shadow:2px 4px 5px rgb(0,0,0,0.4)">
        <img class="cursor-pointer" onclick="$('#home').click()" src="{{ asset('images/logo/finallogo.png') }}" alt="">
        <ul class="flex ms-4 h-full" style="line-height:64px">
            <li class="ms-10 cursor-pointer hover:bg-amber-500 hover:text-white hover:px-5 duration-500 {{ request()->is('home*')? 'bg-amber-500 px-5 text-white' : ''}}" onclick="this.childNodes[1].click()">
                <a href="{{ route('home') }}" id="home">HOME</a>
            </li>
            <li class="ms-10 cursor-pointer hover:bg-amber-500 hover:text-white hover:px-5 duration-500 {{ request()->is('overview*')? 'bg-amber-500 px-5 text-white' : ''}}" onclick="this.childNodes[1].click()">
                <a href="{{ route('overview') }}" id="home">OVERVIEW</a>
            </li>

                    <li class="ms-10 cursor-pointer hover:bg-amber-500 hover:text-white hover:px-5 duration-500 relative select-none {{ (request()->is('mybooking*') || request()->is('todaybooking*') || request()->is('all_booking/history') || request()->is('action*')) ? 'bg-amber-500 px-5 text-white' : ''}}" id="nav_drop_hov" onclick="this.childNodes[1].click()">
                        <div class="bg-white h-0" onclick="this.childNodes[1].click()">
                            <span class="select-none" id="book_list" data-dropdown-toggle="dropdown">{{ getAuth()->employee_id == 'recho@pro1' ? 'ACTION' : 'BOOKING' }}</span>

                            <div class=" inline-block text-left">

                                <div class="absolute  z-10 w-60 origin-top-right translate-y-6 divide-y divide-gray-100  bg-cus2 text-black shadow-xl ring-1 ring-black ring-opacity-5 focus:outline-none hidden" id="nav_drop" role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1" style="z-index: 9999;right:-100px">
                                <div class="" role="none">
                                    @if (getAuth()->employee_id == 'recho@pro1')
                                        <a href="{{ route('action') }}" class="group flex items-center px-4 py-2 text-sm hover:bg-amber-500 hover:text-white {{ request()->is('action*') ? 'bg-amber-500 text-white' : '' }}" role="menuitem" tabindex="-1" id="menu-item-1">
                                            <i class="material-symbols-outlined mr-5">check</i>
                                            ACTION
                                        </a>
                                    @endif

                                    <a href="{{ route('my_booking') }}" class="group flex items-center px-4 py-2 text-sm hover:bg-amber-500 hover:text-white {{ request()->is('mybooking*') ? 'bg-amber-500 text-white' : '' }}" role="menuitem" tabindex="-1" id="menu-item-1">
                                        <i class="material-symbols-outlined mr-5">keyboard_tab</i>
                                    {{ getAuth()->employee_id == 'recho@pro1' ? 'UPCOMING BOOKING' : 'MY UPCOMING BOOKING' }}
                                    </a>
                                    @if (getAuth()->employee_id != 'recho@pro1')
                                        <a href="{{ route('booking_history') }}" class="group flex items-center px-4 py-2 text-sm hover:bg-amber-500 hover:text-white {{ request()->is('all_booking/history*') ? 'bg-amber-500 text-white' : '' }}" role="menuitem" tabindex="-1" id="menu-item-1">
                                            <i class="material-symbols-outlined mr-5">history</i>
                                        MY BOOKING HISTORY
                                        </a>
                                        <a href="{{ route('today_booking') }}" class="group flex items-center px-4 py-2 text-sm hover:bg-amber-500 hover:text-white {{ request()->is('todaybooking*') ? 'bg-amber-500 text-white' : ''  }}" role="menuitem" tabindex="-1" id="menu-item-1">
                                            <i class="material-symbols-outlined mr-5">list</i>
                                        ALL BOOKING WITHIN WEEK
                                        </a>
                                    @endif
                                </div>
                                </div>
                            </div>
                        </div>
                    </li>
            {{-- <li class="ms-10 cursor-pointer hover:bg-amber-500 hover:text-white hover:px-5 duration-500" onclick="this.childNodes[1].click()">BOOKING TODAY'S LIST</li> --}}
            {{-- <li class="ms-10 cursor-pointer hover:bg-amber-500 hover:text-white hover:px-5 duration-500" onclick="this.childNodes[1].click()">BOOKING HISTORY</li> --}}
            {{-- <li class="ms-10 cursor-pointer hover:bg-amber-500 hover:text-white hover:px-5 duration-500">REQUEST NOTI</li> --}}
        </ul>
        <input type="hidden" id="user_id" value="{{ getAuth()->employee_id }}">
        <div class="header flex justify-between px-10 z-10 relative">
            @if (getAuth()->employee_id != 'recho@pro1')
                    <div class="relative me-10" id="noti_div">
                        <i class="material-symbols-outlined text-4xl mt-3 cursor-pointer font-extralight select-none mr-16" id="noti_btn">notifications</i>
                        <span class="bg-rose-600 text-white text-xs px-1 rounded-full absolute left-5 top-3 cursor-pointer " onclick="$('#noti_btn').click()" id="id_count"></span>


                        <div class=" inline-block text-left">

                            <div class="absolute overflow-hidden overflow-y-scroll right-28 top-14 z-10 w-72 origin-top-right translate-y-2 translate-x-10 divide-y divide-gray-100 rounded-md bg-white shadow-xl ring-1 ring-black ring-opacity-5 focus:outline-none hidden" id="noti_drop" role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1" style="z-index: 9999;max-height:500px;">
                            <div class="" role="none">
                                @foreach (noti_in_one_week() as $item)
                                        @php
                                            $data = json_decode($item->data,true);
                                        @endphp
                                    <a href="{{ route('request_page',['id'=>$data['request_id']]) }}" class="break-word flex flex-col {{ $item->read_at ? '' : 'bg-sky-50' }} text-gray-700 group px-4 py-2 text-sm hover:bg-slate-200" role="menuitem" tabindex="-1" id="menu-item-1">
                                        <span>{!! noti_msg($data['request_id'],$data['req_user_id']) !!}</span>
                                        <div class="text-end">
                                            <small class="">{{ \Carbon\Carbon::parse($item->created_at)->diffForHumans() }}</small>
                                        </div>
                                    </a><hr>
                                @endforeach
                            </div>
                            </div>
                        </div>

                    </div>
                @endif
            <div class="w-20 relative ">
                <i class="material-symbols-outlined cursor-pointer drop_icon text-5xl mt-2 font-thin select-none" id="profile_icon"  data-dropdown-toggle="dropdown">{{ getAuth()->icon ?? 'person' }}</i>
                <div class=" inline-block text-left">

                    <div class="absolute  right-2 z-10 w-48 origin-top-right translate-y-2 translate-x-10 divide-y divide-gray-100 rounded-md bg-white shadow-xl ring-1 ring-black ring-opacity-5 focus:outline-none hidden" id="auth_drop" role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1" style="z-index: 9999">
                      <div class="pt-1" role="none">
                        <!-- Active: "bg-gray-100 text-gray-900", Not Active: "text-gray-700" -->
                        <span href="#" class="text-gray-700 text-xl justify-center group flex items-center px-4 py-2 text-sm" role="menuitem" tabindex="-1" id="menu-item-0">
                          {{ getAuth()->name }}
                        </span><hr>
                        @if (getAuth()->employee_id != 'recho@pro1' )
                            <a href="{{ route('admin#dashboard') }}" class="whitespace-nowrap text-gray-700 group flex items-center px-4 py-2 text-sm hover:bg-slate-200" role="menuitem" tabindex="-1" id="menu-item-1">
                                <i class="material-symbols-outlined mr-3">keyboard_return</i>
                            Go To Dashboard
                            </a>
                        @endif
                        <a href="{{ asset('user_guide/Meeting Room Booking System (User Guide) By SD.pdf') }}" target="_blank" class="whitespace-nowrap text-gray-700 group flex items-center px-4 py-2 text-sm hover:bg-slate-200" role="menuitem" tabindex="-1" id="menu-item-1">
                            <i class="material-symbols-outlined mr-3">developer_guide</i>
                          User Guide
                        </a>
                        <a href="{{ route('user_edit') }}" class="whitespace-nowrap text-gray-700 group flex items-center px-4 py-2 text-sm hover:bg-slate-200" role="menuitem" tabindex="-1" id="menu-item-1">
                            <i class="material-symbols-outlined mr-3">key</i>
                          Change Password
                        </a>
                        <form action="{{ route('logout') }}" method="post" id="logout">
                            @csrf
                            <a href="javascript:{}" onclick="$('#logout').submit()" class=" text-gray-700 group flex items-center px-4 py-2 text-sm hover:bg-slate-200" role="menuitem" tabindex="-1" id="menu-item-1">
                                <i class="material-symbols-outlined mr-3">logout</i>
                              Log Out
                            </a>
                        </form>
                      </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    <div class="main_content">
        @yield('content')
        <div class="fixed bottom-20 bg-amber-400 right-0 shadow-lg shadow-slate-600 px-5 py-2 ps-10 mb-20 rounded-l-lg duration-500 noti_div whitespace-nowrap w-0 opacity-0 pointer-events-none">
            <i class="material-symbols-outlined absolute text-rose-600 text-3xl left-2 top-1 cursor-pointer py-2" id="close_noti_btn">close</i>
            <div class="text-center text-white flex px-5 py-1">
                <i class="material-symbols-outlined text-2xl noti">alarm_on</i>
                @if (getAuth()->employee_id == 'recho@pro1')
                    <span class="ms-2 mt-1">| &nbsp;&nbsp;<b id="owner"></b> Booking for <b id="booking_room"></b> is going to Start in <b id="start_minute"></b> min</span>
                @else
                    <span class="ms-2 mt-1">| &nbsp;&nbsp;Your Booking for <b id="booking_room"></b> is going to Start in <b id="start_minute"></b> min</span>
                @endif
            </div>
        </div>
    </div>
</body>
@stack('js')
{{-- <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script
  type="text/javascript"
  src="../node_modules/tw-elements/dist/js/tw-elements.umd.min.js"></script> --}}
<script>
    $(document).ready(function(e){

        read_due_booking();
        check_booking();
        setInterval(() => {
            // count_noti();
            read_due_booking()
            check_booking();
        }, 60000);

        // function count_noti()
        // {
        //     $.ajax({
        //         url : "/booking/ajax/unread_noti",
        //         type: 'get',
        //         success: function(res){
        //             $('#id_count').text(res);
        //         }
        //     })
        // }
        function check_booking()
        {
            $id = $('#user_id').val();
            $.ajax({
                url: "{{ route('check_booking') }}",
                type: 'get',
                success: function(res){
                    // console.log(res.data);
                    if(res.status == 'start')
                    {
                        if($id = 'recho@pro1'){
                            $('#owner').text(res.data.user.name)
                        }
                        $('#booking_room').text(res.data.room.room_name);
                        $('#start_minute').text(res.min);
                        $('.noti_div').removeClass('w-0 opacity-0 pointer-events-none');
                        $('.noti_div').addClass('xl:w-4/12 w-5/12');
                        $('#close_noti_btn').attr('data-id',res.data.id);
                    }
                    if(res.status == 'no'){
                        if(!$('.noti_div').hasClass('w-0 opacity-0 pointer-events-none')){
                            $('.noti_div').addClass('w-0 opacity-0 pointer-events-none');
                        }
                        if(!$('.noti_div').hasClass('xl:w-4/12 w-5/12')){
                            $('.noti_div').removeClass('xl:w-4/12 w-5/12');
                            $('.noti_div').addClass('w-0');
                        }
                    }
                }
            })
        }

        function read_due_booking()
        {
            $.ajax({
                url : "/booking/ajax/read_due",
                type: 'get',
                success: function(res){
                    $('#id_count').text(res);
                    if(res > 0){
                        $('#noti_div').append(`
                        <span class="bg-rose-600 text-white text-xs px-2 py-2 rounded-full absolute left-5 top-3 cursor-pointer animate-ping" id="noti_animate"></span>
                        `);
                    }else{
                        $('#noti_animate').remove();
                    }
                }
            })
        }

        $(document).on('click','#profile_icon',function(e){
            $('#auth_drop').toggle();
        })

        // $(document).on('click','#book_list',function(e){
        //     $('#nav_drop').toggle();
        // })

        $(document).on('mouseover','#nav_drop_hov',function(e){
            $('#nav_drop').removeClass('hidden');
        })
        $(document).on('mouseleave','#nav_drop_hov',function(e){
            $('#nav_drop').addClass('hidden');
        })

        $(document).on('click','#noti_btn',function(){
            $('#noti_drop').toggle();
        })

        $(document).on('click','#close_noti_btn',function(){
            $id = $(this).data('id');
            $.ajax({
                url : "/booking/ajax/noti_pass/"+$id,
                type: 'get',
                success: function(res){
                    $('.noti_div').removeClass('w-4/12');
                    $('.noti_div').addClass('w-0 opacity-0 pointer-events-none');
                }
            })
        })
    })
</script>
</html>
