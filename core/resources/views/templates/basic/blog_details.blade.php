@extends($activeTemplate.'layouts.frontend')
@section('content')
<section class="pt-100 pb-100 section--bg">
	<div class="container">
		<div class="row gy-4 justify-content-center">
			<div class="col-lg-8">
				<div class="blog-details-wrapper">
					<h2 class="title mb-4">{{__($blog->data_values->title)}}</h2>
					<span class="fst-italicmk fs--14px text--muted" >{{showDateTime($blog->created_at, 'd M Y')}}<br/><br/></span>
					<div class="thumb">
						<img src="{{getImage('assets/images/frontend/blog/'. $blog->data_values->blog_image, '768x520')}}" alt="@lang('image')">
					</div>
					@php echo $blog->data_values->description_nic  @endphp
				</div>
				<div class="fb-comments" data-href="{{ route('blog.details',[$blog->id,slug($blog->data_values->title)]) }}" data-numposts="5"></div>
			</div>
			<div class="col-lg-4 mt-lg-0 mt-5">
				<div class="sidebar">
					<div class="widget">
						<h3 class="widget__title">@lang('Recent Posts')</h3>
						<ul class="small-post-list">
							@foreach($recentBlogs as $value)
								<li class="small-post">
									<div class="small-post__thumb">
										<img src="{{getImage('assets/images/frontend/blog/'. $value->data_values->blog_image, '768x520')}}" alt="@lang('img')">
									</div>
									<div class="small-post__content">
										<h6 class="small-post__title"><a href="{{route('blog.details', [$value->id, slug($value->data_values->title)])}}">{{__($value->data_values->title)}}</a></h6>
										<span class="fst-italic fs--14px text--muted">{{showDateTime($value->created_at, 'd M Y')}}</span>
									</div>
								</li>
							@endforeach
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
@endsection
@push('fbComment')
	@php echo loadFbComment() @endphp
@endpush
