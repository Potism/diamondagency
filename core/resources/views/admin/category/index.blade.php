@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Name')</th>
                                    <th>@lang('Main Category')</th>
                                    <th>@lang('Permanent Agency Fee')</th>
                                    <th>@lang('Temporary Agency Fee')</th>
                                    <th>@lang('Permanent Markup')</th>
                                    <th>@lang('Temp Markup')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse($categorys as $category)
                                <tr>
                                    <td data-label="@lang('Name')"><strong>{{__($category->name)}}</strong></td>
                                    <td data-label="@lang('Main Category')">
                                      @foreach($industries as $industrie)
                                        @if($industrie->id == @$category->cat_id) {{__($industrie->name)}}@endif
                                      @endforeach
                                    </td>
                                    <td data-label="@lang('Permanent Rate')">{{__($category->full_timerate)}}</td>
                                    <td data-label="@lang('Temporary Rate')">{{__($category->temp_rate)}}</td>
                                    <td data-label="@lang('Permanent Markup')">{{__($category->markup_rate)}}</td>
                                    <td data-label="@lang('Temp Markup')">{{__($category->temp_markup_rate)}}</td>
                                    <td data-label="@lang('Status')">
                                        @if($category->status == 1)
                                            <span class="badge badge--success">@lang('Enable')</span>
                                        @else
                                            <span class="badge badge--danger">@lang('Disable')</span>
                                        @endif
                                    </td>
                                    <td data-label="@lang('Action')">
                                        <a href="javascript:void(0)" class="icon-btn btn--primary ml-1 updateCategory"
                                            data-id="{{$category->id}}"
                                            data-name="{{$category->name}}"
                                            data-cat_id="{{$category->cat_id}}"
                                            data-full_timerate="{{$category->full_timerate}}"
                                            data-temp_rate="{{$category->temp_rate}}"
                                            data-markup_rate="{{$category->markup_rate}}"
                                            data-temp_markup_rate="{{$category->temp_markup_rate}}"
                                            data-status ="{{$category->status}}"
                                        ><i class="las la-pen"></i></a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-muted text-center" colspan="100%">{{__($emptyMessage) }}</td>
                                </tr>
                            @endforelse

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer py-4">
                    {{paginateLinks($categorys)}}
                </div>
            </div>
        </div>
    </div>


    <div id="categoryModel" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Add Category')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('admin.category.store')}}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name" class="form-control-label font-weight-bold">@lang('Name')</label>
                            <input type="text" class="form-control form-control-lg" name="name" id="name" placeholder="@lang("Enter Name")"  maxlength="120" required="">
                        </div>
                        <div class="form-group">
                            <label for="name" class="form-control-label font-weight-bold">@lang('Main Category')</label>
                            <br>
                            <select class="form--control" name="cat_id" id="cat_id" required="" style="width:100%">
                              <option value="">@lang('Select One')</option>
                                @foreach($industries as $industrie)
                                    <option value="{{$industrie->id}}" @if($industrie->id == @$user->cat_id) selected @endif>{{__($industrie->name)}}</option>
                                @endforeach
                            </select>
                          </div>
                        <div class="form-group">
                            <label for="name" class="form-control-label font-weight-bold">@lang('Permanent Rate')</label>
                            <input type="text" class="form-control form-control-lg" name="full_timerate" id="name" placeholder="@lang("Enter Full Time Rate")"  required="">
                        </div>
                        <div class="form-group">
                            <label for="name" class="form-control-label font-weight-bold">@lang('Temporary Rate')</label>
                            <input type="text" class="form-control form-control-lg" name="temp_rate" id="temp_rate" placeholder="@lang("Enter Temporary Rate")" required="">
                        </div>
                        <div class="form-group">
                            <label for="name" class="form-control-label font-weight-bold">@lang('Permanent Markup') (%)</label>
                            <input type="text" class="form-control form-control-lg" name="markup_rate" id="markup_rate" placeholder="@lang("Enter Markup Rate")" required="">
                        </div>
                        <div class="form-group">
                            <label for="name" class="form-control-label font-weight-bold">@lang('Temp Markup') (%)</label>
                            <input type="text" class="form-control form-control-lg" name="temp_markup_rate" id="temp_markup_rate" placeholder="@lang("Enter Temp Markup Rate")" required="">
                        </div>
                        <div class="form-group">
                            <label class="form-control-label font-weight-bold">@lang('Status') </label>
                            <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger"
                                data-toggle="toggle" data-on="@lang('Enable')" data-off="@lang('Disable')" name="status">
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn--primary"><i class="fa fa-fw fa-paper-plane"></i>@lang('Create')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div id="updateCategoryModel" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Update Category')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('admin.category.update')}}" method="POST">
                    @csrf
                    <input type="hidden" name="id">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name" class="form-control-label font-weight-bold">@lang('Name')</label>
                            <input type="text" class="form-control form-control-lg" id="name" name="name" placeholder="@lang("Enter Name")"  maxlength="120" required="">
                        </div>
                        <div class="form-group">
                            <label for="name" class="form-control-label font-weight-bold">@lang('Main Category')</label>
                            <br>
                            <select class="form--control" name="cat_id" id="cat_id" required="" style="width:100%">
                              <option value="">@lang('Select One')</option>
                                @foreach($industries as $industrie)
                                    <option value="{{$industrie->id}}" @if($industrie->id == @$user->cat_id) selected @endif>{{__($industrie->name)}}</option>
                                @endforeach
                            </select>
                          </div>
                        <div class="form-group">
                            <label for="name" class="form-control-label font-weight-bold">@lang('Permanent Rate')</label>
                            <input type="text" class="form-control form-control-lg" name="full_timerate" id="name" placeholder="@lang("Enter Full Time Rate")"  required="">
                        </div>
                        <div class="form-group">
                            <label for="name" class="form-control-label font-weight-bold">@lang('Temporary Rate')</label>
                            <input type="text" class="form-control form-control-lg" name="temp_rate" id="temp_rate" placeholder="@lang("Enter Temporary Rate")" required="">
                        </div>
                        <div class="form-group">
                            <label for="name" class="form-control-label font-weight-bold">@lang('Parmanent Markup') (%)</label>
                            <input type="text" class="form-control form-control-lg" name="markup_rate" id="markup_rate" placeholder="@lang("Enter Markup Rate")" required="">
                        </div>
                        <div class="form-group">
                            <label for="name" class="form-control-label font-weight-bold">@lang('Temp Markup') (%)</label>
                            <input type="text" class="form-control form-control-lg" name="temp_markup_rate" id="temp_markup_rate" placeholder="@lang("Enter Temp Markup Rate")" required="">
                        </div>
                        <div class="form-group">
                            <label class="form-control-label font-weight-bold">@lang('Status') </label>
                            <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger"
                                data-toggle="toggle" data-on="@lang('Enable')" data-off="@lang('Disabled')" name="status">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn--primary"><i class="fa fa-fw fa-paper-plane"></i>@lang('Update')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <a href="javascript:void(0)" class="btn btn-sm btn--primary box--shadow1 text--small addCategory" ><i class="fa fa-fw fa-paper-plane"></i>@lang('Add Job Category')</a>
@endpush

@push('script')
<script>
    "use strict";
    $('.addCategory').on('click', function() {
        $('#categoryModel').modal('show');
    });

    $('.updateCategory').on('click', function() {
        var modal = $('#updateCategoryModel');
        modal.find('input[name=id]').val($(this).data('id'));
        modal.find('input[name=name]').val($(this).data('name'));
        modal.find('select[name=cat_id]').val($(this).data('cat_id'));
        modal.find('input[name=full_timerate]').val($(this).data('full_timerate'));
        modal.find('input[name=temp_rate]').val($(this).data('temp_rate'));
        modal.find('input[name=markup_rate]').val($(this).data('markup_rate'));
        modal.find('input[name=temp_markup_rate]').val($(this).data('temp_markup_rate'));
        var data = $(this).data('status');
        if(data == 1){
            modal.find('input[name=status]').bootstrapToggle('on');
        }else{
            modal.find('input[name=status]').bootstrapToggle('off');
        }
        modal.modal('show');
    });
</script>
@endpush
