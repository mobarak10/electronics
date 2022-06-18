@extends('layouts.user')

@section('title', __('contents.barcode'))

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 py-3 p-print-0">
                <div class="card border-0">

                    <div class="card-header d-flex justify-content-between align-items-center print-none">
                        <h5 class="m-0">@lang('contents.batch_generator') </h5>

                        <div class="btn-group" role="group" aria-level="Action area">
                            <a href="{{ route('barcode.invoice') }}" class="btn btn-success">
                                <i class="fa fa-refresh" aria-hidden="true"></i>
                            </a>

                            <button type="button" class="btn btn-primary" onclick="window.print()">
                                <i class="fa fa-print" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>

                    <div class="card-body p-print-0">

                        <!-- generator -->
                        <div class="print-none">
                            <form action="{{ route('barcode.invoice') }}" method="GET" class="d-flex">
                                <input type="text" name="voucher_no" class="form-control mr-3" placeholder="Enter voucher number" required>
                                <button type="submit" class="btn btn-primary text-white">GO</button>
                            </form>
                        </div>

                        <!-- barcode list -->
                        <div class="row mt-3 text-center">
                            @if($purchase != null )
                                @foreach ($purchase->details as $details)
                                    {{-- <div class="col-12">
                                        <h5>{{ $details->product->name }}</h5>
                                    </div> --}}
                                    @for($i = 0; $i < $details->purchase_total_quantities; $i++)
                                        <div class="col-md-2 mt-3 mb-3 barcode-single print-w-0">
                                            <h6 class="mb-1" style="font-size: small">{{ $details->product->name }}</h6>
                                            <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($details->product->barcode, "C128", 2, 70) }}" alt="barcode">
                                            <small class="d-block mt-1" style="font-size: smaller">{{ $details->product->barcode }}</small>
                                            {{--                                    <h6>Haat Store</h6>--}}
                                            <h6 class="d-block mr-3">Price: TK {{ number_format($details->product->retail_price, 2) }}</h6>
                                        </div>
                                    @endfor
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
