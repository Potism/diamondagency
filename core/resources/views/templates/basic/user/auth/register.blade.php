@extends($activeTemplate.'layouts.auth')
@section('content')
@php
    $auth = getContent('auth.content', true);
    $policys = getContent('policy_pages.element', false);
@endphp
<section class="account-section">
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
                    <form method="POST" action="{{route('user.register')}}" class="account-form">
                        @csrf
                        <div class="row">

                            <div class="form-group col-md-6">
                                <label for="firstname">@lang('First Name') <sup class="text--danger">*</sup></label>
                                <div class="custom-icon-field">
                                    <i class="las la-user"></i>
                                    <input type="text" id="firstname" name="firstname" value="{{old('firstname')}}" placeholder="@lang('First name')" class="form--control" maxlength="40" required="">
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="lastname">@lang('Last Name') <sup class="text--danger">*</sup></label>
                                <div class="custom-icon-field">
                                    <i class="las la-user"></i>
                                    <input type="text" id="lastname" name="lastname" value="{{old('lastname')}}" placeholder="@lang('Last name')" class="form--control" maxlength="40" required="">
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="username">@lang('Username') <sup class="text--danger">*</sup></label>
                                <div class="custom-icon-field">
                                    <i class="las la-user"></i>
                                    <input type="text" id="username" name="username" value="{{old('username')}}" placeholder="@lang('Username')" class="form--control checkUser" maxlength="40" required="">
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="email">@lang('Email') <sup class="text--danger">*</sup></label>
                                <div class="custom-icon-field">
                                    <i class="las la-user"></i>
                                    <input type="email" id="email" name="email" value="{{old('email')}}" placeholder="@lang('Enter email address')" class="form--control checkUser" maxlength="40" required="">
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="country">{{ __('Country') }}</label>
                                <div class="custom-icon-field">
                                    <i class="las la-globe"></i>
                                    <select name="country" id="country" class="select" required="">
                                        @foreach($countries as $key => $country)
                                        <option data-mobile_code="{{ $country->dial_code }}" value="{{ $country->country }}" data-code="{{ $key }}" >{{ __($country->country) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <label>@lang('Mobile')</label>
                                <div class="input-group ">
                                    <span class="input-group-text bg--base text-white border-0 mobile-code"></span>
                                    <input type="hidden" name="mobile_code">
                                    <input type="hidden" name="country_code">
                                    <input type="text" name="mobile" id="mobile" value="{{ old('mobile') }}" class="form--control checkUser" placeholder="@lang('Your Phone Number')">
                                </div>
                            </div>

                            <div class="form-group hover-input-popup col-md-6">
                                <label for="password">@lang('Password') <sup class="text--danger">*</sup></label>
                                <div class="custom-icon-field">
                                    <i class="las la-key"></i>
                                    <input type="password" id="password" name="password" placeholder="@lang('Enter password')" class="form--control">
                                    @if($general->secure_password)
                                    <div class="input-popup">
                                        <p class="error lower">@lang('1 small letter minimum')</p>
                                        <p class="error capital">@lang('1 capital letter minimum')</p>
                                        <p class="error number">@lang('1 number minimum')</p>
                                        <p class="error special">@lang('1 special character minimum')</p>
                                        <p class="error minimum">@lang('6 character password')</p>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <label id="con_password">@lang('Confirm Password') <sup class="text--danger">*</sup></label>
                                <div class="custom-icon-field">
                                    <i class="las la-key"></i>
                                    <input type="password" id="con_password" name="password_confirmation" placeholder="@lang('Re-enter password')" class="form--control" required="">
                                </div>
                            </div>

                            <div class="form-group">
                                @php echo loadReCaptcha() @endphp
                            </div>

                            @include($activeTemplate.'partials.custom_captcha')

                            @if($general->agree)
                                <div class="form-group col-md-12">
                                    <input type="checkbox" id="agree" name="agree">
                                    <label for="agree">@lang('I agree with ')
                                        @foreach($policys as $policy)
                                            <a href="{{route('footer.menu', [slug($policy->data_values->title), $policy->id])}}"><font style="color:#d6d6d6;">{{__($policy->data_values->title)}}, </font></a>
                                        @endforeach
                                    </label>
                                </div>
                            @endif
                        </div>

                        <button type="submit" class="btn btn--base w-100 mt-2">@lang('Register Now')</button>

                        <p class="mt-3 text-center text-white">@lang('Have an account?')
                            <a href="{{route('login')}}" class="text--base"><font style="color:#fff000;">@lang('Login now')</font></a>
                        </p>
                    </form>
                </div>
                <div class="tab-pane fade" id="employer" role="tabpanel" aria-labelledby="employer-tab">
                    <form method="POST" action="{{route('employer.register')}}" class="account-form">
                        @csrf
                        <div class="row">

                            <div class="form-group col-md-6">
                                <label for="company_name">@lang('Company Name') <sup class="text--danger">*</sup></label>
                                <div class="custom-icon-field">
                                    <i class="las la-user"></i>
                                    <input type="text" id="company_name" name="company_name" value="{{old('company_name')}}" placeholder="@lang('Company name')" class="form--control" maxlength="40" required="">
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="company_ceo">@lang('Company CEO') <sup class="text--danger">*</sup></label>
                                <div class="custom-icon-field">
                                    <i class="las la-user"></i>
                                    <input type="text" id="company_ceo" name="company_ceo" value="{{old('company_ceo')}}" placeholder="@lang('Enter Company CEO')" class="form--control" maxlength="40" required="">
                                </div>
                            </div>



                            <div class="form-group col-md-6">
                                <label for="username">@lang('Username') <sup class="text--danger">*</sup></label>
                                <div class="custom-icon-field">
                                    <i class="las la-user"></i>
                                    <input type="text" id="username" name="username" value="{{old('username')}}" placeholder="@lang('Username')" class="form--control checkEmployer" maxlength="40" required="">
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="email">@lang('Email') <sup class="text--danger">*</sup></label>
                                <div class="custom-icon-field">
                                    <i class="las la-user"></i>
                                    <input type="email" id="email" name="email" value="{{old('email')}}" placeholder="@lang('Enter email address')" class="form--control checkEmployer" maxlength="40" required="">
                                </div>
                            </div>

                              <div class="form-group col-md-6">
                                <label for="country">{{ __('Country') }}</label>
                                <div class="custom-icon-field">
                                    <i class="las la-globe"></i>
                                    <select name="country" id="emp_country" class="select" required="">
                                        @foreach($countries as $key => $country)
                                        <option data-mobile_code="{{ $country->dial_code }}" value="{{ $country->country }}" data-code="{{ $key }}" >{{ __($country->country) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <label>@lang('Mobile')</label>
                                <div class="input-group ">
                                    <span class="input-group-text bg--base text-white border-0 emp_mobile-code"></span>
                                    <input type="hidden" name="mobile_code">
                                    <input type="hidden" name="country_code">
                                    <input type="text" name="mobile" id="emp_mobile" value="{{ old('mobile') }}" class="form--control checkEmployer" placeholder="@lang('Your Phone Number')" required="" maxlength="40">
                                </div>
                                <small class="text-danger mobileExist"></small>
                            </div>

                            <div class="form-group hover-input-popup col-md-6">
                                <label for="password">@lang('Password') <sup class="text--danger">*</sup></label>
                                <div class="custom-icon-field">
                                    <i class="las la-key"></i>
                                    <input type="password" id="password" name="password" placeholder="@lang('Enter password')" class="form--control">
                                    @if($general->secure_password)
                                    <div class="input-popup">
                                        <p class="error lower">@lang('1 small letter minimum')</p>
                                        <p class="error capital">@lang('1 capital letter minimum')</p>
                                        <p class="error number">@lang('1 number minimum')</p>
                                        <p class="error special">@lang('1 special character minimum')</p>
                                        <p class="error minimum">@lang('6 character password')</p>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <label id="con_password">@lang('Confirm Password') <sup class="text--danger">*</sup></label>
                                <div class="custom-icon-field">
                                    <i class="las la-key"></i>
                                    <input type="password" id="con_password" name="password_confirmation" placeholder="@lang('Re-enter password')" class="form--control" required="">
                                </div>
                            </div>

                            <div class="form-group">
                                @php echo loadReCaptcha() @endphp
                            </div>

                            @include($activeTemplate.'partials.custom_captcha')

                            @if($general->agree)
                                <div class="form-group col-md-12">
                                    <input type="checkbox" id="agree" name="agree">
                                    <label for="agree">@lang('I agree with ')
                                        @foreach($policys as $policy)
                                            <a href="{{route('footer.menu', [slug($policy->data_values->title), $policy->id])}}"><font style="color:#d6d6d6;">{{__($policy->data_values->title)}},</font></a>
                                        @endforeach
                                    </label>
                                </div>
                            @endif
                        </div>

                        <button type="submit" class="btn btn--base w-100 mt-2">@lang('Register Now')</button>

                        <p class="mt-3 text-center text-white">@lang('Have an account?')
                            <a href="{{route('login')}}" class="text--base"><font style="color:#fff000;">@lang('Login now')</font></a>
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

<div class="modal fade custom--modal" id="existModalCenter" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">@lang('You are with us')</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6>@lang('You already have an account please Sign in')</h6>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn--secondary btn-sm" data-bs-dismiss="modal">@lang('Close')</button>
                <a href="{{ route('user.login') }}" class="btn btn--primary btn-sm">@lang('Login')</a>
            </div>
        </div>
    </div>
</div>

@endsection

@push('style')
<style>
    .country-code .input-group-prepend .input-group-text{
        background: #fff !important;
    }
    .country-code select{
        border: none;
    }
    .country-code select:focus{
        border: none;
        outline: none;
    }
    .hover-input-popup {
        position: relative;
    }
    .hover-input-popup:hover .input-popup {
        opacity: 1;
        visibility: visible;
    }
    .input-popup {
        position: absolute;
        bottom: 130%;
        left: 50%;
        width: 280px;
        background-color: #1a1a1a;
        color: #fff;
        padding: 20px;
        border-radius: 5px;
        -webkit-border-radius: 5px;
        -moz-border-radius: 5px;
        -ms-border-radius: 5px;
        -o-border-radius: 5px;
        -webkit-transform: translateX(-50%);
        -ms-transform: translateX(-50%);
        transform: translateX(-50%);
        opacity: 0;
        visibility: hidden;
        -webkit-transition: all 0.3s;
        -o-transition: all 0.3s;
        transition: all 0.3s;
    }
    .input-popup::after {
        position: absolute;
        content: '';
        bottom: -19px;
        left: 50%;
        margin-left: -5px;
        border-width: 10px 10px 10px 10px;
        border-style: solid;
        border-color: transparent transparent #1a1a1a transparent;
        -webkit-transform: rotate(180deg);
        -ms-transform: rotate(180deg);
        transform: rotate(180deg);
    }
    .input-popup p {
        padding-left: 20px;
        position: relative;
    }
    .input-popup p::before {
        position: absolute;
        content: '';
        font-family: 'Line Awesome Free';
        font-weight: 900;
        left: 0;
        top: 4px;
        line-height: 1;
        font-size: 18px;
    }
    .input-popup p.error {
        text-decoration: line-through;
    }
    .input-popup p.error::before {
        content: "\f057";
        color: #ea5455;
    }
    .input-popup p.success::before {
        content: "\f058";
        color: #28c76f;
    }
</style>
@endpush
@push('script-lib')
<script src="{{ asset('assets/global/js/secure_password.js') }}"></script>
@endpush
@push('script')
<script>
        $('#user-tab').click('on',function()
    {
      window.history.replaceState(null, null, "?user");
    })
    $('#employer-tab').click('on',function()
    {
      window.history.replaceState(null, null, "?employer");
    })
    $(document).ready(function()
    {
      if (window.location.href.indexOf("user") > -1)
      {
        $('#user-tab').click();
      }
      else if(window.location.href.indexOf("employer") > -1)
      {
        $('#employer-tab').click();
      }
      else
      {
          $('#user-tab').click();
      }
  });
      "use strict";
        function submitUserForm() {
            var response = grecaptcha.getResponse();
            if (response.length == 0) {
                document.getElementById('g-recaptcha-error').innerHTML = '<span class="text-danger">@lang("Captcha field is required.")</span>';
                return false;
            }
            return true;
        }
        (function ($) {
            $('#country').val('Canada');
            $('#emp_country').val('Canada');
            @if($mobile_code)
            $(`option[data-code={{ $mobile_code }}]`).attr('selected','');
            @endif
            $('select[name=country]').change(function()
            {
              var active = $('#employer-tab').hasClass('active');
              if(active == true)
              {
                $('input[name=emp_mobile_code]').val($('select[name=country] :selected').last().attr('data-mobile_code'));
                $('input[name=emp_country_code]').val($('select[name=country] :selected').last().attr('data-code'));
                $('.emp_mobile-code').text('+'+$('select[name=country] :selected').last().attr('data-mobile_code'));
              }
              else
              {
                $('input[name=mobile_code]').val($('select[name=country] :selected').data('mobile_code'));
                $('input[name=country_code]').val($('select[name=country] :selected').data('code'));
                $('.mobile-code').text('+'+$('select[name=country] :selected').data('mobile_code'));
              }
            });
            $('input[name=mobile_code]').val($('select[name=country] :selected').data('mobile_code'));
            $('input[name=country_code]').val($('select[name=country] :selected').data('code'));
            $('.mobile-code').text('+'+$('select[name=country] :selected').data('mobile_code'));
            $('input[name=mobile_code]').val($('select[name=country] :selected').data('mobile_code'));
            $('input[name=country_code]').val($('select[name=country] :selected').data('code'));
            $('.emp_mobile-code').text('+'+$('select[name=country] :selected').data('mobile_code'));
            @if($general->secure_password)
                $('input[name=password]').on('input',function(){
                    secure_password($(this));
                });
            @endif

            $('.checkUser').on('focusout',function(e){
                var url = '{{ route('user.checkUser') }}';
                var value = $(this).val();
                var token = '{{ csrf_token() }}';
                if ($(this).attr('name') == 'mobile') {
                    var mobile = `${$('.mobile-code').text().substr(1)}${value}`;
                    var data = {mobile:mobile,_token:token}
                }
                if ($(this).attr('name') == 'email') {
                    var data = {email:value,_token:token}
                }
                if ($(this).attr('name') == 'username') {
                    var data = {username:value,_token:token}
                }
                $.post(url,data,function(response) {
                  if (response['data'] && response['type'] == 'email') {
                    $('#existModalCenter').modal('show');
                  }else if(response['data'] != null){
                    $(`.${response['type']}Exist`).text(`${response['type']} already exist`);
                  }else{
                    $(`.${response['type']}Exist`).text('');
                  }
                });
            });


            $('.checkEmployer').on('focusout',function(e){
                var url = '{{ route('employer.checkUser') }}';
                var value = $(this).val();
                var token = '{{ csrf_token() }}';
                if ($(this).attr('name') == 'mobile') {
                    var mobile = `${$('.mobile-code').text().substr(1)}${value}`;
                    var data = {mobile:mobile,_token:token}
                }
                if ($(this).attr('name') == 'email') {
                    var data = {email:value,_token:token}
                }
                if ($(this).attr('name') == 'username') {
                    var data = {username:value,_token:token}
                }
                $.post(url,data,function(response) {
                  if (response['data'] && response['type'] == 'email') {
                    $('#existModalCenter').modal('show');
                  }else if(response['data'] != null){
                    $(`.${response['type']}Exist`).text(`${response['type']} already exist`);
                  }else{
                    $(`.${response['type']}Exist`).text('');
                  }
                });
            });
        })(jQuery);
    </script>
@endpush
