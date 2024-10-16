<?php

namespace App\Http\Controllers;

use App\Models\PrefixName;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PrefixNameController extends Controller
{
    public function AllPrefixName()
    {
        $prefixname = PrefixName::latest()->get();
        return view('admin.backend.pages.prefix.all_prefix_name', compact('prefixname'));
    }

    public function AddPrefixName()
    {
        return view('admin.backend.pages.prefix.add_prefix_name');
    }

    public function StorePrefixName(Request $request)
    {
        $request->validate([
            'prefix_name' => 'required',
        ]);
        PrefixName::insert([
            'title' => $request->prefix_name,
            'created_at' => Carbon::now(),
        ]);
        $notification = array(
            'message' => 'Prefix Name Added Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.prefix.name')->with($notification);
    }

    public function EditPrefixName($id)
    {
        $prefixname = PrefixName::findOrFail($id);
        return view('admin.backend.pages.prefix.edit_prefix_name', compact('prefixname'));
    }

    public function UpdatePrefixName(Request $request, $id)
    {
        $request->validate([
            'prefix_name' => 'required',
        ]);
        $prefixname = PrefixName::findOrFail($id);
        if ($prefixname === null) {
            abort(404);
        }
        try {
            $prefixname->title = $request->prefix_name;
            $prefixname->save();
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Something went wrong.']);
        }
        $notification = array(
            'message' => 'Prefix Name Updated Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.prefix.name')->with($notification);
    }

    public function DeletePrefixName($id)
    {
        $prefixname = PrefixName::findOrFail($id);
        $prefixname->delete();
        $notification = array(
            'message' => 'Prefix Name Deleted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }
}
