@extends($activeTemplate.'layouts.frontend')
@section('content')
 <div class="pt-50 pb-50 section--bg">
        <div class="container">
            <div class="row justify-content-center gy-4">
                <!-- @include($activeTemplate . 'partials.employer_sidebar') -->
                <div class="col-xl-12 ps-xl-4"> 
                    <div class="custom--card mt-4">
                        <div class="card-header bg--dark d-flex justify-content-between align-items-center">
                            <h5 class="text-white me-3">{{__($pageTitle)}}</h5>
                            <a href="{{route('ticket') }}" class="text--base p-0 bg-transparent"><i class="las la-plus"></i> @lang('Add New')</a>
                        </div>
                        <div class="card-body">
                            <form  action="{{route('ticket.store')}}"  method="post" enctype="multipart/form-data" onsubmit="return submitUserForm();">
                                @csrf
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="name">@lang('Name')</label>
                                        <input type="text" name="name" value="{{@$user->ceo_name}}" class="form--control" placeholder="@lang('Enter your name')" readonly>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="email">@lang('Email address')</label>
                                        <input type="email"  name="email" value="{{@$user->email}}" class="form--control" placeholder="@lang('Enter your email')" readonly>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="website">@lang('Subject')</label>
                                        <input type="text" name="subject" value="{{old('subject')}}" class="form--control" placeholder="@lang('Subject')" >
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="priority">@lang('Priority')</label>
                                        <select name="priority" class="form--control">
                                            <option value="3">@lang('High')</option>
                                            <option value="2">@lang('Medium')</option>
                                            <option value="1">@lang('Low')</option>
                                        </select>
                                    </div>
                                    <div class="col-12 form-group">
                                        <label for="inputMessage">@lang('Message')</label>
                                        <textarea name="message" id="inputMessage" placeholder="@lang('Enter Message')" rows="4" class="form--control">{{old('message')}}</textarea>
                                    </div>
                                </div>

                                <div class="row justify-content-between">
                                    <div class="col-md-8">
                                        <div class="row justify-content-between">
                                            <div class="col-md-11">
                                                <div class="form-group">
                                                    <label for="inputAttachments">@lang('Attachments')</label>
                                                    <input type="file" name="attachments[]" id="inputAttachments" class="form-control"/>
                                                    <div id="fileUploadsContainer"></div>
                                                    <p class="my-2 ticket-attachments-message text-muted">
                                                        @lang('Allowed File Extensions'): .@lang('jpg'), .@lang('jpeg'), .@lang('png'), .@lang('pdf'), .@lang('doc'), .@lang('docx')
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <div class="form-group pt-3">
                                                    <a href="javascript:void(0)" class="btn px-2 py-1 mt-4 btn--primary addFile">
                                                        <i class="las la-plus"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row form-group justify-content-center">
                                    <div class="col-md-12">
                                        <button class="btn btn--success w-100" type="submit" id="recaptcha" ><i class="fa fa-paper-plane"></i>&nbsp;@lang('Submit')</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        (function ($) {
            "use strict";
            $('.addFile').on('click',function(){
                $("#fileUploadsContainer").append(`
                   <div class="input-group mt-2">
                        <input type="file" name="attachments[]" class="form-control" required />
                        <span class="input-group-text btn btn-sm btn--danger support-btn remove-btn"><i class="las la-times"></i></span>
                    </div>
                `)
            });
            $(document).on('click','.remove-btn',function(){
                $(this).closest('.input-group').remove();
            });
        })(jQuery);
    </script>
@endpush
