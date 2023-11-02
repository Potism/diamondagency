@extends($activeTemplate.'layouts.frontend')
@section('content')
    <div class="pt-50 pb-50 section--bg">
        <div class="container">
            <div class="row justify-content-center gy-4">
                <!-- @include($activeTemplate . 'partials.user_sidebar') -->
                <div class="col-xl-12 ps-xl-4">
                    <div class="custom--card mt-4">
                        <div class="card-header bg--dark d-flex justify-content-between align-items-center">
                            <h5 class="text-white me-3">@lang('Educational Qualification')</h5>
                            <button type="button" class="text--base p-0 bg-transparent" data-bs-toggle="modal" data-bs-target="#addEducation"><i class="las la-plus"></i> @lang('Add New')</button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive--md">
                                <table class="table custom--table">
                                    <thead>
                                        <tr>
                                            <th>@lang('Level Of Education')</th>
                                            <th>@lang('Graduation Year')</th>
                                            <th>@lang('Exam/Degree')</th>
                                            <th>@lang('Action')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($educations as $education)
                                            <tr>
                                                <td data-label="@lang('Level Of Education')">{{__($education->levelOfEducation->name)}}</td>
                                                <td data-label="@lang('Graduation Year')">{{__($education->passing_year)}}</td>
                                                <td data-label="@lang('Exam/Degree')">{{__($education->degree->name)}}</td>
                                                <td data-label="@lang('Action')">
                                                    <a href="javascript:void(0)" class="icon-btn bg--primary"><i class="las fa-pencil-alt text-white updateEducationHistory" data-toggle="tooltip" title="Edit" data-bs-toggle="modal" data-bs-target="#updateEducation"
                                                        data-id="{{$education->id}}"
                                                        data-level_of_education = "{{$education->level_of_education_id}}"
                                                        data-institute = "{{$education->institute}}"
                                                        data-passing_year = "{{$education->passing_year}}"
                                                        data-degree_id = "{{$education->degree_id}}"
                                                        ></i></a>
                                                    <a href="javascript:void(0)" class="icon-btn bg--danger deleteEducationHistory" data-toggle="tooltip" title="Delete" data-bs-toggle="modal" data-bs-target="#educationDelete" data-id={{$education->id}}><i class="las la-trash-alt"></i></a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td class="text-muted text-center" colspan="100%">@lang('No data found')</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



<!-- Start Education Qualification -->
<div class="modal fade custom--modal" id="addEducation" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">@lang('Add Education Qualification')</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('user.education.store')}}" method="POST">
            @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="level">@lang('Level Of Education')</label>
                        <select class="form--control" name="level_id">
                            <option value="">@lang('Select One')</option>
                            @foreach($levels as $level)
                                <option value="{{$level->id}}">{{__($level->name)}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="level">@lang('Institute Name')</label>
                        <input type="text" name="institute" class="form--control" value="{{old('Institute')}}" required="" placeholder="@lang('Enter institute name')">
                    </div>

                    <div class="form-group">
                        <label for="level">@lang('Graduation Year')</label>
                        <select class="form--control" name="passing_year" id="passing_year">
                            <option>@lang('Select One')</option>
                            @for ($i = 1960; $i < 2031; $i++)
                            <option value="{{$i}}">{{$i}}</option>
                            @endfor
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="degree">@lang('Exam/Degree')</label>
                        <select class="form--control" name="degree" id="degree">
                            <option>@lang('Select One')</option>
                            @foreach($degrees as $degree)
                                <option value="{{$degree->id}}">{{__($degree->name)}}</option>
                            @endforeach
                        </select>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn--secondary btn-sm" data-bs-dismiss="modal">@lang('Close')</button>
                    <button type="submit" class="btn btn--success btn-sm" data-bs-dismiss="modal">@lang('Submit')</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade custom--modal" id="updateEducation" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">@lang('Update Education Qualification')</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('user.education.update')}}" method="POST">
            @csrf
                <input type="hidden" name="id">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="level">@lang('Level Of Education')</label>
                        <select class="form--control" name="level_id">
                            <option value="">@lang('Select One')</option>
                            @foreach($levels as $level)
                                <option value="{{$level->id}}">{{__($level->name)}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="level">@lang('Institute Name')</label>
                        <input type="text" name="institute" class="form--control" value="{{old('Institute')}}" required="" placeholder="@lang('Enter institute name')">
                    </div>

                    <div class="form-group">
                        <label for="level">@lang('Graduation Year')</label>
                        <input type="text" name="passing_year" class="form--control" value="{{old('passing_year')}}" required="" placeholder="@lang('Enter Passing Year')">
                    </div>

                    <div class="form-group">
                        <label for="degree">@lang('Exam/Degree')</label>
                        <select class="form--control" name="degree" id="degree">
                            <option>@lang('Select One')</option>
                            @foreach($degrees as $degree)
                                <option value="{{$degree->id}}">{{__($degree->name)}}</option>
                            @endforeach
                        </select>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn--secondary btn-sm" data-bs-dismiss="modal">@lang('Close')</button>
                    <button type="submit" class="btn btn--success btn-sm" data-bs-dismiss="modal">@lang('Submit')</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade custom--modal" id="educationDelete" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{route('user.education.delete')}}">
                @csrf
                <input type="hidden" name="id">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">@lang('Delete Confirmation')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @lang('Are you sure you want to delete this educational qualification?')
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--secondary btn-sm" data-bs-dismiss="modal">@lang('Close')</button>
                    <button type="submit" class="btn btn--primary btn-sm">@lang('Confirm')</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    "uss strict";
     $('.deleteEducationHistory').on('click', function(){
        var modal = $('#educationDelete');
        modal.find('input[name=id]').val($(this).data('id'));
    });

    $('.updateEducationHistory').on('click', function(){
        var modal = $('#updateEducation');
        modal.find('input[name=id]').val($(this).data('id'));
        modal.find('select[name=level_id]').val($(this).data('level_of_education'));
        modal.find('input[name=institute]').val($(this).data('institute'));
        modal.find('input[name=passing_year]').val($(this).data('passing_year'));
        modal.find('select[name=degree]').val($(this).data('degree_id'));
        modal.show();
    });
</script>
@endpush
