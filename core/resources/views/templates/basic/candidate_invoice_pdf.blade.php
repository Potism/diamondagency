@php
$contact = getContent('contact_us.content', true);
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Invoice Details</title>
  <style type="text/css">
  @page { margin: 10px; }
  body { margin: 10px;
    font-family: sans-serif;
  }
  h2,span
  {
    color:red;
  }
  p,h3 {
    margin: 0;
    padding: 0;
  }
  span
  {
    font-style: oblique;
  }
  .avatar {
    vertical-align: middle;
    width: 100px;
  }
  tr,td,table
  {
    border:none;
  }
  table
  {
    border-bottom: 1px solid #6082B6;
    width: 100%;
  }
  td
  {
    vertical-align:top
  }
  </style>
</head>
<body>
  <div class="container" style="margin:0 5%;">
    <table style="padding-top:5%;">
      <tbody>
        <tr>
          <td width="15">
            <img class="avatar"  src="{{getImage(imagePath()['logoIcon']['path'] .'/invoice_logo.png')}}" alt="@lang('image')">
          </td>
          <td>
            <p style="font-weight: bold;font-size:12px;">
              {{$general->sitename}}
            </p>
            <p  style="font-weight: bold;font-size:12px;">
              {{__(@$contact->data_values->email_address)}}
            </p>
            <p style="font-weight: bold;font-size:12px;">
              {{__(@$contact->data_values->contact_details)}}
            </p>
            <p style="font-weight: bold;font-size:12px;">
              Tel #{{__(@$contact->data_values->contact_number)}}
            </p>
          </td>
          <td align="right">
            <p>Pay Statement : {{__($invoice->prefix)}}-00{{__($invoice->id)}}</p>
            <p>Issue Date : {{showDateTime($invoice->created_at, 'm/d/Y')}}</p>
          </td>
        </tr>
      </tbody>
    </table>
    <p style="width:100%">  
      <h4 style="color: #357ec2;"><center>PAY STATEMENT</center></h4>
      <h4>
        @if($invoice->job_type == 'temp_job')
        TEMPORARY
        @else
        FULL TIME
        @endif
        : {{__(strtoupper($job_details[0]->user->firstname.' '.$job_details[0]->user->lastname))}} - {{__($job_details[0]->job->title)}}
      </h4>
    </p>
    <table>
      <tbody>
          <td width="60%">
            <p><b>Bill To:</b></p>
            <p>{{__($job_details[0]->job->employer->company_name)}}</p>
            <p>{{__($job_details[0]->job->employer->email)}}</p>
            <p>{{$job_details[0]->job->employer->address->address}}, {{$job_details[0]->job->employer->address->city}}, {{$job_details[0]->job->employer->address->state}},  {{$job_details[0]->job->employer->address->zip}}</p>
            <p>{{$job_details[0]->job->employer->address->country}} </p>
          </td>
          <td width="40%">
            <p><b>Payment</b></p>
            <p>Due on {{showDateTime($invoice->created_at, 'M d,Y')}}</p>
          </td>
        </tr>
      </tbody>
    </table>
    <table>
        <tr>
          <td width="49%">
            <h4>Description</h4>
          </td>
          <td align="right" width="17%">
            <h4>Hour</h4>
          </td>
          <td align="right" width="17%">
            <h4>Rate</h4>
          </td>
          <td align="right" width="17%">
            <h4>Amount</h4>
          </td>
        </tr>
    </table>
    <table>
      <tbody>
        <tr>
          <td  width="49%">
            <h4>{{$general->cur_sym}} {{getAmount($invoice->hourly_price)}}/hr- {{$job_details[0]->job->title}}</h4>
            <p>{{showDateTime($invoice->created_at, 'm/d/Y')}}</p>
            <p>JobRef: #{{$job_details[0]->job->id}}</p>
          </td>
          <td align="right" width="17%">
            <h4>{{__($invoice->working_hours)}} </h4>
          </td>
          <td align="right" width="17%">
            <h4>{{$general->cur_sym}} {{getAmount($invoice->hourly_price)}}</h4>
          </td>
          <td align="right" width="17%">
            <h4>{{$general->cur_sym}} {{getAmount(($invoice->invoice_amount))}}</h4>
          </td>
        </tr>
      </tbody>
    </table>
    <table style="border-bottom: 1px solid transparent;">
      <tbody>
        <tr>
          <td>
            <h4 style="line-height:5px;">Total </h4>
          </td>
          <td align="right">
            <h4 style="line-height:5px;">{{$general->cur_sym}} {{getAmount($invoice->invoice_amount)}}</h4>
          </td>
        </tr>
      </tbody>
    </table>
    <p style="width:100%;">  
      <h4 style="color: red;margin-bottom:5px;">Note:</h4>
      <p>
        This Pay Statement includes HST. Please pay your HST, if applicable.
      </p>
      <p style="color: red;margin-top:15px;">
        E-transfer Fee: {{$general->cur_sym}}1.50
      </p>
    </p>
  </div>
</body>

</html>