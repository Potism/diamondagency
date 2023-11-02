@extends($activeTemplate.'layouts.frontend')
@section('content')
    <div class="pt-50 pb-50 section--bg">
        <div class="container">
            <div class="row justify-content-center gy-4">
                <!-- @include($activeTemplate . 'partials.employer_sidebar') -->
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
                                                    {{$general->cur_sym}} {{getAmount($appliedJob->invoice_amt_with_tax)}}
                                                </td>
                                                <td data-label="@lang('Candidate')">
                                                    {{__($appliedJob->user->firstname)}} {{__($appliedJob->user->lastname)}}
                                                </td>
                                                <td data-label="@lang('Invoice Download')" >
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


<!-- Modal -->
<div class="modal fade custom--modal" id="jobApprovalModel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{route('employer.job.temp_approved')}}">
                @csrf
                <input type="hidden" name="id">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">@lang('Received Confirmation')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @lang('Are you sure you want to received this application ?')
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--secondary btn-sm" data-bs-dismiss="modal">@lang('Close')</button>
                    <button type="submit" class="btn btn--primary btn-sm">@lang('Confirm')</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade custom--modal" id="jobCancelModel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{route('employer.job.temp_cancel')}}">
                @csrf
                <input type="hidden" name="id">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">@lang('Rejected Confirmation')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @lang('Are you sure you want to rejected this application ?')
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--secondary btn-sm" data-bs-dismiss="modal">@lang('Close')</button>
                    <button type="submit" class="btn btn--primary btn-sm">@lang('Confirm')</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    $('.approved').on('click', function(){
        var modal = $('#jobApprovalModel');
        modal.find('input[name=id]').val($(this).data('id'));
        modal.show();
    });

    $('.cancel').on('click', function(){
        var modal = $('#jobCancelModel');
        modal.find('input[name=id]').val($(this).data('id'));
        modal.show();
    });
</script>
@endpush
