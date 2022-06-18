@extends('layouts.admin')

@section('title', __('contents.barcode'))

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-12 py-3 p-print-0">
			<div class="card border-0">

				<div class="card-header d-flex justify-content-between align-items-center print-none">
                    <h5 class="m-0">@lang('contents.generator') </h5>
                    
					<div class="btn-group" role="group" aria-level="Action area">
                        <a href="{{ route('admin.barcode.index') }}" class="btn btn-success">
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
                        <form action="{{ route('admin.barcode.index') }}" method="GET" class="d-flex">
                            <input type="text" name="barcode" class="form-control mr-3" placeholder="Barcode" required>
                            <input type="number" name="quantity" class="form-control mr-3" placeholder="Quantity" required>
                            <button type="submit" class="btn btn-primary text-white">GO</button>
                        </form>
                    </div>

                    <!-- barcode list -->
                    <div class="row mt-3 text-center">
                        @if($product != null )
                            @for($i = 0; $i < $product->quantity; $i++)
                                <div class="col-md-2 py-2 print-border">
                                    <small class="d-block mb-n1 barcode-text">{{ $product->name }}</small>
                                    <small class="barcode-text">Price: ৳ {{ $product->retail_price }}</small>

                                    <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($product->barcode, "C128", 2, 70) }}" alt="barcode">

                                    <small>{{ $product->barcode }}</small>
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
                    
                </div>
			</div>			
		</div>		
	</div>
</div>
@endsection