@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                            <tr>
                                <th>@lang('Level Of Education')</th>
                                <th>@lang('Institute Name')</th>
                                <th>@lang('Passing Year')</th>
                                <th>@lang('Exam/Degree')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($educations as $education)
                            <tr>
                                <td data-label="@lang('Level Of Education')">{{__($education->levelOfEducation->name)}}</td>
                                <td data-label="@lang('Institute Name')">{{__($education->institute)}}</td>
                                <td data-label="@lang('Passing Year')">{{__($education->passing_year)}}</td>
                                <td data-label="@lang('Exam/Degree')">{{__($education->degree->name)}}</td>
                            </tr>
                            @empty
                                <tr>
                                    <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table><!-- table end -->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('breadcrumb-plugins')
    <a href="#" onclick="window.history.go(-1); return false;" style="cursor: pointer;" class="btn btn-sm btn--primary box--shadow1 text--small" ><i class="las la-paper-plane" style="transform: rotate(180deg)"></i>@lang('Go Back')</a>
@endpush
