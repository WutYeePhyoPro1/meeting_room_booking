<?php

use Carbon\Carbon;
use App\Models\User;
use App\Models\Booking;
use App\Models\BookingRequest;
use Illuminate\Support\Facades\DB;
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
        $from   = strtotime($data->booking->original_start ? $data->booking->original_start : $data->booking->start_time);
        $to     = $from + $total_sec;
        $from   = date('g:i A',$from);
        $to     = date('g:i A',$to);
    }else if($data->from == 'end')
    {
        $to     = strtotime($data->booking->original_end ? $data->booking->original_end : $data->booking->end_time);
        $from   = $to-$total_sec;
        $from   = date('g:i A',$from);
        $to     = date('g:i A',$to);
    }
    $date = 'From <b class="text-emerald-500">'.$from.'</b> To <b class="text-emerald-500">'.$to.'</b>';
    return $date;
}

function unread_noti_count()
{
    return getAuth()->unreadNotifications->count();
}

function noti_in_one_week()
{
    $user_id = getAuth()->id;
    $noti = DB::table('notifications')
                ->where('notifiable_id', $user_id )
                ->where('notifiable_type', 'App\\Models\\User')
                ->whereDate('created_at','>',Carbon::now()->subDays(7))
                ->orderBy('created_at','desc')
                ->get();

    // $arr = [];
    // foreach($noti as $item)
    // {
    //     $diff = $item->created_at->diffForHumans();
    //     $item['diff'] = $diff;
    //     $arr .= $item;
    // }
    return $noti;
}

function noti_msg($req_id,$req_user)
{
    $book_req = BookingRequest::where('id',$req_id)->first();
    $request_user = User::where('id',$req_user)->first();
    $msg = '';
    if($book_req->request_user == getAuth()->id)
    {
        switch ($book_req->request_status) {
            case 1:
                if($book_req->resend_status){
                    $msg = "Your Have <b class='text-emerald-600'>Accepted</b> The New Conditions By ".$book_req->booking->user->name ?? '';
                }else{
                    $msg = "Your Request Have Been <b class='text-emerald-600'>Accepted</b> By ".$book_req->approve->name ?? '';
                }
                    break;
            case 2:
                $msg = "System Has Auto <b class='text-rose-600'>Reject</b> Since Exceeded The Meeting Time";
                    break;
            case 3:
                $msg = "You have been <b class='text-rose-600'>Rejected</b>";
                    break;
            case 4:
                $msg = "You Have Changed And <b class='text-sky-600'>Resended</b> New Condition";
                    break;
            case 5:
                $msg = $book_req->booking->user->name. " Have Changed And Resended The Condition";
                    break;
            default:
                $msg = "Something Is Wrong,Please Contact SD Team";
                break;
        }
    }else{
        switch ($book_req->request_status) {
            case 0:
                $msg = $request_user->name." Has Send You A Request";
                break;
            case 1:
                if($book_req->resend_status){
                    $msg = $book_req->approve->name ." had <b class='text-emerald-600'>Accepted</b> Your New Conditions ";
                }else{
                    $msg = "You <b class='text-emerald-600'>Accepted</b> The Request From ".$request_user->name;
                }
                    break;
            case 2:
                $msg = "System Has Auto <b class='text-rose-600'>Reject</b> Since Exceeded The Meeting Time";
                    break;
            case 3:
                $msg = "You <b class='text-rose-600'>Rejected</b>";
                    break;
            case 4:
                $msg = "You Have Changed And <b class='text-sky-600'>Resended</b> New Condition";
                    break;
            case 5:
                $msg = $book_req->booking->user->name. " Have Changed And Resent The Condition";
                    break;
            default:
                $msg = "Something Is Wrong,Please Contact SD Team";
                    break;
        }
    }
    return $msg;
}

function check_extendable($id)
{
    $arr = [];
    $data = Booking::where('id',$id)->first();
    $all  = Booking::where('date',$data->date)
                    ->where('start_time','>=',$data->end_time)
                    ->where('room_id',$data->room_id)
                    ->orderBy('start_time','asc')
                    ->first();

    $tem = strtotime($data->end_time);
    if($all){
        $start_time = strtotime($all->start_time);
        $diff = $start_time - $tem;
        if($diff > 1800){
            $diff = $diff - 1800;
            $step = $diff/1800;
            $start_str = 1800;
            for($i = 0 ; $i < $step ; $i++){
                $hours = floor($start_str / 3600);
                $min = floor(($start_str % 3600) / 60);
                $sec = $start_str % 60;
                $arr[] = sprintf("%02d:%02d:%02d", $hours, $min, $sec);
                $start_str +=1800;
            }
        }
    }else{
        $start_time = strtotime('17:30:00');
        $diff = $start_time - $tem;
        if($diff > 1800){
            $diff = $diff - 1800;
            $step = $diff/1800;
            $start_str = 1800;
            for($i = 0 ; $i < $step ; $i++){
                $hours = floor($start_str / 3600);
                $min = floor(($start_str % 3600) / 60);
                $sec = $start_str % 60;
                $arr[] = sprintf("%02d:%02d:%02d", $hours, $min, $sec);
                $start_str = $start_str + 1800;
            }
        }
    }
    return $arr;
}

function today_booking_or_not($id){
    $booking = Booking::where('id',$id)->first();
    $now     = Carbon::now()->format('Y-m-d');
    if($now == $booking->date){
        return 1;
    }else{
        return 0;
    }
}

function is_early_end($id){
    $booking = Booking::where('id',$id)->first();
    $end_time= strtotime($booking->end_time);
    $valid_time = floor(strtotime($booking->end_time)-5*60);

    $finish_time = strtotime($booking->finished_time);

    if($finish_time >= $valid_time && $finish_time < $end_time){
        return 1;
    }else{
        return 0;
    }
}

function is_request($id){
    $req = BookingRequest::where('booking_id',$id)
                        ->where('request_user',getAuth()->id)->first();
    return $req;
}

function get_user_name($id)
{
    $name = User::where('id',$id)->first();
    return $name->name;
}

function get_status($id)
{
    $id = $id==6 ?  0 : $id;
    $msg = '';
    switch ($id) {
        case 0: $msg = 'Pending';
            break;
        case 1 : $msg = 'Started';
            break;
        case 2 : $msg = 'Ended';
            break;
        case 3: $msg = 'Cancelled';
            break;
        case 4: $msg = 'Missed';
            break;
        case 5: $msg = 'Finished';
            break;
        default: $msg = '';
            break;
    }
    return $msg;
}
