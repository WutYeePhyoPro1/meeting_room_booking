<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Branch;
use App\Models\Booking;
use App\Rules\ColorRule;
use App\Models\RoomImage;
use App\Models\Department;
use App\Models\MeetingRoom;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Rules\RoomNameDublicate;
use App\Customize\Commonfunction;
use App\Models\BookingRequest;
use App\Models\Reason;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\CssSelector\Node\FunctionNode;

class adminController extends Controller
{
    public function dashboard(){
        if(getAuth()->employee_id == '000-000000'){
            return view('admin.home');
        }else{
            $room = MeetingRoom::orderBy('created_at','asc')->get();
            $current_month = Carbon::now()->format('m');
            $current_year = Carbon::now()->format('Y');
            $bookings = Booking::whereMonth('date',$current_month)->whereYear('date',$current_year)->orderBy('date','desc')->withTrashed()->paginate(10);
            return view('user.home',compact('room','bookings'));
        }
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
        $data = Booking::orderBy('date','desc')
                        ->orderBy('start_time','desc')
                        ->withTrashed()
                        ->paginate(15);
        return view('admin.booking.index',compact('data'));
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
        $data = Booking::where("id",$id)->first();
        $req_book = BookingRequest::where('booking_id',$id)->whereNot('request_status',0)->get();
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
        }else{
            MeetingRoom::where('id',$id)->update([
                'boss' => 0
            ]);
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
