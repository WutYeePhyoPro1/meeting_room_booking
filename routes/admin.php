<?php

use Carbon\Carbon;
use App\Models\Booking;
use App\Models\MeetingRoom;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\adminController;
use App\Http\Middleware\AuthCheckMiddleware;
use App\Http\Middleware\AdminCheckMiddleware;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
})->middleware(AuthCheckMiddleware::class);

Route::middleware(['auth:sanctum',config('jetstream.auth_session'),'verified',])->group(function () {
    route::get('/dashboard',[adminController::class,'dashboard'])->name('home');
    route::get('/home',[adminController::class,'home'])->name('admin#user_home');

    route::group(['prefix'=>'admin','controller'=>adminController::class],function(){
        route::group(['middleware'=>AdminCheckMiddleware::class],function(){
            route::get('user','user')->name('admin#user');
            route::get('room','room')->name('admin#room');
            //booking
            route::get('booking','booking')->name('admin#booking');
            route::get('booking/edit/{id}','edit_booking')->name('admin#editbooking');
            route::get('booking/detail/{id}','detail_booking')->name('admin#detailbooking');
            //user
            route::get('user/create','create')->name('create_user');
            route::get('user/delete/{id}','delete')->name('delete_user');
            route::post('user/create','store_user')->name('store_user');
            route::get('user/edit/{id}','edit')->name('edit_user');
            //room
            route::get('room/create','room_create')->name('create_room');
            route::get('room/edit/{id}','room_edit')->name('edit_room');
            route::post('room/create','store_room')->name('store_room');
            route::get('room/delete/{id}','room_delete')->name('delete_room');
        });

        //ajax
        route::get('room/boss/{id}','boss_in');
        route::get('room/guest/{id}','guest_in');
    });
});
