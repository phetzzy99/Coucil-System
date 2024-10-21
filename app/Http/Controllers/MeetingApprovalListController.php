<?php

namespace App\Http\Controllers;

use App\Models\CommitteeCategory;
use App\Models\MeetingReport;
use App\Models\MeetingType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MeetingApprovalListController extends Controller
{
    public function list($meeting_type_id, $committee_id)
    {
        // ตรวจสอบสิทธิ์ของผู้ใช้
        $user = Auth::user();
        $userMeetingTypes = $user->meetingTypes->pluck('id')->toArray();

        // ตรวจสอบว่าผู้ใช้มีสิทธิ์ดูรายการอนุมัติประเมินประเภทการประชุมนี้หรือไม่
        if (in_array($meeting_type_id, $userMeetingTypes)) {
            // ถ้ามีสิทธิ์ ให้แสดงรายการ
            return view('meeting_approval_list.list', ['meeting_type_id' => $meeting_type_id, 'committee_id' => $committee_id]);
        }

        // ตรวจสอบว่า committee_id อยู่ในสิทธิ์ของผู้ใช้หรือไม่
        $userCommitteeIds = json_decode($user->meetingTypes->where('id', $meeting_type_id)->first()->pivot->committee_ids, true) ?? [];
        if (!in_array($committee_id, $userCommitteeIds)) {
            return redirect()->route('dashboard')->with('error', 'คุณไม่มีสิทธิ์เข้าถึงข้อมูลนี้');
        }

        // ดึงข้อมูล MeetingType และ CommitteeCategory
        $meetingType = MeetingType::findOrFail($meeting_type_id);
        $committee = CommitteeCategory::findOrFail($committee_id);

        // ดึงรายการรายงานการประชุม
        $meetingReports = MeetingReport::where('meeting_type_id', $meeting_type_id)
            ->where('committee_category_id', $committee_id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.meeting_reports.list', compact('meetingReports', 'meetingType', 'committee'));
    }
}
