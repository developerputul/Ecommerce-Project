<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function UserDashboard(){
        $id = Auth::user()->id;
        $userData = User::find($id);
        return view('index', compact('userData'));
    } // UserDashboard end

    public function UserProfileStore(Request $request){
        $id = Auth::user()->id;
        $data = User::find($id);
        $data->name = $request->name;
        $data->username = $request->username;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->address = $request->address;


        if($request->file('photo')) {
            $file = $request->file('photo');
            @unlink(public_path('upload/user_image/'.$data->photo));
            $filename = date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('upload/user_image'), $filename);
            $data['photo'] = $filename;
        }
        $data->save();

        $notification = array(
            'message' => 'User Profile Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);

    } //end method

    public function UserLogout(Request $request){
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        $notification = array(

            'message' => 'User Logout Successfully',
            'alert-type' => 'success'
    );

        return redirect('/login')->with($notification);
    } //end method

    public function UserUpdatepassword(Request $request){
        //validation
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|confirmed',
        ]);
        //match the old password
        if(!Hash::check($request->old_password, auth::user()->password)){
            return back()->with("error", "Old Password Dose not Match!!");
        }
        //update the new password
        User::whereId(auth()->user()->id)->update([
            'password' =>Hash::make($request->new_password)

        ]);
        return back()->with("status", "Password Change Successfully");


    } //end method
}
