<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use App\Models\Category;
use App\Models\City;
use App\Models\Deposit;
use App\Models\Job;
use App\Models\JobApply;
use App\Models\JobExperience;
use App\Models\JobShift;
use App\Models\JobSkill;
use App\Models\JobType;
use App\Models\Location;
use App\Models\Order;
use App\Models\SalaryPeriod;
use App\Models\User;
use App\Models\JobRadiography;
use App\Models\JobCharting;
use App\Models\JobSoftware;
use App\Models\GeneralSetting;
use App\Models\Invoice;
use App\Models\Review;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobController extends Controller
{
    public function __construct()
    {
        $this->activeTemplate = activeTemplate();
    }

    public function index()
    {
        $pageTitle = "Job Posting";
        $emptyMessage = "No data found";
        $employer = auth()->guard('employer')->user();
        $jobs = Job::where('employer_id', $employer->id)->with('category', 'shift', 'type', 'software', 'radiography', 'charting', 'jobApplication')->latest()->paginate(getPaginate());
        return view($this->activeTemplate . 'employer.job.index', compact('employer', 'pageTitle', 'emptyMessage', 'jobs'));
    }

    public function appliedJob($id)
    {
        $pageTitle = "Applied job list";
        $emptyMessage = "No data found";
        $employer = auth()->guard('employer')->user();
        $appliedJobs = JobApply::whereHas('job',function($q) use ($employer){
            $q->where('employer_id',$employer->id);
        })->where('job_id', $id)->latest()->with('user','job','review')->paginate(getPaginate());
        return view($this->activeTemplate . 'employer.job.applied', compact('pageTitle', 'emptyMessage', 'appliedJobs'));
    }

    public function create()
    {
        $employer = Auth::guard('employer')->user();
        $order = Order::where('employer_id', $employer->id)->where('status', 1)->first();
        $totalDeposit = Deposit::where('employer_id',$employer->id)->where('status',1)->sum('amount');
        // if(!$order){
        //     $notify[] = ['error', 'Buy a plan before posting a job.'];
        //     return redirect()->route('employer.home')->withNotify($notify);
        // }
        // if($employer->job_post_count <= 0){
        //     $notify[] = ['error', 'Job posting limit is over'];
        //     return back()->withNotify($notify);
        // }
        if($employer->balance <= 0)
        {
          $notify[] = ['error', 'Please load your Account With Sufficient Funds. (For Staffs Payment and Fees.)'];
          return redirect()->route('employer.deposit')->withNotify($notify);
        }
        $pageTitle = "Job Post Creation";
        $cities = City::where('status', 1)->select('id', 'name')->orderBy('name', 'asc')->with(['location' => function ($q) {
            $q->orderBy('name', 'asc');
        }])->get();
        $types = JobType::where('status', 1)->select('id', 'name')->get();
        $shifts = JobShift::where('status', 1)->select('id', 'name')->get();
        $skills = JobSkill::where('status', 1)->select('id', 'name')->orderBy('name', 'asc')->get();
        $categorys = Category::where('status', 1)->select('id', 'name')->orderBy('name', 'asc')->get();
        $experiences = JobExperience::where('status', 1)->select('id', 'name')->get();
        $salaryPeriods = SalaryPeriod::where('status', 1)->select('id', 'name')->get();
        $radiographies = JobRadiography::where('status', 1)->select('id', 'name')->get();
        $chartings = JobCharting::where('status', 1)->select('id', 'name')->get();
        $softwares = JobSoftware::where('status', 1)->select('id', 'name')->orderBy('name', 'asc')->get();
        return view($this->activeTemplate . 'employer.job.create', compact('pageTitle', 'cities', 'types', 'shifts', 'skills', 'categorys', 'experiences', 'salaryPeriods','radiographies','chartings','softwares'));
    }

    public function store(Request $request)
    {
        $employer = Auth::guard('employer')->user();
        $order = Order::where('employer_id', $employer->id)->where('status', 1)->first();
        // if(!$order){
        //     $notify[] = ['error', 'Buy a plan before posting a job.'];
        //     return back()->withNotify($notify);
        // }
        if($employer->balance <= 0)
        {
          $notify[] = ['error', 'Load Amount to EWallet then create job post'];
          return redirect()->route('employer.deposit')->withNotify($notify);
        }
        // if($employer->job_post_count <= 0){
        //     $notify[] = ['error', 'Job posting limit is over'];
        //     return back()->withNotify($notify);
        // }
        $request->validate([
            'job_cat_rate' => 'required',
        ]);
        $request->validate([
            'title' => 'required|max:255',
            'category' => 'required|exists:categories,id',
            'job_cat_rate' => 'required',
            // 'type' => 'required|exists:job_types,id',
            'city' => 'required|exists:cities,id',
            'location' => 'required|exists:locations,id',
            'shift' => 'required|exists:job_shifts,id',
            'vacancy' => 'required|integer|gt:0',
            'job_experience' => 'required|exists:job_experiences,id',
            'gender' => 'required|in:1,2,3',
            // 'salary_type' => 'required|in:1,2',
            // 'salary_period' => 'required|exists:salary_periods,id',
            'deadline' => 'required|date',
            'description' => 'required',
            'responsibilities' => 'required',
            'requirements' => 'required',
            'hourly_rate' => 'required',
            'software' => 'required|exists:job_software,id',
            'skill' => 'nullable|array|exists:job_skills,id',
        ]);
        // if($request->salary_type == 2){
        //     $request->validate([
        //         'salary_from' => 'required|numeric|gt:0',
        //         'salary_to' => 'required|numeric|gt:0',
        //     ]);
        // }
        if($request->job_cat_rate == 'temp_rate')
        {
            $request->validate([
                'primary_contact' => 'required|max:255',
                // 'parking' => 'required|max:255',
                // 'radiography' => 'required|exists:job_radiographies,id',
                // 'ultrasonic' => 'required',
                // 'avg_recall' => 'required|max:255',
                // 'charting' => 'required|exists:job_chartings,id',
                'lunch_break' => 'required',
            ]);
        }
        $employer->job_post_count -= 1;
        $employer->save();
        // if($employer->job_post_count <= 0){
        //     $order = Order::where('employer_id', $employer->id)->where('status', 1)->first();
        //     if($order){
        //         $order->status = 2;
        //         $order->save();
        //         notify($employer, 'JOB_LIMIT_OVER', [
        //             'plan_name' => $order->plan->name,
        //             'order_number' => $order->order_number
        //         ]);
        //     }
        // }
        $category = Category::where('status', 1)->where('id',$request->category)->firstOrFail();
        // $type = JobType::where('status', 1)->where('id',$request->type)->firstOrFail();
        $city = City::where('status', 1)->where('id',$request->city)->firstOrFail();
        $shift = JobShift::where('status', 1)->where('id',$request->shift)->firstOrFail();
        $experience = JobExperience::where('status', 1)->where('id',$request->job_experience)->firstOrFail();
        // $salaryPeriod = SalaryPeriod::where('status', 1)->where('id',$request->salary_period)->firstOrFail();
        $softwares = JobSoftware::where('status', 1)->where('id', $request->software)->firstOrFail();
        if($request->job_cat_rate == 'temp_rate')
        {
          $type_id = 1;
        }
        else
        {
          $type_id = 2;
        }
        $job = new Job();
        $job->title = $request->title;
        $job->employer_id = $employer->id;
        $job->category_id = $request->category;
        $job->job_cat_rate = $request->job_cat_rate;
        $job->type_id = $type_id;
        $job->city_id = $request->city;
        $job->location_id = $request->location;
        $job->shift_id = $request->shift;
        $job->vacancy = $request->vacancy;
        $job->salary_type = '';
        if($request->salary_type == 2)
        {
          $job->salary_from = $request->salary_from;
          $job->salary_to = $request->salary_to;
        }
        $job->salary_period ='';
        $job->job_experience_id = $request->job_experience;
        $job->deadline = $request->deadline;
        $job->gender = $request->gender;
        $job->hourly_rate = $request->hourly_rate;
        $job->age = '';
        $job->description = $request->description;
        $job->responsibilities = $request->responsibilities;
        $job->requirements = $request->requirements;
        if($request->job_cat_rate == 'temp_rate')
        {
            $job->primary_contact = $request->primary_contact;
            $job->parking = '0';
            $job->radiography_id = '0';
            $job->ultrasonic = '0';
            $job->avg_recall = '0';
            $job->charting_id = '0';
            $job->lunch_break = $request->lunch_break;
        }
        $job->software_id = implode(',',$request->software);
        $job->skill_id = implode(',',$request->skill);
        $job->save();
        $adminNotification = new AdminNotification();
        $adminNotification->user_id = $employer->id;
        $adminNotification->title = 'Job create';
        $adminNotification->click_url = urlPath('admin.manage.job.detail', $job->id);
        $adminNotification->save();

        $notify[] =['success', 'Job has been created'];
        return redirect()->route('employer.home')->withNotify($notify);
    }

    public function edit($id)
    {
        $employer = Auth::guard('employer')->user();
        $order = Order::where('employer_id', $employer->id)->where('status', 1)->first();
        // if(!$order){
        //     $notify[] = ['error', 'Buy a plan before posting a job.'];
        //     return redirect()->route('employer.home')->withNotify($notify);
        // }
        // if($employer->job_post_count <= 0){
        //     $notify[] = ['error', 'Job posting limit is over'];
        //     return back()->withNotify($notify);
        // }
        $pageTitle = "Job Update";
        $cities = City::where('status', 1)->select('id', 'name')->with('location')->get();
        $types = JobType::where('status', 1)->select('id', 'name')->get();
        $shifts = JobShift::where('status', 1)->select('id', 'name')->get();
        $skills = JobSkill::where('status', 1)->select('id', 'name')->orderBy('name', 'asc')->get();
        $categorys = Category::where('status', 1)->select('id', 'name')->get();
        $experiences = JobExperience::where('status', 1)->select('id', 'name')->get();
        $salaryPeriods = SalaryPeriod::where('status', 1)->select('id', 'name')->get();
        $radiographies = JobRadiography::where('status', 1)->select('id','name')->get();
        $chartings = JobCharting::where('status', 1)->select('id','name')->get();
        $softwares = JobSoftware::where('status', 1)->select('id','name')->orderBy('name', 'asc')->get();
        $employer = Auth::guard('employer')->user();
        $job = Job::where('id', $id)->where('employer_id', $employer->id)->firstOrFail();
        $skill_id = explode(',',$job->skill_id);
        $software_id = explode(',',$job->software_id);
        return view($this->activeTemplate . 'employer.job.edit', compact('pageTitle', 'employer', 'job', 'cities', 'types', 'shifts', 'skills', 'categorys', 'experiences', 'salaryPeriods','radiographies', 'chartings', 'softwares','skill_id','software_id'));
    }

    public function update(Request $request, $id)
    {
        $employer = Auth::guard('employer')->user();
        $order = Order::where('employer_id', $employer->id)->where('status', 1)->first();
        // if(!$order){
        //     $notify[] = ['error', 'Buy a plan before posting a job.'];
        //     return back()->withNotify($notify);
        // }
        // if($employer->job_post_count <= 0){
        //     $notify[] = ['error', 'Job posting limit is over'];
        //     return back()->withNotify($notify);
        // }
        $request->validate([
            'title' => 'required|max:255',
            'category' => 'required|exists:categories,id',
            'job_cat_rate' => 'required',
            // 'type' => 'required|exists:job_types,id',
            'city' => 'required|exists:cities,id',
            'location' => 'required|exists:locations,id',
            'shift' => 'required|exists:job_shifts,id',
            'vacancy' => 'required|integer|gt:0',
            'job_experience' => 'required|exists:job_experiences,id',
            'gender' => 'required|in:1,2,3',
            // 'salary_type' => 'required|in:1,2',
            // 'salary_period' => 'required|exists:salary_periods,id',
            'deadline' => 'required|date',
            'description' => 'required',
            'responsibilities' => 'required',
            'requirements' => 'required',
            'hourly_rate' => 'required',
            'software' => 'required|exists:job_software,id',
            'skill' => 'nullable|array|exists:job_skills,id',
        ]);

        // if($request->salary_type == 2){
        //     $request->validate([
        //         'salary_from' => 'required|numeric|gt:0',
        //         'salary_to' => 'required|numeric|gt:0',
        //     ]);
        // }
        if($request->job_cat_rate == "temp_rate")
        {
          $request->validate([
            'primary_contact' => 'required|max:255',
            // 'parking' => 'required|max:255',
            // 'radiography' => 'required|exists:job_radiographies,id',
            // 'ultrasonic' => 'required',
            // 'avg_recall' => 'required|max:255',
            // 'charting' => 'required|exists:job_chartings,id',
            'lunch_break' => 'required',
          ]);
        }
        if($request->job_cat_rate == 'temp_rate')
        {
          $type_id = 1;
        }
        else
        {
          $type_id = 2;
        }
        $category = Category::where('status', 1)->where('id',$request->category)->firstOrFail();
        // $type = JobType::where('status', 1)->where('id',$request->type)->firstOrFail();
        $city = City::where('status', 1)->where('id',$request->city)->firstOrFail();
        $shift = JobShift::where('status', 1)->where('id',$request->shift)->firstOrFail();
        $experience = JobExperience::where('status', 1)->where('id',$request->job_experience)->firstOrFail();
        // $salaryPeriod = SalaryPeriod::where('status', 1)->where('id',$request->salary_period)->firstOrFail();
        if($request->job_cat_rate == "temp_rate")
        {
          // $radiographies = JobRadiography::where('status', 1)->where('id', $request->radiography)->firstOrFail();
          // $chartings = JobCharting::where('status', 1)->where('id', $request->charting)->firstOrFail();
          // $softwares = JobSoftware::where('status', 1)->where('id', $request->software)->firstOrFail();
        }
        $job = Job::where('employer_id', $employer->id)->where('id', $id)->firstOrFail();
        if($job->status != 0){
            $notify[] = ['error', 'Only pending job update'];
            return back()->withNotify($notify);
        }
        $job->title = $request->title;
        $job->employer_id = $employer->id;
        $job->category_id = $request->category;
        if($request->job_cat_rate == "temp_rate")
        {
          $job->primary_contact = $request->primary_contact;
          $job->parking = '0';
          $job->radiography_id = '0';
          $job->ultrasonic = '0';
          $job->avg_recall = '0';
          $job->charting_id = '0';
          $job->lunch_break = $request->lunch_break;
        }
        $job->software_id = implode(',',$request->software);
        $job->skill_id = implode(',',$request->skill);
        $job->type_id =  $type_id;
        $job->city_id = $request->city;
        $job->location_id = $request->location;
        $job->shift_id = $request->shift;
        $job->vacancy = $request->vacancy;
        $job->hourly_rate = $request->hourly_rate;
        $job->salary_type = '';
        if($request->salary_type == 2)
        {
          $job->salary_from = $request->salary_from;
          $job->salary_to = $request->salary_to;
        }
        $job->salary_period = '';
        $job->job_experience_id = $request->job_experience;
        $job->deadline = $request->deadline;
        $job->gender = $request->gender;
        $job->age = '';
        $job->description = $request->description;
        $job->responsibilities = $request->responsibilities;
        $job->requirements = $request->requirements;

        $job->save();
        $notify[] =['success', 'Job has been updated'];
        return back()->withNotify($notify);
    }

    public function cvDownload($id)
    {
        $employer = Auth::guard('employer')->user();
        $jobApply = JobApply::whereHas('job',function($q) use ($employer){
            $q->where('employer_id',$employer->id);
        })->findOrFail(decrypt($id));
        if($jobApply->user->cv)
        {
          $path = imagePath()['profile']['user']['path'];
          $fullPath = $path.'/'. $jobApply->user->cv;
          $title = slug($jobApply->user->username);
          $ext = pathinfo($jobApply->user->cv, PATHINFO_EXTENSION);
          $mimetype = mime_content_type($fullPath);
          header('Content-Disposition: attachment; filename="' . $title . '.' . $ext . '";');
          header("Content-Type: " . $mimetype);
          // return Response::download($fullPath, 'filename.pdf', $headers);
          return readfile($fullPath);
        }
        else {
          $notify[] = ['error', 'CV does not exist'];
          return back()->withNotify($notify);
        }

    }

    public function approved(Request $request)
    {
        $employer = Auth::guard('employer')->user();
        $jobApply = JobApply::whereHas('job',function($q) use ($employer){
            $q->where('employer_id',$employer->id);
        })->findOrFail($request->id);
        $jobApply->status = 1;
        $jobApply->save();
        
        $job = Job::findOrFail($jobApply->job_id);
        $emp_add = $jobApply->job->employer->address;
        $office_details = $emp_add->address.', '.$emp_add->city.', '.$emp_add->state.', '.$emp_add->country.', '.$emp_add->zip;
        $website = $jobApply->job->employer->website;
        $fax_number = $jobApply->job->employer->fax;
        $company_email = $jobApply->job->employer->email;

        $user = User::findOrFail($jobApply->user_id);
        notify($user, 'JOB_APPLICATION_RECIVED', [
            'company_name' => $jobApply->job->employer->company_name,
            'job_title' => $jobApply->job->title,
            'deadline' => showDateTime($job->deadline, 'd M Y'), 
            'shift_time' => $job->shift->name,
            'company_address' => $office_details,
            'phone_number' => '+'.$jobApply->job->employer->mobile_code.' '.$jobApply->job->employer->mobile,
            'company_website' => isset($website)?$website:'Not available',
            'company_fax' => isset($fax_number)?$fax_number:'Not available',
            'company_email' => isset($company_email)?$company_email:'Not available',
        ]);
        $notify[] = ['success', 'Job application received'];
        return back()->withNotify($notify);
    }

    public function cancel(Request $request)
    {
        $employer = Auth::guard('employer')->user();
        $jobApply = JobApply::whereHas('job',function($q) use ($employer){
            $q->where('employer_id',$employer->id);
        })->findOrFail($request->id);
        $jobApply->status = 2;
        $jobApply->save();

        $user = User::findOrFail($jobApply->user_id);
        notify($user, 'JOB_APPLICATION_CANCELLED', [
            'company_name' => $jobApply->job->employer->company_name,
            'job_title' => $jobApply->job->title
        ]);
        $notify[] = ['success', 'Job application cancelled'];
        return back()->withNotify($notify);
    }

    public function UserInovices($job_type,$job_id,$id)
    {
        $pageTitle = "Invoice list";
        $emptyMessage = "No data found";
        $employer = auth()->guard('employer')->user();
        $appliedJobs = Invoice::where('user_id',$id)->where('prefix','!=','CNINV')->where('job_id', $job_id)->where('job_type',$job_type)->latest()->with('job','user')->paginate(getPaginate());
        return view($this->activeTemplate . 'employer.job.permanent_job_invoice', compact('pageTitle', 'emptyMessage', 'appliedJobs'));
    }

    public function catmarkup(Request $request)
    {
        $employer = auth()->guard('employer')->user();
        $id = $request->category;
        $category = Category::where('status', 1)->where('id',$id)->firstOrFail();
        $rate = $category->markup_rate;
        return response()->json($rate);
    }
    public function jobreview(Request $request) {
        $employer = Auth::guard('employer')->user();
        $request->validate([
            'star' => 'required',
        ]);
        $review = new Review();
        $review->employer_id  =  Auth::guard('employer')->user()->id;
        $review->user_id =  $request->input('user_id');
        $review->rating = $request->input('star');
        $review->job_apply_id = $request->input('id');
        $review->comment = 'rating by employer';
        $review->status = 1;
        $review->save();
        $jobApply = JobApply::whereHas('job',function($q) use ($employer){
            $q->where('employer_id',$employer->id);
        })->findOrFail($request->id);
        $jobApply->job_status = 2;
        $jobApply->candidate_review = $request->input('star');
        $jobApply->save();
        $user = User::findOrFail($jobApply->user_id);
        notify($user, 'REVIEW',[
            'company_name' => $jobApply->job->employer->company_name,
            'candidate_name' => $jobApply->user->firstname.' '.$jobApply->user->lastname,
            'job_title' => $jobApply->job->title,
            'rating' => $request->input('star').' stars',
        ]);
        $notify[] =['success', 'Review is succesfully submitted'];
        return redirect()->back()->withNotify($notify);
        // return redirect()->route('employer.job.appliedJob')->withNotify($notify);
    }
}
