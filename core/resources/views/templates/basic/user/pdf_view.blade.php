@extends($activeTemplate.'layouts.frontend')
@section('content')
<style>
.responsive {
  width: 100%;
  height: auto;
}
</style>
<div class="pt-50 pb-50 section--bg">
    <div class="container">
        <div class="row justify-content-center gy-4">
            <!-- @include($activeTemplate . 'partials.user_sidebar') -->
            <!-- div class="col-xl-12 ps-xl-4"> 
                <div class="custom--card mt-4">
                        <div class="card-header bg--dark d-flex justify-content-between align-items-center">
                            <h5 class="text-white me-3">@lang('Credentials')</h5>
                        </div>
                    <form action="{{route('user.upload.cv')}}" method="POST" enctype="multipart/form-data" class="edit-profile-form px-3 py-3">
                        @csrf
                      <div class="row align-items-center justify-content-end">
                            <div class="col-md-4 col-sm-6 offset-6">
                              <input class="form-control" name="cv" type="file" id="formFile">
                            </div>
                            <div class="col-md-4 col-sm-6 offset-6 text-end mt-2">
                                <button type="submit" class="btn btn-sm btn--base w-100"><i class="las la-upload fs--18px"></i> @lang('Update CV')</button>
                            </div>
                        </div>
                    </form>

                    <div class="card-body">
                        @if($fullPath == null)
                            <h6 class="text-center">@lang('Please upload your CV')</h6>
                        @else
                            <iframe src="{{asset($fullPath)}}" frameborder="0" style="width:100%;min-height:640px;"></iframe>
                        @endif
                    </div>
                </div>
            </div-->
            <div class="col-xl-12 ps-xl-4"> 
                <div class="custom--card mt-4">

                    <form action="{{route('user.upload.certificate')}}" method="POST" enctype="multipart/form-data" class="edit-profile-form px-3 py-3">
                        @csrf
                      <div class="row align-items-center justify-content-end">
                            <div class="col-md-4 col-sm-6 offset-6">
                              <input class="form-control" name="certificate" type="file" id="formFile">
                            </div>
                            <div class="col-md-4 col-sm-6 offset-6 text-end mt-2">
                                <button type="submit" class="btn btn-sm btn--base w-100"><i class="las la-upload fs--18px"></i> @lang('Update Diploma/Certificate')</button>
                                <div><small><font color="red">Note: .jpg or .png only &nbsp; </font> </small></div>
                            </div>
                        </div>
                        <!--p><em>FYI: * Registration is important for Hygienist (even if they have diploma, they cannot practice without Ontario registration # which is their board exam license) 
                         <br/>* Dental Assistants must have HARP X-ray certification or complete transcripts to practice </em></p-->
                    </form>

                    <div class="card-body">
                        @if($fullPathCertificate == null)
                            <h6 class="text-center">@lang('Please upload your diploma/certificate')</h6>
                        @else
                            <img src="{{asset($fullPathCertificate)}}" alt="Nature" class="responsive" width="600" height="400">
                            <!--<iframe src="{{asset($fullPathCertificate)}}" frameborder="0" style="width:100%;min-height:640px;"></iframe>-->
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-xl-12 ps-xl-4"> 
                <div class="custom--card mt-4">

                    <form action="{{route('user.upload.license')}}" method="POST" enctype="multipart/form-data" class="edit-profile-form px-3 py-3">
                        @csrf
                      <div class="row align-items-center justify-content-end">
                            <div class="col-md-4 col-sm-6 offset-6">
                              <input class="form-control" name="license" type="file" id="formFile">
                            </div>
                            <div class="col-md-4 col-sm-6 offset-6 text-end mt-2">
                                <button type="submit" class="btn btn-sm btn--base w-100"><i class="las la-upload fs--18px"></i> @lang('Update Registration Certificate/Transcript')</button>
                            <div><small><font color="red">Note: .jpg or .png only &nbsp; </font> </small></div>
                            </div>
                        </div>
                    </form>

                    <div class="card-body">
                        @if($fullPathLicense == null)
                            <h6 class="text-center">@lang('Please upload your Registration Certificate/Transcript')</h6>
                        @else
                            <img src="{{asset($fullPathLicense)}}" alt="" class="responsive" width="600" height="400">
                            <!--<iframe src="{{asset($fullPathLicense)}}" frameborder="0" style="width:100%;min-height:640px;"></iframe>-->
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-xl-12 ps-xl-4"> 
                <div class="custom--card mt-4">

                    <form action="{{route('user.upload.drivinglicense')}}" method="POST" enctype="multipart/form-data" class="edit-profile-form px-3 py-3">
                        @csrf
                      <div class="row align-items-center justify-content-end">
                            <div class="col-md-4 col-sm-6 offset-6">
                              <input class="form-control" name="driving_license" type="file" id="formFile">
                            </div>
                            <div class="col-md-4 col-sm-6 offset-6 text-end mt-2">
                                <button type="submit" class="btn btn-sm btn--base w-100"><i class="las la-upload fs--18px"></i> @lang('Update Government Issued ID')</button><br/>
                                <small>(Passport or Driver's License. No Health Card ID)</small>
                                <div><small><font color="red">Note: .jpg or .png only &nbsp; </font> </small></div>
                            </div>
                        </div>
                    </form>

                    <div class="card-body">
                        @if($fullPathDrivingLicense == null)
                            <h6 class="text-center">@lang('Please upload your driving license')</h6>
                        @else
                            <img src="{{asset($fullPathDrivingLicense)}}" alt="Nature" class="responsive" width="600" height="400">
                            <!--<iframe src="{{asset($fullPathDrivingLicense)}}" frameborder="0" style="width:100%;min-height:640px;"></iframe>-->
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-xl-12 ps-xl-4">
                <div class="custom--card mt-4">

                    <form action="{{route('user.upload.covid19id')}}" method="POST" enctype="multipart/form-data" class="edit-profile-form px-3 py-3">
                        @csrf
                      <div class="row align-items-center justify-content-end">
                            <div class="col-md-4 col-sm-6 offset-6">
                              <input class="form-control" name="covid19id" type="file" id="formFile">
                            </div>
                            <div class="col-md-4 col-sm-6 offset-6 text-end mt-2">
                                <button type="submit" class="btn btn-sm btn--base w-100"><i class="las la-upload fs--18px"></i> @lang('Update Covid 19 ID')</button><br/>
                                <div><small><font color="red">Note: .jpg or .png only &nbsp; </font> </small></div>
                            </div>
                        </div>
                        <center><br/><h4 style="color:red;">Please Upload your Covid-19 Proof of Vaccination</h4></center>
                    </form>

                    <div class="card-body">
                        @if($fullPathcovid19id == null)
                            <h6 class="text-center">@lang('Please Covid 19 ID')</h6>
                        @else
                            <img src="{{asset($fullPathcovid19id)}}" alt="covid19id" class="responsive" width="600" height="400">
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


