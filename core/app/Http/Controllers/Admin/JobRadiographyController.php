<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobRadiography;
use Illuminate\Http\Request;

class JobRadiographyController extends Controller
{

    public function index()
    {
        $pageTitle = "Manage Job Radiography";
        $emptyMessage = "No data found";
        $jobRadiographies = JobRadiography::select('id', 'name', 'status')->latest()->paginate(getPaginate());
        return view('admin.job_radiography.index', compact('pageTitle', 'emptyMessage', 'jobRadiographies'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:120|unique:job_experiences'
        ]);
        $jobRadiography = new JobRadiography();
        $jobRadiography->name = $request->name;
        $jobRadiography->status = $request->status ? 1 : 2;
        $jobRadiography->save();
        $notify[] = ['success', 'Job Radiography type has been created'];
        return back()->withNotify($notify);
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:job_radiographies,id',
            'name' => 'required|max:120|unique:job_radiographies,name,'.$request->id,
        ]);
        $jobRadiography = JobRadiography::find($request->id);
        $jobRadiography->name = $request->name;
        $jobRadiography->status = $request->status ? 1 : 2;
        $jobRadiography->save();
        $notify[] = ['success', 'Job Radiography type has been updated'];
        return back()->withNotify($notify);
    }
}
