<?php

namespace App\Http\Controllers;

// use App\Http\Middleware\Role;

use App\Models\CommitteeCategory;
use App\Models\MeetingFormat;
use App\Models\MeetingType;
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
        // return view('admin.admin_dashboard');
        $user = Auth::user();
        $user->load('meetingTypes','meetingFormat');
        $committeecategories = CommitteeCategory::all();
        // $meetingTypes = $user->meetingTypes;
        // $meetingFormats = MeetingFormat::where('id', $user->meeting_format_id)->get();

        return view('admin.index', compact('user', 'committeecategories'));
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
        $meeting_types = MeetingType::all();

        return view('admin.backend.pages.admin.add_admin', compact('roles', 'committeecategories', 'prefixnames', 'positions', 'meeting_formats', 'meeting_types'));
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
            // 'committees' => 'nullable|array|exists:committee_categories,id',
            'meeting_format_id' => 'required|exists:meeting_formats,id',
            'meeting_types' => 'nullable|array',
            'meeting_types.*' => 'exists:meeting_types,id',
            'meeting_committees' => 'nullable|array',
            'meeting_committees.*' => 'array',
            'meeting_committees.*.*' => 'exists:committee_categories,id',
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

        // $user->committees()->attach($validatedData['committees']);

        if ($request->has('meeting_types')) {
            $user->meetingTypes()->attach($request->meeting_types);
        }

        if ($request->has('meeting_committees')) {
            foreach ($request->meeting_committees as $meeting_type_id => $committee_ids) {
                $user->meetingTypes()->attach($meeting_type_id, ['committee_ids' => json_encode($committee_ids)]);
            }
        }

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
        $meeting_types = MeetingType::all();

        return view('admin.backend.pages.admin.edit_admin', compact('user', 'roles', 'committeecategories', 'prefixnames', 'positions', 'meeting_formats', 'meeting_types'));
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
            // 'committees' => 'nullable|array|exists:committee_categories,id',
            'meeting_format_id' => 'required|exists:meeting_formats,id',
            // 'meeting_types' => 'required|array|exists:meeting_types,id',
            'meeting_committees' => 'nullable|array',
            'meeting_committees.*' => 'array',
            'meeting_committees.*.*' => 'exists:committee_categories,id',
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

        // อัปเดตความสัมพันธ์ระหว่างประเภทการประชุมและคณะกรรมการ
        $user->meetingTypes()->detach(); // ลบความสัมพันธ์เดิมทั้งหมด
        if ($request->has('meeting_committees')) {
            foreach ($request->meeting_committees as $meeting_type_id => $committee_ids) {
                $user->meetingTypes()->attach($meeting_type_id, ['committee_ids' => json_encode($committee_ids)]);

            }
        }

        // Update committees
        // $user->committees()->sync($request->committees ?? []);

        // Update roles
        $user->syncRoles([$request->roles]);

        // Update meeting types
        // $user->meetingTypes()->sync($request->meeting_types);

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
        try {
            $user = User::findOrFail($id);

            if (Auth::id() == $user->id) {
                $notification = array(
                    'message' => 'คุณไม่สามารถลบบัญชีของตัวเองได้',
                    'alert-type' => 'error'
                );
                return redirect()->back()->with($notification);
            }

            $user->rolse()->detach();
            $user->committees()->detach();
            $user->meetingTypes()->detach();

            $user->delete();

            $notification = array(
                'message' => 'ลบบัญชีผู้ใช้เรียบร้อยแล้ว',
                'alert-type' => 'success'
            );
            return redirect()->back()->with($notification);

        } catch (\Exception $e) {

            $notification = array(
                'message' => $e->getMessage(),
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
        }

        // $user = User::find($id);
        // if (!is_null($user)) {
        //     $user->delete();
        // }

        // // $user->roles()->detach();

        // $notification = array(
        //     'message' => 'Admin Deleted Successfully',
        //     'alert-type' => 'success'
        // );

        // return redirect()->back()->with($notification);
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
