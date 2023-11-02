@php
    $footer = getContent('footer.content', true);
    $policys = getContent('policy_pages.element', false);
    $socialIcons = getContent('social_icon.element', false);
    $cookie = App\Models\Frontend::where('data_keys','cookie.data')->first();
@endphp


@if(@$cookie->data_values->status && !session('cookie_accepted'))
    <div class="cookie__wrapper ">
        <div class="container">
          <div class="d-flex flex-wrap align-items-center justify-content-between">
            <p class="txt my-2">
               @php echo @$cookie->data_values->description @endphp
              <a href="{{ @$cookie->data_values->link }}" target="_blank">@lang('Read Policy')</a>
            </p>
              <a href="javascript:void(0)" class="btn btn--base my-2 policy">@lang('Accept')</a>
          </div>
        </div>
    </div>
 @endif


<footer class="footer-section">
    <div class="footer-section__top">
        <div class="container">
            <div class="row gy-5">
                <div class="col-lg-4 col-sm-8 order-lg-1 order-1">
                    <a href="{{route('home')}}" class="footer-logo"><img src="{{getImage(imagePath()['logoIcon']['path'] .'/logo.png')}}" alt="@lang('logo')"></a>
                    <p class="mt-4">{{__(@$footer->data_values->title)}}</p>
                </div>
                <div class="col-lg-2 col-sm-4 order-lg-2 order-3">
                    <div class="footer-widget">
                        <h3 class="footer-widget__title">@lang('Related Links')</h3>
                        <ul class="footer-widget__list">
                            @foreach($pages as $k => $data)
                              @if(!auth()->user() && !auth()->guard('employer')->user())
                                @if($data->slug == 'job' || $data->slug == 'clients')
                                @else
                                  <li><a href="{{route('pages',[$data->slug])}}">{{__($data->name)}}</a></li>
                                @endif
                              @else
                                <li><a href="{{route('pages',[$data->slug])}}">{{__($data->name)}}</a></li>
                              @endif
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="col-lg-2 col-sm-4 order-lg-3 order-4">
                    <div class="footer-widget">
                        <h3 class="footer-widget__title">@lang('Information')</h3>
                        <ul class="footer-widget__list" >
                            <li><a href="{{route('contact')}}">@lang('Support')</a></li>
                            @if(!auth()->user() && !auth()->guard('employer')->user())
                                <li><a href="{{route('login')}}">@lang('Login')</a></li>
                                <li><a href="{{route('register')}}">@lang('Join With Us')</a></li>
                            @endif
                        </ul>
                    </div><!-- footer-widget end -->
                </div>
                <div class="col-lg-2 col-sm-4 order-lg-4 order-5">
                    <div class="footer-widget">
                        <h3 class="footer-widget__title">@lang('Support')</h3>
                        <ul class="footer-widget__list">
                            @foreach($policys as $policy)
                                <li><a href="{{route('footer.menu', [slug($policy->data_values->title), $policy->id])}}">{{__($policy->data_values->title)}}</a></li>
                            @endforeach
                        </ul>
                    </div><!-- footer-widget end -->
                </div>
                <div class="col-lg-2 col-sm-4 order-lg-5 order-2">
                    <div class="footer-widget">
                        <div class="overview-item">
                            <div class="overview-item__number text--base">{{__(@$footer->data_values->job_post)}}</div>
                            <p class="caption">@lang('Job posts')</p>
                        </div>
                        <div class="overview-item">
                            <div class="overview-item__number text--base">{{__(@$footer->data_values->candidate)}}</div>
                            <p class="caption">@lang('Total Candidates')</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-section__bottom">
        <div class="container">
            <div class="row gy-4">
                <div class="col-md-6 text-md-start text-center">
                    <p>@lang('Copyright') © {{Carbon\Carbon::now()->format('Y')}} {{__($general->sitename)}}. @lang('All Rights Reserved.')</p>
                </div>
                <div class="col-md-6">
                    <ul class="social-link d-flex flex-wrap align-items-center justify-content-md-end justify-content-center">
                        @foreach($socialIcons as $socialIcon)
                            <li><a href="{{$socialIcon->data_values->url}}" target="_blank">@php echo $socialIcon->data_values->social_icon @endphp</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>


<!-- Modal -->
<div class="modal fade" id="step_1" tabindex="-1" role="dialog" aria-labelledby="step_1Title" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content" style="border-radius:1.3em;">
      <div class="modal-header" style="border:none;">
      </div>
      <div class="modal-body">
        <center><h2>Create Job! 1/2</h2><br/>
          <span style="color:gray;font-size:14px;line-height:2;margin-bottom:30px;">Post to hire the best candidates at your convenience!</span><br/>
          <button type="button" class="btn btn--base btn-block" onclick="hide_modal();">Next</button>
        </center>
      </div>
      <div class="modal-footer" style="border:none;">
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="step_2" tabindex="-1" role="dialog" aria-labelledby="step_2Title" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content" style="border-radius:1.3em;">
      <div class="modal-header" style="border:none;">
      </div>
      <div class="modal-body">
        <center><h2>Create Job! 2/2</h2><br/>
            <span  style="color:gray;font-size:14px;line-height:2;margin-bottom:30px;">Your job posting will display "ACTIVE"
                immediately upon Approval.
              </span><br/>
            <button type="button" class="btn btn--base btn-block close" onclick="redirect_page();">Proceed</button>
          </center>
      </div>
      <div class="modal-footer" style="border:none;">
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="confirm_1" data-ignoreBackdropClick="true"  data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content" style="border-radius:1.3em;">
      <div class="modal-header" style="border:none;">
      </div>
      <div class="modal-body">
        <center><h2>You got a job!</h2></center>
        <!--span style="color:#000;font-size:18px;line-height:2;padding:10px;"><br/></span-->
        <span style="color:gray;font-size:16px;line-height:2;padding:15px;width:100%;">
            <ul>
              <li style="list-style:none;"><i class="fa fa-check-square" style="color:#00A550;"></i>&nbsp; A). To get paid, remember to <strong>Time In</strong> your hours on the same day!(For your accurate pay, please time out immediately after you finished your attended shift)</li>
              <li style="list-style:none;"><i class="fa fa-check-square" style="color:#00A550;"></i>&nbsp; B). Cancellation Is Not Acceptable. (It will impact your DSA Record.). You must contact us directly!</li>
              <li style="list-style:none;"><i class="fa fa-check-square" style="color:#00A550;"></i>&nbsp; C). You’re not Allowed to contact the Employer directly, nor be contacted by the employers directly.</li>
            </ul>
        </span>
      </div>
      <div class="modal-footer" style="border:none;">
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="confirm_2" tabindex="-1" role="dialog" aria-labelledby="step_2Title" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content" style="border-radius:1.3em;">
      <div class="modal-header" style="border:none;">
      </div>
      <div class="modal-body">
        <center><h3> Show up, enjoy your day then submit to record your “Timesheet Hours” to get paid.</h3><br/></center>
      </div>
      <div class="modal-footer" style="border:none;">
      </div>
    </div>
  </div>
</div>
@push('script')
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/clockpicker/0.0.7/bootstrap-clockpicker.min.css">
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/clockpicker/0.0.7/bootstrap-clockpicker.min.js"></script>
<script>
'use strict';
$('.policy').on('click',function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.get('{{route('cookie.accept')}}', function(response){
        $('.cookie__wrapper').addClass('d-none');
         notify('success', response.success);

    });
});
$('form#apply_job_form').submit(function(){
    $(this).find(':input[type=submit]').prop('disabled', true);
});

</script>
<script>
function create_info()
{
    var pathname = window.location.pathname,
    part = pathname.substr(pathname.lastIndexOf('/') + 1);
    if(part == 'create')
    {
        window.location = "{{route('employer.job.create')}}";
    }
    else
    {
        $('#step_1').modal('show');
    }
}
function hide_modal()
{
    $('#step_1').modal('hide');
    $('#step_2').modal('show');
}
function redirect_page()
{
    $('#step_2').modal('hide');
    window.location = "{{route('employer.job.create')}}";
}
function im_sure()
{
  $('.approved_btn').html('<i class="fa fa-spinner fa-spin"></i>');
  $.ajax({
      url:"{{ route('user.job.lowbalance') }}",
      type:"POST",
      data: {
        "_token": "{{ csrf_token() }}",
      'id': $('#userapprovalform > input[name="id"]').val()
      },
      success:function (data) {
        if(data == 'low_balance')
        {
          $('#userjobApprovalModel').modal('hide');
          alert('Please call support at +1 (416) 551-6171.');
          $('.approved_btn').html('Yes');
        }
        else
        {
          $('#userjobApprovalModel').modal('hide');
          $('#confirm_1').modal('show');
          setTimeout(function() {$('#userapprovalform').submit();}, 10000);
          $('.approved').hide();
          $('.cancel').hide();
          $('#action_btn').prepend('<span class="badge badge--success">accepted</span>');
          $('.approved_btn').html('Yes');
        }
      }
  })

}
function confirm_2()
{
  $('#confirm_1').modal('hide');
  $('#confirm_2').modal('show');
}
function emp()
{
  $('#welcome_employer').modal('hide');
  $('#emp_1').modal('show');
}
function emp_2()
{
  $('#emp_1').modal('hide');
  $('#emp_2').modal('show');
}
function work_hr(type)
{
  if(type == 'work_hr')
  {
    $('#work_btn').html('<i class="fa fa-spinner fa-spin"></i>');
    $.ajax({
        url:"{{ route('user.job.lowbalance') }}",
        type:"POST",
        data: {
          "_token": "{{ csrf_token() }}",
        'id': $('#currjobreportform > input[name="id"]').val()
        },
        success:function (data) {
          if(data == 'low_balance')
          {
            $('#userjobApprovalModel').modal('hide');
            alert('Please call support at +1 (416) 551-6171.');
            $('#work_btn').html('Yes');
          }
          else
          {
            $('#currjobreportform').submit();
          }
        }
    })
  }
  else
  {
    $('#outtime_btn').html('<i class="fa fa-spinner fa-spin"></i>');
    $.ajax({
        url:"{{ route('user.job.lowbalance') }}",
        type:"POST",
        data: {
          "_token": "{{ csrf_token() }}",
        'id': document.getElementById("dropdown_job_id").value
        },
        success:function (data) {
          if(data == 'low_balance')
          {
            $('#userjobApprovalModel').modal('hide');
            alert('Please call support at +1 (416) 551-6171.');
            $('#outtime_btn').html('Submit');
          }
          else
          {
            $('#outtime_form').submit();
          }
        }
    })
  }
}
$(".reportintime").clockpicker({
   autoclose: true,
});
</script>

@endpush
