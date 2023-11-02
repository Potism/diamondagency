<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Badge;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;
use Image;

class BadgeController extends Controller
{
    public function index()
    {
        $pageTitle = "Manage Badge";
        $emptyMessage = "No data found";
        $badges = Badge::select('*')->latest()->paginate(getPaginate());
        return view('admin.badge.index', compact('pageTitle', 'emptyMessage', 'badges'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:120|unique:badges',
            'image' => ['image',new FileTypeValidate(['jpg','jpeg','png'])],
        ]);
        $badge = new Badge();
        $badge->name = $request->name;
        $badge->no_of_rewards = $request->no_of_rewards;
        if ($request->hasFile('image')) {
            $location = imagePath()['badge']['path'];
            $filename = uploadImage($request->image, $location, $size=null, $badge->image);
            $badge->image = $filename;
        }
        $badge->save();
        $notify[] = ['success', 'Badge has been created'];
        return back()->withNotify($notify);
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:badges,id',
            'name' => 'required|max:120|unique:badges,name,'.$request->id,
            'image' => ['image',new FileTypeValidate(['jpg','jpeg','png'])],
        ]);
        $badge = Badge::findOrFail($request->id);
        $badge->name = $request->name;
        $badge->no_of_rewards = $request->no_of_rewards;
        if ($request->hasFile('image')) {
            $location = imagePath()['badge']['path'];
            $filename = uploadImage($request->image, $location, $size=null, $badge->image);
            $badge->image = $filename;
        }
        $badge->save();
        $notify[] = ['success', 'Badge has been updated'];
        return back()->withNotify($notify);
    }
}
