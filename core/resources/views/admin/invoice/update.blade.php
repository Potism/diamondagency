@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
          <form action="{{route('admin.invoice.editstore')}}" method="POST">
              @csrf
              @foreach($invoices as $invoice)
              <h2>{{__($invoice->prefix)}}-00{{__($invoice->id)}}</h2><br/>
              <input type="hidden" name="invoice_prefix" value="{{$invoice->prefix}}">
              <input type="hidden" name="invoice_id" value="{{$invoice->id}}">
              <input type="hidden" name="job_type" id="job_type" value="{{$invoice->job_type}}">
              <input type="hidden" name="full_timerate " id="full_timerate" value="{{$invoice->job->category->full_timerate}}">
              <input type="hidden" name="temp_rate" id="temp_rate" value="{{$invoice->job->category->temp_rate}}">
              <input type="hidden" name="markup_rate" id="markup_rate" value="{{$invoice->job->category->markup_rate}}">
                  <div class="row">
                      <div class="col-md-6">
                          <div class="form-group">
                              <label class="form-control-label  font-weight-bold">@lang('Job') </label>
                                  @foreach($jobs as $job)
                                       @if($job->id == @$invoice->job_id) <p> {{ucfirst(__($job->title))}}</p> @endif
                                  @endforeach
                          </div>
                      </div>
                      <div class="col-md-6">
                          <div class="form-group">
                              <label class="form-control-label font-weight-bold">@lang('Candidate Name'):</label>
                                  <p>{{ucwords($invoice->user->firstname)}} {{ucwords($invoice->user->lastname)}}</p>
                          </div>
                      </div>
                  </div>
                  <div class="row">
                      <div class="col-md-6">
                          <div class="form-group ">
                              <label class="form-control-label font-weight-bold">
                                @php
                                if(trim($invoice->prefix) == 'AGINV')
                                {
                                @endphp
                                  @lang('Category Rate')
                                @php
                                }
                                else if(trim($invoice->prefix) == 'EMINV')
                                {
                                @endphp
                                  @lang('Hourly Rate') (<span style="color:red">with markeup rate</span>)
                                @php
                                }
                                else
                                {
                                @endphp
                                  @lang('Hourly Rate')
                                @php
                                }
                                @endphp
                              </label>
                              <input class="form-control" type="text" id="hourly_price" name="hourly_price" value="{{number_format(round($invoice->hourly_price,1),2)}}" placeholder="Enter Invoice Amount" onkeyup="input_change('{{$invoice->prefix}}')" required="">
                          </div>
                      </div>
                      @php
                      if(trim($invoice->prefix) != 'AGINV')
                      {
                      @endphp
                      <div class="col-md-6">
                          <div class="form-group ">
                              <label class="form-control-label font-weight-bold">@lang('Working Hours') </label>
                              <br/>
                              @php
                              $hr = array();
                              $mins = array();
                              $time_exist = strpos($invoice->working_hours, 'hr');
                              if($time_exist == 1)
                              {
                                $time = explode(':',$invoice->working_hours);
                                $hr = explode('hr',$time[0]);
                                $mins = explode('mins',$time[1]);
                              }
                              else
                              {
                                $hr[0] = '';
                                $mins[0] = '';
                              }
                              @endphp
                              <select class="select" style="min-height:40px;width: auto;text-indent:0;" onchange="input_change('{{$invoice->prefix}}')" id="hours" name="hours">
                                @for ($i = 1; $i < 25; $i++)
                                <option value="{{$i}}" @if($hr[0] == $i) selected @endif >{{$i}}</option>
                                @endfor
                              </select>
                              <label>Hr</label>&nbsp;:&nbsp;
                              <select class="select" style="min-height:40px;width: auto;text-indent:0;" id="minutes" name="minutes" onchange="input_change('{{$invoice->prefix}}')">
                                @for ($j = 0; $j < 60; $j++)
                                    @if($mins[0] != '')
                                      @if($j < 10)
                                        <option value="0{{$j}}"  @if($mins[0] == '0'.$j) selected @endif >0{{$j}}</option>
                                      @else
                                        <option value="{{$j}}"  @if($mins[0] == $j) selected @endif >{{$j}}</option>
                                      @endif
                                    @else
                                        @if($j < 10)
                                        <option value="0{{$j}}">0{{$j}}</option>
                                      @else
                                        <option value="{{$j}}">{{$j}}</option>
                                      @endif
                                    @endif
                                @endfor
                              </select>
                              <label>Mins</label>
                          </div>
                      </div>
                        <div class="col-md-6">
                            <div class="form-group ">
                                <label class="form-control-label font-weight-bold">@lang('Tax Rate') </label>
                                <input class="form-control" type="text" id="tax_rate" name="tax_rate" value="{{round($invoice->tax_rate,2)}}" placeholder="Enter Invoice Amount" required="" readonly>
                            </div>
                        </div>
                        @php
                        }
                        @endphp
                        <div class="col-md-6">
                            <div class="form-group ">
                                <label class="form-control-label font-weight-bold">@lang('Tax Amount') </label>
                                <input class="form-control" type="text" id="tax_amount" name="tax_amount" value="{{round($invoice->tax_amt,2)}}" placeholder="Enter Invoice Amount" required="" readonly>
                            </div>
                        </div>
                        @php
                        if(trim($invoice->prefix) != 'AGINV')
                        {
                        @endphp
                        <div class="col-md-6">
                            <div class="form-group ">
                                <label class="form-control-label font-weight-bold">@lang('Invoice Amount') </label>
                                <input class="form-control" type="text" id="invoice_amount" name="invoice_amount" value="{{round($invoice->invoice_amount,2)}}" placeholder="Enter Invoice Amount"  required="" readonly>
                            </div>
                        </div>
                        @php
                        }
                        @endphp
                          <div class="col-md-6">
                              <div class="form-group ">
                                  <label class="form-control-label font-weight-bold">@lang('Invoice Amount with Tax Rate') </label>
                                  <input class="form-control" type="text" id="invoice_amt_with_tax" name="invoice_amt_with_tax" value=" {{round($invoice->invoice_amt_with_tax,2)}}" placeholder="Enter Invoice Amount" required="" readonly>
                              </div>
                            </div>
                        </div>
                        <input type="hidden" id="jobapplyid" name="jobapplyid"/>
                  <div class="row" style="justify-content: center;">
                      <div class="col-md-6">
                          <div class="form-group">
                              <button type="submit" class="btn btn--primary btn-block btn-lg">@lang('Save')
                              </button>
                          </div>
                      </div>
                  </div>
                  @endforeach
          </form>
        </div>
      </div>
    </div>
@endsection

@push('script')
    <script>
    function input_change($prefix)
    {
      if($prefix == 'AGINV')
      {
        var cat_rate = $('#hourly_price').val();
        var tax = $('#tax_amount').val();
        var total = parseFloat(cat_rate) + parseFloat(tax);
        $('#invoice_amt_with_tax').val(total.toFixed(2));
      }
      else
      {
        var job_type = $('#job_type').val();
        var rate = $('#hourly_price').val();
        var tax_rate = $('#tax_rate').val();
        var working_hours = $('#hours').val()+'.'+$('#minutes').val();
        var total = parseFloat(rate) * working_hours;
        var tax_amt = (total * rate)/100;
        var a = parseFloat(tax_amt) + parseFloat(total);
        var invoice_tax_rate = a.toFixed(2);
        $('#invoice_amount').val(total.toFixed(2));
        $('#tax_amount').val(tax_amt.toFixed(2))
        $('#invoice_amt_with_tax').val(invoice_tax_rate);
      }

    }
    </script>
@endpush
