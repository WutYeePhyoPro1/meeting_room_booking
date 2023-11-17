<?php

namespace App\Repositories;

use App\Models\Booking;
use App\Interfaces\BookingRepositoryInterface;

class BookingRepository implements BookingRepositoryInterface
{
    //get avaliable time for a day
    public function check_avaliable($date,$room_id,$id = null)
    {
        $all_time = ['08:30:00','09:00:00','09:30:00','10:00:00','10:30:00','11:00:00','11:30:00','12:00:00','12:30:00','13:00:00','13:30:00','14:00:00','14:30:00','15:00:00','15:30:00','16:00:00','16:30:00','17:00:00','17:30:00','18:00:00'];
        date_default_timezone_set('Asia/Yangon');
        $now = date('Y-m-d H:i:s');
        if($id){
            $data = Booking::where('date',$date)->where('status','!=',3)->where('room_id',$room_id)->where('id','!=',$id)->get();
        }else{
            $data = Booking::where('date',$date)->where('status','!=',3)->where('room_id',$room_id)->get();
        }
        if(count($data) > 0 ){
            $tem = [];
            foreach($data as $item){
                    // $tem[]  = range(explode(':',$item->start_time)[0],explode(':',$item->end_time)[0]);
                    $st_time = explode(':',$item->start_time);
                    $en_time = explode(':',$item->end_time);
                    $tem['start_time'][] = $st_time[0].':'.$st_time[1];
                    $tem['end_time'][] = $en_time[0].':'.$en_time[1];
            }
            // dd($tem);
            $times = [];
            for($i = 0 ; $i < count($tem['start_time']) ; $i++)
            {
                $start_time = strtotime($tem['start_time'][$i]);
                $end_time = strtotime($tem['end_time'][$i]);

                $step = 30 * 60;

                while ($start_time <= $end_time) {
                    $times[] = date("H:i:s", $start_time);
                    $start_time += $step;
                }
            }
            $diff_array = array_diff($all_time,$times);

            $time = [];
            $format_time = [];
            foreach($diff_array as $item){
                $now_time = $date.' '.$item;
                $now_time = date('Y-m-d H:i:s',strtotime($now_time));
                if(($now_time > $now)){
                    $format_time[] = date('g:i A',strtotime($item));
                    $time[] = $item;
                }
            }
        }else{
            $time= [];
            $format_time = [];
            foreach($all_time as $item){
                $now_time = $date.' '.$item;
                $now_time = date('Y-m-d H:i:s',strtotime($now_time));
                if($now_time > $now){
                    $format_time[] = date('g:i A',strtotime($item));
                    $time[] = $item;
                }
            }
        }

        $all_time = [$time, $format_time];
        return $all_time;
    }

    //check validate to 30 min gap
    public function min_gap(array $data,$start,$end){
        $start_time1 = strtotime($start);
        $end_time1 = strtotime($end);

        $step = 30 * 60 ;
        $times  = [];
        while ($start_time1 <= $end_time1) {
            $times[] = date("H:i:s", $start_time1);
            $start_time1 += $step;
        }
        $dublicate = false;
        foreach($times as $item)
        {
            if(!in_array($item,$data)){
                $dublicate = true;
                break;
            }
        }

        return $dublicate;
    }
}
