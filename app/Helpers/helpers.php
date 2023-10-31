<?php

use Carbon\Carbon;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use App\Notifications\BookingNotification;
use Illuminate\Support\Facades\Notification;


 function getAuth(){
    return Auth::guard()->user();
}

function getRemainingTime($date,$time){
    $arr = [$date,$time];
    $start = implode(' ',$arr);
    $now = strtotime(Carbon::now());
    $start = strtotime($start);
    $diff = $start - $now;
    if($diff > 0){
        $days = floor($diff / 86400);
        $days = $days.'d';
        $diff = $diff % 86400;
        $hours = floor($diff / 3600);
        $diff = $diff % 3600;
        $minutes = floor($diff / 60);
        $seconds = $diff % 60;

        $date = $days .' '. str_pad($hours,2,'0',STR_PAD_LEFT) .':'. str_pad($minutes,2,'0',STR_PAD_LEFT) .':'. str_pad($seconds,2,'0',STR_PAD_LEFT);
    }else{
        $date = 0;
    }

    return $date;
}


function avaliable_duration($id){
    $item = Booking::where('id',$id)->first();
    date_default_timezone_set('Asia/Yangon');
    $dur = $item->duration;
    list($hour,$min,$sec) =explode(':',$dur);
    $total = ($hour * 3600) + ($min * 60) + $sec;
    $step = $total/1800;
    $arr = [];
    $start_tistr = strtotime('00:30:00');
    for($i = 1;$i <= $step ; $i++){
        $arr[] = date('H:i:s',$start_tistr);
        $start_tistr = $start_tistr + 1800;
    }
    return $arr;
}

function sendNoti($user,$booking_id,$request_id,$req_user_id)
{
    return Notification::send($user,new BookingNotification($booking_id,$request_id,$req_user_id));
}

function calculate_req_time($data){
    list($hour,$min,$sec) = explode(':',$data->total_duration);
    $total_sec = $hour*3600 + $min*60 + $sec;
    if($data->from == 'start')
    {
        $from   = strtotime($data->booking->start_time);
        $to     = $from + $total_sec;
        $from   = date('g:i A',$from);
        $to     = date('g:i A',$to);
    }else if($data->from == 'end')
    {
        $to     = strtotime($data->booking->end_time);
        $from   = $to-$total_sec;
        $from   = date('g:i A',$from);
        $to     = date('g:i A',$to);
    }
    $date = $from.'~'.$to;
    return $date;
}

function unread_noti_count()
{
    return getAuth()->unreadNotifications->count();
}
