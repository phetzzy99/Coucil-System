<?php

namespace App\Http\Controllers;

use App\Models\MeetingAgenda;
use App\Models\MeetingAgendaItems;
use App\Models\MeetingAgendaLecture;
use App\Models\MeetingAgendaSection;
use App\Models\MeetingType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        return view('admin.backend.pages.meeting_agenda.add_meeting_agenda', compact('meeting_types'));
    }

    public function StoreMeetingAgenda(Request $request)
    {
        $request->validate([
            'meeting_type_id' => 'required',
            'meeting_agenda_title' => 'required',
            'meeting_agenda_number' => 'required',
            'meeting_agenda_year' => 'required',
            'meeting_agenda_date' => 'required',
            'meeting_agenda_time' => 'required',
            'meeting_location' => 'required'
        ]);

        $meeting_agenda = new MeetingAgenda();
        $meeting_agenda->meeting_type_id = $request->meeting_type_id;
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

        if ($meeting_agenda->save()) {
            $notification = array(
                'message' => 'เพิ่มระเบียบวาระการประชุมแล้ว',
                'alert-type' => 'success'
            );
            return redirect()->route('all.meeting.agenda')->with($notification);
        } else {
            return redirect()->back()->with([
                'message' => 'ไม่สามารถเพิ่มระเบียบวาระการประชุมได้',
                'alert-type' => 'error'
            ]);
        }
    }

    public function UpdateStatusMeetingAgenda($id)
    {
        $meeting_agenda = MeetingAgenda::findOrFail($id);
        $meeting_agenda->status = $meeting_agenda->status == 1 ? 0 : 1;
        $meeting_agenda->save();
        return response()->json(['status' => $meeting_agenda->status, 'message' => 'Status Updated Successfully']);
    }

    public function EditMeetingAgenda($id)
    {
        $meeting_agenda = MeetingAgenda::findOrFail($id);
        $meeting_types = MeetingType::latest()->get();
        return view('admin.backend.pages.meeting_agenda.edit_meeting_agenda', compact('meeting_agenda', 'meeting_types'));
    }

    public function UpdateMeetingAgenda(Request $request)
    {
        $request->validate([
            'meeting_type_id' => 'required',
            'meeting_agenda_title' => 'required',
            'meeting_agenda_number' => 'required',
            'meeting_agenda_year' => 'required',
            'meeting_agenda_date' => 'required',
            'meeting_agenda_time' => 'required',
            'meeting_location' => 'required'
        ]);
        $meeting_agenda = MeetingAgenda::findOrFail($request->id);
        try {
            $meeting_agenda->meeting_type_id = $request->meeting_type_id;
            $meeting_agenda->meeting_agenda_title = $request->meeting_agenda_title;
            $meeting_agenda->meeting_agenda_number = $request->meeting_agenda_number;
            $meeting_agenda->meeting_agenda_year = $request->meeting_agenda_year;
            $meeting_agenda->meeting_agenda_date = $request->meeting_agenda_date;
            $meeting_agenda->meeting_agenda_time = $request->meeting_agenda_time;
            $meeting_agenda->meeting_location = $request->meeting_location;
            $meeting_agenda->description = $request->description;
            $meeting_agenda->user_id = Auth::user()->id;
            $meeting_agenda->updated_at = Carbon::now();
            $meeting_agenda->save();
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
            'created_at' => Carbon::now()
        ]);

        $notification = array(
            'message' => 'เพิ่มหัวข้อส่วนของระเบียบวาระการประชุมแล้ว',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }

    public function SaveMeetingAgendaLecture(Request $request)
    {
        try {
            $meeting_agenda_lecture = new MeetingAgendaLecture();
            $meeting_agenda_lecture->meeting_agenda_id = $request->meeting_agenda_id;
            $meeting_agenda_lecture->meeting_agenda_section_id = $request->meeting_agenda_section_id;
            $meeting_agenda_lecture->lecture_title = $request->lecture_title;
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
        $request->validate([
            'course_id' => 'required',  // meeting_agenda
            'section_id' => 'required', // meeting_agenda_section
            'lecture_id' => 'required', // meeting_agenda_lecture
            'item_title' => 'required',
            'content' => 'required',
            // 'pdf' => 'nullable|mimes:pdf|max:5000',
        ]);

        $meeting_agenda_item = new MeetingAgendaItems();
        $meeting_agenda_item->meeting_agenda_id = $request->course_id;
        $meeting_agenda_item->meeting_agenda_section_id = $request->section_id;
        $meeting_agenda_item->meeting_agenda_lecture_id = $request->lecture_id;
        $meeting_agenda_item->item_title = $request->item_title;
        $meeting_agenda_item->content = $request->content;
        $meeting_agenda_item->created_at = Carbon::now();

        if ($meeting_agenda_item->save()) {
            $notification = array(
                'message' => 'เพิ่มรายการของระเบียบวาระการประชุมแล้ว',
                'alert-type' => 'success'
            );
            return redirect()->back()->with($notification);
        }

        $notification = array(
            'message' => 'ไม่สามารถเพิ่มรายการของระเบียบวาระการประชุมได้',
            'alert-type' => 'error'
        );
        return redirect()->back()->with($notification);
    }
}
