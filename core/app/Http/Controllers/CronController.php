<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\GeneralSetting;
use App\Models\Employer;
use Carbon\Carbon;

class CronController extends Controller
{
    
    public function index()
    {
        $orders = Order::where('status', 1)->get();
        $general = GeneralSetting::first();
        $general->last_cron_run = Carbon::now();
        $general->save();
        foreach($orders as $order){
            $duration = $order->created_at->addMonths($order->plan->duration); 
            $nowTime = Carbon::now()->toDateTimeString();
            if($nowTime > $duration){
                $order->status = 2;
                $order->save();

                $employer = Employer::where('id', $order->employer_id)->firstOrFail();
                $employer->job_post_count = 0;
                $employer->save();

                notify($employer, 'PLAN_EXPIRED', [
                    'plan_name' => $order->plan->name, 
                    'order_number' => $order->order_number
                ]);
            }

        }
        return 0;
    }
}
