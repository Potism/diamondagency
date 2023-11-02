@php
    $about_us = getContent('about_us.content', true);
    $about_uss = getContent('about_us.element', false, 8 , true);
@endphp
<section class="pt-100 pb-100">
    <div class="container">
        <div class="row justify-content-center">
            <div><!--div class="col-xxl-6 col-xl-7 col-lg-8"-->
                <div class="section-header text-center">
                    <h2 class="section-title">{{__(@$about_us->data_values->heading)}}</h2>
                    <p class="mt-2 ">{{__(@$about_us->data_values->sub_heading)}}</p>
                </div>
            </div>
        </div>
        <div class="row gy-4" style="padding-right: calc(var(--bs-gutter-x) * .5);padding-left: calc(var(--bs-gutter-x) * .5);margin-top: var(--bs-gutter-y);">
            @foreach($about_uss as $value)
                <div class="col-lg-12 col-md-6">
                    <div class="blog-post row">
                        <div class="blog-post__thumb rounded-3 col-lg-4" style="padding-top: 1rem !important;">
                            @if(isset($value->data_values->about_us_image))
                                <img src="{{getImage('assets/images/frontend/about_us/'. $value->data_values->about_us_image, '768x520')}}" alt="@lang('about us image')" class="w-100 h-100 object-fit--cover">
                            @else
                                <img src="{{getImage('assets/images/default.png', '768x520')}}" alt="@lang('about us image')" class="w-100 h-100 object-fit--cover">                                
                            @endif
                        </div>
                        <div class="blog-post__content col-lg-8">
                            <h4 class="blog-post__title">{{__($value->data_values->title)}}</a></h4>
                            <p class="mt-2">{!!html_entity_decode($value->data_values->description_nic)!!}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

