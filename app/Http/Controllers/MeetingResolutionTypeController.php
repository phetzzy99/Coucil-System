<?php

namespace App\Http\Controllers;

use App\Models\CommitteeCategory;
use App\Models\MeetingType;
use App\Models\MeetingResolutionType;
use App\Models\ManagementCategory;
use App\Models\ManagementKeyword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MeetingResolutionTypeController extends Controller
{
    public function AllMeetingResolutionTypes()
    {
        $resolutionTypes = MeetingResolutionType::with(['managementCategory', 'meetingType'])->latest()->get();
        return view('admin.backend.pages.meeting_resolution_type.all_meeting_resolution_types', compact('resolutionTypes'));
    }

    public function AddMeetingResolutionType()
    {
        $committeeCategories = CommitteeCategory::latest()->get();
        $meetingTypes = MeetingType::latest()->get();
        $managementCategories = ManagementCategory::orderBy('category_code', 'asc')->get();
        $managementKeywords = ManagementKeyword::with('managementCategory')->latest()->get();
        return view('admin.backend.pages.meeting_resolution_type.add_meeting_resolution_type', compact('committeeCategories', 'meetingTypes', 'managementCategories', 'managementKeywords'));
    }

    public function StoreMeetingResolutionType(Request $request)
    {
        $request->validate([
            'management_category_id' => 'required',
            'meeting_type_id' => 'required',
            'meeting_no' => 'required|string|max:10',
            'meeting_year' => 'required|string|max:4',
            'meeting_date' => 'required|date',
            'name' => 'required|string|max:255',
            'agenda_title' => 'required|string|max:255',
            'resolution_text' => 'required|string',
            'task_status' => 'required|in:completed,in_progress,not_started',
            'document' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png|max:10240',
        ]);

        try {
            $documentPath = null;

            // จัดการกับไฟล์เอกสารแนบ
            if ($request->hasFile('document')) {
                $file = $request->file('document');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('upload/meeting_documents/'), $fileName);
                $documentPath = 'upload/meeting_documents/' . $fileName;
            }

            // หา management_keyword_id จากชื่อ keyword ที่เลือก
            $keyword = ManagementKeyword::where('keyword_title', $request->name)->first();
            $management_keyword_id = $keyword ? $keyword->id : null;

            MeetingResolutionType::create([
                'management_category_id' => $request->management_category_id,
                'management_keyword_id' => $management_keyword_id,
                'meeting_type_id' => $request->meeting_type_id,
                'meeting_no' => $request->meeting_no,
                'meeting_year' => $request->meeting_year,
                'meeting_date' => $request->meeting_date,
                'name' => $request->name,
                'agenda_title' => $request->agenda_title,
                'resolution_text' => $request->resolution_text,
                'task_status' => $request->task_status,
                'document' => $documentPath,
                'created_at' => Carbon::now()
            ]);

            $notification = array(
                'message' => 'เพิ่มรายงานมติที่ประชุมเรียบร้อยแล้ว',
                'alert-type' => 'success'
            );

            return redirect()->route('all.meeting.resolution.types')->with($notification);

        } catch (\Throwable $th) {
            $notification = array(
                'message' => 'เกิดข้อผิดพลาด: ' . $th->getMessage(),
                'alert-type' => 'error'
            );

            return redirect()->back()->with($notification)->withInput();
        }
    }

    public function EditMeetingResolutionType($id)
    {
        $resolutionType = MeetingResolutionType::findOrFail($id);
        $committeeCategories = CommitteeCategory::latest()->get();
        $meetingTypes = MeetingType::latest()->get();
        $managementCategories = ManagementCategory::orderBy('category_code', 'asc')->get();
        $managementKeywords = ManagementKeyword::with('managementCategory')->latest()->get();
        return view('admin.backend.pages.meeting_resolution_type.edit_meeting_resolution_type', compact('resolutionType', 'committeeCategories', 'meetingTypes', 'managementCategories', 'managementKeywords'));
    }

    public function UpdateMeetingResolutionType(Request $request)
    {
        $request->validate([
            'management_category_id' => 'required',
            'meeting_type_id' => 'required',
            'meeting_no' => 'required|string|max:10',
            'meeting_year' => 'required|string|max:4',
            'meeting_date' => 'required|date',
            'name' => 'required|string|max:255',
            'agenda_title' => 'required|string|max:255',
            'resolution_text' => 'required|string',
            'task_status' => 'required|in:completed,in_progress,not_started',
            'document' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png|max:10240',
        ]);

        try {
            $resolutionType = MeetingResolutionType::findOrFail($request->id);

            // หา management_keyword_id จากชื่อ keyword ที่เลือก
            $keyword = ManagementKeyword::where('keyword_title', $request->name)->first();
            $management_keyword_id = $keyword ? $keyword->id : null;

            $data = [
                'management_category_id' => $request->management_category_id,
                'management_keyword_id' => $management_keyword_id,
                'meeting_type_id' => $request->meeting_type_id,
                'meeting_no' => $request->meeting_no,
                'meeting_year' => $request->meeting_year,
                'meeting_date' => $request->meeting_date,
                'name' => $request->name,
                'agenda_title' => $request->agenda_title,
                'resolution_text' => $request->resolution_text,
                'task_status' => $request->task_status,
            ];

            // จัดการกับไฟล์เอกสารแนบ
            if ($request->hasFile('document')) {
                // ลบไฟล์เดิม (ถ้ามี)
                if ($resolutionType->document && file_exists(public_path($resolutionType->document))) {
                    unlink(public_path($resolutionType->document));
                }

                $file = $request->file('document');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('upload/meeting_documents/'), $fileName);
                $data['document'] = 'upload/meeting_documents/' . $fileName;
            }

            $resolutionType->update($data);

            $notification = array(
                'message' => 'อัปเดตรายงานมติที่ประชุมเรียบร้อยแล้ว',
                'alert-type' => 'success'
            );

            return redirect()->route('all.meeting.resolution.types')->with($notification);

        } catch (\Throwable $th) {
            $notification = array(
                'message' => 'เกิดข้อผิดพลาด: ' . $th->getMessage(),
                'alert-type' => 'error'
            );

            return redirect()->back()->with($notification)->withInput();
        }
    }

    public function DeleteMeetingResolutionType($id)
    {
        try {
            MeetingResolutionType::findOrFail($id)->delete();

            $notification = array(
                'message' => 'ลบประเภทองค์มติการประชุมเรียบร้อยแล้ว',
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

    // เมธอดสำหรับการดึงข้อมูลแบบ AJAX
    public function GetMeetingResolutionTypes(Request $request)
    {
        $committeeId = $request->committee_category_id;
        $meetingTypeId = $request->meeting_type_id;

        $resolutionTypes = MeetingResolutionType::where('committee_category_id', $committeeId)
            ->where('meeting_type_id', $meetingTypeId)
            ->get(['id', 'name']);

        return response()->json($resolutionTypes);
    }

    /**
     * แสดงหน้าค้นหาข้อมูลมติที่ประชุม
     */
    public function SearchMeetingResolutionTypes(Request $request)
    {
        $managementCategories = ManagementCategory::latest()->get();
        $managementKeywords = ManagementKeyword::latest()->get();
        $meetingTypes = MeetingType::latest()->get();

        $results = null;

        // ตรวจสอบว่ามีการส่งพารามิเตอร์การค้นหามาหรือไม่
        if ($request->filled('keyword') || $request->filled('management_category_id') ||
            $request->filled('meeting_type_id') || $request->filled('meeting_no') ||
            $request->filled('meeting_year') || $request->filled('meeting_date')) {

            $query = MeetingResolutionType::with(['managementCategory', 'managementKeyword', 'meetingType']);

            // กรองตามหมวดด้านการบริหาร
            if ($request->filled('management_category_id')) {
                $query->where('management_category_id', $request->management_category_id);
            }

            // กรองตามประเภทการประชุม
            if ($request->filled('meeting_type_id')) {
                $query->where('meeting_type_id', $request->meeting_type_id);
            }

            // กรองตามปีการประชุม
            if ($request->filled('meeting_year')) {
                $query->where('meeting_year', $request->meeting_year);
            }

            // กรองตามครั้งที่ประชุม
            if ($request->filled('meeting_no')) {
                $query->where('meeting_no', $request->meeting_no);
            }

            // กรองตามวันที่ประชุม
            if ($request->filled('meeting_date')) {
                $query->whereDate('meeting_date', $request->meeting_date);
            }

            // ค้นหาด้วย keyword
            if ($request->filled('keyword')) {
                $keyword = $request->keyword;
                $query->where(function($q) use ($keyword) {
                    $q->where('name', 'LIKE', "%{$keyword}%")
                      ->orWhere('agenda_title', 'LIKE', "%{$keyword}%")
                      ->orWhere('resolution_text', 'LIKE', "%{$keyword}%")
                      ->orWhereHas('managementCategory', function($query) use ($keyword) {
                          $query->where('category_code', 'LIKE', "%{$keyword}%");
                      });
                });
            }

            $results = $query->latest()->get();
        }

        return view('admin.backend.pages.meeting_resolution_type.search_meeting_resolution_types',
            compact('managementCategories', 'managementKeywords', 'meetingTypes', 'results'));
    }
}
