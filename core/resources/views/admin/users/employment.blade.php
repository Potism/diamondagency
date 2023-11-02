@extends('admin.layouts.app')
@section('panel')
<div class="row">
    <div class="col-lg-12">
        <div class="card b-radius--10 ">
            <div class="card-body p-0">
                <div class="table-responsive--md  table-responsive">
                    <table class="table table--light style--two">
                        <thead>
                        <tr>
                            <th>@lang('Company Name')</th>
                            <th>@lang('Department')</th>
                            <th>@lang('Designation')</th>
                            <th>@lang('Employment Period')</th>
                            <th>@lang('Responsibilities')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($employmentHistorys as $employment)
                        <tr>
                            <td data-label="@lang('Company Name')">{{__($employment->company_name)}}</td>
                            <td data-label="@lang('Department')">{{__($employment->department)}}</td>
                            <td data-label="@lang('Designation')">{{__($employment->designation)}}</td>
                            <td data-label="@lang('Employment Period')">{{showDateTime($employment->start_date, 'd M Y')}} -
                                @if($employment->currently_work == 1)
                                    @lang('Present')
                                @else
                                    {{showDateTime($employment->end_date, 'd M Y')}}
                                @endif
                            </td>
                            <td data-label="@lang('Responsibilities')">
                                <a href="javascript:void(0)" data-responsibilitie="{{$employment->responsibilities}}" class="icon-btn bg--primary responsibilitie"><i class="las la-desktop"></i></a>
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="responsibilities" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="" lass="modal-title" id="exampleModalLabel">@lang('Responsibilitie')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <div class="modal-body">
                <p id="res"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn--danger" data-dismiss="modal">@lang('Close')</button>
                <button type="submit" class="btn btn--success">@lang('Confirm')</button>
            </div>
        </div>
    </div>
</div>
@endsection
@push('breadcrumb-plugins')
    <a href="#" onclick="window.history.go(-1); return false;" style="cursor: pointer;" class="btn btn-sm btn--primary box--shadow1 text--small" ><i class="las la-paper-plane" style="transform: rotate(180deg)"></i>@lang('Go Back')</a>
@endpush

@push('script')
    <script>
        "use strict";
        $('.responsibilitie').on('click', function () {
            var modal = $('#responsibilities');
            modal.find('#res').text($(this).data('responsibilitie'))
            modal.modal('show');
        });
    </script>
@endpush
