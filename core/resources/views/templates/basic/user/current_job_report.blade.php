 @extends($activeTemplate.'layouts.frontend')
@section('content')
<div class="pt-50 pb-50 section--bg">
    <div class="container">
        <div class="row justify-content-center gy-4">
            <!-- @include($activeTemplate . 'partials.user_sidebar') -->
            <div class="col-xl-12 ps-xl-4">
                    @forelse($jobApplys as $jobApply)
                    <form id="currjobreportform" action="{{route('user.job.currjobreport',$jobApply->id)}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="{{$jobApply->id}}">
                        <input type="hidden" name="hourly_rate" value="{{$jobApply->user->candidate_rate}}">
                        <div class="custom--card mt-4">
                            <div class="card-header bg--dark">
                                <h5 class="text-white">@lang('Working Hours')</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6 form-group">
                                        <label for="working_hours">@lang('Working hours') <sup class="text--danger">*</sup></label>
                                        <div class="custom-icon-field">
                                            <select class="select" style="min-height:40px;width:15%;text-indent:0" name="hours">
                                              @for ($i = 1; $i < 25; $i++)
                                              <option value="{{$i}}">{{$i}}</option>
                                              @endfor
                                            </select>
                                            <label>Hr</label>&nbsp;:&nbsp;
                                            <select class="select" style="min-height:40px;width:15%;text-indent:0" name="minutes">
                                              @for ($j = 0; $j < 60; $j++)
                                                @if($j < 10)
                                                  <option value="0{{$j}}">0{{$j}}</option>
                                                @else
                                                  <option value="{{$j}}">{{$j}}</option>
                                                @endif
                                              @endfor
                                            </select>
                                            <label>Mins</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 form-group">
                                        <button id="work_btn" type="button" class="btn btn--base" @if($jobApply->job->job_cat_rate == 'full_timerate') @if($weekDay != 5) disabled @endif @endif @if($curr != 0) readonly @endif onclick="work_hr('work_hr')"><!--<i class="las la-upload fs--18px"></i>--> @lang('Submit')</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    @empty
                        {{ __($emptyMessage) }}
                    @endforelse

                <div class="custom--card mt-4">
                    <div class="card-body">

                        <h2>Working Hours Report </h2>
                        <div class="table-responsive--md">
                            <table class="table custom--table ">
                                <thead>
                                <tr>
                                    <th>@lang('Job Title')</th>
                                    <th>@lang('Working Hours')</th>
                                    <th>@lang('Hourly Rate')</th>
                                    <th>@lang('Added On')</th>
                                    <th><center>@lang('Invoice Download')</center></th>
                                </tr>
                                </thead>
                                <tbody>
                                    @forelse($invoices as $invoice)
                                        <tr>
                                            <td data-label="@lang('Job Title')">{{__($invoice->job->title)}}</td>
                                            <td data-label="@lang('Working Hours')">{{__($invoice->working_hours)}}</td>
                                            <td data-label="@lang('Hourly Rate')">{{getAmount($invoice->hourly_price)}} {{$general->cur_text}}</td>
                                            <td data-label="@lang('Added On')"> {{showDateTime($invoice->created_at, 'd M Y')}}</td>
                                            <td data-label="@lang('Invoice Download')">
                                              <center>
                                                <a href="{{route('download.invoice',$invoice->prefix.'-00'.$invoice->id)}}" data-toggle="tooltip" title="Download Invoice" class="icon-btn bg--primary">
                                                  <i class="las la-arrow-down"></i>
                                                </a>
                                              </center>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="100%" class="text-center">{{ __($emptyMessage) }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>

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
                <div class="modal-body" style="padding:10% 10% 0 10%">
                  <div class="row">
                    <center>
                      <p style="padding-top:10px;color:gray;font-size:14px;line-height:2;">Your efforts are always appreciated.<br/>This is your chance to shine!<br/>Are you sure you can commit to this work? </p>
                    </center>
                  </div>
                </div>
                <div class="modal-footer" style="width:100%;border:none;justify-content:center;">
                  <button type="button" onclick="im_sure()" class="btn btn--base btn-block approved_btn"><i class="fa fa-check-square" style="color:#00A550;"></i>&nbsp;@lang("Yes")</button>
                  <button type="button" class="btn btn--base btn-block" data-bs-dismiss="modal"><i class="fa fa-question" style="color:red"></i>&nbsp;@lang("Not Sure")</button>
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
</script>
@endsection