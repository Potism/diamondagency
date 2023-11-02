<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Industry;

class IndustryController extends Controller
{

    public function index()
    {
        $pageTitle = "Manage Industry";
        $emptyMessage = "No data found";
        $industries = Industry::select('id', 'name', 'status')->latest()->paginate(getPaginate());
        return view('admin.industry.index', compact('pageTitle', 'emptyMessage', 'industries'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:120|unique:industries'
        ]);
        $city = new Industry();
        $city->name = $request->name;
        $city->status = $request->status ? 1 : 2;
        $city->save();
        $notify[] = ['success', 'Industry has been created'];
        return back()->withNotify($notify);
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:industries,id',
            'name' => 'required|max:80|unique:industries,name,'. $request->id,
        ]);
        $city = Industry::find($request->id);
        $city->name = $request->name;
        $city->status = $request->status ? 1 : 2;
        $city->save();
        $notify[] = ['success', 'Industry has been updated'];
        return back()->withNotify($notify);
    }
}
