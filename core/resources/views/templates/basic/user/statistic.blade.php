@extends($activeTemplate.'layouts.frontend')
@section('content')
<div class="pt-50 pb-50 section--bg">
	<div class="container">
		<div class="row justify-content-center gy-4">
			<!-- @include($activeTemplate . 'partials.user_sidebar') -->
			<div class="col-xl-12 ps-xl-4">
				<div class="row gy-4 justify-content-center mt-4">
					<h5 class="text-center">@lang('You have completed ') {{__($jobCount)}} @lang('jobs')<br/>{!!get_rating($avg)!!} ({{$count}})</h5>
					<h6 class="text-center">@lang('Earn more by getting more reviews')</h6>
						<div class="col-md-6">
	                        <div class="package-card">
	                        	@forelse($badges as $badge)
		                            <div class="package-card__top" style="padding: 15px 2px;">
		                            	<!-- <input type="checkbox" name="badges" style="height: 40px;" @if($full_rate_count == $badge->no_of_rewards || $full_rate_count > $badge->no_of_rewards) checked @endif>
 -->		                            	<img src="{{getImage(imagePath()['badge']['path'].'/'.$badge->image)}}" style="@if($full_rate_count == $badge->no_of_rewards || $full_rate_count > $badge->no_of_rewards) filter: grayscale(0%); @else filter: grayscale(100%); @endif height:50px;width:50px;">
		                                <div class="content">
		                                    <h3 class="package-card__name">{{__($badge->name)}}</h3>
		                                    <div class="package-card__price" style="font-size:0.8rem;">Complete {{__($badge->no_of_rewards)}} jobs with 5 star rating to acheive this badge</div>
		                                </div>
		                            </div>
		                        @empty
                                    <div class="package-card__top">
		                                <div class="content">
		                                    <h3 class="package-card__name">@lang('No badges are available')</h3>
		                                </div>
		                            </div>
		                        @endforelse
	                            <!-- <div class="package-card__top">
	                                <input type="checkbox" name="badges" style="height: 40px;"  @if($full_rate_count == 10 || $full_rate_count > 10) checked @endif>
	                                <div class="content">
	                                    <h3 class="package-card__name">Silver Badge</h3>
	                                    <div class="package-card__price" style="font-size:0.8rem;">Complete 10 jobs with 5 star rating to acheive this badge</div>
	                                </div>
	                            </div>
	                            <div class="package-card__top">
	                                <input type="checkbox" name="badges" style="height: 40px;"  @if($full_rate_count == 25 || $full_rate_count > 25) checked @endif>
	                                <div class="content">
	                                    <h3 class="package-card__name">Gold Badge</h3>
	                                    <div class="package-card__price" style="font-size:0.8rem;">Complete 25 jobs with 5 star rating to acheive this badge</div>
	                                </div>
	                            </div> -->
	                        </div>
	                    </div>
                </div>
			</div>
		</div>
	</div>
</div>
@endsection