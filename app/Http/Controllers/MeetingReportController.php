<?php

namespace App\Http\Controllers;

use App\Models\CommitteeCategory;
use App\Models\MeetingReport;
use App\Models\MeetingType;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MeetingReportController extends Controller
{
    public function AllMeetingReport()
    {
        $meeting_reports = MeetingReport::latest()->get();
        return view('admin.backend.pages.meeting_report.all_meeting_report', compact('meeting_reports'));
    }

    public function AddMeetingReport()
    {
        $committees = CommitteeCategory::latest()->get();
        $meeting_types = MeetingType::latest()->get();
        return view('admin.backend.pages.meeting_report.add_meeting_report', compact('committees', 'meeting_types'));
    }

    public function StoreMeetingReport(Request $request)
    {
        $request->validate([
            'committee_category_id' => 'required',
            'meeting_type_id' => 'required',
            'title' => 'required',
            'meeting_no' => 'required',
            'date' => 'required',
            'time' => 'required',
            'year' => 'required',
            'location' => 'required',
            'pdf' => 'nullable|mimes:pdf|max:5000',
        ]);

        $save_url = null;
        if ($request->hasFile('pdf')) {
            $file = $request->file('pdf');
            $filename = hexdec(uniqid()) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/meeting_report/'), $filename);
            $save_url = 'uploads/meeting_report/' . $filename;
        }

        try {
            MeetingReport::insert([
                'committee_category_id' => $request->committee_category_id,
                'meeting_type_id' => $request->meeting_type_id,
                'title' => $request->title,
                'meeting_no' => $request->meeting_no,
                'date' => $request->date,
                'time' => $request->time,
                'year' => $request->year,
                'location' => $request->location,
                'pdf' => $save_url,
                'description' => $request->description,
                'created_at' => Carbon::now()
            ]);

            $notification = array(
                'message' => 'Meeting Report Added Successfully',
                'alert-type' => 'success'
            );
            return redirect()->route('all.meeting.report')->with($notification);
        } catch (\Throwable $th) {
            $notification = array(
                'message' => 'Something went wrong.',
                'alert-type' => 'danger'
            );
            return redirect()->back()->with($notification);
        }
    }

    public function EditMeetingReport($id)
    {
        $meeting_report = MeetingReport::findOrFail($id);
        $committees = CommitteeCategory::latest()->get();
        $meeting_types = MeetingType::latest()->get();
        return view('admin.backend.pages.meeting_report.edit_meeting_report', compact('meeting_report', 'committees', 'meeting_types'));
    }

    public function UpdateMeetingReport(Request $request)
    {
        $meeting_report = MeetingReport::findOrFail($request->id);
        $request->validate([
            'committee_category_id' => 'required',
            'meeting_type_id' => 'required',
            'title' => 'required',
            'meeting_no' => 'required',
            'date' => 'required',
            'time' => 'required',
            'year' => 'required',
            'location' => 'required',
            'pdf' => 'nullable|mimes:pdf|max:5000',
        ]);

        $old_pdf = MeetingReport::where('id', $request->id)->value('pdf');
        $save_url = $old_pdf;
        if ($request->hasFile('pdf')) {
            if ($old_pdf) {
                @unlink(public_path($old_pdf));
            }
            $file = $request->file('pdf');
            $filename = hexdec(uniqid()) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/meeting_report/'), $filename);
            $save_url = 'uploads/meeting_report/' . $filename;
        }

        try {
            MeetingReport::findOrFail($request->id)->update([
                'committee_category_id' => $request->committee_category_id,
                'meeting_type_id' => $request->meeting_type_id,
                'title' => $request->title,
                'meeting_no' => $request->meeting_no,
                'date' => $request->date,
                'time' => $request->time,
                'year' => $request->year,
                'pdf' => $save_url,
                'location' => $request->location,
                'description' => $request->description,
                'updated_at' => Carbon::now()
            ]);

            $notification = array(
                'message' => 'Meeting Report Updated Successfully',
                'alert-type' => 'success'
            );
            return redirect()->route('all.meeting.report')->with($notification);
        } catch (\Throwable $th) {
            $notification = array(
                'message' => 'Something went wrong.',
                'alert-type' => 'danger'
            );
            return redirect()->back()->with($notification);
        }
    }

    public function DeleteMeetingReport($id)
    {
        $meeting_report = MeetingReport::findOrFail($id);
        $old_pdf = $meeting_report->pdf;
        if ($old_pdf) {
            @unlink(public_path($old_pdf));
        }
        $meeting_report->delete();
        $notification = array(
            'message' => 'Meeting Report Deleted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }

}
