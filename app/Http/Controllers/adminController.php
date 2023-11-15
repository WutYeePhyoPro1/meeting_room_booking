<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Branch;
use App\Models\Reason;
use App\Models\Booking;
use App\Rules\ColorRule;
use App\Models\RoomImage;
use App\Models\Department;
use App\Models\MeetingRoom;
use Illuminate\Http\Request;
use App\Models\BookingRequest;
use Illuminate\Validation\Rule;
use App\Rules\RoomNameDublicate;
use App\Customize\Commonfunction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\CssSelector\Node\FunctionNode;

class adminController extends Controller
{
    public function home(){
        if(getAuth()->employee_id == '000-000000'){
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


            $user_data = Booking::with('user')->select(DB::raw('count(user_id) as count'),'user_id')
                                    ->when(request('month'),function($q) use($year,$month){
                                        $q->whereMonth('date',$month)
                                        ->whereYear('date',$year);
                                    })
                                    ->when(request('from_date') && !request('month'),function($q){
                                        $q->where('date','>=',request('from_date'));
                                    })
                                    ->when(request('to_date') && !request('month'),function($q){
                                        $q->where('date','<=',request('to_date'));
                                    })
                                    ->groupBy('user_id')
                                    ->orderBy('user_id')
                                    ->withTrashed()
                                    ->get();
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
                                ->withTrashed()
                                ->get();
            $all_user = User::whereNot('employee_id','000-000000')->orderBy('id')->get();
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

            return view('admin.home',compact('this_year_data','last_year_data','final_data','user_data','user','color','all_data'));
        }else{
            $room = MeetingRoom::orderBy('created_at','asc')->get();
            // $week_date = Carbon::now()->subDay(7);
            $bookings = Booking::whereDate('date','>=',Carbon::now())
                                ->orderBy('date','desc')
                                ->orderBy('start_time','desc')
                                ->withTrashed()
                                ->paginate(10);
            return view('user.home',compact('room','bookings'));
        }
    }

    //go to user home page
    public function dashboard(){

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

        $user_data = Booking::with('user')->select(DB::raw('count(user_id) as count'),'user_id')
                                ->when(request('month'),function($q) use($year,$month){
                                    $q->whereMonth('date',$month)
                                    ->whereYear('date',$year);
                                })
                                ->when(request('from_date') && !request('month'),function($q){
                                    $q->where('date','>=',request('from_date'));
                                })
                                ->when(request('to_date') && !request('month'),function($q){
                                    $q->where('date','<=',request('to_date'));
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
        $all_user = User::whereNot('employee_id','000-000000')->orderBy('id')->get();
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

        return view('admin.home',compact('this_year_data','last_year_data','final_data','user_data','user','color','all_data'));
    }

    public function user()
    {
        $data = User::paginate(20);
        return view('admin.user.user',compact('data'));
    }

    //to create page
    public function create()
    {
        $department = Department::get();
        return view('admin.user.create-edit',compact('department'));
    }

    //to room page
    public function room()
    {
        $data = MeetingRoom::orderBy('created_at','asc')->paginate(30);
        return view('admin.room.room',compact('data'));
    }

    //to booking page
    public function booking()
    {
        $data = Booking::when(request('room'),function($q){
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
                        ->withTrashed()
                        ->paginate(15);
        $rooms = MeetingRoom::get();
        return view('admin.booking.index',compact('data','rooms'));
    }

    //store user
    public function store_user(Request $request)
    {
        // dd($request->all());
        if($request->id){
            $this->cus_validate($request,'update');
        }else{
            $this->cus_validate($request,'create');
        }
        try{
            if($request->id){
                $data = [];
                $id = $request->id;
                $data['name']   = $request->name;
                $data['employee_id']   = $request->employee_id;
                $data['password_str']   = $request->password;
                $data['password']   = Hash::make($request->password);
                $data['department_id']   = $request->department_id;
                $data['bg_color']        = $request->bg_color;
                $data['text_color']        = $request->text_color;

                User::where('id',$id)->update($data);
            }else{
                $user = new User;
                $user->name = $request->name;
                $user->employee_id      = $request->employee_id;
                $user->password_str     = $request->password;
                $user->password         = Hash::make($request->password);
                $user->branch_id        = 1;
                $user->department_id    = $request->department_id;
                $user->bg_color         = $request->bg_color;
                $user->text_color         = $request->text_color;
                $user->save();
            }

            return redirect()->route('admin#user')->with('success','User Create Success');
        }catch(\Exception $e){
            return back()->with('fails','User Create Fail');
        }

    }

    //go to edit page
    public function edit($id)
    {
        $data = User::where('id',$id)->first();
        $department = Department::get();
        return view('admin.user.create-edit',compact('data','department'));
    }

    //delete user
    public function delete($id)
    {
        User::where('id',$id)->delete();
        return back()->with('success','User Delete Success');
    }

    //to room create
    public function room_create()
    {
        $branches = Branch::get();
        return view('admin.room.create-edit',compact('branches'));
    }

    //store and edit room
    public function store_room(Request $request)
    {
        // dd($request->all());

        if($request->id){
            $this->cus_validate(request(),'room_update');
        }else{
            $this->cus_validate(request(),'room_create');
        }

        // try{
            if($request->id){

                $id = $request->id;
                $data = [];
                $data['room_name'] = $request->name;
                $data['branch_id'] = $request->branch_id;
                $data['seat']      = $request->seat;
                if($request->file('room_image')){
                    $item = RoomImage::where('room_id',$id)->first();
                    $dbImage = $item->file_name;
                    if(Storage::exists('uploads/room_image/'.$dbImage)){
                        Storage::delete('uploads/room_image/'.$dbImage);
                    }
                    $file = $request->file('room_image');
                    $ori_name = $file->getClientOriginalName();
                    $file_name= uniqid().'_'.$ori_name;
                    $img = [
                        'file_name' => $file_name,
                        'name'      => $ori_name
                    ];
                    $file->storeAs('uploads/room_image',$file_name);
                    RoomImage::where('id',$item->id)->update($img);
                }
                MeetingRoom::where('id',$id)->update($data);
                $msg = 'Room Update Success';
            }else{
                $room = new MeetingRoom();
                $room->room_name = $request->name;
                $room->branch_id = $request->branch_id;
                $room->seat = $request->seat;
                $room->save();


                if($request->file('room_image')){
                    $data = MeetingRoom::where(['room_name'=> $request->name,'branch_id'=>$request->branch_id])->first();
                    $id   = $data->id;
                    $file       = $request->file('room_image');
                    $name       = $file->getClientOriginalName();
                    $file_name  = uniqid().'_'.$name;

                    $tem = [
                        'room_id'   => $id,
                        'file_name' => $file_name,
                        'name'      => $name
                    ];

                    $file->storeAs('uploads/room_image',$file_name);
                    RoomImage::create($tem);
                }
                $msg = 'Room Create Success';
            }
            return redirect()->route('admin#room')->with('success',$msg);
        // }catch(\Exception $e){
        //     return back()->with('fails','User Create Fail');
        // }

    }

    //edit room
    public function room_edit($id)
    {
        $data = MeetingRoom::where('id',$id)->first();
        $img  = RoomImage::where('room_id',$id)->first();
        Commonfunction::getBranch();
        return view('admin.room.create-edit',compact('data','img'));
    }

    //delete room
    public function room_delete($id)
    {
        // dd($id);
        $file = RoomImage::where('room_id',$id)->first();
        if($file){
            $dbImage = $file->file_name;
            if (Storage::exists('uploads/room_image/' . $dbImage)) {
                Storage::delete('uploads/room_image/' . $dbImage);
            }

            RoomImage::where('id',$file->id)->delete();
        }else{
        }

        MeetingRoom::where('id',$id)->delete();
        return back()->with('success','Room Delete Success');
    }

    // edit booking
    public function edit_booking($id)
    {
        $data = Booking::where('id',$id)->first();
        $reason = Reason::get();
        return view('admin.booking.edit',compact('data','reason'));
    }

    //detail booking
    public function detail_booking($id)
    {
        $data = Booking::where("id",$id)->withTrashed()->first();
        $req_book = BookingRequest::where('booking_id',$id)->orderBy('request_status')->get();
        return view('admin.booking.detail',compact('data','req_book'));
    }

    //boss in
    public function boss_in($id)
    {
        $data = MeetingRoom::where('id',$id)->first();
        if($data->boss == 0){
            MeetingRoom::where('id',$id)->update([
                'boss' => 1
            ]);
            return response()->json(['status' => 1],200);
        }else{
            MeetingRoom::where('id',$id)->update([
                'boss' => 0
            ]);
            return response()->json(['status' => 0],200);
        }
    }

    public function guest_in($id)
    {
        $data = MeetingRoom::where('id',$id)->first();
        if($data->guest == 0){
            MeetingRoom::where('id',$id)->update([
                'guest' => 1
            ]);
            return response()->json(['status' => 1],200);

        }else{
            MeetingRoom::where('id',$id)->update([
                'guest' => 0
            ]);
            return response()->json(['status' => 0],200);

        }
    }

    //validate
    public function cus_validate($data,$action)
    {
        if($action == 'create')
        {
            $validate = $data->validate([
                'name'          => 'required',
                'password'      => 'required',
                'department_id'   => 'required',
                'employee_id' => 'required|unique:users,employee_id',
                'bg_color'      => ['required',new ColorRule()],
                'text_color'    => 'required'
            ]);
        }else if($action == 'update')
        {
            // $id = $data->id;
            // dd($data->id);
            $validate = $data->validate([
                'name'   => ['required',Rule::unique('users')->ignore($data->id)],
                'password'      => 'required',
                'department_id'   => 'required',
                'employee_id' => ['required',Rule::unique('users')->ignore($data->id)],
                'bg_color'      => ['required',new ColorRule()],
                'text_color'    => 'required'
            ]);
        }else if($action == 'room_create')
        {
            $validate = $data->validate([
                'name'      => ['required',new RoomNameDublicate($data->branch_id)],
                'branch_id' => 'required',
                'seat'      => 'required|min:1',
                'room_image'=> 'mimetypes:image/*'
            ]);
        }else if($action == 'room_update')
        {
            $id = $data->id;
            $validate = $data->validate([
                'name'      => ['required',new RoomNameDublicate($data->branch_id,$id)],
                'branch_id' => 'required',
                'seat'      => 'required|min:1',
                'room_image'=> 'mimetypes:image/*'
            ]);
        }

        return $validate;
    }
}
