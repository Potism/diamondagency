@extends($activeTemplate.'layouts.frontend')
@section('content')
<div class="preloader-holder">
    <div class="preloader"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
</div>
<div class="pt-50 pb-50 section--bg">
    <div class="container">
        <div class="row justify-content-center gy-4">
            <!-- @include($activeTemplate . 'partials.user_sidebar') -->
            <div class="col-xl-12 ps-xl-4">
                <div class="custom--card mt-4">
                        <div class="card-header bg--dark d-flex justify-content-between align-items-center">
                            <h5 class="text-white me-3">@lang('Current Job')</h5>
                        </div>
                    <div class="card-body">
                        <div class="table-responsive--md">
                            <table class="table custom--table ">
                                <thead>
                                <tr>
                                    <th>@lang('Job Title')</th>
                                    <th>@lang('Job Type')</th>
                                    <th>@lang('Company Name')</th>
                                    <th>@lang('Category')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Time In')</th>
                                    <!-- <th>@lang('Salary Period')</th> -->
                                    <th>@lang('Action')</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @forelse($jobApplys as $jobApply)
                                        <tr>
                                            <td data-label="@lang('Job Title')"> {{__($jobApply->job->title)}}</td>
                                            <td data-label="@lang('Job Type')">@if($jobApply->job->job_cat_rate == "temp_rate") @lang('Temporary') @elseif($jobApply->job->job_cat_rate == "full_timerate") @lang('Permanent') @endif</td>
                                            <td data-label="@lang('Company Name')">{{__($jobApply->job->employer->company_name)}}</td>
                                            <td data-label="@lang('Category')">{{__($jobApply->job->category->name)}}</td>
                                            <td data-label="@lang('Status')">
                                                @if($jobApply->status == 0)
                                                    <span class="badge badge--primary">@lang('Pending')</span>
                                                @elseif($jobApply->status == 1)
                                                    <span class="badge badge--success">@lang('accepted')</span>
                                                @elseif($jobApply->status == 2)
                                                    <span class="badge badge--danger">@lang('Rejected')</span>
                                                @endif
                                            </td>
                                            <td data-label="@lang('Time In')">
                                                @if($jobApply->in_time)
                                                {{__(date('Y-m-d H:i a',strtotime($jobApply->in_time)))}}
                                                @else
                                                --
                                                @endif
                                            </td>
                                            <!-- <td data-label="@lang('Period')">
                                                @if($jobApply->job->salary_period == 1)
                                                    <span class="">@lang('Monthly')</span>
                                                @elseif($jobApply->job->salary_period == 2)
                                                    <span class="">@lang('Yearly')</span>
                                                @elseif($jobApply->job->salary_period == 3)
                                                    <span class="">@lang('Hourly')</span>
                                                @endif
                                            </td> -->
                                            <td data-label="@lang('Action')" id="action_btn">
                                                @if($jobApply->status == 1 && $jobApply->accept_by_user == 0)
                                                <a href="javascript:void(0)" data-bs-toggle="modal" data-id="{{$jobApply->job_id}}" data-type="permanent_job" data-bs-target="#userjobApprovalModel" class="icon-btn bg--primary approved"><i class="las la-check text-white"></i></a>
                                                <a href="javascript:void(0)" data-bs-toggle="modal" data-id="{{$jobApply->job_id}}" data-type="permanent_job" data-bs-target="#userjobCancelModel" class="icon-btn bg--danger cancel"><i class="las la-times text-white"></i></a>
                                                @elseif($jobApply->status != 0)
                                                  @if($jobApply->employee_review == 0)
                                                          <a href="javascript:void(0)" data-bs-toggle="modal" data-id="{{$jobApply->id}}"  data-employer_id="{{$jobApply->job->employer->id}}"  data-url="{{route('submit_employee_rating')}}" class="icon-btn bg--info complete_btn" data-bs-target="#ratingReviewModel" style="width: auto;padding: 5px;">Give Rating</a>
                                                  @else
                                                  {!!get_rating($jobApply->employee_review)!!}
                                                  @endif
                                                @endif
                                                @if($jobApply->status == 1 && $jobApply->accept_by_user == 1  && $jobApply->job_status != 2)
                                                <a href="{{route('user.job.currjobreport', $jobApply->id)}}" class="icon-btn bg--primary" data-toggle="tooltip" title="Submit Time"><i class="las la-clock"></i></a>
                                                @endif
                                                <a href="{{route('job.detail', $jobApply->job_id)}}" class="icon-btn bg--primary" data-toggle="tooltip" title="View Detail"><i class="las la-desktop"></i></a>
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
<!-- Modal -->
<div class="modal fade custom--modal" id="userjobApprovalModel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" id="userapprovalform" action="{{route('user.job.approved')}}">
                @csrf
                <input type="hidden" name="id">
                <input type="hidden" name="type">
                <input type="hidden" name="job_id">
                <div class="modal-body" style="padding:10% 10% 0 10%">
                  <div class="row">
                    <center>
                      <p style="padding-top:10px;">Your efforts are always appreciated.<br/>This is your chance to shine!<br/>Are you sure you can commit to this work? </p>
                    </center>
                  </div>
                </div>
                <div class="modal-footer" style="width:100%;border:none;justify-content:center;">
                  <button type="button" onclick="im_sure()" class="btn approved_btn" style="border:none;background:none;color:#0096FF;border:1px solid lightgray"><i class="fa fa-check-square" style="color:#00A550;"></i>&nbsp;@lang("Yes")</button>
                  <button type="button" class="btn" style="border:none;background:none;color:#0096FF;border:1px solid lightgray" data-bs-dismiss="modal"><i class="fa fa-question" style="color:red"></i>&nbsp;@lang("Not Sure")</button>
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
                <input type="hidden" name="type">
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
<!-- Modal -->
<div class="modal fade custom--modal" id="ratingReviewModel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal poststars" action="" id="addStar" method="POST">
                 {{ csrf_field() }}
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">@lang('Review Employer')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                @lang('Please rate the employer')

                    <div class="form-group required">
                      <div class="col-sm-12">
                        <input type="hidden" name="id">
                        <input type="hidden" name="employer_id">
                        <input class="star star-1" value="1" id="star-1" type="radio" name="star"/>
                        <label class="star star-1" for="star-1"></label>
                        <input class="star star-2" value="2" id="star-2" type="radio" name="star"/>
                        <label class="star star-2" for="star-2"></label>
                        <input class="star star-3" value="3" id="star-3" type="radio" name="star"/>
                        <label class="star star-3" for="star-3"></label>
                        <input class="star star-4" value="4" id="star-4" type="radio" name="star"/>
                        <label class="star star-4" for="star-4"></label>
                        <input class="star star-5" value="5" id="star-5" type="radio" name="star"/>
                        <label class="star star-5" for="star-5"></label>
                       </div>
                    </div>
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
    $('.approved').on('click', function(){
        var modal = $('#userjobApprovalModel');
        modal.find('input[name=id]').val($(this).data('id'));
        modal.find('input[name=type]').val($(this).data('type'));
        modal.show();
    });

    $('.cancel').on('click', function(){
        var modal = $('#userjobCancelModel');
        modal.find('input[name=id]').val($(this).data('id'));
        modal.find('input[name=type]').val($(this).data('type'));
        modal.show();
    });
    function in_time_form(id)
    {
        $('form[name="time_form_'+id+'"]').submit();
    }
    const date = new Date();
date.setDate(date.getDate() + 1);
$('.date').html(date.toDateString());
$('.complete_btn').on('click', function(){
    var rat_modal = $('#ratingReviewModel');
    rat_modal.find('form').attr('action',$(this).data('url'));
    rat_modal.find('input[name=id]').val($(this).data('id'));
    rat_modal.find('input[name=employer_id]').val($(this).data('employer_id'));
    rat_modal.show();
});
$('input.star').on('click',function(){
    var id = $(this).attr('id');
    id = id.split('-');
    $('input[name="star"]').each(function(index,value){
        inp_id = $(this).attr('id');
        if(index < id[1])
        {
            $('label.'+inp_id).addClass('rating_selected');
        }
        else
        {
            if($('label.'+inp_id).hasClass('rating_selected'))
            {
                $('label.'+inp_id).removeClass('rating_selected');
            }
        }
    })
})
</script>
@endsection
