<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JobType;

class JobTypeController extends Controller
{
    public function index()
    {
        $pageTitle = "Manage Type";
        $emptyMessage = "No data found";
        $jobTypes = JobType::select('id', 'name', 'status')->latest()->paginate(getPaginate());
        return view('admin.job_type.index', compact('pageTitle', 'emptyMessage', 'jobTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:120|unique:job_types'
        ]);
        $jobType = new JobType();
        $jobType->name = $request->name;
        $jobType->status = $request->status ? 1 : 2;
        $jobType->save();
        $notify[] = ['success', 'Type has been created'];
        return back()->withNotify($notify);
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:job_types,id',
            'name' => 'required|max:120|unique:job_types,name,'.$request->id,
        ]);
        $jobType = JobType::findOrFail($request->id);
        $jobType->name = $request->name;
        $jobType->status = $request->status ? 1 : 2;
        $jobType->save();
        $notify[] = ['success', 'Type has been updated'];
        return back()->withNotify($notify);
    }
}
