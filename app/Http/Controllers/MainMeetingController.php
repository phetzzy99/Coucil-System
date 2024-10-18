<?php

namespace App\Http\Controllers;

use App\Models\CommitteeCategory;
use App\Models\MainMeeting;
use App\Models\MeetingType;
use Illuminate\Http\Request;

class MainMeetingController extends Controller
{
    public function AllMainMeeting()
    {
        $main_meetings = MainMeeting::latest()->get();
        $meeting_types = MeetingType::all();
        $committee_categories = CommitteeCategory::all();

        return view('admin.backend.pages.main_meeting.all_main_meeting', compact('main_meetings', 'meeting_types', 'committee_categories'));
    }

    public function AddMainMeeting()
    {
        $meeting_types = MeetingType::all();
        $committee_categories = CommitteeCategory::all();

        return view('admin.backend.pages.main_meeting.add_main_meeting', compact('meeting_types', 'committee_categories'));
    }

    public function StoreMainMeeting(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required',
            'description' => 'nullable',
            'meeting_types_id' => 'required|array',
            'committee_categories_id' => 'required|array',
        ]);

        $main_meeting = new MainMeeting();
        $main_meeting->title = $validatedData['title'];
        $main_meeting->description = $validatedData['description'];
        $main_meeting->save();

        $main_meeting->meetingTypes()->sync($validatedData['meeting_types_id']);
        $main_meeting->committeeCategories()->sync($validatedData['committee_categories_id']);

        if ($main_meeting) {
            $notification = array(
                'message' => 'บันทึกข้อมูลสําเร็จ',
                'alert-type' => 'success'
            );
        } else {
            $notification = array(
                'message' => 'บันทึกข้อมูลไม่สําเร็จ',
                'alert-type' => 'error'
            );
        }

        return redirect()->route('all.main.meeting')->with($notification);
    }

    public function EditMainMeeting($id)
    {
        $main_meeting = MainMeeting::findOrFail($id);
        $meeting_types = MeetingType::all();
        $committee_categories = CommitteeCategory::all();

        return view('backend.main_meeting.edit', compact('main_meeting', 'meeting_types', 'committee_categories'));
    }

    public function UpdateMainMeeting(Request $request, $id)
    {
        $request->validate([
            'meeting_type' => 'required',
            'committee_category' => 'required',
            'meeting_name' => 'required',
            'meeting_date' => 'required',
            'meeting_start_time' => 'required',
            'meeting_end_time' => 'required',
            'meeting_location' => 'required',
            'meeting_description' => 'required',
        ]);

        $main_meeting = MainMeeting::findOrFail($id);
        $main_meeting->update([
            'meeting_type_id' => $request->meeting_type,
            'committee_category_id' => $request->committee_category,
            'meeting_name' => $request->meeting_name,
            'meeting_date' => $request->meeting_date,
            'meeting_start_time' => $request->meeting_start_time,
            'meeting_end_time' => $request->meeting_end_time,
            'meeting_location' => $request->meeting_location,
            'meeting_description' => $request->meeting_description,
        ]);

        if ($main_meeting) {
            $notification = array(
                'message' => 'บันทึกข้อมูลสําเร็จ',
                'alert-type' => 'success'
            );
        } else {
            $notification = array(
                'message' => 'บันทึกข้อมูลไม่สําเร็จ',
                'alert-type' => 'error'
            );
        }

        return redirect()->route('all.main.meeting')->with($notification);
    }
}
