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
use Spatie\Browsershot\Browsershot;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpPresentation\IOFactory;
use App\Repositories\DashboardRepository;
use PhpOffice\PhpPresentation\PhpPresentation;
use App\Interfaces\DashboardRepositoryInterface;
use Symfony\Component\CssSelector\Node\FunctionNode;

class adminController extends Controller
{

    private DashboardRepositoryInterface $repository;

    public function __construct(DashboardRepository $repository)
    {
        $this->repository = $repository;
    }

    public function home(){
        if(getAuth()->employee_id == 'SuperAdmin@mail.com'){
            $all = $this->repository->get_home_data();
            return view('admin.home',$all);
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

        $all = $this->repository->get_home_data();

        return view('admin.home',$all);
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
        $data = Booking::where('id',$id)->withTrashed()->first();
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
        if($data->status == 0 ){
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
        }else{
            return response()->json(['msg'=>'error'],500);
        }
    }

    public function guest_in($id)
    {
        $data = MeetingRoom::where('id',$id)->first();
        if($data->status == 0){
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
        }else{
            return response()->json(['msg'=>'error'],500);
        }
    }

    //ppt file for dashboard
    // public function home_ppt()
    // {
    //    // Get data from the repository
    //    $all = $this->repository->get_home_data();

    //    // Render the view with the retrieved data
    //    $view = view('admin.home_ppt', $all);
    //    $htmlContent = $view->render();

    //    // Convert HTML to an image using Browsershot
    //    $imagePath = public_path('presentation_image.png');
    //    Browsershot::html($htmlContent)->save($imagePath);

    //    // Create a presentation object
    //    $presentation = new PhpPresentation();

    //    // Create a slide
    //    $slide = $presentation->createSlide();

    //    // Add the image to the slide
    //    $slide->createDrawingShape()->setName('Presentation Image')->setPath($imagePath)->setHeight(720);

    //    // Save the presentation to a file or send it as a response
    //    $filename = 'presentation.pptx';
    //    $path = public_path($filename);

    //    $writer = IOFactory::createWriter($presentation, 'PowerPoint2007');
    //    $writer->save($path);

    //    // If you want to send the presentation as a response, you can use the following:
    //    $headers = [
    //        'Content-Type' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
    //        'Content-Disposition' => 'attachment; filename="' . $filename . '"',
    //    ];

    //    return response()->download($path, $filename)->deleteFileAfterSend();
    // }

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
