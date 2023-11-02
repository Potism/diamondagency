@php
    $feature = getContent('feature.content', true);
    $features = getContent('feature.element', false);
@endphp

<section class="pt-80 pb-80 dark--overlay bg_img" style="background-image: url({{getImage('assets/images/frontend/feature/'. @$feature->data_values->background_image, '1920x1281')}});">
    <div class="container">
        <div class="row justify-content-center">
            <div style="width: 90%;"><!--div class="col-xxl-6 col-xl-7 col-lg-8"-->
                <div class="section-header text-center">
                    <h2 class="section-title text-white">{{__(@$feature->data_values->heading)}}</h2>
                    <p class="mt-2 text-white">{{__(@$feature->data_values->sub_heading)}}</p>
                </div>
            </div>
        </div>
        <div class="row gy-4">
            @foreach($features as $value)
                <div class="col-lg-3 col-sm-6">
                    <div class="feature-card text-center">
                        <div class="feature-card__icon">
                            @php echo $value->data_values->feature_icon @endphp
                        </div>
                        <div class="feature-card__content">
                            <h4 class="text-white">{{__($value->data_values->title)}}</h4>
                            <p class="mt-2 text-white">{{__($value->data_values->description)}}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>