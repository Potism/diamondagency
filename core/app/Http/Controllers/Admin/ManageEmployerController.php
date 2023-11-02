<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\EmailLog;
use App\Models\Employer;
use App\Models\GeneralSetting;
use App\Models\Industry;
use App\Models\Job;
use App\Models\NumberOfEmployees;
use App\Models\Order;
use App\Models\Review;
use App\Models\Transaction;
use App\Models\UserLogin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ManageEmployerController extends Controller
{
    public function allUsers()
    {
        $pageTitle = 'Manage Employers';
        $emptyMessage = 'No employers found';
        $emplyers = Employer::orderBy('id','desc')->paginate(getPaginate());
        return view('admin.employer.list', compact('pageTitle', 'emptyMessage', 'emplyers'));
    }

    public function activeUsers()
    {
        $pageTitle = 'Manage Active Employers';
        $emptyMessage = 'No active employers found';
        $emplyers = Employer::active()->orderBy('id','desc')->paginate(getPaginate());
        return view('admin.employer.list', compact('pageTitle', 'emptyMessage', 'emplyers'));
    }

    public function bannedUsers()
    {
        $pageTitle = 'Banned Employers';
        $emptyMessage = 'No banned employers found';
        $emplyers = Employer::banned()->orderBy('id','desc')->paginate(getPaginate());
        return view('admin.employer.list', compact('pageTitle', 'emptyMessage', 'emplyers'));
    }

    public function emailUnverifiedUsers()
    {
        $pageTitle = 'Email Unverified Employers';
        $emptyMessage = 'No email unverified employers found';
        $emplyers = Employer::emailUnverified()->orderBy('id','desc')->paginate(getPaginate());
        return view('admin.employer.list', compact('pageTitle', 'emptyMessage', 'emplyers'));
    }
    public function emailVerifiedUsers()
    {
        $pageTitle = 'Email Verified Employers';
        $emptyMessage = 'No email verified employers found';
        $emplyers = Employer::emailVerified()->orderBy('id','desc')->paginate(getPaginate());
        return view('admin.employer.list', compact('pageTitle', 'emptyMessage', 'emplyers'));
    }


    public function smsUnverifiedUsers()
    {
        $pageTitle = 'SMS Unverified Employers';
        $emptyMessage = 'No sms unverified employers found';
        $emplyers = Employer::smsUnverified()->orderBy('id','desc')->paginate(getPaginate());
        return view('admin.employer.list', compact('pageTitle', 'emptyMessage', 'emplyers'));
    }


    public function smsVerifiedUsers()
    {
        $pageTitle = 'SMS Verified Employers';
        $emptyMessage = 'No sms verified employers found';
        $emplyers = Employer::smsVerified()->orderBy('id','desc')->paginate(getPaginate());
        return view('admin.employer.list', compact('pageTitle', 'emptyMessage', 'emplyers'));
    }

    public function search(Request $request, $scope)
    {
        $search = $request->search;
        $emplyers = Employer::where(function ($emplyer) use ($search) {
            $emplyer->where('username', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%");
        });
        $pageTitle = '';
        if ($scope == 'active') {
            $pageTitle = 'Active ';
            $emplyers = $emplyers->where('status', 1);
        }elseif($scope == 'banned'){
            $pageTitle = 'Banned';
            $emplyers = $emplyers->where('status', 0);
        }elseif($scope == 'emailUnverified'){
            $pageTitle = 'Email Unverified ';
            $emplyers = $emplyers->where('ev', 0);
        }elseif($scope == 'smsUnverified'){
            $pageTitle = 'SMS Unverified ';
            $emplyers = $emplyers->where('sv', 0);
        }
        $emplyers = $emplyers->paginate(getPaginate());
        $pageTitle .= 'Emplyers Search - ' . $search;
        $emptyMessage = 'No search result found';
        return view('admin.employer.list', compact('pageTitle', 'search', 'scope', 'emptyMessage', 'emplyers'));
    }


   public function detail($id)
    {
        $pageTitle = 'Employer Detail';
        $user = Employer::findOrFail($id);
        $planSubscribe = Order::where('employer_id', $user->id)->where('status', '!=', 0)->count();
        $industries = Industry::where('status', 1)->select('id', 'name')->get();
        $numberOfemployes = NumberOfEmployees::where('status', 1)->select('id', 'employees')->get();
        $totalDeposit = Deposit::where('employer_id',$user->id)->where('status',1)->sum('amount');
        $totalTransaction = Transaction::where('employer_id',$user->id)->count();
        $totalJobCount = Job::where('employer_id', $user->id)->count();
        $countries = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $emp_rev = Review::where('employer_id',$user->id)->where('comment','rating by candidate')->select('*')->get();
        $avg = $emp_rev->average('rating');
        $avg2 = $emp_rev->sum('rating');
        $medal = getmedal($avg2);
        $count = $emp_rev->count();
        return view('admin.employer.detail', compact('pageTitle', 'user','totalDeposit','totalTransaction','countries', 'industries', 'numberOfemployes', 'planSubscribe', 'totalJobCount','avg','avg2','medal','count'));
    }


    public function employerJob($id)
    {
        $user = Employer::findOrFail($id);
        $pageTitle = ' Job Posting : '.$user->company_name ;
        $emptyMessage = "No data found";
        $jobs = Job::where('employer_id', $user->id)->latest()->with('category', 'city', 'location', 'type', 'shift', 'employer', 'jobApplication')->paginate(getPaginate());
        return view('admin.job.index', compact('pageTitle', 'emptyMessage', 'jobs'));
    }


    public function employerPlanSubscribe($id)
    {
        $user = Employer::findOrFail($id);
        $pageTitle = 'Pre-paid History : '.$user->company_name;
        $emptyMessage = "No data found";
        $orders = Order::where('employer_id', $user->id)->latest()->with('employer', 'plan')->paginate(getPaginate());
        return view('admin.plan.subscriber', compact('pageTitle', 'emptyMessage', 'orders'));
    }


    public function update(Request $request, $id)
    {
        $user = Employer::findOrFail($id);
        $countryData = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $request->validate([
            'company_name' => 'required|max:40',
            'company_ceo' => 'required|max:50',
            'email' => 'required|email|max:90|unique:employers,email,' . $user->id,
            'mobile' => 'required|unique:employers,mobile,' . $user->id,
            'fax' => 'required|unique:employers,fax,' . $user->id,
            'industry' => 'required|exists:industries,id',
            'number_of_employees' => 'required|exists:number_of_employees,id',
            'description' => 'required',
            'facebook' => 'nullable|url',
            'twitter' => 'nullable|url',
            'pinterest' => 'nullable|url',
            'linkedin' => 'nullable|url',
            'country' => 'required',
        ]);
        $countryCode = $request->country;
        $user->mobile = $request->mobile;
        $user->country_code = $countryCode;
        $user->company_name = $request->company_name;
        $user->ceo_name = $request->company_ceo;
        $user->industry_id = $request->industry;
        $user->number_of_employees_id = $request->number_of_employees;
        $user->fax = $request->fax;
        $user->website = $request->website;
        $user->email = $request->email;
        $user->address = [
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'zip' => $request->zip,
            'country' => @$countryData->$countryCode->country,
        ];
        $user->socialMedia =  [
            'facebook' => $request->facebook,
            'twitter' => $request->twitter,
            'pinterest' => $request->pinterest,
            'linkedin' => $request->linkedin
        ];
        $user->description = $request->description;
        $user->status = $request->status ? 1 : 0;
        $user->ev = $request->ev ? 1 : 0;
        $user->sv = $request->sv ? 1 : 0;
        $user->ts = $request->ts ? 1 : 0;
        $user->tv = $request->tv ? 1 : 0;
        $user->save();

        $notify[] = ['success', 'Employer detail has been updated'];
        return redirect()->back()->withNotify($notify);
    }


    public function transactions(Request $request, $id)
    {
        $user = Employer::findOrFail($id);
        if ($request->search) {
            $search = $request->search;
            $pageTitle = 'Search User Transactions : ' . $user->username;
            $transactions = $user->transactions()->where('trx', $search)->with('user')->orderBy('id','desc')->paginate(getPaginate());
            $emptyMessage = 'No transactions';
            return view('admin.reports.transactions', compact('pageTitle', 'search', 'user', 'transactions', 'emptyMessage'));
        }
        $pageTitle = 'Transaction History : ' . $user->company_name;
        $transactions = $user->transactions()->with('user')->orderBy('id','desc')->paginate(getPaginate());
        $emptyMessage = 'No transactions';
        return view('admin.reports.transactions', compact('pageTitle', 'user', 'transactions', 'emptyMessage'));
    }

    public function deposits(Request $request, $id)
    {
        $user = Employer::findOrFail($id);
        $userId = $user->id;
        if ($request->search) {
            $search = $request->search;
            $pageTitle = 'Search Employer Deposits : ' . ucfirst($user->company_name);
            $deposits = $user->deposits()->where('trx', $search)->orderBy('id','desc')->with('user')->paginate(getPaginate());
            $emptyMessage = 'No deposits';
            return view('admin.deposit.log', compact('pageTitle', 'search', 'user', 'deposits', 'emptyMessage','userId'));
        }

        $pageTitle = 'Employer Deposit : ' . ucfirst($user->company_name);
        $deposits = $user->deposits()->orderBy('id','desc')->with(['gateway','user'])->paginate(getPaginate());
        $successful = $user->deposits()->orderBy('id','desc')->where('status',1)->sum('amount');
        $pending = $user->deposits()->orderBy('id','desc')->where('status',2)->sum('amount');
        $rejected = $user->deposits()->orderBy('id','desc')->where('status',3)->sum('amount');
        $emptyMessage = 'No deposits';
        $scope = 'all';
        return view('admin.deposit.log', compact('pageTitle', 'user', 'deposits', 'emptyMessage','userId','scope','successful','pending','rejected'));
    }


    public function addSubBalance(Request $request, $id)
    {
        $request->validate(['amount' => 'required|numeric|gt:0']);

        $user = Employer::findOrFail($id);
        $amount = $request->amount;
        $comments = $request->comments;
        $general = GeneralSetting::first(['cur_text','cur_sym']);
        $trx = getTrx();

        if ($request->act) {
            $user->balance += $amount;
            $user->save();
            $notify[] = ['success', $general->cur_sym . $amount . ' has been added to ' . $user->username . '\'s balance'];

            $transaction = new Transaction();
            $transaction->employer_id = $user->id;
            $transaction->amount = $amount;
            $transaction->post_balance = $user->balance;
            $transaction->charge = 0;
            $transaction->trx_type = '+';
            $transaction->comments = $comments;
            $transaction->details = 'Added Balance Via Admin';
            $transaction->trx =  $trx;
            $transaction->save();

            notify($user, 'BAL_ADD', [
                'trx' => $trx,
                'amount' => showAmount($amount),
                'currency' => $general->cur_text,
                'post_balance' => showAmount($user->balance),
            ]);

        } else {
            if ($amount > $user->balance) {
                $notify[] = ['error', $user->username . '\'s has insufficient balance.'];
                return back()->withNotify($notify);
            }
            $user->balance -= $amount;
            $user->save();



            $transaction = new Transaction();
            $transaction->employer_id = $user->id;
            $transaction->amount = $amount;
            $transaction->post_balance = $user->balance;
            $transaction->charge = 0;
            $transaction->trx_type = '-';
            $transaction->comments = $comments;
            $transaction->details = 'Subtract Balance Via Admin';
            $transaction->trx =  $trx;
            $transaction->save();


            notify($user, 'BAL_SUB', [
                'trx' => $trx,
                'amount' => showAmount($amount),
                'currency' => $general->cur_text,
                'post_balance' => showAmount($user->balance)
            ]);
            $notify[] = ['success', $general->cur_sym . $amount . ' has been subtracted from ' . $user->username . '\'s balance'];
        }
        return back()->withNotify($notify);
    }



    public function userLoginHistory($id)
    {
        $user = Employer::findOrFail($id);
        $pageTitle = 'Emplyer Login History - ' . $user->username;
        $emptyMessage = 'No Emplyer login found.';
        $login_logs = UserLogin::where('employer_id', $user->id)->orderBy('id','desc')->with('employer')->paginate(getPaginate());
        return view('admin.employer.logins', compact('pageTitle', 'emptyMessage', 'login_logs'));
    }

    public function showEmailSingleForm($id)
    {
        $user = Employer::findOrFail($id);
        $pageTitle = 'Send Email To: ' . $user->username;
        return view('admin.employer.email_single', compact('pageTitle', 'user'));
    }

    public function sendEmailSingle(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string|max:65000',
            'subject' => 'required|string|max:190',
        ]);

        $user = Employer::findOrFail($id);
        sendGeneralEmail($user->email, $request->subject, $request->message, $user->username);
        $notify[] = ['success', $user->username . ' will receive an email shortly.'];
        return back()->withNotify($notify);
    }

    public function showEmailAllForm()
    {
        $pageTitle = 'Send Email To All Employers';
        return view('admin.employer.email_all', compact('pageTitle'));
    }

    public function sendEmailAll(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:65000',
            'subject' => 'required|string|max:190',
        ]);

        foreach (Employer::where('status', 1)->cursor() as $user) {
            sendGeneralEmail($user->email, $request->subject, $request->message, $user->username);
        }

        $notify[] = ['success', 'All employers will receive an email shortly.'];
        return back()->withNotify($notify);
    }

    public function login($id){
        $user = Employer::findOrFail($id);
        Auth::guard('employer')->login($user);
        return redirect()->route('employer.home');
    }

    public function emailLog($id){
        $user = Employer::findOrFail($id);
        $pageTitle = 'Email log of '.$user->username;
        $logs = EmailLog::where('employer_id',$id)->with('employer')->orderBy('id','desc')->paginate(getPaginate());
        $emptyMessage = 'No data found';
        return view('admin.employer.email_log', compact('pageTitle','logs','emptyMessage','user'));
    }

    public function emailDetails($id){
        $email = EmailLog::findOrFail($id);
        $pageTitle = 'Email details';
        return view('admin.users.email_details', compact('pageTitle','email'));
    }

}
