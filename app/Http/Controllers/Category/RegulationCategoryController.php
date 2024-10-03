<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use App\Models\RegulationCategory;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RegulationCategoryController extends Controller
{
    public function AllRegulationCategory()
    {
        $reg_cat = RegulationCategory::latest()->get();
        return view('admin.backend.pages.regulation_meeting.regulation_category.all_regulation_category', compact('reg_cat'));
    }

    public function AddRegulationCategory()
    {
        return view('admin.backend.pages.regulation_meeting.regulation_category.add_regulation_category');
    }

    public function StoreRegulationCategory(Request $request)
    {
        $request->validate([
            'regulation_category_name' => 'required',
        ]);
        RegulationCategory::insert([
            'regulation_category_name' => $request->regulation_category_name,
            'description' => $request->description,
            'created_at' => Carbon::now()
        ]);

        $notification = array(
            'message' => 'Regulation Category Added Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.regulation.category')->with($notification);
    }

    public function EditRegulationCategory($id)
    {
        $category = RegulationCategory::findOrFail($id);
        return view('admin.backend.pages.regulation_meeting.regulation_category.edit_regulation_category', compact('category'));
    }

    public function UpdateRegulationCategory(Request $request)
    {
        $cat_id = $request->id; // 1

        $request->validate([
            'regulation_category_name' => 'required',
        ]);

        RegulationCategory::findOrFail($cat_id)->update([
            'regulation_category_name' => $request->regulation_category_name,
            'description' => $request->description,
            'updated_at' => Carbon::now()
        ]);
        $notification = array(
            'message' => 'Regulation Category Updated Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.regulation.category')->with($notification);
    }

    public function DeleteRegulationCategory($id)
    {
        RegulationCategory::findOrFail($id)->delete();
        $notification = array(
            'message' => 'Regulation Category Deleted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }
}
