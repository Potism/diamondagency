@extends($activeTemplate.'layouts.frontend')
@section('content')
<div class="candidate-header">
    <div class="container">
        <div class="row align-items-center gy-4">
            <div class="col-xxl-10 col-lg-9">
                <div class="candidate">
                    <div class="candidate__thumb">
                        <img src="{{getImage(imagePath()['employerLogo']['path'].'/'.@$employer->image)}}" alt="@lang('Company Logo')">
                    </div>
                    <div class="candidate__content">
                        <h4 class="candidate__name text-white">{{__($employer->company_name)}}</h4>
                        <span class="text--base">{{__($employer->ceo_name)}}</span>
                        <ul class="candidate__info-list mt-1">
                            <li><i class="las la-map-marker-alt"></i> {{@$employer->address->address}}, {{@$employer->address->country}}</li>
                            @if($distance != '')
                                <li><strong>{{$distance}} KM away from you </strong></li>
                            @endif
                            <li><i class="las la-clock"></i> @lang('Member since') {{showDateTime($employer->created_at, 'd M Y')}}</li>
                        </ul>
                        <ul class="social-link-list d-flex align-items-center">
                            <li><a href="{{@$employer->socialMedia->facebook}}" target="_blank" class="text-white fs--18px"><i class="lab la-facebook-f"></i></a></li>
                            <li><a href="{{@$employer->socialMedia->twitter}}" target="_blank" class="text-white fs--18px"><i class="lab la-twitter"></i></a></li>
                            <li><a href="{{@$employer->socialMedia->linkedin}}" target="_blank" class="text-white fs--18px"><i class="lab la-linkedin-in"></i></a></li>
                            <li><a href="{{@$employer->socialMedia->pinterest}}" target="_blank" class="text-white fs--18px"><i class="lab la-pinterest-p"></i></a></li>
                        </ul>
                        @if(!empty($avg)){!!get_rating($avg)!!}@endif
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
    </div>
</div>
<section class="pt-50 pb-50 section--bg">
    <div class="container">
        <div class="row gy-4">
            <div class="col-lg-8 pe-lg-4">
                <div class="candidate-details">
                    <div class="job-card has--link" id = "map_div" style="height:380px;">

                    </div>
                </div>
                <div class="candidate-details">
                    <h4>@lang('Company Summary')</h4>
                    <p class="mt-2">@php echo $employer->description @endphp</p>
                </div>

                <div class="open-job-area"><p><br/><hr/></p>
                    <h3 class="title mb-4 text-center">@lang('Job Openings')</h3>

                    @foreach($employer->jobs as $job)
                        <div class="job-card @if($job->featured == 1) featured @endif has--link">
                            <a href="{{route('job.detail', $job->id)}}" class="item--link"></a>
                            <div class="job-card__top d-flex flex-wrap">
                                <div class="left">
                                    <h3 class="job-card__title"><a href="{{route('job.detail', $job->id)}}">{{__($job->title)}}</a></h3>
                                    <ul class="job-card__meta d-flex flex-wrap align-items-center mt-1">
                                        <li><strong>{{__($job->employer->company_name)}}</strong></li>
                                         @if($distance != '')
                                            <li><strong>{{$distance}} KM away from you </strong></li>
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
                            <p class="job-card__description mt-3">{{str_limit(strip_tags($job->description), 300)}}</p>
                            <div class="row mt-3">
                                <div class="col-6">
                                    <strong class="d-inline-block">@lang('Post'): {{diffForHumans($job->created_at)}}</strong>
                                </div>
                                <div class="col-6 text-end">
                                    <i class="las la-calendar-alt fs--18px"></i> @lang('Deadline'): <strong>{{showDateTime($job->deadline, 'd M Y')}}</strong>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="col-lg-4">
                <div class="candidate-sidebar-area">
                    <div class="candidate-sidebar">
                        <h4 class="candidate-sidebar__title">@lang('Company Overview')</h4>
                        <div class="candidate-sidebar__body">
                            <ul class="caption-list">
                                <li>
                                    <span class="caption d-flex align-items-center"><i class="las la-envelope fs--18px text--base me-2"></i> @lang('Email')</span>
                                    <span class="value blurry-text">{{__($employer->email)}}</span>
                                </li>

                                <li>
                                    <span class="caption d-flex align-items-center"><i class="las la-phone-volume fs--18px text--base me-2"></i> @lang('Phone')</span>
                                    <span class="value blurry-text">{{__($employer->mobile)}}</span>
                                </li>

                                 <li>
                                    <span class="caption d-flex align-items-center"><i class="las la-fax fs--18px text--base me-2"></i> @lang('Fax')</span>
                                    <span class="value blurry-text">{{__($employer->fax)}}</span>
                                </li>

                              @if(isset($employer->industry->name))
                                <li>
                                    <span class="caption d-flex align-items-center"><i class="las la-industry fs--18px text--base me-2"></i> @lang('Industry')</span>
                                    <span class="value">{{__($employer->industry->name)}}</span>
                                </li>
                                @endif

                                <li>
                                    <span class="caption d-flex align-items-center"><i class="las la-map-marker fs--18px text--base me-2"></i> @lang('Website')</span>
                                    <span class="value"><a href="{{$employer->website}}" class="blurry-text" target="_blank">{{$employer->website}}</a></span>
                                </li>
                                 @if(isset($employer->numberOrEmployee->employees))
                                <li>
                                    <span class="caption d-flex align-items-center"><i class="las la-user fs--18px text--base me-2"></i> @lang('Total Employee')</span>
                                    <span class="value">{{__($employer->numberOrEmployee->employees)}}</span>
                                </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                    <div class="candidate-sidebar mt-4" style="display:none;">
                        <h4 class="candidate-sidebar__title">@lang('Contact with '){{__($employer->company_name)}}</h4>
                        <div class="candidate-sidebar__body">
                            <form class="candidate-form" action="{{route('contact.with.company')}}" method="POST">
                            	@csrf
                            	<input type="hidden" name="employer_id" value="{{__($employer->id)}}">
                                <div class="form-group">
                                    <input type="text" name="name" class="form--control form-control-md" value="{{old('name')}}" placeholder="@lang('Enter name')" required="">
                                </div>

                                <div class="form-group">
                                    <input type="email" name="email" class="form--control form-control-md" value="{{old('email')}}" placeholder="@lang('Enter email')" required="">
                                </div>

                                <div class="form-group">
                                    <textarea name="message" class="form--control" placeholder="@lang('Message')" required="">{{old('message')}}</textarea>
                                </div>
                                <button type="submit" class="btn btn--base w-100">@lang('Message Now')</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<input type="hidden" name="emp_lat" value="{{$lat_long['lat']}}">
<input type="hidden" name="emp_long" value="{{$lat_long['long']}}">
<script type="text/javascript" src="https://maps.google.com/maps/api/js?key={{$googlemapkey}}&sensor=true"></script>
<script type="text/javascript">
    $(document).ready(function(){
        var lat = $('input[name="emp_lat"]').val();
        var long = $('input[name="emp_long"]').val();
        if(lat == 0 && long == 0 )
        {
            lat = 56.1304;
            long = -106.3468;
        }
        var myLatlng = new google.maps.LatLng(lat, long);
        var myOptions = {
            zoom: 12,
            center: myLatlng,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        }
        var map = new google.maps.Map(document.getElementById("map_div"), myOptions);
        marker = new google.maps.Marker({
            position: myLatlng,
            map: map,
            animation: google.maps.Animation.DROP
        });
    })
</script>
@endsection
