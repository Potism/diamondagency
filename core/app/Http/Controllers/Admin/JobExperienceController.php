<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobExperience;
use Illuminate\Http\Request;

class JobExperienceController extends Controller
{
    
    public function index()
    {
        $pageTitle = "Manage Job Experience";
        $emptyMessage = "No data found";
        $jobExperiences = JobExperience::select('id', 'name', 'status')->latest()->paginate(getPaginate());
        return view('admin.job_experience.index', compact('pageTitle', 'emptyMessage', 'jobExperiences'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:120|unique:job_experiences'
        ]);
        $jobType = new JobExperience();
        $jobType->name = $request->name;
        $jobType->status = $request->status ? 1 : 2;
        $jobType->save();
        $notify[] = ['success', 'Job experience has been created'];
        return back()->withNotify($notify);
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:job_experiences,id',
            'name' => 'required|max:120|unique:job_experiences,name,'.$request->id,
        ]);
        $jobType = JobExperience::find($request->id);
        $jobType->name = $request->name;
        $jobType->status = $request->status ? 1 : 2;
        $jobType->save();
        $notify[] = ['success', 'Job experience has been updated'];
        return back()->withNotify($notify);
    }
}
