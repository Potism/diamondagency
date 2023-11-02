@extends('admin.layouts.app')
@section('panel')
    <div class="row mb-none-30">
        <div class="col-lg-12 mb-30">
            <div class="card">
                <div class="card-body">
                	<form action="{{route('admin.plan.store')}}" method="POST">
                		@csrf
                        <div class="row">
                            <div class="col-lg-6">
                               <div class="form-group">
                                    <label for="name" class="font-weight-bold">@lang('Name')</label>
                                    <input type="text" id="name" name="name" value="{{old('name')}}" class="form-control form-control-lg" placeholder="@lang('Enter Name')" maxlength="120" required="">
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="amount" class="font-weight-bold">@lang('Amount')</label>
                                    <input type="text" id="amount" name="amount" value="{{old('amount')}}" class="form-control form-control-lg" placeholder="@lang('Enter Amount')" required="">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="duration" class="form-control-label font-weight-bold">@lang('Duration')</label>
                                    <div class="input-group mb-3">
                                          <input type="text" id="duration" value="{{old('duration')}}" class="form-control form-control-lg" placeholder="@lang('Enter Duration')" name="duration" aria-label="Recipient's username" aria-describedby="basic-addon2" required="">
                                          <div class="input-group-append">
                                            <span class="input-group-text" id="basic-addon2">@lang('Month')</span>
                                          </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                               <div class="form-group">
                                    <label for="job_post" class="font-weight-bold">@lang('Number Of Job Post')</label>
                                    <input type="text" id="job_post" name="job_post" value="{{old('job_post')}}" class="form-control form-control-lg" placeholder="@lang('Total Job Post')" required="">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">@lang('Icon')</label>
                                    <div class="input-group has_append">
                                        <input type="text" class="form-control form-control-lg icon" name="icon" id="icon" required>
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary iconPicker" data-icon="" role="iconpicker"></button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                 <div class="form-group">
                                    <label class="form-control-label font-weight-bold">@lang('Status') </label>
                                    <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger"
                                        data-toggle="toggle" data-on="@lang('Enable')" data-off="@lang('Disable')" name="status">
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-12">
                                        <label class="font-weight-bold">@lang('Add Service') <span class="text-danger">*</span></label>
                                    </div>
                                    @foreach($services->chunk(1) as $service)
                                        <div class="col-lg-4">
                                            <div class="form-group form-check">
                                                @foreach($service as $value)
                                                    <input type="checkbox" class="form-check-input form-control-lg" name="service[]" value="{{$value->id}}" id="{{$value->id}}">
                                                    <label class="form-check-label" for="{{$value->id}}"><h6>{{$value->name}}</h6></label>
                                                    <br>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                       	<div class="form-group">
                            <button type="submit" class="btn btn--primary btn-block btn-lg"><i class="fa fa-fw fa-paper-plane"></i> @lang('Plan Create')</button>
                        </div>
                	</form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('breadcrumb-plugins')
    <a href="{{route('admin.plan.index')}}" class="btn btn-sm btn--primary box--shadow1 text--small"><i class="las la-angle-double-left"></i>@lang('Go Back')</a>
@endpush


@push('script-lib')
    <script src="{{ asset('assets/admin/js/bootstrap-iconpicker.bundle.min.js') }}"></script>
@endpush


@push('script')
<script>
    "use strict";
    $('.iconPicker').iconpicker().on('change', function (e) {
        $(this).parent().siblings('.icon').val(`<i class="${e.icon}"></i>`);
    });
</script>
@endpush

