@extends('admin.layouts.app')
@push('style-lib')
<style>
.project-tab {
  padding: 10%;
  margin-top: -8%;
}
.project-tab #tabs{
  background: #007b5e;
  color: #eee;
}
.project-tab #tabs h6.section-title{
  color: #eee;
}
.project-tab #tabs .nav-tabs .nav-item.show .nav-link, .nav-tabs .nav-link.active {
  color: #0062cc;
  background-color: transparent;
  border-color: transparent transparent #f3f3f3;
  border-bottom: 3px solid !important;
  font-size: 16px;
  font-weight: bold;
}
.project-tab .nav-link {
  border: 1px solid transparent;
  border-top-left-radius: .25rem;
  border-top-right-radius: .25rem;
  color: #0062cc;
  font-size: 16px;
  font-weight: 600;
}
.project-tab .nav-link:hover {
  border: none;
}
.project-tab thead{
  background: #f3f3f3;
  color: #333;
}
.project-tab a{
  text-decoration: none;
  color: #333;
  font-weight: 600;
}
table th:last-child ,table td:last-child,th,td
{
  text-align: left !important;
}
.fa-star
{
  color:#FF9529;
}
</style>
@endpush
@section('panel')
<div class="row">
  <div class="col-md-12 project-tab" id="tabs" class="">
    <nav>
      <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
        <a class="nav-item nav-link active" id="nav-allratings-tab" data-toggle="tab" href="#nav-allratings" role="tab" aria-controls="nav-allratings" aria-selected="true">List</a>
        <a class="nav-item nav-link" id="nav-gold-tab" data-toggle="tab" href="#nav-gold" role="tab" aria-controls="nav-gold" aria-selected="false">Gold</a>
        <a class="nav-item nav-link" id="nav-silver-tab" data-toggle="tab" href="#nav-silver" role="tab" aria-controls="nav-silver" aria-selected="false">Silver</a>
        <a class="nav-item nav-link" id="nav-bronze-tab" data-toggle="tab" href="#nav-bronze" role="tab" aria-controls="nav-bronze" aria-selected="false">Bronze</a>
      </div>
    </nav>
    <div class="tab-content" id="nav-tabContent">
      <div class="tab-pane fade show active" id="nav-allratings" role="tabpanel" aria-labelledby="nav-allratings-tab">
        <div class="card b-radius--10 ">
            <div class="card-body p-0">
                <div class="table-responsive--sm table-responsive">
                    <table class="table table--light style--two">
                        <thead>
                            <tr>
                                <th width="10%"></th>
                                <th>@lang('Candidate Name')</th>
                                <th>@lang('Rating')</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($all_rating as $rating)
                            <tr>
                                <td><img src="{{ getImage(imagePath()['profile']['user']['path'].'/'.$rating->image,imagePath()['profile']['user']['size'])}}" alt="@lang('Profile Image')" style="border-radius: 50%;width:35px;"></td>
                                <td data-label="@lang('Candidate Name')">{{ucfirst(__($rating->firstname))}} {{ucfirst(__($rating->lastname))}}</td>
                                <td data-label="@lang('Rating')">{{__($rating->rating)}}&nbsp;
                                  @if($rating->rating >= '5')
                                    <i style="color:#FF9529 !important;" class="fas fa-star"></i><i style="color:#FF9529 !important;" class="fas fa-star"></i><i style="color:#FF9529 !important;" class="fas fa-star"></i><i style="color:#FF9529 !important;" class="fas fa-star"></i><i style="color:#FF9529 !important;" class="fas fa-star"></i>&nbsp;
                                  @else
                                    {!!get_rating($rating->rating)!!}
                                  @endif
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
        </div>
      </div>
      <div class="tab-pane fade" id="nav-gold" role="tabpanel" aria-labelledby="nav-gold-tab">
        <div class="card b-radius--10 ">
            <div class="card-body p-0">
                <div class="table-responsive--sm table-responsive">
                    <table class="table table--light style--two">
                        <thead>
                            <tr>
                                <th width="10%"></th>
                                <th>@lang('Candidate Name')</th>
                                <th>@lang('Rating')</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($gold_array as $goldarray)
                            <tr>
                                <td><img src="{{ getImage(imagePath()['profile']['user']['path'].'/'.$goldarray['image'],imagePath()['profile']['user']['size'])}}" alt="@lang('Profile Image')" style="border-radius: 50%;width:35px;"></td>
                                <td data-label="@lang('Candidate Name')">{{__($goldarray['username'])}}</td>
                                <td data-label="@lang('Rating')">{{__($goldarray['rating'])}}
                                  @if($goldarray['rating'] >= '5')
                                    <i style="color:#FF9529 !important;" class="fas fa-star"></i><i style="color:#FF9529 !important;" class="fas fa-star"></i><i style="color:#FF9529 !important;" class="fas fa-star"></i><i style="color:#FF9529 !important;" class="fas fa-star"></i><i style="color:#FF9529 !important;" class="fas fa-star"></i>&nbsp;
                                  @else
                                    {!!get_rating($goldarray['rating'])!!}
                                  @endif
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
        </div>
      </div>
      <div class="tab-pane fade" id="nav-silver" role="tabpanel" aria-labelledby="nav-silver-tab">
        <div class="card b-radius--10 ">
            <div class="card-body p-0">
                <div class="table-responsive--sm table-responsive">
                    <table class="table table--light style--two">
                        <thead>
                            <tr>
                                <th width="10%"></th>
                                <th>@lang('Candidate Name')</th>
                                <th>@lang('Rating')</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($silver_array as $silverarray)
                            <tr>
                                <td><img src="{{ getImage(imagePath()['profile']['user']['path'].'/'.$silverarray['image'],imagePath()['profile']['user']['size'])}}" alt="@lang('Profile Image')" style="border-radius: 50%;width:35px;"></td>
                                <td data-label="@lang('Candidate Name')">{{__($silverarray['username'])}}</td>
                                <td data-label="@lang('Rating')">{{__($silverarray['rating'])}}
                                  @if($silverarray['rating'] >= '5')
                                    <i style="color:#FF9529 !important;" class="fas fa-star"></i><i style="color:#FF9529 !important;" class="fas fa-star"></i><i style="color:#FF9529 !important;" class="fas fa-star"></i><i style="color:#FF9529 !important;" class="fas fa-star"></i><i style="color:#FF9529 !important;" class="fas fa-star"></i>&nbsp;
                                  @else
                                    {!!get_rating($silverarray['rating'])!!}
                                  @endif
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
        </div>
      </div>
      <div class="tab-pane fade" id="nav-bronze" role="tabpanel" aria-labelledby="nav-bronze-tab">
        <div class="card b-radius--10 ">
            <div class="card-body p-0">
                <div class="table-responsive--sm table-responsive">
                    <table class="table table--light style--two">
                        <thead>
                            <tr>
                                <th width="10%"></th>
                                <th>@lang('Candidate Name')</th>
                                <th>@lang('Rating')</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($bronze_array as $bronzearray)
                            <tr>
                                <td><img src="{{ getImage(imagePath()['profile']['user']['path'].'/'.$bronzearray['image'],imagePath()['profile']['user']['size'])}}" alt="@lang('Profile Image')" style="border-radius: 50%;width:35px;"></td>
                                <td data-label="@lang('Candidate Name')">{{__($bronzearray['username'])}}</td>
                                <td data-label="@lang('Rating')">{{__($bronzearray['rating'])}}
                                  @if($bronzearray['rating'] >= '5')
                                    <i style="color:#FF9529 !important;" class="fas fa-star"></i><i style="color:#FF9529 !important;" class="fas fa-star"></i><i style="color:#FF9529 !important;" class="fas fa-star"></i><i style="color:#FF9529 !important;" class="fas fa-star"></i><i style="color:#FF9529 !important;" class="fas fa-star"></i>&nbsp;
                                  @else
                                    {!!get_rating($bronzearray['rating'])!!}
                                  @endif
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
        </div>
      </div>
    </div>
  </div>
</div>
</div>


@endsection

@push('script')
<script>
$( document ).ready(function() {
  $('.fa-star').removeAttr('style');
});
</script>
@endpush
