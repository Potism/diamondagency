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
  text-indent:35px;
  line-height:38px;
  padding-left:0 !important;
}
</style>
<div class="pt-50 pb-50 section--bg">
  <div class="container">
    <div class="row justify-content-center gy-4">
      <!-- @include($activeTemplate . 'partials.user_sidebar') -->
      <div class="col-xl-12 ps-xl-4">
        <form action="{{route('user.profile.setting')}}" method="POST" enctype="multipart/form-data" class="edit-profile-form">
          @csrf
          <div class="profile-thumb-wrapper">
            <div class="profile-thumb">
              <div class="avatar-preview">
                <div class="profilePicPreview" style="background-image:url('{{getImage(imagePath()['profile']['user']['path'].'/'.@$user->image)}}');"></div>
              </div>
              <div class="avatar-edit ps-4">
                <input type='file' name="image" class="profilePicUpload" id="profilePicUpload1" accept=".png, .jpg, .jpeg" @if(@$user->image == '') required="" @endif/>
                <label for="profilePicUpload1" class="btn btn--base">@lang('Select Profile Image')</label>
                <p class="fs--14px mb-3"> @lang('Supported files files are .jpg, .png, .jpeg')</p>
                <p>Employee #: {{$user->employee_id}}</p>
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
              <h5 class="text-white">@lang('Basic Information')</h5>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-lg-6 form-group">
                  <label for="firstname">@lang('First Name') <sup class="text--danger">*</sup></label>
                  <div class="custom-icon-field">
                    <i class="las la-briefcase"></i>
                    <input type="text" name="firstname" id="firstname" value="{{$user->firstname ?? session()->get('firstname') }}" class="form--control" placeholder="@lang('Enter First Name')" maxlength="40" required="">
                  </div>
                </div>
                <div class="col-lg-6 form-group">
                  <label for="lastname">@lang('Last Name') <sup class="text--danger">*</sup></label>
                  <div class="custom-icon-field">
                    <i class="las la-user"></i>
                    <input type="text" name="lastname" id="lastname" value="{{$user->lastname ?? session()->get('lastname')}}" class="form--control" placeholder="@lang('Enter last name')" maxlength="40" required="">
                  </div>
                </div>

                <div class="col-lg-6 form-group">
                  <label for="email">@lang('Email') <sup class="text--danger">*</sup></label>
                  <div class="custom-icon-field">
                    <i class="las la-envelope"></i>
                    <input type="email" name="email" id="email" class="form--control" value="{{$user->email ?? session()->get('email')}}" placeholder="@lang('Enter Email')" required="" maxlength="40">
                  </div>
                </div>

                <div class="col-lg-6 form-group">
                  <label for="mobile">@lang('Phone') <sup class="text--danger">*</sup></label>
                  <div class="custom-icon-field" style="display:flex;">
                    <select name="mobile_code" id="mobile_code" class="select" required="" style="min-height:40px;width:35%;text-indent:0">
                      @foreach($countries as $key => $country)
                      <option data-mobile_code="{{ $country->dial_code }}" value="{{ $country->dial_code }}" data-code="{{ $key }}" @if($user->mobile_code == $country->dial_code) selected @endif >+{{ __($country->dial_code) }}</option>
                      @endforeach
                    </select>
                    <input type="text" name="mobile" id="mobile" class="form--control" value="{{$user->mobile ?? session()->get('mobile')}}" placeholder="@lang('Enter phone number')" required="">
                  </div>
                </div>

                <div class="col-lg-6 form-group">
                  <label for="designation">@lang('Position / Title') <sup class="text--danger">*</sup></label>
                  <div class="custom-icon-field">
                    <i class="las la-graduation-cap"></i>
                    <select class="form--control" name="designation" id="designation" required="">
                      <option value="">@lang('Select One')</option>
                      @foreach($designation as $design)
                      <option value="{{$design->id}}" @if($design->id == (@$user->designation ?? session()->get('designation'))) selected @endif>{{__($design->name)}}</option>
                      @endforeach
                    </select>
                  </div>
                </div>


                <div class="col-lg-6 form-group">
                  <label for="gender">@lang('Gender') <sup class="text--danger">*</sup></label>
                  <div class="custom-icon-field">
                    <i class="las la-user"></i>
                    <select class="form--control" id="gender" name="gender" required="">
                      <option value="">@lang('Select One')</option>
                      <option value="1" @if($user->gender == 1) selected @endif @if(session()->get('gender') == 1) selected @endif>@lang('Male')</option>
                      <option value="2" @if($user->gender == 2) selected @endif @if(session()->get('gender') == 2) selected @endif>@lang('Female')</option>
                    </select>
                  </div>
                </div>
                <div class="col-lg-6 form-group">
                  <label for="date_birth">@lang('Date Of Birth') <!--sup class="text--danger">*</sup--></label>
                  <div class="custom-icon-field">
                    <i class="las la-calendar-plus"></i>
                    <input type="date" id="date_birth" name="birth_date" value="{{showDateTime(($user->birth_date ?? session()->get('birth_date')), 'Y-m-d')}}"  placeholder="@lang('Enter Date Of Birth')" autofocus="off" class="form--control" >
                  </div>
                </div>

                <div class="col-lg-6 form-group">
                  <label for="national_id">@lang('Work Status') <sup class="text--danger">*</sup></label>
                  <div class="custom-icon-field">
                    <i class="las la-suitcase"></i>
                    <select class="form--control" name="national_id" id="national_id" required="">
                      <option value="">@lang('Select One')</option>
                      <option value="Contract Worker" @if($user->national_id == "Contract Worker") selected @endif @if(session()->get('national_id') == "Contract Worker") selected @endif>@lang('Contract Worker')</option>
                      <option value="Currently Employed" @if($user->national_id == "Currently Employed") selected @endif @if(session()->get('national_id') == "Currently Employed") selected @endif >@lang('Currently Employed')</option>
                      <option value="Unemployed" @if($user->national_id == "Unemployed") selected @endif @if(session()->get('national_id') == "Unemployed") selected @endif >@lang('Unemployed')</option>
                      <!--option value="">@lang('Select One')</option>
                      <option value="Work Permit" @if($user->national_id == "Work Permit") selected @endif @if(session()->get('national_id') == "Work Permit") selected @endif>@lang('Work Permit')</option>
                      <option value="Permanent Resident" @if($user->national_id == "Permanent Resident") selected @endif @if(session()->get('national_id') == "Permanent Resident") selected @endif >@lang('Permanent Resident')</option>
                      <option value="Citizen" @if($user->national_id == "Citizen") selected @endif @if(session()->get('national_id') == "Citizen") selected @endif >@lang('Citizen')</option-->
                    </select>

                  </div>
                </div>
                <div class="col-lg-6 form-group">
                  <label for="married">@lang('Rate') <sup class="text--danger">*</sup></label>
                  <select class="form--control" name="candidate_rate" id="candidate_rate" required="" style="text-indent:5px;">
                    <option value="">@lang('Select One')</option>
                    @for ($i = 1; $i <= 400; $i++)
                    <option value="{{$i}}" @if($user->candidate_rate == $i) selected @endif @if(session()->get('candidate_rate') == $i) selected @endif>{{$general->cur_sym}} {{$i}}</option>
                    @endfor
                  </select>
                </div>

                <div class="col-lg-6 form-group">
                  <label for="address">@lang('Address') <sup class="text--danger">*</sup></label>
                  <div class="custom-icon-field">
                    <i class="las la-map-marker-alt"></i>
                    @php
                    if($user->address->address != '')
                    {
                      $address = $user->address->address;
                    }
                    else
                    {
                      $address =  session()->get('address');
                    }
                    @endphp
                    <input type="text" name="address" id="address" class="form--control" value="{{$address}}" placeholder="@lang('Enter your address')" required="">
                  </div>
                </div>

                <div class="col-lg-6 form-group">
                  <label for="state">@lang('Province') <sup class="text--danger">*</sup></label>
                  <div class="custom-icon-field">
                    <i class="las la-map-signs"></i>
                    @php
                    if($user->address->state != '')
                    {
                      $state = $user->address->state;
                    }
                    else
                    {
                      $state =  session()->get('state');
                    }
                    @endphp
                    <input type="text" name="state" id="state" value="{{$state}}" class="form--control " placeholder="@lang('Enter Province')" required="" >
                  </div>
                </div>
                <div class="col-lg-6 form-group">
                  <label for="city">@lang('City') <sup class="text--danger">*</sup></label>
                  <div class="custom-icon-field">
                    <i class="las la-map-pin"></i>
                    @php
                    if($user->address->city != '')
                    {
                      $city = $user->address->city;
                    }
                    else
                    {
                      $city =  session()->get('city');
                    }
                    @endphp
                    <input type="text" name="city" id="city" value="{{@$city}}" class="form--control " placeholder="@lang('Enter City')" required="" >
                  </div>
                </div>
                <div class="col-lg-6 form-group">
                  <label for="city">@lang('Country') <sup class="text--danger">*</sup></label>
                  <div class="custom-icon-field">
                    <i class="las la-map-pin"></i>
                    @php
                    if($user->address->country != '')
                    {
                      $country = $user->address->country;
                    }
                    else
                    {
                      $country =  session()->get('country');
                    }
                    @endphp
                    <input type="text" name="country" id="country" value="{{$country}}" class="form--control " placeholder="@lang('Enter Country')" required="" >
                    <input type="hidden" name="country_code" id="country_code" value="{{@$user->country_code}}" class="form--control" required="">
                  </div>
                </div>
                <div class="col-lg-6 form-group">
                  <label for="zip">@lang('Postal Code') <sup class="text--danger">*</sup></label>
                  <div class="custom-icon-field">
                    <i class="las la-location-arrow"></i>
                    @php
                    if($user->address->zip != '')
                    {
                      $zip = $user->address->zip;
                    }
                    else
                    {
                      $zip =  session()->get('zip');
                    }
                    @endphp
                    <input type="text" name="zip" id="zip" value="{{$zip}}" class="form--control" placeholder="@lang('Enter Postal Code')" required="" >
                  </div>
                </div>
              </div>
            </div>
          </div>


          <div class="custom--card mt-4">
            <div class="card-header bg--dark">
              <h5 class="text-white">@lang('Software And Language')</h5>
            </div>

            <div class="card-body">
              <div class="row">
                <div class="col-lg-6 form-group">
                  <label for="language">@lang('Software') <sup class="text--danger">*</sup></label>
                  <select class="form--control select2" name="software_id[]" multiple="multiple" required="">
                    @foreach($softwares as $software)
                    @php
                    $soft_id = '';
                    if(!empty(session()->get('software_id')))
                    {
                      $soft_id = implode(',',session()->get('software_id'));
                    }
                    @endphp
                    <option value="{{$software->id}}" @if(@in_array($software->id, @$software_id)) selected @endif @if(@in_array($software->id, $soft_id)) selected @endif>{{__($software->name)}}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-lg-6 form-group">
                  <label for="language">@lang('Language') <sup class="text--danger">*</sup></label>
                  <select class="form--control select2" name="language[]" multiple="multiple" required="">
                    @if(!empty($user->language))
                    @foreach (array_unique($user->language) as  $value)
                    <option value="{{$value}}" selected="true">{{ $value }}</option>
                    @endforeach
                    @endif
                    @if(!empty($user->language))
                    @foreach (array_unique($user->language) as  $value)
                    <option value="{{$value}}" selected="true">{{ $value }}</option>
                    @endforeach
                    @endif
                    @include('partials.language')
                  </select>
                </div>

              </div>

              <div class="form-group">
                <label for="desciption">@lang('Career Summary') <sup class="text--danger">*</sup></label>
                <textarea class="form--control" rows="5" name="detail" placeholder="@lang('Enter Career Summary')" required="">{{$user->details ?? session()->get('detail')}}</textarea>
              </div>

            </div>
          </div>
<!--HIDE 
          <div class="custom--card mt-4">
            <div class="card-header bg--dark">
              <h5 class="text-white">@lang('Social Links')</h5>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-lg-6 form-group">
                  <label for="facebook">@lang('Facebook')</label>
                  <div class="custom-icon-field">
                    <i class="lab la-facebook-f"></i>
                    <input type="url" name="facebook" class="form--control" value="{{@$user->socialMedia->facebook ?? session()->get('facebook')}}" placeholder="@lang('https://facebook.com/demo')">
                  </div>
                </div>
                <div class="col-lg-6 form-group">
                  <label for="linkedin">@lang('Linkedin')</label>
                  <div class="custom-icon-field">
                    <i class="lab la-linkedin-in"></i>
                    <input type="url" name="linkedin" class="form--control" value="{{@$user->socialMedia->linkedin ?? session()->get('linkedin')}}" placeholder="@lang('https://linkedin.com/in/demo')">
                  </div>
                </div>
              </div>
            </div>
          </div>
 -->
          <div class="custom--card mt-4">
            <div class="card-header bg--dark">
              <h5 class="text-white">@lang('Character References')</h5>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-lg-4 form-group">
                  <label for="linkedin">@lang('Name')<sup class="text--danger">*</sup></label>
                  <div class="custom-icon-field" style="padding: 5px 0;">
                    <i class="las la-user"></i>
                    <input type="text" name="name1" class="form--control" value="{{@$user->references->name1 ?? session()->get('name1')}}" placeholder="@lang('Name')">
                  </div>
                </div>
                <div class="col-lg-4 form-group">
                  <label for="linkedin">@lang('Contact Information')<sup class="text--danger">*</sup></label>
                  <div class="custom-icon-field" style="padding: 5px 0;">
                    <i class="las la-phone"></i>
                    <input type="text" name="contact1" class="form--control" value="{{@$user->references->contact1 ?? session()->get('contact1')}}" placeholder="@lang('Contact')">
                  </div>
                </div>
                <div class="col-lg-4 form-group">
                  <label for="linkedin"><!--@lang('Email')<sup class="text--danger">*</sup-->&nbsp;</label>
                  <div class="custom-icon-field" style="padding: 5px 0;">
                    <i class="las la-envelope"></i>
                    <input type="email" name="email1" class="form--control" value="{{@$user->references->email1 ?? session()->get('email1')}}" placeholder="@lang('Email')">
                  </div>
                </div>
                <div class="col-lg-4 form-group">
                  <label for="linkedin">@lang('Name')</label>
                  <div class="custom-icon-field" style="padding: 5px 0;">
                    <i class="las la-user"></i>
                    <input type="text" name="name2" class="form--control" value="{{@$user->references->name2 ?? session()->get('name2')}}" placeholder="@lang('Name')">
                  </div>
                </div>
                <div class="col-lg-4 form-group">
                  <label for="linkedin">@lang('Contact Information')<!--sup class="text--danger">*</sup--></label>
                  <div class="custom-icon-field" style="padding: 5px 0;">
                    <i class="las la-phone"></i>
                    <input type="text" name="contact2" class="form--control" value="{{@$user->references->contact2 ?? session()->get('contact2')}}" placeholder="@lang('Contact')">
                  </div>
                </div>
                <div class="col-lg-4 form-group">
                  <label for="linkedin"><!--@lang('Email')<sup class="text--danger">*</sup-->&nbsp; </label>
                  <div class="custom-icon-field" style="padding: 5px 0;">
                    <i class="las la-envelope"></i>
                    <input type="email" name="email2" class="form--control" value="{{@$user->references->email2 ?? session()->get('email2')}}" placeholder="@lang('Email')">
                  </div>
                </div>
                <div class="col-lg-4 form-group">
                  <label for="linkedin">@lang('Name')<!--sup class="text--danger">*</sup--></label>
                  <div class="custom-icon-field" style="padding: 5px 0;">
                    <i class="las la-user"></i>
                    <input type="text" name="name3" class="form--control" value="{{@$user->references->name3 ?? session()->get('name3')}}" placeholder="@lang('Name')">
                  </div>
                </div>
                <div class="col-lg-4 form-group">
                  <label for="linkedin">@lang('Contact Information')<!--sup class="text--danger">*</sup--></label>
                  <div class="custom-icon-field" style="padding: 5px 0;">
                    <i class="las la-phone"></i>
                    <input type="text" name="contact3" class="form--control" value="{{@$user->references->contact3 ?? session()->get('contact3')}}" placeholder="@lang('Contact')">
                  </div>
                </div>
                <div class="col-lg-4 form-group">
                  <label for="linkedin"><!--@lang('Email')<sup class="text--danger">*</sup-->&nbsp;</label>
                  <div class="custom-icon-field" style="padding: 5px 0;">
                    <i class="las la-envelope"></i>
                    <input type="email" name="email3" class="form--control" value="{{@$user->references->email3 ?? session()->get('email3')}}" placeholder="@lang('Email')">
                  </div>
                </div>
              </div>
            </div><!-- row end -->
          </div>
          <div class="custom--card mt-4">
            <div class="card-header bg--dark">
              <h5 class="text-white">@lang('Fill in the blanks (All answers are acceptable)') </h5>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-lg-12 form-group">
                  <ul>
                    <li>
                      <label>1). @lang('The World Is A ')<input type="text" name="answer1" value="{{@$user->answers->answer1 ?? session()->get('answer1')}}"> @lang(' Place, Full Of ')<input type="text" name="answer2" value="{{@$user->answers->answer2 ?? session()->get('answer2')}}"> @lang(' People')</label>
                    </li>
                    <li>
                      <label>2). @lang('One challange I\'ve overcome is ')<input type="text" name="answer3" value="{{@$user->answers->answer3 ?? session()->get('answer3')}}" style="width:auto"></label>
                    </li>
                    <li>
                      <label>3). @lang('One personal or professional failure I\'ve had is ')<input type="text" name="answer4" value="{{@$user->answers->answer4 ?? session()->get('answer4')}}" style="width:auto"></label>
                    </li>
                    <li>
                      <label>4). @lang('One thing I still feel vulnerable about is ')<input type="text" name="answer5" value="{{@$user->answers->answer5 ?? session()->get('answer5')}}" style="width:auto"></label>
                    </li>
                  </ul>
                </div>
              </div><!-- row end -->
            </div>
          </div>

          <div class="custom--card mt-4">
            <div class="card-header bg--dark">
              <h5 class="text-white">@lang('Bank Information')</h5>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-lg-12 form-group">
                  <ul>
                    <li style="margin-bottom:20px;">
                      <div class="row"><div class="col-md-2"><label>@lang('Email for E-transfer:')</label></div><div class="col-md-6"><input type="text" name="accountno" value="{{@$user->accountno ?? session()->get('accountno')}}" class="form--control"></div></div>
                    </li>
                    <li>
                      <div class="row"><div class="col-md-2"><label>@lang('Upload Void Cheque')</label></div><div class="col-md-6"><input class="form--control" name="upload_pad" type="file" id="upload_pad"></div><div class="col-md-4">  @if($user->upload_pad != '')<a href="{{route('paddownload',$user->upload_pad)}}">  @lang('PAD Download Link')</a> @endif</div></div>
                    </li>
                  </ul>
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

@push('style-lib')
<link rel="stylesheet" href="{{asset($activeTemplateTrue.'css/select2.min.css')}}">
@endpush

@push('script-lib')
<script src="{{asset($activeTemplateTrue.'js/nicEdit.js')}}"></script>
<script src="{{asset($activeTemplateTrue.'js/select2.min.js') }}"></script>
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
'use strict';
$('.select2').select2({
  tags: true,
  maximumSelectionLength : 15
});

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
