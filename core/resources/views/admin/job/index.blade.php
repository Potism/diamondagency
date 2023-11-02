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
                                <th>@lang('Posting Title')</th>
                                <th>@lang('Category - Type - Shift')</th>
                                <th>@lang('City - Location')</th>
                                <th>@lang('Vacancy - Deadline')</th>
                                <th>@lang('Total application')</th>
                                <th>@lang('Featured Job')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($jobs as $job)
                            <tr @if($job->featured == 1) class="table-light" @endif>
                                <td data-label="@lang('Title')"><strong>
                                    {{__($job->title)}}<br>
                                    <span class="small">
                                        <a href="{{ route('admin.employers.detail', $job->employer_id) }}"><span>@</span>{{ $job->employer->username }}</a>
                                    </span></strong>
                                </td>
                                <td data-label="@lang('Category - Type - Shift')">
                                    <span>{{__($job->category->name)}}</span><br>
                                    <span>@if($job->job_cat_rate == "temp_rate")Temp Job @else Permanent Job @endif <br/> {{__($job->shift->name)}}</span>
                                </td>
                                <td data-label="@lang('City - Location')">
                                    <span>{{__($job->location->name)}}</span><br>
                                    <span>{{__($job->city->name)}}</span>
                                </td>
                                <td data-label="@lang('Vacancy - Deadline')">
                                    <span>{{__($job->vacancy)}}</span><br>
                                    <span>{{showDateTime($job->deadline, 'd M Y')}}</span>
                                </td>
                                <td data-label="Total application">
                                    <a href="{{route('admin.manage.job.applied', $job->id)}}" class="icon-btn btn--info">@lang('view all') ({{$job->jobApplication->count()}})</a>
                                </td>

                               <td data-label="@lang('Featured Job')">
                                    @if($job->featured == 1)
                                        <span class="badge badge--success">@lang('Included')</span>
                                        <a href="javascript:void(0)" class="icon-btn btn--info ml-2 notInclude" data-toggle="tooltip" title="" data-original-title="@lang('Not Include')" data-id="{{$job->id }}">
                                            <i class="las la-arrow-alt-circle-left"></i>
                                        </a>
                                    @else
                                        <span class="badge badge--warning">@lang('Not included')</span>
                                        <a href="javascript:void(0)" class="icon-btn btn--success ml-2 include text-white" data-toggle="tooltip" title="" data-original-title="@lang('Include')" data-id="{{ $job->id }}">
                                            <i class="las la-arrow-alt-circle-right"></i>
                                        </a>
                                    @endif
                                </td>

                                <td data-label="@lang('Status')">
                                    @if($job->status == 0)
                                        <span class="badge badge--primary">@lang('Pending')</span>
                                    @elseif($job->status == 1 && $job->deadline > Carbon\Carbon::now())
                                        <span class="badge badge--success">@lang('Approved')</span>
                                    @elseif($job->status == 2)
                                        <span class="badge badge--danger">@lang('Cancel')</span>
                                    @elseif(Carbon\Carbon::now() > $job->deadline)
                                        <span class="badge badge--warning">@lang('Expired')</span>
                                    @endif
                                </td>
                                <td data-label="@lang('Action')">
                                    @if($job->status == 0)
                                        <a href="javascript:void(0)" data-id="{{$job->id}}" class="icon-btn btn--success ml-1 approved"><i class="las la-check"></i></a>
                                        <a href="javascript:void(0)" data-id="{{$job->id}}" class="icon-btn btn--danger ml-1 cancel"><i class="las la-times"></i></a>
                                    @endif
                                    <a href="{{route('admin.manage.job.detail', $job->id)}}" class="icon-btn btn--primary ml-1"><i class="las la-desktop"></i></a>
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
                {{paginateLinks($jobs)}}
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="approvedby" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="" lass="modal-title" id="exampleModalLabel">@lang('Approval Confirmation')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>

            <form action="{{route('admin.manage.job.approve')}}" method="POST">
                @csrf
                @method('POST')
                <input type="hidden" name="id">
                <div class="modal-body">
                    <p>@lang('Are you sure to approved this job?')</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--secondary" data-dismiss="modal">@lang('Close')</button>
                    <button type="submit" class="btn btn--success">@lang('Confirm')</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="cancelBy" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="" lass="modal-title" id="exampleModalLabel">@lang('Cancel Confirmation')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>

            <form action="{{route('admin.manage.job.cancelBy')}}" method="POST">
                @csrf
                @method('POST')
                <input type="hidden" name="id">
                <div class="modal-body">
                    <p>@lang('Are you sure to cancel this job?')</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--secondary" data-dismiss="modal">@lang('Close')</button>
                    <button type="submit" class="btn btn--success">@lang('Confirm')</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="includeFeatured" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="" lass="modal-title" id="exampleModalLabel">@lang('Featured Item Include')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <form action="{{ route('admin.manage.job.featured.include') }}" method="POST">
                @csrf
                @method('POST')
                <input type="hidden" name="id">
                <div class="modal-body">
                    <p>@lang('Are you sure include this job featured list?')</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--danger" data-dismiss="modal">@lang('Close')</button>
                    <button type="submit" class="btn btn--success">@lang('Confirm')</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="NotincludeFeatured" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="" lass="modal-title" id="exampleModalLabel">@lang('Featured Item Remove')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <form action="{{ route('admin.manage.job.featured.remove') }}" method="POST">
                @csrf
                @method('POST')
                <input type="hidden" name="id">
                <div class="modal-body">
                    <p>@lang('Are you sure remove this job featured list?')</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--danger" data-dismiss="modal">@lang('Close')</button>
                    <button type="submit" class="btn btn--success">@lang('Confirm')</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('breadcrumb-plugins')
<a href="#" onclick="window.history.go(-1); return false;" style="cursor: pointer;float:right;margin-bottom:10px;" class="btn btn-sm btn--primary box--shadow1 text--small" ><i class="las la-paper-plane" style="transform: rotate(180deg)"></i>@lang('Go Back')</a>

@endpush



@push('script')
<script>
    "use strict";
    $('.approved').on('click', function () {
        var modal = $('#approvedby');
        modal.find('input[name=id]').val($(this).data('id'))
        modal.modal('show');
    });

    $('.cancel').on('click', function () {
        var modal = $('#cancelBy');
        modal.find('input[name=id]').val($(this).data('id'))
        modal.modal('show');
    });

    $('.include').on('click', function () {
        var modal = $('#includeFeatured');
        modal.find('input[name=id]').val($(this).data('id'))
        modal.modal('show');
    });

    $('.notInclude').on('click', function () {
        var modal = $('#NotincludeFeatured');
        modal.find('input[name=id]').val($(this).data('id'))
        modal.modal('show');
    });
</script>
@endpush
