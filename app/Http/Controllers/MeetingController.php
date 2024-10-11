<?php

namespace App\Http\Controllers;

use App\Models\CommitteeCategory;
use App\Models\Meeting;
use App\Models\MeetingAgenda;
use App\Models\MeetingAgendaItems;
use App\Models\MeetingAgendaLecture;
use App\Models\MeetingAgendaSection;
use App\Models\MeetingFormat;
use App\Models\MeetingType;
use App\Models\RegulationMeeting;
use App\Models\RuleofMeeting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MeetingController extends Controller
{

    public function MyMeetings(){
        $user_id = Auth::user()->id;
        $my_meetings = MeetingAgenda::latest()->get();

        return view('admin.backend.pages.meeting.my_all_meeting',compact('my_meetings'));

    }

    public function MeetingDetails($id){
        $my_meetings = MeetingAgenda::findOrFail($id);

        return view('admin.backend.pages.meeting.meeting_details',compact('my_meetings'));
    }

    public function AllMeeting(Request $request)
    {
        $meetings = Meeting::where('user_id',Auth::user()->id)->orderBy('id', 'desc')->get();
        return view('admin.backend.pages.meeting.all_meeting',compact('meetings'));
    }

    public function AddMeeting()
    {
        $meetingTypes = MeetingType::latest()->get();
        $committeeCategories = CommitteeCategory::latest()->get();
        $meetingFormats = MeetingFormat::latest()->get();
        $meetingAgendas = MeetingAgenda::latest()->get();
        $ruleOfMeetings = RuleofMeeting::latest()->get();
        $regulationMeetings = RegulationMeeting::latest()->get();
        $meetingAgendaLectures = MeetingAgendaLecture::latest()->get();
        $meetingAgendaSections = MeetingAgendaSection::latest()->get();
        $meetingAgendaItems = MeetingAgendaItems::latest()->get();

        return view('admin.backend.pages.meeting.add_meeting', compact(
            'meetingTypes',
            'committeeCategories',
            'meetingFormats',
            'meetingAgendas',
            'ruleOfMeetings',
            'regulationMeetings',
            'meetingAgendaLectures',
            'meetingAgendaSections',
            'meetingAgendaItems'
        ));
    }

    public function StoreMeeting(Request $request)
    {
        $meeting = new Meeting();
        $meeting->meeting_type_id = $request->input('meeting_type_id');
        $meeting->committee_category_id = $request->input('committee_category_id');
        $meeting->meeting_format_id = $request->input('meeting_format_id');
        $meeting->meeting_agenda_id = $request->input('meeting_agenda_id');
        $meeting->rule_of_meeting_id = $request->input('rule_of_meeting_id');
        $meeting->regulation_meeting_id = $request->input('regulation_meeting_id');
        $meeting->meeting_agenda_lecture_id = $request->input('meeting_agenda_lecture_id');
        $meeting->meeting_agenda_section_id = $request->input('meeting_agenda_section_id');
        $meeting->meeting_agenda_item_id = $request->input('meeting_agenda_item_id');
        $meeting->title = $request->input('title');
        $meeting->description = $request->input('description');
        $meeting->user_id = Auth::user()->id;
        $meeting->save();

        $notification = array(
            'message' => 'Meeting Added Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.meeting')->with($notification);
    }

    public function sectionAgendaItemDetail($id)
    {
        $meetingAgendaSection = MeetingAgendaSection::findOrFail($id);
        $meetingAgenda = $meetingAgendaSection->meetingAgenda;

        $meetingAgendaItems = MeetingAgendaItems::where('meeting_agenda_section_id', $id)
            ->with('meetingAgendaLecture')
            ->get();

        $meetingAgendaLectures = MeetingAgendaLecture::where('meeting_agenda_section_id', $id)->get();

        return view('admin.backend.pages.meeting_section_detail.section_agenda_item_detail',
            compact('meetingAgendaSection', 'meetingAgendaItems', 'meetingAgenda', 'meetingAgendaLectures'));
    }

    // public function sectionAgendaItemDetail($id)
    // {
    //     $meetingAgendaSection = MeetingAgendaSection::findOrFail($id);

    //     // ดึงข้อมูล MeetingAgenda ที่เกี่ยวข้องกับ MeetingAgendaSection
    //     $meetingAgenda = $meetingAgendaSection->meetingAgenda;

    //     // ดึงข้อมูล MeetingAgendaItems พร้อมกับ MeetingAgendaLecture ที่เกี่ยวข้อง
    //     $meetingAgendaItems = MeetingAgendaItems::where('meeting_agenda_section_id', $id)
    //         ->with('meetingAgendaLecture')
    //         ->get();

    //     $meetingAgendaLectures = MeetingAgendaLecture::where('meeting_agenda_id', $meetingAgenda->id)->get();

    //     return view('admin.backend.pages.meeting_section_detail.section_agenda_item_detail',
    //         compact('meetingAgendaSection', 'meetingAgendaItems', 'meetingAgenda', 'meetingAgendaLectures'));
    // }

    // public function ShowMeeting($id)
    // {
    //     $meetings = MeetingAgendaItems::with(['meetingAgendaSection','meetingAgendaSection'])
    //                    ->orderBy('created_at', 'asc')
    //                    ->paginate(10);
    //     return view('admin.backend.pages.meeting.show_meeting_all', compact('meetings'));

    //     // $meetings = Meeting::with([
    //     //     'meetingType',
    //     //     'committeeCategory',
    //     //     'meetingFormat',
    //     //     'meetingAgenda',
    //     //     'ruleOfMeeting',
    //     //     'regulationMeeting',
    //     //     'meetingAgendaSection',
    //     //     'meetingAgendaLecture',
    //     //     'meetingAgendaItems',
    //     //     'user'
    //     // ])->findOrFail($id);

    //     // return view('admin.backend.pages.meeting.show_meeting_all', compact('meetings'));
    // }
}
