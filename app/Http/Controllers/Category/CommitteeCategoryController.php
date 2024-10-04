<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use App\Models\CommitteeCategory;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CommitteeCategoryController extends Controller
{
    public function AllCommitteeCategory()
    {
        $committee_category = CommitteeCategory::latest()->get();
        return view('admin.backend.pages.committee.committe_category.all_committee_category', compact('committee_category'));
    }

    public function AddCommitteeCategory()
    {
        return view('admin.backend.pages.committee.committe_category.add_committee_category');
    }

    public function StoreCommitteeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        CommitteeCategory::insert([
            'name' => $request->name,
            'description' => $request->description,
            'created_at' => Carbon::now()
        ]);

        $notification = array(
            'message' => 'Committee Category Added Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.committee.category')->with($notification);

    }

    public function EditCommitteeCategory($id)
    {
        $category = CommitteeCategory::findOrFail($id);
        return view('admin.backend.pages.committee.committe_category.edit_committee_category', compact('category'));
    }

    public function UpdateCommitteeCategory(Request $request)
    {
        $cat_id = $request->id; // 1

        $request->validate([
            'name' => 'required',
        ]);

        CommitteeCategory::findOrFail($cat_id)->update([
            'name' => $request->name,
            'description' => $request->description,
            'updated_at' => Carbon::now()
        ]);
        
        $notification = array(
            'message' => 'Committee Category Updated Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.committee.category')->with($notification);
    }

    public function DeleteCommitteeCategory($id)
    {
        CommitteeCategory::findOrFail($id)->delete();
        $notification = array(
            'message' => 'Committee Category Deleted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }
}
