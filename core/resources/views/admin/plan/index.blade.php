@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Name')</th>
                                    <th>@lang('Amount')</th>
                                    <th>@lang('Duration')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse($plans as $plan)
                                <tr>
                                    <td data-label="@lang('Name')">{{__($plan->name)}}</td>
                                    <td data-label="@lang('Amount')">
                                        <span class="font-weight-bold">{{getAmount($plan->amount)}} {{$general->cur_text}}</span>
                                    </td>

                                    <td data-label="@lang('Duration')">
                                        {{__($plan->duration)}} @lang('Month')
                                    </td>

                                    <td data-label="@lang('Status')">
                                        @if($plan->status == 1)
                                            <span class="badge badge--success">@lang('Enable')</span>
                                        @else
                                            <span class="badge badge--danger">@lang('Disable')</span>
                                        @endif
                                    </td>
                                    <td data-label="@lang('Action')">
                                        <a href="{{route('admin.plan.edit', $plan->id)}}" class="icon-btn btn--primary ml-1"><i class="las la-pen"></i></a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-muted text-center" colspan="100%">{{__($emptyMessage) }}</td>
                                </tr>
                            @endforelse

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer py-4">
                    {{paginateLinks($plans)}}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <a href="{{route('admin.plan.create')}}" class="btn btn-sm btn--primary box--shadow1 text--small addCity" ><i class="fa fa-fw fa-paper-plane"></i>@lang('Add New Plan')</a>
@endpush
