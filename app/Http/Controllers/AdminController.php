<?php

namespace App\Http\Controllers;

// use App\Http\Middleware\Role;

use App\Models\CommitteeCategory;
use App\Models\MeetingFormat;
use App\Models\Position;
use App\Models\PrefixName;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $committeecategories = CommitteeCategory::all();
        return view('admin.backend.pages.admin.all_admin', compact('alladmin', 'committeecategories'));
    }

    public function AddAdmin() {

        $roles = Role::all();
        $committeecategories = CommitteeCategory::all();
        $prefixnames = PrefixName::all();
        $positions = Position::all();
        $meeting_formats = MeetingFormat::all();
        return view('admin.backend.pages.admin.add_admin', compact('roles', 'committeecategories', 'prefixnames', 'positions', 'meeting_formats'));
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

        $validatedData = $request->validate([
            'prefix_name' => 'required',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'position_id' => 'required|exists:positions,id',
            // 'email' => 'required|email|unique:users,email',
            'password' => 'required|min:3',
            'roles' => 'required|exists:roles,name',
            'committees' => 'nullable|array|exists:committee_categories,id',
            'meeting_format_id' => 'required|exists:meeting_formats,id',
            // 'meeting_formats_id' => 'nullable|array|exists:meeting_formats,id',
        ]);

        // $user = new User();
        // // $user->title = $request->title;
        // $user->prefix_name_id = $request->prefix_name;
        // $user->first_name = $request->first_name;
        // $user->last_name = $request->last_name;
        // $user->username = $request->first_name;
        // $user->position_id = $request->position_id;
        // // $user->phone = $request->phone;
        // $user->email = $request->email;
        // $user->password = Hash::make($request->password);
        // $user->role = 'admin';
        // $user->status = 1;
        // $user->save();

        $user = User::create([
            'prefix_name_id' => $request->prefix_name,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'username' => $request->first_name, // Consider using a more unique username
            'position_id' => $request->position_id,
            'meeting_format_id' => $request->meeting_format_id,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admin',
            'status' => 1,
        ]);

        $user->committees()->attach($validatedData['committees']);
        // Assign committees
        // if ($request->has('committees')) {
        //     $user->committees()->attach($request->committees);
        // }

        // Assign meeting formats
        // if ($request->has('meeting_formats')) {
        //     $user->meetingFormats()->attach($request->meeting_formats);
        // }

        if($request->roles) {
            $user->assignRole($request->roles);
        }

        $notification = [
            'message' => 'เพิ่มผู้ใช้ใหม่สำเร็จ',
            'alert-type' => 'success'
        ];

        return redirect()->route('all.admin')->with($notification);
    }

    public function EditAdmin($id) {
        $user = User::findOrFail($id);
        $roles = Role::all();
        $committeecategories = CommitteeCategory::all();
        $prefixnames = PrefixName::all();
        $positions = Position::all();
        $meeting_formats = MeetingFormat::all();

        return view('admin.backend.pages.admin.edit_admin', compact('user', 'roles', 'committeecategories', 'prefixnames', 'positions', 'meeting_formats'));
    }

    public function UpdateAdmin(Request $request, $id) {

        // $request->validate([
        //     'prefix_name' => 'required',
        //     'first_name' => 'required|string|max:255',
        //     'last_name' => 'required|string|max:255',
        //     'position_id' => 'required|exists:positions,id',
        //     'email' => 'required|email|unique:users,email,'.$id,
        //     'roles' => 'required|exists:roles,name',
        // ]);

        // $user = User::findOrFail($id);
        // $user->title = $request->title;
        // $user->prefix_name_id = $request->prefix_name;
        // $user->first_name = $request->first_name;
        // $user->last_name = $request->last_name;
        // $user->username = $request->first_name;
        // // $user->phone = $request->phone;
        // $user->position_id = $request->position_id;
        // $user->email = $request->email;
        // // $user->password = Hash::make($request->password);
        // if ($request->filled('password')) {
        //     $user->password = Hash::make($request->password);
        // }
        // $user->role = 'admin';
        // $user->status = 1;
        // $user->save();

        $user = User::findOrFail($id);
        $request->validate([
            'prefix_name' => 'required|exists:prefix_names,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'position_id' => 'required|exists:positions,id',
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'nullable|min:3',
            'roles' => 'required|exists:roles,name',
            'committees' => 'nullable|array|exists:committee_categories,id',
            'meeting_format_id' => 'required|exists:meeting_formats,id',
        ]);

        $user->update([
            'prefix_name_id' => $request->prefix_name,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'username' => $request->first_name,
            'position_id' => $request->position_id,
            'meeting_format_id' => $request->meeting_format_id,
            'email' => $request->email,
            'role' => 'admin',
            'status' => 1,
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        // Update committees
        $user->committees()->sync($request->committees ?? []);

        // Update roles
        $user->syncRoles([$request->roles]);

        // $user->roles()->detach();
        // if($request->roles) {
        //     $user->assignRole($request->roles);
        // }

        $notification = [
            'message' => 'ข้อมูลผู้ใช้ถูกอัปเดตเรียบร้อยแล้ว',
            'alert-type' => 'success'
        ];

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

    public function AdminLogout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        $notification = array(
            'message' => 'Logout Successfully',
            'alert-type' => 'success'
        );
        return redirect('/user/login')->with($notification);
    }

    public function UserLogin() {
        return view('auth.login');
    }
}
