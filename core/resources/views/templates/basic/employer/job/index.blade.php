@extends($activeTemplate.'layouts.frontend')
@section('content')
    <div class="pt-50 pb-50 section--bg">
        <div class="container">
            <div class="row justify-content-center gy-4">
                <!-- @include($activeTemplate . 'partials.employer_sidebar') -->
                <div class="col-xl-12 ps-xl-4">
                    <div class="custom--card mt-4">
                        <div class="card-header bg--dark d-flex justify-content-between align-items-center">
                            <h5 class="text-white me-3">@lang('Jobs')</h5>
                        </div>
                        <div class="card-body px-4">
                            <div class="table-responsive--md">
                                <table class="table custom--table">
                                    <thead>
                                        <tr>
                                            <th>@lang('Title - Shift')</th>
                                            <th>@lang('Category - Type')</th>
                                            <th>@lang('Applied Candidate')</th>
                                            <th>@lang('Status')</th>
                                            <th>@lang('Action')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($jobs as $job)
                                            <tr>
                                                <td data-label="@lang('Title - Shift')">
                                                    <span style="text-transform:capitalize"><strong>{{__($job->title)}}</strong></span>
                                                    <br>
                                                    <span>{{__($job->shift->name)}}</span>
                                                    <br>
                                                    <span>JobRef: #{{__($job->id)}}</span>
                                                </td>

                                                <td data-label="@lang('Category - Type')">
                                                    <span>{{$job->category->name}}</span>
                                                    <br>
                                                    @if($job->job_cat_rate == "temp_rate")<span>Temp Job</span>@else<span>Permanent Job</span>@endif
                                                </td>

                                                <td data-label="@lang('Applied Candidate')">
                                                    <a href="{{route('employer.applied.job', $job->id)}}" class="btn fs--12px px-2 py-1 btn--primary"  data-toggle="tooltip" title="View Applicants">@lang('view all') ({{$job->jobApplication->count()}})</a>
                                                </td>

                                                <td data-label="@lang('Status')">
                                                    @if($job->status == 0)
                                                        <span class="badge badge--primary">@lang('Pending')</span>
                                                    @elseif($job->status == 1 && $job->deadline > Carbon\Carbon::now()->toDateString())
                                                        <span class="badge badge--success">@lang('Approved')</span>
                                                    @elseif($job->status == 2)
                                                        <span class="badge badge--danger">@lang('Cancel')</span>
                                                    @elseif(Carbon\Carbon::now()->toDateString() > $job->deadline)
                                                        <span class="badge badge--warning">@lang('Expired')</span>
                                                    @endif
                                                </td>

                                                <td>
                                                    <a href="{{route('employer.job.edit', $job->id)}}" class="icon-btn bg--primary" data-toggle="tooltip" title="View Job Detail"><i class="las fa-pencil-alt text-white"></i></a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="100%" class="text-center">{{ __($emptyMessage) }}</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                {{$jobs->links()}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
