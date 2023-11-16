<?php

namespace App\Console\Commands;

use App\Models\Booking;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ChangeStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:change_status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Change Booking Status Every 5min';

    /**
     * Execute the console command.
     */
    public function handle()
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
        // Log::info('change successfully');
    }
}
