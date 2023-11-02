@php
    $cta = getContent('cta.content', true);
@endphp

<section class="cta-section pt-80 pb-80">
    <div class="container">
        <div class="row gy-4 justify-content-between align-items-center">
            <div class="col-lg-6">
                <div class="cta-thumb">
                    <img src="{{getImage('assets/images/frontend/cta/'. @$cta->data_values->cta_image, '946x887')}}" alt="@lang('image')">
                </div>
            </div>
            <div class="col-lg-5 text-lg-start text-center">
                <h2 class="cta-title">{{__(@$cta->data_values->heading)}}</h2>
                <a href="{{url(@$cta->data_values->btn_url)}}" class="btn btn--base mt-4">{{__(@$cta->data_values->btn_name)}}</a>
            </div>
        </div>
    </div>
</section>