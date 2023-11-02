@extends('admin.layouts.app')
@section('panel')
@push('style-lib')
<style>
.icon_btn
{
  margin-left: 6px !important;
}
td
{
    white-space: nowrap !important;
}
</style>
@endpush
    <div class="row">
        <div class="col-lg-12">
          <table class="table table--light style--two table-responsive">
              <thead>
                <tr>
                    <th>@lang('Invoice No')</th>
                    <th>@lang('Job Title')</th>
                    <th>@lang('Invoice Amount')</th>
                    <th>@lang('Employer')</th>
                    <th>@lang('Candidate')</th>
                    <th>@lang('Invoice Date')</th>
                    <th>@lang('Manage')</th>
                </tr>
              </thead>
              <tbody>
              @forelse($invoices as $invoice)
              <input type="hidden" id="url" name="url" value="{{url('core/storage/app/public/uploads/')}}">
              <iframe src='{{url("core/storage/app/public/uploads/".$invoice->prefix."-00".$invoice->id.".pdf")}}' id='iframe' name='iframe' frameborder='0' style='border:0;' width='0' height='0'></iframe>
                  <tr>
                    <td data-label="@lang('Invoice No')">
                        {{__($invoice->prefix)}}-00{{__($invoice->id)}}
                    </td>
                    <td data-label="@lang('Title')">
                        @isset($invoice->job->title)
                            {{__($invoice->job->title)}}
                        @endisset
                    </td>
                    <td data-label="@lang('Invoice Amount')">
                        $ {{(($invoice->prefix == 'CNINV' || $invoice->prefix == 'EMINV' )?round($invoice->invoice_amount,2):round($invoice->invoice_amt_with_tax,2))}}
                    </td>
                    <td data-label="@lang('Employer')">
                        @isset($invoice->job->employer->company_name)
                        {{ucfirst(__($invoice->job->employer->company_name))}}
                        @endisset
                    </td>
                    <td data-label="@lang('Candidate')">
                        {{ucfirst(__($invoice->user->firstname))}}&nbsp;{{ucfirst(__($invoice->user->lastname))}}
                    </td>
                    <td data-label="@lang('Invoice Date')">{{ showDateTime($invoice->created_at) }}</td>
                    <td data-label="@lang('Manage')">
                        <a href="{{route('preview.invoice',$invoice->prefix.'-00'.$invoice->id)}}" target="_blank" class="icon-btn icon_btn" style="background:green;"><i class="fa fa-eye"></i>&nbsp;Preview</a>
                        <a href="{{route('admin.invoice.update',$invoice->id)}}" class="icon-btn icon_btn" style="background:orange;"><i class="fa fa-pen"></i>&nbsp;Edit</a>
                        <a href="{{route('download.invoice',$invoice->prefix.'-00'.$invoice->id)}}" class="icon-btn icon_btn bg--primary"><i class="fa fa-file-pdf"></i>&nbsp;Pdf</a>
                        <a href="#" onclick="printpdf('{{$invoice->prefix}}','{{$invoice->id}}');" id="print" class="icon-btn icon_btn bg--primarymk" style="background:red;"><i class="fa fa-paper-plane"></i>&nbsp;Print</a>
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
@endsection
@push('script')
<script>
function printpdf(p,id)
{
  var url = $('#url').val();
  var wnd = window.open(url+'/'+p+'-00'+id+'.pdf');
  setTimeout(
   function() {
       wnd.print();
   }, 100);
};
</script>
@endpush
