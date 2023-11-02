@extends($activeTemplate.'layouts.frontend')
@section('content')
@push('style')
<style type="text/css">
    /** rating **/
#ratingReviewModel div.stars {
  display: inline-block;
}

#ratingReviewModel input.star { display: none; }

#ratingReviewModel label.star {
  /*float: right;*/
  padding: 10px;
  font-size: 20px;
  color: #444;
  transition: all .2s;
}

#ratingReviewModel label.star:hover { transform: rotate(-15deg) scale(1.3); }

#ratingReviewModel label.star:before {
  content: '\f006';
  font-family: FontAwesome;
  color: #ffff33;
}


#ratingReviewModel .horline > li:not(:last-child):after {
    content: " |";
}
#ratingReviewModel .horline > li {
  font-weight: bold;
  color: #ff7e1a;

}
.rating_selected:before{
    content: '\f005' !important;
    color: #ffff33 !important;
    transition: all .25s !important;
}
/** end rating **/
</style>
@endpush
    <div class="pt-50 pb-50 section--bg">
        <div class="container">
            <div class="row justify-content-center gy-4">
                <div class="col-xl-12 ps-xl-4">
                    <div class="custom--card mt-4">
                          <div class="card-header bg--dark d-flex flex-wrap justify-content-between align-items-center">
                        <h5 class="text-white me-3">{{__($pageTitle)}}</h5>

                    </div>
                        <div class="card-body px-4">
                            <div class="table-responsive--md">
                                <table class="table custom--table">
                                    <thead>
                                        <tr>
                                            <th>@lang('Candidate')</th>
                                            <th>@lang('Rate')</th>
                                            <!--th>@lang('CV Download')</th-->
                                            <th>@lang('Status')</th>
                                            <th>@lang('Action')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($appliedJobs as $appliedJob)
                                            <tr>
                                                <td data-label="@lang('Candidate')">
                                                    {{__($appliedJob->user->firstname)}} {{__($appliedJob->user->lastname)}}
                                                </td>
                                                <td data-label="@lang('Rate')">
                                                    <strong>
                                                    @if($appliedJob->job_type == 'temp_job')
                                                        $ {{ $appliedJob->user->candidate_rate + ($appliedJob->user->candidate_rate * $appliedJob->job->category->temp_markup_rate/100) }}
                                                    @else
                                                        $ {{ $appliedJob->user->candidate_rate + ($appliedJob->user->candidate_rate * $appliedJob->job->category->markup_rate/100) }}
                                                    @endif
                                                    </strong>
                                                </td>
                                                <!--td data-label="@lang('Cv Download')">
                                                    <a href="{{route('employer.cv.download', encrypt($appliedJob->id))}}" class="icon-btn bg--primary"><i class="las la-arrow-down"></i></a>
                                                </td-->

                                                <td data-label="@lang('Status')">
                                                  @php
                                                  $job_applied_emp = App\Models\JobApply::where('job_id', $appliedJob->job->id)->where('status','=', 1)->where('accept_by_user','=', 1)->get();
                                                  @endphp
                                                    @if($appliedJob->job->vacancy == sizeof($job_applied_emp) && $appliedJob->status != 1 && $appliedJob->accept_by_user != 1 )
                                                    <span class="badge badge--warning">@lang('Job Is Filled')</span>
                                                    @elseif($appliedJob->accept_by_user == 2)
                                                      <span class="badge badge--danger">@lang('Rejected')</span>
                                                    @elseif($appliedJob->status == 0)
                                                        <span class="badge badge--primary">@lang('Pending')</span>
                                                    @elseif($appliedJob->status == 1)
                                                        <span class="badge badge--success">@lang('Received')</span>
                                                    @elseif($appliedJob->status == 2)
                                                        <span class="badge badge--danger">@lang('Rejected')</span>
                                                    @endif
                                                </td>

                                                <td data-label="@lang('Action')">
                                                    @if($appliedJob->job->vacancy == sizeof($job_applied_emp) && $appliedJob->user->id == $appliedJob->user_id)

                                                    @elseif($appliedJob->status == 0)
                                                        <a href="javascript:void(0)" data-bs-toggle="modal" data-id="{{$appliedJob->id}}" data-bs-target="#jobApprovalModel" class="icon-btn bg--primary approved"><i class="las la-check text-white" data-toggle="tooltip" title="Approved"></i></a>
                                                        <a href="javascript:void(0)" data-bs-toggle="modal" data-id="{{$appliedJob->id}}" data-bs-target="#jobCancelModel" class="icon-btn bg--danger cancel"><i class="las la-times text-white" data-toggle="tooltip" title="Cancel"></i></a>
                                                    @elseif($appliedJob->status != 2 && $appliedJob->accept_by_user != 2 && $appliedJob->status != 0 && $appliedJob->accept_by_user != 0)
                                                        @if($appliedJob->job_status != 2 && $appliedJob->employee_review == 0)
                                                          <a href="javascript:void(0)" data-bs-toggle="modal" data-id="{{$appliedJob->id}}" data-user_id="{{$appliedJob->user_id}}" data-url="{{route('employer.job.jobreview')}}" class="icon-btn bg--info complete_btn" data-bs-target="#ratingReviewModel" style="width: auto;padding: 5px;">Complete</a>
                                                        @else
                                                          {!!get_rating($appliedJob->employee_review)!!}
                                                        @endif
                                                        <a href="{{route('employer.user.invoices',[$appliedJob->job_type,$appliedJob->job_id,$appliedJob->user_id])}}" class="icon-btn bg--info"><i class="las la-file-invoice text-white" data-toggle="tooltip" title="Invoice"></i></a>
                                                    @endif
                                                    <a href="{{route('candidate.profile', [slug($appliedJob->user->username), $appliedJob->user_id,$appliedJob->job_id])}}" class="icon-btn bg--info"><i class="las la-desktop text-white" data-toggle="tooltip" title="View Profile"></i></a>
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
            <form method="POST" action="{{route('employer.job.approved')}}">
                @csrf
                <input type="hidden" name="id">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">@lang('Approve Application')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @lang('Are you sure you want to HIRE this Candidate ?')
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
            <form method="POST" action="{{route('employer.job.cancel')}}">
                @csrf
                <input type="hidden" name="id">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">@lang('Reject Application')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @lang('Are you sure you want to reject this Candidate?')
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
                    <h5 class="modal-title" id="exampleModalLabel">@lang('Review Candidate')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                @lang('Please rate the candidate')

                    <div class="form-group required">
                      <div class="col-sm-12">
                        <input type="hidden" name="id">
                        <input type="hidden" name="user_id">
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
    $('.complete_btn').on('click', function(){
        var rat_modal = $('#ratingReviewModel');
        rat_modal.find('form').attr('action',$(this).data('url'));
        rat_modal.find('input[name=id]').val($(this).data('id'));
        rat_modal.find('input[name=user_id]').val($(this).data('user_id'));
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
@endpush
