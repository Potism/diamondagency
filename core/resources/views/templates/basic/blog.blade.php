@extends($activeTemplate.'layouts.frontend')
@section('content')
    <section class="pt-100 pb-100">
        <div class="container">
            <div class="row gy-4">
                @foreach($blogs as $blog)
                    <div class="col-lg-4 col-md-6">
                        <div class="blog-post">
                            <div class="blog-post__thumb rounded-3">
                                <span class="blog-post__date"><i class="las la-calendar"></i> {{showDateTime($blog->created_at, 'd M Y')}}</span>
                                <a href="{{route('blog.details', [$blog->id, slug($blog->data_values->title)])}}" class="d-block h-100">
                                    <img src="{{getImage('assets/images/frontend/blog/'. $blog->data_values->blog_image, '768x520')}}" alt="@lang('blog image')" class="w-100 h-100 object-fit--cover">
                                </a>
                            </div>
                            <div class="blog-post__content">
                                <h4 class="blog-post__title"><a href="{{route('blog.details', [$blog->id, slug($blog->data_values->title)])}}">{{__($blog->data_values->title)}}</a></h4>
                                <p class="mt-2">{{str_limit(strip_tags($blog->data_values->description_nic), 80)}}</p>
                                <a href="{{route('blog.details', [$blog->id, slug($blog->data_values->title)])}}" class="text--base text-decoration-underline mt-4">@lang('Read More')</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    
@if($sections->secs != null)
    @foreach(json_decode($sections->secs) as $sec)
        @include($activeTemplate.'sections.'.$sec)
    @endforeach
@endif

@endsection

