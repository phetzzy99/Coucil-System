<?php

namespace App\Http\Controllers;

use App\Models\MeetingAgenda;
use App\Models\MeetingAgendaItems;
use App\Models\MeetingAgendaLecture;
use App\Models\MeetingAgendaSection;
use App\Models\MeetingApproval;
use App\Models\MeetingApprovalDetail;
use App\Models\MeetingApprovalHistory;
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
        ->where('status', true)
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
            'sections.meetingAgendaLectures.meetingAgendaItems',
            'sections.approvalDetails.meetingApproval.user.position',
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

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            foreach ($request->approvals as $sectionId => $sectionApprovals) {
                foreach ($sectionApprovals as $approvalId => $data) {
                    $detail = MeetingApprovalDetail::where('meeting_approval_id', $approvalId)
                        ->where('meeting_agenda_section_id', $sectionId)
                        ->first();

                    if ($detail) {
                        $detail->update([
                            'approval_type' => $data['type'],
                            'comments' => $data['comments']
                        ]);
                    }
                }
            }

            DB::commit();
            $notification = array(
                'message' => 'เพิ่มระเบียบวาระการประชุมแล้ว',
                'alert-type' => 'success'
            );
            return redirect()
                ->route('meeting.report.summary', $id)
                ->with($notification);

        } catch (\Exception $e) {
            DB::rollBack();
            $notification = array(
                'message' => 'เกิดข้อผิดพลาดในการบันทึกข้อมูล: ' . $e->getMessage(),
                'alert-type' => 'error'
            );
            return back()
                ->with($notification)
                ->withInput();
        }
    }

    // public function updateSummary(Request $request, $id)
    // {
    //     $request->validate([
    //         'sections' => 'required|array',
    //         'sections.*.content' => 'required|string'
    //     ]);

    //     $meetingAgenda = MeetingAgenda::findOrFail($id);

    //     foreach ($request->sections as $sectionId => $data) {
    //         $section = $meetingAgenda->sections()->find($sectionId);
    //         if ($section) {
    //             $section->update([
    //                 'description' => $data['content']
    //             ]);
    //         }
    //     }

    //     return redirect()->back()->with('success', 'อัปเดตรายงานการประชุมเรียบร้อยแล้ว');
    // }

    // รับรองโดย Admin
    public function adminApprove(Request $request, $id)
    {
        $request->validate([
            'admin_approval_note' => 'required|string|min:5'
        ], [
            'admin_approval_note.required' => 'กรุณาระบุบันทึกการรับรอง',
            'admin_approval_note.min' => 'บันทึกการรับรองต้องมีความยาวอย่างน้อย 5 ตัวอักษร'
        ]);

        DB::beginTransaction();
        try {
            $meetingAgenda = MeetingAgenda::findOrFail($id);

            // ตรวจสอบว่าเคยรับรองแล้วหรือไม่
            if ($meetingAgenda->is_admin_approved) {
                throw new \Exception('รายงานการประชุมนี้ถูกรับรองแล้ว');
            }

            // อัปเดตสถานะการรับรอง
            $meetingAgenda->update([
                'is_admin_approved' => true,
                'admin_approved_at' => now(),
                'admin_approved_by' => Auth::id(),
                'admin_approval_note' => $request->admin_approval_note,
                'status_public' => 'published'
            ]);

            // บันทึกประวัติการรับรอง
            MeetingApprovalHistory::create([
                'meeting_agenda_id' => $id,
                'user_id' => Auth::id(),
                'action' => 'approve',
                'note' => $request->admin_approval_note,
                'action_at' => now()
            ]);

            // ส่งอีเมล์แจ้งเตือนผู้เกี่ยวข้อง (ถ้าต้องการ)
            // $this->sendApprovalNotifications($meetingAgenda);

            DB::commit();
            $notification = array(
                'message' => 'รับรองการประชุมเรียบร้อยแล้ว',
                'alert-type' => 'success'
            );
            return redirect()
                ->route('meeting.report.summary', $id)
                ->with($notification);

            // return response()->json(['success' => true]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

        // ยกเลิกการรับรอง
        public function adminCancel($id)
        {
            DB::beginTransaction();
            try {
                $meetingAgenda = MeetingAgenda::findOrFail($id);

                if (!$meetingAgenda->is_admin_approved) {
                    throw new \Exception('รายงานการประชุมนี้ยังไม่ได้รับการรับรอง');
                }

                // อัปเดตสถานะ
                $meetingAgenda->update([
                    'is_admin_approved' => false,
                    'admin_approved_at' => null,
                    'admin_approved_by' => null,
                    'admin_approval_note' => null,
                    'status_public' => 'draft'
                ]);

                // บันทึกประวัติ
                MeetingApprovalHistory::create([
                    'meeting_agenda_id' => $id,
                    'user_id' => Auth::id(),
                    'action' => 'cancel',
                    'note' => 'ยกเลิกการรับรอง',
                    'action_at' => now()
                ]);

                DB::commit();
                return response()->json(['success' => true]);

            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 400);
            }
        }
}
