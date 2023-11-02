<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Degree;
use Illuminate\Http\Request;

class DegreeController extends Controller
{
    
    public function index()
    {
        $pageTitle = "Manage Degree";
        $emptyMessage = "No data found";
        $degrees = Degree::select('id', 'name', 'status')->latest()->paginate(getPaginate());
        return view('admin.degree.index', compact('pageTitle', 'emptyMessage', 'degrees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255|unique:exam_or_degrees'
        ]);
        $degree = new Degree();
        $degree->name = $request->name;
        $degree->status = $request->status ? 1 : 2;
        $degree->save();
        $notify[] = ['success', 'Degree has been created'];
        return back()->withNotify($notify);
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:exam_or_degrees,id',
            'name' => 'required|max:255|unique:exam_or_degrees,name,'. $request->id,
        ]);
        $degree = Degree::find($request->id);
        $degree->name = $request->name;
        $degree->status = $request->status ? 1 : 2;
        $degree->save();
        $notify[] = ['success', 'Degree has been updated'];
        return back()->withNotify($notify);
    }
}
