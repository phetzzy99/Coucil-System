<?php

namespace App\Http\Controllers;

use App\Models\CommitteeCategory;
use App\Models\MeetingAgenda;
use App\Models\MeetingAgendaLecture;
use App\Models\MeetingAgendaSection;
use App\Models\MeetingResolution;
use App\Models\MeetingType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MeetingResolutionController extends Controller
{
    public function AllMeetingResolution()
    {
        $meeting_resolutions = MeetingResolution::with([
            'meetingAgenda',
            'meetingAgendaSection',
            'committeeCategory',
            'meetingType'
        ])->latest()->get();

        return view('admin.backend.pages.meeting_report_sum.all_meeting_resolution', compact('meeting_resolutions'));
    }

    public function AddMeetingResolution()
    {
        $committeeCategories = CommitteeCategory::latest()->get();
        $meetingTypes = MeetingType::latest()->get();

        return view('admin.backend.pages.meeting_report_sum.add_meeting_resolution', compact('committeeCategories', 'meetingTypes'));
    }

    public function GetMeetingAgendaLectures(Request $request)
    {
        $sectionId = $request->section_id ?? $request->meeting_agenda_section_id;
        $lectures = MeetingAgendaLecture::where('meeting_agenda_section_id', $sectionId)->get();
        return response()->json($lectures);
    }

    public function GetMeetingAgendas(Request $request)
    {
        $committeeId = $request->committee_category_id;
        $meetingTypeId = $request->meeting_type_id;

        $meetingAgendas = MeetingAgenda::where('committee_category_id', $committeeId)
            ->where('meeting_type_id', $meetingTypeId)
            // ->where('is_admin_approved', true)
            ->get(['id', 'meeting_agenda_title']);

        return response()->json($meetingAgendas);
    }

    public function GetMeetingSections(Request $request)
    {
        $meetingAgendaId = $request->meeting_agenda_id;

        $sections = MeetingAgendaSection::where('meeting_agenda_id', $meetingAgendaId)
            ->get(['id', 'section_title']);

        return response()->json($sections);
    }

    public function StoreMeetingResolution(Request $request)
    {
        $request->validate([
            'committee_category_id' => 'required',
            'meeting_type_id' => 'required',
            'meeting_agenda_id' => 'required',
            'meeting_agenda_section_id' => 'required',
            'meeting_agenda_lecture_id' => 'nullable|exists:meeting_agenda_lectures,id',
            'proposer' => 'required|string|max:255',
            'resolution_text' => 'required',
            'resolution_date' => 'required|date',
            'resolution_status' => 'required',
            'task_title' => 'required|string|max:255',
            'responsible_person' => 'required|string|max:255',
            'task_status' => 'required|in:completed,in_progress,not_started',
            'report_date' => 'required|date',
            'document' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png|max:10240',
        ]);

        try {
            $documentPath = null;

            // จัดการกับไฟล์เอกสารประกอบการประชุม
            if ($request->hasFile('document')) {
                $file = $request->file('document');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('upload/meeting_documents/'), $fileName);
                $documentPath = 'upload/meeting_documents/' . $fileName;
            }

            MeetingResolution::create([
                'committee_category_id' => $request->committee_category_id,
                'meeting_type_id' => $request->meeting_type_id,
                'meeting_agenda_id' => $request->meeting_agenda_id,
                'meeting_agenda_section_id' => $request->meeting_agenda_section_id,
                'meeting_agenda_lecture_id' => $request->meeting_agenda_lecture_id,
                'proposer' => $request->proposer,
                'document' => $documentPath,
                'resolution_text' => $request->resolution_text,
                'resolution_date' => $request->resolution_date,
                'resolution_status' => $request->resolution_status,
                'task_title' => $request->task_title,
                'responsible_person' => $request->responsible_person,
                'task_status' => $request->task_status,
                'report_date' => $request->report_date,
                'user_id' => Auth::id(),
                'created_at' => Carbon::now()
            ]);

            $notification = array(
                'message' => 'เพิ่มมติการประชุมเรียบร้อยแล้ว',
                'alert-type' => 'success'
            );

            return redirect()->route('all.meeting.resolution')->with($notification);

        } catch (\Throwable $th) {
            $notification = array(
                'message' => 'เกิดข้อผิดพลาด: ' . $th->getMessage(),
                'alert-type' => 'error'
            );

            return redirect()->back()->with($notification)->withInput();
        }
    }

    public function EditMeetingResolution($id)
    {
        $resolution = MeetingResolution::findOrFail($id);
        $committeeCategories = CommitteeCategory::latest()->get();
        $meetingTypes = MeetingType::latest()->get();

        // ดึงข้อมูลรายงานการประชุมตามประเภทคณะกรรมการและประเภทการประชุม
        $meetingAgendas = MeetingAgenda::where('committee_category_id', $resolution->committee_category_id)
            ->where('meeting_type_id', $resolution->meeting_type_id)
            // ->where('is_admin_approved', true)
            ->get();

        // ดึงข้อมูลวาระการประชุมตามรายงานการประชุม
        $meetingSections = MeetingAgendaSection::where('meeting_agenda_id', $resolution->meeting_agenda_id)
            ->get();

        // ดึงข้อมูลหัวข้อย่อยตามวาระการประชุม
        $meetingLectures = MeetingAgendaLecture::where('meeting_agenda_section_id', $resolution->meeting_agenda_section_id)->get();

        return view('admin.backend.pages.meeting_report_sum.edit_meeting_resolution', compact(
            'resolution',
            'committeeCategories',
            'meetingTypes',
            'meetingAgendas',
            'meetingSections',
            'meetingLectures'
        ));
    }

    public function UpdateMeetingResolution(Request $request)
    {
        $request->validate([
            'committee_category_id' => 'required',
            'meeting_type_id' => 'required',
            'meeting_agenda_id' => 'required',
            'meeting_agenda_section_id' => 'required',
            'meeting_agenda_lecture_id' => 'nullable|exists:meeting_agenda_lectures,id',
            'proposer' => 'required|string|max:255',
            'resolution_text' => 'required',
            'resolution_date' => 'required|date',
            'resolution_status' => 'required',
            'task_title' => 'required|string|max:255',
            'responsible_person' => 'required|string|max:255',
            'task_status' => 'required|in:completed,in_progress,not_started',
            'report_date' => 'required|date',
            'document' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png|max:10240',
        ]);

        try {
            $resolution = MeetingResolution::findOrFail($request->id);
            $documentPath = $resolution->document;

            // จัดการกับไฟล์เอกสารประกอบการประชุม
            if ($request->hasFile('document')) {
                // ลบไฟล์เดิม (ถ้ามี)
                if ($resolution->document && file_exists(public_path($resolution->document))) {
                    unlink(public_path($resolution->document));
                }

                $file = $request->file('document');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('upload/meeting_documents/'), $fileName);
                $documentPath = 'upload/meeting_documents/' . $fileName;
            } elseif ($request->remove_document == '1') {
                // ลบไฟล์เดิมโดยไม่อัปโหลดไฟล์ใหม่
                if ($resolution->document && file_exists(public_path($resolution->document))) {
                    unlink(public_path($resolution->document));
                }
                $documentPath = null; // ตั้งค่าเป็น null เพื่อลบการอ้างอิงไฟล์ในฐานข้อมูล
            }

            $resolution->update([
                'committee_category_id' => $request->committee_category_id,
                'meeting_type_id' => $request->meeting_type_id,
                'meeting_agenda_id' => $request->meeting_agenda_id,
                'meeting_agenda_section_id' => $request->meeting_agenda_section_id,
                'meeting_agenda_lecture_id' => $request->meeting_agenda_lecture_id,
                'proposer' => $request->proposer,
                'document' => $documentPath,
                'resolution_text' => $request->resolution_text,
                'resolution_date' => $request->resolution_date,
                'resolution_status' => $request->resolution_status,
                'task_title' => $request->task_title,
                'responsible_person' => $request->responsible_person,
                'task_status' => $request->task_status,
                'report_date' => $request->report_date,
                'updated_at' => Carbon::now()
            ]);

            $notification = array(
                'message' => 'อัปเดตมติการประชุมเรียบร้อยแล้ว',
                'alert-type' => 'success'
            );

            return redirect()->route('all.meeting.resolution')->with($notification);

        } catch (\Throwable $th) {
            $notification = array(
                'message' => 'เกิดข้อผิดพลาด: ' . $th->getMessage(),
                'alert-type' => 'error'
            );

            return redirect()->back()->with($notification)->withInput();
        }
    }

    public function DeleteMeetingResolution($id)
    {
        try {
            $resolution = MeetingResolution::findOrFail($id);

            // ลบไฟล์เอกสารประกอบการประชุม (ถ้ามี)
            if ($resolution->document && file_exists(public_path($resolution->document))) {
                unlink(public_path($resolution->document));
            }

            $resolution->delete();

            $notification = array(
                'message' => 'ลบมติการประชุมเรียบร้อยแล้ว',
                'alert-type' => 'success'
            );

            return redirect()->back()->with($notification);

        } catch (\Throwable $th) {
            $notification = array(
                'message' => 'เกิดข้อผิดพลาด: ' . $th->getMessage(),
                'alert-type' => 'error'
            );

            return redirect()->back()->with($notification);
        }
    }

    public function SearchMeetingResolution()
    {
        $committeeCategories = CommitteeCategory::latest()->get();
        $meetingTypes = MeetingType::latest()->get();

        // ส่งค่าตัวแปรว่างเพื่อเตรียมไว้สำหรับผลการค้นหา
        $meeting_resolutions = null;

        return view('admin.backend.pages.meeting_report_sum.search_meeting_resolution', compact('committeeCategories', 'meetingTypes', 'meeting_resolutions'));
    }

    public function SearchMeetingResolutionResults(Request $request)
    {
        $query = MeetingResolution::with([
            'meetingAgenda',
            'meetingAgendaSection',
            'committeeCategory',
            'meetingType'
        ]);

        // กรองตามประเภทคณะกรรมการ
        if ($request->filled('committee_category_id')) {
            $query->where('committee_category_id', $request->committee_category_id);
        }

        // กรองตามประเภทการประชุม
        if ($request->filled('meeting_type_id')) {
            $query->where('meeting_type_id', $request->meeting_type_id);
        }

        // กรองตามรายงานการประชุม
        if ($request->filled('meeting_agenda_id')) {
            $query->where('meeting_agenda_id', $request->meeting_agenda_id);
        }

        // กรองตามวาระการประชุม
        if ($request->filled('meeting_agenda_section_id')) {
            $query->where('meeting_agenda_section_id', $request->meeting_agenda_section_id);
        }

        // เพิ่มในเงื่อนไขการค้นหา
if ($request->filled('meeting_agenda_lecture_id')) {
    $query->where('meeting_agenda_lecture_id', $request->meeting_agenda_lecture_id);
}

        // ค้นหาตามคำค้น (ในมติที่ประชุม)
        if ($request->filled('keyword')) {
            $query->where(function($q) use ($request) {
                $q->where('resolution_text', 'LIKE', '%' . $request->keyword . '%')
                  ->orWhere('task_title', 'LIKE', '%' . $request->keyword . '%')
                  ->orWhere('responsible_person', 'LIKE', '%' . $request->keyword . '%')
                  ->orWhere('proposer', 'LIKE', '%' . $request->keyword . '%')
                  ->orWhereHas('meetingAgenda', function($query) use ($request) {
                      $query->where('meeting_agenda_title', 'LIKE', '%' . $request->keyword . '%');
                  })
                  ->orWhereHas('meetingAgendaSection', function($query) use ($request) {
                      $query->where('section_title', 'LIKE', '%' . $request->keyword . '%');
                  });
            });
        }

        // กรองตามสถานะงาน
        if ($request->filled('task_status')) {
            $query->where('task_status', $request->task_status);
        }

        // กรองตามช่วงวันที่มีมติ
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('resolution_date', [$request->start_date, $request->end_date]);
        } elseif ($request->filled('start_date')) {
            $query->where('resolution_date', '>=', $request->start_date);
        } elseif ($request->filled('end_date')) {
            $query->where('resolution_date', '<=', $request->end_date);
        }

        $meeting_resolutions = $query->latest()->get();
        $committeeCategories = CommitteeCategory::latest()->get();
        $meetingTypes = MeetingType::latest()->get();

        // ส่งค่ากลับไปยังหน้า search_meeting_resolution แทนที่จะไปยังหน้า search_meeting_resolution_results
        return view('admin.backend.pages.meeting_report_sum.search_meeting_resolution', compact('meeting_resolutions', 'committeeCategories', 'meetingTypes', 'request'));
    }
}
