<?php

namespace App\Http\Controllers;

use App\Models\CommitteeCategory;
use App\Models\MeetingAgenda;
use App\Models\MeetingAgendaItems;
use App\Models\MeetingAgendaLecture;
use App\Models\MeetingAgendaSection;
use App\Models\MeetingFormat;
use App\Models\MeetingParticipant;
use App\Models\MeetingType;
use App\Models\RegulationMeeting;
use App\Models\RuleofMeeting;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MeetingAgendaController extends Controller
{

    public function AllMeetingAgenda(Request $request)
    {
        $user_id = Auth::user()->id;
        $meeting_agendas = MeetingAgenda::where('user_id', $user_id)->orderBy('id', 'desc')->get();
        return view('admin.backend.pages.meeting_agenda.all_meeting_agenda', compact('meeting_agendas'));
    }

    public function AddMeetingAgenda()
    {
        $meeting_types = MeetingType::latest()->get();
        $meeting_format = MeetingFormat::latest()->get();
        $committee_categories = CommitteeCategory::latest()->get();
        $rule_of_meetings = RuleofMeeting::latest()->get();
        $regulation_meetings = RegulationMeeting::latest()->get();

        return view('admin.backend.pages.meeting_agenda.add_meeting_agenda', compact('meeting_types', 'meeting_format', 'committee_categories', 'rule_of_meetings', 'regulation_meetings'));
    }

    public function StoreMeetingAgenda(Request $request)
    {
        $request->validate([
            'meeting_type_id' => 'required',
            'committee_category_id' => 'required',
            'meeting_format_id' => 'required',
            // 'rule_of_meeting_id' => 'required',
            'rule_of_meeting_ids' => 'required|array',
            'rule_of_meeting_ids.*' => 'exists:ruleof_meetings,id',
            'regulation_meeting_id' => 'required',

            'meeting_agenda_title' => 'required',
            'meeting_agenda_number' => 'required',
            'meeting_agenda_year' => 'required',
            'meeting_agenda_date' => 'required',
            'meeting_agenda_time' => 'required',
            'meeting_location' => 'required',
            'approval_deadline_date' => 'required|date',
            'approval_deadline_time' => 'required'
        ]);

        DB::beginTransaction();

        try {
                $meeting_agenda = new MeetingAgenda();
                $meeting_agenda->meeting_type_id = $request->meeting_type_id;
                $meeting_agenda->committee_category_id = $request->committee_category_id;
                $meeting_agenda->meeting_format_id = $request->meeting_format_id;
                // $meeting_agenda->rule_of_meeting_id = $request->rule_of_meeting_id;
                // $meeting_agenda->regulation_meeting_id = $request->regulation_meeting_id;
                $meeting_agenda->meeting_agenda_title = $request->meeting_agenda_title;
                $meeting_agenda->meeting_agenda_number = $request->meeting_agenda_number;
                $meeting_agenda->meeting_agenda_year = $request->meeting_agenda_year;
                $meeting_agenda->meeting_agenda_date = $request->meeting_agenda_date;
                $meeting_agenda->meeting_agenda_time = $request->meeting_agenda_time;
                $meeting_agenda->meeting_location = $request->meeting_location;
                $meeting_agenda->description = $request->description;
                $meeting_agenda->user_id = Auth::user()->id;
                $meeting_agenda->status = 1;
                $meeting_agenda->created_at = Carbon::now();

                // เพิ่ม approval_deadline
                // Set approval deadline
                $deadlineDate = $request->approval_deadline_date;
                $deadlineTime = $request->approval_deadline_time;
                $meeting_agenda->approval_deadline = Carbon::parse("$deadlineDate $deadlineTime");
                $meeting_agenda->save();

                if ($request->has('rule_of_meeting_ids')) {
                    $meeting_agenda->ruleOfMeeting()->sync($request->rule_of_meeting_ids);
                }

                if ($request->has('regulation_meeting_ids')) {
                    $meeting_agenda->regulationMeeting()->sync($request->regulation_meeting_ids);
                }

                DB::commit();

                $notification = array(
                    'message' => 'บันทึกระเบียบวาระการประชุมสำเร็จ',
                    'alert-type' => 'success'
                );

                return redirect()->route('all.meeting.agenda')->with($notification);

            } catch (\Exception $e) {
                DB::rollBack();
                // \Log::error('Error in StoreMeetingAgenda: ' . $e->getMessage());

                $notification = array(
                    'message' => 'เกิดข้อผิดพลาดในการบันทึกข้อมูล: ' . $e->getMessage(),
                    'alert-type' => 'error'
                );

                return redirect()->back()->withInput()->with($notification);
            }
                // $deadlineDays = Setting::where('key', 'approval_deadline_days')->first()->value ?? 7;
                // $meeting_agenda->approval_deadline = Carbon::parse($request->meeting_agenda_date)->addDays($deadlineDays);

        // if ($meeting_agenda->save()) {
        //     DB::commit();
        //     $notification = array(
        //         'message' => 'เพิ่มระเบียบวาระการประชุมแล้ว',
        //         'alert-type' => 'success'
        //     );
        //     // บันทึกความสัมพันธ์กับกฎการประชุม
        //     $meeting_agenda->ruleOfMeetings()->sync($request->rule_of_meeting_ids);
        //     return redirect()->route('all.meeting.agenda')->with($notification);
        // } else {
        //     return redirect()->back()->with([
        //         'message' => 'ไม่สามารถเพิ่มระเบียบวาระการประชุมได้',
        //         'alert-type' => 'error'
        //     ]);
        // }
    }

    public function UpdateStatusMeetingAgenda($id)
    {
        try {
            $meeting_agenda = MeetingAgenda::findOrFail($id);

            // สลับสถานะระหว่าง 0 และ 1
            $meeting_agenda->status = $meeting_agenda->status == 1 ? 0 : 1;

            $meeting_agenda->save();

            $statusText = $meeting_agenda->status == 1 ? 'เปิดใช้งาน' : 'ปิดใช้งาน';

            $notification = [
                'message' => "สถานะถูกเปลี่ยนเป็น {$statusText} เรียบร้อยแล้ว",
                'alert-type' => 'success'
            ];

            return response()->json([
                'status' => $meeting_agenda->status,
                'message' => $notification['message']
            ]);

        } catch (\Exception $e) {
            // \Log::error('Error in UpdateStatusMeetingAgenda: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'เกิดข้อผิดพลาดในการอัปเดตสถานะ: ' . $e->getMessage()
            ], 500);
        }
        // $meeting_agenda = MeetingAgenda::findOrFail($id);
        // $meeting_agenda->status = $meeting_agenda->status == 1 ? 0 : 1;
        // $meeting_agenda->save();
        // return response()->json(['status' => $meeting_agenda->status, 'message' => 'Status Updated Successfully']);
    }

    public function EditMeetingAgenda($id)
    {
        try {
            $meeting_agenda = MeetingAgenda::with('ruleOfMeeting', 'regulationMeeting')->findOrFail($id);
            $meeting_types = MeetingType::all();
            $committee_categories = CommitteeCategory::all();
            $meeting_formats = MeetingFormat::all();
            $rule_of_meetings = RuleofMeeting::all();
            $regulation_meetings = RegulationMeeting::all();

            return view('admin.backend.pages.meeting_agenda.edit_meeting_agenda', compact(
                'meeting_agenda',
                'meeting_types',
                'committee_categories',
                'meeting_formats',
                'rule_of_meetings',
                'regulation_meetings'
            ));
        } catch (\Exception $e) {
            // \Log::error('Error in EditMeetingAgenda: ' . $e->getMessage());

            $notification = [
                'message' => 'เกิดข้อผิดพลาดในการโหลดข้อมูล: ' . $e->getMessage(),
                'alert-type' => 'error'
            ];

            return redirect()->route('all.meeting.agenda')->with($notification);
        }
    }

    // public function EditMeetingAgenda($id)
    // {
    //     $meeting_agenda = MeetingAgenda::findOrFail($id);
    //     $meeting_types = MeetingType::latest()->get();
    //     return view('admin.backend.pages.meeting_agenda.edit_meeting_agenda', compact('meeting_agenda', 'meeting_types'));
    // }

    public function UpdateMeetingAgenda(Request $request)
    {
        $request->validate([
            'meeting_type_id' => 'required',
            'meeting_agenda_title' => 'required',
            'meeting_agenda_number' => 'required',
            'meeting_agenda_year' => 'required',
            'meeting_agenda_date' => 'required',
            'meeting_agenda_time' => 'required',
            'meeting_location' => 'required',
            'approval_deadline_date' => 'required|date',
            'approval_deadline_time' => 'required'
        ]);
        $meeting_agenda = MeetingAgenda::findOrFail($request->id);

        $approval_deadline = null;
        if ($request->approval_deadline_date && $request->approval_deadline_time) {
            $approval_deadline = Carbon::createFromFormat(
                'Y-m-d H:i',
                $request->approval_deadline_date . ' ' . $request->approval_deadline_time
            );
        }

        try {
            $meeting_agenda->meeting_type_id = $request->meeting_type_id;
            $meeting_agenda->committee_category_id = $request->committee_category_id;
            $meeting_agenda->meeting_format_id = $request->meeting_format_id;
            $meeting_agenda->meeting_agenda_title = $request->meeting_agenda_title;
            $meeting_agenda->meeting_agenda_number = $request->meeting_agenda_number;
            $meeting_agenda->meeting_agenda_year = $request->meeting_agenda_year;
            $meeting_agenda->meeting_agenda_date = $request->meeting_agenda_date;
            $meeting_agenda->meeting_agenda_time = $request->meeting_agenda_time;
            $meeting_agenda->meeting_location = $request->meeting_location;
            $meeting_agenda->description = $request->description;
            $meeting_agenda->approval_deadline = $approval_deadline;
            $meeting_agenda->user_id = Auth::user()->id;
            $meeting_agenda->updated_at = Carbon::now();
            $meeting_agenda->save();

            // อัพเดทความสัมพันธ์กับ RuleofMeeting
            // $meeting_agenda->ruleOfMeeting()->sync($request->rule_of_meeting_ids);

            if ($request->has('rule_of_meeting_ids')) {
                $meeting_agenda->ruleOfMeeting()->sync($request->rule_of_meeting_ids);
            } else {
                $meeting_agenda->ruleOfMeeting()->detach();
            }

            if ($request->has('regulation_meeting_ids')) {
                $meeting_agenda->regulationMeeting()->sync($request->regulation_meeting_ids);
            } else {
                $meeting_agenda->regulationMeeting()->detach();
            }

            $notification = array(
                'message' => 'แก้ไขระเบียบวาระการประชุมแล้ว',
                'alert-type' => 'success'
            );
            return redirect()->route('all.meeting.agenda')->with($notification);
        } catch (\Exception $e) {
            return redirect()->back()->with([
                'message' => 'ไม่สามารถแก้ไขระเบียบวาระการประชุมได้: ' . $e->getMessage(),
                'alert-type' => 'error'
            ]);
        }
    }

    public function DeleteMeetingAgenda($id)
    {
        try {
            MeetingAgenda::findOrFail($id)->delete();
            $notification = array(
                'message' => 'ลบระเบียบวาระการประชุมแล้ว',
                'alert-type' => 'success'
            );
            return redirect()->back()->with($notification);
        } catch (\Exception $e) {
            return back()->with('error', 'Unable to delete meeting agenda. Please try again.');
        }
    }

    public function AddMeetingAgendaLecture($id)
    {
        $meeting_agenda = MeetingAgenda::findOrFail($id);
        $meeting_section = MeetingAgendaSection::where('meeting_agenda_id', $id)->orderBy('id', 'asc')->get();
        $meeting_lectures = MeetingAgendaLecture::with('meetingAgendaItems')->where('meeting_agenda_id', $id)->latest()->get();

        return view('admin.backend.pages.section.add_meeting_agenda_lecture', compact('meeting_agenda', 'meeting_section', 'meeting_lectures'));
    }

    public function AddMeetingAgendaSection(Request $request)
    {
        $meeting_agenda_id = $request->id;

        MeetingAgendaSection::insert([
            'meeting_agenda_id' => $meeting_agenda_id,
            'section_title' => $request->section_title,
            'description' => $request->section_content,
            'created_at' => Carbon::now()
        ]);

        $notification = array(
            'message' => 'เพิ่มหัวข้อส่วนของระเบียบวาระการประชุมแล้ว',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }


    public function EditMeetingAgendaSection($id)
    {
        $meeting_agenda_section = MeetingAgendaSection::findOrFail($id);
        $meeting_agenda = MeetingAgenda::where('id', $meeting_agenda_section->meeting_agenda_id)->first();

        return view('admin.backend.pages.section.edit_meeting_agenda_section', compact('meeting_agenda_section', 'meeting_agenda'));
    }


    public function UpdateMeetingAgendaSection(Request $request)
    {
        $meeting_agenda_section = MeetingAgendaSection::findOrFail($request->id);
        $meeting_agenda_section->section_title = $request->section_title;
        $meeting_agenda_section->description = $request->description;
        $meeting_agenda_section->updated_at = Carbon::now();
        $meeting_agenda_section->save();

        $notification = array(
            'message' => 'แก้ไขหัวข้อส่วนของระเบียบวาระการประชุมแล้ว',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }
    public function DeleteMeetingAgendaSection($id)
    {
        try {
            MeetingAgendaSection::findOrFail($id)->delete();
            $notification = array(
                'message' => 'ลบหัวข้อส่วนของระเบียบวาระการประชุมแล้ว',
                'alert-type' => 'success'
            );
            return redirect()->back()->with($notification);
        } catch (\Exception $e) {
            return back()->with('error', 'Unable to delete meeting agenda section. Please try again.');
        }
    }

    public function SaveMeetingAgendaLecture(Request $request)
    {
        try {
            $meeting_agenda_lecture = new MeetingAgendaLecture();
            $meeting_agenda_lecture->meeting_agenda_id = $request->meeting_agenda_id;
            $meeting_agenda_lecture->meeting_agenda_section_id = $request->meeting_agenda_section_id;
            $meeting_agenda_lecture->lecture_title = $request->lecture_title;
            $meeting_agenda_lecture->content = $request->content;
            $meeting_agenda_lecture->created_at = Carbon::now();

            if ($meeting_agenda_lecture->save()) {
                return response()->json(['success' => 'เพิ่มหัวข้อเรื่องรายงานวาระการประชุมแล้ว'], 200);
            }

            return response()->json(['error' => 'เพิ่มหัวข้อเรื่องรายงานวาระการประชุมไม่สําเร็จ'], 400);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function EditMeetingAgendaLecture($id)
    {
        $meeting_agenda_lecture = MeetingAgendaLecture::findOrFail($id);
        return view('admin.backend.pages.lecture.edit_meeting_agenda_lecture', compact('meeting_agenda_lecture'));
    }

    public function UpdateMeetingAgendaLecture(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|exists:meeting_agenda_lectures,id',
                'lecture_title' => 'required|string|max:255',
                'content' => 'required|string',
            ]);

            $lecture = MeetingAgendaLecture::findOrFail($request->id);
            $lecture->lecture_title = $request->lecture_title;
            $lecture->content = $request->content;
            $lecture->save();

            $notification = array(
                'message' => 'Meeting Agenda Lecture Updated Successfully',
                'alert-type' => 'success'
            );

            return redirect()->back()->with($notification);
        } catch (\Exception $e) {
            // \Log::error('Error in UpdateMeetingAgendaLecture: ' . $e->getMessage());

            $notification = array(
                'message' => 'Error updating Meeting Agenda Lecture: ' . $e->getMessage(),
                'alert-type' => 'error'
            );

            return redirect()->back()->with($notification)->withInput();
        }
        // $meeting_lectures_id = $request->id;

        // MeetingAgendaLecture::findOrFail($meeting_lectures_id)->update([
        //     'lecture_title' => $request->lecture_title,
        //     'updated_at' => Carbon::now()
        // ]);

        // $notification = array(
        //     'message' => 'แก้ไขหัวข้อเรื่องรายงานวาระการประชุมแล้ว',
        //     'alert-type' => 'success'
        // );
        // return redirect()->back()->with($notification);
    }

    public function DeleteMeetingAgendaLecture($id)
    {
        try {
            MeetingAgendaLecture::findOrFail($id)->delete();
            $notification = array(
                'message' => 'ลบหัวข้อเรื่องรายงานวาระการประชุมแล้ว',
                'alert-type' => 'success'
            );
            return redirect()->back()->with($notification);
        } catch (\Exception $e) {
            return back()->with('error', 'Unable to delete meeting agenda lecture. Please try again.');
        }
    }

    public function SaveMeetingAgendaItem(Request $request)
    {
        try {
            $request->validate([
                'course_id' => 'required',
                'section_id' => 'required',
                'lecture_id' => 'required',
                'item_title' => 'required',
                // 'content' => 'required',
            ]);

            $meeting_agenda_item = new MeetingAgendaItems();
            $meeting_agenda_item->meeting_agenda_id = $request->course_id;
            $meeting_agenda_item->meeting_agenda_section_id = $request->section_id;
            $meeting_agenda_item->meeting_agenda_lecture_id = $request->lecture_id;
            $meeting_agenda_item->item_title = $request->item_title;
            $meeting_agenda_item->content = $request->content;
            $meeting_agenda_item->created_at = Carbon::now();

            if ($meeting_agenda_item->save()) {
                return response()->json([
                    'success' => true,
                    'message' => 'เพิ่มรายการของระเบียบวาระการประชุมแล้ว'
                ], 200);
            }

            return response()->json([
                'success' => false,
                'message' => 'ไม่สามารถเพิ่มรายการของระเบียบวาระการประชุมได้'
            ], 400);
        } catch (\Exception $e) {
            // \Log::error('Error in SaveMeetingAgendaItem: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาดในการประมวลผล: ' . $e->getMessage()
            ], 500);
        }
    }

    public function EditMeetingAgendaItem($id)
    {
        $meeting_agenda_item = MeetingAgendaItems::findOrFail($id);
        return view('admin.backend.pages.lecture.edit_meeting_agenda_item', compact('meeting_agenda_item'));
    }

    public function GetAgendaItem($lectureId)
    {
        $meeting_agenda_items = MeetingAgendaItems::where('meeting_agenda_lecture_id', $lectureId)->get();
        return response()->json($meeting_agenda_items);
    }

    public function getAgendaItems($itemId)
{
    try {
        $item = MeetingAgendaItems::findOrFail($itemId);
        return response()->json($item);
    } catch (\Exception $e) {
        // \Log::error('Error in getAgendaItem: ' . $e->getMessage());
        return response()->json(['error' => 'Failed to load agenda item'], 500);
    }
}

    public function UpdateAgendaItem(Request $request, $itemId)
    {
        try {
            $request->validate([
                'item_title' => 'required',
                // 'content' => 'required',
            ]);

            $item = MeetingAgendaItems::findOrFail($itemId);
            $item->item_title = $request->item_title;
            $item->content = $request->content;
            $item->save();

            return response()->json([
                'success' => true,
                'message' => 'Agenda item updated successfully',
                'meeting_agenda_lecture_id' => $item->meeting_agenda_lecture_id
            ]);
        } catch (\Exception $e) {
            // \Log::error('Error in updateAgendaItem: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update agenda item: ' . $e->getMessage()
            ], 500);
        }
    }


    public function DeleteAgendaItem($itemId)
    {
        $agendaItem = MeetingAgendaItems::findOrFail($itemId);

        $lectureId = $agendaItem->meeting_agenda_lecture_id;
        if ($agendaItem->delete()) {
            return response()->json([
                'success' => true,
                'message' => 'Agenda item deleted successfully',
                'meeting_agenda_lecture_id' => $lectureId
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete agenda item'
            ], 500);
        }

        // try {
        //     MeetingAgendaItems::findOrFail($itemId)->delete();
        //     return response()->json([
        //         'success' => true,
        //         'message' => 'Agenda item deleted successfully'
        //     ]);
        // } catch (\Exception $e) {
        //     // \Log::error('Error in DeleteMeetingAgendaItem: ' . $e->getMessage());
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Failed to delete agenda item: ' . $e->getMessage()
        //     ], 500);
        // }
    }
}
