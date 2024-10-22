<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function editApprovalDeadline()
    {
        $deadlineDays = Setting::where('key', 'approval_deadline_days')->first()->value ?? 7;
        return view('admin.backend.pages.settings.edit_approval_deadline', compact('deadlineDays'));
    }

    public function updateApprovalDeadline(Request $request)
    {
        $request->validate([
            'deadline_days' => 'required|integer|min:1|max:30',
        ]);

        Setting::updateOrCreate(
            ['key' => 'approval_deadline_days'],
            ['value' => $request->deadline_days]
        );

        return redirect()->back()->with('success', 'จำนวนวันสำหรับ deadline ได้รับการปรับปรุงแล้ว');
    }
}
