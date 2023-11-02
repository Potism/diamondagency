@php
    $package = getContent('package.content', true);
    $plans = App\Models\Plan::where('status', 1)->get();
@endphp
<section class="pt-80 pb-80 section--bg2">
    <div class="container">
        <div class="row gy-4">
            <div class="col-lg-5 pe-lg-5">
                <div class="package-details-wrapper">
                    <h2 class="title text-white">{{__(@$package->data_values->heading)}}</h2>
                    <p class="mt-3 text-white">{{__(@$package->data_values->sub_heading)}}</p>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="row gy-4 justify-content-center">
                    @foreach($plans as $plan)
                        <div class="col-md-6">
                            <div class="package-card">
                                <div class="package-card__top">
                                    <div class="icon">
                                        @php echo $plan->icon @endphp
                                    </div>
                                    <div class="content">
                                        <h3 class="package-card__name">{{__($plan->name)}}</h3>
                                        <div class="package-card__price">{{$general->cur_sym}}{{getAmount($plan->amount)}} <!-- <sub>/ {{__($plan->duration)}} @lang('month')</sub> --></div>
                                    </div>
                                </div>
                                <!-- <div class="package-card__content">
                                    <ul class="package-card__feature-list">
                                        <li>@lang('You can create') <span class="badge badge--base">{{$plan->job_post}} @lang('job posts')</span></li>
                                        @foreach($plan->services as $value)
                                            @php 
                                                $service = App\Models\Service::find($value) 
                                            @endphp
                                            <li>{{__($service->name)}}</li>
                                        @endforeach
                                    </ul>
                                </div> -->
                                <div class="package-card__footer">
                                    <a href="javascript:void(0)" data-bs-toggle="modal" data-plan_id="{{$plan->id}}" data-bs-target="#exampleModal" class="btn btn--dark w-100 mt-4 planSubscribe">@lang('Buy')</a>
                                </div>  
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal -->
<div class="modal fade custom--modal" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{route('employer.plan.subscribe')}}">
                @csrf
                <input type="hidden" name="plan_id">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">@lang('Subscribe Plan')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @lang('Are you sure you want to subscribe this plan?')
                    <div class="form-group mt-3">
                        <select class="form--control" name="payment" required="">
                            <option value="">@lang('Select One')</option>
                            <option value="1">{{__($general->sitename)}} @lang('Wallet')</option>
                            <option value="2">@lang('Checkout')</option>
                        </select>   
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--secondary btn-md" data-bs-dismiss="modal">@lang('Close')</button>
                    <button type="submit" class="btn btn--primary btn-md">@lang('Confirm')</button>
                </div>
            </form>
        </div>
    </div>
</div>


@push('script')
<script>
    "use strict";
    $('.planSubscribe').on('click', function(){
        var modal = $('#exampleModal');
        modal.find('input[name=plan_id]').val($(this).data('plan_id'));
    })
</script>
@endpush

