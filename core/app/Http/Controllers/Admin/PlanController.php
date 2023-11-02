<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Plan;
use App\Models\Service;
use Illuminate\Http\Request;

class PlanController extends Controller
{

    public function create()
    {
        $pageTitle = "Plan create";
        $services = Service::select('id', 'name', 'status')->get();
        return view('admin.plan.create', compact('pageTitle', 'services'));
    }
    
    public function index()
    {
        $pageTitle = "Manage Plan";
        $emptyMessage = "no data found";
        $plans = Plan::latest()->paginate(getPaginate());
        return view('admin.plan.index', compact('pageTitle', 'emptyMessage', 'plans'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:120|unique:plans',
            'amount' => 'required|numeric|min:0',
            'duration' => 'required|integer|gt:0',
            'job_post' => 'required|integer|gt:0',
            'icon' => 'required|max:120',
            'service' => 'required|array|exists:services,id'
        ]);
        $plan = new Plan;
        $plan->name = $request->name;
        $plan->amount = $request->amount;
        $plan->job_post = $request->job_post;
        $plan->duration = $request->duration;
        $plan->icon = $request->icon;
        $plan->services =  $request->service;
        $plan->status = $request->status ? 1 : 2;
        $plan->save();
        $notify[] = ['success', 'Plan has been created'];
        return back()->withNotify($notify);
    }

    public function edit($id)
    {
        $pageTitle = "Plan update";
        $plan = Plan::findOrFail($id);
        $services = Service::select('id', 'name', 'status')->get();
        return view('admin.plan.edit', compact('pageTitle', 'plan', 'services'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:120|unique:plans,name,'.$id,
            'amount' => 'required|numeric|min:0',
            'duration' => 'required|integer|gt:0',
            'job_post' => 'required|integer|gt:0',
            'icon' => 'required|max:120',
            'service' => 'required|array|exists:services,id'
        ]);
        $plan = Plan::findOrFail($id);
        $plan->name = $request->name;
        $plan->amount = $request->amount;
        $plan->job_post = $request->job_post;
        $plan->duration = $request->duration;
        $plan->icon = $request->icon;
        $plan->services =  $request->service;
        $plan->status = $request->status ? 1 : 2;
        $plan->save();
        $notify[] = ['success', 'Plan has been updated'];
        return back()->withNotify($notify);
    }

    public function planSubscribe()
    {
        $pageTitle = "All Plan Subscribers list";
        $emptyMessage = "No data found";
        $orders = Order::latest()->with('employer', 'plan')->paginate(getPaginate());
        return view('admin.plan.subscriber', compact('pageTitle', 'emptyMessage', 'orders')); 
    }

    public function planSubscribeSearch(Request $request)
    {
        $search = $request->search;
        $pageTitle = "Plan subscribers search";
        $emptyMessage = "No data found";
        $orders = Order::where('order_number', $search)->with('employer', 'plan')->paginate(getPaginate());
        return view('admin.plan.subscriber', compact('pageTitle', 'emptyMessage', 'orders', 'search'));
    }

}
