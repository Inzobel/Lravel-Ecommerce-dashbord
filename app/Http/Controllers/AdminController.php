<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AdminController extends Controller
{
    public function AdminLogout()
    {
        // Auth guard to logout
        Auth::guard('web')->logout();
        
        // Redirect to the login route
        return redirect()->route('login');
    } // end method

    public function UserProfile(){

        $adminData = User::find(1);
        return view('backend.admin.admin_profile',compact('adminData'));

    } // end mehtod 

    public function UserProfileStore(Request $request){
        $data = User::find(1);
        $data->name = $request->name;
        $data->email = $request->email;

        if ($request->file('profile_photo_path')) {
            $file = $request->file('profile_photo_path');
            @unlink(public_path('upload/admin_images/'.$data->profile_photo_path));
            $filename = date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('upload/admin_images'),$filename);
            $data['profile_photo_path'] = $filename;
        }
        $data->save();

        $notification = array(
            'message' => 'User Profile Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('user.profile')->with($notification);
    }// end mehtod 


}
