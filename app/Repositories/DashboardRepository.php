<?php

namespace App\Repositories;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Booking;
use Illuminate\Support\Facades\DB;
use App\Interfaces\DashboardRepositoryInterface;

class DashboardRepository implements DashboardRepositoryInterface
{
    public function get_home_data()
    {
        $this_year = Carbon::now()->format('Y');
            $last_year = Carbon::now()->subYear(1)->format('Y');
            $data = Booking::selectRaw('TO_CHAR(date,\'Month\')  as month, count(date) as count')
                            ->whereYear('date',$this_year)
                            ->groupBy(DB::raw('TO_CHAR(date,\'Month\') '))
                            ->orderBy('month')
                            ->get();

            $data1 = Booking::selectRaw('TO_CHAR(date,\'Month\')  as month, count(date) as count')
                            ->whereYear('date',$last_year)
                            ->groupBy(DB::raw('TO_CHAR(date,\'Month\') '))
                            ->orderBy('month')
                            ->get();


                $year = date('Y',strtotime(request('month')));
                $month= date('m',strtotime(request('month')));
            // dd(request('status'));
            $user_data = Booking::with('user')
                        ->select(DB::raw('count(user_id) as count'), 'user_id')
                        ->when(request('month'), function ($q) use ($year, $month) {
                            $q->whereMonth('date', $month)
                                ->whereYear('date', $year);
                        })
                        ->when(request('from_date') && !request('month'), function ($q) {
                            $q->where('date', '>=', request('from_date'));
                        })
                        ->when(request('to_date') && !request('month'), function ($q) {
                            $q->where('date', '<=', request('to_date'));
                        })
                        ->when(request('room'), function ($q) {
                            $q->where('room_id', request('room'));
                        })
                        ->when(request('status') , function($q){
                            $q->when(in_array(6,request('status')),function($q){
                                $q->whereIn('status',request('status'))
                                ->orwhere('status',0);
                            })
                            ->when(!in_array(6,request('status')) , function($q){
                                $q->whereIn('status',request('status'));
                            });
                        })
                        ->groupBy('user_id')
                        ->orderBy('user_id')
                        ->withTrashed()
                        ->get();

                        // dd($user_data);
            $all_data = Booking::when(request('month'),function($q) use($year,$month){
                                    $q->whereMonth('date',$month)
                                    ->whereYear('date',$year);
                                })
                                ->when(request('from_date') && !request('month'),function($q){
                                    $q->where('date','>=',request('from_date'));
                                })
                                ->when(request('to_date') && !request('month'),function($q){
                                    $q->where('date','<=',request('to_date'));
                                })
                                ->when(request('room'),function($q){
                                    $q->where('room_id',request('room'));
                                })
                                ->when(request('status') , function($q){
                                    $q->when(in_array(6,request('status')),function($q){
                                        $q->whereIn('status',request('status'))
                                        ->orwhere('status',0);
                                    })
                                    ->when(!in_array(6,request('status')) , function($q){
                                        $q->whereIn('status',request('status'));
                                    });
                                })
                                ->withTrashed()
                                ->get();
            $all_user = User::whereNotIn('employee_id',['SuperAdmin@mail.com','recho@pro1','000-000024'])->orderBy('id')->get();
            $user = $all_user->pluck('name')->all();
            $color = $all_user->pluck('bg_color')->all();
            $data_user = [];
            $final_data= [];
            foreach($all_user as $item){
                $data_user[$item->name] = null;
            }
            foreach($user_data as $item)
            {
                    $data_user[$item->user->name] = $item->count;
            }
            foreach($data_user as $index=>$item){
                $final_data[]=$item;
            }

            $this_year_data = ['January' => null, 'February' => null, 'March' => null, 'April' => null, 'May' => null, 'June' => null, 'July' => null,'August' => null,'September' => null,'October' => null,'November' => null,'December' => null];
            $last_year_data = $this_year_data;
            foreach($data as $item){
                $this_year_data[trim($item->month,' ')] = $item->count;
            }
            foreach($data1 as $item){
                $last_year_data[trim($item->month,' ')] = $item->count;
            }

            $all = compact(
                'this_year_data',
                'last_year_data',
                'final_data',
                'user_data',
                'user',
                'color',
                'all_data'
            );
            return $all;
    }
}
