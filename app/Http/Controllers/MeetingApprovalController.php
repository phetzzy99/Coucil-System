<?php

namespace App\Http\Controllers;

use App\Models\MeetingAgenda;
use App\Models\MeetingApproval;
use App\Models\MeetingApprovalDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MeetingApprovalController extends Controller
{
    public function AllMeetingApproval()
    {
        $user_id = Auth::user()->id;
        $my_meetings = MeetingAgenda::where('status', 1)->get();
        // $my_meetings = MeetingAgenda::where('status', 1)->where('user_id', $user_id)->get();

        return view('admin.backend.pages.meeting_approval.all_meeting_approval', compact('my_meetings'));
    }

    public function MeetingApprovalDetail($id)
    {
        $my_meetings = MeetingAgenda::findOrFail($id);

        $approvals = MeetingApproval::where('meeting_agenda_id', $id)
        ->with(['user', 'approvalDetails.meetingAgendaSection'])
        ->orderBy('created_at', 'desc')
        ->get();

        // เพิ่ม prefix name สำหรับนำไปแสดง title
        $prefixNames = [];
        foreach ($approvals as $approval) {
            $prefixNames[$approval->id] = $approval->user->prefixName();
        }

        return view('admin.backend.pages.meeting_approval.meeting_approval_detail', compact('my_meetings', 'approvals', 'prefixNames'));
        // return view('admin.backend.pages.meeting_approval.meeting_approval_detail_2',compact('my_meetings'));
        // return view('admin.backend.pages.meeting_approval.meeting_approval_detail_3',compact('my_meetings'));
    }

    public function store(Request $request, $meetingAgendaId)
    {
        $meetingAgenda = MeetingAgenda::findOrFail($meetingAgendaId);
        $userId = Auth::id();

        // ตรวจสอบว่าผู้ใช้นี้เคยรับรองการประชุมนี้แล้วหรือไม่
        $existingApproval = MeetingApproval::where('meeting_agenda_id', $meetingAgenda->id)
            ->where('user_id', $userId)
            ->first();

        if ($existingApproval) {
            // อัพเดทการรับรองที่มีอยู่
            $existingApproval->update([
                'meeting_type_id' => $request->meeting_type_id,
                'committee_category_id' => $request->committee_category_id,
                'meeting_format_id' => $request->meeting_format_id,
                'rule_of_meeting_id' => $request->rule_of_meeting_id,
                'regulation_meeting_id' => $request->regulation_meeting_id,
                'approval_date' => now(),
            ]);
            $meetingApproval = $existingApproval;
        } else {
            // สร้างการรับรองใหม่
            $meetingApproval = MeetingApproval::create([
                'meeting_agenda_id' => $meetingAgenda->id,
                'user_id' => $userId,
                'meeting_type_id' => $request->meeting_type_id,
                'committee_category_id' => $request->committee_category_id,
                'meeting_format_id' => $request->meeting_format_id,
                'rule_of_meeting_id' => $request->rule_of_meeting_id,
                'regulation_meeting_id' => $request->regulation_meeting_id,
                'approval_date' => now(),
            ]);
        }

        // ลบรายละเอียดการรับรองเก่า (ถ้ามี) และสร้างใหม่
        MeetingApprovalDetail::where('meeting_approval_id', $meetingApproval->id)->delete();

        foreach ($request->input('approvals') as $sectionId => $approval) {
            MeetingApprovalDetail::create([
                'meeting_approval_id' => $meetingApproval->id,
                'meeting_agenda_section_id' => $sectionId,
                'approval_type' => $approval['type'],
                'comments' => $approval['type'] === 'with_changes' ? $approval['comments'] : null,
            ]);
        }

        $message = $existingApproval ? 'อัพเดทการรับรองรายงานการประชุมเรียบร้อยแล้ว' : 'บันทึกการรับรองรายงานการประชุมเรียบร้อยแล้ว';
        return response()->json(['message' => $message], 200);

        // $meetingAgenda = MeetingAgenda::findOrFail($meetingAgendaId);

        // $meetingApproval = MeetingApproval::create([
        //     'meeting_agenda_id' => $meetingAgenda->id,
        //     'meeting_type_id' => $request->meeting_type_id,
        //     'rule_of_meeting_id' => $request->rule_of_meeting_id,
        //     'regulation_meeting_id' => $request->regulation_meeting_id,
        //     'committee_category_id' => $request->committee_category_id,
        //     'meeting_format_id' => $request->meeting_format_id,
        //     'user_id' => Auth::user()->id,
        //     'approval_date' => now(),
        // ]);

        // foreach ($request->input('approvals') as $sectionId => $approval) {
        //     MeetingApprovalDetail::create([
        //         'meeting_approval_id' => $meetingApproval->id,
        //         'meeting_agenda_section_id' => $sectionId,
        //         'approval_type' => $approval['type'],
        //         'comments' => $approval['type'] === 'with_changes' ? $approval['comments'] : null,
        //     ]);
        // }

        // return response()->json(['message' => 'บันทึกการรับรองรายงานการประชุมเรียบร้อยแล้ว'], 200);
    }

    public function getApprovalDetails($id)
    {
        $approval = MeetingApproval::with(['user', 'approvalDetails.meetingAgendaSection'])
            ->findOrFail($id);

        return view('admin.backend.pages.meeting_approval.approval_details', compact('approval'));
    }
}
