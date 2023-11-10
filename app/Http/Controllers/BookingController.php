<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Branch;
use App\Models\Reason;
use App\Models\Booking;
use App\Models\MeetingRoom;
use Illuminate\Http\Request;
use App\Models\BookingRequest;
use Illuminate\Support\Facades\DB;
use App\Repositories\BookingRepository;
use Illuminate\Support\Facades\Notification;
use App\Interfaces\BookingRepositoryInterface;

//store booking data
// go to my booking page
// go to today's booking
//go to request Noti page
//go to booking history page
//search time
//check resize time(booking တခုနဲ့တခုကြား 30 miခြားမခြားစစ် calander က resize လုပ်တဲ့အချိန် )
//drop validation fullcalendar
//edit to click
//cancel booking
//extend booking
//my booking filter
//today booking filter
// check room status
//booking start
//request booking accept
//change status
//booking change status
//request booking
//end booking
//check booking exist
//read due booking
//check booking
//noti pass(10 min အလို alerm ပေးတာကို သိပြီကြောင်း)
//read noti
//resend_noti
//get avaliable extend time

class BookingController extends Controller
{
    private BookingRepositoryInterface $repository;

    public function __construct(BookingRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index($id){
        $data = MeetingRoom::where('id',$id)->first();
        $book = Booking::where('room_id',$id)->where('status','!=',3)->get();
        $reason = Reason::get();
        // dd($book);
        return view('user.start_booking',compact('data','reason','book'));
    }

    //store booking data
    public  function store(Request $request)
    {
        // dd($request->all());
        $user_id = getAuth()->id;
        $this->cus_validate($request,'store');

        if($request->booking_id){
            $book               = Booking::find($request->booking_id);
            $book->id           = $request->booking_id;
            $book->date         = $request->date;
            $book->start_time   = $request->start_time;
            $book->end_time     = $request->end_time;
            $book->duration     = $request->duration;
            $book->title        = $request->title;
            $book->reason_id    = $request->reason_id;
            $book->remark       = $request->remark;
            $book->save();
            $msg  = 'update';
        }else{
            $book               = new Booking();
            $book->room_id      = $request->room_id;
            $book->date         = $request->date;
            $book->start_time   = $request->start_time;
            $book->end_time     = $request->end_time;
            $book->duration     = $request->duration;
            $book->title        = $request->title;
            $book->reason_id    = $request->reason_id;
            $book->user_id      = $user_id;
            $book->remark       = $request->remark;
            $book->save();
            $msg = 'create';
        }

        return back()->with($msg,'Booking Success');
    }


    // go to my booking page
    public function my_booking()
    {
        date_default_timezone_set('Asia/Yangon');
        $date = Carbon::now()->format('Y-m-d');
        $now = Carbon::now()->format('H:i:s');


        $booking = Booking::where(function($q) use($date,$now){
                                    $q->where('date', '>', $date)
                                                    ->orWhere(function ($q) use ($date,$now) {
                                                        $q->where('date', $date)
                                                            ->where('end_time', '>', $now);
                                                    });
                                })
                            ->where('user_id',getAuth()->id)
                            ->whereIn('status',[0,1])
                            ->orderBy('date','asc')
                            ->orderBy('start_time','asc')
                            ->get();
        // dd($booking);
        return view('user.my_booking',compact('booking'));
    }

    // go to today's booking
    public function today_booking()
    {
        date_default_timezone_set('Asia/Yangon');
        $date = Carbon::now()->format('Y-m-d');
        $now = Carbon::now()->format('H:i:s');

        $booking = Booking::where(function($q) use ($date, $now) {
        $q->where('date', '>', $date)
            ->orWhere(function ($q) use ($date, $now) {
                $q->where('date', $date)
                    ->where('start_time', '>', $now);
            });
    })
    ->where('status', '!=', 3)
    ->orderBy('date', 'asc')
    ->orderBy('start_time', 'asc')
    ->get();


        return view('user.today_booking',compact('booking'));
    }

    //go to request Noti page
    public function request_page($id)
    {
        $data = BookingRequest::where('id',$id)->first();
        return view('user.request_noti',compact('data'));
    }

    //go to booking history page
    public function booking_history()
    {
        // if(request('room')){
        //     dd(request('room'));
        // }
        $bookings = Booking::where('user_id',getAUth()->id)
                        ->when(request('room'),function($q){
                            $q->where('room_id',request('room'));
                        })
                        ->when(request('status'),function($q){
                            $q->when(request('status')==6,function($q){
                                $q->where('status',0);
                            })
                            ->when(request('status') != 6, function($q){
                                $q->where('status',request('status'));
                            });
                        })
                        ->when(request('from_date'),function($q){
                            $q->where('date','>=',request('from_date'));
                        })
                        ->when(request('to_date'),function($q){
                            $q->where('date','<=',request('to_date'));
                        })
                        ->orderBy('date','desc')
                        ->orderBy('start_time','desc')
                        ->paginate(15);
        $rooms    = MeetingRoom::orderBy('id')->get();
        return view('user.booking_history',compact('bookings','rooms'));
    }

    //search time
    public function time_search(Request $request)
    {
        // dd($request->all());
        $date = $request->date;
        $data = $this->repository->check_avaliable($date,$request->room_id);

        return response()->json([
            'time'  => $data[0],
            'format_time' => $data[1]
        ],200);
    }

    //check resize time
    public function check_resize(Request $request)
    {
        $date = explode(' ',$request->start)[0];
        // logger($date);
        $data = $this->repository->check_avaliable($date,$request->room_id,$request->id);
        $start_time = explode(' ',$request->start)[1];
        $end_time = explode(' ',$request->end)[1];
        // $st_time1 = explode(':',$start_time);
        // $en_time1 = explode(':',$end_time);
        // $start_time = $st_time1[0].':'.$st_time1[1];
        // $end_time = $en_time1[0].':'.$en_time1[1];
        $dublicate = $this->repository->min_gap($data[0],$start_time,$end_time);
        $end = strtotime($end_time);
        $start = strtotime($start_time);
        $diff = $end - $start ;
        $hours = floor($diff / 3600);
        $min = floor(($diff % 3600) / 60);
        $sec = $diff % 60;
        $duration = sprintf("%02d:%02d:%02d", $hours, $min, $sec);
        if($dublicate == true){
            // dd('yes');
            return response()->json(['error' => 'booking မအားပါ'], 404);
        }else{
            Booking::where('id',$request->id)->update([
                'date'          => $date,
                'start_time'    => $start_time,
                'end_time'      => $end_time,
                'duration'      => $duration
            ]);
            return response()->json(200);
        }
    }

    //drop validation fullcalendar
    public function drop_check(Request $request)
    {
        $date = explode(' ',$request->start)[0];
        $data = $this->repository->check_avaliable($date,$request->room_id,$request->id);
        $start_time = explode(' ',$request->start)[1];
        $end_time = explode(' ',$request->end)[1];
        $end = strtotime($end_time);
        $start = strtotime($start_time);
        $diff = $end - $start ;
        $hours = floor($diff / 3600);
        $min = floor(($diff % 3600) / 60);
        $sec = $diff % 60;
        $duration = sprintf("%02d:%02d:%02d", $hours, $min, $sec);
        $dublicate = $this->repository->min_gap($data[0],$start_time,$end_time);
        if($dublicate == true){
            return response()->json(['error' => 'booking တခုနဲ့ တခုကြား နာရီ၀က်ခြားရပါမည်'], 404);
        }else{
            Booking::where('id',$request->id)->update([
                'date'          => $date,
                'start_time'    => $start_time,
                'end_time'      => $end_time,
                'duration'      => $duration
            ]);
            return response()->json(200);
        }

    }

    //edit to click
    public function event_click($id)
    {
        try{
            $data = Booking::where('id',$id)->first();
            $time = $this->repository->check_avaliable($data->date,$data->room_id,$data->id);
            return response()->json([
                'data' => $data,
                'time' => $time[0],
                'format_time'=> $time[1]
            ],200);
        }catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    //cancel booking
    public function booking_cancel(Request $request)
    {
        // dd($request->all());
        $id = $request->id;
        Booking::where('id',$id)->update([
            'status'    => 3
        ]);
        Booking::where('id',$id)->delete();
        return response()->json(200);
    }

    //my booking filter
    public function my_booking_filter($id)
    {
        date_default_timezone_set('Asia/Yangon');
        $date = Carbon::now()->format('Y-m-d');
        $now = Carbon::now()->format('H:i:s');
        $booking = Booking::where(function($q) use($date,$now){
                            $q->where('date', '>', $date)
                                            ->orWhere(function ($q) use ($date,$now) {
                                                $q->where('date', $date)
                                                    ->where('end_time', '>', $now);
                                            });
                        })
                    ->where('user_id',getAuth()->id)
                    ->where('status','!=',3)
                    ->orderBy('date','asc')
                    ->orderBy('start_time','asc');
        switch ($id) {
            case 0:
                break;
            case 1:
                $booking->where('room_id', 1);
                break;
            case 2:
                $booking->where('room_id', 2);
                break;
            case 3:
                $booking->where('room_id', 3);
                break;
            default:
                break;
        }


        $booking = $booking->get();
        if(count($booking) > 0){
            return view('user.my_booking_filter',compact('booking'))->render();
        }else{
            return response()->json(['status'=>'fail']);
        }
    }

    //today booking filter
    public function today_booking_filter($id){
        date_default_timezone_set('Asia/Yangon');
        $date = Carbon::now()->format('Y-m-d');
        $now = Carbon::now()->format('H:i:s');
        $startDate = Carbon::now();
        $endDate = Carbon::now()->addDays(7);

        $booking = Booking::where(function($q) use ($date, $now) {
            $q->where('date', '>', $date)
                ->orWhere(function ($q) use ($date, $now) {
                    $q->where('date', $date)
                        ->where('start_time', '>', $now);
                });
        });

        if ($id) {
            $booking->where('room_id', $id);
        }

        $booking = $booking
            ->where('status', '!=', 3)
            ->orderBy('date', 'asc')
            ->orderBy('start_time', 'asc')
            ->get();

        if(count($booking) > 0){
            return view('user.today_booking_filter',compact('booking'))->render();
        }else{
            return response()->json(['status'=>'fail']);
        }
    }

    // check room status
    public function room_status($id)
    {
        date_default_timezone_set('Asia/Yangon');
        $now_date = Carbon::now()->format('Y-m-d');
        $now_time = Carbon::now()->addMinutes(5)->format('H:i:s');

        $booking = Booking::where('room_id', $id)
                ->where(function ($q) use ($now_date, $now_time) {
                    $q->where('date', $now_date)
                        ->where('start_time', '<=', $now_time);
                })
                ->where(function ($q) use ($now_date, $now_time) {
                    $q->where('date', $now_date)
                        ->where('end_time', '>=', $now_time);
                })
                ->where(function($q){
                    $q->where('status',0)
                    ->orwhere('status',1);
                })
                ->first();

        $data = MeetingRoom::where('id',$id)->first();
        $status1 = $data->status;
        $boss = $data->boss;
        if($boss == 1){
            $status = 'Boss In';
            return response()->json(['status'=>$status],200);
        }else{
            if($booking){
                $user = $booking->user->name;
                if($booking->status == 0){
                    $status = 'Not Avaliable';
                    MeetingRoom::where('id',$id)->update([
                        'status'    => 0
                    ]);
                }else if($booking->status == 1){
                    // dd('yes');
                    $status = 'Occupied';
                    if($status1 == 0){
                        MeetingRoom::where('id',$id)->update([
                            'status'    => 1
                        ]);
                    }
                }
                return response()->json([
                    'status' =>$status,
                    'user'  => $user
                ],200);
            }else{
                $status = 'Avaliable';
                if($status1 == 1){
                    MeetingRoom::where('id',$id)->update([
                        'status'    => 0
                    ]);
                }
                return response()->json(['status'=>$status],200);
            }

        }
    }

    //booking start
    public function booking_start($id)
    {
        $booking = Booking::where('id',$id)->first();
        Booking::where('id',$id)->update(['status'=>1]);
        MeetingRoom::where('id',$booking->room_id)->update(['status' =>1]);
        return response()->json(200);
    }

    //request booking accept
    public function req_accept(Request $request)
    {
        // dd($request->all());
        $req_booking = BookingRequest::where('id',$request->req_id)->first();
        $booking     = Booking::where('id',$req_booking->booking_id)->first();
        $user_name  = $booking->user->name;
        $user = User::where('id',$req_booking->request_user)->first();
        if($req_booking->total_duration == $booking->duration){
            // Booking::where('id',$req_booking->booking_id)->update([
            //     'title'     => 'Accepted by '.$user_name,
            //     'remark'    => $req_booking->reason,
            //     'user_id'   => $req_booking->request_user
            // ]);
            $booking = Booking::find($req_booking->booking_id);
            $booking->title = 'Accepted by '.$user_name;
            $booking->remark = $req_booking->reason;
            $booking->user_id= $req_booking->request_user;
            $booking->save();
        }else{
            $req_duration = $req_booking->total_duration;
            $duration     = $booking->duration;
            list($hour,$min,$sec) = explode(':',$req_duration);
            list($hour1,$min1,$sec1) = explode(':',$duration);
            $total_sec = $hour*3600 + $min*60 + $sec;
            $total_sec1 = $hour1*3600 + $min1*60 + $sec1;
            $final_duration = $total_sec1-$total_sec;
            $hours2 = floor($final_duration / 3600);
            $min2 = floor(($final_duration % 3600) / 60);
            $sec2 = $final_duration % 60;
            $final_duration = sprintf("%02d:%02d:%02d", $hours2, $min2, $sec2);

            if($req_booking->from == 'start'){
                $start = strtotime($booking->start_time);
                $end = floor($start+$total_sec);
                $end_time = date('H:i:s',$end);
                Booking::where('id',$req_booking->booking_id)->update([
                    'start_time'    => $end_time,
                    'duration'      =>$final_duration,
                    'original_start'=>$booking->start_time,
                    'original_end'=>$booking->end_time,
                ]);
                Booking::create([
                    'room_id'   => $booking->room_id,
                    'date'      => $booking->date,
                    'start_time'=> $booking->start_time,
                    'end_time'  => $end_time,
                    'duration'  => $req_booking->total_duration,
                    'title'     => 'Accepted by '.$user_name,
                    'reason_id' => $booking->reason_id,
                    'user_id'   => $req_booking->request_user,
                    'remark'    => $req_booking->reason,
                    'status'    => 0
                ]);
            }else{
                $end = strtotime($booking->end_time);
                $start = floor($end-$total_sec);
                $start_time = date('H:i:s',$start);
                Booking::where('id',$req_booking->booking_id)->update([
                    'end_time' => $start_time,
                    'duration'   => $final_duration,
                    'original_start'=>$booking->start_time,
                    'original_end'=>$booking->end_time,
                ]);
                Booking::create([
                    'room_id'   => $booking->room_id,
                    'date'      => $booking->date,
                    'start_time'=> $start_time,
                    'end_time'  => $booking->end_time,
                    'duration'  => $req_booking->total_duration,
                    'title'     => 'Accepted by '.$user_name,
                    'reason_id' => $booking->reason_id,
                    'user_id'   => $req_booking->request_user,
                    'remark'    => $req_booking->reason,
                    'status'    => 0
                ]);
            }
        }
        if($req_booking->resend_status){
            $og_user = User::where('id',$req_booking->booking->user_id)->first();
            sendNoti($og_user,$booking->id,$req_booking->id,getAuth()->id);
        }
        BookingRequest::where('id',$request->req_id)->update([
            'request_status' => 1,
            'approve_user'  => getAuth()->id
        ]);
        $all_req = BookingRequest::where('booking_id',$booking->id)
                                ->where('request_status',0)
                                ->get();
        if($all_req){
            foreach($all_req as $item){
                BookingRequest::where('id',$item->id)->update([
                    'request_status'    => 3,
                    'approve_user'      => getAuth()->id
                ]);
                $rej_user = User::where('id',$item->request_user)->first();

                sendNoti($rej_user,$item->booking_id,$item->id,getAuth()->id);
            }
        }
        $noti = getAuth()->unreadNotifications;
        foreach($noti as $item){
            if($item->data['request_id'] == $req_booking->id){
                $item->markAsRead();
            }
        }
        sendNoti($user,$booking->id,$req_booking->id,getAuth()->id);
        return back()->with('success','Request Accept Success');
    }

    //extend booking
    public function extend(Request $request)
    {
        $book = Booking::find($request->id);
        list($hour,$min,$sec)       = explode(':',$request->extend_time);
        list($hour1,$min1,$sec1)    = explode(':',$book->duration);
        $new_total_sec              = $hour*3600 + $min * 60 + $sec;
        $total_sec              = $hour1*3600 + $min1 * 60 + $sec1;
        $final_sec              = $total_sec + $new_total_sec;
        $hours2 = floor($final_sec / 3600);
        $min2 = floor(($final_sec % 3600) / 60);
        $sec2 = $final_sec % 60;
        $final_duration = sprintf("%02d:%02d:%02d", $hours2, $min2, $sec2);
        $end_time = $book->end_time;
        $final_end_time = date('H:i:s',strtotime($end_time) + $new_total_sec);
        $book->update([
            'end_time'              => $final_end_time,
            'duration'          => $final_duration,
            'extend_status'     => 1,
            'extended_duration' => $request->extend_time,
            'extended_time'     => Carbon::now()
        ]);
        return back()->with('success','Extend Time Success');
    }

    //request booking reject
    public function req_reject(Request $request)
    {
        $id = $request->req_id;
        $user_id = $request->req_user_id;
        $data = BookingRequest::where('id',$id)->first();
        BookingRequest::where('id',$id)->update([
            'request_status'    => 3,
            'approve_user'      => getAuth()->id
        ]);
        $user = User::where('id',$user_id)->first();
        sendNoti($user,$data->booking_id,$id,getAuth()->id);
        $unread_noti = getAuth()->unreadNotifications;
        foreach($unread_noti as $item){
            if($item->data['request_id'] == $id && $item->data['req_user_id'] == $user_id){
                $item->markAsRead();
            }
        }
        return back()->with('success','Request Reject Success');
    }

    //change status
    public function change_status($id)
    {
        $booking = Booking::where('id',$id)->first();
        $status = $booking->status;
        if($status == 0){
            Booking::where('id',$id)->update([
                'status' => 4
            ]);
        }else if($status == 1){
            Booking::where('id',$id)->update([
                'status' => 2
            ]);
        }
        MeetingRoom::where('id',$booking->room_id)->update([
            'status'    => 0
        ]);
        return response()->json(200);
    }

    //booking change status
    public function booking_status(Request $request)
    {
        date_default_timezone_set('Asia/Yangon');
        $now_date = date('Y-m-d');
        $now_time = date('H:i:s');

        $data = Booking::where('date','<',$now_date)
                        ->orwhere(function($q) use($now_date,$now_time){
                            $q->where('date',$now_date)
                            ->where('end_time','<=',$now_time);
                        })
                        ->get();

        foreach($data as $item)
        {
            if($item->status == 0){
                Booking::where('id',$item->id)->update([
                    'status' => 4
                ]);
            }else if($item->status == 1){
                Booking::where('id',$item->id)->update([
                    'status'    => 2
                ]);
            }
        }

        return response()->json(200);
    }

    //request booking
    public function request_booking(Request $request)
    {
        // dd($request->all());
        $this->cus_validate($request,'request');
        $tem = [
            'booking_id'    => $request->booking_id,
            'request_reason'=> $request->reason,
            'request_status'=> 0,
            'request_user'  => getAuth()->id,
            'from'          => $request->from,
            'total_duration'=> $request->total_duration
        ];
        $booking = Booking::where('id',$request->booking_id)->first();
        $user   = User::where('id',$booking->user_id)->first();
        $req = BookingRequest::create($tem);
        sendNoti($user,$booking->id,$req->id,getAuth()->id);
        return back()->with('success','Booking Request Success');
    }

    //end booking
    public function end_booking(Request $request)
    {
        date_default_timezone_set('Asia/Yangon');
        $id = $request->data;
        $booking = Booking::where('id',$id)->first();
        Booking::where('id',$id)->update([
            'status'  => 5,
            'finished_time' => Carbon::now()
        ]);
        MeetingRoom::where('id',$booking->room_id)->update([
            'status'    => 0
        ]);
        return response(200);
    }

    //check booking exist
    public function booking_req($id)
    {
        $data = BookingRequest::where(['booking_id'=>$id,'request_user'=>getAuth()->id])->first();
        if($data){
            return response()->json([
                'status' => 'exist',
                'data'  => $data,
            ],200);
        }else{
            return response()->json([
                'status' => 'not_exist',
            ],200);
        }
    }

    //unread noti
    // public function unread_noti()
    // {
    //     $notis = getAuth()->unreadNotifications;
    //     $count = $notis ? count($notis) : 0;
    //     return response()->json($count,200);
    // }

    //read due booking
    public function read_due()
    {
        date_default_timezone_set('Asia/Yangon');
        $now = strtotime(Carbon::now());
        $user_id = User::whereNot('id',1)->pluck('id');
        $notifications = DB::table('notifications')
            ->whereIn('notifiable_id', $user_id )
            ->where('notifiable_type', 'App\\Models\\User')
            ->whereNull('read_at')
            ->get();

        foreach ($notifications as $item) {
            $data = json_decode($item->data, true);
            $bookingId = $data['booking_id'];

            $booking = Booking::where('id', $bookingId)->first();
            $start = strtotime($booking->date.' '.$booking->start_time);
            if ($now > $start) {
                DB::table('notifications')
                    ->where('id', $item->id)
                    ->update(['read_at' => Carbon::now()]);

                $requestId = $data['request_id'];
                $book = BookingRequest::find($requestId);
                $user = User::where('id',$data['req_user_id'])->first();
                if ($book) {
                    if($book->request_status === 0){
                        $book->update(['request_status' => 2]);
                        sendNoti($user,$data['booking_id'],$data['request_id'],getAuth()->id);
                    }elseif($book->request_status == 1 || $book->request_status == 3){
                        $item->markAsRead();
                    }
                }

                $user = User::where('id',$data['req_user_id'])->first();
            }
            // 0    => pending
            // 1    => accept
            // 2    => system reject
            // 3    => user reject
            // 4    => user send
            // 5    => resended request
        }

        $count = getAuth()->unreadNotifications->count();
        return response()->json($count,200);
    }

    //check booking
    public function check_booking(){

        $now_date = Carbon::now()->format('Y-m-d');
        $now_time = Carbon::now()->format('H:i:s');
        $data = Booking::with('room')
                        ->where('date',$now_date)
                        ->where('start_time','>',$now_time)
                        ->where('user_id',getAuth()->id)
                        ->whereNull('noti')
                        ->orderBy('start_time','asc')
                        ->first();

        if($data)
        {
            $start = strtotime($data->start_time) - 60*10;
            $now   = strtotime(Carbon::now());
            if($now > $start){
                $diff = strtotime($data->start_time) -$now;
                $min  = round($diff / 60);
                return response()->json([
                    'status' => 'start',
                    'data'   => $data,
                    'min'    => $min
                ],200);
            }
        }else{
            return response()->json(200);
        }
    }

    //noti pass
    public function noti_pass($id)
    {
        Booking::where('id',$id)->update([
            'noti' => 1
        ]);
        return response(200);
    }

    //read noti
    public  function read_noti($id)
    {
        $data  = BookingRequest::where('id',$id)->first();
        if(!$data->read){
            BookingRequest::where('id',$id)->update([
                'read' => 1
            ]);
        }
        $noti = getAuth()->unreadNotifications;
        if($data->request_status == 1 || $data->request_status == 3)
        {
            foreach($noti as $item)
            {
                // dd($data->request_user);
                if($item->data['request_id'] == $id){
                    $item->markAsRead();
                }
            }
        }
        return response(200);
    }

    //get avaliable extend time
    public function extend_time($id)
    {
        $time = check_extendable($id);
        return response()->json($time,200);
    }

    //resend notification
    public function resend_noti(Request $request)
    {
        // dd($request->all());
        $req_booking = BookingRequest::find($request->id);
        BookingRequest::where('id',$request->id)->update([
            'request_status'    => 4,
            'resend_status'    => 1,
            'approve_user'      => getAuth()->id
        ]);
        $new_req=BookingRequest::create([
            'booking_id'    => $req_booking->booking_id,
            'request_reason'=> $req_booking->request_reason,
            'request_status'=> 5,
            'resend_status'=> 1,
            'request_user'  => $req_booking->request_user,
            'from'          => $req_booking->from,
            'total_duration'=> $request->duration
        ]);
        $user = User::find($req_booking->request_user);
        $noti = getAuth()->unreadNotifications;
        foreach($noti as $item){
            if($item->data['request_id'] == $request->id && $item->data['req_user_id'] == $req_booking->request_user){
                $item->markAsRead();
            }
        }
        sendNoti($user,$req_booking->booking_id,$new_req->id,$user->id);
        return response(200);
    }

    //validation
    private function cus_validate($data,$action)
    {
        if($action == 'store')
        {
            $validate = $data->validate([
                'title'         => 'required',
                'date'          => 'required',
                'start_time'    => 'required',
                'end_time'      => 'required',
                'reason_id'     => 'required'
            ]);
        }else if($action == 'request')
        {
            $validate = $data->validate([
                'total_duration'    => 'required',
                'from'              => 'required',
                'reason'            => 'required'
            ]);
        }

        return $validate;
    }
}
