<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobCharting;
use Illuminate\Http\Request;

class JobChartingController extends Controller
{

    public function index()
    {
        $pageTitle = "Manage Job Radiography";
        $emptyMessage = "No data found";
        $jobChartings = JobCharting::select('id', 'name', 'status')->latest()->paginate(getPaginate());
        return view('admin.job_charting.index', compact('pageTitle', 'emptyMessage', 'jobChartings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:120|unique:job_experiences'
        ]);
        $jobCharting = new JobCharting();
        $jobCharting->name = $request->name;
        $jobCharting->status = $request->status ? 1 : 2;
        $jobCharting->save();
        $notify[] = ['success', 'Job Charting type has been created'];
        return back()->withNotify($notify);
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:job_chartings,id',
            'name' => 'required|max:120|unique:job_chartings,name,'.$request->id,
        ]);
        $jobCharting = JobCharting::find($request->id);
        $jobCharting->name = $request->name;
        $jobCharting->status = $request->status ? 1 : 2;
        $jobCharting->save();
        $notify[] = ['success', 'Job Charting type has been updated'];
        return back()->withNotify($notify);
    }
}
