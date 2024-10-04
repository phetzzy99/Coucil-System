<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use App\Models\MeetingType;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MeetingTypeController extends Controller
{
    public function AllMeetingType()
    {
        $meeting_type = MeetingType::latest()->get();
        return view('admin.backend.pages.meeting_report.meeting_type.all_meeting_type', compact('meeting_type'));
    }

    public function AddMeetingType()
    {
        return view('admin.backend.pages.meeting_report.meeting_type.add_meeting_type');
    }

    public function StoreMeetingType(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        try {
            MeetingType::insert([
                'name' => $request->name,
                'description' => $request->description,
                'created_at' => Carbon::now()
            ]);

            $notification = array(
                'message' => 'Meeting Type Added Successfully',
                'alert-type' => 'success'
            );
            return redirect()->route('all.meeting.type')->with($notification);
        } catch (\Exception $e) {
            return back()->with('error', 'Unable to add meeting type. Please try again.');
        }
    }

    public function EditMeetingType($id)
    {
        $meeting_type = MeetingType::findOrFail($id);
        return view('admin.backend.pages.meeting_report.meeting_type.edit_meeting_type', compact('meeting_type'));
    }

    public function UpdateMeetingType(Request $request)
    {
        $type_id = $request->id; // 1

        $request->validate([
            'name' => 'required',
        ]);

        try {
            MeetingType::findOrFail($type_id)->update([
                'name' => $request->name,
                'description' => $request->description,
                'updated_at' => Carbon::now()
            ]);

            $notification = array(
                'message' => 'Meeting Type Updated Successfully',
                'alert-type' => 'success'
            );
            return redirect()->route('all.meeting.type')->with($notification);
        } catch (\Exception $e) {
            return back()->with('error', 'Unable to update meeting type. Please try again.');
        }
    }

    public function DeleteMeetingType($id)
    {
        try {
            MeetingType::findOrFail($id)->delete();
            $notification = array(
                'message' => 'Meeting Type Deleted Successfully',
                'alert-type' => 'success'
            );
            return redirect()->back()->with($notification);
        } catch (\Exception $e) {
            return back()->with('error', 'Unable to delete meeting type. Please try again.');
        }
    }
}
