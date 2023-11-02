@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two" id="lowbalance" cellpadding='1'>
                            <thead>
                            <tr>
                                <th>@lang('Company Name')</th>
                                <th>@lang('Contact Person')</th>
                                <th>@lang('Phone No.')</th>
                                <th>@lang('Email Address')</th>
                                <th>@lang('Balance')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($emplyers as $user)
                              @if(getAmount($user->balance) < 500)
                                <tr>
                                    <td data-label="@lang('Company-Name')">
                                        <span class="font-weight-bold">{{$user->company_name}}</span>
                                    </td>

                                    <td data-label="@lang('User')">
                                      <span>{{ $user->username }}</span>
                                    </td>
                                    <td data-label="@lang('Mobile')">
                                        <span>{{ $user->mobile }}</span>
                                    </td>


                                    <td data-label="@lang('Email')">
                                        <span>{{ $user->email }}</span>
                                    </td>

                                    <td data-label="@lang('Balance')">
                                        <span class="font-weight-bold">{{$general->cur_sym}}{{getAmount($user->balance)}}</span>
                                    </td>
                                </tr>
                              @endif
                            @empty
                                <tr>
                                    <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                </tr>
                            @endforelse

                            </tbody>
                        </table><!-- table end -->
                    </div>
                </div>
                <div class="card-footer py-4">
                    {{ paginateLinks($emplyers) }}
                </div>
            </div>
        </div>


    </div>
@endsection



@push('breadcrumb-plugins')
<a href="#" class="btn btn-sm btn--primary box--shadow1 text--small report_print" ><i class="fa fa-fw fa-paper-plane"></i>@lang('Print Report')</a>
@endpush
@push('script')
<script>
$('.report_print').click(function(){
    var divToPrint=document.getElementById("lowbalance");
    newWin= window.open("");
    newWin.document.write('<html><head><title>Low Balance</title>');
    newWin.document.write('<style type = "text/css">');
    newWin.document.write('body{font-family: Arial;font-size: 10pt;}table{border: 1px solid #ccc;border-collapse: collapse;}table th{background-color: #F7F7F7;color: #333;font-weight: bold;}table th, table td{padding: 5px;border: 1px solid #ccc;}');
    newWin.document.write('</style>');
    newWin.document.write('</head>');
    newWin.document.write('<body>');
    newWin.document.write(divToPrint.outerHTML);
    newWin.document.write('</body>');
    newWin.document.write('</html>');
    newWin.print();
})
</script>
@endpush
