<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\userController;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth:sanctum',config('jetstream.auth_session'),'verified',])->group(function () {
        route::group(['controller'=>BookingController::class],function(){
            route::get('booking/{id}','index')->name('booking_start');
            route::post('booking/store','store')->name('booking_store');
            route::get('mybooking','my_booking')->name('my_booking');
            route::get('todaybooking','today_booking')->name('today_booking');
            route::post('today_booking/request_booking','request_booking')->name('request_booking');
            route::get('request_booking/{id}','request_page')->name('request_page');
            route::post('request_booking/accept','req_accept')->name('request_accept');
            route::post('request_booking/reject','req_reject')->name('request_reject');
            route::post('my_booking/extend_time','extend')->name('extend');
            route::get('all_booking/history','booking_history')->name('booking_history');

            // ajax
            //booking
            route::post('booking/ajax/time_search','time_search')->name('time_search');
            route::post('booking/ajax/resize_check','check_resize')->name('resize_check');
            route::post('booking/ajax/drop_check','drop_check')->name('drop_check');
            route::get('booking/ajax/event_click/{id}','event_click')->name('event_click');
            //my_booking
            route::post('my_booking/ajax/cancel','booking_cancel')->name('booking_cancel');
            route::get('my_booking/ajax/filter/{id}','my_booking_filter')->name('booking_filter');
            route::get('my_booking/ajax/start/{id}','booking_start')->name('booking_start');
            route::get('my_booking/ajax/status/{id}','change_status')->name('change_status');
            route::get('my_booking/ajax/extend_time/{id}','extend_time');
            route::post('my_booking/ajax/end','end_booking')->name('end_booking');
            //today_booking
            route::get('today_booking/ajax/filter/{id}','today_booking_filter')->name('today_booking_filter');
            route::get('today_booking/ajax/booking_request/{id}','booking_req')->name('booking_req');
            // home
            route::get('room/ajax/{id}','room_status')->name('room_status');
            route::post('room/ajax/booking_status','booking_status')->name('booking_status');
            //notification(layout page)
            // route::get('booking/ajax/unread_noti','unread_noti');
            route::get('booking/ajax/read_due','read_due');
            route::get('booking/ajax/check_booking','check_booking')->name('check_booking');
            route::get('booking/ajax/noti_pass/{id}','noti_pass');
            route::get('booking/ajax/read_noti/{id}','read_noti');
            route::post('booking/ajax/resend_noti','resend_noti')->name('resend_noti');
        });

        route::group(['controller' => userController::class],function(){
            route::get('user/edit','edit')->name('user_edit');
            route::post('user/change_password','change_password')->name('user_change_password');
            // ajax
            route::post('user/change/backgound_color','change_bg_color')->name('change_bg_color');
            route::post('user/change/text_color','change_text_color')->name('change_text_color');
            route::post('user/change/icon','change_icon')->name('change_icon');
        });
});
