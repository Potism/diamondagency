@extends($activeTemplate.'layouts.auth')
@section('content')
@php
    $auth = getContent('auth.content', true);
@endphp
<section class="account-section login-section">
    <div class="account-area">
        <div class="account-area__inner">
            <h3 class="title text-white text-center">@lang('Reset Password')</h3>
            <form method="POST" action="{{ route('employer.password.email') }}" class="account-form box--border box--shadow p-sm-5 p-3 rounded-3">
                @csrf

                <div class="form-group">
                    <label>{{ __('Select One') }}</label>
                    <select name="type" class="select" required="">
                        <option value="email">@lang('E-Mail Address')</option>
                        <option value="username">@lang('Username')</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="value" class="my_value"></label>
                    <div class="custom-icon-field">
                        <i class="las la-envelope"></i>
                        <input type="text" id="value" name="value" value="{{old('value')}}" class="form--control" autofocus="off" required="">
                        @error('value')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <button type="submit" class="btn btn--base w-100 mt-2">@lang('Send Password Code')</button>
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
        
        myVal();
        $('select[name=type]').on('change',function(){
            myVal();
        });
        function myVal(){
            $('.my_value').text($('select[name=type] :selected').text());
        }
    })(jQuery)
</script>
@endpush