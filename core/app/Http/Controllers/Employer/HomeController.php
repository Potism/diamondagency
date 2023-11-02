<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Lib\GoogleAuthenticator;
use App\Models\AdminNotification;
use App\Models\Deposit;
use App\Models\GatewayCurrency;
use App\Models\GeneralSetting;
use App\Models\Industry;
use App\Models\Job;
use App\Models\Extension;
use App\Models\NumberOfEmployees;
use App\Models\Order;
use App\Models\Plan;
use App\Models\Transaction;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Image;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->activeTemplate = activeTemplate();
    }

    public function dashboard()
    {
        $pageTitle = "Employer Dashboard";
        $emptyMessage = "No data found";
        $employer = Auth::guard('employer')->user();
        $jobCount = Job::where('employer_id', $employer->id)->count();
        $plans = Plan::where('status', 1)->latest()->paginate(getPaginate());
        $totalDeposit = Deposit::where('employer_id',$employer->id)->where('status',1)->sum('amount');
        $totalTransaction = Transaction::where('employer_id',$employer->id)->count();
        $planOrders = Order::where('employer_id', $employer->id)->where('status','!=', 0)->with('plan')->get();
        return view($this->activeTemplate.'employer.dashboard', compact('pageTitle', 'plans', 'jobCount', 'totalDeposit', 'totalTransaction', 'planOrders', 'emptyMessage', 'employer'));
    }

    public function profile()
    {
        $pageTitle = "Employer Profile Settings ";
        $industries = Industry::where('status', 1)->select('id', 'name')->orderBy('name', 'asc')->get();
        $numberOfEmployees = NumberOfEmployees::where('status', 1)->select('id', 'employees')->get();
        $employer = Auth::guard('employer')->user();
        $apikey = Extension::where('act', 'map_api')->where('status', 1)->first();
        $countries = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $googlemapkey = '';
        if(!empty($apikey))
        {
          $googlemapkey = $apikey->shortcode->api_key->value;
        }
        $emp_rev = Review::where('employer_id',$employer->id)->where('comment','rating by candidate')->select('*')->get();
        $avg = $emp_rev->average('rating');
        $avg2 = $emp_rev->sum('rating');
        $medal = getmedal($avg2);
        $count = $emp_rev->count();
        return view($this->activeTemplate . 'employer.profile', compact('pageTitle', 'employer', 'industries', 'numberOfEmployees','googlemapkey','countries','avg','medal','count'));
    }

    public function submitProfile(Request $request)
    {
        $employer = Auth::guard('employer')->user();
        $request->validate([
            'company_name' => 'required|max:40',
            'ceo_name' => 'required|max:40',
            'email' => 'required|max:40|unique:employers,email,'.$employer->id,
            'mobile' => 'required|max:40|unique:employers,mobile,'.$employer->id,
            'fax' => 'nullable|max:40|unique:employers,fax,'.$employer->id,
            'industry' => 'required|exists:industries,id',
            'number_of_employees' => 'required|exists:number_of_employees,id',
            'address' => 'required|max:200',
            'state' => 'required|max:80',
            'city' => 'required|max:40',
            'zip' => 'required|max:40',
            'description' => 'required',
            'country' => 'required',
            'facebook' => 'nullable|url',
            'twitter' => 'nullable|url',
            'pinterest' => 'nullable|url',
            'linkedin' => 'nullable|url',
            'logo' => ['nullable','image',new FileTypeValidate(['jpg','jpeg','png'])]
        ]);
        $employer->company_name = $request->company_name;
        $employer->ceo_name = $request->ceo_name;
        $employer->email = $request->email;
        $employer->mobile = $request->mobile;
        $employer->mobile_code = $request->mobile_code;
        $employer->fax = $request->fax;
        $employer->industry_id = $request->industry;
        $employer->number_of_employees_id = $request->number_of_employees;
        $employer->website = $request->website;
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
        $employer->country_code = $country_code;
        $address = [
            'address' => $request->address,
            'state' => $request->state,
            'zip' => $request->zip,
            'country' => $request->country,
            'city' => $request->city,
        ];
        $employer->address = $address;
        $socialMedia =  [
            'facebook' => $request->facebook,
            'twitter' => $request->twitter,
            'pinterest' => $request->pinterest,
            'linkedin' => $request->linkedin
        ];
        $employer->socialMedia = $socialMedia;
        $employer->description = $request->description;
        if ($request->hasFile('logo')) {
            $location = imagePath()['employerLogo']['path'];
            $filename = uploadImage($request->logo, $location, $size=null, $employer->image);
            $employer->image = $filename;
        }
        $employer->save();
        $notify[] = ['success', 'Employers Profile updated successfully.'];
        return back()->withNotify($notify);
    }


    public function changePassword()
    {
        $pageTitle = 'Change Password';
        return view($this->activeTemplate . 'employer.password', compact('pageTitle'));
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
            $user = auth()->guard('employer')->user();
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
        $user = auth()->guard('employer')->user();
        $secret = $ga->createSecret();
        $qrCodeUrl = $ga->getQRCodeGoogleUrl($user->username . '@' . $general->sitename, $secret);
        $pageTitle = 'Two Factor Authentication';
        return view($this->activeTemplate.'employer.twofactor', compact('pageTitle', 'secret', 'qrCodeUrl'));
    }


    public function create2fa(Request $request)
    {
        $user = auth()->guard('employer')->user();
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

        $user = auth()->guard('employer')->user();
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


    public function depositHistory()
    {
        $pageTitle = "Deposit History";
        $emptyMessage = "No data found";
        $employer = auth()->guard('employer')->user();
        $logs = Deposit::where('employer_id', $employer->id)->with('gateway')->where('status','!=',0)->latest()->paginate(getPaginate());
        return view($this->activeTemplate . 'employer.deposit_history', compact('pageTitle', 'emptyMessage', 'logs'));
    }

    public function transaction()
    {
        $pageTitle = "Transaction History";
        $emptyMessage = "No data found";
        $employer = auth()->guard('employer')->user();
        $transactions = Transaction::where('employer_id', $employer->id)->orderBy('id', 'DESC')->paginate(getPaginate());
        return view($this->activeTemplate . 'employer.transaction', compact('pageTitle', 'emptyMessage', 'transactions','employer'));
    }

    public function planSubscribe(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:plans,id',
            'payment' => 'required|in:1,2',
        ]);
        $plan = Plan::findOrFail($request->plan_id);
        $employer = Auth::guard('employer')->user();
        $order = Order::where('employer_id', $employer->id)->where('status', 1)->first();
        // if($order){
        //     $notify[] = ['error', 'You are already subscribed'];
        //     return back()->withNotify($notify);
        // }

        if($request->payment == 1){
            if($plan->amount > $employer->balance){
                $notify[] = ['error', 'Your account insufficient balance!'];
                return back()->withNotify($notify);
            }
            $employer->balance -= $plan->amount;
            $employer->job_post_count = $plan->job_post;
            $employer->save();

            $order = new Order();
            $order->plan_id  = $request->plan_id;
            $order->employer_id = $employer->id;
            $order->order_number = getTrx();
            $order->amount = $plan->amount;
            $order->status = 1;
            $order->save();

            $transaction = new Transaction();
            $transaction->employer_id = $employer->id;
            $transaction->amount = $order->amount;
            $transaction->post_balance = $employer->balance;
            $transaction->trx_type = '-';
            $transaction->details = 'Payment via wallet';
            $transaction->trx = getTrx();
            $transaction->save();

            $employer->balance += $plan->amount;
            $employer->job_post_count = $plan->job_post;
            $employer->save();

            $transaction = new Transaction();
            $transaction->employer_id = $employer->id;
            $transaction->amount = $order->amount;
            $transaction->post_balance = $employer->balance;
            $transaction->trx_type = '+';
            $transaction->details = 'Plan amount deposited via wallet';
            $transaction->trx = getTrx();
            $transaction->save();

            $adminNotification = new AdminNotification();
            $adminNotification->user_id = $employer->id;
            $adminNotification->title = 'Plan subscriber';
            $adminNotification->click_url = urlPath('admin.plan.subscriber');
            $adminNotification->save();

            $notify[] = ['success', 'The plan has been subscribed'];
            return back()->withNotify($notify);
        }
        else if($request->payment == 2){
            $order = new Order();
            $order->plan_id  = $request->plan_id;
            $order->employer_id = $employer->id;
            $order->order_number = getTrx();
            $order->amount = $plan->amount;
            $order->status = 0;
            $order->save();
            session()->put('order',$order->order_number);
            return redirect()->route('employer.payment');
        }
    }

    public function payment()
    {
        $gatewayCurrency = GatewayCurrency::whereHas('method', function ($gate) {
            $gate->where('status', 1);
        })->with('method')->orderby('method_code')->get();
        $pageTitle = 'Payment Methods';
        return view($this->activeTemplate . 'employer.payment', compact('gatewayCurrency', 'pageTitle'));
    }

    public function paymentInsert(Request $request)
    {
        $request->validate([
            'order_number' => 'required|exists:orders,order_number',
            'method_code' => 'required',
            'currency' => 'required',
        ]);
        $booking = Order::where('status', 0)->where('order_number', $request->order_number)->first();
        if(!$booking){
            $notify[] = ['error', 'Invalid order number'];
            return back()->withNotify($notify);
        }
        $user = auth()->guard('employer')->user();
        $gate = GatewayCurrency::whereHas('method', function ($gate) {
            $gate->where('status', 1);
        })->where('method_code', $request->method_code)->where('currency', $request->currency)->first();
        if (!$gate) {
            $notify[] = ['error', 'Invalid gateway'];
            return back()->withNotify($notify);
        }

        if ($gate->min_amount > $booking->amount || $gate->max_amount < $booking->amount) {
            $notify[] = ['error', 'Please follow deposit limit'];
            return back()->withNotify($notify);
        }

        $charge = $gate->fixed_charge + ($booking->amount * $gate->percent_charge / 100);
        $payable = $booking->amount + $charge;
        $final_amo = $payable * $gate->rate;

        $data = new Deposit();
        $data->employer_id = $user->id;
        $data->order_id = $booking->id;
        $data->method_code = $gate->method_code;
        $data->method_currency = strtoupper($gate->currency);
        $data->amount = $booking->amount;
        $data->charge = $charge;
        $data->rate = $gate->rate;
        $data->final_amo = $final_amo;
        $data->btc_amo = 0;
        $data->btc_wallet = "";
        $data->trx = $booking->order_number;
        $data->try = 0;
        $data->status = 0;
        $data->save();
        session()->put('Track', $data->trx);
        return redirect()->route('employer.deposit.preview');
    }
}
