@extends($activeTemplate.'layouts.frontend')
@section('content')
    <div class="pt-50 pb-50 section--bg">
        <div class="container">
            <div class="row justify-content-center gy-4">
                
                <div class="col-xl-12 ps-xl-4">
                    <div class="custom--card mt-4">
                        <div class="card-body px-4">
                            <div class="table-responsive--md">
                                <table class="table custom--table">
                                    <thead>
                                        <tr>
                                            <th>@lang('Invoice No')</th>
                                            <th>@lang('Job Title')</th>
                                            <th>@lang('Invoice Amount')</th>
                                            <th>@lang('Candidate')</th>
                                            <th>@lang('Invoice Download')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($appliedJobs as $appliedJob)
                                            <tr>
                                                <td data-label="@lang('Invoice No')">
                                                    {{__($appliedJob->prefix)}}-00{{__($appliedJob->id)}}
                                                </td>
                                                <td data-label="@lang('Title')">
                                                    {{__($appliedJob->job->title)}}
                                                </td>
                                                <td data-label="@lang('Invoice Amount')">
                                                    {{$general->cur_sym}} {{getAmount($appliedJob->invoice_amount)}}
                                                </td>
                                                <td data-label="@lang('Candidate')">
                                                    {{__($appliedJob->user->firstname)}} {{__($appliedJob->user->lastname)}}
                                                </td>
                                                <td data-label="@lang('Invoice Download')">
                                                    <a href="{{route('download.invoice',$appliedJob->prefix.'-00'.$appliedJob->id)}}" class="icon-btn bg--primary" data-toggle="tooltip" title="Download Invoice" ><i class="las la-arrow-down"></i></a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="100%" class="text-center">{{ __($emptyMessage) }}</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                {{$appliedJobs->links()}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection