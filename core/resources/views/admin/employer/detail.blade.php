@extends('admin.layouts.app')
@section('panel')
    <div class="row mb-none-30">
        <div class="col-xl-3 col-lg-5 col-md-5 mb-30">

            <div class="card b-radius--10 overflow-hidden box--shadow1">
                <div class="card-body p-0">
                    <div class="p-3 bg--white">
                        <div class="">
                            <img src="{{ getImage(imagePath()['employerLogo']['path'].'/'.$user->image)}}" alt="@lang('Profile Image')" class="b-radius--10 w-100">
                        </div>

                        <div class="mt-15">
                            <h4 class="">{{$user->fullname}}</h4>
                            <span class="text--small">@lang('Joined At') <strong>{{showDateTime($user->created_at,'d M, Y h:i A')}}</strong></span>
                        </div>
                        <div class="mt-15">
                          @if(!empty($avg)){!!get_rating($avg)!!}({{$count}})@endif
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
                    <h5 class="mb-20 text-muted">@lang('Employer Information')</h5>
                    <ul class="list-group">

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Username')
                            <span class="font-weight-bold">{{$user->username}}</span>
                        </li>


                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Status')
                            @if($user->status == 1)
                                <span class="badge badge-pill bg--success">@lang('Active')</span>
                            @elseif($user->status == 0)
                                <span class="badge badge-pill bg--danger">@lang('Banned')</span>
                            @endif
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Balance')
                            <span class="font-weight-bold">{{showAmount($user->balance)}}  {{__($general->cur_text)}}</span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="card b-radius--10 overflow-hidden mt-30 box--shadow1">
                <div class="card-body">
                    <h5 class="mb-20 text-muted">@lang('Employer Action')</h5>
                     <a data-toggle="modal" href="#addSubModal" class="btn btn--success btn--shadow btn-block btn-lg">
                        @lang('Add/Subtract Balance')
                    </a>
                    <a href="{{ route('admin.employers.login.history.single', $user->id) }}"
                       class="btn btn--primary btn--shadow btn-block btn-lg">
                        @lang('Login Logs')
                    </a>
                    <a href="{{route('admin.employers.email.single',$user->id)}}"
                       class="btn btn--info btn--shadow btn-block btn-lg">
                        @lang('Send Email')
                    </a>
                    <a href="{{route('admin.employers.login',$user->id)}}" target="_blank" class="btn btn--dark btn--shadow btn-block btn-lg">
                        @lang('Login as Employer')
                    </a>
                    <a href="{{route('admin.employers.email.log',$user->id)}}" class="btn btn--warning btn--shadow btn-block btn-lg">
                        @lang('Email Log')
                    </a>
                </div>
            </div>
        </div>

        <div class="col-xl-9 col-lg-7 col-md-7 mb-30">
            <div class="row mb-none-30">
                <div class="col-xl-3 col-lg-6 col-sm-6 mb-30">
                    <div class="dashboard-w1 bg--3 b-radius--10 box-shadow has--link">
                        <a href="{{route('admin.employers.deposits',$user->id)}}" class="item--link"></a>
                        <div class="icon">
                            <i class="la la-exchange-alt"></i>
                        </div>
                        <div class="details">
                            <div class="numbers">
                                <span class="amount">{{$general->cur_sym}} {{getAmount($totalDeposit)}}</span>
                            </div>
                            <div class="desciption">
                                <span>@lang('Total Deposit')</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-6 col-sm-6 mb-30">
                    <div class="dashboard-w1 bg--10 b-radius--10 box-shadow has--link">
                        <a href="{{route('admin.employers.transactions',$user->id)}}" class="item--link"></a>
                        <div class="icon">
                            <i class="la la-exchange-alt"></i>
                        </div>
                        <div class="details">
                            <div class="numbers">
                                <span class="amount">{{$general->cur_sym}} {{showAmount($user->balance)}} </span> 
                            </div>
                            <div class="desciption">
                                <span>@lang('Current Balance')</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-6 col-sm-6 mb-30">
                    <div class="dashboard-w1 bg--12 b-radius--10 box-shadow has--link">
                        <a href="{{route('admin.employers.transactions', $user->id)}}" class="item--link"></a>
                        <div class="icon">
                            <i class="la la-exchange-alt"></i>
                        </div>
                        <div class="details">
                            <div class="numbers">
                                <span class="amount">{{$totalTransaction}}</span>
                            </div>
                            <div class="desciption">
                                <span>@lang('No. of Transactions')</span>
                            </div>
                        </div>
                    </div>
                </div><!-- dashboard-w1 end -->

                <div class="col-xl-3 col-lg-6 col-sm-6 mb-30">
                    <div class="dashboard-w1 bg--14 b-radius--10 box-shadow has--link">
                        <a href="{{route('admin.employers.job.list', $user->id)}}" class="item--link"></a>
                        <div class="icon">
                            <i class="la la-exchange-alt"></i>
                        </div>
                        <div class="details">
                            <div class="numbers">
                                <span class="amount">{{$totalJobCount}}</span>
                            </div>
                            <div class="desciption">
                                <span>@lang('Total Job Postings')</span>
                            </div>
                        </div>
                    </div>
                </div><!-- dashboard-w1 end -->
            </div>

            <div class="card mt-50">
                <div class="card-body">
                    <h5 class="card-title border-bottom pb-2">@lang('Information of') {{$user->company_name}}</h5>

                    <form action="{{route('admin.employers.update',[$user->id])}}" method="POST"
                          enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('Company Name')<span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="company_name" value="{{$user->company_name}}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label  font-weight-bold">@lang('Contact Person') <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="company_ceo" value="{{$user->ceo_name}}">
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
                                    <label class="form-control-label  font-weight-bold">@lang('Contact Number') <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="mobile" value="{{$user->mobile}}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('Website')</label>
                                    <input class="form-control" type="url" name="website" value="{{$user->website}}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label  font-weight-bold">@lang('Fax Number')</label>
                                    <input class="form-control" type="text" name="fax" value="{{$user->fax}}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label font-weight-bold">@lang('Industry')</label>
                                    <select class="form-control" name="industry">
                                        <option value="">@lang('Select One')</option>
                                        @foreach($industries as $industry)
                                            <option value="{{$industry->id}}" @if($user->industry_id == $industry->id) selected @endif>{{__($industry->name)}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label font-weight-bold">@lang('Number Of Employees')</label>
                                    <select class="form-control" name="number_of_employees">
                                        <option value="">@lang('Select One')</option>
                                        @foreach($numberOfemployes as $numberOfemploye)
                                            <option value="{{$numberOfemploye->id}}" @if($user->number_of_employees_id == $numberOfemploye->id) selected @endif>{{__($numberOfemploye->employees )}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>


                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('Address') </label>
                                    <input class="form-control" type="text" name="address" value="{{@$user->address->address}}">
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label font-weight-bold">@lang('City') </label>
                                    <input class="form-control" type="text" name="city" value="{{@$user->address->city}}">
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('State/Province') </label>
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


                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-control-label font-weight-bold">@lang('Facebook')</label>
                                    <input type="text" name="facebook" class="form-control" placeholder="Enter Facebook url" value="{{@$user->socialMedia->facebook}}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                 <div class="form-group">
                                    <label class="form-control-label font-weight-bold">@lang('Twitter')</label>
                                    <input type="text" name="twitter" class="form-control" placeholder="Enter Twitter url" value="{{@$user->socialMedia->twitter}}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                 <div class="form-group">
                                    <label class="form-control-label font-weight-bold">@lang('Pinterest')</label>
                                    <input type="text" name="pinterest" class="form-control" placeholder="Enter Pinterest url" value="{{@$user->socialMedia->pinterest}}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                 <div class="form-group">
                                    <label class="form-control-label font-weight-bold">@lang('Linkedin')</label>
                                    <input type="text" name="linkedin" class="form-control" placeholder="Enter Linkedin url" value="{{@$user->socialMedia->linkedin}}">
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-control-label font-weight-bold">@lang('Description')</label>
                                    <textarea class="form-control nicEdit" name="description">@php echo @$user->description @endphp</textarea>
                                </div>
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



    {{-- Add Sub Balance MODAL --}}
    <div id="addSubModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Add / Subtract Balance')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('admin.employers.add.sub.balance', $user->id)}}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger" data-toggle="toggle" data-on="@lang('Add Balance')" data-off="@lang('Subtract Balance')" name="act" checked>
                            </div>


                            <div class="form-group col-md-12">
                                <label>@lang('Amount')<span class="text-danger">*</span></label>
                                <div class="input-group has_append">
                                    <input type="text" name="amount" class="form-control" placeholder="@lang('Please provide positive amount')">
                                    <div class="input-group-append">
                                        <div class="input-group-text">{{ __($general->cur_sym) }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                <div class="input-group has_append">
                                    <textarea class="form-control" name="comments" placeholder="Comments"></textarea>
                                  </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn--success">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
