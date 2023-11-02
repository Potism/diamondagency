<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\City;

class CityController extends Controller
{
    public function index()
    {
        $pageTitle = "Manage Province";
        $emptyMessage = "No data found";
        $cities = City::select('id', 'name', 'status')->latest()->paginate(getPaginate());
        return view('admin.city.index', compact('pageTitle', 'emptyMessage', 'cities'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:80|unique:cities'
        ]);
        $city = new City();
        $city->name = $request->name;
        $city->status = $request->status ? 1 : 2;
        $city->save();
        $notify[] = ['success', 'City has been created'];
        return back()->withNotify($notify);
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:cities,id',
            'name' => 'required|max:80|unique:cities,name,'. $request->id,
        ]);
        $city = City::find($request->id);
        $city->name = $request->name;
        $city->status = $request->status ? 1 : 2;
        $city->save();
        $notify[] = ['success', 'City has been updated'];
        return back()->withNotify($notify);
    }
}
