@extends($activeTemplate.'layouts.auth')
@section('content')
@php
    $auth = getContent('auth.content', true);
@endphp
<section class="account-section login-section">
    <div class="account-area">
        <div class="account-area__inner">
            <h3 class="title text-white text-center">@lang('Please Verify Your Mobile to Get Access')</h3>
            <h6 class="title text-white text-center">{{auth()->guard('employer')->user()->mobile}}</h6>
            <form method="POST" action="{{route('employer.verify.sms')}}" class="account-form box--border box--shadow p-sm-5 p-3 rounded-3">
                @csrf
                <div class="form-group">
                    <label for="code">@lang('Verification Code') <sup class="text--danger">*</sup></label>
                    <div class="custom-icon-field">
                        <i class="las la-key"></i>
                        <input type="text" id="code" name="sms_verified_code" maxlength="7" placeholder="@lang('Enter Verify Code')" class="form--control" required="">
                    </div>
                </div>

                <button type="submit" class="btn btn--base w-100 mt-2">@lang('Submit')</button>
                <p class="mt-3 text-center text-white">
                    @lang('If you don\'t get any code'), <a href="{{route('employer.send.verify.code')}}?type=phone" class="forget-pass"> @lang('Try again')</a>
                </p>

                @if ($errors->has('resend'))
                    <div class="text-center">
                        <small class="text-danger text-center">{{ $errors->first('resend') }}</small>
                    </div>
                @endif
            </form>
        </div>
    </div>
      
    <div class="account-thumb bg_img" style="background-image: url({{getImage('assets/images/frontend/auth/'. @$auth->data_values->background_image, '1920x1281')}})">
        <div class="account-thumb__content text-center">
            <h2 class="title text-white mb-4">@lang('Welcome to')</h2>
            <div class="account-area__logo text-center">
                <a href="{{route('home')}}"><img src="{{getImage(imagePath()['logoIcon']['path'] .'/logo.png')}}" alt="image"></a>
            </div>
        </div>
    </div>
</section>
@endsection
@push('script')
<script>
    (function($){
        "use strict";
        $('#code').on('input change', function () {
          var xx = document.getElementById('code').value;
          $(this).val(function (index, value) {
             value = value.substr(0,7);
              return value.replace(/\W/gi, '').replace(/(.{3})/g, '$1 ');
          });
      });
    })(jQuery)
</script>
@endpush