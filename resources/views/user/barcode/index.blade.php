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
                            <a href="{{ route('barcode.index') }}" class="btn btn-success">
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
                            <form action="{{ route('barcode.index') }}" method="GET" class="d-flex">
                                <input type="text" name="barcode" class="form-control mr-3" placeholder="Barcode" required>
                                <input type="number" name="quantity" class="form-control mr-3" placeholder="Quantity" required>
                                <button type="submit" class="btn btn-primary text-white">GO</button>
                            </form>
                        </div>

                        <!-- barcode list -->
                        <div class="row mt-3 text-center barcode-wrapper">
                            @if($product != null )
                                @for($i = 0; $i < $product->quantity; $i++)
                                    <div class="col-md-2 mt-2 mb-3 barcode-single print-w-0">
                                        <h6 class="mb-1" style="font-size: small">{{ $product->name }}</h6>
                                        <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($product->barcode, "C128", 2, 70) }}" alt="barcode">
                                        <small class="d-block mt-1" style="font-size: smaller">{{ $product->barcode }}</small>
                                        {{--                                    <h6>Haat Store</h6>--}}
                                        <h6 class="d-block mr-3">Price: TK {{ number_format($product->retail_price, 2) }}</h6>
                                    </div>
                                @endfor
                            @endif



                            {{-- {{ DNS1D::getBarcodeSVG("4445645656", "PHARMA2T") }}

                            <div class="col-md-3">
                                <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG("4", "C39+", 5, 50, [1, 1, 1], true) }}" alt="barcode">
                            </div>

                            <div class="col-md-3">
                                {!! DNS1D::getBarcodeHTML("1001", "C128", 2, 60, 'green', true) !!}
                            </div>

                            <div class="col-md-3">
                                {!! DNS2D::getBarcodeHTML("1002", "QRCODE") !!}
                            </div>

                            <div class="col-md-3">
                                {!! DNS2D::getBarcodeHTML("Baky", "QRCODE") !!}
                            </div> --}}

                        </div>

                        {{--                    <div class="barcode-print-new">--}}
                        {{--                        @if($product != null )--}}
                        {{--                            @for($i = 0; $i < $product->quantity; $i++)--}}
                        {{--                                <div class="barcode-print-new-single">--}}
                        {{--                                    <small class="d-block mb-1">{{ $product->barcode }}</small>--}}
                        {{--                                    <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($product->barcode, "C128", 2, 70) }}" alt="barcode">--}}
                        {{--                                    <small class="d-block mt-1">{{ $product->barcode }}</small>--}}
                        {{--                                    <h5>{{ $product->name }}</h5>--}}
                        {{--                                    <h6>Silver Moom</h6>--}}
                        {{--                                    <h4>Price: TK {{ $product->retail_price }} + Vat</h4>--}}
                        {{--                                </div>--}}
                        {{--                            @endfor--}}
                        {{--                        @endif--}}
                        {{--                    </div>--}}

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
