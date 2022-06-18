@extends('layouts.user')

@section('title', __('contents.barcode'))

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-12 py-3 p-print-0">
			<div class="card border-0">

				<div class="card-header d-flex justify-content-between align-items-center print-none">
                    <h5 class="m-0">@lang('contents.single_generator')</h5>

					<div class="btn-group" role="group" aria-level="Action area">
                        <a href="{{ route('barcode.single') }}" class="btn btn-success">
                            <i class="fa fa-refresh" aria-hidden="true"></i>
                        </a>

						<button type="button" class="btn btn-primary" onclick="openWin('{{ route('barcode.single-print',['barcode'=>$product->barcode ?? null]) }}')">
							<i class="fa fa-print" aria-hidden="true"></i>
						</button>
					</div>
				</div>

				<div class="card-body p-print-0">

                    <!-- generator -->
					<div class="print-none">
                        <form action="" method="GET" class="d-flex">
                            <input type="text" name="barcode" class="form-control mr-3" placeholder="Barcode" required>
                            <button type="submit" class="btn btn-primary text-white">GO</button>
                        </form>
                    </div>

                    <!-- barcode list -->
                    <div class="row mt-3 text-center barcode-wrapper">
                        @if($product != null )
                            <div class="col-md-3 mt-2 mb-3 barcode-single print-w-0">
                                <h6 class="mb-1" style="font-size: small">{{ $product->name }}</h6>
                                <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($product->barcode, "C128", 2, 70) }}" alt="barcode">
                                <small class="d-block mt-1" style="font-size: smaller">{{ $product->barcode }}</small>
                                <h6 class="d-block mr-3">Price: BDT {{ number_format($product->retail_price, 2) }} +VAT</h6>
                            </div>
                        @endif
                    </div>
                </div>
			</div>
		</div>
	</div>
</div>
@endsection
@push("script")
<script type="text/javascript">
    function openWin(url)
    {
        var myWindow=window.open(url,'','width=500,height=500');
        // myWindow.document.close();
        // myWindow.focus();
        // myWindow.print();
        // myWindow.close();

    }
</script>
@endpush
