<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Industry;
class CategoryController extends Controller
{
    public function index()
    {
        $pageTitle = "Manage Job Category";
        $emptyMessage = "No data found";
        $categorys = Category::select('*')->latest()->paginate(getPaginate());
        $industries = Industry::select('id', 'name', 'status')->orderBy('name', 'asc')->latest()->paginate(getPaginate());
        return view('admin.category.index', compact('pageTitle', 'emptyMessage', 'categorys','industries'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:120|unique:categories'
        ]);
        $category = new Category();
        $category->name = $request->name;
        $category->cat_id = $request->cat_id;
        $category->full_timerate = $request->full_timerate;
        $category->temp_rate = $request->temp_rate;
        $category->markup_rate = $request->markup_rate;
        $category->temp_markup_rate = $request->temp_markup_rate;
        $category->status = $request->status ? 1 : 2;
        $category->save();
        $notify[] = ['success', 'Category has been created'];
        return back()->withNotify($notify);
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:categories,id',
            'name' => 'required|max:120|unique:categories,name,'.$request->id,
        ]);
        $category = Category::findOrFail($request->id);
        $category->name = $request->name;
        $category->cat_id = $request->cat_id;
        $category->full_timerate = $request->full_timerate;
        $category->temp_rate = $request->temp_rate;
        $category->markup_rate = $request->markup_rate;
        $category->temp_markup_rate = $request->temp_markup_rate;
        $category->status = $request->status ? 1 : 2;
        $category->save();
        $notify[] = ['success', 'Category has been updated'];
        return back()->withNotify($notify);
    }
}
