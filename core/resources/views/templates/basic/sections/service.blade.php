@php
    $service = getContent('service.content', true);
    $services = getContent('service.element', false, 4 , true);
@endphp
<section class="pt-100 pb-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xxl-12 col-xl-12 col-lg-12">
                <div class="section-header text-center">
                    <h2 class="section-title">{{__(@$service->data_values->heading)}}</h2>
                    <p class="mt-2 ">{{__(@$service->data_values->sub_heading)}}</p>
                </div>
            </div>
        </div>
        <div class="row gy-4">
            @foreach($services as $value)
                <div class="col-lg-3 col-md-6">
                    <div class="blog-post" style="height:100%;">
                        <div class="blog-post__thumb rounded-3">
                            @if(isset($value->data_values->service_image))
                                <img src="{{getImage('assets/images/frontend/service/'. $value->data_values->service_image, '768x520')}}" alt="@lang('service image')" class="w-100 h-100 object-fit--cover">
                            @else
                                <img src="{{getImage('assets/images/default.png', '768x520')}}" alt="@lang('service image')" class="w-100 h-100 object-fit--cover">                                
                            @endif
                        </div>
                        <div class="blog-post__content">
                            @isset($value->data_values->title)
                              <h4 class="blog-post__title" style="text-align: center;">{{__($value->data_values->title)}}</a></h4>
                            @endisset
                            @isset($value->data_values->description_nic)
                              <p class="mt-2">{{str_limit(strip_tags($value->data_values->description_nic), 500)}}</p>
                            @endisset
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

