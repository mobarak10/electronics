@extends('layouts.admin')

@section('title', 'Customer')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 mt-3">
                <div class="alert {{ ($total_balance < 0) ? 'alert-danger' : 'alert-success' }}">
                    <strong>@lang('contents.total_cash_bdt') {{ $total_balance }}</strong>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 py-3">

                <h1 class="text-center pt-5 pb-4 d-none d-print-block">জননী বস্ত্রালয়</h1>

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="m-0">@lang('contents.customer_records')</h5>
                        <span class="d-none d-print-block">10/5/20</span>
                        <div class="action-area print-none">
                            <a href="#" onclick="window.print();" title="Print" class="btn btn-warning"><i aria-hidden="true" class="fa fa-print"></i></a>
                            <a href="{{ route('admin.customer.create') }}" class="btn btn-primary" title="Create new customer">
                                <i class="fa fa-plus"></i>
                            </a>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>@lang('contents.customer_name')</th>
                                <th>@lang('contents.phone')</th>
                                <th class="text-right">@lang('contents.balance') (@lang('contents.bdt'))</th>
                                <th class="text-right print-none">@lang('contents.action')</th>
                            </tr>
                            </thead>

                            <tbody>
                            @forelse($customers as $customer)
                                <tr>
                                    <td>{{ $loop->iteration }}.</td>
                                    <td>{{ $customer->name }}</td>
                                    <td>{{ $customer->phone }}</td>
                                    <td class="text-right">{{ number_format($customer->balance, 2) }}</td>
                                    <td class="text-right print-none">
                                        <a href="{{ route('admin.customer.show', $customer->id) }}" class="btn btn-primary" title="Customer details.">
                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                        </a>

                                        <a href="{{ route('admin.customer.edit', $customer->id) }}" class="btn btn-success" title="Change customer information.">
                                            <i class="fa fa-pencil" aria-hidden="true"></i>
                                        </a>

                                        <a href="{{ route('admin.customer.index') }}" class="btn btn-danger" title="Trash" onClick="if(confirm('Are you sure, You want to delete this record?')){event.preventDefault();document.getElementById('delete-form-{{ $customer->id }}').submit();} else {event.preventDefault();}">
                                            <i class="fa fa-times" aria-hidden="true"></i>
                                        </a>

                                        <form action="{{ route('admin.customer.destroy', $customer->id) }}" method="post" id="delete-form-{{ $customer->id }}" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No customer available</td>
                                </tr>
                            @endforelse
                            <tr>
                                <td colspan="3" class="text-right">@lang('contents.total')</td>
                                <td class="text-right">{{ $total_balance }}</td>
                                <td class="print-none"></td>
                            </tr>
                            </tbody>
                        </table>

                        <!-- paginate -->
                        <div class="float-right mx-2">
                            {{ $customers->links() }}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
