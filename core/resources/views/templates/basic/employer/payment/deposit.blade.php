@extends($activeTemplate.'layouts.frontend')
@section('content')
<div class="pt-50 pb-50 section--bg">
    <div class="container">
        <div class="row justify-content-center gy-4">
            <!-- @include($activeTemplate . 'partials.employer_sidebar') -->
            <div class="col-xl-12 ps-xl-4">
                <div class="row gy-4">
                    @foreach($gatewayCurrency as $data)
                        <div class="col-xxl-3 col-xl-4 col-lg-3 col-md-4 col-sm-6">
                            <div class="card custom--card h-100">
                                <div class="card-body text-center">
                                    <h6 class="text-center mb-3">{{__($data->name)}} </h6>
                                    <img src="{{$data->methodImage()}}" class="card-img-top" alt="{{__($data->name)}}" class="w-100">
                                    <a href="javascript:void(0)" data-id="{{$data->id}}"
                                       data-name="{{$data->name}}"
                                       data-currency="{{$data->currency}}"
                                       data-method_code="{{$data->method_code}}"
                                       data-min_amount="{{showAmount($data->min_amount)}}"
                                       data-max_amount="{{showAmount($data->max_amount)}}"
                                       data-base_symbol="{{$data->baseSymbol()}}"
                                       data-fix_charge="{{showAmount($data->fixed_charge)}}"
                                       data-percent_charge="{{showAmount($data->percent_charge)}}" 
                                       class=" btn btn--success btn-sm w-100 mt-3 deposit" 
                                       data-bs-toggle="modal" 
                                       data-bs-target="#depositModal">
                                        @lang('Deposit Now')</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade custom--modal" id="depositModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title method-name" id="depositModalLabel"></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('employer.deposit.insert')}}" method="post">
                @csrf
                <div class="modal-body">
                    <p class="text-danger depositLimit"></p>
                    <p class="text-danger depositCharge"></p>
                    <div class="form-group">
                        <input type="hidden" name="currency" class="edit-currency">
                        <input type="hidden" name="method_code" class="edit-method-code">
                    </div>
                    <div class="form-group">
                        <label>@lang('Enter Amount'):</label>
                        <div class="input-group">
                            <input id="amount" type="text" class="form--control" name="amount" placeholder="@lang('Amount')" required  value="{{old('amount')}}">
                            <span class="input-group-text">{{__($general->cur_text)}}</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-md btn--secondary" data-bs-dismiss="modal">@lang('Close')</button>
                    <button type="submit" class="btn btn-md btn--primary">@lang('Confirm')</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection



@push('script')
    <script>
        (function ($) {
            "use strict";
            $('.deposit').on('click', function () {
                var name = $(this).data('name');
                var currency = $(this).data('currency');
                var method_code = $(this).data('method_code');
                var minAmount = $(this).data('min_amount');
                var maxAmount = $(this).data('max_amount');
                var baseSymbol = "{{$general->cur_text}}";
                var fixCharge = $(this).data('fix_charge');
                var percentCharge = $(this).data('percent_charge');

                var depositLimit = `@lang('Deposit Limit'): ${minAmount} - ${maxAmount}  ${baseSymbol}`;
                $('.depositLimit').text(depositLimit);
                var depositCharge = `@lang('Charge'): ${fixCharge} ${baseSymbol}  ${(0 < percentCharge) ? ' + ' +percentCharge + ' % ' : ''}`;
                $('.depositCharge').text(depositCharge);
                $('.method-name').text(`@lang('Payment By ') ${name}`);
                $('.currency-addon').text(baseSymbol);
                $('.edit-currency').val(currency);
                $('.edit-method-code').val(method_code);
            });
        })(jQuery);
    </script>
@endpush
