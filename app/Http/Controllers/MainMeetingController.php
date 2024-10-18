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

        return view('admin.backend.pages.main_meeting.edit_main_meeting', compact('main_meeting', 'meeting_types', 'committee_categories'));
    }

    public function UpdateMainMeeting(Request $request, $id)
    {
        $validatedData = $request->validate([
            'title' => 'required',
            'description' => 'nullable',
            'meeting_types_id' => 'required|array|exists:meeting_types,id',
            'committee_categories_id' => 'required|array|exists:committee_categories,id',
        ]);

        $mainMeeting = MainMeeting::findOrFail($id);
        $mainMeeting->update([
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
        ]);

        $mainMeeting->meetingTypes()->sync($validatedData['meeting_types_id']);
        $mainMeeting->committeeCategories()->sync($validatedData['committee_categories_id']);

        if ($mainMeeting) {
            $notification = array(
                'message' => 'แก้ไขข้อมูลสําเร็จ',
                'alert-type' => 'success'
            );
        } else {
            $notification = array(
                'message' => 'แก้ไขข้อมูลไม่สําเร็จ',
                'alert-type' => 'error'
            );
        }

        return redirect()->route('all.main.meeting')->with($notification);
    }
}
