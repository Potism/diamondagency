@extends('admin.layouts.app')
@push('style-lib')
<style>
.mb-30
{
  margin-bottom: 10px !important;
}
.p_mobile
{
    word-wrap: break-word !important;
    text-align:right !important;
}
@media screen and (max-width:789px)
{
    body,html
    {
        overflow-x:hidden;
        position:relative;
    }
}
</style>
@endpush
@section('panel')
<div class="row">
  <div class="col-md-12">
    <a href="#" onclick="window.history.go(-1); return false;" style="cursor: pointer;float:right;margin-bottom:10px;" class="btn btn-sm btn--primary box--shadow1 text--small" ><i class="las la-paper-plane" style="transform: rotate(180deg)"></i>@lang('Go Back')</a>
</div>
    <div class="col-lg-12">
        <div class="card b-radius--10 ">
            <div class="card-body p-0">
                <div class="table-responsive--sm table-responsive">
                    <table class="table table--light style--two">
                        <thead>
                            <tr>
                                <th>@lang('Employer')</th>
                                <th>@lang('Transaction ID')</th>
                                <th>@lang('Transaction Date')</th>
                                <th>@lang('Amount')</th>
                                <th>@lang('Post Balance')</th>
                                <th>@lang('Detail')</th>
                                <th>@lang('Comments')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $trx)
                            <tr>
                                <td data-label="@lang('Employer')">
                                    <span class="font-weight-bold">{{ $trx->user->company_name }}</span>
                                    <br>
                                    <span class="small"> <a href="{{ route('admin.employers.detail', $trx->employer_id) }}"><span>@</span>{{ $trx->user->username }}</a> </span>
                                </td>

                                <td data-label="@lang('Trx')">
                                    <strong>{{ $trx->trx }}</strong>
                                </td>

                                <td data-label="@lang('Transacted')">
                                    {{ showDateTime($trx->created_at) }}<br>{{ diffForHumans($trx->created_at) }}
                                </td>

                                <td data-label="@lang('Amount')" class="budget">
                                    <span class="font-weight-bold @if($trx->trx_type == '+')text-success @else text-danger @endif">
                                        {{ $trx->trx_type }} {{showAmount($trx->amount)}} {{ $general->cur_text }}
                                    </span>
                                </td>

                                <td data-label="@lang('Post Balance')" class="budget">
                                   {{ showAmount($trx->post_balance) }} {{ __($general->cur_text) }}
                               </td>


                               <td data-label="@lang('Detail')"><p class="p_mobile" style="text-align:left;">{!!html_entity_decode($trx->details)!!}</p></td>
                               <td data-label="@lang('Comments')"><p class="p_mobile" style="text-align:left;">{!!html_entity_decode($trx->comments)!!}</p></td>
                           </tr>
                           @empty
                           <tr>
                            <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                        </tr>
                        @endforelse

                    </tbody>
                </table><!-- table end -->
            </div>
        </div>
        <div class="card-footer py-4">
            {{ paginateLinks($transactions) }}
        </div>
    </div><!-- card end -->
</div>
</div>

@endsection


@push('breadcrumb-plugins')
@if(request()->routeIs('admin.users.transactions'))
<form action="" method="GET" class="form-inline float-sm-right bg--white">
    <div class="input-group has_append">
        <input type="text" name="search" class="form-control" placeholder="@lang('TRX / Username')" value="{{ $search ?? '' }}">
        <div class="input-group-append">
            <button class="btn btn--primary" type="submit"><i class="fa fa-search"></i></button>
        </div>
    </div>
</form>
@else
<form action="{{ route('admin.report.transaction.search') }}" method="GET" class="form-inline float-sm-right bg--white">
    <div class="input-group has_append">
        <input type="text" name="search" class="form-control" placeholder="@lang('TRX / Username')" value="{{ $search ?? '' }}">
        <div class="input-group-append">
            <button class="btn btn--primary" type="submit"><i class="fa fa-search"></i></button>
        </div>
    </div>
</form>
@endif
@endpush
