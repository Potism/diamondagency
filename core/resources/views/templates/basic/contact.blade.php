@extends($activeTemplate.'layouts.frontend')
@section('content')
@php 
    $contact = getContent('contact_us.content', true);
@endphp

<section class="pt-100 pb-100">
    <div class="container">
        <div class="row gy-4">
            <div class="col-lg-6 pe-lg-4">
                <div class="contact-form-area">
                    <h3 class="title">@lang('Contact Us')</h3>
                    <form method="POST" class="mt-4">
                        @csrf
                        <div class="form-group">
                            <label>@lang('Name')</label>
                            <input type="text" name="name" class="form--control" placeholder="@lang('Full name')" required="">
                        </div>
                        <div class="form-group">
                            <label>@lang('Email')</label>
                            <input type="email" name="email" class="form--control" placeholder="@lang('Enter Your email')" required="">
                        </div>
                        <div class="form-group">
                            <label>@lang('Subject')</label>
                            <input type="text" name="subject" class="form--control" placeholder="@lang('Enter Subject')" required="">
                        </div>
                        <div class="form-group">
                            <label>@lang('Message')</label>
                            <textarea name="message" class="form--control" placeholder="@lang('Your message')" required=""></textarea>
                        </div>
                        <button type="submit" class="btn btn--base w-100">@lang('Submit Now')</button>
                    </form>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="map-area">
                    <iframe src = "https://maps.google.com/maps?q={{$contact->data_values->latitude}},{{$contact->data_values->longitude}}&hl=es;z=14&amp;output=embed"></iframe>
                </div>
            </div>
        </div>
        <div class="row mt-5 gy-4 justify-content-center">
            <div class="col-lg-4 col-md-6">
                <div class="contact-item">
                    <div class="contact-item__icon">
                        <i class="las la-map-marked-alt"></i>
                    </div>
                    <div class="contact-item__content">
                        <h5 class="mb-2">@lang('Office Address')</h5>
                        <p>{{__(@$contact->data_values->contact_details)}}</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="contact-item">
                    <div class="contact-item__icon">
                        <i class="las la-phone-volume"></i>
                    </div>
                    <div class="contact-item__content">
                        <h5 class="mb-2">@lang('Phone Number')</h5>
                        <p><a href="tel:{{__(@$contact->data_values->contact_number)}}">{{__(@$contact->data_values->contact_number)}}</a><br/>&nbsp; </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="contact-item">
                    <div class="contact-item__icon">
                        <i class="las la-envelope"></i>
                    </div>
                    <div class="contact-item__content">
                        <h5 class="mb-2">@lang('Email Address')</h5>
                        <p><a href="mailto:{{__(@$contact->data_values->email_address)}}">{{__(@$contact->data_values->email_address)}}</a><br/>&nbsp; </p>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
