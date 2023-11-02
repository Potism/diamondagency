@extends($activeTemplate.'layouts.frontend')
@section('content')
<div class="pt-50 pb-50 section--bg">
    <div class="container">
        <div class="row justify-content-center gy-4">
            <!-- @include($activeTemplate . 'partials.employer_sidebar') -->
            <div class="col-xl-12 ps-xl-4">
                <div class="custom--card mt-4">
                    <div class="card-body px-4">
                        <div class="row">
                            <div class="col-md-4">
                                <img src="{{$deposit->gatewayCurrency()->methodImage()}}" class="card-img-top" alt="@lang('Image')" class="w-100">
                            </div>
                            <div class="col-md-8 text-center">
                                    <h4>@lang('Please Pay') {{showAmount($deposit->final_amo)}} {{$deposit->method_currency}}</h4>
                                    <h4 class="my-3">@lang('To Get') {{showAmount($deposit->amount)}}  {{__($general->cur_text)}}</h4>
                                <form action="{{$data->url}}" method="{{$data->method}}">
                                    <input type="hidden" custom="{{$data->custom}}" name="hidden">
                                    <script src="{{$data->checkout_js}}"
                                            @foreach($data->val as $key=>$value)
                                            data-{{$key}}="{{$value}}"
                                        @endforeach >
                                    </script>
                                </form>
                            </div>
                        </div>
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
            $('input[type="submit"]').addClass("ml-4 mt-4 btn btn--success btn-lg");
        })(jQuery);
    </script>
@endpush
