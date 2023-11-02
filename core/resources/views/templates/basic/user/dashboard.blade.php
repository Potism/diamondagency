@extends($activeTemplate.'layouts.frontend')
@section('content')
<div class="pt-50 pb-50 section--bg">
    <div class="container">
        <div class="row justify-content-center gy-4">
            <div class="col-xl-12 ps-xl-4">
                <div class="row gy-4">
                    <div class="col-lg-4 col-md-6 mb-30">
                        <div class="d-widget style--two d-flex flex-wrap align-items-center">
                            <div class="d-widget__content">
                                <h3 class="d-number">{{$jobApplyCount}}</h3>
                                <span class="caption fs--14px">@lang('Total Jobs Apply')</span>
                            </div>
                            <div class="d-widget__icon border-radius--100">
                               <i class="las la-file-alt"></i>
                                <a href="{{route('user.job.application.list')}}" class="d-widget__btn mt-2">@lang('View all')</a>
                            </div>
                        </div><!-- d-widget end -->
                    </div>
                    <div class="col-lg-4 col-md-6 mb-30">
                        <div class="d-widget style--two d-flex flex-wrap align-items-center">
                            <div class="d-widget__content">
                                <h3 class="d-number">{{date('h:i a',strtotime(date('Y-m-d H:i:s')))}}</h3>
                                @if($curr != 0)
                                <div class="d-number" style="line-height: normal;margin-top: -2%;">
                                  <a href="#" data-bs-toggle="modal" data-bs-target="#in_time" class="d-widget__btn mt-2" style="margin:0rem 0.5rem !important;">Time In</a><a href="#" data-bs-toggle="modal" data-bs-target="#out_time" class="d-widget__btn mt-2" style="margin:0rem 0.5rem !important;">Submit Hours</a>
                                </div>
                                @else
                                  Not applied for any job
                                @endif
                            </div>
                            <div class="d-widget__icon border-radius--100">
                                <i class="lar la-clock"></i>
                            </div>
                        </div><!-- d-widget end -->
                    </div>
                    <div class="col-lg-4 col-md-6 mb-30">
                        <div class="d-widget style--two d-flex flex-wrap align-items-center">
                            <div class="d-widget__content">
                                <h3 class="d-number">{{$totalInvoice}}</h3>
                                <span class="caption fs--14px">@lang('Total Invoices')</span>
                            </div>
                            <div class="d-widget__icon border-radius--100">
                                <i class="las la-ticket-alt"></i>
                                <a href="{{route('user.invoices')}}" class="d-widget__btn mt-2">@lang('View all')</a>
                            </div>
                        </div><!-- d-widget end -->
                    </div>
                </div>

                <div class="row gy-4">
                    <div class="col-lg-12">
                        <div class="custom--card mt-4">
                            <div class="card-body px-4" id='calendar'>
                            </div>
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
              <div class="modal-body" style="color:gray;font-size:14px;line-height:2;padding:10% 10% 0 10%">
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
<div class="modal fade" id="welcome_user" tabindex="-1" role="dialog" aria-labelledby="welcome_userTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content" style="border-radius:1.3em;">
      <div class="modal-header" style="border:none;">
      </div>
      <div class="modal-body">
        <center>
          <h2>Welcome<b> !</b><br/>
        </center>
        <span style="color:#000;font-size:18px;line-height:2;padding:10px;">We have designed a fast, easy and efficient way to find job opportunities for you!</span><br/>
        <span style="color:gray;font-size:16px;line-height:2;padding:15px;width:100%;">
            <ul>
              <li style="list-style:none;"><i class="fa fa-check-square" style="color:#00A550;"></i>&nbsp;Access an easy to book Temp Shifts.</li>
              <li style="list-style:none;"><i class="fa fa-check-square" style="color:#00A550;"></i>&nbsp;Accept to work near your home.</li>
              <li style="list-style:none;"><i class="fa fa-check-square" style="color:#00A550;"></i>&nbsp;Earn hourly rate based on your qualifications.</li>
            </ul>
        </span>
      </div>
      <div class="modal-footer" style="border:none; justify-content: center;">
          <button type="button" class="btn btn--base btn-block" data-bs-dismiss="modal" >Get Started !</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade custom--modal" id="in_time" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{route('user.job.currjobintime')}}">
                @csrf
                <!-- <input type="hidden" name="id"> -->
                <input type="hidden" name="type">
                <input type="hidden" name="job_id">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">@lang('Time In')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row rate_d" style="margin-left: 5px;">
                        <!-- <label for="cat_rate">@lang('In Time') <sup class="text--danger">*</sup></label> -->
                        <div class="row btn-group btn-group-toggle" data-toggle="buttons">
                            <div class="col-md-3 form-group">
                                <label for="cat_rate">@lang('Time In')<sup class="text--danger">*</sup>:</label>
                            </div>
                            <div class="col-md-9 form-group">
                              <select class="select" style="padding-left:5px;min-height:40px;width:auto;text-indent:0" name="hours">
                                @for ($i = 1; $i < 13; $i++)
                                @if($i < 10)
                                  <option value="0{{$i}}">0{{$i}}</option>
                                @else
                                  <option value="{{$i}}">{{$i}}</option>
                                @endif
                                @endfor
                              </select>
                              <label>:</label>
                              <select class="select" style="padding-left:5px;min-height:40px;width:auto;text-indent:0" name="minutes">
                                @for ($j = 0; $j < 60; $j++)
                                  @if($j < 10)
                                    <option value="0{{$j}}">0{{$j}}</option>
                                  @else
                                    <option value="{{$j}}">{{$j}}</option>
                                  @endif
                                @endfor
                              </select>
                              <select class="select" style="padding-left:5px;min-height:40px;width:auto;text-indent:0" name="am_pm">
                                <option value="am">am</option>
                                <option value="pm">pm</option>
                              </select>
                            </div>
                        </div>
                    </div>
                    <div class="row rate_d" style="margin-left: 5px;">
                        <!-- <label for="cat_rate">@lang('JOb title') <sup class="text--danger">*</sup></label> -->
                        <div class="row btn-group btn-group-toggle" data-toggle="buttons">
                            <div class="col-md-3 form-group">
                                <label for="cat_rate">@lang('Job title')<sup class="text--danger">*</sup>:</label>
                            </div>
                            <div class="col-md-9 form-group">
                                <select class="select" style="min-height:40px;text-indent:0" name="job_id" required>
                                    @if($current_jobApplys)
                                        <option value='' selected>Select a Job</option>
                                    @endif
                                    @forelse($current_jobApplys as $current_jobApply)
                                        <option value="{{__($current_jobApply->id)}}" data-jobid="{{__($current_jobApply->job_id)}}" data-type="{{__($current_jobApply->job_type)}}" @if(in_array($current_jobApply->id,$job_rep)) disabled @elseif(in_array($current_jobApply->id,$per_job)) disabled @endif >{{__($current_jobApply->job->title)}} - {{__($current_jobApply->job->employer->company_name)}} &nbsp;</option>
                                    @empty
                                        <option value=''>Not applied for any job</option>
                                    @endforelse
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--secondary btn-sm" data-bs-dismiss="modal">@lang('Close')</button>
                    <button type="submit" class="btn btn--primary btn-sm">@lang('Submit')</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade custom--modal" id="out_time" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="outtime_form" method="POST" action="{{route('user.job.currjobouttime')}}">
                @csrf
                <!-- <input type="hidden" name="id"> -->
                <input type="hidden" name="type">
                <input type="hidden" name="job_id">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">@lang('Submit Hours')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row rate_d" style="margin-left: 5px;">
                        <div class="row btn-group btn-group-toggle" data-toggle="buttons">
                            <div class="col-md-3 form-group">
                                <label for="cat_rate">@lang('Your Total Worked Hrs')<sup class="text--danger">*</sup>:</label>
                            </div>
                            <div class="col-md-9 form-group">
                                <select class="select" style="min-height:40px;width:35%;text-indent:0" name="hours">
                                  @for ($i = 1; $i < 101; $i++)
                                  <option value="{{$i}}">{{$i}}</option>
                                  @endfor
                                </select>
                                <label>Hr</label>&nbsp;:&nbsp;
                                <select class="select" style="min-height:40px;width:35%;text-indent:0" name="minutes">
                                  @for ($j = 0; $j < 60; $j++)
                                    @if($j < 10)
                                      <option value="0{{$j}}">0{{$j}}</option>
                                    @else
                                      <option value="{{$j}}">{{$j}}</option>
                                    @endif
                                  @endfor
                                </select>
                                <label>Mins</label>
                                <br/><em>(Subject To Approval)</em>
                            </div>
                        </div>
                    </div>
                    <div class="row rate_d" style="margin-left: 5px;">
                        <!-- <label for="cat_rate">@lang('JOb title') <sup class="text--danger">*</sup></label> -->
                        <div class="row btn-group btn-group-toggle" data-toggle="buttons">
                            <div class="col-md-3 form-group">
                                <label for="cat_rate">@lang('Worked Assignment')<sup class="text--danger">*</sup>:</label>
                            </div>
                            <div class="col-md-9 form-group">
                                <select class="select" style="min-height:40px;text-indent:0" name="job_id" id="dropdown_job_id" required>
                                    @if($current_jobApplys)
                                        <option value='' selected>Select a Job</option>
                                    @endif
                                    @forelse($current_jobApplys as $current_jobApply)
                                          <option value="{{__($current_jobApply->id)}}" data-jobid="{{__($current_jobApply->job_id)}}" data-type="{{__($current_jobApply->job_type)}}" @if(in_array($current_jobApply->id,$job_out_rep)) disabled @elseif(in_array($current_jobApply->id,$per_job) && $weekDay != 5) disabled @endif>{{__($current_jobApply->job->title)}}  - {{__($current_jobApply->job->employer->company_name)}} &nbsp;</option>
                                    @empty
                                        <option value=''>Not applied for any job</option>
                                    @endforelse
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--secondary btn-sm" data-bs-dismiss="modal">@lang('Close')</button>
                    <button type="button" id="outtime_btn" onclick="work_hr('outtime');" class="btn btn--primary btn-sm">@lang('Submit')</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('script')
<script>
    $(document).ready(function()
    {
        $('#welcome_user').modal('show');
    });
    $('.approved').on('click', function(){
        var modal = $('#userjobApprovalModel');
        modal.find('input[name=id]').val($(this).data('id'));
        modal.find('input[name=type]').val($(this).data('type'));
        modal.find('input[name=job_id]').val($(this).data('job_id'));
        modal.show();
    });

    $('.cancel').on('click', function(){
        var modal = $('#userjobCancelModel');
        modal.find('input[name=id]').val($(this).data('id'));
        modal.find('input[name=type]').val($(this).data('type'));
        modal.find('input[name=job_id]').val($(this).data('job_id'));
        modal.show();
    });
    const date = new Date();
    date.setDate(date.getDate() + 1);
    $('.date').html(date.toDateString());
    function isNumberKey(evt)
   {
      var charCode = (evt.which) ? evt.which : evt.keyCode;
      if (charCode != 46 && charCode > 31
        && (charCode < 48 || charCode > 57))
         return false;

      return true;
   }
   $(document).ready(function() {

        $('#calendar').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            defaultView: 'month',
            editable: false,
            events: {!! $jobs !!},
            eventClick: function(event) {
              if (event.id) {
                let url = "{{ route('job.detail', ':id') }}";
                url = url.replace(':id', event.id);
                  window.open(url, "_blank");
                  return false;
              }
          }
        });

    });

</script>
@endpush
