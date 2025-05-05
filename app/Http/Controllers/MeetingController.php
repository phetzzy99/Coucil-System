<?php

namespace App\Http\Controllers;

use App\Models\CommitteeCategory;
use App\Models\CommitteeOpinion;
use App\Models\DocumentExport;
use App\Models\Meeting;
use App\Models\MeetingAgenda;
use App\Models\MeetingAgendaItems;
use App\Models\MeetingAgendaLecture;
use App\Models\MeetingAgendaSection;
use App\Models\MeetingFormat;
use App\Models\MeetingType;
use App\Models\RegulationMeeting;
use App\Models\RuleofMeeting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;

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

    public function lectureSectionDetail($id)
    {
        $lecture = MeetingAgendaLecture::with([
            'meetingAgendaSection',
            'meetingAgendaItems',
            'meetingAgendaSection.meetingAgenda'
        ])->findOrFail($id);

        // ดึงข้อมูล committee feedbacks
        $committeeFeedbacks = CommitteeOpinion::where('meeting_agenda_lecture_id', $id)
            ->with(['user', 'user.prefixName'])
            ->get();

        return view('admin.backend.pages.meeting_section_detail.lecture_detail', compact('lecture', 'committeeFeedbacks'));
    }

    public function exportToWord($id)
    {
        $meetingAgenda = MeetingAgenda::with(['meetingAgendaSections.meetingAgendaLectures', 'meetingAgendaSections.meetingAgendaItems'])
            ->findOrFail($id);

        // Create new Word document
        $phpWord = new PhpWord();

        // Add styles
        $titleStyle = ['bold' => true, 'size' => 18, 'name' => 'TH SarabunPSK', 'alignment' => 'center'];
        $headingStyle = ['bold' => true, 'size' => 16, 'name' => 'TH SarabunPSK', 'alignment' => 'center'];
        $normalStyle = ['size' => 16, 'name' => 'TH SarabunPSK', 'alignment' => 'left'];

        $section = $phpWord->addSection();

        // Add content to the Word document
        // $section->addText($meetingAgenda->meeting_agenda_title, $titleStyle);
        // $section->addText("ครั้งที่: {$meetingAgenda->meeting_agenda_number}/{$meetingAgenda->meeting_agenda_year}", $normalStyle);

    // Add user name and timestamp to top right corner
    $header = $section->addHeader();
    $table = $header->addTable();
    $table->addRow();
    $cell = $table->addCell(4500);
    $cell = $table->addCell(4500);
    $user = Auth::user();
    $cell->addText($user->prefixName->title.'' . $user->first_name . ' ' . $user->last_name, ['size' => 10, 'name' => 'TH SarabunPSK'], ['alignment' => 'right']);
    $cell->addText(date('Y-m-d H:i:s'), ['size' => 10, 'name' => 'TH SarabunPSK'], ['alignment' => 'right']);

    // Continue with the rest of the document
    $section->addText($meetingAgenda->meeting_agenda_title, $titleStyle);
    $section->addText("ครั้งที่: {$meetingAgenda->meeting_agenda_number}/{$meetingAgenda->meeting_agenda_year}", $titleStyle);

    // Format meeting date in Thai
    $meeting_date = Carbon::parse($meetingAgenda->meeting_agenda_date);
    $thai_months = [
        1 => 'มกราคม', 2 => 'กุมภาพันธ์', 3 => 'มีนาคม', 4 => 'เมษายน',
        5 => 'พฤษภาคม', 6 => 'มิถุนายน', 7 => 'กรกฎาคม', 8 => 'สิงหาคม',
        9 => 'กันยายน', 10 => 'ตุลาคม', 11 => 'พฤศจิกายน', 12 => 'ธันวาคม'
    ];

                $thai_date = $meeting_date->day . ' ' . $thai_months[$meeting_date->month] . ' พ.ศ. ' . ($meeting_date->year + 543);
                $section->addText("วันที่: {$thai_date}", $titleStyle);
                $section->addText("เวลา: {$meetingAgenda->meeting_agenda_time} น.", $titleStyle);
                $section->addText("สถานที่: {$meetingAgenda->meeting_location}", $titleStyle);

                $section->addTextBreak();

                // Add Sections, Lectures, and Items
        foreach ($meetingAgenda->meetingAgendaSections as $agendaSection) {
            // Add Section
            $section->addText($agendaSection->section_title, $headingStyle);

            if ($agendaSection->description) {
                \PhpOffice\PhpWord\Shared\Html::addHtml($section, $agendaSection->description);
            }

            // Add Lectures under this section
            foreach ($agendaSection->meetingAgendaLectures as $lecture) {
                $section->addText($lecture->lecture_title, $normalStyle);
                // $section->addText("วิทยากร: " . $lecture->meeting_agenda_lecture_by, $normalStyle);
                if ($lecture->content) {
                    // $section->addText($lecture->content, $normalStyle);
                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $lecture->content);
                }
                $section->addTextBreak();
            }

            // Add Items under this section
            foreach ($agendaSection->meetingAgendaItems as $item) {
                $section->addText($item->item_title, $normalStyle);
                if ($item->content) {
                    // $section->addText("รายละเอียด: " . $item->content, $normalStyle);
                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $item->content);
                }
                $section->addTextBreak();
            }

            $section->addTextBreak();
        }

        // Generate filename with timestamp
        $timestamp = Carbon::now()->format('Y-m-d_H-i-s');
        $filename = "meeting_agenda_{$id}_{$timestamp}.docx";

        // Create directory if it doesn't exist
        $directory = 'documents/meeting_exports/' . date('Y/m');
        if (!Storage::exists($directory)) {
            Storage::makeDirectory($directory);
        }

        // Save file to storage
        $filePath = $directory . '/' . $filename;
        $tempFile = tempnam(sys_get_temp_dir(), 'meeting_agenda_');
        $writer = IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save($tempFile);

        // Copy to storage
        Storage::put($filePath, file_get_contents($tempFile));

        // Save export history with file path
        DocumentExport::create([
            'user_id' => Auth::id(),
            'meeting_agenda_id' => $id,
            'file_name' => $filename,
            'file_path' => $filePath
        ]);

        // Save file to temporary location
        $tempFile = tempnam(sys_get_temp_dir(), 'meeting_agenda_');
        $writer = IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save($tempFile);

        // Prepare response for direct download
        return response()->download($tempFile, $filename)->deleteFileAfterSend(true);
    }

    public function updateCommitteeOpinionVisibility(Request $request, $id)
    {
        try {
            $lecture = MeetingAgendaLecture::findOrFail($id);
            $lecture->show_committee_opinion = $request->show_committee_opinion;
            $lecture->save();

            return response()->json([
                'success' => true,
                'message' => 'Committee opinion visibility updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating committee opinion visibility'
            ], 500);
        }
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

    public function ViewMeetingType($meeting_type_id = null)
    {
        $user = Auth::user();

        if ($meeting_type_id) {
            $selectedMeetingType = $user->meetingTypes()->where('meeting_type_id', $meeting_type_id)->first();

            if (!$selectedMeetingType) {
                return redirect()->back()->with('error', 'คุณไม่มีสิทธิ์เข้าถึงประเภทการประชุมนี้');
            }

            // เก็บ meeting_type_id ใน session
            session(['selected_meeting_type' => $meeting_type_id]);
        }

        // redirect กลับไปหน้าเดิม
        return redirect()->back();
    }
}
