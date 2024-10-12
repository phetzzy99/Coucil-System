<?php

namespace App\Http\Controllers;

use App\Models\MeetingAgenda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MeetingApprovalController extends Controller
{
    public function AllMeetingApproval()
    {
        $user_id = Auth::user()->id;
        $my_meetings = MeetingAgenda::where('status', 1)->get();
        // $my_meetings = MeetingAgenda::where('status', 1)->where('user_id', $user_id)->get();

        return view('admin.backend.pages.meeting_approval.all_meeting_approval',compact('my_meetings'));
    }

    public function MeetingApprovalDetail($id)
    {
        $my_meetings = MeetingAgenda::findOrFail($id);
        return view('admin.backend.pages.meeting_approval.meeting_approval_detail',compact('my_meetings'));
        // return view('admin.backend.pages.meeting_approval.meeting_approval_detail_2',compact('my_meetings'));
        // return view('admin.backend.pages.meeting_approval.meeting_approval_detail_3',compact('my_meetings'));
    }
}
