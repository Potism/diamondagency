@extends($activeTemplate.'layouts.auth')
@section('content')
@php
    $auth = getContent('auth.content', true);
@endphp
<section class="account-section login-section">
    <div class="account-area">
        <div class="account-area__inner">
            <form method="POST" action="{{ route('user.password.verify.code') }}" class="account-form box--border box--shadow p-sm-5 p-3 rounded-3">
                @csrf
                <input type="hidden" name="email" value="{{ $email }}">

                <div class="form-group">
                    <label for="code">@lang('Verification Code') <sup class="text--danger">*</sup></label>
                    <div class="custom-icon-field">
                        <i class="las la-key"></i>
                        <input type="text" id="code" name="code" placeholder="@lang('Enter Verify Code')" class="form--control" required="">
                    </div>
                </div>

                <button type="submit" class="btn btn--base w-100 mt-2">@lang('Verify Code')</button>
                <p class="mt-3 text-center text-white">
                    @lang('Please check including your Junk/Spam Folder. if not found, you can')
                    <a href="{{ route('user.password.request') }}">@lang('Try to send again')</a>
                </p>
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