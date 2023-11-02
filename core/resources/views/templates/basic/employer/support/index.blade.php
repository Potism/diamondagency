@extends($activeTemplate.'layouts.frontend')
@section('content')
 <div class="pt-50 pb-50 section--bg">
        <div class="container">
            <div class="row justify-content-center gy-4">
                <!-- @include($activeTemplate . 'partials.employer_sidebar') -->
                <div class="col-xl-12 ps-xl-4"> 
                    <div class="custom--card mt-4">
                        <div class="card-header bg--dark d-flex justify-content-between align-items-center">
                            <h5 class="text-white me-3">{{__($pageTitle)}}</h5>
                            <a href="{{route('ticket.open') }}" class="text--base p-0 bg-transparent"><i class="las la-plus"></i> @lang('Add New')</a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive--md">
                                <table class="table custom--table">
                                    <thead>
                                        <tr>
                                            <th>@lang('Subject')</th>
                                            <th>@lang('Status')</th>
                                            <th>@lang('Priority')</th>
                                            <th>@lang('Last Reply')</th>
                                            <th>@lang('Action')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($supports as $key => $support)
                                            <tr>
                                                <td data-label="@lang('Subject')"> <a href="{{ route('ticket.view', $support->ticket) }}" class="font-weight-bold"> [@lang('Ticket')#{{ $support->ticket }}] {{ __($support->subject) }} </a></td>
                                                <td data-label="@lang('Status')">
                                                    @if($support->status == 0)
                                                        <span class="badge badge--success">@lang('Open')</span>
                                                    @elseif($support->status == 1)
                                                        <span class="badge badge--primary">@lang('Answered')</span>
                                                    @elseif($support->status == 2)
                                                        <span class="badge badge--warning">@lang('Customer Reply')</span>
                                                    @elseif($support->status == 3)
                                                        <span class="badge badge--dark">@lang('Closed')</span>
                                                    @endif
                                                </td>
                                                <td data-label="@lang('Priority')">
                                                    @if($support->priority == 1)
                                                        <span class="badge badge--dark">@lang('Low')</span>
                                                    @elseif($support->priority == 2)
                                                        <span class="badge badge--success">@lang('Medium')</span>
                                                    @elseif($support->priority == 3)
                                                        <span class="badge badge--primary">@lang('High')</span>
                                                    @endif
                                                </td>
                                                <td data-label="@lang('Last Reply')">{{ \Carbon\Carbon::parse($support->last_reply)->diffForHumans() }} </td>

                                                <td data-label="@lang('Action')">
                                                    <a href="{{ route('ticket.view', $support->ticket) }}" class="icon-btn" data-toggle="tooltip" title="View Ticket">
                                                        <i class="las la-desktop"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
