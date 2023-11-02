@php 
    $featureJobs = App\Models\Job::where('status', 1)->latest()->orderBy('id', 'DESC')->where('featured', 1)->whereDate('deadline','>=',date("Y-m-d"))->with('employer')->limit(16)->get();
    $feature_content = getContent('feature_job.content', true);
@endphp

<section class="pt-80 pb-80 section--bg">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="section-header text-center">
                    <h2 class="section-title">{{__($feature_content->data_values->heading)}} <i class="lab la-hotjar text--base"></i></h2>
                </div>
            </div>
        </div>
        <div class="row g-3">
            @foreach($featureJobs as $featureJob)
              @php
                if($lat_long)
                {
                    $emp_add = $featureJob->employer->address;
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
                <div class="col-xl-3 col-lg-4 col-6">
                    <div class="short-job-card">
                        <div class="short-job-card__top">
                            <div class="short-job-card__thumb">
                                <img src="{{getImage(imagePath()['employerLogo']['path'].'/'.@$featureJob->employer->image)}}" alt="@lang('image')">
                            </div>
                            <div class="short-job-card__content">
                                @if(!auth()->user() && !auth()->guard('employer')->user())
                                <h6 class="short-job-card__title"><a href="{{route('login')}}">{{$featureJob->title}}</a></h6>
                                <div class="short-job-card__bottom flex-wrap align-items-center justify-content-between mt-1">
                                    <p class="caption"><a href="{{route('login')}}" class="text--base">{{__($featureJob->employer->company_name)}}</a>
                                    </p>
                                    <p class="fs--14px text--muted">{{diffforhumans($featureJob->created_at)}}</p>
                                </div>
                              @else
                                <h6 class="short-job-card__title"><a href="{{route('job.detail', $featureJob->id)}}">{{$featureJob->title}}</a></h6>
                                <div class="short-job-card__bottom flex-wrap align-items-center justify-content-between mt-1">
                                    <p class="caption"><a href="{{route('profile', [slug($featureJob->employer->company_name), $featureJob->employer_id])}}" class="text--base">{{__($featureJob->employer->company_name)}}</a>
                                     @if($distance != '')
                                        <br/>{{$distance}} KM
                                    @endif
                                    </p>
                                    <p class="fs--14px text--muted">{{diffforhumans($featureJob->created_at)}}</p>
                                </div>
                              @endif
                            </div>
                            <div class="short-job-card__action">
                                <a href="{{route('user.favorite.item', $featureJob->id)}}" class="bookmark-btn" >
                                    <span class="non-bookmark"><i class="far fa-bookmark"></i></span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div> 
    </div> 
</section>