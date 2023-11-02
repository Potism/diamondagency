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
                            <div class="col-lg-4 col-md-6">
                                <img src="{{ $data->gatewayCurrency()->methodImage() }}" alt="@lang('Image')" class="w-100" />
                            </div>
                            <div class="col-lg-8 col-md-6 mt-md-0 mt-4">
                                <ul class="caption-list">
                                    <li>
                                        <span class="caption">@lang('Amount')</span>
                                        <span class="value text-end"><strong>{{showAmount($data->amount)}} </strong> {{__($general->cur_text)}}</span>
                                    </li>
                                    <li>
                                        <span class="caption">@lang('Charge')</span>
                                        <span class="value text-end"><strong>{{showAmount($data->charge)}}</strong> {{__($general->cur_text)}}</span>
                                    </li>
                                    <li>
                                        <span class="caption">@lang('Payable')</span>
                                        <span class="value text-end"><strong> {{showAmount($data->amount + $data->charge)}}</strong> {{__($general->cur_text)}}</span>
                                    </li>
                                    <li>
                                        <span class="caption">@lang('Conversion Rate')</span>
                                        <span class="value text-end"><strong>1 {{__($general->cur_text)}} = {{showAmount($data->rate)}}  {{__($data->baseCurrency())}}</strong></span>
                                    </li>
                                    <li>
                                        <span class="caption">@lang('In')</span>
                                        <span class="value text-end">{{$data->baseCurrency()}}:
                                        <strong>{{showAmount($data->final_amo)}}</strong></span>
                                    </li>

                                    @if($data->gateway->crypto==1)
                                        <li>
                                            <span class="caption">@lang('Conversion with')</span>
                                            <span class="value text-end"><b> {{ __($data->method_currency) }}</b> @lang('and final value will Show on next step')</span>
                                        </li>
                                    @endif
                                </ul>
                                <div class="text-center"> 
                                    @if( 1000 >$data->method_code)
                                        <a href="{{route('employer.deposit.confirm')}}" class="btn btn--success mt-4 font-weight-bold text-center w-100">@lang('Pay Now')</a>
                                    @else
                                        <a href="{{route('employer.deposit.manual.confirm')}}" class="btn btn--success mt-4 font-weight-bold w-100">@lang('Pay Now')</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

