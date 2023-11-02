@extends('admin.layouts.app')
@push('style-lib')
<style>
html,body{
  overflow-x: hidden;
}
.custom-icon-field i {
  position: absolute;
  top: 50%;
  -webkit-transform: translateY(-50%);
  -ms-transform: translateY(-50%);
  transform: translateY(-50%);
  left: 0.75rem;
}
.custom-icon-field i[class*="la-"] {
  font-size: 1.25rem;
}
.custom-icon-field .form-control {
  padding-left: 2.5rem;
}
.custom-icon-field {
  position: relative;
}
</style>
@endpush
@section('panel')
    <div class="row mb-none-30">
        <div class="col-xl-3 col-lg-5 col-md-5 mb-30">

            <div class="card b-radius--10 overflow-hidden box--shadow1">
                <div class="card-body p-0">
                    <div class="p-3 bg--white">
                        <div class="">
                            <img src="{{ getImage(imagePath()['profile']['user']['path'].'/'.$user->image,imagePath()['profile']['user']['size'])}}" alt="@lang('Profile Image')" class="b-radius--10 w-100">
                        </div>
                        <div class="mt-15">
                            <h4 class="">{{$user->fullname}}</h4>
                            <span class="text--small">@lang('Joined At') <strong>{{showDateTime($user->created_at,'d M, Y h:i A')}}</strong></span>
                        </div>
                        <div class="mt-15">
                          @if(!empty($avg))
                              {!!get_rating($avg)!!}
                               ({{$count}})
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
            </div>

            <div class="card b-radius--10 overflow-hidden mt-30 box--shadow1">
                <div class="card-body">
                    <h5 class="mb-20 text-muted">@lang('User information')</h5>
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Username')
                            <span class="font-weight-bold">{{$user->username}}</span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Birth Date')
                            <span class="font-weight-bold">{{showDateTime(@$user->birth_date, 'd M Y')}}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Emp #'):
                            <span class="font-weight-bold">{{$user->employee_id}}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Status')
                            @if($user->status == 1)
                                <span class="badge badge-pill bg--success">@lang('Active')</span>
                            @elseif($user->status == 0)
                                <span class="badge badge-pill bg--danger">@lang('Banned')</span>
                            @endif
                        </li>
                    </ul>
                </div>
            </div>
            <div class="card b-radius--10 overflow-hidden mt-30 box--shadow1">
                <div class="card-body">
                    <h5 class="mb-20 text-muted">@lang('Bank information')</h5>
                      <ul class="list-group" style="text-align:center">
                          <li class="list-group-item d-flex justify-content-between align-items-center">
                              @lang('PAD')&nbsp;#{{$user->accountno}}
                          </li>
                            @if($user->upload_pad != '')
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                              <a href="{{route('admin.users.padfile.download',$user->upload_pad)}}">  @lang('PAD Download Link')</a>
                            </li>
                            @endif
                      </ul>
                </div>
            </div>
            <div class="card b-radius--10 overflow-hidden mt-30 box--shadow1">
                <div class="card-body">
                    <h5 class="mb-20 text-muted">@lang('User action')</h5>
                    <a href="{{ route('admin.users.login.history.single', $user->id) }}"
                       class="btn btn--primary btn--shadow btn-block btn-lg">
                        @lang('Login Logs')
                    </a>
                    <a href="{{route('admin.users.email.single',$user->id)}}"
                       class="btn btn--info btn--shadow btn-block btn-lg">
                        @lang('Send Email')
                    </a>
                    <a href="{{route('admin.users.login',$user->id)}}" target="_blank" class="btn btn--dark btn--shadow btn-block btn-lg">
                        @lang('Login as User')
                    </a>
                    <a href="{{route('admin.users.email.log',$user->id)}}" class="btn btn--warning btn--shadow btn-block btn-lg">
                        @lang('Email Log')
                    </a>
                </div>
            </div>
        </div>

        <div class="col-xl-9 col-lg-7 col-md-7 mb-30">

            <div class="row mb-none-30">
                <div class="col-lg-3 col-sm-6 mb-30">
                    <div class="dashboard-w1 bg--17 b-radius--10 box-shadow has--link">
                        <a href="{{route('admin.users.job.application', $user->id)}}" class="item--link"></a>
                        <div class="icon">
                            <i class="la la-exchange-alt"></i>
                        </div>
                        <div class="details">
                            <div class="numbers">
                                <span class="amount">{{$totalJobApply}}</span>
                            </div>
                            <div class="desciption">
                                <span>@lang('Job Applications')</span>
                            </div>
                        </div>
                    </div>
                </div><!-- dashboard-w1 end -->


                <div class="col-lg-3 col-sm-6 mb-30">
                    <div class="dashboard-w1 bg--5 b-radius--10 box-shadow has--link">
                        <a href="{{route('admin.users.education.list', $user->id)}}" class="item--link"></a>
                        <div class="icon">
                            <i class="la la-exchange-alt"></i>
                        </div>
                        <div class="details">
                            <div class="numbers">
                                <span class="amount">{{$educationCount}}</span>
                            </div>
                            <div class="desciption">
                                <span>@lang('Educational Qualification')</span>
                            </div>
                        </div>
                    </div>
                </div><!-- dashboard-w1 end -->

                <div class="col-lg-3 col-sm-6 mb-30">
                    <div class="dashboard-w1 bg--6 b-radius--10 box-shadow has--link">
                        <a href="{{route('admin.users.employment.list', $user->id)}}" class="item--link"></a>
                        <div class="icon">
                            <i class="la la-exchange-alt"></i>
                        </div>
                        <div class="details">
                            <div class="numbers">
                                <span class="amount">{{$employmentCount}}</span>
                            </div>
                            <div class="desciption">
                                <span>@lang('Employment History')</span>
                            </div>
                        </div>
                    </div>
                </div><!-- dashboard-w1 end -->


                <div class="col-lg-3 col-sm-6 mb-30">
                    <div class="dashboard-w1 bg--12 b-radius--10 box-shadow has--link">
                        <a href="{{route('admin.users.support.ticket', $user->id)}}" class="item--link"></a>
                        <div class="icon">
                            <i class="la la-exchange-alt"></i>
                        </div>
                        <div class="details">
                            <div class="numbers">
                                <span class="amount">{{$supportTicketCount}}</span>
                            </div>
                            <div class="desciption">
                                <span>@lang('Support Ticket')</span>
                            </div>
                        </div>
                    </div>
                </div><!-- dashboard-w1 end -->
            </div>


            <div class="card mt-50">
                <div class="card-body">
                    <h5 class="card-title border-bottom pb-2">@lang('Information of') {{$user->fullname}}</h5>

                    <form action="{{route('admin.users.update',[$user->id])}}" method="POST"
                          enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('First Name')<span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="firstname" value="{{$user->firstname}}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label  font-weight-bold">@lang('Last Name') <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="lastname" value="{{$user->lastname}}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('Email') <span class="text-danger">*</span></label>
                                    <input class="form-control" type="email" name="email" value="{{$user->email}}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label  font-weight-bold">@lang('Mobile Number') <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="mobile" value="{{$user->mobile}}">
                                </div>
                            </div>
                        </div>

                        <div class="row">

                        <div class="col-lg-6 form-group">
                            <label class="form-control-label  font-weight-bold" for="designation">@lang('Designation') <sup class="text--danger">*</sup></label>
                                <select class="form-control" name="designation" id="designation" required="">
                                  <option value="">@lang('Select One')</option>
                                    @foreach($designation as $design)
                                        <option value="{{$design->id}}" @if($design->id == @$user->designation) selected @endif>{{__($design->name)}}</option>
                                    @endforeach
                                </select>
                        </div>


                        <div class="col-lg-6 form-group">
                            <label class="form-control-label  font-weight-bold" for="gender">@lang('Gender') <sup class="text--danger">*</sup></label>
                                <select class="form-control" id="gender" name="gender" required="">
                                    <option value="">@lang('Select One')</option>
                                    <option value="1" @if($user->gender == 1) selected @endif>@lang('Male')</option>
                                    <option value="2" @if($user->gender == 2) selected @endif>@lang('Female')</option>
                                </select>
                        </div>
                        <div class="col-lg-6 form-group">
                            <label class="form-control-label  font-weight-bold" for="date_birth">@lang('Date Of Birth') <sup class="text--danger">*</sup></label>
                            <div class="">
                                 <input type="date" id="date_birth" name="birth_date" value="{{showDateTime($user->birth_date, 'Y-m-d')}}"  placeholder="@lang('Enter Date Of Birth')" autofocus="off" class="form-control" required="">
                            </div>
                        </div>

                        <div class="col-lg-6 form-group">
                            <label class="form-control-label  font-weight-bold" for="national_id">@lang('Work Status') <sup class="text--danger">*</sup></label>
                                <select class="form-control" name="national_id" id="national_id" required="">
                                    <option value="">@lang('Select One')</option>
                                    <option value="Contract Worker" @if($user->national_id == "Contract Worker") selected @endif>@lang('Contract Worker')</option>
                                    <option value="Currently Employed" @if($user->national_id == "Currently Employed") selected @endif>@lang('Currently Employed')</option>
                                    <option value="Unemployed" @if($user->national_id == "Unemployed") selected @endif>@lang('Unemployed')</option>
                                </select>
                        </div>
                          <div class="col-md-6">
                              <div class="form-group">
                                  <label class="form-control-label font-weight-bold">@lang('Rate') <span class="text-danger">*</span></label>
                                  <select class="form-control" name="candidate_rate" id="candidate_rate" required="">
                                      <option value="">@lang('Select One')</option>
                                      @for ($i = 1; $i <= 1000; $i++)
                                          <option value="{{$i}}" @if($user->candidate_rate == $i) selected @endif>{{$general->cur_sym}} {{$i}}</option>
                                      @endfor
                                  </select>
                              </div>
                          </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('Address') <sup class="text--danger">*</sup></label>
                                    <input class="form-control" type="text" name="address" value="{{@$user->address->address}}">
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label font-weight-bold">@lang('City') <sup class="text--danger">*</sup></label>
                                    <input class="form-control" type="text" name="city" value="{{@$user->address->city}}">
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('State/Province') <sup class="text--danger">*</sup></label>
                                    <input class="form-control" type="text" name="state" value="{{@$user->address->state}}">
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('Zip/Postal') </label>
                                    <input class="form-control" type="text" name="zip" value="{{@$user->address->zip}}">
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('Country') </label>
                                    <select name="country" class="form-control">
                                        @foreach($countries as $key => $country)
                                            <option value="{{ $key }}" @if($country->country == @$user->address->country ) selected @endif>{{ __($country->country) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <h5 class="card-title border-bottom pb-2">Software And Language</h5>
                        <div class="row">
                          <div class="col-md-6">
                              <div class="form-group ">
                              <label class="form-control-label font-weight-bold" for="software" class="form-control-label font-weight-bold">@lang('Software') <sup class="text--danger">*</sup></label>
                              <select class="form-control select2-auto-tokenize" name="software_id[]" multiple="multiple" required="">
                                @foreach($softwares as $software)
                                    <option value="{{$software->id}}" @if(@in_array($software->id, @$software_id)) selected @endif>{{__($software->name)}}</option>
                                @endforeach
                              </select>
                          </div>
                        </div>
                            <div class="col-md-6">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('Language') </label>
                                    <select class="form-control select2-auto-tokenize" multiple="multiple" name="language[]">
                                         @if(!empty($user->language))
                                             @foreach (array_unique($user->language) as  $value)
                                                <option value="{{$value}}" selected="true">{{ $value }}</option>
                                            @endforeach
                                        @endif
                                        @include('partials.language')
                                    </select>
                                </div>
                            </div>
                          </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <label class="form-control-label font-weight-bold">@lang('Career Summary')</label>
                                <textarea class="form-control" rows="6" name="details" placeholder="@lang('Enter Career Summary')">{{@$user->details}}</textarea>
                            </div>
                        </div>
                        <h5 class="card-title border-bottom pb-2">Social Links</h5>
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label font-weight-bold">@lang('Facebook')</label>
                                    <input type="text" name="facebook" class="form-control" placeholder="Enter Facebook url" value="{{@$user->socialMedia->facebook}}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                 <div class="form-group">
                                    <label class="form-control-label font-weight-bold">@lang('Linkedin')</label>
                                    <input type="text" name="linkedin" class="form-control" placeholder="Enter Linkedin url" value="{{@$user->socialMedia->linkedin}}">
                                </div>
                            </div>
                        </div>
                        <h5 class="card-title border-bottom pb-2">Character References</h5>
                        <div class="row">
                          <div class="col-lg-4 form-group">
                            <label  class="form-control-label font-weight-bold"  for="linkedin">@lang('Name')<sup class="text--danger">*</sup></label>
                            <div class="custom-icon-field" style="padding: 5px 0;">
                              <i class="las la-user"></i>
                              <input type="text" name="name1" class="form-control" value="{{@$user->references->name1 ?? session()->get('name1')}}" placeholder="@lang('Name')">
                            </div>
                          </div>
                          <div class="col-lg-4 form-group">
                            <label  class="form-control-label font-weight-bold"  for="linkedin">@lang('Contact#')<sup class="text--danger">*</sup></label>
                            <div class="custom-icon-field" style="padding: 5px 0;">
                              <i class="las la-phone"></i>
                              <input type="text" name="contact1" class="form-control" value="{{@$user->references->contact1 ?? session()->get('contact1')}}" placeholder="@lang('Contact')">
                            </div>
                          </div>
                          <div class="col-lg-4 form-group">
                            <label  class="form-control-label font-weight-bold"  for="linkedin">@lang('Email')<sup class="text--danger">*</sup></label>
                            <div class="custom-icon-field" style="padding: 5px 0;">
                              <i class="las la-envelope"></i>
                              <input type="email" name="email1" class="form-control" value="{{@$user->references->email1 ?? session()->get('email1')}}" placeholder="@lang('Email')">
                            </div>
                          </div>
                          <div class="col-lg-4 form-group">
                            <label  class="form-control-label font-weight-bold"  for="linkedin">@lang('Name')<sup class="text--danger">*</sup></label>
                            <div class="custom-icon-field" style="padding: 5px 0;">
                              <i class="las la-user"></i>
                              <input type="text" name="name2" class="form-control" value="{{@$user->references->name2 ?? session()->get('name2')}}" placeholder="@lang('Name')">
                            </div>
                          </div>
                          <div class="col-lg-4 form-group">
                            <label  class="form-control-label font-weight-bold"  for="linkedin">@lang('Contact#')<sup class="text--danger">*</sup></label>
                            <div class="custom-icon-field" style="padding: 5px 0;">
                              <i class="las la-phone"></i>
                              <input type="text" name="contact2" class="form-control" value="{{@$user->references->contact2 ?? session()->get('contact2')}}" placeholder="@lang('Contact')">
                            </div>
                          </div>
                          <div class="col-lg-4 form-group">
                            <label  class="form-control-label font-weight-bold"  for="linkedin">@lang('Email')<sup class="text--danger">*</sup></label>
                            <div class="custom-icon-field" style="padding: 5px 0;">
                              <i class="las la-envelope"></i>
                              <input type="email" name="email2" class="form-control" value="{{@$user->references->email2 ?? session()->get('email2')}}" placeholder="@lang('Email')">
                            </div>
                          </div>
                          <div class="col-lg-4 form-group">
                            <label  class="form-control-label font-weight-bold" for="linkedin">@lang('Name')<sup class="text--danger">*</sup></label>
                            <div class="custom-icon-field" style="padding: 5px 0;">
                              <i class="las la-user"></i>
                              <input type="text" name="name3" class="form-control" value="{{@$user->references->name3 ?? session()->get('name3')}}" placeholder="@lang('Name')">
                            </div>
                          </div>
                          <div class="col-lg-4 form-group">
                            <label  class="form-control-label font-weight-bold"  for="linkedin">@lang('Contact#')<sup class="text--danger">*</sup></label>
                            <div class="custom-icon-field" style="padding: 5px 0;">
                              <i class="las la-phone"></i>
                              <input type="text" name="contact3" class="form-control" value="{{@$user->references->contact3 ?? session()->get('contact3')}}" placeholder="@lang('Contact')">
                            </div>
                          </div>
                          <div class="col-lg-4 form-group">
                            <label  class="form-control-label font-weight-bold"  for="linkedin">@lang('Email')<sup class="text--danger">*</sup></label>
                            <div class="custom-icon-field" style="padding: 5px 0;">
                              <i class="las la-envelope"></i>
                              <input type="email" name="email3" class="form-control" value="{{@$user->references->email3 ?? session()->get('email3')}}" placeholder="@lang('Email')">
                            </div>
                          </div>
                        </div>
                        <h5 class="card-title border-bottom pb-2">FIll in the blanks (All answers are acceptable)</h5>
                        <div class="row">
                            <div class="col-lg-12 form-group">
                                <ul>
                                    <li>
                                        <label>1). @lang('The World Is A ')<input type="text" name="answer1" value="{{@$user->answers->answer1}}" > @lang(' Place, Full Of ')<input type="text" name="answer2" value="{{@$user->answers->answer2}}" > @lang(' People')</label>
                                    </li>
                                    <li>
                                        <label>2). @lang('One challange I\'ve overcome is ')<input type="text" name="answer3" value="{{@$user->answers->answer3}}" style="width:auto"></label>
                                    </li>
                                    <li>
                                        <label>3). @lang('One personal or professional failure I\'ve had is ')<input type="text" name="answer4" value="{{@$user->answers->answer4}}" style="width:auto"></label>
                                    </li>
                                    <li>
                                        <label>4). @lang('One thing I still feel vulnerable about is ')<input type="text" name="answer5" value="{{@$user->answers->answer5}}" style="width:auto"></label>
                                    </li>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-xl-4 col-md-6  col-sm-3 col-12">
                                <label class="form-control-label font-weight-bold">@lang('Status') </label>
                                <input type="checkbox" data-onstyle="-success" data-offstyle="-danger"
                                       data-toggle="toggle" data-on="@lang('Active')" data-off="@lang('Banned')" data-width="100%"
                                       name="status"
                                       @if($user->status) checked @endif>
                            </div>

                            <div class="form-group  col-xl-4 col-md-6  col-sm-3 col-12">
                                <label class="form-control-label font-weight-bold">@lang('Email Verification') </label>
                                <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger"
                                       data-toggle="toggle" data-on="@lang('Verified')" data-off="@lang('Unverified')" name="ev"
                                       @if($user->ev) checked @endif>

                            </div>

                            <div class="form-group  col-xl-4 col-md-6  col-sm-3 col-12">
                                <label class="form-control-label font-weight-bold">@lang('SMS Verification') </label>
                                <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger"
                                       data-toggle="toggle" data-on="@lang('Verified')" data-off="@lang('Unverified')" name="sv"
                                       @if($user->sv) checked @endif>

                            </div>
                            <div class="form-group  col-md-6  col-sm-3 col-12">
                                <label class="form-control-label font-weight-bold">@lang('2FA Status') </label>
                                <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger"
                                       data-toggle="toggle" data-on="@lang('Active')" data-off="@lang('Deactive')" name="ts"
                                       @if($user->ts) checked @endif>
                            </div>

                            <div class="form-group  col-md-6  col-sm-3 col-12">
                                <label class="form-control-label font-weight-bold">@lang('2FA Verification') </label>
                                <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger"
                                       data-toggle="toggle" data-on="@lang('Verified')" data-off="@lang('Unverified')" name="tv"
                                       @if($user->tv) checked @endif>
                            </div>
                        </div>


                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn--primary btn-block btn-lg">@lang('Save Changes')
                                    </button>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('breadcrumb-plugins')
    <a href="{{route('admin.users.cv.download', $user->id)}}" class="btn btn-sm btn--primary box--shadow1 text--small"><i class="las la-arrow-down"></i>@lang('Download CV')</a>
    <a href="{{route('admin.users.credentials.download', $user->id)}}" class="btn btn-sm btn--primary box--shadow1 text--small"><i class="las la-arrow-down"></i>@lang('Download Credentials')</a>
@endpush


@push('script')
<script>
    (function ($) {
        "use strict";
        $('.select2-auto-tokenize').select2({
            tags: true,
            tokenSeparators: [',']
        });
    })(jQuery);
</script>
@endpush
