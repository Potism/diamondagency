<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SalaryPeriod;
use Illuminate\Http\Request;

class SalaryPeriodController extends Controller
{
    public function index()
    {
        $pageTitle = "Manage Salary Period";
        $emptyMessage = "No data found";
        $salaryPeriods = SalaryPeriod::latest()->paginate(getPaginate());
        return view('admin.salary_period.index', compact('pageTitle', 'emptyMessage', 'salaryPeriods'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:120|unique:salary_periods'
        ]);
        $salaryPeriod = new SalaryPeriod();
        $salaryPeriod->name = $request->name;
        $salaryPeriod->status = $request->status ? 1 : 2;
        $salaryPeriod->save();
        $notify[] = ['success', 'Salary Period has been created'];
        return back()->withNotify($notify);
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:salary_periods,id',
            'name' => 'required|max:120|unique:salary_periods,name,'. $request->id,
        ]);
        $salaryPeriod = SalaryPeriod::find($request->id);
        $salaryPeriod->name = $request->name;
        $salaryPeriod->status = $request->status ? 1 : 2;
        $salaryPeriod->save();
        $notify[] = ['success', 'Salary Period has been updated'];
        return back()->withNotify($notify);
    }
}
