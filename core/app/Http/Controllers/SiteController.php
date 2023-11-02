<?php

namespace App\Http\Controllers;
use App\Models\AdminNotification;
use App\Models\Category;
use App\Models\City;
use App\Models\Employer;
use App\Models\Frontend;
use App\Models\Industry;
use App\Models\Job;
use App\Models\JobExperience;
use App\Models\JobShift;
use App\Models\JobSkill;
use App\Models\JobApply;
use App\Models\JobType;
use App\Models\Language;
use App\Models\Page;
use App\Models\Extension;
use App\Models\SupportAttachment;
use App\Models\SupportMessage;
use App\Models\SupportTicket;
use App\Models\JobSoftware;
use App\Models\User;
use App\Models\TempJob;
use App\Models\Review;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;


class SiteController extends Controller
{
    public function __construct(){
        $this->activeTemplate = activeTemplate();
    }

    public function index(){
        $count = Page::where('tempname',$this->activeTemplate)->where('slug','home')->count();
        if($count == 0){
            $page = new Page();
            $page->tempname = $this->activeTemplate;
            $page->name = 'HOME';
            $page->slug = 'home';
            $page->save();
        }
        $pageTitle = 'Home';
        $cities = City::where('status', 1)->with(['location' => function ($q) {
            $q->orderBy('name', 'asc');
        }])->select('id', 'name')->orderBy('name')->get();
        $sections = Page::where('tempname',$this->activeTemplate)->where('slug','home')->first();
        $categorys = Category::where('status', 1)->with('job')->select('id', 'name')->get();
        $user = Auth::user();
        $lat_long = array();
        $address = '';
        if($user)
        {
            $user_add = $user->address;
            if($user_add)
            {
                $address = $user_add->address.', '.$user_add->city.', '.$user_add->state.', '.$user_add->country.', '.$user_add->zip;
            }
        }
        $lat_long = $this->get_lat_long($address);
        $employer = auth()->guard('employer')->user();
        $user = Auth::user();
        return view($this->activeTemplate . 'home', compact('pageTitle','sections', 'cities', 'categorys','lat_long','employer','user'));
    }

    public function candidateProfile($slug,$id,$job_id)
    {
          if(!auth()->user() && !auth()->guard('employer')->user())
          {
            return redirect()->route('login');
          }
        $pageTitle = "Candidate Profile";
        $candidate = User::where('status', 1)->where('id', $id)->with('education')->firstOrFail();
        $employer = auth()->guard('employer')->user();
        $markup = '';
        if(isset($employer->id))
        {
          $job = JOB::select( 'categories.*','jobs.job_cat_rate')
          ->leftJoin('categories', 'categories.id', '=', 'jobs.category_id')
          ->where('jobs.id' , $job_id)
           ->first();
          if($job->job_cat_rate == "temp_rate")
          {
            $markup = $job->temp_markup_rate;
          }
          else
          {
            $markup = $job->markup_rate;
          } 
        }
        $designation = Category::where('status', 1)->select('*')->latest()->paginate(getPaginate());
        $candidate_rev = Review::where('user_id',$candidate->id)->where('comment','rating by employer')->select('*')->get();
        $avg = $candidate_rev->average('rating');
        $avg1 = $candidate_rev->sum('rating');
        $medal = getmedal($avg1);
        $count = $candidate_rev->count();
        $softwares = JobSoftware::where('status', 1)->select('id', 'name')->get();
        $software_id = explode(',',$candidate->software_id);
        return view($this->activeTemplate . 'candidate_profile', compact('pageTitle', 'candidate','avg','count','designation','softwares','software_id','markup','medal'));
    }

    public function pages($slug)
    {
        $page = Page::where('tempname',$this->activeTemplate)->where('slug',$slug)->firstOrFail();
        $pageTitle = $page->name;
        $sections = $page->secs;
        return view($this->activeTemplate . 'pages', compact('pageTitle','sections'));
    }

    public function contact()
    {
        $pageTitle = "Contact Us";
        return view($this->activeTemplate . 'contact',compact('pageTitle'));
    }
    public function contactSubmit(Request $request)
    {
        $attachments = $request->file('attachments');
        $allowedExts = array('jpg', 'png', 'jpeg', 'pdf');
        $this->validate($request, [
            'name' => 'required|max:191',
            'email' => 'required|max:191',
            'subject' => 'required|max:100',
            'message' => 'required',
        ]);
        $random = getNumber();
        $ticket = new SupportTicket();
        $ticket->user_id = auth()->id() ?? 0;
        $ticket->name = $request->name;
        $ticket->email = $request->email;
        $ticket->priority = 2;


        $ticket->ticket = $random;
        $ticket->subject = $request->subject;
        $ticket->last_reply = Carbon::now();
        $ticket->status = 0;
        $ticket->save();

        $adminNotification = new AdminNotification();
        $adminNotification->user_id = auth()->user() ? auth()->user()->id : 0;
        $adminNotification->title = 'A new support ticket has opened ';
        $adminNotification->click_url = urlPath('admin.ticket.view',$ticket->id);
        $adminNotification->save();

        $message = new SupportMessage();
        $message->supportticket_id = $ticket->id;
        $message->message = $request->message;
        $message->save();

        $notify[] = ['success', 'Your Message has been Sent!'];
        return redirect()->route('contact')->withNotify($notify);
    }

    public function companyProfile($slug, $id)
    {
        if(!auth()->user() && !auth()->guard('employer')->user())
          {
            return redirect()->route('login');
          }
        $pageTitle = "Company Profile";
        $emptyMessage = "No data found";
        $employer = Employer::where('id', $id)->where('status', 1)->with('jobs', 'jobs.employer', 'jobs.location', 'jobs.city')->firstOrFail();
        $emp_add = $employer->address;
        $emp_rev = Review::where('employer_id',$employer->id)->where('comment','rating by candidate')->select('*')->get();
        $avg = $emp_rev->average('rating');
        $avg2 = $emp_rev->sum('rating');
        $medal = getmedal($avg2);
        $count = $emp_rev->count();
        $lat_long = array();
        $user = Auth::user();
        $lat_long2 = array();
        $address2 = '';
        if($user)
        {
            $user_add = $user->address;
            if($user_add)
            {
                $address2 = $user_add->address.', '.$user_add->city.', '.$user_add->state.', '.$user_add->country.', '.$user_add->zip;
            }
        }
        $lat_long2 = $this->get_lat_long($address2);
        if($lat_long2)
        {
            $address = $emp_add->address.', '.$emp_add->city.', '.$emp_add->state.', '.$emp_add->country.', '.$emp_add->zip;
            $lat_long_emp = get_lat_long($address);
            if($lat_long2['lat'] != 0 || $lat_long2['long'] != 0)
            {
                $distance = distance($lat_long2['lat'],$lat_long2['long'],$lat_long_emp['lat'],$lat_long_emp['long']);
            }
            else
            {
                $distance = '';
            }
        }
        if($employer)
        {
            $address = $emp_add->address.', '.$emp_add->city.', '.$emp_add->state.', '.$emp_add->country.', '.$emp_add->zip;
            $lat_long = $this->get_lat_long($address);
        }
        $apikey = Extension::where('act', 'map_api')->where('status', 1)->first();
        $googlemapkey = '';
        if(!empty($apikey))
        {
            $googlemapkey = $apikey->shortcode->api_key->value;
        }
        return view($this->activeTemplate . 'employer_profile', compact('pageTitle', 'emptyMessage', 'employer','lat_long','googlemapkey','distance','avg','count','medal'));
    }

    public function companyList()
    {
        if(!auth()->user() && !auth()->guard('employer')->user())
          {
            return redirect()->route('login');
          }
        $pageTitle = "Client's List";
        $emptyMessage = "No data found";
        $industries = Industry::where('status', 1)->latest()->get();
        $employers = Employer::where('status', 1)->inRandomOrder()->with('jobs')->paginate(getPaginate());
        $sections = Page::where('tempname',$this->activeTemplate)->where('slug','companies')->first();
        return view($this->activeTemplate . 'employer', compact('pageTitle', 'emptyMessage', 'employers', 'industries', 'sections'));
    }

    public function companySearch(Request $request)
    {
        if(!auth()->user() && !auth()->guard('employer')->user())
          {
            return redirect()->route('login');
          }
        $request->validate([
            'industry_id' => 'nullable|exists:industries,id'
        ]);
        $search = $request->search;
        $industryId = $request->industry_id;
        $pageTitle = "Company Search";
        $emptyMessage = "No data found";
        $industries = Industry::where('status', 1)->latest()->get();
        $employers = Employer::where('status', 1)->with('jobs');
        if($request->industry_id){
            $employers = $employers->where('industry_id', $request->industry_id);
        }
        if($request->search){
            $employers  = $employers->where('company_name', 'like', "%$search%");
        }
        $employers = $employers->paginate(getPaginate());
        $sections = '';
        return view($this->activeTemplate . 'employer', compact('pageTitle', 'emptyMessage', 'employers', 'industries', 'search', 'industryId', 'sections'));
    }

    public function job()
    {
        if(!auth()->user() && !auth()->guard('employer')->user())
          {
            return redirect()->route('login');
          }
        $pageTitle = "All Job";
        $emptyMessage = "No data found";
        $cities = City::where('status', 1)->select('id', 'name')->with('location')->get();
        $jobTypes = JobType::where('status', 1)->select('id', 'name')->with(['job' => function ($q){
            $q->whereDate('deadline','>', Carbon::now()->toDateTimeString());
        }])->get();
        $categorys = Category::where('status', 1)->select('id', 'name')->with(['job' => function ($q){
            $q->whereDate('deadline','>', Carbon::now()->toDateTimeString());
        }])->get();
        $jobShifts = JobShift::where('status', 1)->select('id', 'name')->with(['job' => function ($q){
            $q->whereDate('deadline','>', Carbon::now()->toDateTimeString());
        }])->get();
        $jobExperiences = JobExperience::where('status', 1)->select('id', 'name')->with(['job' => function ($q){
            $q->whereDate('deadline','>', Carbon::now()->toDateTimeString());
        }])->get();
        $jobs = Job::where('status', 1)->whereDate('deadline','>', Carbon::now()->toDateTimeString())->latest()->with('employer', 'location', 'city', 'software', 'radiography', 'charting','jobApplication')->inRandomOrder()->paginate(getPaginate(6));
        $employer = auth()->guard('employer')->user();
        $user = Auth::user();
        $lat_long = array();
        $address = '';
        if($user)
        {
            $user_add = $user->address;
            if($user_add)
            {
                $address = $user_add->address.', '.$user_add->city.', '.$user_add->state.', '.$user_add->country.', '.$user_add->zip;
            }
        }
        $lat_long = $this->get_lat_long($address);
        return view($this->activeTemplate . 'job', compact('pageTitle', 'emptyMessage', 'jobs', 'jobTypes', 'jobShifts', 'jobExperiences', 'categorys', 'cities','employer','user','lat_long'));
    }

    public function jobFilter(Request $request)
    {
        if(!auth()->user() && !auth()->guard('employer')->user())
          {
            return redirect()->route('login');
          }
        $request->validate([
            'city' => 'nullable|exists:cities,id',
            'location' => 'nullable|exists:locations,id',
            'category.*' => 'nullable|exists:categories,id',
            'job_type.*' => 'nullable|exists:job_types,id',
            'job_shift.*' => 'nullable|exists:job_shifts,id',
            'job_experience.*' => 'nullable|exists:job_experiences,id',
            'search' => 'nullable|max:255',
        ]);
        $pageTitle = "Job Filter";
        $emptyMessage = "No data found";

        $cities = City::where('status', 1)->select('id', 'name')->with('location')->get();
        $jobTypes = JobType::where('status', 1)->select('id', 'name')->with(['job' => function ($q){
            $q->whereDate('deadline','>', Carbon::now()->toDateTimeString());
        }])->get();
        $categorys = Category::where('status', 1)->select('id', 'name')->with(['job' => function ($q){
            $q->whereDate('deadline','>', Carbon::now()->toDateTimeString());
        }])->get();
        $jobShifts = JobShift::where('status', 1)->select('id', 'name')->with(['job' => function ($q){
            $q->whereDate('deadline','>', Carbon::now()->toDateTimeString());
        }])->get();
        $jobExperiences = JobExperience::where('status', 1)->select('id', 'name')->with(['job' => function ($q){
            $q->whereDate('deadline','>', Carbon::now()->toDateTimeString());
        }])->get();

        $cityId = $request->city;
        $locationId = $request->location;
        $categoryId = $request->category;
        $search = $request->search;
        $jobTypeId = $request->job_type;
        $shiftId = $request->job_shift;
        $jobExperienceId = $request->job_experience;

        $jobs = Job::where('status', 1)->whereDate('deadline','>', Carbon::now()->toDateTimeString());
        if($request->city){
            $jobs = $jobs->where('city_id', $request->city);
        }
        if($request->location){
            $jobs = $jobs->where('location_id', $request->location);
        }
        if($request->search){
            $jobs = $jobs->where('title', 'like', "%$search%");
        }
        if($request->category){
            $jobs = $jobs->whereIn('category_id', $request->category);
        }
        if($request->job_type){
            $jobs = $jobs->whereIn('type_id', $request->job_type);
        }
        if($request->job_shift){
            $jobs = $jobs->whereIn('shift_id', $request->job_shift);
        }
        if($request->job_experience){
            $jobs = $jobs->whereIn('job_experience_id', $request->job_experience);
        }
        $jobs = $jobs->with('employer', 'location', 'city','jobApplication')->paginate(getPaginate(6));
        $jobs->appends(request()->input());
        $tempjobs = TempJob::where('status', 1)->latest()->with('employer', 'software', 'radiography', 'charting')->inRandomOrder()->paginate(getPaginate(6));
        $employer = auth()->guard('employer')->user();
        $user = Auth::user();
        return view($this->activeTemplate . 'job', compact('pageTitle', 'emptyMessage', 'jobs', 'jobTypes', 'jobShifts', 'jobExperiences', 'categorys', 'cities', 'cityId', 'locationId', 'search', 'categoryId', 'jobTypeId', 'shiftId', 'jobExperienceId', 'tempjobs','employer','user'));
    }

      public function jobDetails($id)
    {
        if(!auth()->user() && !auth()->guard('employer')->user())
          {
            return redirect()->route('login');
          }
        $pageTitle = "Job Detail";
        $emptyMessage = "No data found";
        $job = Job::where('status', 1)->where('id', $id)->firstOrFail();
        $expired = '';
        $expired_job = Job::where('status', 1)->whereDate('deadline','<', Carbon::now()->toDateTimeString())->where('id', $id)->first();
        if(!empty($expired_job))
        {
          $expired = 'Job Is Expired';
        }
        $skills = JobSkill::where('status', 1)->select('id', 'name')->orderBy('name', 'asc')->get();
        $skill_id = explode(',',$job->skill_id);
        $softwares = JobSoftware::where('status', 1)->select('id', 'name')->orderBy('name', 'asc')->get();
        $software_id = explode(',',$job->software_id);
        $companyJobs = Job::where('status', 1)->where('employer_id', $job->employer_id)->latest()->limit(4)->with('employer')->where('id', '!=', $id)->paginate(getPaginate());
        $employer = auth()->guard('employer')->user();
        $user = Auth::user();
        $lat_long = array();
        $address = '';
        if($user)
        {
            $user_add = $user->address;
            if($user_add)
            {
                $address = $user_add->address.', '.$user_add->city.', '.$user_add->state.', '.$user_add->country.', '.$user_add->zip;
            }
        }
        $lat_long = $this->get_lat_long($address);
        $job_apply = array();
        if($user)
        {  
            $job_apply = jobApply::where('job_id', $id)->where('user_id', $user->id)->where('status','!=', 2)->where('accept_by_user','!=',2)->get();
        }
        $job_applied_emp = JobApply::where('job_id', $id)->where('status','=', 1)->where('accept_by_user','=', 1)->get();
        $filled = '';
        $applied = '';
        if($job->vacancy == sizeof($job_applied_emp))
        {
          $filled = 'The position has been filled.';
        }
        else if(sizeof($job_apply) > 0)
        {
          $applied = "You've already applied to this job";
        }
        return view($this->activeTemplate . 'job_details', compact('pageTitle', 'emptyMessage', 'job', 'companyJobs','skills','skill_id','softwares','software_id','employer','user','lat_long','expired','filled','applied'));
    }
    
    public function jobCategory($id)
    {
        if(!auth()->user() && !auth()->guard('employer')->user())
          {
            return redirect()->route('login');
          }
        $pageTitle = "Job Category";
        $emptyMessage = "No data found";
        $emptyMessage = "No data found";
        $cities = City::where('status', 1)->select('id', 'name')->with('location')->get();
        $jobTypes = JobType::where('status', 1)->select('id', 'name')->with('job')->get();
        $categorys = Category::where('status', 1)->select('id', 'name')->with('job')->get();
        $jobShifts = JobShift::where('status', 1)->select('id', 'name')->with('job')->get();
        $jobExperiences = JobExperience::where('status', 1)->select('id', 'name')->with('job')->get();
        $jobs = Job::where('status', 1)->whereDate('deadline','>', Carbon::now()->toDateTimeString())->where('category_id', $id)->with('employer', 'location', 'city')->paginate(getPaginate());
        $tempjobs = '';
        $employer = auth()->guard('employer')->user();
        $user = Auth::user();
        $lat_long = array();
        $address = '';
        if($user)
        {
            $user_add = $user->address;
            if($user_add)
            {
                $address = $user_add->address.', '.$user_add->city.', '.$user_add->state.', '.$user_add->country.', '.$user_add->zip;
            }
        }
        $lat_long = $this->get_lat_long($address);
        return view($this->activeTemplate . 'job', compact('pageTitle', 'emptyMessage', 'jobs', 'cities', 'jobTypes', 'categorys', 'jobShifts', 'jobExperiences', 'tempjobs','lat_long'));
    }

    public function changeLanguage($lang = null)
    {
        $language = Language::where('code', $lang)->first();
        if (!$language) $lang = 'en';
        session()->put('lang', $lang);
        return redirect()->back();
    }

    public function blogDetails($id,$slug){
        $recentBlogs = Frontend::where('data_keys','blog.element')->orderby('id', 'DESC')->limit(9)->get();
        $blog = Frontend::where('id',$id)->where('data_keys','blog.element')->firstOrFail();
        $pageTitle = "Blog Details";
        return view($this->activeTemplate.'blog_details',compact('blog','pageTitle', 'recentBlogs'));
    }


    public function blog(){
        $pageTitle = "Blog";
        $blogs = Frontend::where('data_keys','blog.element')->orderby('id', 'DESC')->paginate(9);
        $sections = Page::where('tempname',$this->activeTemplate)->where('slug','blog')->first();
        return view($this->activeTemplate.'blog',compact('blogs','pageTitle', 'sections'));
    }

    public function footerMenu($slug, $id)
    {
        $data = Frontend::where('id', $id)->where('data_keys', 'policy_pages.element')->firstOrFail();
        $pageTitle =  $data->data_values->title;
        return view($this->activeTemplate . 'menu', compact('data', 'pageTitle'));
    }

    public function cookieAccept(){
        session()->put('cookie_accepted',true);
        return response()->json(['success' => 'Cookie accepted successfully']);
    }

    public function placeholderImage($size = null){
        $imgWidth = explode('x',$size)[0];
        $imgHeight = explode('x',$size)[1];
        $text = $imgWidth . '×' . $imgHeight;
        $fontFile = realpath('assets/font') . DIRECTORY_SEPARATOR . 'RobotoMono-Regular.ttf';
        $fontSize = round(($imgWidth - 50) / 8);
        if ($fontSize <= 9) {
            $fontSize = 9;
        }
        if($imgHeight < 100 && $fontSize > 30){
            $fontSize = 30;
        }

        $image     = imagecreatetruecolor($imgWidth, $imgHeight);
        $colorFill = imagecolorallocate($image, 100, 100, 100);
        $bgFill    = imagecolorallocate($image, 175, 175, 175);
        imagefill($image, 0, 0, $bgFill);
        $textBox = imagettfbbox($fontSize, 0, $fontFile, $text);
        $textWidth  = abs($textBox[4] - $textBox[0]);
        $textHeight = abs($textBox[5] - $textBox[1]);
        $textX      = ($imgWidth - $textWidth) / 2;
        $textY      = ($imgHeight + $textHeight) / 2;
        header('Content-Type: image/jpeg');
        imagettftext($image, $fontSize, 0, $textX, $textY, $colorFill, $fontFile, $text);
        imagejpeg($image);
        imagedestroy($image);
    }


    public function candidateCvDownload($id)
    {
        $user = User::findOrFail(decrypt($id));
        $path = imagePath()['profile']['user']['path'];
        $fullPath = $path.'/'. $user->cv;
        $title = slug($user->username);
        $ext = pathinfo($user->cv, PATHINFO_EXTENSION);
        $mimetype = mime_content_type($fullPath);
        header('Content-Disposition: attachment; filename="' . $title . '.' . $ext . '";');
        header("Content-Type: " . $mimetype);
        return readfile($fullPath);
    }

    public function contactWithCompany(Request $request)
    {
        $request->validate([
            'employer_id' => 'required|exists:employers,id',
            'name' => 'required|max:80',
            'email' => 'required|max:80',
            'message' => 'required|max:500'
        ]);
        $employer = Employer::findOrFail($request->employer_id);
        notify($employer, 'CONTACT_WITH_COMPANY',[
            'name' => $request->name,
            'email' => $request->email,
            'message' => $request->message
        ]);
        $notify[] = ['success', 'Contact mail has been submitted'];
        return back()->withNotify($notify);
    }

    public function contactWithEmployer(Request $request)
    {
        $request->validate([
            'candidate_id' => 'required|exists:users,id',
            'name' => 'required|max:80',
            'email' => 'required|max:80',
            'message' => 'required|max:500'
        ]);
        $employer = User::findOrFail($request->candidate_id);
        notify($employer, 'CONTACT_WITH_CANDIDATE',[
            'name' => $request->name,
            'email' => $request->email,
            'message' => $request->message
        ]);
        $notify[] = ['success', 'Contact mail has been submitted'];
        return back()->withNotify($notify);
    }

    public function downloadInvoice($filename)
    {
        $filename = $filename.".pdf";
        $path = storage_path('app/public/uploads/');
        $file = storage_path('app/public/uploads/'.$filename);
        $headers = array(
            'Content-Type: application/pdf',
        );
        return \Response::download($file, $filename, $headers);
    }
    public function previewInvoice($filename)
    {
        $filename = $filename.".pdf";
        $file = storage_path('app/public/uploads/'.$filename);
        $headers = array(
            'Content-Type: application/pdf',
        );
        return response()->file($file, $headers);
    }
    public function printInvoice($filename)
    {
      $filename = $filename.".pdf";
      $file = storage_path('app/public/uploads/'.$filename);
      $headers = array(
          'Content-Type: application/pdf',
      );
      return response()->file($file, $headers,$script);
    }
    public function get_lat_long($address)
    {
        $apikey = Extension::where('act', 'map_api')->where('status', 1)->first();
        $googlemapkey = '';
        if(!empty($apikey))
        {
          $googlemapkey = $apikey->shortcode->api_key->value;
        }
        $url = "https://maps.google.com/maps/api/geocode/json?address=".urlencode($address).'&sensor=false&key='.$googlemapkey;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $responseJson = curl_exec($ch);
        curl_close($ch);

        $response = json_decode($responseJson);
        if(!empty($response))
        {
            if ($response->status == 'OK') {
                $latitude = $response->results[0]->geometry->location->lat;
                $longitude = $response->results[0]->geometry->location->lng;
    
                $data['lat'] = $latitude;
                $data['long'] = $longitude;
            } else {
                $data['lat'] = 0;
                $data['long'] = 0;
            }
        }
        return $data;
    }
    public function paddownload($filename)
    {
      $path = imagePath()['profile']['user']['path'];
      $file = $path.'/'.$filename;;
      $headers = array(
          'Content-Type: application/pdf',
      );
      return \Response::download($file, $filename, $headers);
    }
}
