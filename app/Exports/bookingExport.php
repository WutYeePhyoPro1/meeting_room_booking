<?php

namespace App\Exports;

use App\Models\Booking;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class bookingExport implements FromView,WithColumnWidths
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $filter;

    public function __construct($filter)
    {
        $this->filter = $filter;
    }

    public function view(): View
    {
        $bookings = Booking::where('user_id',getAUth()->id)
                        ->orderBy('date','desc')
                        ->orderBy('start_time','desc')
                        ->withTrashed();

        if(isset($this->filter['room'])){
                    $bookings = $bookings->where('room_id',$this->filter['room']);
        }
        if(isset($this->filter['status'])){
            if($this->filter['status'] == 6){
                $bookings = $bookings->where('status',0);
            }else{
                $bookings = $bookings->where('status',$this->filter['status']);
            }
        }
        if(isset($this->filter['from_date'])){
            $bookings = $bookings->where('date','>=',$this->filter['from_date']);
        }
        if(isset($this->filter['tp_date'])){
            $bookings = $bookings->where('date','<=',$this->filter['to_date']);
        }

        $bookings = $bookings->get();
        $filters  = $this->filter;

        return view('user.booking_excel',compact('bookings','filters'));
    }
    // public function collection()
    // {
    //     $bookings = Booking::select('meeting_rooms.room_name','bookings.date','bookings.title','bookings.remark','bookings.start_time','bookings.end_time','bookings.duration','user.name')
    //                         ->leftJoin('meeting_rooms','meeting_rooms.id','bookings.room_id')
    //                         ->leftJoin('users','users.id','bookings.user_id')
    //                         ->where('user_id',getAUth()->id)
    //                         ->orderBy('date','desc')
    //                         ->orderBy('start_time','desc')
    //                         ->withTrashed();

    //     if($this->filter['room']){
    //         $bookings = $bookings->where('room_id',$this->filter['room']);
    //     }
    //     if($this->filter['status']){
    //         if($this->filter['status'] == 6){
    //             $bookings = $bookings->where('status',0);
    //         }else{
    //             $bookings = $bookings->where('status',$this->filter['status']);
    //         }
    //     }
    //     if($this->filter['from_date']){
    //         $bookings = $bookings->where('date','>=',$this->filter['from_date']);
    //     }
    //     if($this->filter['tp_date']){
    //         $bookings = $bookings->where('date','<=',$this->filter['to_date']);
    //     }

    //     $bookings = $bookings->get();
    //     return $bookings;
    // }

    // public function headings(): array
    // {
    //     return [

    //     ];
    // }
    public function columnWidths(): array
    {
        return [
            'B' => 27,
            'C' => 27,
            'D' => 13,
            'E' => 25,
            'F' => 27,
            'K' => 35,
            'L' => 20,
        ];
    }
}
