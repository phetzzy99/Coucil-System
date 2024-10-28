<?php

namespace App\Http\Controllers;

use App\Models\MeetingAgenda;
use App\Models\MeetingAgendaItems;
use App\Models\MeetingAgendaLecture;
use App\Models\MeetingAgendaSection;
use App\Models\MeetingApproval;
use App\Models\MeetingApprovalDetail;
use App\Models\MeetingType;
use App\Models\MeetingView;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MeetingApprovalController extends Controller
{
    private function isAdmin($user)
    {
        // return $user->role === 'admin' || $user->hasRole('admin');
        return $user->hasRole('Super Admin');
    }

    public function AllMeetingApproval()
    {

        $user = Auth::user();
        $meeting_types = MeetingType::all(); // เพิ่มการดึงข้อมูล meeting types

        $my_meetings = MeetingAgenda::whereIn('meeting_type_id', $user->meetingTypes->pluck('id'))
            ->whereIn('committee_category_id', $this->getUserCommitteeIds($user))
            // ->where('status', 1)
            // ->where('approval_deadline', '>', now()) // ตรวจสอบ deadline
            ->get();

        $my_meetings = $my_meetings->map(function ($item) use ($user) {
            $item->hasPermission = $this->checkUserPermission($user, $item);
            $item->hasViewed = MeetingView::where('user_id', $user->id)
                                        ->where('meeting_agenda_id', $item->id)
                                        ->exists();
            $item->approvalStatus = $this->getApprovalStatus($user->id, $item->id);
            $item->daysUntilDeadline = now()->diffInDays(Carbon::parse($item->approval_deadline), false);

            // เพิ่มการเช็ค deadline สำหรับปุ่ม
            $item->isDeadlinePassed = $item->approval_deadline ? now()->gt(Carbon::parse($item->approval_deadline)) : false;
            $item->isAdmin = $this->isAdmin($user); // เพิ่มการเช็คสถานะ admin

            return $item;
        });

        // $user = Auth::user();
        // $userMeetingTypes = $user->meetingTypes;
        // $userCommitteeIds = [];
        // foreach ($userMeetingTypes as $meetingType) {
        //     $committeeIds = json_decode($meetingType->pivot->committee_ids, true);
        //     $userCommitteeIds = array_merge($userCommitteeIds, $committeeIds ?? []);
        // }
        // $userCommitteeIds = array_unique($userCommitteeIds);

        // $my_meetings = MeetingAgenda::whereIn('meeting_type_id', $userMeetingTypes->pluck('id'))
        //     ->whereIn('committee_category_id', $userCommitteeIds)
        //     ->where('status', 1)
        //     ->get();

        // $my_meetings = MeetingAgenda::where('status', 1)->where('user_id', $user_id)->get();

        return view('admin.backend.pages.meeting_approval.all_meeting_approval', compact('my_meetings', 'meeting_types'));
    }

    public function MeetingApprovalDetail($id)
    {
        $user = Auth::user();
        $meetingAgenda = MeetingAgenda::findOrFail($id);

        // ตรวจสอบสิทธิ์การเข้าถึง
        if (!$this->checkUserPermission($user, $meetingAgenda)) {
            abort(403, 'Unauthorized action.');
        }

            // อนุญาตให้ admin เข้าถึงได้แม้เลย deadline
        if (!$this->isAdmin($user) && Carbon::now()->isAfter($meetingAgenda->approval_deadline)) {
            return redirect()->back()->with('error', 'เลยกำหนดเวลารับรองแล้ว');
        }

        // ตรวจสอบ deadline
        // if (Carbon::now()->isAfter($meetingAgenda->approval_deadline)) {
        //     return redirect()->back()->with('error', 'เลยกำหนดเวลารับรองแล้ว');
        // }

        // ตรวจสอบสิทธิ์การเข้าถึง
        if (!$this->checkUserPermission($user, $meetingAgenda)) {
            abort(403, 'Unauthorized action.');
        }

         // บันทึกการเข้าดูรายละเอียด
         MeetingView::updateOrCreate(
            ['user_id' => $user->id, 'meeting_agenda_id' => $id],
            ['viewed_at' => now()]
        );


        $my_meetings = MeetingAgenda::findOrFail($id);

        $approvals = MeetingApproval::where('meeting_agenda_id', $id)
        ->with(['user', 'approvalDetails.meetingAgendaSection'])
        ->orderBy('created_at', 'desc')
        ->get();

        $hasApproved = $approvals->where('user_id', $user->id)->isNotEmpty();
        $totalApprovals = $approvals->count();

        // เพิ่ม prefix name สำหรับนำไปแสดง title
        $prefixNames = [];
        foreach ($approvals as $approval) {
            $prefixNames[$approval->id] = $approval->user->prefixName();
        }

        return view('admin.backend.pages.meeting_approval.meeting_approval_detail_0', compact('my_meetings', 'approvals', 'prefixNames', 'meetingAgenda', 'hasApproved', 'totalApprovals'));
        // return view('admin.backend.pages.meeting_approval.meeting_approval_detail', compact('my_meetings', 'approvals', 'prefixNames', 'meetingAgenda', 'hasApproved', 'totalApprovals'));
        // return view('admin.backend.pages.meeting_approval.meeting_approval_detail_2',compact('my_meetings'));
        // return view('admin.backend.pages.meeting_approval.meeting_approval_detail_3',compact('my_meetings'));
    }

    private function checkUserPermission($user, $meetingAgenda)
    {
        $userMeetingTypes = $user->meetingTypes;
        $userCommitteeIds = [];
        foreach ($userMeetingTypes as $meetingType) {
            $committeeIds = json_decode($meetingType->pivot->committee_ids, true);
            $userCommitteeIds = array_merge($userCommitteeIds, $committeeIds ?? []);
        }
        $userCommitteeIds = array_unique($userCommitteeIds);

        return $userMeetingTypes->contains($meetingAgenda->meeting_type_id) &&
                in_array($meetingAgenda->committee_category_id, $userCommitteeIds);
    }

    private function getUserCommitteeIds($user)
    {
        $userCommitteeIds = [];
        foreach ($user->meetingTypes as $meetingType) {
            $committeeIds = json_decode($meetingType->pivot->committee_ids, true);
            $userCommitteeIds = array_merge($userCommitteeIds, $committeeIds ?? []);
        }
        return array_unique($userCommitteeIds);
    }

    public function store(Request $request, $meetingAgendaId)
    {
        try {
            DB::beginTransaction();

            $meetingAgenda = MeetingAgenda::findOrFail($meetingAgendaId);
            $userId = Auth::id();

            // ตรวจสอบ deadline
            if ($meetingAgenda->approval_deadline && now()->gt($meetingAgenda->approval_deadline)) {
                return redirect()->back()->with([
                    'message' => 'หมดเวลารับรองแล้ว',
                    'alert-type' => 'error'
                ]);
            }
            // if (Carbon::now()->isAfter($meetingAgenda->approval_deadline)) {
            //     return response()->json(['message' => 'เลยกำหนดเวลารับรองแล้ว'], 403);
            // }

            // ตรวจสอบสิทธิ์การเข้าถึง
            if (!$this->checkUserPermission(Auth::user(), $meetingAgenda)) {
                return response()->json(['message' => 'ไม่มีสิทธิ์ในการรับรองการประชุมนี้'], 403);
            }

            // ตรวจสอบการรับรองที่มีอยู่
            $existingApproval = MeetingApproval::where('meeting_agenda_id', $meetingAgenda->id)
                ->where('user_id', $userId)
                ->first();

            // สร้างหรืออัปเดตการรับรอง
            $meetingApproval = MeetingApproval::updateOrCreate(
                [
                    'meeting_agenda_id' => $meetingAgenda->id,
                    'user_id' => $userId
                ],
                [
                    'meeting_type_id' => $request->meeting_type_id,
                    'committee_category_id' => $request->committee_category_id,
                    'meeting_format_id' => $request->meeting_format_id,
                    'rule_of_meeting_id' => $request->rule_of_meeting_id,
                    'regulation_meeting_id' => $request->regulation_meeting_id,
                    'approval_date' => now(),
                    'status' => 'approved'
                ]
            );

            // ลบรายละเอียดการรับรองเก่า (ถ้ามี)
            MeetingApprovalDetail::where('meeting_approval_id', $meetingApproval->id)->delete();

            // บันทึกรายละเอียดการรับรองใหม่
            foreach ($request->input('approvals') as $sectionId => $approval) {
                // ตรวจสอบว่าเป็นวาระที่มีเนื้อหา
                $section = MeetingAgendaSection::find($sectionId);
                if ($this->sectionHasContent($section)) {
                    MeetingApprovalDetail::create([
                        'meeting_approval_id' => $meetingApproval->id,
                        'meeting_agenda_section_id' => $sectionId,
                        'approval_type' => $approval['type'],
                        'comments' => $approval['type'] === 'with_changes' ? $approval['comments'] : null,
                    ]);
                }
            }

            DB::commit();

            // ส่งข้อความตอบกลับตามสถานะ
            $message = $existingApproval
                ? 'อัปเดตการรับรองรายงานการประชุมเรียบร้อยแล้ว'
                : 'บันทึกการรับรองรายงานการประชุมเรียบร้อยแล้ว';

            return response()->json([
                'message' => $message,
                'approval_id' => $meetingApproval->id,
                'status' => 'success'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            // \Log::error('Error in meeting approval: ' . $e->getMessage());
            return response()->json([
                'message' => 'เกิดข้อผิดพลาดในการบันทึกข้อมูล: ' . $e->getMessage(),
                'status' => 'error'
            ], 500);
        }
    }

    // เพิ่มเมธอดสำหรับตรวจสอบเนื้อหาของวาระ
    private function sectionHasContent($section)
    {
        if (!$section) return false;

        // ตรวจสอบ description
        if (!empty($section->description)) {
            return true;
        }

        // ตรวจสอบ lectures
        $hasLectures = MeetingAgendaLecture::where('meeting_agenda_section_id', $section->id)
            ->exists();
        if ($hasLectures) {
            return true;
        }

        // ตรวจสอบ items
        $hasItems = MeetingAgendaItems::where('meeting_agenda_section_id', $section->id)
            ->exists();
        if ($hasItems) {
            return true;
        }

        return false;
    }


    // public function store(Request $request, $meetingAgendaId)
    // {
    //     $meetingAgenda = MeetingAgenda::findOrFail($meetingAgendaId);
    //     $userId = Auth::id();

    //     // ตรวจสอบ deadline
    //     if (Carbon::now()->isAfter($meetingAgenda->approval_deadline)) {
    //         return response()->json(['message' => 'เลยกำหนดเวลารับรองแล้ว'], 403);
    //     }

    //     // ตรวจสอบสิทธิ์การเข้าถึง
    //     if (!$this->checkUserPermission(Auth::user(), $meetingAgenda)) {
    //         return response()->json(['message' => 'ไม่มีสิทธิ์ในการรับรองการประชุมนี้'], 403);
    //     }

    //     try {
    //         // บันทึกหรืออัปเดตการรับรอง
    //         $meetingApproval = MeetingApproval::updateOrCreate(
    //             [
    //                 'meeting_agenda_id' => $meetingAgendaId,
    //                 'user_id' => $userId
    //             ],
    //             [
    //                 'meeting_type_id' => $request->meeting_type_id,
    //                 'committee_category_id' => $request->committee_category_id,
    //                 'meeting_format_id' => $request->meeting_format_id,
    //                 'rule_of_meeting_id' => $request->rule_of_meeting_id,
    //                 'regulation_meeting_id' => $request->regulation_meeting_id,
    //                 'approval_date' => now()
    //             ]
    //         );

    //         // บันทึกรายละเอียดการรับรอง
    //         MeetingApprovalDetail::where('meeting_approval_id', $meetingApproval->id)->delete();
    //         foreach ($request->input('approvals') as $sectionId => $approval) {
    //             MeetingApprovalDetail::create([
    //                 'meeting_approval_id' => $meetingApproval->id,
    //                 'meeting_agenda_section_id' => $sectionId,
    //                 'approval_type' => $approval['type'],
    //                 'comments' => $approval['type'] === 'with_changes' ? $approval['comments'] : null,
    //             ]);
    //         }

    //         return response()->json([
    //             'message' => 'บันทึกการรับรองรายงานการประชุมเรียบร้อยแล้ว',
    //             'approval_status' => $this->getApprovalStatus($userId, $meetingAgendaId)
    //         ]);

    //     } catch (\Exception $e) {
    //         return response()->json(['message' => 'เกิดข้อผิดพลาดในการบันทึกข้อมูล: ' . $e->getMessage()], 500);
    //     }
    // }

    private function getApprovalStatus($userId, $meetingAgendaId)
    {
        $approval = MeetingApproval::where('meeting_agenda_id', $meetingAgendaId)
            ->where('user_id', $userId)
            ->first();

        $meetingAgenda = MeetingAgenda::find($meetingAgendaId);

        if (Carbon::now()->isAfter($meetingAgenda->approval_deadline)) {
            return $approval ? 'รับรองแล้ว' : 'ไม่ได้รับรอง';
        }

        return $approval ? 'รับรองแล้ว' : 'รอการรับรอง';
    }

    public function getApprovalDetails($id)
    {
        $approval = MeetingApproval::with(['user', 'approvalDetails.meetingAgendaSection'])
            ->findOrFail($id);

        return view('admin.backend.pages.meeting_approval.approval_details', compact('approval'));
    }

    public function editApproval($id)
    {
        try {
            // ลบการรับรองเดิม
            $approval = MeetingApproval::where([
                'meeting_agenda_id' => $id,
                'user_id' => Auth::id()
            ])->first();

            if ($approval) {
                // ลบข้อมูลการรับรองเดิม
                MeetingApprovalDetail::where('meeting_approval_id', $approval->id)->delete();
                $approval->delete();

                $notification = array(
                    'message' => 'คุณสามารถทำการรับรองใหม่ได้',
                    'alert-type' => 'success'
                );
            } else {
                $notification = array(
                    'message' => 'ไม่พบข้อมูลการรับรอง',
                    'alert-type' => 'error'
                );
            }

            // กลับไปยังหน้าเดิม
            return redirect()->route('meeting.approval.detail', $id)->with($notification);

        } catch (\Exception $e) {
            return redirect()->back()->with([
                'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage(),
                'alert-type' => 'error'
            ]);
        }
    }

    public function getApprovalData($meetingId)
    {
        try {
            $approval = MeetingApproval::where([
                'meeting_agenda_id' => $meetingId,
                'user_id' => Auth::id()
            ])->with('approvalDetails')->first();

            if (!$approval) {
                return response()->json([
                    'success' => false,
                    'message' => 'ไม่พบข้อมูลการรับรอง'
                ], 404);
            }

            // จัดรูปแบบข้อมูลสำหรับส่งกลับไปยัง frontend
            $approvalData = [];
            foreach ($approval->approvalDetails as $detail) {
                $approvalData[$detail->meeting_agenda_section_id] = [
                    'type' => $detail->approval_type,
                    'comments' => $detail->comments
                ];
            }

            return response()->json([
                'success' => true,
                'approval_data' => $approvalData
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
            ], 500);
        }
    }


    public function updateApproval(Request $request, $meetingId)
    {
        try {
            DB::beginTransaction();

            // ลบการรับรองเดิม
            $existingApproval = MeetingApproval::where([
                'meeting_agenda_id' => $meetingId,
                'user_id' => Auth::id()
            ])->first();

            if ($existingApproval) {
                MeetingApprovalDetail::where('meeting_approval_id', $existingApproval->id)->delete();
            }

            // สร้างการรับรองใหม่
            $approval = MeetingApproval::updateOrCreate(
                [
                    'meeting_agenda_id' => $meetingId,
                    'user_id' => Auth::id()
                ],
                [
                    'meeting_type_id' => $request->meeting_type_id,
                    'committee_category_id' => $request->committee_category_id,
                    'meeting_format_id' => $request->meeting_format_id,
                    'rule_of_meeting_id' => $request->rule_of_meeting_id,
                    'regulation_meeting_id' => $request->regulation_meeting_id,
                    'approval_date' => now(),
                    'status' => 'approved'
                ]
            );

            // บันทึกรายละเอียดการรับรอง
            foreach ($request->approvals as $sectionId => $approvalData) {
                MeetingApprovalDetail::create([
                    'meeting_approval_id' => $approval->id,
                    'meeting_agenda_section_id' => $sectionId,
                    'approval_type' => $approvalData['type'],
                    'comments' => $approvalData['type'] === 'with_changes' ? $approvalData['comments'] : null
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'บันทึกการรับรองเรียบร้อยแล้ว'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
            ], 500);
        }
    }



    //// original ////////////

    // public function store(Request $request, $meetingAgendaId)
    // {
    //     $meetingAgenda = MeetingAgenda::findOrFail($meetingAgendaId);
    //     $userId = Auth::id();

    //     // ตรวจสอบว่าผู้ใช้นี้เคยรับรองการประชุมนี้แล้วหรือไม่
    //     $existingApproval = MeetingApproval::where('meeting_agenda_id', $meetingAgenda->id)
    //         ->where('user_id', $userId)
    //         ->first();

    //     if ($existingApproval) {
    //         // อัพเดทการรับรองที่มีอยู่
    //         $existingApproval->update([
    //             'meeting_type_id' => $request->meeting_type_id,
    //             'committee_category_id' => $request->committee_category_id,
    //             'meeting_format_id' => $request->meeting_format_id,
    //             'rule_of_meeting_id' => $request->rule_of_meeting_id,
    //             'regulation_meeting_id' => $request->regulation_meeting_id,
    //             'approval_date' => now(),
    //         ]);
    //         $meetingApproval = $existingApproval;
    //     } else {
    //         // สร้างการรับรองใหม่
    //         $meetingApproval = MeetingApproval::create([
    //             'meeting_agenda_id' => $meetingAgenda->id,
    //             'user_id' => $userId,
    //             'meeting_type_id' => $request->meeting_type_id,
    //             'committee_category_id' => $request->committee_category_id,
    //             'meeting_format_id' => $request->meeting_format_id,
    //             'rule_of_meeting_id' => $request->rule_of_meeting_id,
    //             'regulation_meeting_id' => $request->regulation_meeting_id,
    //             'approval_date' => now(),
    //         ]);
    //     }

    //     // ลบรายละเอียดการรับรองเก่า (ถ้ามี) และสร้างใหม่
    //     MeetingApprovalDetail::where('meeting_approval_id', $meetingApproval->id)->delete();

    //     foreach ($request->input('approvals') as $sectionId => $approval) {
    //         MeetingApprovalDetail::create([
    //             'meeting_approval_id' => $meetingApproval->id,
    //             'meeting_agenda_section_id' => $sectionId,
    //             'approval_type' => $approval['type'],
    //             'comments' => $approval['type'] === 'with_changes' ? $approval['comments'] : null,
    //         ]);
    //     }

    //     $message = $existingApproval ? 'อัพเดทการรับรองรายงานการประชุมเรียบร้อยแล้ว' : 'บันทึกการรับรองรายงานการประชุมเรียบร้อยแล้ว';
    //     return response()->json(['message' => $message], 200);

    //     // $meetingAgenda = MeetingAgenda::findOrFail($meetingAgendaId);

    //     // $meetingApproval = MeetingApproval::create([
    //     //     'meeting_agenda_id' => $meetingAgenda->id,
    //     //     'meeting_type_id' => $request->meeting_type_id,
    //     //     'rule_of_meeting_id' => $request->rule_of_meeting_id,
    //     //     'regulation_meeting_id' => $request->regulation_meeting_id,
    //     //     'committee_category_id' => $request->committee_category_id,
    //     //     'meeting_format_id' => $request->meeting_format_id,
    //     //     'user_id' => Auth::user()->id,
    //     //     'approval_date' => now(),
    //     // ]);

    //     // foreach ($request->input('approvals') as $sectionId => $approval) {
    //     //     MeetingApprovalDetail::create([
    //     //         'meeting_approval_id' => $meetingApproval->id,
    //     //         'meeting_agenda_section_id' => $sectionId,
    //     //         'approval_type' => $approval['type'],
    //     //         'comments' => $approval['type'] === 'with_changes' ? $approval['comments'] : null,
    //     //     ]);
    //     // }

    //     // return response()->json(['message' => 'บันทึกการรับรองรายงานการประชุมเรียบร้อยแล้ว'], 200);
    // }


    // public function getApprovalDetails($id)
    // {
    //     $approval = MeetingApproval::with(['user', 'approvalDetails.meetingAgendaSection'])
    //         ->findOrFail($id);

    //     return view('admin.backend.pages.meeting_approval.approval_details', compact('approval'));
    // }


}
