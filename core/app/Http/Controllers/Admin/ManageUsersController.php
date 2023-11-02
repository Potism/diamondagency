<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailLog;
use App\Models\GeneralSetting;
use App\Models\JobApply;
use App\Models\SupportTicket;
use App\Models\EducationalQualification;
use App\Models\EmploymentHistory;
use App\Models\User;
use App\Models\JobSkill;
use App\Models\JobSoftware;
use App\Models\Category;
use App\Models\UserLogin;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use File;
use ZipArchive;


class ManageUsersController extends Controller
{
    public function allUsers()
    {
        $pageTitle = 'Manage Users';
        $emptyMessage = 'No user found';
        $users = User::orderBy('id','desc')->paginate(getPaginate());
        return view('admin.users.list', compact('pageTitle', 'emptyMessage', 'users'));
    }

    public function activeUsers()
    {
        $pageTitle = 'Manage Active Users';
        $emptyMessage = 'No active user found';
        $users = User::active()->orderBy('id','desc')->paginate(getPaginate());
        return view('admin.users.list', compact('pageTitle', 'emptyMessage', 'users'));
    }

    public function bannedUsers()
    {
        $pageTitle = 'Banned Users';
        $emptyMessage = 'No banned user found';
        $users = User::banned()->orderBy('id','desc')->paginate(getPaginate());
        return view('admin.users.list', compact('pageTitle', 'emptyMessage', 'users'));
    }

    public function emailUnverifiedUsers()
    {
        $pageTitle = 'Email Unverified Users';
        $emptyMessage = 'No email unverified user found';
        $users = User::emailUnverified()->orderBy('id','desc')->paginate(getPaginate());
        return view('admin.users.list', compact('pageTitle', 'emptyMessage', 'users'));
    }
    public function emailVerifiedUsers()
    {
        $pageTitle = 'Email Verified Users';
        $emptyMessage = 'No email verified user found';
        $users = User::emailVerified()->orderBy('id','desc')->paginate(getPaginate());
        return view('admin.users.list', compact('pageTitle', 'emptyMessage', 'users'));
    }

    public function smsUnverifiedUsers()
    {
        $pageTitle = 'SMS Unverified Users';
        $emptyMessage = 'No sms unverified user found';
        $users = User::smsUnverified()->orderBy('id','desc')->paginate(getPaginate());
        return view('admin.users.list', compact('pageTitle', 'emptyMessage', 'users'));
    }

    public function smsVerifiedUsers()
    {
        $pageTitle = 'SMS Verified Users';
        $emptyMessage = 'No sms verified user found';
        $users = User::smsVerified()->orderBy('id','desc')->paginate(getPaginate());
        return view('admin.users.list', compact('pageTitle', 'emptyMessage', 'users'));
    }

    public function search(Request $request, $scope)
    {
        $search = $request->search;
        $users = User::where(function ($user) use ($search) {
            $user->where('username', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%")
                ->orWhere('employee_id', 'like', "%$search%");
        });
        $pageTitle = '';
        if ($scope == 'active') {
            $pageTitle = 'Active ';
            $users = $users->where('status', 1);
        }elseif($scope == 'banned'){
            $pageTitle = 'Banned';
            $users = $users->where('status', 0);
        }elseif($scope == 'emailUnverified'){
            $pageTitle = 'Email Unverified ';
            $users = $users->where('ev', 0);
        }elseif($scope == 'smsUnverified'){
            $pageTitle = 'SMS Unverified ';
            $users = $users->where('sv', 0);
        }
        $users = $users->paginate(getPaginate());
        $pageTitle .= 'User Search - ' . $search;
        $emptyMessage = 'No search result found';
        return view('admin.users.list', compact('pageTitle', 'search', 'scope', 'emptyMessage', 'users'));
    }


    public function detail($id)
    {
        $pageTitle = 'User Detail';
        $user = User::findOrFail($id);
        $skills = JobSkill::where('status', 1)->select('id', 'name')->orderBy('name', 'asc')->get();
        $designation = Category::where('status', 1)->select('*')->orderBy('name', 'asc')->latest()->paginate(getPaginate());;
        $totalJobApply = JobApply::where('user_id', $user->id)->count();
        $supportTicketCount = SupportTicket::where('user_id', $user->id)->count();
        $educationCount = EducationalQualification::where('user_id', $user->id)->count();
        $employmentCount = EmploymentHistory::where('user_id', $user->id)->count();
        $countries = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $softwares = JobSoftware::where('status', 1)->select('id', 'name')->orderBy('name', 'asc')->get();
        $software_id = explode(',',$user->software_id);
        $candidate_rev = Review::where('user_id',$user->id)->where('comment','rating by employer')->select('*')->get();
        $avg = $candidate_rev->average('rating');
        $avg1 = $candidate_rev->sum('rating');
        $medal = getmedal($avg1);
        $count = $candidate_rev->count();
        return view('admin.users.detail', compact('pageTitle', 'user','countries', 'totalJobApply', 'supportTicketCount', 'skills', 'educationCount', 'employmentCount','designation','softwares','software_id','avg','medal','count'));
    }


    public function educations($id)
    {
        $user = User::findOrFail($id);
        $pageTitle = ucfirst($user->firstname).' '.ucfirst($user->lastname)." Educational Qualification";
        $emptyMessage = "No data found";
        $educations = EducationalQualification::where('user_id', $user->id)->get();
        return view('admin.users.education', compact('pageTitle','emptyMessage', 'educations'));
    }

    public function employment($id)
    {
        $user = User::findOrFail($id);
        $pageTitle = ucfirst($user->firstname).' '.ucfirst($user->lastname)." Employment History";
        $emptyMessage = "No data found";
        $employmentHistorys = EmploymentHistory::where('user_id', $user->id)->get();
        return view('admin.users.employment', compact('pageTitle','emptyMessage', 'employmentHistorys'));
    }


    public function jobApplication($id)
    {
        $user = User::findOrFail($id);
        $pageTitle = ucfirst($user->firstname).' '.ucfirst($user->lastname). ' Total Job Application';
        $emptyMessage = "No data found";
        $jobApplications = JobApply::where('user_id', $user->id)->paginate(getPaginate());
        return view('admin.job.job_apply', compact('pageTitle', 'emptyMessage', 'jobApplications'));
    }

    public function supportTicket($id)
    {
        $user = User::findOrFail($id);
        $pageTitle = ucfirst($user->firstname).' '.ucfirst($user->lastname). ' Total Support Ticket';
        $emptyMessage = "No data found";
        $items = SupportTicket::where('user_id', $user->id)->paginate(getPaginate());
        return view('admin.support.tickets', compact('pageTitle', 'emptyMessage', 'items'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $countryData = json_decode(file_get_contents(resource_path('views/partials/country.json')));
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
          'details' => 'required',
          'facebook' => 'nullable|url',
          'twitter' => 'nullable|url',
          'pinterest' => 'nullable|url',
          'linkedin' => 'nullable|url',
          'candidate_rate' => 'required',
          'answer1' => 'required',
          'answer2' => 'required',
          'answer3' => 'required',
          'answer4' => 'required',
          'answer5' => 'required',
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
        if(!empty($countryData))
        {
            foreach($countryData as $key=>$value)
            {
              if($key == ucfirst($request->country))
              {
                $country_code = $key;
              }
            }
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
        $user->details = $request->details;

        $user->socialMedia =  [
            'facebook' => $request->facebook,
            'twitter' => $request->twitter,
            'pinterest' => $request->pinterest,
            'linkedin' => $request->linkedin
        ];
        $user->status = $request->status ? 1 : 0;
        $user->ev = $request->ev ? 1 : 0;
        $user->sv = $request->sv ? 1 : 0;
        $user->ts = $request->ts ? 1 : 0;
        $user->tv = $request->tv ? 1 : 0;
        $user->save();

        $notify[] = ['success', 'User detail has been updated'];
        return redirect()->back()->withNotify($notify);
    }

    public function userLoginHistory($id)
    {
        $user = User::findOrFail($id);
        $pageTitle = 'User Login History - ' . $user->username;
        $emptyMessage = 'No users login found.';
        $login_logs = $user->login_logs()->orderBy('id','desc')->with('user')->paginate(getPaginate());
        return view('admin.users.logins', compact('pageTitle', 'emptyMessage', 'login_logs'));
    }

    public function showEmailSingleForm($id)
    {
        $user = User::findOrFail($id);
        $pageTitle = 'Send Email To: ' . $user->username;
        return view('admin.users.email_single', compact('pageTitle', 'user'));
    }

    public function sendEmailSingle(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string|max:65000',
            'subject' => 'required|string|max:190',
        ]);

        $user = User::findOrFail($id);
        sendGeneralEmail($user->email, $request->subject, $request->message, $user->username);
        $notify[] = ['success', $user->username . ' will receive an email shortly.'];
        return back()->withNotify($notify);
    }

    public function showEmailAllForm()
    {
        $pageTitle = 'Send Email To All Users';
        return view('admin.users.email_all', compact('pageTitle'));
    }

    public function sendEmailAll(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:65000',
            'subject' => 'required|string|max:190',
        ]);

        foreach (User::where('status', 1)->cursor() as $user) {
            sendGeneralEmail($user->email, $request->subject, $request->message, $user->username);
        }

        $notify[] = ['success', 'All users will receive an email shortly.'];
        return back()->withNotify($notify);
    }

    public function login($id){
        $user = User::findOrFail($id);
        Auth::login($user);
        return redirect()->route('user.home');
    }

    public function emailLog($id){
        $user = User::findOrFail($id);
        $pageTitle = 'Email log of '.$user->username;
        $logs = EmailLog::where('user_id',$id)->with('user')->orderBy('id','desc')->paginate(getPaginate());
        $emptyMessage = 'No data found';
        return view('admin.users.email_log', compact('pageTitle','logs','emptyMessage','user'));
    }

    public function emailDetails($id){
        $email = EmailLog::findOrFail($id);
        $pageTitle = 'Email details';
        return view('admin.users.email_details', compact('pageTitle','email'));
    }



    public function cvDownload($id)
    {
        $user = User::findOrFail($id);
        if($user->cv == null){
            $notify[] =['error', 'Cv not uploaded'];
            return back()->withNotify($notify);
        }
        $path = imagePath()['profile']['user']['path'];
        $fullPath = $path.'/'. $user->cv;
        $title = slug($user->username);
        $ext = pathinfo($user->cv, PATHINFO_EXTENSION);
        $mimetype = mime_content_type($fullPath);
        header('Content-Disposition: attachment; filename="' . $title . '.' . $ext . '";');
        header("Content-Type: " . $mimetype);
        return readfile($fullPath);
    }

    public function credentialsDownload($id)
    {
        $user = User::findOrFail($id);
        if($user->driving_license == null && $user->license == null && $user->certificate == null){
            $notify[] =['error', 'Credentials not uploaded'];
            return back()->withNotify($notify);
        }
        $files = array();
        $path = imagePath()['profile']['user']['path'];
        if($user->certificate != null)
        {
            $files[] = $path.'/'. $user->certificate;    
        }
        if($user->license != null)
        {
            $files[] = $path.'/'. $user->license;
        }
        if($user->driving_license != null)
        {
            $files[] = $path.'/'. $user->driving_license;
        }
        if($user->covid19id != null)
        {
            $files[] = $path.'/'. $user->covid19id;
        }
        $zip = new ZipArchive;
        $fileName = 'credentials.zip';
        if ($zip->open($path.'/'.$fileName, ZipArchive::CREATE) === TRUE)
        {
            foreach ($files as $file) {
                $content = file_get_contents($file);
                $zip->addFromString(pathinfo ( $file, PATHINFO_BASENAME), $content);
            }
            $zip->close();
        }
        $filetopath = $path.'/'.$fileName;

        $headers = [
            'Cache-control: maxage=1',
            'Pragma: no-cache',
            'Expires: 0',
            'Content-Type : application/octet-stream',
            'Content-Transfer-Encoding: binary',
            'Content-Type: application/force-download',
            'Content-Disposition: attachment; filename='.time().'.zip',
            "Content-length: " . filesize($filetopath)
        ];
        if (file_exists($filetopath)) {
            $res = response()->download($filetopath, $fileName, $headers)->deleteFileAfterSend(true);
            if (ob_get_contents()) ob_end_clean();
        } else {
            $res = ['status'=>'zip file does not exist'];
        }
        return $res;
    }
     public function downloadPadFile($filename)
    {
      $path = imagePath()['profile']['user']['path'];
      $file = $path.'/'.$filename;;
      $headers = array(
          'Content-Type: application/pdf',
      );
      return \Response::download($file, $filename, $headers);
    }
}
