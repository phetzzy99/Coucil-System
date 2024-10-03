<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use App\Models\RuleCategory;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RuleCategoryController extends Controller
{
    public function AllRuleCategory()
    {
        $r_cat = RuleCategory::latest()->get();
        return view('admin.backend.pages.rule_meeting.rule_category.all_rule_category', compact('r_cat'));
    }

    public function AddRuleCategory()
    {
        return view('admin.backend.pages.rule_meeting.rule_category.add_rule_category');
    }

    public function StoreRulesCategory(Request $request)
    {
        $request->validate([
            'rule_category_name' => 'required',
        ]);
        RuleCategory::insert([
            'rule_category_name' => $request->rule_category_name,
            'description' => $request->description,
            'created_at' => Carbon::now()
        ]);

        $notification = array(
            'message' => 'Rule Category Added Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('rule.category')->with($notification);
    }

    public function EditRulesCategory($id)
    {
        $category = RuleCategory::findOrFail($id);
        return view('admin.backend.pages.rule_meeting.rule_category.edit_rule_category', compact('category'));
    }

    public function UpdateRulesCategory(Request $request)
    {
        $cat_id = $request->id;

        $request->validate([
            'rule_category_name' => 'required',
        ]);
        RuleCategory::findOrFail($cat_id)->update([
            'rule_category_name' => $request->rule_category_name,
            'description' => $request->description,
            'updated_at' => Carbon::now()
        ]);
        $notification = array(
            'message' => 'Rule Category Updated Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('rule.category')->with($notification);
    }

    public function DeleteRulesCategory($id)
    {
        RuleCategory::findOrFail($id)->delete();
        $notification = array(
            'message' => 'Rule Category Deleted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }
}
