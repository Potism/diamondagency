@extends($activeTemplate.'layouts.frontend')
@section('content')
    <section class="pt-80 pb-80">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-12">
                     @php echo $data->data_values->details @endphp
                </div>
            </div>
        </div>
    </section>
@endsection