@php
    $brand = getContent('brand.content', true);
    $brands = getContent('brand.element', false);
@endphp
<section class="pt-80 pb-80 section--bg">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="section-header text-center">
                    <h2 class="section-title">{{__(@$brand->data_values->heading)}}</h2>
                </div>
            </div>
        </div>
        <div class="row align-items-center justify-content-center gy-5">
            @foreach($brands as $value)
                <div class="col-lg-2 col-sm-3 col-4">
                    <div class="brand-item">
                        <img src="{{getImage('assets/images/frontend/brand/'. @$value->data_values->brand_image, '220x70')}}" alt="@lang('image')">
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>