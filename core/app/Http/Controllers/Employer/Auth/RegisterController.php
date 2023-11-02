<?php

namespace App\Http\Controllers\Employer\Auth;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use App\Models\GeneralSetting;
use App\Models\Employer;
use App\Models\UserLogin;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
        $this->middleware('regStatus')->except('registrationNotAllowed');
        $this->activeTemplate = activeTemplate();
    }


    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $general = GeneralSetting::first();
        $password_validation = Password::min(6);
        if ($general->secure_password) {
            $password_validation = $password_validation->mixedCase()->numbers()->symbols()->uncompromised();
        }
        $agree = 'nullable';
        if ($general->agree) {
            $agree = 'required';
        }
        $countryData = (array)json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $countryCodes = implode(',', array_keys($countryData));
        $mobileCodes = implode(',',array_column($countryData, 'dial_code'));
        $countries = implode(',',array_column($countryData, 'country'));
        $validate = Validator::make($data, [
            'company_name' => 'required|string|max:40',
            'company_ceo' => 'required|string|max:40',
            'email' => 'required|string|email|max:40|unique:employers',
            'mobile' => 'required|string|max:40|unique:employers',
            'password' => ['required','confirmed',$password_validation],
            'username' => 'required|alpha_num|unique:employers|min:6',
            'captcha' => 'sometimes|required',
            'mobile_code' => 'required|in:'.$mobileCodes,
            'country_code' => 'required|in:'.$countryCodes,
            'country' => 'required|in:'.$countries,
            'agree' => $agree
        ]);
        return $validate;
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();
        $exist = Employer::where('mobile',$request->mobile)->first();
        if ($exist) {
            $notify[] = ['error', 'The mobile number already exists'];
            return back()->withNotify($notify)->withInput();
        }
        if (isset($request->captcha)) {
            if (!captchaVerify($request->captcha, $request->captcha_secret)) {
                $notify[] = ['error', "Invalid captcha"];
                return back()->withNotify($notify)->withInput();
            }
        }
        event(new Registered($user = $this->create($request->all())));
        auth()->guard('employer')->login($user);
        return $this->registered($request, $user)
            ?: redirect($this->redirectPath());
    }


    protected function create(array $data)
    {
        $general = GeneralSetting::first();
        $employer = new Employer();
        $employer->company_name = isset($data['company_name']) ? $data['company_name'] : null;
        $employer->ceo_name = isset($data['company_ceo']) ? $data['company_ceo'] : null;
        $employer->email = strtolower(trim($data['email']));
        $employer->password = Hash::make($data['password']);
        $employer->username = trim($data['username']);
        $employer->country_code = $data['country_code'];
        $employer->mobile = $data['mobile'];
        $employer->mobile_code = $data['mobile_code'];
        $employer->address = [
            'address' => '',
            'state' => '',
            'zip' => '',
            'country' => isset($data['country']) ? $data['country'] : null,
            'city' => ''
        ];
        $employer->status = 1;
        $employer->ev = $general->ev ? 0 : 1;
        $employer->sv = $general->sv ? 0 : 1;
        $employer->ts = 0;
        $employer->tv = 1;
        $employer->save();

        $adminNotification = new AdminNotification();
        $adminNotification->employer_id = $employer->id;
        $adminNotification->title = 'New Employer registered';
        $adminNotification->click_url = urlPath('admin.employers.detail',$employer->id);
        $adminNotification->save();

        $ip = $_SERVER["REMOTE_ADDR"];
        $exist = UserLogin::where('user_ip',$ip)->first();
        $userLogin = new UserLogin();
        if ($exist) {
            $userLogin->longitude =  $exist->longitude;
            $userLogin->latitude =  $exist->latitude;
            $userLogin->city =  $exist->city;
            $userLogin->country_code = $exist->country_code;
            $userLogin->country =  $exist->country;
        }else{
            $info = json_decode(json_encode(getIpInfo()), true);
            $userLogin->longitude =  @implode(',',$info['long']);
            $userLogin->latitude =  @implode(',',$info['lat']);
            $userLogin->city =  @implode(',',$info['city']);
            $userLogin->country_code = @implode(',',$info['code']);
            $userLogin->country =  @implode(',', $info['country']);
        }
        $userAgent = osBrowser();
        $userLogin->employer_id = $employer->id;
        $userLogin->user_ip =  $ip;
        
        $userLogin->browser = @$userAgent['browser'];
        $userLogin->os = @$userAgent['os_platform'];
        $userLogin->save();
        return $employer;
    }

    public function checkUser(Request $request){
        $exist['data'] = null;
        $exist['type'] = null;
        if ($request->email) {
            $exist['data'] = Employer::where('email',$request->email)->first();
            $exist['type'] = 'email';
        }
        if ($request->mobile) {
            $exist['data'] = Employer::where('mobile',$request->mobile)->first();
            $exist['type'] = 'mobile';
        }
        if ($request->username) {
            $exist['data'] = Employer::where('username',$request->username)->first();
            $exist['type'] = 'username';
        }
        return response($exist);
    }

    public function registered()
    {
        return redirect()->route('employer.profile');
    }

}
