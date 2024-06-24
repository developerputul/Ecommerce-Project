<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use PhpParser\Node\Expr\FuncCall;
// use Illuminate\Support\Arr;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Notifications\VendorApprovedNotification;
use Illuminate\Support\Facades\Notification;

class AdminController extends Controller
{
    public function AdminDashboard(){
        return view('admin.index');
    } //end method

    public function AdminLogin(){
        return view('admin.admin_login');
    } //end method

    public function AdminDestroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/admin/login');
    } // end method


    public function AdminProfile(){
        $id = Auth::user()->id;
        $adminData = User::find($id);
        return view('admin.admin_profile_view', compact('adminData'));
    } // end method

    public function AdminProfileStore(Request $request){
        $id = Auth::user()->id;
        $data = User::find($id);
        $data->name = $request->name;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->address = $request->address;


        if($request->file('photo')) {
            $file = $request->file('photo');
            @unlink(public_path('upload/admin_images/'.$data->photo));
            $filename = date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('upload/admin_images'), $filename);
            $data['photo'] = $filename;
        }
        $data->save();

        $notification = array(
            'message' => 'Admin Profile Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);

    } //end method

    public function AdminChangePassword(){
        return view('admin.admin_change_password');
    } //end method

    public function AdminUpdatePassword(Request $request){
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

    public function InactiveVendor(){

        $inActiveVendor = User::where('status', 'inactive')->where('role', 'vendor')->latest()->get();
        return view('backend.vendor.inactive_vendor', compact('inActiveVendor'));

    } //end method

    public function ActiveVendor(){

        $ActiveVendor = User::where('status', 'active')->where('role', 'vendor')->latest()->get();
        return view('backend.vendor.active_vendor', compact('ActiveVendor'));

    } //end method

    public function InactiveVendorDetails($id){
        $inactiveVendorDetails = User::findOrFail($id);
        return view('backend.vendor.inactive_vendor_details', compact('inactiveVendorDetails'));
    } //end method

    public function ActiveVendorApprove(Request $request){

        $vendor_id = $request->id;
        $user = User::findOrFail($vendor_id)->update([
            'status' => 'active',
        ]);
         $notification = array(
            'message' => 'Vendor Active Successfully',
            'alert-type' => 'success'
        );
        $vuser = User::where('role','vendor')->get();
        Notification::send($vuser, new VendorApprovedNotification($request)); 

        return redirect()->route('active.vendor')->with($notification);
    } //end method

    public function activeVendorDetails($id){

        $activeVendorDetails = User::findOrFail($id);
       return view('backend.vendor.active_vendor_details', compact('activeVendorDetails'));

    } //end method

    public function InactiveVendorApprove(Request $request){

        $vendor_id = $request->id;
        $user = User::findOrFail($vendor_id)->update([
            'status' => 'inactive',
        ]);

        $notification = array(
            'message' => 'Vendor InActive Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('inactive.vendor')->with($notification);

    } //end method


    ///Admin All Method

    public function AllAdmin(){

        $alladminuser = User::whereIn('role',['admin','superadmin','ceo'])->get();

        return view('backend.admin.all_admin',compact('alladminuser'));
    } // End Method

    public function AddAdmin(){

        $roles = Role::all();
        return view('backend.admin.add_admin',compact('roles'));
    } // End Method

    public function AdminUserStore(Request $request){
        $user = new User();
        $user->username = $request->username;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->password = Hash::make($request->password);
        $role_name = Role::find(abs($request->roles))->name;
        $user->role =strtolower($role_name);
        $user->status ='active';
        $user->save();

        if ($request->roles) {
          $user->assignRole(abs($request->roles));
        }

        $notification = array(
            'message' => 'New Admin User Inserted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.admin')->with($notification);
    } // End Method

  
    public function EditAdminRole($id){

        $user = User::findOrFail($id);
        $roles = Role::all();

        return view('backend.admin.edit_admin',compact('user','roles'));
    } // End Method

    public function AdminUserUpdate(Request $request,$id){
        $user = User::findOrfail($id);
        $user->username = $request->username;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $role_name = Role::find(abs($request->roles))->name;
        $user->role = strtolower($role_name);
        $user->status ='active';
        $user->save();

        $user->roles()->detach();
        if ($request->roles) {
            $user->assignRole(abs($request->roles));
        }

        $notification = array(
            'message' => 'New Admin User Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.admin')->with($notification);


    } // End Method
    
    public function DeleteAdminRole($id){

        $user = User::findOrFail($id);

        if (!is_null($user)) {
            $user->delete();
        }

        $notification = array(
            'message' => 'Admin User Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);

    }  //End Method

}
