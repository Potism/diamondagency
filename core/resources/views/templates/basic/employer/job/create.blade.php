@extends($activeTemplate.'layouts.frontend')
@section('content')
@push('style')
<style media="screen">
	.modal-backdrop{
	   backdrop-filter: blur(15px);
	   background-color: #01101ae8;
	}
	.modal-backdrop{
	   opacity: 1 !important;
	}
	.buttonload {
	  background-color: #04AA6D; /* Green background */
	  border: none; /* Remove borders */
	  color: white; /* White text */
	  padding: 12px 24px; /* Some padding */
	  font-size: 16px; /* Set a font-size */
	}
	.select2-container--default.select2-container--focus .select2-selection--multiple,.select2-container--default .select2-selection--multiple
	{
      border: 1px solid #e5e5e5;
      outline: 0;
      padding: 8px;
	}
	input[type="date"]
    {
        display:block;
        -webkit-appearance: textfield;
        -moz-appearance: textfield;
        min-height: 1.2em; 
        
    }
</style>
@endpush
<div class="pt-50 pb-50 section--bg">
	<div class="container">
		<div class="row justify-content-center gy-4">
			<!-- @include($activeTemplate . 'partials.employer_sidebar') -->
			<div class="col-xl-12 ps-xl-4">
				<form action="{{route('employer.job.store')}}" method="POST" enctype="multipart/form-data" class="edit-profile-form">
					@csrf
					<div id="permanent_job">
						<div class="custom--card mt-4">
							<div class="card-header bg--dark">
								<h5 class="text-white">@lang('Basic Information')</h5>
							</div>
							<div class="card-body">
								<div class="row">
									<div class="col-lg-6 form-group">
										<label for="title">@lang('Title') <sup class="text--danger">*</sup></label>
										<input type="text" name="title" id="title" value="{{old('title')}}" class="form--control" placeholder="@lang('Enter Job Title')"maxlength="255" required="">
									</div>
									<div class="col-lg-6 form-group">
										<label for="shift">@lang('Shift') <sup class="text--danger">*</sup></label>
										<select class="form--control" name="shift" id="shift" required="">
											<option value="" selected="">@lang('Select Shift')</option>
											@foreach($shifts as $shift)
												<option value="{{$shift->id}}" @if(old('shift') == $shift->id) selected @endif>{{__($shift->name)}}</option>
											@endforeach
										</select>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-6 form-group">
										<label for="category">@lang('Category') <sup class="text--danger">*</sup></label>
										<select class="form--control" name="category" id="category" required="">
											<option value="">@lang('Select Category')</option>
											@foreach($categorys as $category)
												<option value="{{$category->id}}" @if(old('category') == $category->id) selected @endif>{{__($category->name)}}</option>
											@endforeach
										</select>
									</div>
									<div class="col-lg-6 form-group">
										<label for="vacancy">@lang('Vacancy') <sup class="text--danger">*</sup></label>
										<input type="text" name="vacancy" id="vacancy" value="{{old('vacancy')}}" class="form--control" placeholder="@lang('Number of Job vacant')" required="">
									</div>
									<!-- <div class="col-lg-6 form-group">
										<label for="type">@lang('Type') <sup class="text--danger">*</sup></label>
										<select class="form--control" name="type" id="type" required="">
											<option value="">@lang('Select Type')</option>
											@foreach($types as $type)
												<option value="{{$type->id}}" @if(old('type') == $type->id) selected @endif>{{__($type->name)}}</option>
											@endforeach
										</select>
									</div> -->


									<div class="col-lg-6 form-group">
										<label for="city">@lang('Province') <sup class="text--danger">*</sup></label>
										<select class="form--control" name="city" id="city" required="">
											<option value="" selected="">@lang('Select Province')</option>
											@foreach($cities as $city)
												<option value="{{$city->id}}" data-locations="{{json_encode($city->location)}}">{{__($city->name)}}</option>
											@endforeach
										</select>
									</div>

									<div class="col-lg-6 form-group">
										<label for="location">@lang('Location') <sup class="text--danger">*</sup>/ City</label>
										<select class="form--control" name="location" id="location" required="">
										</select>
									</div>
								</div>
							</div>
						</div>


						<div class="custom--card mt-4">
							<div class="card-header bg--dark">
								<h5 class="text-white">@lang('Job Information')</h5>
							</div>
							<div class="card-body">
								<div class="row">
									<div class="col-lg-6 form-group">
										<label for="job_experience">@lang('Experience') <sup class="text--danger">*</sup></label>
										<select class="form--control" name="job_experience" id="job_experience">
											<option value="">@lang('Select Experience')</option>
											@foreach($experiences as $experience)
												<option value="{{$experience->id}}" @if(old('job_experience') == $experience->id) selected @endif>{{__($experience->name)}}</option>
											@endforeach
										</select>
									</div>

									<div class="col-lg-6 form-group">
										<label for="gender">@lang('Gender') <sup class="text--danger">*</sup></label>
										<select class="form--control" name="gender" id="gender">
											<option value="">@lang('Select One')</option>
											<option value="1" @if(old('gender') == 1) selected @endif>@lang('Male')</option>
											<option value="2" @if(old('gender') == 2) selected @endif>@lang('Female')</option>
											<option value="3" @if(old('gender') == 3) selected @endif>@lang('No Preference')</option>
										</select>
									</div>


									<!-- <div class="col-lg-6 form-group">
										<label for="salary_type">@lang('Salary Type') <sup class="text--danger">*</sup></label>
										<select class="form--control" name="salary_type" id="salary_type">
											<option value="">@lang('Select One')</option>
											<option value="1">@lang('Negotiation')</option>
											<option value="2">@lang('Range')</option>
										</select>
									</div>

									<div class="col-lg-6 form-group">
										<label for="salary_period">@lang('Salary Period') <sup class="text--danger">*</sup></label>
										<select class="form--control" name="salary_period" id="salary_period">
											<option value="">@lang('Select One')</option>
											@foreach($salaryPeriods as $salaryPeriod)
												<option value="{{$salaryPeriod->id}}" @if(old('salary_period') == $salaryPeriod->id) selected @endif>{{__($salaryPeriod->name)}}</option>
											@endforeach
										</select>
									</div> -->
								</div>

								<div class="row addSalaryField"></div>

								<div class="row">
									<div class="col-lg-12 form-group">
										<label for="deadline">@lang('Date of Work Assignment') <sup class="text--danger">*</sup></label><br>
										<input type="date" min="{{ date('Y-m-d') }}" name="deadline" value="{{old('deadline')}}" id="deadline" placeholder="@lang('Enter Application Deadline')" class="form--control">
									</div>
								</div>
								<div id="temp_job">
									<div class="row">
										<div class="col-lg-6 form-group">
											<label for="primary_contact">@lang('Contact Person') <sup class="text--danger">*</sup></label>
											<input type="text" name="primary_contact" id="primary_contact" value="{{old('primary_contact')}}" class="form--control" placeholder="@lang('Enter Primary Contact')" maxlength="255" required="">
										</div>
										<!-- <div class="col-lg-6 form-group">
											<label for="parking">@lang('Parking') <sup class="text--danger">*</sup></label>
											<input type="text" name="parking" id="parking" value="{{old('parking')}}" class="form--control" placeholder="@lang('Enter Parking')" maxlength="255" required="">
										</div>
										<div class="col-lg-6 form-group">
											<label for="radiography">@lang('Radiography') <sup class="text--danger">*</sup></label>
											<select class="form--control" name="radiography" id="radiography" required="">
												<option value="">@lang('Select Radiography')</option>
												@foreach($radiographies as $radiography)
													<option value="{{$radiography->id}}" @if(old('radiography') == $radiography->id) selected @endif>{{__($radiography->name)}}</option>
												@endforeach
											</select>
										</div>
										<div class="col-lg-6 form-group">
											<label for="ultrasonic">@lang('Ultrasonic') <sup class="text--danger">*</sup></label>
											<select class="form--control" name="ultrasonic" id="ultrasonic" required="">
												<option value="">@lang('Select Ultrasonic')</option>
												<option value="piezoelectric" @if(old('ultrasonic') == 'piezoelectric') selected @endif>@lang('Piezoelectric')</option>
												<option value="magnetostrictive" @if(old('ultrasonic') == 'magnetostrictive') selected @endif>@lang('Magnetostrictive')</option>
												<option value="both" @if(old('ultrasonic') == 'both') selected @endif>@lang('Both')</option>
											</select>
										</div>

										<div class="col-lg-6 form-group">
											<label for="avg_recall">@lang('Avg Recall') <sup class="text--danger">*</sup></label>
											<input type="text" name="avg_recall" id="avg_recall" value="{{old('avg_recall')}}" class="form--control" placeholder="@lang('Avg Recall')" required="">
										</div>

										<div class="col-lg-6 form-group">
											<label for="charting">@lang('Charting') <sup class="text--danger">*</sup></label>
											<select class="form--control" name="charting" id="charting" required="">
												<option value="" selected="">@lang('Select Charting')</option>
												@foreach($chartings as $charting)
													<option value="{{$charting->id}}" data-locations="{{json_encode($charting->location)}}">{{__($charting->name)}}</option>
												@endforeach
											</select>
										</div> -->
										<div class="col-lg-6 form-group">
											<label for="lunch_break">@lang('Lunch Break') <sup class="text--danger">*</sup></label>
											<!-- <input type="radio" name="lunch_break" id="lunch_break" value="{{old('lunch_break')}}" class="form--control" required=""> -->
											<select class="form--control" name="lunch_break" id="lunch_break" required="">
												<option value="" selected="">@lang('Select Lunch Break')</option>
												<option value="paid" @if(old('lunch_break') == 'paid') selected @endif>@lang('Paid')</option>
												<option value="unpaid" @if(old('lunch_break') == 'unpaid') selected @endif>@lang('Unpaid')</option>
											</select>
										</div>
									</div>
								</div>
								<div class="row">
								    <div class="col-lg-6 form-group">
											<label>@lang('Skills') <sup class="text--danger">*</sup></label>
											<select class="form--control select2" name="skill[]" multiple="multiple" required="">
													@foreach($skills as $skill)
															<option value="{{$skill->id}}" @if(@in_array($skill->id, @$user->skill)) selected @endif>{{__($skill->name)}}</option>
													@endforeach
											</select>
									</div>
									<div class="col-lg-6 form-group">
										<label for="software">@lang('Software') <sup class="text--danger">*</sup></label>
											<select class="form-control select2" name="software[]" multiple="multiple" required="">
												@foreach($softwares as $software)
														<option value="{{$software->id}}" @if(@in_array($software->id, @$software_id)) selected @endif>{{__($software->name)}}</option>
												@endforeach
										</select>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-6 form-group">
										<label for="hourly_rate">@lang('Hourly Rate') <sup class="text--danger">*</sup></label>
										<input type="text" name="hourly_rate" id="hourly_rate" value="{{old('hourly_rate')}}" placeholder="@lang('Enter Hourly Rate')" class="form--control" required="">
									</div>
								</div>
							</div>
						</div>


						<div class="custom--card mt-4">
							<div class="card-header bg--dark">
								<h5 class="text-white">@lang('Job Description')</h5>
							</div>
							<div class="card-body">
								<div class="row">

									<div class="col-lg-12 form-group">
										<label>@lang('Description') | Address | Information  <sup class="text--danger">*</sup></label>
										<textarea class="form--control nicEdit" name="description" rows="8" >{{old('description')}}</textarea>
									</div>

									<div class="col-lg-12 form-group">
										<label>@lang('Responsibilities') <sup class="text--danger">*</sup></label>
										<textarea class="form--control nicEdit" name="responsibilities" rows="8">{{old('responsibilities')}}</textarea>
									</div>

									<div class="col-lg-12 form-group">
										<label>@lang('Requirements') <sup class="text--danger">*</sup></label>
										<textarea class="form--control nicEdit" name="requirements" rows="8">{{old('requirements')}}</textarea>
									</div>
								</div>
							</div>
						</div>
					</div>

					<input type="text" name="job_cat_rate" id="jobcat_rate" value="{{old('job_cat_rate')}}" class="form--control" maxlength="255" hidden>
					<div class="text-end mt-4">
						<button type="submit" id="create_job" class="btn btn--base btn-block w-100"><i class="lab la-telegram-plane"></i> @lang('Post Job')</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="cat_rate" data-ignoreBackdropClick="true"  data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><center>Which Type of job you want to post</center></h5>
      </div>
      <div class="modal-body">
				<div class="row rate_d">
					<label for="cat_rate">@lang('Job Category Type') <sup class="text--danger">*</sup></label><br>
					<div class="row btn-group btn-group-toggle" data-toggle="buttons">
						<div class="col-md-12 form-group" style="text-align:center">
							<label class="btn btn--base btn-block active">
					   		<input type="radio" class="job_cat_rate" name="cat_rate" id="full_timerate" autocomplete="off" style="display:contents" value="full_timerate"> Permanent
					   	</label>
							<label class="btn btn--base btn-block active">
						   	<input type="radio" class="job_cat_rate" name="cat_rate" id="temp_rate" autocomplete="off" style="display:contents" value="temp_rate"> Temporary
							 </label>
						</div>
					</div>
				</div>
				<div class="row select_cat" style="margin-left: 5px; display:none">
					<br>
					<h3 for="cat_rate">@lang('Which Temp Would You Like to Find?') <sup class="text--danger">*</sup></h3>
					<br>
					<div class="row btn-group btn-group-toggle" data-toggle="buttons">
						<span class="required_error" style="color:red;display:none">Please select one of these:</span>
						@foreach($categorys as $category)
							<div class="col-md-6 form-group">
								<label class="radio-inline" style="color:gray;font-size:14px;line-height:2;">
	              	<input type="radio" name="category" class="category_option" id="{{$category->id}}" @if(old('category') == $category->id) checked @endif value="{{$category->id}}" required > {{ucfirst($category->name)}}
	            	</label>
							</div>
						@endforeach
					</div>
					<center>
						<button type="button" id="ok_btn" class="btn btn--base btn-block">OK</button>
						<a href="{{route('employer.home')}}" id="cancel_btn" class="btn btn--base btn-block">Cancel</a>
					</center>
				</div>
      </div>
    </div>
  </div>
</div>
<!-- Modal -->
<div class="modal fade" id="temp_job_modal" tabindex="-1" role="dialog" aria-labelledby="temp_job_modalTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content" style="border-radius:1.3em;">
      <div class="modal-header" style="border:none;">
      </div>
      <div class="modal-body">
        <center>
              <h2>We made hiring easy!<br/></h2>
				<div class="modal-title"></div>
					<span style="color:gray;font-size:16px;line-height:2;">Our Placement Fee is billed after  successfully<br/>Approving our motivated candidates to work.<br/>Would you like to enter information now?</span>
		</center>
				<br/>
				<center>
					<button type="button" id="ready_btn" onclick="hidemodal()" class="btn btn--base btn-block" >I'm ready</button>
					<a href="{{route('employer.home')}}" id="cancel_btn" class="btn btn--base btn-block">Cancel</a>
				</center>
      </div>
      <div class="modal-footer" style="border:none;">
      </div>
    </div>
  </div>
</div>
@endsection

@push('style-lib')
    <link rel="stylesheet" href="{{asset($activeTemplateTrue.'css/select2.min.css')}}">
@endpush

@push('script-lib')
    <script src="{{asset($activeTemplateTrue.'js/nicEdit.js')}}"></script>
    <script src="{{asset($activeTemplateTrue.'js/select2.min.js') }}"></script>
@endpush

@push('script')
<script>
	"use strict";
	$(document).ready(function() {
		$('#cat_rate').modal({
		  keyboard: false,
		  ignoreBackdropClick:true,
		  backdrop:'static',
		})
	  	$('#cat_rate').modal('show');
  	});
	$('.job_cat_rate').click(function() {
        var rate = $("input[name=cat_rate]:checked").val();
        if(rate == 'full_timerate')
        {
        	$('#permanent_job').css('display','block');
        	$('#temp_job').css('display','none');
        	$('#temp_job input, #temp_job select').removeAttr('required');
					$('#jobcat_rate').val(rate);
	        $('#cat_rate').modal('hide');
        }
        else if(rate == 'temp_rate')
				{
					$('#jobcat_rate').val(rate);
					$('#cat_rate .modal-header').css('display','none');
					$('.rate_d').css('display','none');
					$('.select_cat').css('display','block');
				//	$('#temp_job_modal').modal('show');
				}
  });
	$('.category_option').click(function()
	{
		$('.required_error').hide();
	});
	$('#ok_btn').click(function() {
		var category = $("input[name=category]:checked").val();
		if(category == undefined)
		{
			$('.required_error').show();
		}
		else
		{
			$('#temp_job').css('display','block');
			$('#ok_btn').html('<i class="fa fa-spinner fa-spin"></i>');
			$('select[name="category"]').val(category);
			$('#cat_rate').modal('hide');
			$('#temp_job_modal').modal('show');
		}
	});

	bkLib.onDomLoaded(function() {
        $( ".nicEdit" ).each(function( index ) {
            $(this).attr("id","nicEditor"+index);
            new nicEditor({fullPanel : true}).panelInstance('nicEditor'+index,{hasPanel : true});
        });
    });

    $('select[name=city]').on('change',function() {
        $('select[name=location]').html('<option value="" selected="" disabled="">@lang('Select One')</option>');
        var locations = $('select[name=city] :selected').data('locations');
        var html = '';
        locations.forEach(function myFunction(item, index) {
            html += `<option value="${item.id}">${item.name}</option>`
        });
        $('select[name=location]').append(html);
    });

    // $('#salary_type').on('change', function(){
    // 	var value = $('#salary_type').val();
    // 	if(value == 2){
    // 		var html = `<div class="col-lg-6">
		// 					<div class="form-group">
		// 						<label for="salary_from">@lang('Salary From') <sup class="text--danger">*</sup></label>
		// 						 <div class="input-group">
		//                             <input id="salary_from" type="text" class="form--control" name="salary_from" placeholder="@lang('Enter Amount')" required>
		//                             <span class="input-group-text">{{__($general->cur_text)}}</span>
		//                         </div>
		// 					</div>
		// 				</div>
		// 				<div class="col-lg-6">
		// 					<div class="form-group">
		// 						<label for="salary_to">@lang('Salary To') <sup class="text--danger">*</sup></label>
		// 						<div class="input-group">
		//                             <input id="salary_to" type="text" class="form--control" name="salary_to" placeholder="@lang('Enter Amount')" required>
		//                             <span class="input-group-text">{{__($general->cur_text)}}</span>
		//                         </div>
		// 					</div>
		// 				</div>`;
    // 		$(".addSalaryField").append(html);
    // 	}else{
    // 		$(".addSalaryField").empty();
    // 	}
    // });
		function hidemodal()
		{
			$('#temp_job_modal').modal('hide');
		}
		'use strict';
		$('.select2').select2({
				tags: true,
				maximumSelectionLength : 15
		});
</script>
@endpush