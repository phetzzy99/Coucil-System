<?php

namespace App\Http\Controllers;

use App\Models\CommitteeOpinion;
use App\Models\MeetingAgendaLecture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\VoteUpdated; // เพิ่มบรรทัดนี้

class CommitteeOpinionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'lecture_id' => 'required|exists:meeting_agenda_lectures,id',
            'feedback_status' => 'required|in:approve,reject',
            'feedback_content' => 'required_if:feedback_status,reject',
        ]);

        // ค้นหาความเห็นที่มีอยู่แล้ว
        $opinion = CommitteeOpinion::where([
            'meeting_agenda_lecture_id' => $request->lecture_id,
            'user_id' => Auth::id()
        ])->first();

        if (!$opinion) {
            // ถ้าไม่มีความเห็น ให้สร้างใหม่
            $opinion = new CommitteeOpinion();
            $opinion->meeting_agenda_lecture_id = $request->lecture_id;
            $opinion->user_id = Auth::id();
        }

        // อัพเดทข้อมูล
        $opinion->vote_type = $request->feedback_status;
        $opinion->opinion = $request->feedback_content;
        $opinion->save();

        // โหลดข้อมูลที่เกี่ยวข้อง
        $opinion->load(['user', 'user.prefixName']);

        // นับจำนวนโหวตทั้งหมด
        $approveCount = CommitteeOpinion::where('meeting_agenda_lecture_id', $request->lecture_id)
            ->where('vote_type', 'approve')
            ->count();

        $rejectCount = CommitteeOpinion::where('meeting_agenda_lecture_id', $request->lecture_id)
            ->where('vote_type', 'reject')
            ->count();

        // ส่ง event เพื่ออัพเดทแบบ real-time
        event(new VoteUpdated($request->lecture_id, $approveCount, $rejectCount, $opinion));

        return response()->json([
            'success' => true,
            'message' => 'บันทึกความเห็นเรียบร้อยแล้ว',
            'approve_count' => $approveCount,
            'reject_count' => $rejectCount
        ]);
    }

    public function getOpinions($lectureId)
    {
        $opinions = CommitteeOpinion::with('user', 'user.prefixName')
            ->where('meeting_agenda_lecture_id', $lectureId)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($opinions);
    }

    /**
     * ดึงความเห็นของผู้ใช้ปัจจุบันสำหรับ lecture ที่ระบุ
     */
    public function getCurrentUserOpinion($lectureId)
    {
        $opinion = CommitteeOpinion::with('user', 'user.prefixName')
            ->where('meeting_agenda_lecture_id', $lectureId)
            ->where('user_id', auth()->id())
            ->first();

        return response()->json($opinion);
    }
}
