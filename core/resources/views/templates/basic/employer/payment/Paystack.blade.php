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
                            <div class="col-md-8">
                                <form action="{{ route('ipn.'.$deposit->gateway->alias) }}" method="POST" class="text-center">
                                    @csrf
                                    <h4>@lang('Please Pay') {{showAmount($deposit->final_amo)}} {{__($deposit->method_currency)}}</h4>
                                    <h4 class="my-3">@lang('To Get') {{showAmount($deposit->amount)}}  {{__($general->cur_text)}}</h4>
                                    <button type="button" class=" mt-4 btn--success btn-round  text-center btn-lg" id="btn-confirm">@lang('Pay Now')</button>
                                    <script
                                        src="//js.paystack.co/v1/inline.js"
                                        data-key="{{ $data->key }}"
                                        data-email="{{ $data->email }}"
                                        data-amount="{{$data->amount}}"
                                        data-currency="{{$data->currency}}"
                                        data-ref="{{ $data->ref }}"
                                        data-custom-button="btn-confirm"
                                    >
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
