<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Booking;
use App\Rules\oldPassword;
use App\Rules\passwordRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class userController extends Controller
{
    // go to user edit page
    public function edit()
    {
        return view('user.edit_user');
    }

    // change background color
    public function change_bg_color(Request $request)
    {
        $id = getAuth()->id;
        $user = User::find($id);
        $user->bg_color = $request->data;
        $user->save();
        return response()->json(200);
    }

    // change text color
    public function change_text_color(Request $request)
    {
        $id = getAuth()->id;
        $user = User::find($id);
        $user->text_color = $request->data;
        $user->save();
        return response()->json(200);
    }

    // change icon
    public function change_icon(Request $request)
    {
        $id = getAuth()->id;
        $user = User::find($id);
        $user->icon = $request->data;
        $user->save();
        return response()->json(200);
    }

    //change Password
    public function change_password(Request $request)
    {
        // dd($request->all());
        $this->cus_validate($request);

        $password = Hash::make($request->new_pass);
        User::where('id',getAuth()->id)->update([
            'password' => $password,
            'password_str' => $request->new_pass
        ]);
        return back()->with('success','Create Success');
        // dd($password);
    }

    //custom validation
    private function cus_validate($data){
        $validate = $data->validate([
            'old_pass' => ['required',new oldPassword($data->old_pass)],
            'new_pass' => ['required'],
            'con_pass' => ['required',new passwordRule($data->new_pass,$data->con_pass)]
        ]);
        return $validate;
    }
}
