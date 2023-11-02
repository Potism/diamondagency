<?php

namespace App\Http\Controllers\Employer\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Extension;
use App\Models\UserLogin;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use PDF;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */

    protected $username;

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct()
    {
        $this->middleware('employer.guest')->except('logout');
        $this->username = $this->findUsername();
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);
        if(isset($request->captcha)){
            if(!captchaVerify($request->captcha, $request->captcha_secret)){
                $notify[] = ['error',"Invalid captcha"];
                return back()->withNotify($notify)->withInput();
            }
        }

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);
        return $this->sendFailedLoginResponse($request);
    }

    public function findUsername()
    {
        $login = request()->input('username');
        $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        request()->merge([$fieldType => $login]);
        return $fieldType;
    }

    protected function guard()
    {
        return Auth::guard('employer');
    }

    public function username()
    {
        return $this->username;
    }

    protected function validateLogin(Request $request)
    {
        $customRecaptcha = Extension::where('act', 'custom-captcha')->where('status', 1)->first();
        $validation_rule = [
            $this->username() => 'required|string',
            'password' => 'required|string',
        ];
        if ($customRecaptcha) {
            $validation_rule['captcha'] = 'required';
        }
        $request->validate($validation_rule);

    }

    public function logout()
    {
        $this->guard()->logout();
        request()->session()->invalidate();
        $notify[] = ['success', 'You have been logged out.'];
        return redirect()->route('user.login')->withNotify($notify);
    }


    public function authenticated(Request $request, $user)
    {
        if ($user->status == 0) {
            $this->guard()->logout();
            $notify[] = ['error','Your account has been deactivated.'];
            return redirect()->route('user.login')->withNotify($notify);
        }
        $user =  auth()->guard('employer')->user();
        $user->tv = $user->ts == 1 ? 0 : 1;
        $user->save();
        $ip = $_SERVER["REMOTE_ADDR"];
        $exist = UserLogin::where('user_ip',$ip)->first();
        $userLogin = new UserLogin();
        $info = getIpInfo();
        $userLogin->longitude =  $info['long'];
        $userLogin->latitude =  $info['lat'];
        $userLogin->city =  $info['city'];
        $userLogin->country_code = $info['code'];
        $userLogin->country = $info['country'];
        $userAgent = osBrowser();
        $userLogin->employer_id = $user->id;
        $userLogin->user_ip =  $ip;
        
        $userLogin->browser = @$userAgent['browser'];
        $userLogin->os = @$userAgent['os_platform'];
        $userLogin->save();
        return redirect()->route('employer.home');
    }
    public function generatePdf(Request $request)
    {
        // $res = orders::where('orders.id',$order_id)
        //     ->select('orders.*','users.name','users.job_title')
        //     ->join('users','users.id','orders.user_id')
        //     ->first();
        //
        // $order_details = order_details::select('*')
        // ->join('products','products.id','order_details.product_id')
        // ->join('product_units','product_units.id','order_details.unit_id')
        // ->whereOrderId($order_id)->get();
        //
        // $suppliers = NewSupplier::where('id',$res->supplier_id)->first();
        // $project = Project::whereId($res->project_id)->first();
        // $contact = Contact::where('supplier_id',$res->supplier_id)
        //         ->where('type','Supplier')->first();
        //
        // $data['details'] = $order_details;
        // $data['supplier_name'] = isset($suppliers->company_name) ? $suppliers->company_name : '';
        // $data['orders'] = $res;
        // $data['project'] = $project;
        // $data['contact'] = $contact;
        $data = array();
        $pdf = PDF::loadView('templates.basic.invoice_pdf', $data);
        $pdf->setOptions(['isPhpEnabled' => true,'isRemoteEnabled' => true,'isHtml5ParserEnabled' => true]);
        header("Content-type: application/pdf");
        header("Content-Transfer-Encoding: binary");
        // echo $pdf->stream();
        // exit();
        // $filename = $res->po_number.".pdf";
        $filename = 'abc.pdf';
        $pdf->save(storage_path('app/public/uploads/'.$filename));
        // $pdf->save(public_path().'/uploads/pdf/'.$filename);
    }
    public function downloadInvoice(Request $request)
    {
        $filename = "abc.pdf";
        $path = storage_path('app/public/uploads/');
        $file = storage_path('app/public/uploads/'.$filename);
        $headers = array(
            'Content-Type: application/pdf',
        );
        return \Response::download($file, $filename, $headers);
    }

}
