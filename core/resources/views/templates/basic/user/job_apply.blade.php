@extends($activeTemplate.'layouts.frontend')
@section('content')
<div class="pt-50 pb-50 section--bg">
    <div class="container">
        <div class="row justify-content-center gy-4">
            <!-- @include($activeTemplate . 'partials.user_sidebar') -->
            <div class="col-xl-12 ps-xl-4">
                <div class="custom--card mt-4">
                       <div class="card-header bg--dark d-flex justify-content-between align-items-center">
                            <h5 class="text-white me-3">@lang('Job Applications')</h5>
                        </div>
                    <div class="card-body">
                        <div class="table-responsive--md">
                            <table class="table custom--table ">
                                <thead>
                                <tr>
                                    <th>@lang('Job Title')</th>
                                    <th>@lang('Company Name')</th>
                                    <th>@lang('Deadline')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @forelse($jobApplys as $jobApply)
                                        <tr>
                                            <td data-label="@lang('Job Title')" style="text-transform:capitalize">{{__($jobApply->job->title)}}<br>JobRef: #{{__($jobApply->job->id)}}</td>
                                            <td data-label="@lang('Company Name')">{{__($jobApply->job->employer->company_name)}}</td>
                                            <td data-label="@lang('Deadline')">{{showDateTime($jobApply->job->deadline, 'd M Y')}}</td>
                                            <td data-label="@lang('Status')">
                                                @php
                                                $job_applied_emp = App\Models\JobApply::where('job_id', $jobApply->job->id)->where('status','=', 1)->where('accept_by_user','=', 1)->get();
                                                @endphp
                                                @if($jobApply->job->vacancy == sizeof($job_applied_emp) && $jobApply->status != 1)
                                                  <span class="badge badge--warning">@lang('Job Is Filled')</span>
                                                @elseif($jobApply->status == 0)
                                                    <span class="badge badge--primary">@lang('Pending')</span>
                                                @elseif($jobApply->status == 1)
                                                    <span class="badge badge--success">@lang('Received')</span>
                                                @elseif($jobApply->status == 2)
                                                    <span class="badge badge--warning">@lang('Rejected')</span>
                                                @endif
                                            </td>
                                            <td data-label="@lang('Action')">
                                                @if($jobApply->status == 1 && $jobApply->accept_by_user == 0)
                                                <a href="javascript:void(0)" data-bs-toggle="modal" data-job_id="{{$jobApply->job_id}}" data-id="{{$jobApply->id}}" data-type="{{__($jobApply->job_type)}}" data-bs-target="#userjobApprovalModel" class="icon-btn bg--primary approved" data-toggle="tooltip" title="Accept"><i class="las la-check text-white"></i></a>
                                    <!--hide by mark -->            <a href="javascript:void(0)" data-bs-toggle="modal" data-job_id="{{$jobApply->job_id}}" data-id="{{$jobApply->id}}" data-type="{{__($jobApply->job_type)}}" data-bs-target="#userjobCancelModel" class="icon-btn bg--danger cancel" data-toggle="tooltip" title="Reject"><i class="las la-times text-white"></i></a>
                                                @elseif($jobApply->status == 1 && $jobApply->accept_by_user == 1)
                                                <span class="badge badge--success" >@lang('accepted')</span>
                                                @elseif($jobApply->status == 1 && $jobApply->accept_by_user == 2)
                                                <span class="badge badge--warning" >@lang('Rejected')</span>
                                                @endif
                                                <a href="{{route('job.detail', $jobApply->job_id)}}" class="icon-btn bg--primary" data-toggle="tooltip" title="View Job Detail" ><i class="las la-desktop"></i></a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="100%" class="text-center">{{ __($emptyMessage) }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            {{$jobApplys->links()}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade custom--modal" id="userjobApprovalModel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
          <form method="POST" id="userapprovalform" action="{{route('user.job.approved')}}">
              @csrf
              <input type="hidden" name="id">
              <input type="hidden" name="type">
              <input type="hidden" name="job_id">
              <div class="modal-body" style="padding:10% 10% 0 10%; border-radius:1.3em;">
                <div class="row">
                  <center><h2> New Job is Waiting!<br/></h2>
                    <p style="padding-top:10px;color:gray;font-size:16px;line-height:2;">Your efforts are always appreciated.<br/>This is your chance to shine!<br/><br/>Are you sure you can commit to this work?<br/> </p>
                  </center>
                </div>
              </div>
              <div class="modal-footer" style="width:100%;border:none;justify-content:center; padding:5% 5% 5% 5%;" >
                <button type="button" onclick="im_sure()" class="btn btn--base btn-block approved_btn">@lang("Yes")</button>
                <button type="button" class="btn btn--base btn-block" data-bs-dismiss="modal">@lang("Not Sure")</button>
              </div>
          </form>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade custom--modal" id="userjobCancelModel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{route('user.job.cancel')}}">
                @csrf
                <input type="hidden" name="id">
                <input type="hidden" name="job_id">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">@lang('Reject This Job')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @lang('Are you sure you want to reject this Job?')
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--secondary btn-sm" data-bs-dismiss="modal">@lang('Close')</button>
                    <button type="submit" class="btn btn--primary btn-sm">@lang('Confirm')</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
$(document).ready(function(){
    $('.approved').on('click', function(){
        var modal = $('#userjobApprovalModel');
        modal.find('input[name=id]').val($(this).data('id'));
        modal.find('input[name=job_id]').val($(this).data('job_id'));
        modal.show();

    });

    $('.cancel').on('click', function(){
        var modal = $('#userjobCancelModel');
        modal.find('input[name=id]').val($(this).data('id'));
        modal.find('input[name=job_id]').val($(this).data('job_id'));
        modal.show();
    });
    const date = new Date();
date.setDate(date.getDate() + 1);
$('.date').html(date.toDateString());
});
</script>
@endsection