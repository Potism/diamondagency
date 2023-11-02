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
                                            <th>@lang('Candidate')</th>
                                            <th>@lang('Offered Price')</th>
                                            <th>@lang('CV Download')</th>
                                            <th>@lang('Status')</th>
                                            <th>@lang('Action')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($appliedJobs as $appliedJob)
                                            <tr>
                                                <td data-label="@lang('Candidate')">
                                                    {{__($appliedJob->user->username)}}
                                                </td>

                                                <td data-label="@lang('Amount')">
                                                    {{round($appliedJob->job_amt, 2)}}
                                                </td>
                                                <td data-label="@lang('Cv Download')">
                                                    <a href="{{route('employer.cv.download', encrypt($appliedJob->id))}}" class="icon-btn bg--primary"><i class="las la-arrow-down"></i></a>
                                                </td>

                                                <td data-label="@lang('Status')">
                                                    @if($appliedJob->status == 0)
                                                        <span class="badge badge--primary">@lang('Pending')</span>
                                                    @elseif($appliedJob->status == 1)
                                                        <span class="badge badge--success">@lang('Received')</span>
                                                    @elseif($appliedJob->status == 2)
                                                        <span class="badge badge--danger">@lang('Rejected')</span>
                                                    @endif
                                                </td>

                                                <td>
                                                    @if($appliedJob->status == 0)
                                                        <a href="javascript:void(0)" data-bs-toggle="modal" data-id="{{$appliedJob->id}}" data-bs-target="#jobApprovalModel" class="icon-btn bg--primary approved"><i class="las la-check text-white"></i></a>
                                                        <a href="javascript:void(0)" data-bs-toggle="modal" data-id="{{$appliedJob->id}}" data-bs-target="#jobCancelModel" class="icon-btn bg--danger cancel"><i class="las la-times text-white"></i></a>
                                                    @else
                                                        <a href="{{route('employer.user.invoices',[$appliedJob->job_type,$appliedJob->job_id,$appliedJob->user_id])}}" class="icon-btn bg--info"><i class="las la-file-invoice text-white"></i></a>
                                                    @endif
                                                    <a href="{{route('candidate.profile', [slug($appliedJob->user->username), $appliedJob->user_id])}}" class="icon-btn bg--info"><i class="las la-desktop text-white"></i></a>
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
                    @lang('Are you sure you want to received this application?')
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
                    @lang('Are you sure you want to rejected this application?')
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
