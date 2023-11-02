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
                                    <th>@lang('Job Title - Primary Contact')</th>
                                    <th>@lang('Employer')</th>
                                    <th>@lang('Candidate')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse($jobApplications as $jobApplication)
                                <tr>
                                    <td data-label="@lang('Job Title - Category')">
                                        {{__($jobApplication->tempJob->title)}}<br>
                                        <span>{{__($jobApplication->tempJob->primary_contact)}}</span>
                                    </td>
                                    <td data-label="@lang('Employer')">
                                        <span class="font-weight-bold">{{$jobApplication->tempJob->employer->company_name}}</span>
                                        <br>
                                        <span class="small">
                                        <a href="{{ route('admin.employers.detail', $jobApplication->tempJob->employer_id) }}"><span>@</span>{{ $jobApplication->job->employer->username }}</a>
                                        </span>
                                    </td>
                                    <td data-label="@lang('Candidate')">
                                        <span class="font-weight-bold">{{$jobApplication->user->fullname}}</span>
                                        <br>
                                        <span class="small">
                                        <a href="{{ route('admin.users.detail', $jobApplication->user_id) }}"><span>@</span>{{ $jobApplication->user->username }}</a>
                                        </span>
                                    </td>
                                    <td data-label="@lang('Status')">
                                        @if($jobApplication->status == 0)
                                            <span class="badge badge--primary">@lang('Pending')</span>
                                        @elseif($jobApplication->status == 1)
                                            <span class="badge badge--success">@lang('Received')</span>
                                        @elseif($jobApplication->status == 2)
                                            <span class="badge badge--danger">@lang('Rejected')</span>
                                        @endif
                                    </td>
                                    <td data-label="@lang('Action')">
                                        <a href="{{route('admin.manage.tempjob.detail', $jobApplication->job_id)}}" class="icon-btn btn--primary ml-1"><i class="las la-desktop"></i></a>
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
                    {{paginateLinks($jobApplications)}}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <a href="javascript:void(0)" class="btn btn-sm btn--primary box--shadow1 text--small" ><i class="las la-paper-plane"></i>@lang('Go Back')</a>
@endpush
