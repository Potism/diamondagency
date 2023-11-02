@extends($activeTemplate.'layouts.auth')
@section('content')
@php
    $auth = getContent('auth.content', true);
    $policys = getContent('policy_pages.element', false);
@endphp
<section class="account-section login-section">
    <div class="account-area">
        <div class="account-area__inner">
            <ul class="nav nav-tabs account-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="user-tab" data-bs-toggle="tab" data-bs-target="#user" type="button" role="tab" aria-controls="user" aria-selected="true">@lang('Candidate')</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="employer-tab" data-bs-toggle="tab" data-bs-target="#employer" type="button" role="tab" aria-controls="employer" aria-selected="false">@lang('Employer')</button>
                </li>
            </ul>
            <div class="tab-content mt-5" id="myTabContent">
                <div class="tab-pane fade show active" id="user" role="tabpanel" aria-labelledby="user-tab">
                    <form method="POST" action="{{route('user.login')}}" onsubmit="return submitUserForm();" class="account-form box--border box--shadow p-sm-5 p-3 rounded-3">
                        @csrf
                        <div class="form-group">
                            <label for="username">@lang('Username or Email') <sup class="text--danger">*</sup></label>
                            <div class="custom-icon-field">
                                <i class="las la-user"></i>
                                <input type="text" id="username" name="username" value="{{old('username')}}" placeholder="@lang('Username or Email')" class="form--control" required="">
                            </div>
                        </div>

                        <div class="form-group">
                            <label id="password">@lang('Password') <sup class="text--danger">*</sup></label>
                            <div class="custom-icon-field">
                                <i class="las la-key"></i>
                                <input type="password" id="password" name="password" placeholder="@lang('Enter Password')" class="form--control" required="">
                            </div>
                        </div>

                        <div class="form-group">
                            @php echo loadReCaptcha() @endphp
                        </div>

                        @include($activeTemplate.'partials.custom_captcha')

                        <button type="submit" class="btn btn--base w-100 mt-2">@lang('Login')</button>
                         <p class="mt-3 text-center text-white">@lang('New to ') {{__($general->sitename)}} ?
                            <a href="{{route('register')}}?user" class="text--base"><font style="color:#fff000;">@lang('Create an account')</font></a>
                        </p>

                        <p class="mt-3 text-center">
                            <a href="{{route('user.password.request')}}" class="text--base"><font style="color:#fff000;">@lang('Forgot Your Password?')</font></a>
                        </p>
                    </form>
                </div>


                <div class="tab-pane fade" id="employer" role="tabpanel" aria-labelledby="employer-tab">
                    <form method="POST" action="{{route('employer.login')}}" onsubmit="return submitUserForm();" class="account-form box--border box--shadow p-sm-5 p-3 rounded-3">
                        @csrf
                        <div class="form-group">
                            <label for="username">@lang('Username or Email') <sup class="text--danger">*</sup></label>
                            <div class="custom-icon-field">
                                <i class="las la-user"></i>
                                <input type="text" id="username" name="username" value="{{old('username')}}" placeholder="@lang('Username or Email')" class="form--control" required="">
                            </div>
                        </div>

                        <div class="form-group">
                            <label id="password">@lang('Password') <sup class="text--danger">*</sup></label>
                            <div class="custom-icon-field">
                                <i class="las la-key"></i>
                                <input type="password" id="password" name="password" placeholder="@lang('Enter Password')" class="form--control" required="">
                            </div>
                        </div>

                        <div class="form-group">
                            @php echo loadReCaptcha() @endphp
                        </div>

                        @include($activeTemplate.'partials.custom_captcha')

                        <button type="submit" class="btn btn--base w-100 mt-2">@lang('Login')</button>
                        <p class="mt-3 text-center text-white">@lang('New to ') {{__($general->sitename)}} ?
                            <a href="{{route('register')}}?employer" class="text--base"><font style="color:#fff000;">@lang('Create an account')</font></a>
                        </p>

                        <p class="mt-3 text-center">
                            <a href="{{route('employer.password.request')}}" class="text--base"><font style="color:#fff000;">@lang('Forgot Your Password?')</font></a>
                        </p>
                    </form>
                </div>
            </div>


            <!--ul class="inline-menu d-flex flex-wrap align-items-center justify-content-center mt-5">
                <li><a href="{{route('contact')}}">@lang('Support')</a></li>
                @foreach($policys as $policy)
                    <li><a href="{{route('footer.menu', [slug($policy->data_values->title), $policy->id])}}">{{__($policy->data_values->title)}}</a></li>
                @endforeach
            </ul-->
        </div>
    </div>
    <div class="account-thumb bg_img" style="background-image: url({{getImage('assets/images/frontend/auth/'. @$auth->data_values->background_image, '1920x1281')}})">
        <div class="account-thumb__content text-center">
            <h2 class="title text-white mb-4">@lang('Welcome to')</h2>
            <div class="account-area__logo text-center">
                <a href="{{route('home')}}"><img src="{{getImage(imagePath()['logoIcon']['path'] .'/logo.png')}}" alt="@lang('image')"></a>
            </div>
        </div>
    </div>
</section>
@endsection
@push('script')
    <script>
        "use strict";
        function submitUserForm() {
            var response = grecaptcha.getResponse();
            if (response.length == 0) {
                document.getElementById('g-recaptcha-error').innerHTML = '<span class="text-danger">@lang("Captcha field is required.")</span>';
                return false;
            }
            return true;
        }
    </script>
@endpush
