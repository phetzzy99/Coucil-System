<?php

namespace App\Http\Controllers;

use App\Models\MeetingFormat;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MeetingFormatController extends Controller
{

    public function AllMeetingFormat()
    {
        $m_format = MeetingFormat::orderBy('id', 'asc')->get();
        return view('admin.backend.pages.meeting_format.all_meeting_format', compact('m_format'));
    }

    public function AddMeetingFormat()
    {
        return view('admin.backend.pages.meeting_format.add_meeting_format');
    }

    public function StoreMeetingFormat(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);
        MeetingFormat::insert([
            'name' => $request->name,
            'description' => $request->description,
            'created_at' => Carbon::now()
        ]);
        $notification = array(
            'message' => 'Meeting Format Added Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.meeting.format')->with($notification);
    }

    public function EditMeetingFormat($id)
    {
        $m_format = MeetingFormat::findOrFail($id);
        return view('admin.backend.pages.meeting_format.edit_meeting_format', compact('m_format'));
    }

    public function UpdateMeetingFormat(Request $request)
    {
        $m_format_id = $request->id;
        $request->validate([
            'name' => 'required',
        ]);
        MeetingFormat::findOrFail($m_format_id)->update([
            'name' => $request->name,
            'description' => $request->description,
            'updated_at' => Carbon::now()
        ]);
        $notification = array(
            'message' => 'Meeting Format Updated Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.meeting.format')->with($notification);
    }

    public function DeleteMeetingFormat($id)
    {
        MeetingFormat::findOrFail($id)->delete();
        $notification = array(
            'message' => 'Meeting Format Deleted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }
}
