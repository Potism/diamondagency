@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
          <form action="{{route('admin.invoice.store')}}" method="POST">
              @csrf
                  <div class="row">
                      <div class="col-md-6">
                          <div class="form-group">
                              <label class="form-control-label  font-weight-bold">@lang('Job')</label>
                              <select class="form-control" name="job" id="job" required="" onchange="get_amount();">
                                  <option value="">@lang('Select One')</option>
                                  @foreach($jobs as $job)
                                      <option value="{{$job->id}}">{{__($job->title)}}</option>
                                  @endforeach
                              </select>
                          </div>
                      </div>
                      <div class="col-md-6">
                          <div class="form-group">
                              <label class="form-control-label font-weight-bold">@lang('Select User')</label>
                              <select class="form-control" id="user" name="user" required="" onchange="get_amount();">
                                  <option value="">@lang('Select One')</option>
                                  @foreach($users as $user)
                                      <option value="{{$user->id}}">{{__($user->firstname)}} {{__($user->lastname)}}</option>
                                  @endforeach
                              </select>
                          </div>
                      </div>
                  </div>
                  <div class="row">
                      <div class="col-md-6">
                          <div class="form-group ">
                              <label class="form-control-label font-weight-bold">@lang('Invoice Amount') </label>
                              <input class="form-control" type="text" id="invoice_amount" name="invoice_amount" value="" placeholder="Enter Invoice Amount">
                          </div>
                      </div>
                      <div class="col-md-6">
                          <div class="form-group ">
                              <label class="form-control-label font-weight-bold">@lang('Hourly Price') </label>
                              <input class="form-control" type="text" id="hourly_price" name="hourly_price" value="" placeholder="Enter Invoice Amount">
                          </div>
                      </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group ">
                                <label class="form-control-label font-weight-bold">@lang('Tax Rate') </label>
                                <input class="form-control" type="text" id="tax_rate" name="tax_rate" value="" placeholder="Enter Invoice Amount">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group ">
                                <label class="form-control-label font-weight-bold">@lang('Tax Amount') </label>
                                <input class="form-control" type="text" id="tax_amount" name="tax_amount" value="" placeholder="Enter Invoice Amount">
                            </div>
                        </div>
                      </div>
                      <div class="row">
                          <div class="col-md-6">
                              <div class="form-group ">
                                  <label class="form-control-label font-weight-bold">@lang('Invoice Amount with Tax Rate') </label>
                                  <input class="form-control" type="text" id="invoice_amt_with_tax" name="invoice_amt_with_tax" value="" placeholder="Enter Invoice Amount">
                              </div>
                            </div>
                              <div class="col-md-6">
                                  <div class="form-group ">
                                      <label class="form-control-label font-weight-bold">@lang('Working Hours') </label>
                                      <input class="form-control" type="text" id="working_hours" name="working_hours" value="" placeholder="Enter Invoice Amount">
                                  </div>
                              </div>
                        </div>
                        <input type="hidden" id="jobapplyid" name="jobapplyid"/>
                  <div class="row" style="justify-content: center;">
                      <div class="col-md-6">
                          <div class="form-group">
                              <button type="submit" class="btn btn--primary btn-block btn-lg">@lang('Create Invoice')
                              </button>
                          </div>
                      </div>
                  </div>
          </form>
        </div>
      </div>
    </div>
@endsection

@push('script')
    <script>
      function get_amount()
      {
        var user = $('#user').val();
        var job = $('#job').val();
        if(job == '')
        {
          alert('Select the job');
        }
        else if(user == '')
        {
          alert('Select the user');
        }
        else
        {
          $.ajax({
              url:"{{ route('admin.invoice.get_amount') }}",
              type:"POST",
              data: {
                "_token": "{{ csrf_token() }}",
              'user': user,
              'job':job
              },
              success:function (data) {
              var  key = JSON.parse(data);
                $('#invoice_amount').val(key.invoice_amount);
                $('#hourly_price').val(key.hourly_price);
                $('#tax_rate').val(key.tax_rate);
                $('#tax_amount').val(key.tax_amt);
                $('#invoice_amt_with_tax').val(key.invoice_amt_with_tax);
                $('#working_hours').val(key.working_hours);
                $('#jobapplyid').val(key.jobapplyid);
                if(key.hourly_price == '0')
                {
                  alert('Ask customer to add the hourly rate');
                }
              }
            })
        }
      }
    </script>
@endpush
