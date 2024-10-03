<?php

namespace App\Http\Controllers;

use App\Models\RuleCategory;
use App\Models\RuleofMeeting;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RuleMeetingController extends Controller
{

    public function AllRulesOfMeeting()
    {
        $rules = RuleofMeeting::latest()->get();
        return view('admin.backend.pages.rule_meeting.all_rule_meeting', compact('rules'));
    }


    public function AddRulesOfMeeting()
    {
        $categories = RuleCategory::latest()->get();
        return view('admin.backend.pages.rule_meeting.add_rule_meeting',compact('categories'));
    }


    public function StoreRulesOfMeeting(Request $request)
    {

        $request->validate([
            'title' => 'required',
            'rule_category_id' => 'required',
            'pdf' => 'nullable|mimes:pdf',
        ]);

        $save_url = null;
        if ($request->hasFile('pdf')) {
            $file = $request->file('pdf');
            $filename = hexdec(uniqid()) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/rule_meeting/'), $filename);
            $save_url = 'uploads/rule_meeting/' . $filename;
        }

        RuleofMeeting::insert([
            'rule_category_id' => $request->rule_category_id,
            'title' => $request->title,
            'description' => $request->description,
            'pdf' => $save_url,
            'status' => 1,
            'created_at' => Carbon::now()
        ]);

        $notification = array(
            'message' => 'Rule meeting Added Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.rule.meeting')->with($notification);
    }


    public function UpdateStatusRulesOfMeeting($id)
    {
        $rule = RuleofMeeting::findOrFail($id);
        $rule->status = $rule->status == 1 ? 0 : 1;
        $rule->save();

        return response()->json(['status' => $rule->status, 'message' => 'Status Updated Successfully']);
    }

    public function EditRulesOfMeeting($id)
    {
        $categories = RuleCategory::latest()->get();
        $rule = RuleofMeeting::findOrFail($id);
        return view('admin.backend.pages.rule_meeting.edit_rule_meeting',compact('categories','rule'));
    }


    public function UpdateRulesOfMeeting(Request $request)
    {
        $rid = $request->rule_meeting_id;

        RuleofMeeting::findOrFail($rid)->update([
            'rule_category_id' => $request->rule_category_id,
            'title' => $request->title,
            'description' => $request->description,
            'updated_at' => Carbon::now(),
        ]);

        if ($request->hasFile('pdf')) {
            $file = $request->file('pdf');
            $filename = hexdec(uniqid()) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/rule_meeting'), $filename);

            $old_pdf = RuleofMeeting::findOrFail($rid)->pdf;
            if ($old_pdf && file_exists(public_path($old_pdf))) {
                @unlink(public_path($old_pdf));
            }

            RuleofMeeting::findOrFail($rid)->update([
                'pdf' => 'uploads/rule_meeting/' . $filename
            ]);
        }

        $notification = array(
            'message' => 'Rule meeting Updated Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.rule.meeting')->with($notification);
    }

    public function DeleteRulesOfMeeting($id)
    {
        $rule = RuleofMeeting::findOrFail($id);

        $old_pdf = $rule->pdf;

        if ($old_pdf) {
            @unlink(public_path($old_pdf));
        }

        $rule->delete();

        $notification = array(
            'message' => 'Rule meeting Deleted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }
}
