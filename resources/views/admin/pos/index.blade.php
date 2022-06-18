@extends('layouts.admin')

@section('title', $title)

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1 class="text-center pt-5 pb-4 d-none d-print-block">জননী বস্ত্রালয়</h1>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="mb-0">@lang('contents.all_sales')</h5>
                    <span class="d-none d-print-block">{{ date('d-m-Y') }}</span>
                    <div>
                        {{-- for refresh --}}
                        <a href="{{ route('admin.pos.index') }}" class="btn btn-primary print-none" title="Refresh">
                            <i class="fa fa-refresh" aria-hidden="true"></i>
                        </a>
                        {{-- for collaps search --}}
                        <button class="btn btn-primary print-none" type="button" data-toggle="collapse" data-target="#collapseSearch" aria-expanded="false" aria-controls="collapseSearch">
                        <i class="fa fa-search"></i>
                        </button>
                        {{-- for print --}}
                        <a href="#" onclick="window.print();" title="Print" class="btn btn-warning print-none"><i aria-hidden="true" class="fa fa-print"></i></a>
                    </div>
                </div>

                <div class="collapse" id="collapseSearch">
                    <div class="card card-body">
                        <form action="{{ route('admin.pos.index') }}" method="GET">
                            <input type="hidden" name="search" value="1">
            
                            <div class="row">
                                <div class="form-group col-md-4 required">
                                    <input type="date" class="form-control" name="date" placeholder="Enter date" id="from-date">
                                </div>

                                <div class="form-group col-md-3 required">
                                    <input type="text" class="form-control" name="invoice" placeholder="Enter invoice number" id="from-date">
                                </div>

                                <div class="form-group col-md-3 required">
                                    <input type="text" class="form-control" name="number" placeholder="Enter phone number" id="from-date">
                                </div>
                                
                                <div class="form-group col-md-2 text-right">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-search"></i> &nbsp;
                                        Search 
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-sm">
                        <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th>@lang('contents.date')</th>
                            <th>@lang('contents.invoice_no')</th>
                            <th>@lang('contents.customer')</th>
                            <th class="text-right">@lang('contents.total')</th>
                            <th class="text-right">@lang('contents.due')</th>
                            <th class="text-center print-none">@lang('contents.return')</th>
                            <th class="text-right print-none">@lang('contents.action')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($sales as $sale)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}.</td>
                                <td>{{ $sale->created_at->format('d F, Y') }}</td>
                                <td>
                                    <a href="{{ route('invoice.generate', $sale->invoice_no) }}"
                                       title="View Invoice" target="_blank">
                                        {{ $sale->invoice_no }}
                                    </a>
                                </td>
                                <td>{{ $sale->customer->name }}</td>
                                @php
                                    /**
                                    * Calculate Total
                                    */
                                    $vat = $sale->vat;
                                    $subtotal = $sale->subtotal;
                                    $total = $subtotal + (($subtotal * $vat) / 100);

                                    $discount = $sale->discount;
                                    if($sale->discount_type === 'flat'){
                                        $total -= $discount;
                                    }else{
                                        $total -= (($total * $discount) / 100);
                                    }

                                @endphp
                                <td class="text-right">{{ number_format($total, 2) }}</td>
                                <td class="text-right">{{ $sale->due }}</td>
                                <td class="text-center print-none">
                                    <a href="{{ route('admin.pos.return', $sale->invoice_no) }}" class="btn btn-sm btn-success">
                                        <i class="fa fa-undo"></i> @lang('contents.return')
                                    </a>
                                </td>
                                <td class="print-none">
                                    <a href="{{ route('admin.pos.show', $sale->id) }}"
                                       class="btn btn-sm btn-info" title="View Details">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center">No Sale available.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $sales->links() }}
            </div>
        </div>
    </div>
@endsection
