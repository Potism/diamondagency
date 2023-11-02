@extends($activeTemplate.'layouts.frontend')
@section('content')
    <div class="pt-50 pb-50 section--bg">
        <div class="container">
            <div class="row justify-content-center gy-4">
                <!-- @include($activeTemplate . 'partials.user_sidebar') -->
                <div class="col-xl-12 ps-xl-4"> 
                    <div class="custom--card mt-4">
                        <div class="card-header bg--dark d-flex justify-content-between align-items-center">
                            <h5 class="text-white me-3">@lang('Employment History')</h5>
                            <button type="button" class="text--base p-0 bg-transparent" data-bs-toggle="modal" data-bs-target="#addEmployment"><i class="las la-plus"></i> @lang('Add New')</button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive--md">
                                <table class="table custom--table">
                                    <thead>
                                        <tr>
                                            <th>@lang('Company Name')</th>
                                            <th>@lang('Department')</th>
                                            <th>@lang('Designation')</th>
                                            <th>@lang('Employment Period')</th>
                                            <th>@lang('Action')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($employments as $employment)
                                            <tr>
                                                <td data-label="@lang('Company Name')">{{__($employment->company_name)}}</td>
                                                <td data-label="@lang('Department')">{{__($employment->department)}}</td>
                                                <td data-label="@lang('Designation')">{{__($employment->designation)}}</td>
                                                <td data-label="@lang('Employment Period')">{{showDateTime($employment->start_date, 'd M Y')}} - 
                                                    @if($employment->currently_work == 1) 
                                                        @lang('Present')
                                                    @else
                                                        {{showDateTime($employment->end_date, 'd M Y')}}
                                                    @endif
                                                </td>
                                                <td data-label="@lang('Action')">
                                                    <a href="javascript:void(0)" class="icon-btn bg--primary updateEmploymentHistory" data-toggle="tooltip" title="Edit" data-bs-toggle="modal" data-bs-target="#updateEmployment"
                                                        data-id = "{{$employment->id}}"
                                                        data-company_name = "{{$employment->company_name}}"
                                                        data-designation = "{{$employment->designation}}"
                                                        data-department = "{{$employment->department}}"
                                                        data-start_date = "{{$employment->start_date}}"
                                                        data-end_date = "{{$employment->end_date}}"
                                                        data-currently_work = "{{$employment->currently_work}}"
                                                        data-responsibilities = "{{$employment->responsibilities}}"
                                                    ><i class="las fa-pencil-alt text-white"></i></a>

                                                    <a href="javascript:void(0)" data-id="{{$employment->id}}"  data-bs-toggle="modal" data-toggle="tooltip" title="Delete" data-bs-target="#employmentDelete"  class="icon-btn bg--danger deleteEmploymentHistory"><i class="las la-trash-alt"></i></a>
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

<!-- Start Employment History -->
<div class="modal fade custom--modal" id="addEmployment" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">@lang('Add Employment History')</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('user.employment.store')}}" method="POST">
            @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group">
                            <label for="company_name">@lang('Company Name')</label>
                            <input type="text" name="company_name" id="company_name" class="form--control" value="{{old('company_name')}}" required="" placeholder="@lang('Enter Company Name')">
                        </div>

                        <div class="form-group">
                            <label for="department">@lang('Department')</label>
                            <input type="text" name="department" id="department" class="form--control" value="{{old('department')}}" required="" placeholder="@lang('Enter Department')">
                        </div>
              
                        <div class="form-group">
                            <label for="designation">@lang('Designation')</label>
                            <input type="text" name="designation" id="designation" class="form--control" value="{{old('designation')}}" required="" placeholder="@lang('Enter Designation')">
                        </div>

                      
                        <div class="form-group">
                            <label for="start_date">@lang('Employment Period')</label>
                            <input type="date" name="start_date" id="start_date" class="form--control" value="{{old('start_date')}}" required="" placeholder="@lang('mm/dd/yyyy')">
                        </div>

                        <div class="form-group">
                            <div class="d-flex flex-wrap align-items-center justify-content-between">
                                <label for="end_date">@lang('To')</label>
                                <div class="form-check custom--checkbox style--two text-end">
                                  <input class="form-check-input" type="checkbox" name="currently_work" value="1" id="flexCheckDefault">
                                  <label class="form-check-label fs--12px font-weight-bold" for="flexCheckDefault">
                                        @lang('Currently Work')
                                  </label>
                                </div>
                            </div>
                            <input type="date" name="end_date" id="end_date" class="form--control" value="{{old('end_date')}}" placeholder="@lang('mm/dd/yyyy')">
                        </div>

                        <div class="form-group">
                            <label for="responsibilities">@lang('Responsibilities')</label>
                            <textarea class="form--control" id="responsibilities" name="responsibilities" placeholder="@lang('Write Job Responsibilities')" required=""></textarea>
                        </div>
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

<div class="modal fade custom--modal" id="updateEmployment" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">@lang('Update Employment History')</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('user.employment.update')}}" method="POST">
                @csrf
                <input type="hidden" name="id">
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group">
                            <label for="company_name">@lang('Company Name')</label>
                            <input type="text" name="company_name" id="company_name" class="form--control" value="" required="" placeholder="@lang('Enter Company Name')">
                        </div>

                         <div class="form-group">
                            <label for="department">@lang('Department')</label>
                            <input type="text" name="department" id="department" class="form--control" value="" required="" placeholder="@lang('Enter Department')">
                        </div>
              
                        <div class="form-group">
                            <label for="designation">@lang('Designation')</label>
                            <input type="text" name="designation" id="designation" class="form--control" value="" required="" placeholder="@lang('Enter Designation')">
                        </div>
                      
                        <div class="form-group">
                            <label for="start_date">@lang('Employment Period')</label>
                            <input type="date" name="start_date" id="start_date" class="form--control" value="" required="" placeholder="@lang('mm/dd/yyyy')">
                        </div>

                        <div class="form-group">
                            <div class="d-flex flex-wrap align-items-center justify-content-between">
                                <label for="end_date">@lang('To')</label>
                                <div class="form-check custom--checkbox style--two text-end">
                                  <input class="form-check-input" type="checkbox" name="currently_work" value="1" id="flexCheckDefault">
                                  <label class="form-check-label fs--12px font-weight-bold" for="flexCheckDefault">
                                        @lang('Currently Work')
                                  </label>
                                </div>
                            </div>
                            <input type="date" name="end_date" id="end_date" class="form--control" value="" placeholder="@lang('mm/dd/yyyy')">
                        </div>

                        <div class="form-group">
                            <label for="responsibilities">@lang('Responsibilities')</label>
                            <textarea class="form--control" id="responsibilities" name="responsibilities" placeholder="@lang('Write Job Responsibilities')" required=""></textarea>
                        </div>
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

<div class="modal fade custom--modal" id="employmentDelete" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{route('user.employment.delete')}}">
                @csrf
                <input type="hidden" name="id">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">@lang('Delete Confirmation')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @lang('Are you sure you want to delete this employment history?')
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
    "use strict";
    $('.deleteEmploymentHistory').on('click', function(){
        var modal = $('#employmentDelete');
        modal.find('input[name=id]').val($(this).data('id'));
        modal.show();
    });

    $('.updateEmploymentHistory').on('click', function(){
        var modal = $('#updateEmployment');
        modal.find('input[name=id]').val($(this).data('id'));
        modal.find('input[name=company_name]').val($(this).data('company_name'));
        modal.find('input[name=designation]').val($(this).data('designation'));
        modal.find('input[name=department]').val($(this).data('department'));
        modal.find('input[name=start_date]').val($(this).data('start_date'));
        modal.find('input[name=end_date]').val($(this).data('end_date'));
        var data = $(this).data('currently_work');
        if(data == 1){
            modal.find('input[name=currently_work]').attr('checked', true);
        }else{
            modal.find('input[name=currently_work]').attr('checked', false);
        }
        modal.find('textarea[name=responsibilities]').val($(this).data('responsibilities'));
        modal.show();
    });
</script>
@endpush