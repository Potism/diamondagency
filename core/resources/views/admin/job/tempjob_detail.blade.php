@extends('admin.layouts.app')
@section('panel')
    <div class="row mb-none-30">
        <div class="col-xl-3 col-lg-5 col-md-5 col-sm-12">
            <div class="card b-radius--10 overflow-hidden mt-30 box--shadow1">
                <div class="card-body">
                    <h5 class="mb-20 text-muted">@lang('Emplyer Information')</h5>
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Username')
                            <span class="font-weight-bold">{{__($tempjob->employer->username)}}</span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Status')
                            @if($tempjob->employer->status == 1)
                                <span class="badge badge-pill bg--success">@lang('Active')</span>
                            @elseif($tempjob->employer->status == 0)
                                <span class="badge badge-pill bg--danger">@lang('Banned')</span>
                            @endif
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Balance')
                            <span class="font-weight-bold">{{getAmount($tempjob->employer->balance)}}  {{__($general->cur_text)}}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-xl-9 col-lg-7 col-md-7 col-sm-12 mt-10">
            <div class="row mb-30 mt-4">
                <div class="col-lg-6">
                    <div class="card border--dark">
                        <h5 class="card-header bg--dark">{{__($tempjob->title)}}</h5>
                        <div class="card-body">
                            <ul class="list-group">
                                <li class="list-group-item d-flex justify-content-between align-items-center font-weight-bold">
                                    @lang('Radiography')
                                    <span>{{__($tempjob->radiography->name)}}</span>
                                </li>

                                <li class="list-group-item d-flex justify-content-between align-items-center font-weight-bold">
                                  @lang('ultrasonic')
                                    <span>{{$tempjob->ultrasonic}}</span>
                                </li>

                                <li class="list-group-item d-flex justify-content-between align-items-center font-weight-bold">
                                    @lang('Software')
                                    <span>{{__($tempjob->software->name)}}</span>
                                </li>

                                <li class="list-group-item d-flex justify-content-between align-items-center font-weight-bold">
                                    @lang('Software')
                                    <span>{{__($tempjob->charting->name)}}</span>
                                </li>

                                <li class="list-group-item d-flex justify-content-between align-items-center font-weight-bold">
                                    @lang('Salary Range')
                                    <span>
                                            {{getAmount($tempjob->salary_from)}} - {{getAmount($tempjob->salary_to)}} {{$general->cur_text}}
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card border--dark">
                        <h5 class="card-header bg--dark">@lang('Job Information')</h5>
                        <div class="card-body">
                            <ul class="list-group">
                                <li class="list-group-item d-flex justify-content-between align-items-center font-weight-bold">
                                    @lang('Primary Contact')
                                    <span>{{__($tempjob->primary_contact)}}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center font-weight-bold">
                                    @lang('Parking')
                                      <span>{{__($tempjob->parking)}}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center font-weight-bold">
                                    @lang('Charting')
                                      <span>{{__($tempjob->lunch_break)}}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center font-weight-bold">
                                    @lang('Avg Recall')
                                      <span>{{__($tempjob->avg_recall)}} mins</span>
                                </li>



                                <li class="list-group-item d-flex justify-content-between align-items-center font-weight-bold">
                                    @lang('Status')
                                    @if($tempjob->status == 0)
                                        <span class="badge badge--primary">@lang('Pending')</span>
                                    @elseif($tempjob->status == 1)
                                        <span class="badge badge--success">@lang('Approved')</span>
                                    @elseif($tempjob->status == 2)
                                        <span class="badge badge--danger">@lang('Cancel')</span>
                                    @elseif($tempjob->status == 3)
                                        <span class="badge badge--warning">@lang('Expired')</span>
                                    @endif
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-30">
                <div class="col-lg-12">
                    <div class="card border--dark">
                        <h5 class="card-header bg--dark">@lang('Description')</h5>
                        <div class="card-body">
                            @php echo $tempjob->description @endphp
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <a href="{{ route('admin.manage.job.index') }}" class="btn btn-sm btn--primary box--shadow1 text--small"><i class="la la-fw la-backward"></i>@lang('Go Back')</a>
@endpush
