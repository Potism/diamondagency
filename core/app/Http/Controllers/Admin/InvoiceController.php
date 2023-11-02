<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Employer;
use App\Models\Job;
use App\Models\User;
use App\Models\GeneralSetting;
use App\Models\JobApply;
use App\Models\Category;
use Illuminate\Http\Request;
use File;
use Illuminate\Support\Facades\Auth;
use PDF;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $pageTitle= "List Of Invoices";
      $emptyMessage = "No data found";
      $storage_path = storage_path('app/public/uploads/');
      $invoices = Invoice::select('*')->latest()->with('user','job','job.employer')->paginate(getPaginate());
      return view('admin.invoice.index', compact('pageTitle', 'emptyMessage','invoices','storage_path'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
      $pageTitle= "Create Invoice";
      $users = User::orderBy('id','desc')->paginate(getPaginate());
      $jobs = Job::orderBy('id','desc')->paginate(getPaginate());
      return view('admin.invoice.create', compact('pageTitle','users','jobs'));
    }
    public function editstore(Request $request)
    {
      $invoice = Invoice::select('*')->latest()->where('invoice_amount',$request->invoice_amount)->where('hourly_price',$request->hourly_price)->where('tax_rate',$request->tax_rate)->where('tax_amt',$request->tax_amount)->where('invoice_amt_with_tax',$request->invoice_amt_with_tax)->where('working_hours',$request->working_hours)->count();
      if($invoice == 0)
      {
        if(File::exists(storage_path('app/public/uploads/'.$request->invoice_prefix.'-00'.$request->invoice_id.'.pdf')))
        {
          File::delete(storage_path('app/public/uploads/'.$request->invoice_prefix.'-00'.$request->invoice_id.'.pdf'));
        }
        if($request->invoice_prefix == 'AGINV')
        {
          $this->generateAgencyPdf($request);
        }
        else if($request->invoice_prefix == 'CNINV')
        {
          $this->generatePdf($request);
        }
        else
        {
          $this->generateEMINVPdf($request);
        }
        $notify[]  = ['success', 'Invoice is updated successfully'];
        return back()->withNotify($notify);
        $pageTitle= "List Of Invoices";
        $emptyMessage = "No data found";
        $invoices = Invoice::select('*')->latest()->with('user','job','job.employer')->paginate(getPaginate());
        return view('admin.invoice.index', compact('pageTitle', 'emptyMessage','invoices'));
      }
      $pageTitle= "List Of Invoices";
      $emptyMessage = "No data found";
      $invoices = Invoice::select('*')->latest()->with('user','job','job.employer')->paginate(getPaginate());
      return view('admin.invoice.index', compact('pageTitle', 'emptyMessage','invoices'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
      $pageTitle= "Edit Invoice";
      $emptyMessage = "No data found";
      $users = User::orderBy('id','desc')->paginate(getPaginate());
      $jobs = Job::orderBy('id','desc')->paginate(getPaginate());
      $invoices = Invoice::select('*')->latest()->where('id',$id)->with('user','job','job.employer','job.category')->paginate(getPaginate());
      return view('admin.invoice.update', compact('pageTitle', 'emptyMessage','invoices','jobs','users'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function destroy(Invoice $invoice)
    {
        //
    }
    public function generatePdf($val = array())
    {
        $user = Auth::user();
        $invoice = Invoice::findOrFail($val->invoice_id);
        $jobApplys = JobApply::where('user_id', $invoice->user_id)->with('job', 'job.employer','job.category', 'user')->where('status',1)->where('accept_by_user',1)->where('job_id',$invoice->job_id)->get();
        $invoice->invoice_amount = $val->invoice_amount;
        $invoice->tax_rate = $val->tax_rate;
        $invoice->tax_amt = $val->tax_amount;
        $invoice->hourly_price = $val->hourly_price;
        $invoice->invoice_amt_with_tax = $val->invoice_amt_with_tax;
        $invoice->working_hours = $val->hours.'hr : '.$val->minutes.'mins';
        $invoice->save();
        $data['invoice'] = $invoice;
        $data['job_details'] = $jobApplys;
        $pdf = PDF::loadView('templates.basic.candidate_invoice_pdf', $data);
        $pdf->setOptions(['isPhpEnabled' => true,'isRemoteEnabled' => true,'isHtml5ParserEnabled' => true]);
        header("Content-type: application/pdf");
        header("Content-Transfer-Encoding: binary");
        $filename = 'CNINV-00'.$invoice->id.'.pdf';
        $pdf->save(storage_path('app/public/uploads/'.$filename));

    }
    public function generateEMINVPdf($val = array())
    {
        $user = Auth::user();
        $invoice = Invoice::findOrFail($val->invoice_id);
        $jobApplys = JobApply::where('user_id', $invoice->user_id)->with('job', 'job.employer','job.category', 'user')->where('status',1)->where('accept_by_user',1)->where('job_id',$invoice->job_id)->get();
        $invoice->invoice_amount = $val->invoice_amount;
        $invoice->tax_rate = $val->tax_rate;
        $invoice->tax_amt = $val->tax_amount;
        $invoice->hourly_price = $val->hourly_price;
        $invoice->invoice_amt_with_tax = $val->invoice_amt_with_tax;
        $invoice->working_hours = $val->hours.'hr : '.$val->minutes.'mins';
        $invoice->save();
        $data['invoice'] = $invoice;
        $data['job_details'] = $jobApplys;
        $pdf = PDF::loadView('templates.basic.candidate_invoice_pdf', $data);
        $pdf->setOptions(['isPhpEnabled' => true,'isRemoteEnabled' => true,'isHtml5ParserEnabled' => true]);
        header("Content-type: application/pdf");
        header("Content-Transfer-Encoding: binary");
        $filename = 'EMINV-00'.$invoice->id.'.pdf';
        $pdf->save(storage_path('app/public/uploads/'.$filename));

    }

    public function generateAgencyPdf($val = array())
    {
        $user = Auth::user();
        $invoice = Invoice::findOrFail($val->invoice_id);
        $jobApplys = JobApply::where('user_id', $invoice->user_id)->with('job', 'job.employer','job.category', 'user')->where('status',1)->where('accept_by_user',1)->where('job_id',$invoice->job_id)->get();
        $invoice->invoice_amount = $val->hourly_price;
        $invoice->tax_rate = $val->tax_rate;
        $invoice->tax_amt = $val->tax_amount;
        $invoice->hourly_price = $val->hourly_price;
        $invoice->invoice_amt_with_tax = $val->invoice_amt_with_tax;
        $invoice->working_hours = '1';
        $invoice->save();
        $data['invoice'] = $invoice;
        $data['job_details'] = $jobApplys;
        $pdf = PDF::loadView('templates.basic.invoice_pdf', $data);
        $pdf->setOptions(['isPhpEnabled' => true,'isRemoteEnabled' => true,'isHtml5ParserEnabled' => true]);
        header("Content-type: application/pdf");
        header("Content-Transfer-Encoding: binary");
        $filename = 'AGINV-00'.$invoice->id.'.pdf';
        $pdf->save(storage_path('app/public/uploads/'.$filename));
    }
    public function lowbalance()
    {
      $pageTitle= "Low (Prepaid) Balance Accounts";
      $emptyMessage = 'No data found';
      $emplyers = Employer::orderBy('id','desc')->where('balance','<','200')->paginate(getPaginate());
      return view('admin.invoice.lowbalance', compact('pageTitle','emplyers','emptyMessage'));
    }
}
