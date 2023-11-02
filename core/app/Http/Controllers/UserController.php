<?php

namespace App\Http\Controllers;

use App\Lib\GoogleAuthenticator;
use App\Models\Degree;
use App\Models\EducationalQualification;
use App\Models\EmploymentHistory;
use App\Models\FavoriteItem;
use App\Models\GeneralSetting;
use App\Models\JobApply;
use App\Models\JobSkill;
use App\Models\JobSoftware;
use App\Models\LevelOfEducation;
use App\Models\SupportTicket;
use App\Models\Transaction;
use App\Models\Invoice;
use App\Models\Employer;
use App\Models\Review;
use App\Models\Badge;
use App\Models\Category;
use App\Models\Extension;
use App\Models\Admin;
use App\Models\Job;
use App\Models\User;
use App\Rules\FileTypeValidate;
use App\Models\TimeReporting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\DB;
use PDF;

class UserController extends Controller
{
    public function __construct()
    {
        $this->activeTemplate = activeTemplate();
    }

    public function home()
    {
        $pageTitle = 'Dashboard';
        $emptyMessage = "No data found";
        $user = Auth::user();
        $general = GeneralSetting::first();
        $jobApplyCount = JobApply::where('user_id', $user->id)->count();
        $favoriteJobCount = FavoriteItem::where('user_id', $user->id)->count();
        $totalTicketCount = SupportTicket::where('user_id', $user->id)->count();
        $jobApplys = JobApply::where('user_id', $user->id)->orderBy('id', 'DESC')->with('job', 'job.employer','review')->paginate(getPaginate());
        $totalInvoice = Invoice::where('user_id', $user->id)->where('prefix','CNINV')->count();
        $weekDay = date('w', strtotime(date('Y-m-d H:i:s')));
        // $current_jobApplys = JobApply::where('user_id', $user->id)->where('in_time', null)->where('status', 1)->where('accept_by_user', 1)->orderBy('id', 'DESC')->with('job', 'job.employer')->paginate(getPaginate());
        $current_per_job = JobApply::where('user_id', $user->id)->orderBy('id', 'DESC')->with('job', 'job.employer','job.category')->where('status',1)->where('job_status','!=',2)->where('job_type','permanent_job')->where('accept_by_user',1)->get();
        $per_job = array();
        foreach ($current_per_job as $value) {
            $per_job[] = $value->id;
        }
        $current_jobApplys = JobApply::where('user_id', $user->id)->orderBy('id', 'DESC')->with('job', 'job.employer','job.category')->where('status',1)->where('job_status','!=',2)->where('accept_by_user',1)->paginate(getPaginate());
        $curr =  sizeof($current_jobApplys);
        $job_reportinglist = TimeReporting::whereHas('job_apply',function($q) use ($user){
            $q->where('user_id',$user->id);
        })->whereDate('in_time','=', Carbon::now()->format('Y-m-d'))->select('job_applies_id')->get();
        $job_rep = array();
        foreach ($job_reportinglist as $value) {
            $job_rep[] = $value->job_applies_id;
        }
        $job_out_time_reporting = TimeReporting::whereHas('job_apply',function($q) use ($user){
            $q->where('user_id',$user->id);
        })->whereDate('out_time','=', Carbon::now()->format('Y-m-d'))->select('job_applies_id')->get();
        $job_out_rep = array();
        foreach ($job_out_time_reporting as $value) {
            $job_out_rep[] = $value->job_applies_id;
        }
        $check_profile = check_user_profile();
        if(!$check_profile)
        {
            $notify[] = ['success', 'Please complete your profile before proceeding!'];
            return redirect()->route('user.profile.setting')->withNotify($notify);
        }
        $invoices = Invoice::where('user_id', $user->id)->where('prefix','CNINV')->with('job', 'job.employer','job.category', 'user')->get();
         $jobs_detail = array();
         $jobs_array = array();
         $job1 = array();
         $jobs_already_applied = JobApply::select('job_id')->where('user_id', $user->id)->orderBy('id', 'DESC')->get();
         foreach ($jobs_already_applied as $key)
         {
           $job1[] = $key['job_id'];
         }
         $job_applied_array = implode(',',$job1);
         $jobs_info = Job::select('jobs.id AS id','jobs.created_at AS start','jobs.deadline AS end','jobs.title AS title')
                      ->Join('users','users.designation','=','jobs.category_id')
                      ->where('users.id',$user->id)
                      ->where('jobs.status',1)
                      ->whereDate('jobs.deadline','>', Carbon::now()->toDateTimeString())
                      ->whereNotIn('jobs.id',$job1)
                      ->groupBy('jobs.id')
                      ->get();
        $jobs = $jobs_info->toJson();
        return view($this->activeTemplate . 'user.dashboard', compact('pageTitle', 'jobApplys', 'jobApplyCount', 'favoriteJobCount', 'totalTicketCount', 'totalInvoice', 'emptyMessage','user','current_jobApplys','job_rep','curr','invoices','job_out_rep','per_job','weekDay','jobs'));
    }

    public function profile()
    {
        $pageTitle = "Profile Settings";
        $user = Auth::user();
        $designation = Category::where('status', 1)->select('*')->orderBy('name', 'asc')->latest()->paginate(getPaginate());
        $skills = JobSkill::where('status', 1)->select('id', 'name')->orderBy('name', 'asc')->get();
        $softwares = JobSoftware::where('status', 1)->select('id', 'name')->orderBy('name', 'asc')->get();
        $software_id = explode(',',$user->software_id);
        $countries = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $apikey = Extension::where('act', 'map_api')->where('status', 1)->first();
        $googlemapkey = '';
        if(!empty($apikey))
        {
          $googlemapkey = $apikey->shortcode->api_key->value;
        }
        $candidate_rev = Review::where('user_id',$user->id)->where('comment','rating by employer')->select('*')->get();
        $avg = $candidate_rev->average('rating');
        $avg1 = $candidate_rev->sum('rating');
        $medal = getmedal($avg1);
        $count = $candidate_rev->count();
        return view($this->activeTemplate. 'user.profile_setting', compact('pageTitle','user', 'skills','designation','softwares','software_id','countries','googlemapkey','avg','count','medal'));
    }

    public function submitProfile(Request $request)
    {
        $user = Auth::user();
        $countryData = json_decode(file_get_contents(resource_path('views/partials/country.json')));
  session()->put('firstname',$request['firstname']);
        session()->put('lastname',$request['lastname']);
        session()->put('email',$request['email']);
        session()->put('mobile',$request['mobile']);
        session()->put('designation',$request['designation']);
        session()->put('gender',$request['gender']);
        session()->put('birth_date',$request['birth_date']);
        session()->put('national_id',$request['national_id']);
        session()->put('address',$request['address']);
        session()->put('state',$request['state']);
        session()->put('zip',$request['zip']);
        session()->put('city',$request['city']);
        session()->put('country',$request['country']);
        session()->put('software_id',$request['software_id']);
        session()->put('language',$request['language']);
        session()->put('detail',$request['detail']);
        session()->put('facebook',$request['facebook']);
        session()->put('twitter',$request['twitter']);
        session()->put('pinterest',$request['pinterest']);
        session()->put('linkedin',$request['linkedin']);
        session()->put('candidate_rate',$request['candidate_rate']);
        session()->put('answer1',$request['answer1']);
        session()->put('answer2',$request['answer2']);
        session()->put('answer3',$request['answer3']);
        session()->put('answer4',$request['answer4']);
        session()->put('answer5',$request['answer5']);
        session()->put('accountno',$request['accountno']);
        session()->put('name1', $request['name1']);
        session()->put('name2', $request['name2']);
        session()->put('name3', $request['name3']);
        session()->put('contact1', $request['contact1']);
        session()->put('contact2', $request['contact2']);
        session()->put('contact3',$request['contact3']);
        session()->put('email1', $request['email1']);
        session()->put('email2',$request['email2']);
        session()->put('email3',$request['email3']);
        $request->validate([
            'firstname' => 'required|string|max:40',
            'lastname' => 'required|string|max:40',
            'email' => 'required|email|max:40|unique:users,email,'.$user->id,
            'mobile' => 'required|max:40|unique:users,mobile,'.$user->id,
            'designation' => 'required|max:120',
            'gender' => 'required|in:1,2',
            'birth_date' => 'required|date',
            'national_id' => 'required|max:40',
            'address' => 'required|max:80',
            'state' => 'required|max:80',
            'zip' => 'required|max:40',
            'city' => 'required|max:50',
            'country' => 'required',
            'software' => 'nullable|array|exists:job_software,id',
            'language' => 'required|array',
            'detail' => 'required',
            'facebook' => 'nullable|url',
            'twitter' => 'nullable|url',
            'pinterest' => 'nullable|url',
            'linkedin' => 'nullable|url',
            'image' => ['nullable','image',new FileTypeValidate(['jpg','jpeg','png'])],
            'cv' => ['nullable',new FileTypeValidate(['pdf'])],
            'candidate_rate' => 'required',
            'upload_pad' => ['nullable','image',new FileTypeValidate(['jpg','jpeg','png','pdf'])],
            'accountno' => 'required',
        ]);
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->email = $request->email;
        $user->mobile = $request->mobile;
        $user->mobile_code = $request->mobile_code;
        $user->designation = $request->designation;
        $user->gender = $request->gender;
        $user->married = $request->married;
        $user->birth_date = $request->birth_date;
        $user->national_id = $request->national_id;
        $user->candidate_rate = $request->candidate_rate;
        $country_code = array();
        $countryData = (array)json_decode(file_get_contents(resource_path('views/partials/country.json')));
        for ($i=0; $i < sizeof(array_column($countryData, 'country')); $i++)
        {
          if(array_column($countryData, 'country')[$i] == ucfirst($request->country))
          {
            $country_code = array_keys($countryData)[$i];
          }
          if(array_column($countryData, 'short_code')[$i] == ucfirst($request->country))
          {
            $country_code = array_keys($countryData)[$i];
          }
        }
        if(empty($country_code))
        {
            $notify[] = ['warning', 'Please fill valid country'];
            return redirect()->route('user.profile.setting')->withNotify($notify);
        }
        $user->country_code = $country_code;
        $user->address = [
            'address' => $request->address,
            'state' => $request->state,
            'zip' => $request->zip,
            'country' =>$request->country,
            'city' => $request->city,
        ];
        $user->answers = [
            'answer1' => $request->answer1,
            'answer2' => $request->answer2,
            'answer3' => $request->answer3,
            'answer4' => $request->answer4,
            'answer5' => $request->answer5,
        ];
        $user->references = [
            'name1' => $request->name1,
            'name2' => $request->name2,
            'name3' => $request->name3,
            'contact1' => $request->contact1,
            'contact2' => $request->contact2,
            'contact3' => $request->contact3,
            'email1' => $request->email1,
            'email2' => $request->email2,
            'email3' => $request->email3,
        ];
        $user->language = $request->language;
        $user->skill = $request->skill;
        $user->software_id =implode(',',$request->software_id);
        $user->details = $request->detail;

        $user->socialMedia =  [
            'facebook' => $request->facebook,
            'twitter' => $request->twitter,
            'pinterest' => $request->pinterest,
            'linkedin' => $request->linkedin
        ];
        if ($request->hasFile('image')) {
            $location = imagePath()['profile']['user']['path'];
            $size = imagePath()['profile']['user']['size'];
            $filename = uploadImage($request->image, $location, $size, $user->image);
            $user->image = $filename;
        }
        $user->accountno = $request->accountno;
        if ($request->hasFile('upload_pad')) {
            $location = imagePath()['profile']['user']['path'];
            $filenameCv = uploadFile($request->upload_pad, $location, null, $user->upload_pad);
            $user->upload_pad = $filenameCv;
        }
        $user->save();
        $notify[] = ['success', 'Profile updated successfully.'];
        return back()->withNotify($notify);
    }

    public function uploadCv(Request $request)
    {
        $request->validate([
            'cv' => ['required',new FileTypeValidate(['pdf'])],
        ]);
        $user = Auth::user();
        if ($request->hasFile('cv')) {
            $location = imagePath()['profile']['user']['path'];
            $filenameCv = uploadFile($request->cv, $location, null, $user->cv);
            $user->cv = $filenameCv;
        }
        $user->save();
        $notify[] = ['success', 'cv uploaded successfully.'];
        return back()->withNotify($notify);
    }

    public function uploadLicense(Request $request)
    {
        $request->validate([
            'license' => ['required',new FileTypeValidate(['png','jpg'])],
        ]);
        $user = Auth::user();
        if ($request->hasFile('license')) {
            $location = imagePath()['profile']['user']['path'];
            $filenameLicense = uploadFile($request->license, $location, null, $user->license);
            $user->license = $filenameLicense;
        }
        $user->save();
        $notify[] = ['success', 'License uploaded successfully.'];
        return back()->withNotify($notify);
    }

    public function uploadCertificate(Request $request)
    {
        $request->validate([
            'certificate' => ['required',new FileTypeValidate(['png','jpg'])],
        ]);
        $user = Auth::user();
        if ($request->hasFile('certificate')) {
            $location = imagePath()['profile']['user']['path'];
            $filenameCertificate = uploadFile($request->certificate, $location, null, $user->certificate);
            $user->certificate = $filenameCertificate;
        }
        $user->save();
        $notify[] = ['success', 'Certificate uploaded successfully.'];
        return back()->withNotify($notify);
    }

    public function uploadDrivingLicense(Request $request)
    {
        $request->validate([
            'driving_license' => ['required',new FileTypeValidate(['png','jpg'])],
        ]);
        $user = Auth::user();
        if ($request->hasFile('driving_license')) {
            $location = imagePath()['profile']['user']['path'];
            $filenamedriving_license = uploadFile($request->driving_license, $location, null, $user->driving_license);
            $user->driving_license = $filenamedriving_license;
        }
        $user->save();
        $notify[] = ['success', 'Driving License uploaded successfully.'];
        return back()->withNotify($notify);
    }
    public function covid19id(Request $request)
    {
        $request->validate([
            'covid19id' => ['required',new FileTypeValidate(['png','jpg'])],
        ]);
        $user = Auth::user();
        if ($request->hasFile('covid19id')) {
            $location = imagePath()['profile']['user']['path'];
            $filenamecovid19id = uploadFile($request->covid19id, $location, null, $user->covid19id);
            $user->covid19id = $filenamecovid19id;
        }
        $user->save();
        $notify[] = ['success', 'Covid 19 Id uploaded successfully.'];
        return back()->withNotify($notify);
    }
    public function pdfViewer()
    {
        $pageTitle = "Credentials";
        $user = Auth::user();

        if($user->cv != null){
            $path = imagePath()['profile']['user']['path'];
            $fullPath = $path.'/'. $user->cv;
        }else{
            $fullPath = null;
        }
        if($user->certificate != null){
            $path = imagePath()['profile']['user']['path'];
            $fullPathCertificate = $path.'/'. $user->certificate;
        }else{
            $fullPathCertificate = null;
        }
        if($user->license != null){
            $path = imagePath()['profile']['user']['path'];
            $fullPathLicense = $path.'/'. $user->license;
        }else{
            $fullPathLicense = null;
        }
        if($user->driving_license != null){
            $path = imagePath()['profile']['user']['path'];
            $fullPathDrivingLicense = $path.'/'. $user->driving_license;
        }else{
            $fullPathDrivingLicense = null;
        }
        if($user->covid19id != null){
            $path = imagePath()['profile']['user']['path'];
            $fullPathcovid19id = $path.'/'. $user->covid19id;
        }else{
            $fullPathcovid19id = null;
        }
        $check_profile = check_user_profile();
        if(!$check_profile)
        {
            $notify[] = ['success', 'Please complete your profile before proceeding!'];
            return redirect()->route('user.profile.setting')->withNotify($notify);
        }
        return view($this->activeTemplate . 'user.pdf_view', compact('user', 'fullPath', 'fullPathCertificate', 'fullPathLicense', 'fullPathDrivingLicense', 'pageTitle','fullPathcovid19id'));
    }

    public function educationIndex(Request $request)
    {
        $pageTitle = "Educational Qualification";
        $emptyMessage = "No data found";
        $user = Auth::user();
        $levels = LevelOfEducation::where('status', 1)->select('id', 'name')->orderby('name','asc')->get();
        $degrees = Degree::where('status', 1)->select('id', 'name')->orderby('name','asc')->get();
        $educations = EducationalQualification::where('user_id', $user->id)->get();
        $check_profile = ($user->id);
        if(!$check_profile)
        {
            $notify[] = ['success', 'Please complete your profile before proceeding!'];
            return redirect()->route('user.profile.setting')->withNotify($notify);
        }
        return view($this->activeTemplate . 'user.education', compact('pageTitle', 'emptyMessage', 'educations', 'levels', 'degrees'));
    }

    public function educationStore(Request $request)
    {
        $request->validate([
            'level_id' => 'required|exists:level_of_education,id',
            'institute' => 'required|max:255',
            'passing_year' => 'required|date_format:Y',
            'degree' => 'required|exists:exam_or_degrees,id'
        ]);
        $level = LevelOfEducation::where('id', $request->level_id)->where('status', 1)->firstOrFail();
        $degree = Degree::where('id', $request->degree)->where('status', 1)->firstOrFail();
        $education = new EducationalQualification;
        $education->user_id = auth()->user()->id;
        $education->level_of_education_id = $request->level_id;
        $education->institute = $request->institute;
        $education->passing_year = $request->passing_year;
        $education->degree_id = $request->degree;
        $education->save();
        $notify[] =['success', 'Education Qualification has been created'];
        return back()->withNotify($notify);
}


    public function educationUpdate(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:educational_qualifications,id',
            'level_id' => 'required|exists:level_of_education,id',
            'institute' => 'required|max:255',
            'passing_year' => 'required|date_format:Y',
            'degree' => 'required|exists:exam_or_degrees,id'
        ]);
        $level = LevelOfEducation::where('id', $request->level_id)->where('status', 1)->firstOrFail();
        $degree = Degree::where('id', $request->degree)->where('status', 1)->firstOrFail();
        $education = EducationalQualification::find($request->id);
        $education->user_id = auth()->user()->id;
        $education->level_of_education_id = $request->level_id;
        $education->institute = $request->institute;
        $education->passing_year = $request->passing_year;
        $education->degree_id = $request->degree;
        $education->save();
        $notify[] =['success', 'Education Qualification has been created'];
        return back()->withNotify($notify);
    }


    public function educationDelete(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:educational_qualifications,id'
        ]);
        $user = Auth::user();
        $education = EducationalQualification::where('id', $request->id)->where('user_id', $user->id)->firstOrFail();
        $education->delete();
        $notify[] = ['success', 'Education Qualification has been deleted'];
        return back()->withNotify($notify);
    }

    public function employmentIndex()
    {
        $user = Auth::user();
        $pageTitle = "Employment History";
        $emptyMessage = "No data found";
        $employments = EmploymentHistory::where('user_id', $user->id)->get();
        $check_profile = check_user_profile();
        if(!$check_profile)
        {
            $notify[] = ['success', 'Please complete your profile before proceeding!'];
            return redirect()->route('user.profile.setting')->withNotify($notify);
        }
        return view($this->activeTemplate . 'user.employment', compact('pageTitle', 'emptyMessage', 'employments'));
    }

    public function employmentStore(Request $request)
    {
        $request->validate([
            'company_name' => 'required|max:120',
            'designation' => 'required|max:120',
            'department' => 'required|max:120',
            'start_date' => 'required|date',
            'currently_work' => 'nullable|in:1',
            'end_date' => 'nullable|date|after:start_date',
            'responsibilities' => 'required'
        ]);
        if($request->end_date == null){
            $request->validate([
                'currently_work' => 'required|in:1',
            ]);
        }else{
            $request->validate([
                'end_date' => 'required|date|after:start_date',
            ]);
        }
        $employment = new EmploymentHistory;
        $employment->user_id = auth()->user()->id;
        $employment->company_name = $request->company_name;
        $employment->designation = $request->designation;
        $employment->department = $request->department;
        $employment->start_date = $request->start_date;
        $employment->end_date = $request->end_date;
        $employment->currently_work = $request->currently_work ? $request->currently_work : 0;
        $employment->responsibilities = $request->responsibilities;
        $employment->save();
        $notify[] = ['success', 'Employment history has been created'];
        return back()->withNotify($notify);
    }


    public function employmentUpdate(Request $request)
    {
        $request->validate([
            'company_name' => 'required|max:120',
            'designation' => 'required|max:120',
            'department' => 'required|max:120',
            'start_date' => 'required|date',
            'currently_work' => 'nullable|in:1',
            'end_date' => 'nullable|date|after:start_date',
            'responsibilities' => 'required'
        ]);
        if($request->end_date == null){
            $request->validate([
                'currently_work' => 'required|in:1',
            ]);
        }else{
            $request->validate([
                'end_date' => 'required|date|after:start_date',
            ]);
        }
        $employment = EmploymentHistory::findOrFail($request->id);
        $employment->user_id = auth()->user()->id;
        $employment->company_name = $request->company_name;
        $employment->designation = $request->designation;
        $employment->department = $request->department;
        $employment->start_date = $request->start_date;
        $employment->end_date = $request->end_date;
        $employment->responsibilities = $request->responsibilities;
        $employment->save();
        $notify[] = ['success', 'Employment history has been updated'];
        return back()->withNotify($notify);
    }


    public function employmentDelete(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:employment_histories,id'
        ]);
        $user = Auth::user();
        $employment = EmploymentHistory::where('id', $request->id)->where('user_id', $user->id)->firstOrFail();
        $employment->delete();
        $notify[] = ['success', 'Employment history has been deleted'];
        return back()->withNotify($notify);
    }

    public function favoriteItem($id)
    {
        $user = Auth::user();
        $already_exist_job = FavoriteItem::where('user_id', $user->id)->where('job_id', $id)->with('job')->get()->count();
        if($already_exist_job  > 0)
        {
          $notify[] = ['warning', 'Already exists to favourite job list'];
          return back()->withNotify($notify);
        }
        else
        {
          $featureItem = new FavoriteItem();
          $featureItem->user_id = $user->id;
          $featureItem->job_id = $id;
          $featureItem->save();
          $notify[] = ['success', 'Favourite job added'];
          return back()->withNotify($notify);
        }

    }

    public function favoriteJob()
    {
        $pageTitle = "Favourite Job List";
        $emptyMessage = "No data found";
        $user = Auth::user();
        $favorites = FavoriteItem::where('user_id', $user->id)->with('job')->paginate(getPaginate());
        $check_profile = check_user_profile();
        if(!$check_profile)
        {
            $notify[] = ['success', 'Please complete your profile before proceeding!'];
            return redirect()->route('user.profile.setting')->withNotify($notify);
        }
        return view($this->activeTemplate . 'user.favorite_job', compact('pageTitle', 'emptyMessage', 'favorites'));
    }

    public function favoriteJobdelete(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:favorite_items,id'
        ]);
        $user = Auth::user();
        $favorite = FavoriteItem::where('user_id', $user->id)->where('id', $request->id)->delete();
        $notify[] = ['success', 'Item has been removed'];
        return back()->withNotify($notify);
    }

    public function applyJob(Request $request)
    {
      $user = Auth::user();
      $job_cat = Job::where('id', $request->job_id)->first();
      if($user->designation != $job_cat->category_id)
      {
        $notify[] = ['error', 'You are not eligible for this job. Please apply to another job.'];
        return back()->withNotify($notify);
      }
      $educations = EducationalQualification::where('user_id', $user->id)->get()->count();
      $employments = EmploymentHistory::where('user_id', $user->id)->get()->count();
      if(($educations  == 0) && ($employments  == 0) && (empty($user->cv) || empty($user->certificate) || empty($user->license) || empty($user->driving_license) || empty($user->covid19id)))
      {
        $notify[] = ['error', 'Please fill out the details available on : Educational Qualification, Employment History,Credentials Pages'];
        return back()->withNotify($notify);
      }
      else if(($educations  == 0) && ($employments  == 0))
      {
        $notify[] = ['error', 'Please fill out the details available on : Educational Qualification, Employment History Pages'];
        return back()->withNotify($notify);
      }
      else if(($educations  == 0) && (empty($user->cv) && empty($user->certificate) || empty($user->license) || empty($user->driving_license)  || empty($user->covid19id)))
      {
        $notify[] = ['error', 'Please fill out the details available on : Educational Qualification,Credentials Pages'];
        return back()->withNotify($notify);
      }
      else if(($employments  == 0) && (empty($user->cv) && empty($user->certificate) || empty($user->license) || empty($user->driving_license)  || empty($user->covid19id)))
      {
        $notify[] = ['error', 'Please fill out the details available on : Employment History,Credentials'];
        return back()->withNotify($notify);
      }
      else if(empty($user->cv) && empty($user->certificate) || empty($user->license) || empty($user->driving_license)  || empty($user->covid19id))
      {
        $notify[] = ['error', 'Please fill out the details available on : Credentials Page'];
        return back()->withNotify($notify);
      }
      else if(($employments  == 0))
      {
        $notify[] = ['error', 'Please fill out the details available on : Employment History Page'];
        return back()->withNotify($notify);
      }
      else if(($educations  == 0))
      {
        $notify[] = ['error', 'Please fill out the details available on : Educational Qualification Page'];
        return back()->withNotify($notify);
      }
      else
      {
        $request->validate([
            'job_id' => 'required|exists:jobs,id',
        ]);
        $job_apply = jobApply::where('job_id', $request->id)->where('user_id', $user->id)->where('status','!=', 2)->where('job_type',$request->job_type)->where('accept_by_user','!=',2)->get();
        $job_applied_emp = JobApply::where('job_id', $request->id)->where('status','=', 1)->where('accept_by_user','=', 1)->get();
        if($job_cat->vacancy == sizeof($job_applied_emp))
        {
          $notify[] = ['error', 'This job is FILLED UP'];
          return back()->withNotify($notify);
        }
        if(sizeof($job_apply) > 0)
        {
            $notify[] = ['error', 'Already applied this job'];
            return back()->withNotify($notify);
        }
        $jobApply = new JobApply();
        $jobApply->job_id = $request->job_id;
        $jobApply->user_id = $user->id;
        $jobApply->job_type = $request->job_type;
        $jobApply->save();
        $employer = Employer::findOrFail($jobApply->job->employer->id);
        notify($employer, 'JOB_APPLIED',[
            'candidate_name' => $jobApply->user->firstname.' '.$jobApply->user->lastname,
            'job_title' => $jobApply->job->title,
        ]);
        $notify[]  = ['success', 'Applied successfully'];
        return back()->withNotify($notify);
      }
    }

    public function jobApplication()
    {
        $pageTitle = "Job Application List";
        $emptyMessage = "No data found";
        $user = Auth::user();
        $jobApplys = JobApply::where('user_id', $user->id)->orderBy('id', 'DESC')->with('job', 'job.employer')->paginate(getPaginate());
        $check_profile = check_user_profile();
        if(!$check_profile)
        {
            $notify[] = ['success', 'Please complete your profile before proceeding!'];
            return redirect()->route('user.profile.setting')->withNotify($notify);
        }
        return view($this->activeTemplate . 'user.job_apply', compact('pageTitle', 'emptyMessage', 'jobApplys','user'));
    }

    public function changePassword()
    {
        $pageTitle = 'Change Password';
        $check_profile = check_user_profile();
        if(!$check_profile)
        {
            $notify[] = ['success', 'Please complete your profile before proceeding!'];
            return redirect()->route('user.profile.setting')->withNotify($notify);
        }
        return view($this->activeTemplate . 'user.password', compact('pageTitle'));
    }

    public function submitPassword(Request $request)
    {
        $password_validation = Password::min(6);
        $general = GeneralSetting::first();
        if ($general->secure_password) {
            $password_validation = $password_validation->mixedCase()->numbers()->symbols()->uncompromised();
        }
        $this->validate($request, [
            'current_password' => 'required',
            'password' => ['required','confirmed',$password_validation]
        ]);
        try {
            $user = auth()->user();
            if (Hash::check($request->current_password, $user->password)) {
                $password = Hash::make($request->password);
                $user->password = $password;
                $user->save();
                $notify[] = ['success', 'Password changes successfully.'];
                return back()->withNotify($notify);
            } else {
                $notify[] = ['error', 'The password doesn\'t match!'];
                return back()->withNotify($notify);
            }
        } catch (\PDOException $e) {
            $notify[] = ['error', $e->getMessage()];
            return back()->withNotify($notify);
        }
    }


    public function show2faForm()
    {
        $general = GeneralSetting::first();
        $ga = new GoogleAuthenticator();
        $user = auth()->user();
        $secret = $ga->createSecret();
        $qrCodeUrl = $ga->getQRCodeGoogleUrl($user->username . '@' . $general->sitename, $secret);
        $pageTitle = 'Two Factor Authentication';
        $check_profile = check_user_profile();
        if(!$check_profile)
        {
            $notify[] = ['success', 'Please complete your profile before proceeding!'];
            return redirect()->route('user.profile.setting')->withNotify($notify);
        }
        return view($this->activeTemplate.'user.twofactor', compact('pageTitle', 'secret', 'qrCodeUrl'));
    }

    public function create2fa(Request $request)
    {
        $user = auth()->user();
        $this->validate($request, [
            'key' => 'required',
            'code' => 'required',
        ]);
        $response = verifyG2fa($user,$request->code,$request->key);
        if ($response) {
            $user->tsc = $request->key;
            $user->ts = 1;
            $user->save();
            $userAgent = getIpInfo();
            $osBrowser = osBrowser();
            notify($user, '2FA_ENABLE', [
                'operating_system' => @$osBrowser['os_platform'],
                'browser' => @$osBrowser['browser'],
                'ip' => @$userAgent['ip'],
                'time' => @$userAgent['time']
            ]);
            $notify[] = ['success', 'Google authenticator enabled successfully'];
            return back()->withNotify($notify);
        } else {
            $notify[] = ['error', 'Wrong verification code'];
            return back()->withNotify($notify);
        }
    }


    public function disable2fa(Request $request)
    {
        $this->validate($request, [
            'code' => 'required',
        ]);

        $user = auth()->user();
        $response = verifyG2fa($user,$request->code);
        if ($response) {
            $user->tsc = null;
            $user->ts = 0;
            $user->save();
            $userAgent = getIpInfo();
            $osBrowser = osBrowser();
            notify($user, '2FA_DISABLE', [
                'operating_system' => @$osBrowser['os_platform'],
                'browser' => @$osBrowser['browser'],
                'ip' => @$userAgent['ip'],
                'time' => @$userAgent['time']
            ]);
            $notify[] = ['success', 'Two factor authenticator disable successfully'];
        } else {
            $notify[] = ['error', 'Wrong verification code'];
        }
        return back()->withNotify($notify);
    }
    public function approved(Request $request)
    {
        $user = auth()->user();
        $general = GeneralSetting::first();
        $jobApply = jobApply::where('job_id', $request->job_id)->where('user_id', $user->id)->findOrFail($request->id);
        $jobApply->accept_by_user = 1;
        $jobApply->save();
        $jobFullApplys = JobApply::where('id',$request->id)->where('user_id', $user->id)->with('job', 'job.category')->get();
        foreach($jobFullApplys as $jobApply)
        {
            $job_id = $jobApply->job->id;
            $job_type = $jobApply->job_type;
            if($job_type == 'permanent_job')
            {
                $rate = $jobApply->job->category['full_timerate'];
            }
            else
            {
                $rate = $jobApply->job->category['temp_rate'];
            }
        }
        $total = $rate;
        $tax_amt = ((int)$general->tax_rate * $total)/100;
        $f_report = new Invoice;
        $f_report->job_id = $job_id;
        $f_report->user_id = $user->id;
        $f_report->job_type = $job_type;
        $f_report->prefix = 'AGINV';
        $f_report->invoice_amount = $total;
        $f_report->hourly_price = $rate;
        $f_report->working_hours = 1;
        $f_report->tax_rate = $general->tax_rate;
        $f_report->tax_amt = $tax_amt;
        $f_report->invoice_amt_with_tax = $total + $tax_amt;
        $f_report->save();

        $tot_amt = $total + $tax_amt;

        $employer = Employer::findOrFail($jobApply->job->employer->id);
        $employer->balance -= $tot_amt;
        $employer->save();

        $transaction = new Transaction();
        $transaction->employer_id = $employer->id;
        $transaction->amount = $tot_amt;
        $transaction->post_balance = $employer->balance;
        $transaction->trx_type = '-';
        $transaction->details = 'Payment for invoice <a href="'.route('download.invoice','AGINV-00'.$f_report->id).'">AGINV-00'.$f_report->id.'</a>';
        $transaction->trx = getTrx();
        $transaction->save();

        $this->generateAgencyPdf($f_report);
        $notify[]  = ['success', 'Job accepted successfully'];
        return back()->withNotify($notify);
    }

    public function cancel(Request $request)
    {
        $user = auth()->user();
        $jobApply = jobApply::where('job_id', $request->job_id)->where('user_id', $user->id)->findOrFail($request->id);
        $jobApply->accept_by_user = 2;
        $jobApply->save();
        $employer = Employer::findOrFail($jobApply->job->employer->id);
        notify($employer, 'JOB_REJECTED',[
            'candidate_name' => $jobApply->user->firstname.' '.$jobApply->user->lastname,
            'job_title' => $jobApply->job->title,
        ]);
        $admins = Admin::select('*')->get();
        foreach($admins as $admin)
        {
            notify($admin, 'JOB_REJECTED_ADMIN',[
                'company_name' => $jobApply->job->employer->company_name,
                'candidate_name' => $jobApply->user->firstname.' '.$jobApply->user->lastname,
                'job_title' => $jobApply->job->title,
                'admin_name' => $admin->name,
            ]);
        }
        $notify[]  = ['success', 'You have rejected the Job'];
        return back()->withNotify($notify);
    }

    public function currentjob()
    {
        $pageTitle = "Current Job";
        $emptyMessage = "No data found";
        $user = Auth::user();
        $jobApplys = JobApply::where('user_id', $user->id)->orderBy('id', 'DESC')->with('job', 'job.employer','job.category','review')->where('status',1)->where('accept_by_user',1)->paginate(getPaginate());          $check_profile = check_user_profile();
        if(!$check_profile)
        {
            $notify[] = ['success', 'Please complete your profile before proceeding!'];
            return redirect()->route('user.profile.setting')->withNotify($notify);
        }
        return view($this->activeTemplate . 'user.current_job', compact('pageTitle', 'emptyMessage', 'jobApplys','user'));
    }

    public function currjobreport($id)
    {
        $pageTitle = "Current Job";
        $emptyMessage = "No data found";
        $user = Auth::user();
        $jobApplys = JobApply::where('id',$id)->where('user_id', $user->id)->with('job', 'job.employer','job.category', 'user')->where('status',1)->where('accept_by_user',1)->get();
        $invoices = Invoice::where('job_id',$jobApplys[0]->job_id)->where('user_id', $user->id)->where('prefix','CNINV')->with('job', 'job.employer','job.category', 'user')->where('job_type',$jobApplys[0]->job_type)->get();
        $invoicescur = Invoice::where('job_id',$jobApplys[0]->job_id)->where('prefix','CNINV')->where('user_id', $user->id)->where('prefix','CNINV')->with('job', 'job.employer','job.category', 'user')->where('job_type',$jobApplys[0]->job_type)->get();
        $curr = sizeof($invoicescur);
        $curr = 0;
        $weekDay = date('w', strtotime(date('Y-m-d H:i:s')));
        $check_profile = check_user_profile();
        if(!$check_profile)
        {
            $notify[] = ['success', 'Please complete your profile before proceeding!'];
            return redirect()->route('user.profile.setting')->withNotify($notify);
        }
        return view($this->activeTemplate . 'user.current_job_report', compact('pageTitle', 'emptyMessage', 'jobApplys','invoices','weekDay','user','curr'));
    }

    public function submitjobreport(Request $request)
    {
        $user = Auth::user();
        $general = GeneralSetting::first();
        $request->validate([
            'hours' => 'required',
            'minutes' => 'required',
        ]);
        $request->working_hours = $request->hours.".".$request->minutes;
        $workinghrs = $request->hours."hr : ".$request->minutes."mins";
        $timeReporting = TimeReporting::where('job_applies_id',$request->id)->whereDate('in_time','=', Carbon::now()->format('Y-m-d'))->get();
        if($timeReporting->count() > 0)
        {
            if($timeReporting[0]->out_time == null)
            {
                $jobApplys = JobApply::where('id',$request->id)->where('user_id', $user->id)->with('job', 'job.category')->get();
                foreach($jobApplys as $jobApply)
                {
                    $job_type = $jobApply->job_type;
                    if($job_type == 'permanent_job')
                    {
                        $markup_rate = $jobApply->job->category->markup_rate;
                    }
                    else
                    {
                        $markup_rate = $jobApply->job->category->temp_markup_rate;
                    }
                    $job_id = $jobApply->job->id;
                    $full_timerate = $jobApply->job->category->full_timerate;
                }
                $wage_markup = ($markup_rate * $user->candidate_rate)/100;
                $candidate_rate = $user->candidate_rate + $wage_markup;
                $candidate_rate_per_min = $candidate_rate/60;
                $working_hour = $this->convert_hours_min($request->working_hours);
                $total = $working_hour * $candidate_rate_per_min;
                $full_time_amt = ($full_timerate * $total)/100;
                $total_amt = $total;
                $tax_amt = ((int)$general->tax_rate * $total_amt)/100;

                $report = new Invoice;
                $report->job_id = $job_id;
                $report->user_id = $user->id;
                $report->job_type = $job_type;
                $report->prefix = 'EMINV';
                $report->invoice_amount = $total_amt;
                $report->hourly_price = $candidate_rate;
                $report->working_hours = $workinghrs;
                $report->tax_rate = $general->tax_rate;
                $report->tax_amt = $tax_amt;
                $report->invoice_amt_with_tax = $total_amt + $tax_amt;
                $report->save();

                $UpdatetimeReporting = TimeReporting::where('job_applies_id',$request->id)->whereDate('in_time','=', Carbon::now()->format('Y-m-d'))->firstOrFail();
                $UpdatetimeReporting->job_applies_id = $job_id;
                $UpdatetimeReporting->out_time = date("Y-m-d H:i", strtotime($timeReporting[0]->in_time ));
                $UpdatetimeReporting->working_hours = $workinghrs;
                $UpdatetimeReporting->invoice_id = $report->id;
                $UpdatetimeReporting->save();

                $this->generatePdf($report,'');
                $can_rate = $user->candidate_rate;
                $can_candidate_rate_per_min = $can_rate/60;
                $can_working_hour = $this->convert_hours_min($request->working_hours);
                $can_total = $working_hour * $can_candidate_rate_per_min;
                $can_full_time_amt = ($full_timerate * $can_total)/100;
                $can_total_amt = $can_total;
                $can_tax_amt = ((int)$general->tax_rate * $can_total_amt)/100;
                $can_report = new Invoice;
                $can_report->job_id = $job_id;
                $can_report->user_id = $user->id;
                $can_report->job_type = $job_type;
                $can_report->prefix = 'CNINV';
                $can_report->invoice_amount = $can_total_amt;
                $can_report->hourly_price = $can_rate;
                $can_report->working_hours = $workinghrs;
                $can_report->tax_rate = $general->tax_rate;
                $can_report->tax_amt = $can_tax_amt;
                $can_report->invoice_amt_with_tax = $can_total_amt + $can_tax_amt;
                $can_report->save();
                $this->generatePdf($can_report,'candidate');
                $employer = Employer::findOrFail($jobApply->job->employer->id);
                notify($employer, 'OUT_TIME_INFO_EMPLOYER',[
                    'company_name' => $jobApply->job->employer->company_name,
                    'candidate_name' => $jobApply->user->firstname.' '.$jobApply->user->lastname,
                    'job_title' => $jobApply->job->title,
                    'time_out' => $workinghrs
                ]);
                $admins = Admin::select('*')->get();
                foreach($admins as $admin)
                {
                    notify($admin, 'OUT_TIME_INFO_ADMIN',[
                        'company_name' => $jobApply->job->employer->company_name,
                        'candidate_name' => $jobApply->user->firstname.' '.$jobApply->user->lastname,
                        'job_title' => $jobApply->job->title,
                        'admin_name' => $admin->name,
                        'time_out' => $workinghrs
                    ]);
                }
                $notify[] = ['success', 'Submitted successfully.'];
            }
            else
            {
                $notify[] = ['error', 'You have already submitted working hours for this job'];
            }
        }
        else
        {

            $notify[] = ['error', 'Please submit in time from dashboard for this job'];
        }
        return back()->withNotify($notify);
    }
    public function generatePdf($val = array(),$role)
    {
        $user = Auth::user();
        $jobApplys = JobApply::where('user_id', $user->id)->with('job', 'job.employer','job.category', 'user')->where('status',1)->where('accept_by_user',1)->where('job_id',$val->job_id)->get();
        $data['invoice'] = $val;
        $data['job_details'] = $jobApplys;
        $pdf = PDF::loadView('templates.basic.candidate_invoice_pdf', $data);
        $pdf->setOptions(['isPhpEnabled' => true,'isRemoteEnabled' => true,'isHtml5ParserEnabled' => true]);
        header("Content-type: application/pdf");
        header("Content-Transfer-Encoding: binary");
        if($role == 'candidate')
        {
          $in = 'CNINV-00';
        }
        else {
          $in = 'EMINV-00';
        }
        $filename = $in.$val->id.'.pdf';
        $pdf->save(storage_path('app/public/uploads/'.$filename));
        $employer = Employer::findOrFail($jobApplys[0]->job->employer->id);
        notify($employer, 'INVOICE_CREATED',[
                'company_name' => $jobApplys[0]->job->employer->company_name,
                'candidate_name' => $jobApplys[0]->user->firstname.' '.$jobApplys[0]->user->lastname,
                'job_title' => $jobApplys[0]->job->title,
                'invoice_link' => route('download.invoice',$in.$val->id),
            ]);


    }

    public function generateAgencyPdf($val = array())
    {
        $user = Auth::user();
        $jobApplys = JobApply::where('user_id', $user->id)->with('job', 'job.employer','job.category', 'user')->where('status',1)->where('accept_by_user',1)->where('job_id',$val->job_id)->get();
        $data['invoice'] = $val;
        $data['job_details'] = $jobApplys;
        $pdf = PDF::loadView('templates.basic.invoice_pdf', $data);
        $pdf->setOptions(['isPhpEnabled' => true,'isRemoteEnabled' => true,'isHtml5ParserEnabled' => true]);
        header("Content-type: application/pdf");
        header("Content-Transfer-Encoding: binary");
        $filename = 'AGINV-00'.$val->id.'.pdf';
        $pdf->save(storage_path('app/public/uploads/'.$filename));
        $employer = Employer::findOrFail($jobApplys[0]->job->employer->id);
        notify($employer, 'AGENCY_INVOICE_CREATED',[
            'company_name' => $jobApplys[0]->job->employer->company_name,
            'candidate_name' => $jobApplys[0]->user->firstname.' '.$jobApplys[0]->user->lastname,
            'job_title' => $jobApplys[0]->job->title,
            'invoice_link' => route('download.invoice','AGINV-00'.$val->id),
        ]);
        notify($user, 'YOU_GOT_A_JOB',[
            "candidate_name" => $jobApplys[0]->user->firstname.' '.$jobApplys[0]->user->lastname,
        ]);
        
        if($jobApplys[0]->job->gender == 1)
        {
            $job_gender = 'Male';
        }
        else if($jobApplys[0]->job->gender == 2)
        {
            $job_gender = 'Female';
        }
        else if($jobApplys[0]->job->gender == 3)
        {
            $job_gender = 'No Preference';
        }
        
        $skills = JobSkill::where('status', 1)->select('id', 'name')->orderBy('name', 'asc')->get();
        $skill_id = explode(',',$jobApplys[0]->job->skill_id);
        $softwares = JobSoftware::where('status', 1)->select('id', 'name')->orderBy('name', 'asc')->get();
        $software_id = explode(',',$jobApplys[0]->job->software_id);
        $skill_list = '';
        $software_list = '';

        $i = 0;
        foreach($skills as $skill)
        {   
            if(in_array($skill->id, $skill_id))
            {
                if($i == 0)
                {
                   $skill_list .= $skill->name; 
                }
                else
                {
                    $skill_list .= ', '.$skill->name;
                }
                $i++;
            }
        }
        $j = 0;
        foreach($softwares as $software)
        {   
            if(in_array($software->id, $software_id))
            {
                if($j == 0)
                {
                   $software_list .= $software->name; 
                }
                else
                {
                    $software_list .= ', '.$software->name;
                }
                $j++;
            }    
        }
        $emp_add = $jobApplys[0]->job->employer->address;
        $office_details = $emp_add->address.', '.$emp_add->city.', '.$emp_add->state.', '.$emp_add->country.', '.$emp_add->zip;
        notify($user, 'JOB_CONFIRMATION',[
            'job_hourly_rate' => isset($jobApplys[0]->job->hourly_rate)?$jobApplys[0]->job->hourly_rate:'Not Available',
            'job_experience' => isset($jobApplys[0]->job->experience->name)?$jobApplys[0]->job->experience->name:'Not Available',
            'job_gender' => isset($job_gender)?$job_gender:'Not Available',
            'job_lunch_break' => ucfirst(isset($jobApplys[0]->job->lunch_break)?$jobApplys[0]->job->lunch_break:'Not Available'),
            'job_skills' => (isset($skill_list) && $skill_list != '')?$skill_list:'Not Available',
            'job_software' => (isset($software_list) && $software_list != '')?$software_list:'Not Available',
            'job_description' => isset($jobApplys[0]->job->description)?$jobApplys[0]->job->description:'Not Available',
            'job_responsibilities' => isset($jobApplys[0]->job->responsibilities)?$jobApplys[0]->job->responsibilities:'Not Available',
            'job_requirements' => isset($jobApplys[0]->job->requirements)?$jobApplys[0]->job->requirements:'Not Available',
            'office_details' => isset($office_details)?$office_details:'Not Available'
        ]);
        $admins = Admin::select('*')->get();
        foreach($admins as $admin)
        {
            notify($admin, 'JOB_ACCEPTED_ADMIN',[
                'company_name' => $jobApplys[0]->job->employer->company_name,
                'candidate_name' => $jobApplys[0]->user->firstname.' '.$jobApplys[0]->user->lastname,
                'job_title' => $jobApplys[0]->job->title,
                'admin_name' => $admin->name,
            ]);
        }
    }

    public function statistics()
    {
        $pageTitle = "Your Statistics";
        $emptyMessage = "No data found";
        $user = Auth::user();
        $employer = '';
        $jobCount = JobApply::where('user_id', $user->id)->where('job_status',2)->count();
        $plans = '';
        $totalDeposit = '';
        $totalTransaction = '';
        $planOrders ='';
        $candidate_rev = Review::whereHas('jobApplication',function($q) use ($user){
            $q->where('user_id',$user->id);
        })->get();
        $avg = $candidate_rev->average('rating');
        $count = $candidate_rev->count();
        $full_rate = Review::whereHas('jobApplication',function($q) use ($user){
            $q->where('user_id',$user->id)->where('rating',5);
        })->get();
        $full_rate_count = $full_rate->count();
        $badges = Badge::select('*')->get();
        $check_profile = check_user_profile();
        if(!$check_profile)
        {
            $notify[] = ['success', 'Please complete your profile before proceeding!'];
            return redirect()->route('user.profile.setting')->withNotify($notify);
        }
        return view($this->activeTemplate.'user.statistic', compact('pageTitle', 'emptyMessage', 'user', 'employer','jobCount','plans','totalDeposit','totalTransaction','planOrders','avg','count','full_rate_count','badges'));
    }
    public function convert_hours_min($working_hours)
    {
        $total_hours =  (int)$working_hours;
        $remaing_time = ($working_hours-$total_hours)*100;
        $total_min = $total_hours*60;
        $total_min = $total_min+$remaing_time;
        return $total_min;
    }
    public function currjobreportingtime(Request $request)
    {
        $user = Auth::user();
        $general = GeneralSetting::first();
        $request->validate([
            'in_time' => 'required',
        ]);
        $jobApply = jobApply::where('job_id', $request->job_id)->with('job', 'job.employer')->where('user_id', $user->id)->findOrFail($request->job_apply_id);
        $jobApply->in_time = date("Y-m-d H:i", strtotime( $request->in_time ));
        $jobApply->save();
        $employer = Employer::findOrFail($jobApply->job->employer->id);
        // notify($employer, 'IN_TIME_INFO_EMPLOYER',[
        //     'company_name' => $jobApply->job->employer->company_name,
        //     'candidate_name' => $jobApply->user->firstname.' '.$jobApply->user->lastname,
        //     'job_title' => $jobApply->job->title,
        // ]);
        $admins = Admin::select('*')->get();
        foreach($admins as $admin)
        {
            notify($admin, 'IN_TIME_INFO_ADMIN',[
                'company_name' => $jobApply->job->employer->company_name,
                'candidate_name' => $jobApply->user->firstname.' '.$jobApply->user->lastname,
                'job_title' => $jobApply->job->title,
                'admin_name' => $admin->name,
            ]);
        }
        //current admin email => kreativmark@yahoo.com
        $notify[] = ['success', 'Submitted successfully.'];
        return back()->withNotify($notify);
    }
    public function currjobintime(Request $request)
    {
        $user = Auth::user();
        $general = GeneralSetting::first();
        $request->validate([
            'hours' => 'required',
            'minutes' => 'required',
            'am_pm' => 'required',
            'job_id' => 'required',
        ]);
        $report = new TimeReporting;
        $report->job_applies_id = $request->job_id;
        $report->in_time = Carbon::now()->format('Y-m-d').' '.$request->hours.':'.$request->minutes.' '.$request->am_pm;
        $report->save();
        $jobApply = JobApply::where('id',$request->job_id)->with('job', 'job.category', 'job.employer')->get();
        $job_applied = JobApply::find($jobApply[0]->id);
        if($job_applied) {
            $job_applied->in_time = Carbon::now()->format('Y-m-d').' '.$request->hours.':'.$request->minutes.' '.$request->am_pm;
            $job_applied->save();
        }
      $employer = Employer::findOrFail($jobApply[0]->job->employer->id);
      notify($employer, 'IN_TIME_INFO_EMPLOYER',[
          'company_name' => $jobApply[0]->job->employer->company_name,
          'candidate_name' => $jobApply[0]->user->firstname.' '.$jobApply[0]->user->lastname,
          'job_title' => $jobApply[0]->job->title,
          'time_in' => Carbon::now()->format('Y-m-d').' '.$request->hours.':'.$request->minutes.' '.$request->am_pm
      ]);
      $admins = Admin::select('*')->get();
      foreach($admins as $admin)
      {
          notify($admin, 'IN_TIME_INFO_ADMIN',[
              'company_name' => $jobApply[0]->job->employer->company_name,
              'candidate_name' => $jobApply[0]->user->firstname.' '.$jobApply[0]->user->lastname,
              'job_title' => $jobApply[0]->job->title,
              'admin_name' => $admin->name,
              'time_in' => Carbon::now()->format('Y-m-d').' '.$request->hours.':'.$request->minutes.' '.$request->am_pm
          ]);
      }
        $notify[] = ['success', 'Submitted successfully.'];
        return back()->withNotify($notify);
    }
    public function currjobouttime(Request $request)
    {
        $user = Auth::user();
        $general = GeneralSetting::first();
        $request->validate([
            'hours' => 'required',
            'minutes' => 'required',
            'job_id' => 'required',
        ]);
        $request->working_hours = $request->hours.".".$request->minutes;
        $workinghrs = $request->hours."hr : ".$request->minutes."mins";
        $jobApplys = JobApply::where('id',$request->job_id)->where('user_id', $user->id)->with('job', 'job.category')->get();
        $TimeReporting = TimeReporting::where('job_applies_id',$request->job_id)->get();
        if(sizeof($TimeReporting) == 0 && $jobApplys[0]->job_type == 'temp_job')
        {
          $notify[] = ['warning', 'Please fill the in time.'];
          return back()->withNotify($notify);
        }
        $job_reporting = TimeReporting::select('*')->where('job_applies_id',$request->job_id)->first();        
        if(explode(' ',$job_reporting->in_time)[0] == Carbon::now()->format('Y-m-d'))
        {
            $daysToAdd =  $request->working_hours;
            $job_reporting->job_applies_id = $request->job_id;
            $date = Carbon::parse($job_reporting->in_time)->addHours($daysToAdd)->format('Y-m-d H:i:s');
            $job_reporting->out_time = date("Y-m-d H:i", strtotime( $date));
            $job_reporting->working_hours = $request->hours."hr :".$request->minutes.'mins';
            $job_reporting->save();
        }
        else
        {
            $job_reporting = new TimeReporting();
            $daysToAdd =  $request->working_hours;
            $job_reporting->job_applies_id = $request->job_id;
            $job_reporting->out_time = date("Y-m-d H:i");
            $job_reporting->working_hours = $workinghrs;
            $job_reporting->save();
        }

        // $jobApply = jobApply::where('job_id', $request->job_id)->with('job', 'job.employer')->where('user_id', $user->id)->findOrFail($request->job_apply_id);
        $weekDay = date('w', strtotime(date('Y-m-d H:i:s')));
        foreach($jobApplys as $jobApply)
        {
            $job_type = $jobApply->job_type;
            if($job_type == 'permanent_job')
            {
                $jobtype = 'permanent_job';
                $markup_rate = $jobApply->job->category->markup_rate;
            }
            else
            {
                $jobtype = 'temp_job';
                $markup_rate = $jobApply->job->category->temp_markup_rate;
            }
            $job_id = $jobApply->job->id;
            $full_timerate = $jobApply->job->category->full_timerate;
        }
        if($jobtype == 'permanent_job')
        {
            if($weekDay == 5)
            {
                $wage_markup = ($markup_rate * $user->candidate_rate)/100;
                $candidate_rate = $user->candidate_rate + $wage_markup;
                $candidate_rate_per_min = $candidate_rate/60;
                $working_hour = $this->convert_hours_min($daysToAdd);
                $total = $working_hour * $candidate_rate_per_min;
                $full_time_amt = ($full_timerate * $total)/100;
                $total_amt = $total;
                $tax_amt = ((int)$general->tax_rate * $total_amt)/100;
                $report = new Invoice;
                $report->job_id = $job_id;
                $report->user_id = $user->id;
                $report->job_type = $job_type;
                $report->in_time = date("Y-m-d H:i", strtotime( $request->in_time ));
                $report->prefix = 'EMINV';
                $report->invoice_amount = $total_amt;
                $report->hourly_price = $candidate_rate;
                $report->working_hours = $workinghrs;
                $report->tax_rate = $general->tax_rate;
                $report->tax_amt = $tax_amt;
                $report->invoice_amt_with_tax = $total_amt + $tax_amt;
                $report->save();

                $job_reporting->invoice_id = $report->id;
                $job_reporting->save();

                $tot_amt = $total_amt + $tax_amt;

                $employer = Employer::findOrFail($jobApply->job->employer->id);
                $employer->balance -= $total_amt;
                $employer->save();

                $transaction = new Transaction();
                $transaction->employer_id = $employer->id;
                $transaction->amount = $total_amt;
                $transaction->post_balance = $employer->balance;
                $transaction->trx_type = '-';
                $transaction->details = 'Payment for invoice <a href="'.route('download.invoice','EMINV-00'.$report->id).'">EMINV-00'.$report->id.'</a>';
                $transaction->trx = getTrx();
                $transaction->save();

                $this->generatePdf($report,'');
                $can_rate = $user->candidate_rate;
                $can_candidate_rate_per_min = $can_rate/60;
                $can_working_hour = $this->convert_hours_min($daysToAdd);
                $can_total = $working_hour * $can_candidate_rate_per_min;
                $can_full_time_amt = ($full_timerate * $can_total)/100;
                $can_total_amt = $can_total;
                $can_tax_amt = ((int)$general->tax_rate * $can_total_amt)/100;
                $can_report = new Invoice;
                $can_report->job_id = $job_id;
                $can_report->user_id = $user->id;
                $can_report->job_type = $job_type;
                $can_report->prefix = 'CNINV';
                $can_report->invoice_amount = $can_total_amt;
                $can_report->hourly_price = $can_rate;
                $can_report->working_hours = $workinghrs;
                $can_report->tax_rate = $general->tax_rate;
                $can_report->tax_amt = $can_tax_amt;
                $can_report->invoice_amt_with_tax = $can_total_amt + $can_tax_amt;
                $can_report->save();
                $this->generatePdf($can_report,'candidate');

            }
        }
        else
        {
            $wage_markup = ($markup_rate * $user->candidate_rate)/100;
            $candidate_rate = $user->candidate_rate + $wage_markup;
            $candidate_rate_per_min = $candidate_rate/60;
            $working_hour = $this->convert_hours_min($daysToAdd);
            $total = $working_hour * $candidate_rate_per_min;
            $full_time_amt = ($full_timerate * $total)/100;
            $total_amt = $total;
            $tax_amt = ((int)$general->tax_rate * $total_amt)/100;
            $report = new Invoice;
            $report->job_id = $job_id;
            $report->user_id = $user->id;
            $report->job_type = $job_type;
            $report->in_time = date("Y-m-d H:i", strtotime( $request->in_time ));
            $report->prefix = 'EMINV';
            $report->invoice_amount = $total_amt;
            $report->hourly_price = $candidate_rate;
            $report->working_hours = $workinghrs;
            $report->tax_rate = $general->tax_rate;
            $report->tax_amt = $tax_amt;
            $report->invoice_amt_with_tax = $total_amt + $tax_amt;
            $report->save();

            $job_reporting->invoice_id = $report->id;
            $job_reporting->save();

            $tot_amt = $total_amt + $tax_amt;

            $employer = Employer::findOrFail($jobApply->job->employer->id);
            $employer->balance -= $total_amt;
            $employer->save();

            $transaction = new Transaction();
            $transaction->employer_id = $employer->id;
            $transaction->amount = $total_amt;
            $transaction->post_balance = $employer->balance;
            $transaction->trx_type = '-';
            $transaction->details = 'Payment for invoice <a href="'.route('download.invoice','EMINV-00'.$report->id).'">EMINV-00'.$report->id.'</a>';
            $transaction->trx = getTrx();
            $transaction->save();

            $this->generatePdf($report,'');
            $can_rate = $user->candidate_rate;
            $can_candidate_rate_per_min = $can_rate/60;
            $can_working_hour = $this->convert_hours_min($daysToAdd);
            $can_total = $working_hour * $can_candidate_rate_per_min;
            $can_full_time_amt = ($full_timerate * $can_total)/100;
            $can_total_amt = $can_total;
            $can_tax_amt = ((int)$general->tax_rate * $can_total_amt)/100;
            $can_report = new Invoice;
            $can_report->job_id = $job_id;
            $can_report->user_id = $user->id;
            $can_report->job_type = $job_type;
            $can_report->prefix = 'CNINV';
            $can_report->invoice_amount = $can_total_amt;
            $can_report->hourly_price = $can_rate;
            $can_report->working_hours = $workinghrs;
            $can_report->tax_rate = $general->tax_rate;
            $can_report->tax_amt = $can_tax_amt;
            $can_report->invoice_amt_with_tax = $can_total_amt + $can_tax_amt;
            $can_report->save();
            $this->generatePdf($can_report,'candidate');
        }

        notify($employer, 'OUT_TIME_INFO_EMPLOYER',[
            'company_name' => $jobApply->job->employer->company_name,
            'candidate_name' => $jobApply->user->firstname.' '.$jobApply->user->lastname,
            'job_title' => $jobApply->job->title,
            'time_out' => $workinghrs
        ]);
        $admins = Admin::select('*')->get();
        foreach($admins as $admin)
        {
            notify($admin, 'OUT_TIME_INFO_ADMIN',[
                'company_name' => $jobApply->job->employer->company_name,
                'candidate_name' => $jobApply->user->firstname.' '.$jobApply->user->lastname,
                'job_title' => $jobApply->job->title,
                'admin_name' => $admin->name,
                'time_out' => $workinghrs
            ]);
        }
        // current admin email => kreativmark@yahoo.com
        $notify[] = ['success', 'Submitted successfully.'];
        return back()->withNotify($notify);
    }
    public function invoices(){
        $pageTitle = "Invoice list";
        $emptyMessage = "No data found";
        $user = Auth::user();
        $appliedJobs = Invoice::where('user_id',$user->id)->latest()->where('prefix','CNINV')->with('job','user')->paginate(getPaginate());
        return view($this->activeTemplate . 'user.invoices', compact('pageTitle', 'emptyMessage', 'appliedJobs'));
    }
    public function lowbalance()
    {
      $user = auth()->user();
      $general = GeneralSetting::first();
      $job_employer = JobApply::where('id',$_POST['id'])->where('user_id', $user->id)->with('job', 'job.category','job.employer')->get();
      foreach ($job_employer as $jobe)
      {
        if($jobe->job->employer['balance'] < 200)
        {
          $admins = Admin::select('*')->get();
          foreach($admins as $admin)
          {
              notify($admin, 'ADMIN_ALERT_LOWBALANCE',[
                  'company_name' => $jobe->job->employer->company_name,
                  'balance' => $jobe->job->employer['balance'],
                  'admin_name' => $admin->name,
              ]);
          }
          echo "low_balance";
        }
        else
        {
          echo "no";
        }
      }
    }
    public function submit_employee_rating(Request $request) 
    {
        $user = Auth::user();
        $request->validate([
            'star' => 'required',
        ]);
        $review = new Review();
        $review->user_id = Auth::user()->id;
        $review->employer_id = $request->input('employer_id');
        $review->rating = $request->input('star');
        $review->job_apply_id = $request->input('id');
        $review->comment = 'rating by candidate';
        $review->status = 1;
        $review->save();
        $jobApply = JobApply::whereHas('job',function($q) use ($user){
            $q->where('user_id',$user->id);
        })->findOrFail($request->id);
        $jobApply->employee_review = $request->input('star');
        $jobApply->save();
        $employer = Employer::findOrFail($request->input('employer_id'));
        notify($employer, 'CANDIDATE_REVIEW',[
            'employer_name' => $jobApply->job->employer->company_name,
            'candidate_name' => $jobApply->user->firstname.' '.$jobApply->user->lastname,
            'job_title' => $jobApply->job->title,
            'rating' => $request->input('star').' stars',
        ]);
        $notify[] =['success', 'Review is succesfully submitted'];
        return back()->withNotify($notify);
    }
}