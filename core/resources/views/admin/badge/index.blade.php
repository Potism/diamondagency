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
                                    <th>@lang('No of Rewards')</th>
                                    <th>@lang('Image')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse($badges as $badge)
                                <tr>
                                    <td data-label="@lang('Name')"><strong>{{__($badge->name)}}</strong></td>
                                    <!--td data-label="@lang('no_of_rewards')">{{__($badge->no_of_rewards)}}</td-->
                                    <td data-label="@lang('No of Rewards')">{{__($badge->no_of_rewards)}}</td>
                                    <td> <img src="{{ getImage(imagePath()['badge']['path'].'/'.$badge->image)}}" alt="@lang('Profile Image')" class="" style="overflow: none; width: 30px;height: 30px; display: inline-block;"></td>

                                    <td data-label="@lang('Action')">
                                        <a href="javascript:void(0)" class="icon-btn btn--primary ml-1 updateBadge"
                                            data-id="{{$badge->id}}"
                                            data-name="{{$badge->name}}"
                                            data-no_of_rewards="{{$badge->no_of_rewards}}"
                                            data-icon="{{$badge->image}}"
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
                    {{paginateLinks($badges)}}
                </div>
            </div>
        </div>
    </div>
    <div id="badgeModel" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Add Badge')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('admin.badge.store')}}" method="POST"  enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name" class="form-control-label font-weight-bold">@lang('Name')</label>
                            <input type="text" class="form-control form-control-lg" name="name" id="name" placeholder="@lang("Enter Name")"  maxlength="120" required="">
                        </div>
                        <div class="form-group">
                            <label for="no_of_rewards" class="form-control-label font-weight-bold">@lang('No of Rewards')</label>
                            <input type="text" class="form-control form-control-lg" name="no_of_rewards" id="no_of_rewards" placeholder="@lang("Enter no of Rewards point")"  required="">
                        </div>
                        <div class="form-group">
                            <input type='file' name="image" class="profilePicUpload" id="profilePicUpload1" accept=".png, .jpg, .jpeg" />
                            <label for="profilePicUpload1" class="btn btn--base">@lang('Select Badge image')</label>
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


    <div id="updateBadgeModel" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Update Badge')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('admin.badge.update')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name" class="form-control-label font-weight-bold">@lang('Name')</label>
                            <input type="text" class="form-control form-control-lg" name="name" id="name" placeholder="@lang("Enter Name")"  maxlength="120" required="">
                        </div>
                        <div class="form-group">
                            <label for="no_of_rewards" class="form-control-label font-weight-bold">@lang('No of Rewards')</label>
                            <input type="text" class="form-control form-control-lg" name="no_of_rewards" id="no_of_rewards" placeholder="@lang("Enter no of Rewards point")"  required="">
                        </div>
                        <div class="form-group">
                          <input type='file' name="image" class="profilePicUpload" id="profilePicUpload1" accept=".png, .jpg, .jpeg" />
          								<label for="profilePicUpload1" class="btn btn--base">@lang('Select Badge image')</label>
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
    <a href="javascript:void(0)" class="btn btn-sm btn--primary box--shadow1 text--small addBadge" ><i class="fa fa-fw fa-paper-plane"></i>@lang('Add New Badge')</a>
@endpush

@push('script')
<script>
    "use strict";
    $('.addBadge').on('click', function() {
        $('#badgeModel').modal('show');
    });

    $('.updateBadge').on('click', function() {
        var modal = $('#updateBadgeModel');
        modal.find('input[name=id]').val($(this).data('id'));
        modal.find('input[name=name]').val($(this).data('name'));
        modal.find('input[name=no_of_rewards]').val($(this).data('no_of_rewards'));
        modal.find('input[name=image]').val($(this).data('image'));
        modal.modal('show');
    });

</script>
@endpush
