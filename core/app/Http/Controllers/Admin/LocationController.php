<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index()
    {
        $pageTitle = "Manage City";
        $emptyMessage = "No data found";
        $cities = City::where('status', 1)->select('id', 'name')->get();
        $locations = Location::latest()->with('city')->paginate(getPaginate());
        return view('admin.location.index', compact('pageTitle', 'emptyMessage', 'cities', 'locations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'city' => 'required|exists:cities,id',
            'name' => 'required|max:255'
        ]);
        $location = new Location();
        $location->city_id = $request->city;
        $location->name = $request->name;
        $location->save();
        $notify[] = ['success', 'Location has been created'];
        return back()->withNotify($notify);
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:locations,id',
            'city' => 'required|exists:cities,id',
            'name' => 'required|max:255'
        ]);
        $location = Location::findOrFail($request->id);
        $location->city_id = $request->city;
        $location->name = $request->name;
        $location->save();
        $notify[] = ['success', 'Location has been updated'];
        return back()->withNotify($notify);
    }


}
