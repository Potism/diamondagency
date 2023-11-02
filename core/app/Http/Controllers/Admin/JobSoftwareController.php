<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobSoftware;
use Illuminate\Http\Request;

class JobSoftwareController extends Controller
{

    public function index()
    {
        $pageTitle = "Manage Job Software";
        $emptyMessage = "No data found";
        $jobSoftwares = JobSoftware::select('id', 'name', 'status')->latest()->paginate(getPaginate());
        return view('admin.job_software.index', compact('pageTitle', 'emptyMessage', 'jobSoftwares'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:120|unique:job_experiences'
        ]);
        $jobSoftware = new JobSoftware();
        $jobSoftware->name = $request->name;
        $jobSoftware->status = $request->status ? 1 : 2;
        $jobSoftware->save();
        $notify[] = ['success', 'Job Software type has been created'];
        return back()->withNotify($notify);
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:job_software,id',
            'name' => 'required|max:120|unique:job_software,name,'.$request->id,
        ]);
        $jobSoftware = JobSoftware::find($request->id);
        $jobSoftware->name = $request->name;
        $jobSoftware->status = $request->status ? 1 : 2;
        $jobSoftware->save();
        $notify[] = ['success', 'Job Software type has been updated'];
        return back()->withNotify($notify);
    }
}
