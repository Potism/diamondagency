@extends($activeTemplate.'layouts.frontend')
@section('content')
@php
    $symbol = App\Models\GeneralSetting::first();
@endphp
<div class="candidate-header">
    <div class="container">
        <div class="row align-items-center gy-4">
            <div class="col-xxl-10 col-lg-9">
                <div class="candidate">
                    <div class="candidate__thumb">
                        <img src="{{getImage(imagePath()['profile']['user']['path'].'/'.@$candidate->image)}}" alt="@lang('candidate image')">
                    </div>
                    <div class="candidate__content">
                        <h4 class="candidate__name text-white">{{__($candidate->fullname)}}</h4>
                        <span class="text--base">
                          @foreach($designation as $design)
                            @if($design->id == @$candidate->designation)
                                {{__($design->name)}}
                            @endif
                          @endforeach
                        </span>
                        <ul class="candidate__info-list mt-1">
                            <li><i class="las la-map-marker-alt"></i> <!--{{@$candidate->address->address}},--> {{@$candidate->address->city}}</li>
                            <li><i class="las la-clock"></i> @lang('Member since') {{showDateTime($candidate->created_at, 'd M Y')}}</li>
                        </ul>
                        <ul class="social-link-list d-flex align-items-center">
                            <li><a href="@if($candidate->socialMedia->facebook != '') {{__($candidate->socialMedia->facebook)}} @else {{__('#')}} @endif" @if($candidate->socialMedia->facebook != '') {{__('target="_blank"')}} @endif class="text-white fs--18px"><i class="lab la-facebook-f"></i></a></li>
                            <li><a href="@if($candidate->socialMedia->linkedin != '') {{__($candidate->socialMedia->linkedin)}} @else {{__('#')}} @endif" @if($candidate->socialMedia->linkedin != '') {{__('target="_blank"')}} @endif class="text-white fs--18px"><i class="lab la-linkedin-in"></i></a></li>
                        </ul>
                        @if(!empty($avg))
                            {!!get_rating($avg)!!}
                        @endif
                        @if(!empty($medal))
                          @if($medal == 'Bronze')
                          <img src="{{getImage(imagePath()['badge']['path'].'/61ef58fdf0f201643075837.png')}}" style="object-fit: cover;width: 35px;height: 35px;border-radius: 50%;position: relative;" alt="@lang('logo')">
                          @elseif($medal == 'Silver')
                          <img src="{{getImage(imagePath()['badge']['path'].'/61ef58d31854b1643075795.png')}}" style="object-fit: cover;width: 35px;height: 35px;border-radius: 50%;position: relative;" alt="@lang('logo')">
                          @elseif($medal == 'Gold')
                          <img src="{{getImage(imagePath()['badge']['path'].'/61ef589c8e98a1643075740.png')}}" style="object-fit: cover;width: 35px;height: 35px;border-radius: 50%;position: relative;" alt="@lang('logo')">
                          @endif
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-xxl-2 col-lg-3 text-lg-end">
            <!--@if(@$candidate->cv ===  null)
                <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#candidate_cv_modal" class="btn btn--base"><i class="las la-download fs--18px"></i> @lang('Download CV')</a>
            @else
                <a href="{{route('candidate.cv.download', encrypt($candidate->id))}}" class="btn btn--base"><i class="las la-download fs--18px"></i> @lang('Download CV')</a>
            @endif
                <a href="{{route('candidate.cv.download', encrypt($candidate->id))}}" class="btn btn--base"><i class="las la-download fs--18px"></i> @lang('Download CV')</a> -->
            </div>
        </div>
    </div>
</div>
<section class="pt-50 pb-50 section--bg">
    <div class="container">
        <div class="row gy-4">
            <div class="col-lg-8 pe-lg-4">
                <div class="candidate-details">
                    <div class="row d-flex">
                      <div class="col">
                        <h4 aria-level="3" class="mb-0">@lang('Candidate Details')</h4>
                        <p class="mt-2">{{__($candidate->details)}}</p>
                      </div>
                      <div class="d-flex col col-auto">
                        <div>
                          <!-- <h3 class="d-inline font-weight-black">
                            <span><span>{{$symbol->cur_sym}}{{__($candidate->candidate_rate)}}</span>/hr</span>
                          </h3> -->
                        </div>
                      </div>
                    </div>


                    <h4 class="mt-5">@lang('Work Experience')</h4>
                    <div class="experience-area mt-3">
                        @foreach($candidate->employment as $employment)
                            <div class="single-experience">
                                <div class="d-flex align-items-baseline">
                                    <h6 class="me-3">{{__($employment->designation)}}</h6>
                                    <span class="badge badge--base">{{showDateTime($employment->start_date, 'd M Y')}} -
                                        @if($employment->currently_work == 1)
                                            @lang('Present')
                                        @else
                                            {{showDateTime($employment->end_date, 'd M Y')}}
                                        @endif
                                    </span>
                                </div>
                                <span class="fs--14px fst-italic text--base">{{__($employment->company_name)}}.</span>
                                <p class="mt-2">{{$employment->responsibilities}}</p>
                            </div>
                        @endforeach
                    </div>

                    <h4 class="mt-5">@lang('Education History')</h4>
                    <div class="experience-area mt-3">
                        @foreach($candidate->education as $education)
                            <div class="single-experience">
                                <div class="d-flex align-items-baseline">
                                    <h6 class="me-3">{{$education->levelOfEducation->name}}</h6>
                                    <span class="badge badge--base">{{$education->passing_year}}</span>
                                </div>
                                <span class="fs--16px text--base">{{$education->degree->name}}</span>
                                <p class="mt-2">{{$education->institute}}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="candidate-sidebar-area">
                    <div class="candidate-sidebar">
                        <h4 class="candidate-sidebar__title">@lang('Profile Overview')</h4>
                        <div class="candidate-sidebar__body">
                            <ul class="caption-list">
                                <!--li>
                                    <span class="caption d-flex align-items-center"><i class="las la-envelope fs--18px text--base me-2"></i> @lang('Email')</span>
                                    <span class="value">{{__($candidate->email)}}</span>
                                </li>

                                <li>
                                    <span class="caption d-flex align-items-center"><i class="las la-phone fs--18px text--base me-2"></i> @lang('Phone')</span>
                                    <span class="value">{{__($candidate->mobile)}}</span>
                                </li>

                                 <li>
                                    <span class="caption d-flex align-items-center"><i class="las la-calendar fs--18px text--base me-2"></i> @lang('Date Of Birth')</span>
                                    <span class="value">{{showDateTime($candidate->birth_date, 'd M Y')}}</span>
                                </li>

                                <li>
                                    <span class="caption d-flex align-items-center"><i class="las la-calendar-check fs--18px text--base me-2"></i> @lang('Age')</span>
                                    <span class="value">{{Carbon\Carbon::parse($candidate->birth_date)->age}} @lang('Years')</span>
                                </li-->
                                <li>
                                    <span class="caption d-flex align-items-center"><i class="las la-wallet fs--18px text--base me-2"></i> @lang('Work Status')</span>
                                    <span class="value">{{$candidate->national_id}}</span>
                                </li>
                                <li>
                                    <span class="caption d-flex align-items-center"><i class="las la-venus-mars fs--18px text--base me-2"></i> @lang('Gender')</span>
                                    <span class="value">
                                        @if($candidate->gender == 1)
                                            @lang('Male')
                                        @elseif($candidate->gender == 2)
                                            @lang('Female')
                                        @endif
                                    </span>
                                </li>
                                <li>
                                    <span class="caption d-flex align-items-center"><i class="las la-money-bill fs--18px text--base me-2"></i> @lang('Rate')</span>
                                    <span class="value">
                                      {{$symbol->cur_sym}}
                                      @if($markup !='')
                                        @php
                                          $wage_markup = ($markup * $candidate->candidate_rate)/100;
                                          $candidate_rate = $candidate->candidate_rate + $wage_markup;
                                        @endphp
                                        {{__($candidate_rate)}}
                                      @else
                                        {{__($candidate->candidate_rate)}}
                                      @endif
                                      </span>
                                </li>
                                <li>
                                    <span class="caption d-flex align-items-center"><i class="las la-language fs--18px text--base me-2"></i> @lang('Languages')</span>
                                    <span class="value">
                                        @foreach(array_unique($candidate->language) as $value)
                                            {{$value}},
                                        @endforeach
                                    </span>
                                </li>
                                <li>
                                    <span class="caption d-flex align-items-center"><i class="las la-desktop fs--18px text--base me-2"></i> @lang('Software')</span>
                                    <span class="value">
                                      @foreach($softwares as $software)
                                           @if(@in_array($software->id, @$software_id)) {{__($software->name)}}, @endif
                                      @endforeach
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div><!-- candidate-sidebar end -->
                    <!--div class="candidate-sidebar mt-4">
                        <h4 class="candidate-sidebar__title">@lang('Contact with '){{$candidate->fullname}}</h4>
                        <div class="candidate-sidebar__body">
                            <form class="candidate-form" action="{{route('contact.with.employer')}}" method="POST">
                                @csrf
                                <input type="hidden" name="candidate_id" value="{{$candidate->id}}">
                                <div class="form-group">
                                    <input type="text" name="name" class="form--control form-control-md" placeholder="@lang('Enter name')" required="">
                                </div>
                                 <div class="form-group">
                                    <input type="email" name="email" class="form--control form-control-md" placeholder="@lang('Enter email')" required="">
                                </div>
                                <div class="form-group">
                                    <textarea name="message" class="form--control" placeholder="@lang('Message')"></textarea>
                                </div>
                                <button type="submit" class="btn btn--base w-100">@lang('Message Now')</button>
                            </form>
                        </div>
                    </div--><!-- candidate-sidebar end -->
                </div><!-- candidate-sidebar-area end -->
            </div>
        </div>
    </div>
</section>
<!-- Modal -->
<div class="modal fade custom--modal" id="candidate_cv_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">@lang('Download CV')</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @lang('No CV attached')
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn--secondary btn-sm" data-bs-dismiss="modal">@lang('OK')</button>
            </div>
        </div>
    </div>
</div>
@endsection
