<?php

namespace App\Http\Controllers;

use App\Models\MeetingAgenda;
use App\Models\MeetingAgendaItems;
use App\Models\MeetingAgendaLecture;
use App\Models\MeetingAgendaSection;
use App\Models\MeetingApproval;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MeetingReportSummaryController extends Controller
{

    // public function index()
    // {
    //     $meetingAgendas = MeetingAgenda::with([
    //         'meeting_type',
    //         'approvals',
    //         'adminApprovedBy'
    //     ])->orderBy('meeting_agenda_date', 'desc')->get();

    //     return view('admin.backend.pages.meeting_report_sum.all_meeting_report_summary', compact('meetingAgendas'));
    // }

    // public function allReportSummary()
    // {
    //     $reports = MeetingAgenda::with([
    //         'meeting_type',
    //         'committeeCategory',
    //         'approvals',
    //         'adminApprovedBy'
    //     ])
    //     ->orderBy('meeting_agenda_date', 'desc')
    //     ->get();

    //     return view('admin.backend.pages.meeting_report_sum.all_meeting_report_summary', compact('reports'));
    // }

    public function index()
    {
        // คำนวณค่าเฉลี่ยเวลาในการรับรอง
        $averageApprovalTime = MeetingAgenda::where('is_admin_approved', true)
            ->whereNotNull('admin_approved_at')
            ->get()
            ->average(function ($agenda) {
                return $agenda->created_at->diffInDays($agenda->admin_approved_at);
            }) ?? 0;

        $meetingAgendas = MeetingAgenda::with([
            'meeting_type',
            'committeeCategory',
            'approvals',
            'adminApprovedBy',
            'sections.approvalDetails'
        ])
        ->orderBy('meeting_agenda_date', 'desc')
        ->get();

        return view('admin.backend.pages.meeting_report_sum.all_meeting_report_summary', compact(
            'meetingAgendas',
            'averageApprovalTime'
        ));
    }

    public function showSummary($id)
    {
        $meetingAgenda = MeetingAgenda::with([
            'meeting_type',
            'committeeCategory',
            'meetingFormat',
            'meetingAgendaSections.meetingAgendaLectures.meetingAgendaItems',
            'approvals.user',
            'approvals.approvalDetails'
        ])->findOrFail($id);

        // จัดกลุ่มการรับรองตามวาระ
        $approvalsBySection = [];
        foreach ($meetingAgenda->approvals as $approval) {
            foreach ($approval->approvalDetails as $detail) {
                $sectionId = $detail->meeting_agenda_section_id;
                if (!isset($approvalsBySection[$sectionId])) {
                    $approvalsBySection[$sectionId] = [];
                }
                $approvalsBySection[$sectionId][] = [
                    'user' => $approval->user,
                    'type' => $detail->approval_type,
                    'comments' => $detail->comments
                ];
            }
        }

        return view('admin.backend.pages.meeting_report_sum.meeting_summary', compact('meetingAgenda', 'approvalsBySection'));
    }

    public function edit($id)
    {
        $meetingAgenda = MeetingAgenda::with([
            'meeting_type',
            'committeeCategory',
            'meetingFormat',
            'sections.meetingAgendaLectures.meetingAgendaItems',
            'sections.approvalDetails.meetingApproval.user.position'
        ])->findOrFail($id);

        $approvalsBySection = $meetingAgenda->getApprovalsBySections();

        return view('admin.backend.pages.meeting_report_sum.meeting_summary_edit', compact('meetingAgenda', 'approvalsBySection'));
    }

    public function updateSummary(Request $request, $id)
    {
        $request->validate([
            'sections' => 'required|array',
            'sections.*.content' => 'required|string'
        ]);

        $meetingAgenda = MeetingAgenda::findOrFail($id);

        foreach ($request->sections as $sectionId => $data) {
            $section = $meetingAgenda->sections()->find($sectionId);
            if ($section) {
                $section->update([
                    'description' => $data['content']
                ]);
            }
        }

        return redirect()->back()->with('success', 'อัปเดตรายงานการประชุมเรียบร้อยแล้ว');
    }

    public function adminApprove(Request $request, $id)
    {
        $meetingAgenda = MeetingAgenda::findOrFail($id);

        $meetingAgenda->update([
            'is_admin_approved' => true,
            'admin_approved_at' => now(),
            'admin_approved_by' => Auth::id()
        ]);

        return redirect()->back()->with('success', 'รับรองรายงานการประชุมโดย Admin เรียบร้อยแล้ว');
    }
}
