@extends($activeTemplate.'layouts.frontend')
@section('content')
@push('style')
<style>
input[type="date"]
{
  display:block;
  -webkit-appearance: textfield;
  -moz-appearance: textfield;
  min-height: 1.2em;
}
select
{
 text-indent:5%;
 line-height:38px;
 padding-left:0 !important;
}
</style>
@endpush
<div class="pt-50 pb-50 section--bg">
	<div class="container">
		<div class="row justify-content-center gy-4">
			<!-- @include($activeTemplate . 'partials.employer_sidebar') -->
			<div class="col-xl-12 ps-xl-4">
				<form action="{{route('employer.profile.submit')}}" method="POST" enctype="multipart/form-data" class="edit-profile-form">
					@csrf
					<div class="profile-thumb-wrapper">
						<div class="profile-thumb">
							<div class="avatar-preview">
								<div class="profilePicPreview" style="background-image:url('{{getImage(imagePath()['employerLogo']['path'].'/'.$employer->image)}}');"></div>
							</div>
							<div class="avatar-edit ps-4">
								<input type='file' name="logo" class="profilePicUpload" id="profilePicUpload1" accept=".png, .jpg, .jpeg" />
								<label for="profilePicUpload1" class="btn btn--base">@lang('Select Company Logo')</label>
                                <p class="fs--14px mb-3"> @lang('Supported files are .jpg, .png, .jpeg')</p>
                                <!--p style="color:#7367f0"><strong>Employer #: 80001 </strong></p-->
                                @if(!empty($avg))
                                    {!!get_rating($avg)!!}
                                @endif
                                @if(!empty($medal))
                                  @if($medal == 'Bronze')
                                  <img src="{{getImage(imagePath()['badge']['path'].'/61ef58fdf0f201643075837.png')}}" style="object-fit: cover;width: 35px;height: 35px;border-radius: 50%;position: relative;" alt="@lang('logo')">
                                  @elseif($medal == 'Silver')
                                  <img src="{{getImage(imagePath()['badge']['path'].'/61ef58d31854b1643075795.png')}}" style="object-fit: cover;width: 35px;height: 35px;border-radius: 50%;position: relative;" alt="@lang('logo')">
                                  @elseif($medal == 'Gold')
                                  <img src="{{getImage(imagePath()['badge']['path'].'/61ef589c8e98a1643075740.png')}}" style="object-fit: cover;width: 35px;height: 35px;border-radius: 50%;position: relative;" alt="@lang('logo')">
                                  @endif
                                @endif
							</div>
						</div>
					</div>

					<div class="custom--card mt-4">
						<div class="card-header bg--dark">
							<h5 class="text-white">@lang('Company Information')</h5>
						</div>
						<div class="card-body">
							<div class="row">
								<div class="col-lg-6 form-group">
									<label for="company_name">@lang('Company Name') <sup class="text--danger">*</sup></label>
									<div class="custom-icon-field">
										<i class="las la-briefcase"></i>
										<input type="text" name="company_name" id="company_name" value="{{$employer->company_name}}" class="form--control" placeholder="@lang('Enter company name')" maxlength="40" required="">
									</div>
								</div>
								<div class="col-lg-6 form-group">
									<label for="ceo_name">@lang('Contact Person') <sup class="text--danger">*</sup></label>
									<div class="custom-icon-field">
										<i class="las la-user"></i>
										<input type="text" name="ceo_name" id="ceo_name" value="{{$employer->ceo_name}}" class="form--control" placeholder="@lang('Enter Company CEO name')" maxlength="40" required="">
									</div>
								</div>
								<div class="col-lg-6 form-group">
									<label for="email">@lang('Email') <sup class="text--danger">*</sup></label>
									<div class="custom-icon-field">
										<i class="las la-envelope"></i>
										<input type="email" name="email" id="email" class="form--control" value="{{$employer->email}}" placeholder="@lang('Enter Email')" maxlength="40" required="">
									</div>
								</div>

								<div class="col-lg-6 form-group">
									<label for="mobile">@lang('Texting Phone #') <sup class="text--danger">*</sup></label>
										<div class="custom-icon-field" style="display:flex;">
											<select name="mobile_code" id="mobile_code" class="select" required="" style="min-height:40px;width:35%;text-indent:0">
												@foreach($countries as $key => $country)
												<option data-mobile_code="{{ $country->dial_code }}" value="{{ $country->dial_code }}" data-code="{{ $key }}" @if($employer->mobile_code == $country->dial_code) selected @endif >+{{ __($country->dial_code) }}</option>
												@endforeach
											</select>
												<input type="text" name="mobile" id="mobile" class="form--control" value="{{$employer->mobile}}" placeholder="@lang('Enter phone number')" required="">
										</div>
								</div>

								<div class="col-lg-6 form-group">
									<label for="website">@lang('Website')</label>
									<div class="custom-icon-field">
										<i class="las la-link"></i>
										<input type="url" name="website" id="website" class="form--control"  value="{{$employer->website}}" placeholder="@lang('https://demowebsite.com')">
									</div>
								</div>

								<div class="col-lg-6 form-group">
									<label for="fax">@lang('Office #')</label>
									<div class="custom-icon-field">
										<i class="las la-fax"></i>
										<input type="text" name="fax" id="fax" maxlength="40" class="form--control" value="{{@$employer->fax}}" placeholder="@lang('Enter company fax')">
									</div>
								</div>


								<div class="col-lg-6 form-group">
								    <label for="industry">@lang('Industry') <sup class="text--danger">*</sup></label>
								    <div class="custom-icon-field">
                                        <i class="las la-graduation-cap"></i>
    									<select class="form--control" name="industry" id="industry" required="">
    										<option value="">@lang('Select One')</option>
    										@foreach($industries as $industry)
    											<option value="{{$industry->id}}" @if($industry->id == $employer->industry_id) selected @endif>{{__($industry->name)}}</option>
    										@endforeach
    									</select>
    								</div>
								</div>


								<div class="col-lg-6 form-group">
									<label for="number_of_employees">@lang('Number Of Employees') <sup class="text--danger">*</sup></label>
									 <div class="custom-icon-field">
                                        <i class="las la-user"></i>
    									<select class="form--control" name="number_of_employees" id="number_of_employees" required="">
    										<option value="">@lang('Select One')</option>
    										@foreach($numberOfEmployees as $employee)
    											<option value="{{$employee->id}}" @if($employee->id == $employer->number_of_employees_id) selected @endif>{{__($employee->employees )}}</option>
    										@endforeach
    									</select>
    								</div>
								</div>


								<div class="col-lg-6 form-group">
									<label for="address">@lang('Address') <sup class="text--danger">*</sup></label>
									<div class="custom-icon-field">
										<i class="las la-map-marker-alt"></i>
										<input type="text" name="address" id="address" class="form--control" value="{{@$employer->address->address}}" placeholder="@lang('Enter company address')" required="">
									</div>
								</div>

								<div class="col-lg-6 form-group">
									<label for="state">@lang('Province') <sup class="text--danger">*</sup></label>
									<div class="custom-icon-field">
										<i class="las la-map-signs"></i>
										<input type="text" name="state" id="state" value="{{@$employer->address->state}}" class="form--control " placeholder="@lang('Enter Province')" required="" >
									</div>
								</div>
								<div class="col-lg-6 form-group">
									<label for="city">@lang('City') <sup class="text--danger">*</sup></label>
									<div class="custom-icon-field">
										<i class="las la-map-pin"></i>
										<input type="text" name="city" id="city" value="{{@$employer->address->city}}" class="form--control " placeholder="@lang('Enter City')" required=""  >
									</div>
								</div>
								<div class="col-lg-6 form-group">
										<label for="city">@lang('Country') <sup class="text--danger">*</sup></label>
										<div class="custom-icon-field">
												<i class="las la-map-pin"></i>
												<input type="text" name="country" id="country" value="{{@$employer->address->country}}" class="form--control " placeholder="@lang('Enter Country')" required="" >
												<input type="hidden" name="country_code" id="country_code" value="{{@$user->country_code}}" class="form--control" required="">
										</div>
								</div>
								<div class="col-lg-12 form-group">
									<label for="zip">@lang('Postal Code') <sup class="text--danger">*</sup></label>
									<div class="custom-icon-field">
										<i class="las la-location-arrow"></i>
										<input type="text" name="zip" id="zip" value="{{@$employer->address->zip}}" class="form--control" placeholder="@lang('Enter Postal Code')" required="" >
									</div>
								</div>
							</div>
						</div>
					</div>


					<div class="custom--card mt-4">
						<div class="card-header bg--dark">
							<h5 class="text-white">@lang('Company Description') <sup class="text--danger">*</sup></h5>
						</div>
						<div class="card-body">
							<div class="row">
								<div class="col-lg-12 form-group">
									<textarea class="form--control nicEdit" name="description" rows="8">@php echo $employer->description @endphp</textarea>
								</div>
							</div>
						</div>
					</div>

					<div class="custom--card mt-4">
						<div class="card-header bg--dark">
							<h5 class="text-white">@lang('Company Social Links')</h5>
						</div>
						<div class="card-body">
							<div class="row">
								<div class="col-lg-6 form-group">
									<label for="facebook">@lang('Facebook')</label>
									<div class="custom-icon-field">
										<i class="lab la-facebook-f"></i>
										<input type="url" name="facebook" class="form--control" value="{{@$employer->socialMedia->facebook}}" placeholder="@lang('https://facebook.com/demo')">
									</div>
								</div>
								<div class="col-lg-6 form-group">
									<label for="twitter">@lang('Twitter')</label>
									<div class="custom-icon-field">
										<i class="lab la-twitter"></i>
										<input type="url" name="twitter" class="form--control" value="{{@$employer->socialMedia->twitter}}" placeholder="@lang('https://twitter.com/demo')">
									</div>
								</div>
								<div class="col-lg-6 form-group">
									<label for="pinterest">@lang('Pinterest')</label>
									<div class="custom-icon-field">
										<i class="lab la-pinterest-p"></i>
										<input type="url" name="pinterest" class="form--control" value="{{@$employer->socialMedia->pinterest}}" placeholder="@lang('https://pinterest.com/demo')">
									</div>
								</div>
								<div class="col-lg-6 form-group">
									<label for="linkedin">@lang('Linkedin')</label>
									<div class="custom-icon-field">
										<i class="lab la-linkedin-in"></i>
										<input type="url" name="linkedin" class="form--control" value="{{@$employer->socialMedia->linkedin}}" placeholder="@lang('https://linkedin.com/in/demo')">
									</div>
								</div>
							</div><!-- row end -->
						</div>
					</div>

					<div class="text-end mt-4">
						<button type="submit" class="btn btn--base"><i class="las la-upload fs--18px"></i> @lang('Update Profile')</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
@endsection


@push('script-lib')
 	<script src="{{asset($activeTemplateTrue.'js/nicEdit.js')}}"></script>
@endpush

@push('script')
<script src="https://maps.googleapis.com/maps/api/js?key={{$googlemapkey}}&callback=initAutocomplete&libraries=places&v=weekly" async></script>
<script>
let autocomplete;
let address1Field;
let address2Field;
let postalField;
function initAutocomplete()
{
  address1Field = document.querySelector("#address");
  autocomplete = new google.maps.places.Autocomplete(address1Field, {
    // componentRestrictions: { country: ["us", "ca"] },
    fields: ["address_components", "geometry"],
    types: ["address"],
  });
  autocomplete.addListener("place_changed", fillInAddress);
}
function fillInAddress() {
  const place = autocomplete.getPlace();
  let address1 = "";
  let postcode = "";
  for (const component of place.address_components) {
    const componentType = component.types[0];
    switch (componentType) {
      case "street_number": {
        address1 = `${component.long_name} ${address1}`;
        break;
      }
      case "route": {
        address1 += component.short_name;
        break;
      }
      case "postal_code": {
        postcode = `${component.long_name}${postcode}`;
        break;
      }
      case "postal_code_suffix": {
        postcode = `${postcode}-${component.long_name}`;
        break;
      }
      case "locality":
        document.querySelector("#city").value = component.long_name;
        break;
      case "administrative_area_level_1": {
        document.querySelector("#state").value = component.short_name;
        break;
      }
      case "country":
        document.querySelector("#country").value = component.long_name;
         document.querySelector("#country_code").value = component.short_name;
        break;
    }
  }
  $('#address').val(address1);
  $('#zip').val(postcode);
}
	"use strict";
	function proPicURL(input) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function(e) {
				var preview = $(input).parents('.profile-thumb').find('.profilePicPreview');
				$(preview).css('background-image', 'url(' + e.target.result + ')');
				$(preview).addClass('has-image');
				$(preview).hide();
				$(preview).fadeIn(650);
			}
			reader.readAsDataURL(input.files[0]);
		}
	}
	$(".profilePicUpload").on('change', function() {
		proPicURL(this);
	});

	$(".remove-image").on('click', function(){
		$(".profilePicPreview").css('background-image', 'none');
		$(".profilePicPreview").removeClass('has-image');
	});

	bkLib.onDomLoaded(function() {
        $( ".nicEdit" ).each(function( index ) {
            $(this).attr("id","nicEditor"+index);
            new nicEditor({fullPanel : true}).panelInstance('nicEditor'+index,{hasPanel : true});
        });
    });

    $( document ).on('mouseover ', '.nicEdit-main,.nicEdit-panelContain',function(){
        $('.nicEdit-main').focus();
    });
</script>
@endpush
