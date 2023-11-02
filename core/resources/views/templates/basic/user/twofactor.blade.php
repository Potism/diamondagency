@extends($activeTemplate.'layouts.frontend')
@section('content')
<div class="pt-50 pb-50 section--bg">
    <div class="container">
        <div class="row justify-content-center gy-4">
            <!-- @include($activeTemplate . 'partials.user_sidebar') -->
            <div class="col-xl-12 ps-xl-4">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="custom--card mt-4">
                            <div class="card-header bg--dark">
                                <h5 class="text-white">@lang('Two Factor Authenticator')</h5>
                            </div>
                            <div class="card-body">
                                @if(Auth::user()->ts)
                                    <div class="form-group mx-auto text-center">
                                        <a href="javascript:void(0)"  class="btn btn-block btn--lg btn--danger" data-bs-toggle="modal" data-bs-target="#disableModal">
                                            @lang('Disable Two Factor Authenticator')</a>
                                    </div>
                                @else
                                    <div class="form-group">
                                        <div class="input-group">
                                            <input type="text" name="key" value="{{$secret}}" class="form-control form-control-lg" id="referralURL" readonly>
                                            <span class="input-group-text copytext" id="copyBoard"> <i class="fa fa-copy"></i> </span>
                                        </div>
                                    </div>
                                    <div class="form-group mx-auto text-center">
                                        <img class="mx-auto" src="{{$qrCodeUrl}}">
                                    </div>
                                    <div class="form-group mx-auto text-center">
                                        <a href="javascript:void(0)" class="btn btn--success btn--lg mt-3" data-bs-toggle="modal" data-bs-target="#enableModal">@lang('Enable Two Factor Authenticator')</a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="custom--card mt-4">
                            <div class="card-header bg--dark">
                                <h5 class="text-white">@lang('Google Authenticator')</h5>
                            </div>
                            <div class="card-body">
                                    <p>@lang('Google Authenticator is a multifactor app for mobile devices. It generates timed codes used during the 2-step verification process. To use Google Authenticator, install the Google Authenticator application on your mobile device.')</p>
                                    <a class="btn btn--success btn--lg mt-3" href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2&hl=en" target="_blank">@lang('DOWNLOAD APP')</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Enable Modal -->
<div class="modal fade custom--modal" id="enableModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{route('user.twofactor.enable')}}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">@lang('Verify Your Otp')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                   <div class="form-group">
                        <input type="hidden" name="key" value="{{$secret}}">
                        <input type="text" class="form--control" name="code" placeholder="@lang('Enter Google Authenticator Code')">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--secondary btn-md" data-bs-dismiss="modal">@lang('Close')</button>
                    <button type="submit" class="btn btn--success btn-md">@lang('Confirm')</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Disable Modal -->
<div class="modal fade custom--modal" id="disableModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{route('user.twofactor.disable')}}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">@lang('Verify Your Otp Disable')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input type="text" class="form--control" name="code" placeholder="@lang('Enter Google Authenticator Code')">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--secondary btn-md" data-bs-dismiss="modal">@lang('Close')</button>
                    <button type="submit" class="btn btn--success btn-md">@lang('Confirm')</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('script')
    <script>
        "use strict";
        $('.copytext').on('click',function(){
            var copyText = document.getElementById("referralURL");
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            document.execCommand("copy");
            iziToast.success({message: "Copied: " + copyText.value, position: "topRight"});
        });
    </script>
@endpush


