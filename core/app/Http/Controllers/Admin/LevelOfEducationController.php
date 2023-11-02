<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LevelOfEducation;
use Illuminate\Http\Request;

class LevelOfEducationController extends Controller
{
    

    public function index()
    {
        $pageTitle = "Manage Level Of Education";
        $emptyMessage = "No data found";
        $levels = LevelOfEducation::select('id', 'name', 'status')->latest()->paginate(getPaginate());
        return view('admin.level_education.index', compact('pageTitle', 'emptyMessage', 'levels'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:120|unique:level_of_education'
        ]);
        $degree = new LevelOfEducation();
        $degree->name = $request->name;
        $degree->status = $request->status ? 1 : 2;
        $degree->save();
        $notify[] = ['success', 'Degree has been created'];
        return back()->withNotify($notify);
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:level_of_education,id',
            'name' => 'required|max:120|unique:level_of_education,name,'. $request->id,
        ]);
        $degree = LevelOfEducation::find($request->id);
        $degree->name = $request->name;
        $degree->status = $request->status ? 1 : 2;
        $degree->save();
        $notify[] = ['success', 'Degree has been updated'];
        return back()->withNotify($notify);
    }
}
