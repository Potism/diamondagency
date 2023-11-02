@extends($activeTemplate.'layouts.frontend')
@section('content')
<div class="search-job-area section--bg2">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                    <div class="row gy-4">
                        <div class="col-md-5 col-sm-6">
                            <div class="custom-icon-field">
                                <i class="las la-search"></i>
                                <select class="form--control" name="city" style="text-indent:30px;line-height:38px;padding-left:0;">
                                    <option value="">@lang('Select Province')</option>
                                    @foreach($cities as $city)
                                        <option value="{{$city->id}}" @if($city->id == @$cityId) selected @endif data-locations="{{json_encode($city->location)}}">{{__($city->name)}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-5 col-sm-6">
                            <div class="custom-icon-field">
                                <i class="las la-map-marker-alt"></i>
                                <select class="form--control" name="location" style="text-indent:30px;line-height:38px;padding-left:0;">
                                    <option>@lang('Select One')</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn--base">@lang('Search')</button>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>

<!-- search result section start -->
<section class="pt-50 pb-50 section--bg">
    <div class="container">
        <div class="row gy-4">
            <div class="col-lg-3">
                <button class="action-sidebar-open"><i class="las la-sliders-h"></i> @lang('Filter')</button>
                <div class="action-sidebar">
                    <button class="action-sidebar-close"><i class="las la-times"></i></button>
                    <form action="{{route('job.filter')}}" method="GET" class="search-form-inline">
                    <div class="action-widget pt-0">
                        <h4 class="action-widget__title">@lang('Search by keyword')</h4>
                        <div class="action-widget__body" id="search_body">
                            
                                <input type="text" name="search" value="{{@$search}}" autocomplete="off" class="form--control form-control-sm" placeholder="Search here">
                                <button type="submit" class="search-form-inline__btn"><i class="las la-search"></i></button>

                        </div>
                    </div>
                    
                        <div class="action-widget">
                            <h4 class="action-widget__title">@lang('Category')</h4>
                            <div class="action-widget__body">
                                @foreach($categorys as $category)
                                    <div class="form-check custom--checkbox d-flex justify-content-between align-items-center">
                                        <div class="left">
                                            <input class="form-check-input categoryType" type="checkbox" value="{{$category->id}}" name="category[]" id="category.{{$category->id}}" @if(@in_array($category->id, @$categoryId)) checked @endif>
                                            <label class="form-check-label" for="category.{{$category->id}}">
                                                {{__($category->name)}}
                                            </label>
                                        </div>
                                        <span class="fs--14px">({{$category->job->count()}})</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="action-widget">
                            <h4 class="action-widget__title">@lang('Job Nature')</h4>
                            <div class="action-widget__body">
                                @foreach($jobTypes as $jobType)
                                    <div class="form-check custom--checkbox d-flex justify-content-between align-items-center">
                                        <div class="left">
                                            <input class="form-check-input jobType" type="checkbox" value="{{$jobType->id}}" name="job_type[]" id="jobType.{{$jobType->id}}" @if(@in_array($jobType->id, @$jobTypeId)) checked @endif>
                                            <label class="form-check-label" for="jobType.{{$jobType->id}}">
                                                {{__($jobType->name)}}
                                            </label>
                                        </div>
                                        <span class="fs--14px">({{$jobType->job->count()}})</span>
                                    </div>
                                @endforeach
                            </div>
                        </div><!-- action-widget end -->
                        <div class="action-widget">
                            <h4 class="action-widget__title">@lang('Job Shift')</h4>
                            <div class="action-widget__body">
                                @foreach($jobShifts as $jobShift)
                                    <div class="form-check custom--checkbox d-flex justify-content-between align-items-center">
                                        <div class="left">
                                            <input class="form-check-input jobShift" value="{{$jobShift->id}}" type="checkbox" name="job_shift[]" id="shift.{{$jobShift->id}}"  @if(@in_array($jobShift->id, @$shiftId)) checked @endif>
                                            <label class="form-check-label" for="shift.{{$jobShift->id}}">
                                                {{__($jobShift->name)}}
                                            </label>
                                        </div>
                                        <span class="fs--14px">({{$jobShift->job->count()}})</span>
                                    </div>
                                @endforeach
                            </div>
                        </div><!-- action-widget end -->

                        <div class="action-widget">
                            <h4 class="action-widget__title">@lang('Experience Range')</h4>
                            <div class="action-widget__body">
                                @foreach($jobExperiences as $jobExperience)
                                    <div class="form-check custom--checkbox d-flex justify-content-between align-items-center">
                                        <div class="left">
                                            <input class="form-check-input jobExperience" type="checkbox" value="{{$jobExperience->id}}" name="job_experience[]" id="experience.{{$jobExperience->id}}" @if(@in_array($jobExperience->id, @$jobExperienceId)) checked @endif>
                                            <label class="form-check-label" for="experience.{{$jobExperience->id}}">
                                                {{$jobExperience->name}}
                                            </label>
                                        </div>
                                        <span class="fs--14px">({{$jobExperience->job->count()}})</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </form>
                    @if(!auth()->user() && !auth()->guard('employer')->user())
                    <div class="action-widget bg--base mt-4 text-center rounded-3 p-4">
                        <h4 class="text-white">@lang('Want a great job opportunity') </h4>
                        <a href="{{route('register')}}" class="btn bg-white mt-4">@lang('Join') {{$general->sitename}} @lang('Now')!</a>
                    </div>
                    @endif
                </div>
            </div>
            <div class="col-lg-9 ps-lg-4">
                @foreach($jobs as $job)
                    @php
                    $distance = '';
                    if(isset($lat_long))
                    {
                        $emp_add = $job->employer->address;
                        $address = $emp_add->address.', '.$emp_add->city.', '.$emp_add->state.', '.$emp_add->country.', '.$emp_add->zip;
                        $lat_long_emp = get_lat_long($address);
                        if($lat_long['lat'] != 0 || $lat_long['long'] != 0)
                        {
                            $distance = distance($lat_long['lat'],$lat_long['long'],$lat_long_emp['lat'],$lat_long_emp['long']);
                        }
                        else
                        {
                            $distance = '';
                        }
                    }
                    @endphp
                    <div class="job-card @if($job->featured == 1) featured @endif has--link">
                        <a href="{{route('job.detail', $job->id)}}" class="item--link"></a>
                        <div class="job-card__top d-flex flex-wrap">
                            <div class="left">
                                <h3 class="job-card__title"><a href="{{route('job.detail', $job->id)}}">{{__($job->title)}}</a></h3>
                                <ul class="job-card__meta d-flex flex-wrap align-items-center mt-1">
                                    <li><strong>{{__($job->employer->company_name)}}</strong></li>
                                    @if($distance != '') 
                                            <li><strong>{{$distance}} KM away from you</strong></li>
                                    @endif
                                    <li><i class="las la-map-marker fs--18px"></i> {{__($job->location->name)}}, {{__($job->city->name)}}</li>
                                </ul>
                            </div>
                            <div class="job-card__bookmark text-end">
                                <button type="button" class="bookmark-btn">
                                    <span class="non-bookmark"><i class="far fa-bookmark"></i></span>
                                    <span class="bookmarked-active"><i class="fas fa-bookmark"></i></span>
                                </button>
                            </div>
                        </div>
                        <p class="job-card__description mt-3">{{str_limit(strip_tags(html_entity_decode($job->description)), 300)}}</p>
                        <div class="row mt-3">
                            <div class="col-6">
                                <strong class="d-inline-block">@lang('Post'): {{diffForHumans($job->created_at)}}</strong>
                            </div>
                            <div class="col-6 text-end">
                                <i class="las la-calendar-alt fs--18px"></i> @lang('Work Date'): <strong>{{showDateTime($job->deadline, 'd M Y')}}</strong>
                            </div>
                        </div>
                    </div>
                @endforeach

                <nav class="mt-4">
                    {{$jobs->links()}}
                </nav>
            </div>
        </div>
    </div>
</section>
@endsection

@push('script')
  <script>
        "use strict";
        $('#search_body.action-widget__body').css('position', 'relative');
        $('.categoryType').on('click', function(){
            this.form.submit();
        });

        $('.jobType').on('click', function(){
            this.form.submit();
        });

        $('.jobShift').on('click', function(){
            this.form.submit();
        });

        $('.jobExperience').on('click', function(){
            this.form.submit();
        });

        $('select[name=city]').on('change',function() {
            $('input[name="city"]').val($(this).val());
            $('select[name=location]').html('<option value="" selected="" disabled="">@lang('Select One')</option>');
            var locations = $('select[name=city] :selected').data('locations');
            var html = '';
            if(locations !== undefined)
            {
                locations.forEach(function myFunction(item, index) {
                    var sel = '';
                    var locid = '{{@$locationId}}';
                    if(locid != '' && item.id == '{{@$locationId}}')
                    {
                        sel = "selected";
                    }
                    html += `<option value="${item.id}">${item.name}</option>`
                });
                $('select[name=location]').append(html);
            }
        }).change();

        $('.action-widget__title').each(function(){
            let ele = $(this).siblings('.action-widget__body');
            $(this).on('click', function(){
                ele.slideToggle();
            });
        })
        $('.search-job-area button[type="submit"]').on('click',function(){
            $('input[name="city"]').val($('select[name=city] :selected').val());
            $('input[name="location"]').val($('select[name=location] :selected').val());
            $('.action-sidebar form').submit();
        });
  </script>
@endpush
