<div class="table-responsive">
    <table class="table table-striped table-bordered table-sm">
        <thead>
        <tr>
            <th class="text-center">#</th>
            <th>@lang('contents.date')</th>
            <th>@lang('contents.invoice_no')</th>
            <th>@lang('contents.customer')</th>
            <th class="text-right">@lang('contents.total')</th>
            <th class="text-right">@lang('contents.discount')</th>
            <th class="text-right print-none">@lang('contents.action')</th>
        </tr>
        </thead>
        <tbody>
        @php
            $total = 0.00;
            $total_discount = 0.00;
        @endphp

        @forelse($sales as $sale)
            <tr>
                <td class="text-center">{{ $loop->iteration }}.</td>
                <td>{{ $sale->date->format('d F, Y') }}</td>
                <td>
                    <a href="{{ route('invoice.generate', $sale->invoice_no) }}" title="View Invoice"
                       target="_blank">
                        {{ $sale->invoice_no }}
                    </a>
                </td>
                <td>{{ $sale->customer->name }}</td>

                <td class="text-right">
                    @php
                        $total += $sale->grand_total;
                    @endphp

                    {{ number_format($sale->grand_total, 2) }}
                </td>

                <td class="text-right">
                    @php
                        $total_discount += $sale->discount;
                    @endphp

                    {{ number_format($sale->discount, 2) }}
                </td>

                <td class="text-right print-none">
                    <a href="{{ route('invoice.generate', $sale->invoice_no) }}" class="btn btn-info"
                       title="View Invoice"
                       target="_blank">
                        <i class="fa fa-eye"></i>
                    </a>

                    <a href="{{ route('saleUpdate', $sale->id) }}" class="btn btn-success" title="Update sale">
                        <i class="fa fa-pencil"></i>
                    </a>

                    <form action="{{ route('pos.destroy', $sale->id) }}" method="post" class="d-inline" onsubmit="return confirm('Are you sure want to delete this?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" title="Delete sale">
                            <i class="fa fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="text-center">No Sale available.</td>
            </tr>
        @endforelse
        <tr>
            <th colspan="4" class="text-right">Total</th>
            <th class="text-right">{{ number_format($total, 2) }}</th>
            <th class="text-right">{{ number_format($total_discount, 2) }}</th>
            <th class="print-none">&nbsp;</th>
        </tr>
        </tbody>
    </table>
</div>
