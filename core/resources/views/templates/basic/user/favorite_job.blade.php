@extends($activeTemplate.'layouts.frontend')
@section('content')
    <div class="pt-50 pb-50 section--bg">
        <div class="container">
            <div class="row justify-content-center gy-4">
                <!-- @include($activeTemplate . 'partials.user_sidebar') -->
                <div class="col-xl-12 ps-xl-4"> 
                    <div class="custom--card mt-4">
                        <div class="card-header bg--dark d-flex justify-content-between align-items-center">
                            <h5 class="text-white me-3">@lang('Favorite Jobs')</h5>
                        </div>                        
                        <div class="card-body">
                            <div class="table-responsive--md">
                                <table class="table custom--table">
                                    <thead>
                                        <tr>
                                            <th>@lang('Job Title')</th>
                                            <th>@lang('Action')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($favorites as $favorite)
                                            <tr>
                                                <td data-label="Job Title">
                                                    <a href="{{route('job.detail', $favorite->job_id)}}" style="text-transform:capitalize;">{{__($favorite->job->title)}}</a>
                                                </td>

                                                <td>
                                                    <a href="javascript:void(0)" class="icon-btn bg--danger favouriteJob" data-bs-toggle="modal" data-toggle="tooltip" title="Delete" data-bs-target="#exampleModal" data-id="{{$favorite->id}}"><i class="las la-trash-alt"></i></a>
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

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{route('user.favorite.job.delete')}}">
                @csrf
                <input type="hidden" name="id">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">@lang('Delete Confirmation')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @lang('Are you sure you want to remove this favorite job?')
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
    $('.favouriteJob').on('click', function(){
        var modal = $('#exampleModal');
        modal.find('input[name=id]').val($(this).data('id'));
        modal.show();
    })
</script>
@endpush

