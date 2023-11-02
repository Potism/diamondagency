<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\JobApply;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\JobSkill;
use App\Models\JobSoftware;
use App\Models\GeneralSetting;

class JobController extends Controller
{
    public function index()
    {
        $pageTitle= "Manage All Job";
        $emptyMessage = "No data found";
        $jobs = Job::latest()->with('category', 'city', 'location', 'type', 'shift', 'employer','radiography','software','charting','jobApplication')->paginate(getPaginate());
        $general = GeneralSetting::first();
        return view('admin.job.index', compact('pageTitle', 'emptyMessage', 'jobs','general'));
    }

    public function detail($id)
    {
        $pageTitle = "Job Detail";
        $job = Job::findOrFail($id);
        $skills = JobSkill::where('status', 1)->select('id', 'name')->orderBy('name', 'asc')->get();
        $skill_id = explode(',',$job->skill_id);
        $softwares = JobSoftware::where('status', 1)->select('id', 'name')->orderBy('name', 'asc')->get();
        $software_id = explode(',',$job->software_id);
        return view('admin.job.detail', compact('pageTitle', 'job','skills','skill_id','softwares','software_id'));
    }
    public function pending()
    {
        $pageTitle = "Manage Pending Job";
        $emptyMessage = "No data found";
        $jobs = Job::where('status', 0)->latest()->with('category', 'city', 'location', 'type', 'shift', 'employer', 'radiography','software','charting','jobApplication')->paginate(getPaginate());
        return view('admin.job.index', compact('pageTitle', 'emptyMessage', 'jobs'));
    }

    public function approved()
    {
        $pageTitle = "Manage Approved Job";
        $emptyMessage = "No data found";
        $jobs = Job::where('status', 1)->whereDate('deadline','>', Carbon::now()->toDateTimeString())->latest()->with('category', 'city', 'location', 'type', 'shift', 'employer','radiography','software','charting', 'jobApplication')->paginate(getPaginate());
        return view('admin.job.index', compact('pageTitle', 'emptyMessage', 'jobs'));
    }

    public function cancel()
    {
        $pageTitle = "Manage Cancel Job";
        $emptyMessage = "No data found";
        $jobs = Job::where('status', 2)->latest()->with('category', 'city', 'location', 'type', 'shift', 'employer', 'radiography','software','charting','jobApplication')->paginate(getPaginate());
        return view('admin.job.index', compact('pageTitle', 'emptyMessage', 'jobs'));
    }

    public function expired()
    {
        $pageTitle = "Manage Expired Job";
        $emptyMessage = "No data found";
        $jobs = Job::whereDate('deadline','<=', Carbon::now()->toDateTimeString())->latest()->with('category', 'city', 'location', 'type', 'shift', 'employer','radiography','software','charting')->paginate(getPaginate());
        return view('admin.job.index', compact('pageTitle', 'emptyMessage', 'jobs'));
    }

    public function approvBy(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:jobs,id'
        ]);
        $job = Job::findOrFail($request->id);
        $job->status = 1;
        $job->save();
        $notify[] = ['success', 'Job has been approved'];
        return back()->withNotify($notify);
    }

    public function cancelBy(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:jobs,id'
        ]);
        $job = Job::findOrFail($request->id);
        $job->status = 2;
        $job->save();
        $notify[] = ['success', 'Job has been cancel'];
        return back()->withNotify($notify);
    }

    public function featuredInclude(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:jobs,id'
        ]);
        $job = Job::findOrFail($request->id);
        $job->featured = 1;
        $job->save();
        $notify[] = ['success', 'Include this job featured list'];
        return back()->withNotify($notify);
    }

    public function featuredNotInclude(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:jobs,id'
        ]);
        $job = Job::findOrFail($request->id);
        $job->featured = 0;
        $job->save();
        $notify[] = ['success', 'Remove this job featured list'];
        return back()->withNotify($notify);
    }


    public function jobApplication($id)
    {
        $pageTitle = "Job application list";
        $emptyMessage = "No data found";
        $job = Job::findOrFail($id);
        $jobApplications = JobApply::where('job_id', $job->id)->paginate(getPaginate());
        return view('admin.job.job_apply', compact('pageTitle', 'emptyMessage', 'jobApplications'));
    }

}
