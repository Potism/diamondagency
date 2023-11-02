@extends($activeTemplate.'layouts.frontend')
@section('content')
@push('style')
<style>
@media (max-width: 991px)
{
  .p_mobile
  {
    padding:5% 2% !important;
  }
}
</style>
@endpush
    <div class="pt-50 pb-50 section--bg">
        <div class="container">
            <div class="row justify-content-center gy-4">
                <!-- @include($activeTemplate . 'partials.employer_sidebar') -->
                @if($employer->balance < 200)
                  <div class="text-end">
                      <a href="{{route('employer.deposit')}}" class="btn btn--primary btn-sm"><i class="las la-wallet"></i> @lang('Deposit Money')</a>
                  </div>
                @endif
                <div class="col-xl-12 ps-xl-4">
                    <div class="custom--card mt-4">
                        <div class="card-header bg--dark d-flex justify-content-between align-items-center">
                            <h5 class="text-white me-3">@lang('Transaction History')</h5>
                        </div>                        
                        <div class="card-body px-4">
                            <div class="table-responsive--md">
                                <table class="table custom--table">
                                    <thead>
                                        <tr>
                                            <th>@lang('Date')</th>
                                            <th>@lang('TXN')</th>
                                            <th>@lang('Amount')</th>
                                            <th>@lang('Post Balance')</th>
                                            <th>@lang('Detail')</th>
                                            <th>@lang('Comments')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($transactions as $transaction)
                                            <tr>
                                                <td data-label="@lang('Date')">
                                                    {{showDateTime($transaction->created_at)}}
                                                    <br>
                                                    {{diffforhumans($transaction->created_at)}}

                                                </td>
                                                <td data-label="@lang('TRX')">{{$transaction->trx}}
                                                </td>
                                                <td data-label="@lang('Amount')">
                                                    <strong
                                                        @if($transaction->trx_type == '+') class="text--success" @else class="text--danger" @endif> 
                                                        {{($transaction->trx_type == '+') ? '+':'-'}} 
                                                        {{getAmount($transaction->amount)}} {{$general->cur_text}}
                                                    </strong>
                                                </td>
                                                <td data-label="@lang('Post Balance')">{{getAmount($transaction->post_balance)}} {{$general->cur_text}}</td>
                                                <td data-label="@lang('Detail')"> <p class="p_mobile" style="text-align:left;">{!!html_entity_decode($transaction->details)!!}</p></td>
                                                <td data-label="@lang('Comments')">
                                                    <p class="p_mobile" style="text-align:left;">
                                                        @if($transaction->comments != '')
                                                        {!!html_entity_decode($transaction->comments)!!}
                                                        @else
                                                        @endif
                                                    </p>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="100%" class="text-center">{{ __($emptyMessage) }}</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                {{$transactions->links()}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection