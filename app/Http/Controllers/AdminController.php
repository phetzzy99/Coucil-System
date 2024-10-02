<?php

namespace App\Http\Controllers;

// use App\Http\Middleware\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AdminController extends Controller
{
    public function AdminDashboard() {
        return view('admin.admin_dashboard');
    }

    public function AllAdmin() {
        // $alladmin = User::where('role', 'admin')->get();
        $alladmin = User::all();
        return view('admin.backend.pages.admin.all_admin', compact('alladmin'));
    }

    public function AddAdmin() {

        $roles = Role::all();
        return view('admin.backend.pages.admin.add_admin', compact('roles'));
    }

    public function StoreAdmin(Request $request) {

        $checkEmail = User::where('email', $request->email)->first();
        if($checkEmail) {
            $notification = array(
                'message' => 'Email already exists',
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
        }

        $user = new User();
        $user->title = $request->title;
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->username = $request->first_name;
        $user->phone = $request->phone;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->role = 'admin';
        $user->status = 1;
        $user->save();

        if($request->roles) {
            $user->assignRole($request->roles);
        }

        $notification = array(
            'message' => 'Admin Added Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.admin')->with($notification);
    }

    public function EditAdmin($id) {
        $user = User::find($id);
        $roles = Role::all();
        return view('admin.backend.pages.admin.edit_admin', compact('user', 'roles'));
    }

    public function UpdateAdmin(Request $request, $id) {

        $user = User::find($id);
        $user->title = $request->title;
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->username = $request->first_name;
        $user->phone = $request->phone;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->role = 'admin';
        $user->status = 1;
        $user->save();

        $user->roles()->detach();
        if($request->roles) {
            $user->assignRole($request->roles);
        }

        $notification = array(
            'message' => 'Admin Updated Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.admin')->with($notification);
    }

    public function DeleteAdmin($id) {

        $user = User::find($id);
        if (!is_null($user)) {
            $user->delete();
        }

        // $user->roles()->detach();

        $notification = array(
            'message' => 'Admin Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }
}
