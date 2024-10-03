<?php

namespace App\Http\Controllers;

use App\Models\RegulationCategory;
use App\Models\RegulationMeeting;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RegulationMeetingController extends Controller
{

    public function AllRegulationOfMeeting()
    {
        $regulation = RegulationMeeting::latest()->get();
        return view('admin.backend.pages.regulation_meeting.all_regulation_meeting', compact('regulation'));
    }


    public function AddRegulationOfMeeting()
    {
        $categories = RegulationCategory::latest()->get();
        return view('admin.backend.pages.regulation_meeting.add_regulation_meeting', compact('categories'));
    }


    public function StoreRegulationOfMeeting(Request $request)
    {
        $request->validate([
            'regulation_title' => 'required',
            'regulation_category_id' => 'required',
            'regulation_pdf' => 'nullable|mimes:pdf|max:5000',
        ]);

        $save_url = null;
        if ($request->hasFile('regulation_pdf')) {
            $file = $request->file('regulation_pdf');
            $filename = hexdec(uniqid()) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/regulation_meeting/'), $filename);
            $save_url = 'uploads/regulation_meeting/' . $filename;
        }

        RegulationMeeting::insert([
            'regulation_category_id' => $request->regulation_category_id,
            'regulation_title' => $request->regulation_title,
            'description' => $request->description,
            'regulation_pdf' => $save_url,
            'status' => 1,
            'created_at' => Carbon::now()
        ]);

        $notification = array(
            'message' => 'Regulation meeting Added Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.regulation.meeting')->with($notification);
    }

    public function UpdateStatusRegulationMeeting($id)
    {
        $regulation = RegulationMeeting::findOrFail($id);
        $regulation->status = $regulation->status == 1 ? 0 : 1;
        $regulation->save();
        return response()->json(['status' => $regulation->status, 'message' => 'Status Updated Successfully']);
    }
}
