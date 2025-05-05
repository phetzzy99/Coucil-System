<?php

namespace App\Http\Controllers;

use App\Models\ManagementCategory;
use Illuminate\Http\Request;

class ManagementCategoryController extends Controller
{
    public function AllManagementCategories()
    {
        $managementCategories = ManagementCategory::latest()->get();
        return view('admin.backend.pages.management_category.all_management_categories', compact('managementCategories'));
    }

    public function AddManagementCategory()
    {
        return view('admin.backend.pages.management_category.add_management_category');
    }

    public function StoreManagementCategory(Request $request)
    {
        $request->validate([
            'category_code' => 'required|unique:management_categories',
            'name' => 'required',
        ]);

        ManagementCategory::insert([
            'category_code' => $request->category_code,
            'name' => $request->name,
            'description' => $request->description,
            'created_at' => now(),
        ]);

        $notification = [
            'message' => 'เพิ่มหมวดหมู่การบริหารจัดการเรียบร้อยแล้ว',
            'alert-type' => 'success'
        ];

        return redirect()->route('all.management.categories')->with($notification);
    }

    public function EditManagementCategory($id)
    {
        $managementCategory = ManagementCategory::findOrFail($id);
        return view('admin.backend.pages.management_category.edit_management_category', compact('managementCategory'));
    }

    public function UpdateManagementCategory(Request $request)
    {
        $managementCategory_id = $request->id;

        $request->validate([
            'category_code' => 'required|unique:management_categories,category_code,' . $managementCategory_id,
            'name' => 'required',
        ]);

        ManagementCategory::findOrFail($managementCategory_id)->update([
            'category_code' => $request->category_code,
            'name' => $request->name,
            'description' => $request->description,
            'updated_at' => now(),
        ]);

        $notification = [
            'message' => 'อัปเดตหมวดหมู่การบริหารจัดการเรียบร้อยแล้ว',
            'alert-type' => 'success'
        ];

        return redirect()->route('all.management.categories')->with($notification);
    }

    public function DeleteManagementCategory($id)
    {
        ManagementCategory::findOrFail($id)->delete();

        $notification = [
            'message' => 'ลบหมวดหมู่การบริหารจัดการเรียบร้อยแล้ว',
            'alert-type' => 'success'
        ];

        return redirect()->back()->with($notification);
    }
}
