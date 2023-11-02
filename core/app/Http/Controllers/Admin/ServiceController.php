<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $pageTitle = "Manage Service";
        $emptyMessage = "No data found";
        $services = Service::select('id', 'name', 'status')->latest()->paginate(getPaginate());
        return view('admin.service.index', compact('pageTitle', 'emptyMessage', 'services'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255|unique:services'
        ]);
        $service = new Service();
        $service->name = $request->name;
        $service->status = $request->status ? 1 : 2;
        $service->save();
        $notify[] = ['success', 'Service has been created'];
        return back()->withNotify($notify);
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:services,id',
            'name' => 'required|max:255|unique:services,name,'. $request->id,
        ]);
        $services = Service::find($request->id);
        $services->name = $request->name;
        $services->status = $request->status ? 1 : 2;
        $services->save();
        $notify[] = ['success', 'Service has been updated'];
        return back()->withNotify($notify);
    }
}
