<?php

namespace App\Http\Controllers;

use App\Models\ManagementCategory;
use App\Models\ManagementKeyword;
use Illuminate\Http\Request;

class ManagementKeywordController extends Controller
{
    public function AllManagementKeywords()
    {
        $managementKeywords = ManagementKeyword::with('managementCategory')->latest()->get();
        return view('admin.backend.pages.management_keyword.all_management_keywords', compact('managementKeywords'));
    }

    public function AddManagementKeyword()
    {
        $managementCategories = ManagementCategory::orderBy('category_code', 'asc')->get();
        return view('admin.backend.pages.management_keyword.add_management_keyword', compact('managementCategories'));
    }

    public function StoreManagementKeyword(Request $request)
    {
        $request->validate([
            'management_category_id' => 'required',
            'keyword_title' => 'required',
        ]);

        ManagementKeyword::insert([
            'management_category_id' => $request->management_category_id,
            'keyword_title' => $request->keyword_title,
            'description' => $request->description,
            'created_at' => now(),
        ]);

        $notification = [
            'message' => 'เพิ่ม Keyword หมวดด้านการบริหารเรียบร้อยแล้ว',
            'alert-type' => 'success'
        ];

        return redirect()->route('all.management.keywords')->with($notification);
    }

    public function EditManagementKeyword($id)
    {
        $managementKeyword = ManagementKeyword::findOrFail($id);
        $managementCategories = ManagementCategory::orderBy('category_code', 'asc')->get();
        return view('admin.backend.pages.management_keyword.edit_management_keyword', compact('managementCategories', 'managementKeyword'));
    }

    public function UpdateManagementKeyword(Request $request)
    {
        $managementKeyword_id = $request->id;

        $request->validate([
            'management_category_id' => 'required',
            'keyword_title' => 'required',
        ]);

        ManagementKeyword::findOrFail($managementKeyword_id)->update([
            'management_category_id' => $request->management_category_id,
            'keyword_title' => $request->keyword_title,
            'description' => $request->description,
            'updated_at' => now(),
        ]);

        $notification = [
            'message' => 'อัปเดต Keyword หมวดด้านการบริหารเรียบร้อยแล้ว',
            'alert-type' => 'success'
        ];

        return redirect()->route('all.management.keywords')->with($notification);
    }

    public function DeleteManagementKeyword($id)
    {
        ManagementKeyword::findOrFail($id)->delete();

        $notification = [
            'message' => 'ลบ Keyword หมวดด้านการบริหารเรียบร้อยแล้ว',
            'alert-type' => 'success'
        ];

        return redirect()->back()->with($notification);
    }
}
