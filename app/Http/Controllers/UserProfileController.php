<?php

namespace App\Http\Controllers;

use App\Models\PrefixName;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserProfileController extends Controller
{
    public function editProfile()
    {
        $user = Auth::user();
        $prefixNames = PrefixName::all();
        return view('admin.backend.pages.user.profile.edit', compact('user', 'prefixNames'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'prefix_name_id' => 'required|exists:prefix_names,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255', 
            // 'username' => 'required|string|max:255|unique:users,username,'.$user->id,
            'email' => 'required|email|unique:users,email,'.$user->id,
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:8|confirmed',
        ]);

        // ตรวจสอบรหัสผ่านปัจจุบัน
        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                $notification = array(
                    'message' => 'รหัสผ่านปัจจุบันไม่ถูกต้อง',
                    'alert-type' => 'error'
                );
                return redirect()->back()->with($notification);
            }
        }

        // อัปเดตข้อมูลผู้ใช้
        $user->update([
            'prefix_name_id' => $request->prefix_name_id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'username' => $request->first_name,
            'email' => $request->email,
            'role' => 'admin',
            'status' => 1,
        ]);

        // อัปเดตรหัสผ่านถ้ามีการเปลี่ยน
        if ($request->filled('new_password')) {
            $user->update([
                'password' => Hash::make($request->new_password)
            ]);
        }

        $notification = array(
            'message' => 'อัปเดตข้อมูลส่วนตัวเรียบร้อยแล้ว',
            'alert-type' => 'success'
        );

        return redirect()->route('user.profile.edit')->with($notification);
    }
    
}
