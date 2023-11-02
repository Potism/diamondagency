<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobShift;
use Illuminate\Http\Request;

class JobShiftController extends Controller
{
    public function index()
    {
        $pageTitle = "Manage Shift";
        $emptyMessage = "No data found";
        $jobShifts = JobShift::select('id', 'name', 'status')->latest()->paginate(getPaginate());
        return view('admin.job_shift.index', compact('pageTitle', 'emptyMessage', 'jobShifts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:120|unique:job_shifts'
        ]);
        $shift = new JobShift();
        $shift->name = $request->name;
        $shift->status = $request->status ? 1 : 2;
        $shift->save();
        $notify[] = ['success', 'Job shift has been created'];
        return back()->withNotify($notify);
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:job_shifts,id',
            'name' => 'required|max:120|unique:job_shifts,name,'.$request->id,
        ]);
        $shift = JobShift::findOrFail($request->id);
        $shift->name = $request->name;
        $shift->status = $request->status ? 1 : 2;
        $shift->save();
        $notify[] = ['success', 'Job shift has been updated'];
        return back()->withNotify($notify);
    }
}
