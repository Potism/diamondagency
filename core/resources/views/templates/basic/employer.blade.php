@extends($activeTemplate.'layouts.frontend')
@section('content')
<div class="search-job-area section--bg2">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <form action="{{route('company.search')}}" method="GET" class="job-search-form">
                    <div class="row gy-4">
                        <div class="col-md-5 col-sm-6">
                            <div class="custom-icon-field">
                                <i class="las la-search"></i>
                               <select class="form--control" name="industry_id" style="text-indent:35px;line-height:38px;padding-left:0;">
                               		<option value="" selected="">@lang('Select Industry')</option>
                               		@foreach($industries as $industry)
                               			<option value="{{$industry->id}}" @if($industry->id == @$industryId) selected @endif>{{__($industry->name)}}</option>
                               		@endforeach
                               </select>
                            </div>
                        </div>
                        <div class="col-md-5 col-sm-6">
                            <div class="custom-icon-field">
                                <i class="las la-map-marker-alt"></i>
                              	<input type="text" name="search" value="{{@$search}}" class="form--control" placeholder="@lang('Enter company name')">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn--base">@lang('Search')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="pt-50 pb-50">
	<div class="container">
		<div class="row gy-4 justify-content-center">
			@foreach($employers as $employer)
				<div class="col-xl-4 col-md-6">
					<div class="company-card d-flex flex-wrap">
						<div class="company-card__thumb">
							<img src="{{getImage(imagePath()['employerLogo']['path'].'/'.@$employer->image)}}" alt="@lang('image')">
						</div>
						<div class="company-card__content">
							<div class="left">
								<h4 class="title"><a href="{{route('profile', [slug($employer->company_name), $employer->id])}}">{{__($employer->company_name)}}</a></h4>
								<p class="fs--14px mt-1">{{@$employer->address->city}}, {{@$employer->address->state}}-{{@$employer->address->zip}}</p>
								<p class="fs--14px text--base mt-1">{{$employer->jobs->count()}} @lang('jobs opening')</p>
							</div>
							<div class="right">
								<a href="{{route('profile', [slug($employer->company_name), $employer->id])}}" class="btn btn--base btn-sm"><i class="las la-arrow-right"></i></a>
							</div>
						</div>
					</div><!-- company-card -->
				</div>
			@endforeach
		</div>
	</div>
</div>
@if(is_array($sections))
  @if($sections->secs != null)
    @foreach(json_decode($sections->secs) as $sec)
        @include($activeTemplate.'sections.'.$sec)
    @endforeach
  @endif
@endif

@endsection


