@extends($activeTemplate.'layouts.frontend')
@section('content')
@push('style')
<style>
.tooltip {
  position: relative;
  display: block;
	opacity:1;
}

.tooltip .tooltiptext {
  visibility: hidden;
  width: 200px;
  background-color: black;
  color: #fff;
  text-align: center;
  border-radius: 6px;
  padding: 5px 0;
  position: absolute;
  z-index: 1;
  bottom: 75%;
  left: 50%;
  margin-left: -100px;
  font-size: 11px;
}

.tooltip .tooltiptext::after {
  content: "";
  position: absolute;
  top: 100%;
  left: 50%;
  margin-left: -5px;
  border-width: 5px;
  border-style: solid;
  border-color: black transparent transparent transparent;
}

.tooltip:hover .tooltiptext {
  visibility: visible;
}
</style>
@endpush
<div class="pt-50 pb-50 section--bg">
	<div class="container">
		<div class="row justify-content-center gy-4">
			<!-- @include($activeTemplate . 'partials.employer_sidebar') -->
			<div class="col-xl-12 ps-xl-4">
				<div class="row gy-4">
                    <div class="col-lg-4 col-md-6 mb-30">
                        <div class="d-widget style--two d-flex flex-wrap align-items-center">
                            <div class="d-widget__content">
                                <h3 class="d-number">{{$jobCount}}</h3>
                                <span class="caption fs--14px">@lang('My Posted Job and Opportunities')<br/>&nbsp;</span>
                            </div>
                            <div class="d-widget__icon border-radius--100">
                                <i class="las la-tasks"></i>
                                <a href="{{route('employer.job.index')}}" class="d-widget__btn mt-2">@lang('View all')</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-30">
                        <div class="d-widget style--two d-flex flex-wrap align-items-center">
                            <div class="d-widget__content">
                                <h3 class="d-number">{{$general->cur_sym}}{{getAmount($totalDeposit)}}</h3>
                                <span class="caption fs--14px">@lang('Payroll Wallet')<br/>&nbsp;</span>
                            </div>
                            <div class="d-widget__icon border-radius--100">
                                <i class="las la-wallet"></i>
                                <a href="{{route('employer.deposit.history')}}" class="d-widget__btn mt-2">@lang('View all')</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-30">
                        <div class="d-widget style--two d-flex flex-wrap align-items-center">
                            <div class="d-widget__content">
                                <h3 class="d-number" style="@if($employer->balance < 1) color:red !important; @endif">{{$general->cur_sym}}{{getAmount($employer->balance)}}
                                  @if($employer->balance < 1)
                                  <label style="margin-bottom:0;">
                                    <div class="tooltip">
                                      <i class="fa fa-info-circle" style="font-size:10px;"></i>
									  <span class="tooltiptext">Minimum balance should be greater than $1</span>
									</div>
                                  </label>
                                  @endif
                                </h3>
                                <span class="caption fs--14px">@lang('Available Balance')<br>(For Agency & Salary Payment)</span>
                            </div>
                            <div class="d-widget__icon border-radius--100">
                                <i class="lar la-credit-card"></i>
                                <a href="{{route('employer.transaction.history')}}" class="d-widget__btn mt-2">@lang('View all')</a>
                            </div>
                        </div>
                    </div>
                </div>

<div class="pt-50 pb-50 section--bg">
	<div class="container">
		<div class="row justify-content-center gy-4">
			<div class="col-xl-12 ps-xl-4"><p style="color:red" align="center"><strong>Important Notes</strong></p>
					<div class="profile-thumb-wrapper">
				 		<!--p style="color:red" >Important Notes</p-->
			                <span style="color:black;font-size:16px;line-height:1.75;width:100%" align="justify">
                                 <ul>	
			                        <li style="list-style:none;"><i class="fa fa-check-square" style="color:#00A550;"></i>&nbsp; For a more successful response, Post your job opportunities at least 1 day in advance. (Same day postings are only available through contacting the agency directly at 416-551-6171). </li>
                                    <li style="list-style:none;"><i class="fa fa-check-square" style="color:#00A550;"></i>&nbsp; To prevent schedule conflicts and confusion, all bookings are done through DSA portal.</li>
                                    <li style="list-style:none;"><i class="fa fa-check-square" style="color:#00A550;"></i>&nbsp; Employers are not to have direct agreement to hire the candidates unless a “Request for Permanent Hire” has been received. (It will impact your DSA record).</li>
                                    <li style="list-style:none;"><i class="fa fa-check-square" style="color:#00A550;"></i>&nbsp; Salary Setup : "Pay At End Of Shift" directly by employers.</li>
                                    <li style="list-style:none;"><i class="fa fa-check-square" style="color:#00A550;"></i>&nbsp; All candidates are independent contractors; responsible to pay their own taxes.</li>   
                                    <li style="list-style:none;"><i class="fa fa-check-square" style="color:#00A550;"></i>&nbsp; Candidates may be paid through the agency. (Submitted Hours Are Subject To Employer's Approval).</li>
                                    <li style="list-style:none;"><i class="fa fa-check-square" style="color:#00A550;"></i>&nbsp; Payroll Wallet Must Have Sufficient Balance For Agency and Salary Payment Upon Approving candidates.</li>
                                 </ul>
                                 <p align="center" style="color:red" ><br/><br/><strong>All our candidates are carefully screened with their valid credentials, IDs, Certifications, Education Qualification and some Personality Assestments.</strong></p>
                            </span> 
					</div>	
			</div>	
		</div>	
	</div>
</div>	

                <!-- <div class="row justify-content-center gy-4">
	                <div class="col-lg-12">
	                    <div class="custom--card mt-4">
	                        <div class="card-body px-4">
	                            <div class="table-responsive--md">
	                                <table class="table custom--table">
	                                    <thead>
	                                        <tr>
	                                            <th>@lang('Plan Name')</th>
	                                            <th>@lang('Order Number')</th>
	                                            <th>@lang('Amount')</th>
	                                            <th>@lang('Status')</th>
	                                        </tr>
	                                    </thead>
	                                    <tbody>
	                                        @forelse($planOrders as $planOrder)
	                                            <tr>
	                                                <td data-label="@lang('Plan Name')">
	                                                    {{__($planOrder->plan->name)}}
	                                                </td>
	                                                <td data-label="@lang('Order Number')">{{$planOrder->order_number}}</td>
	                                                <td data-label="@lang('Amount')">{{getAmount($planOrder->amount)}} {{$general->cur_text}}</td>
	                                                <td data-label="@lang('Status')">
	                                                	@if($planOrder->status == 1)
				                                            <span class="badge badge--success">@lang('Paid')</span>
				                                        @elseif($planOrder->status == 2)
				                                             <span class="badge badge--danger">@lang('Expired')</span>
				                                        @endif
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
            	</div> -->


				<!-- <div class="row gy-4 justify-content-center mt-4">
					@foreach($plans as $plan)
	                    <div class="col-md-6">
	                        <div class="package-card">
	                            <div class="package-card__top">
	                                <div class="icon">
	                                    @php echo $plan->icon @endphp
	                                </div>
	                                <div class="content">
	                                    <h3 class="package-card__name">{{__($plan->name)}}</h3>
	                                    <div class="package-card__price">{{$general->cur_sym}}{{getAmount($plan->amount)}} -->
																				 <!-- <sub>
																				/ {{__($plan->duration)}} @lang('month')
																			</sub> -->
																			<!-- </div>
	                                </div>
	                            </div>
	                            <div class="package-card__content"> -->
	                                <!-- <ul class="package-card__feature-list">
	                                	<li>@lang('You can create') <span class="badge badge--base">{{$plan->job_post}} @lang('job posts')</span></li>
	                                	@foreach($plan->services as $value)
	                                		@php
	                                			$service = App\Models\Service::find($value)
	                                		@endphp
	                                    	<li>{{__($service->name)}}</li>
	                                    @endforeach
	                                </ul> -->
	                            <!-- </div>
	                            <div class="package-card__footer">
	                                <a href="javascript:void(0)" data-bs-toggle="modal" data-plan_id="{{$plan->id}}" data-bs-target="#exampleModal" class="btn btn--dark w-100 mt-4 planSubscribe">@lang('Buy')</a>
	                            </div>
	                        </div>
	                    </div>
                    @endforeach
                </div> -->
			</div>
		</div>
	</div>
</div>


<div class="modal fade custom--modal" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<form method="POST" action="{{route('employer.plan.subscribe')}}">
				@csrf
				<input type="hidden" name="plan_id">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">@lang('Change to Load eWallet')</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<p style="color:red;font-size:14px;"><b><i>@lang('Security Note:')</i></b> @lang('Your credit card information is not stored in your account record.')</p>
					<div class="form-group mt-3">
						<select class="form--control" name="payment" required="">
							<option value="">@lang('Select One')</option>
							<option value="1">{{__($general->sitename)}} @lang('Wallet')</option>
							<option value="2">@lang('Checkout')</option>
						</select>
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
<!-- Modal -->
<div class="modal fade" id="welcome_employer" tabindex="-1" role="dialog" aria-labelledby="welcome_employerTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content" style="border-radius:1.3em;">
      <div class="modal-header" style="border:none;">
      </div>
			<div class="modal-body">
        <center>
					<h2>Welcome<b> !</b><br/>
        </center>
        <span style="padding:10px;"><span style="color:#000;font-size:16px;line-height:1.5;">Let's simplify the process — We have potential candidates who are ready to work!</span></span><br/>
        <span style="padding:10px;width:100%;">
          <span style="color:gray;font-size:14px;line-height:1.75;width:100%">
            <ul style="padding-left:5%; padding-right:5%;">
              <li style="list-style:none;"><i class="fa fa-check-square" style="color:#00A550;"></i>&nbsp;Create Job Posts for immediate staffing needs (For permanent position, please call 416-551-6171).<br/>(SMS notification will be sent to employers when candidates apply to your job posts).</li>
              <li style="list-style:none;"><i class="fa fa-check-square" style="color:#00A550;"></i>&nbsp;Approve (or decline) candidates who applied to your job posts.</li>
              <li style="list-style:none;"><i class="fa fa-check-square" style="color:#00A550;"></i>&nbsp;Add funds to your payroll wallet (For agency fee  of $59+HST per day and for the hired candidate's pay). <br/> ("Pay Salary At The Clinic" option may be arranged once validated).<br/>When finalized, candidates will also recieved the details and confirmation to attend work assignments.</li>
            </ul></span>
          <br/>
          <p align="center"><br/>Sounds good?<br/></p>
        </span>
				<center>
					<button type="button" onclick="emp()" class="btn btn--base btn-block">@lang("Sure!")</button>
					<button type="button" class="btn btn--base btn-block" data-bs-dismiss="modal">@lang("Next")</button>
				</center>
      </div>
      <div class="modal-footer" style="border:none;">
      </div>
    </div>
  </div>
</div>
<!-- Modal -->
<div class="modal fade" id="emp_1" tabindex="-1" role="dialog" aria-labelledby="step_1Title" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content" style="border-radius:1.3em;">
      <div class="modal-header" style="border:none;">
      </div>
      <div class="modal-body">
        <center><h2>Awesome!</h2>
					<span style="color:gray;font-size:16px;line-height:2;">We will be contacting you for verification purposes.</span>
				<!--h5 style="color:red">Please Note:</h5></center>
			    <span style="color:gray;font-size:14px;line-height:1.5;width:100%">
                 <ul>	
			       <li style="list-style:none;"><i class="fa fa-check-square" style="color:#00A550;"></i>&nbsp; For a more successful response, Post your job opportunities at least 1 day in advance. (Same day postings are only available through contacting the agency directly). </li>
                   <li style="list-style:none;"><i class="fa fa-check-square" style="color:#00A550;"></i>&nbsp; All candidates are independent contractors which are paid directly by the agency. Your invoice payment will be deducted from your prepaid account.</li>
                   <li style="list-style:none;"><i class="fa fa-check-square" style="color:#00A550;"></i>&nbsp; Employers are not allowed to have direct agreement to hire the candidates unless a “Request for Permanent Hire” has been received. (It will impact your DSA record).</li>
                 </ul>
                </span--> 
      </div>
      <div class="modal-footer" style="border:none;justify-content: center;">
        <center>
				  <button type="button" class="btn btn--base btn-block" onclick="emp_2();">Next</button>
        </center>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="emp_2" tabindex="-1" role="dialog" aria-labelledby="step_2Title" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content" style="border-radius:1.3em;">
      <div class="modal-header" style="border:none;">
      </div>
      <div class="modal-body">
        <center><h2>We made hiring easy.</h2>
					<span style="color:gray;font-size:16px;line-height:2;">Either temporary or  permanent position, simply post your job opportunities and select the candidates that suit what your office is looking for.</span>
				</center>
      </div>
      <div class="modal-footer" style="border:none; justify-content: center;">
          <button type="button" class="btn btn--base btn-block" data-bs-dismiss="modal">Let's Go!</button>
      </div>
    </div>
  </div>
</div>
@endsection
@push('script')
<script>
	'use strict';
	$('.planSubscribe').on('click', function(){
		var modal = $('#exampleModal');
		modal.find('input[name=plan_id]').val($(this).data('plan_id'));
	})
	$(document).ready(function()
	{
		$('#welcome_employer').modal('show');
	})
</script>
@endpush
