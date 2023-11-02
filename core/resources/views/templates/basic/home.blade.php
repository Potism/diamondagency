@extends($activeTemplate.'layouts.frontend')
@section('content')
@php
	$banner = getContent('banner.content', true);
@endphp
<section class="hero bg_img" style="background-image: url({{getImage('assets/images/frontend/banner/'. @$banner->data_values->background_image, '1920x1281')}});">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-lg-7 text-center">
				<h2 class="hero__title text-white">{{__(@$banner->data_values->heading)}}</h2>
			</div>
		</div><!-- row end -->
		<div class="row justify-content-center mt-5">
			<div class="col-xl-8 col-lg-9">
				<form action="{{route('job.filter')}}" method="GET" class="hero__form">
					<div class="input-field">
						<select name="city" class="form--control">
							<option value="">@lang('Select Province')</option>
							@foreach($cities as $city)
                                <option value="{{$city->id}}" @if($city->id == @$cityId) selected @endif data-locations="{{json_encode($city->location)}}">{{__($city->name)}}</option>
                            @endforeach
						</select>
						<i class="las la-search"></i>
					</div>
					<div class="input-field">
						<select class="form--control" name="location">
							<option value="" selected="" disabled="">@lang('Select One')</option>
						</select>
						<i class="las la-map-marker"></i>
					</div>
					@if(!auth()->user() && !auth()->guard('employer')->user())
						<a href="{{route(('login'))}}" class="hero__form-btn">@lang('Search')</a>
					@else
						<button type="submit" class="hero__form-btn">@lang('Search')</button>
					@endif
				</form>
			</div>
		</div>
	</div>
</section>

<div class="pt-50 pb-50 section--bg2">
    <div class="container">
        <div class="row">
        	<div class="col-lg-12 mb-5">
        		<h3 class="text-center text-white">@lang('Browse Category')</h3>
        	</div>
	        <div class="col-lg-12">
	            <ul class="job-cat-list">
	                 @foreach($categorys as $category)
						@if(!auth()->user() && !auth()->guard('employer')->user())
							<li><a href="{{route('login')}}">{{__($category->name)}} ({{$category->job->count()}})</a></li>
						@else
							<li><a href="{{route('job.category', $category->id)}}">{{__($category->name)}} ({{$category->job->count()}})</a></li>
						@endif
	                @endforeach
	            </ul>
	        </div>
	    </div> 
    </div>
</div>
 
@if($sections->secs != null)
    @foreach(json_decode($sections->secs) as $sec)
        @include($activeTemplate.'sections.'.$sec)
    @endforeach
@endif
@endsection


@push('script')
<script>
	'use strict';
  	$('select[name=city]').on('change',function() {
        $('select[name=location]').html('<option value="" selected="" disabled="">@lang('Select One')</option>');
        var locations = $('select[name=city] :selected').data('locations');
        var html = '';
        locations.forEach(function myFunction(item, index) {
            html += `<option value="${item.id}">${item.name}</option>`
        });
        $('select[name=location]').append(html);
    });
</script>
@endpush
