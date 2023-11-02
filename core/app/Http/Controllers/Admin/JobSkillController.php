<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobSkill;
use Illuminate\Http\Request;

class JobSkillController extends Controller
{
    
    public function index()
    {
        $pageTitle = "Manage job skill";
        $emptyMessage = "No data found";
        $jobSkills = JobSkill::select('id', 'name', 'status')->latest()->paginate(getPaginate());
        return view('admin.job_skill.index', compact('pageTitle', 'emptyMessage', 'jobSkills'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:120|unique:job_skills'
        ]);
        $jobSkill = new JobSkill();
        $jobSkill->name = $request->name;
        $jobSkill->status = $request->status ? 1 : 2;
        $jobSkill->save();
        $notify[] = ['success', 'Job skill has been created'];
        return back()->withNotify($notify);
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:job_skills,id',
            'name' => 'required|max:120|unique:job_skills,name,'.$request->id,
        ]);
        $jobSkill = JobSkill::findOrFail($request->id);
        $jobSkill->name = $request->name;
        $jobSkill->status = $request->status ? 1 : 2;
        $jobSkill->save();
        $notify[] = ['success', 'Job skill has been updated'];
        return back()->withNotify($notify);
    }
}
