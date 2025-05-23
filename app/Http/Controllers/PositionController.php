<?php

namespace App\Http\Controllers;

use App\Models\Position;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PositionController extends Controller
{

    public function AllPosition()
    {
        $positions = Position::get();
        return view('admin.backend.pages.position.all_position', compact('positions'));
    }

    public function AddPosition()
    {
        $positions = Position::all();
        return view('admin.backend.pages.position.add_position', compact('positions'));
    }

    public function StorePosition(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:positions',
            // 'parent_id' => 'nullable|exists:positions,id',
        ], [
            'name.required' => 'กรุณากรอกชื่อตำแหน่ง',
            'name.unique' => 'ชื่อตำแหน่งนี้มีอยู่แล้วในระบบ',
            // 'parent_id.required' => 'กรุณากรอกผู้บังคับตำแหน่ง',
            // 'parent_id.unique' => 'ผู้บังคับตำแหน่งนี้มีอยู่แล้วในระบบ',
        ]);

        Position::create([
            'name' => $request->name,
            // 'parent_id' => $request->parent_id,
            'created_at' => Carbon::now()
        ]);

        $notification = array(
            'message' => 'เพิ่มตำแหน่งเรียบร้อยแล้ว',
            'alert-type' => 'success'
        );

        return redirect()->route('all.position')->with($notification);
    }

    public function EditPosition($id)
    {
        $positions = Position::all();
        $editPosition = Position::findOrFail($id);
        return view('admin.backend.pages.position.edit_position', compact('positions', 'editPosition'));
    }

    public function UpdatePosition(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:positions,name,' . $id,
            // 'parent_id' => 'nullable|exists:positions,id',
        ], [
            'name.required' => 'กรุณากรอกชื่อตำแหน่ง',
            'name.unique' => 'ชื่อตำแหน่งนี้มีอยู่แล้วในระบบ',
            // 'parent_id.required' => 'กรุณากรอกผู้บังคับตำแหน่ง',
            // 'parent_id.unique' => 'ผู้บังคับตำแหน่งนี้มีอยู่แล้วในระบบ',
        ]);

        Position::findOrFail($id)->update([
            'name' => $request->name,
            // 'parent_id' => $request->parent_id,
            'updated_at' => Carbon::now()
        ]);

        $notification = array(
            'message' => 'แก้ไขตำแหน่งเรียบร้อยแล้ว',
            'alert-type' => 'success'
        );

        return redirect()->route('all.position')->with($notification);
    }

    public function DeletePosition($id)
    {
        Position::findOrFail($id)->delete();
        $notification = array(
            'message' => 'ลบตำแหน่งเรียบร้อยแล้ว',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }

}
