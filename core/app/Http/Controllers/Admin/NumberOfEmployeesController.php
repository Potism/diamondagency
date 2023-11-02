<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NumberOfEmployees;
use Illuminate\Http\Request;

class NumberOfEmployeesController extends Controller
{
    
    public function index()
    {
        $pageTitle = "Manage Number Of Employees";
        $emptyMessage = "No data found";
        $numberOfEmployees = NumberOfEmployees::select('id', 'employees', 'status')->latest()->paginate(getPaginate());
        return view('admin.number_employees.index', compact('pageTitle', 'emptyMessage', 'numberOfEmployees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employees' => 'required|max:120|unique:number_of_employees'
        ]);
        $NumberOfEmployee = new NumberOfEmployees();
        $NumberOfEmployee->employees = $request->employees;
        $NumberOfEmployee->status = $request->status ? 1 : 2;
        $NumberOfEmployee->save();
        $notify[] = ['success', 'Number of employees has been created'];
        return back()->withNotify($notify);
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:number_of_employees,id',
            'employees' => 'required|max:120|unique:number_of_employees,employees,'. $request->id,
        ]);
        $NumberOfEmployee = NumberOfEmployees::findOrFail($request->id);
        $NumberOfEmployee->employees = $request->employees;
        $NumberOfEmployee->status = $request->status ? 1 : 2;
        $NumberOfEmployee->save();
        $notify[] = ['success', 'Number of employees has been updated'];
        return back()->withNotify($notify);
    }
}
