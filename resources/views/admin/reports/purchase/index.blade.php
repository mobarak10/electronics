@extends('layouts.admin')

@section('title', $title)

@section('content')
    <div class="container">
        <div class="row">

            <div class="col-md-12 py-3">
                <h1 class="text-center pt-5 pb-4 d-none d-print-block">জননী বস্ত্রালয়</h1>
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">

                        <h5 class="m-0">Purchase List </h5>
                        <span class="d-none d-print-block">{{ date('d-m-Y') }}</span>
                        <div>
                            <a href="{{ route('admin.purchaseReport') }}" class="btn btn-info print-none" title="Refesh list.">
                                <i class="fa fa-refresh" aria-hidden="true"></i>
                            </a>

                            <a href="#" onclick="window.print();" class="btn btn-warning print-none" title="Print.">
                                <i class="fa fa-print" aria-hidden="true"></i>
                            </a>
                        </div>
                    </div>

                    <div class="card-body p-0 print-none">
                        <div class="card card-body">
                            <form action="{{ route('admin.purchaseReport') }}" method="GET">
                                <input type="hidden" name="search" value="1">

                                <div class="form-row col-md-12">
                                    <div class="form-group col-md-4 required">
                                        <label for="business">Business</label>
                                        <select name="businessId" id="business" class="form-control" required>
                                            <option selected disabled value="">Choose one</option>
                                            @foreach($businesses as $business)
                                                <option value="{{ $business->id }}">{{ $business->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group col-md-3 required">
                                        <label for="from-date">From date</label>
                                        <input type="date" class="form-control" name="from" value="{{ date('Y-m-d') }}" placeholder="From date" id="from-date" required>
                                    </div>

                                    <div class="form-group col-md-3">
                                        <label for="to-date">To date</label>
                                        <input type="date" class="form-control" name="to" value="{{ date('Y-m-d') }}" placeholder="To date" id="to-date">
                                    </div>

                                    <div class="form-group col-md-2" style="margin-top: 30px">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa fa-search"></i> &nbsp;
                                            Search
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    @if($records != null)
                        {{-- report header --}}
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="m-0">{{ date('j F, Y', strtotime($from)) }} To {{ date('j F, Y', strtotime($to)) }} Dates Purchase Reports</h5>
                                </div>
                            </div>
                        </div>

                        <div class="card-body p-0">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>@lang('contents.date')</th>
                                    <th>@lang('contents.supplier')</th>
                                    <th>@lang('contents.voucher_no')</th>
                                    <th>@lang('contents.total')</th>
                                    <th>@lang('contents.paid')</th>
                                </tr>
                                </thead>

                                <tbody>
                                @php
                                    $totalAmount = 0.00;
                                    $totalPaid = 0.00;
                                @endphp
                                @forelse($records as $purchase)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $purchase->date->format('d F, Y') }}</td>
                                        <td>{{ $purchase->party->name }}</td>
                                        <td>{{ $purchase->voucher_no }}</td>
                                        <td>{{ number_format($purchase->subtotal - $purchase->discount, 2) }}</td> {{--Only for flat discount--}}
                                        <td>{{ number_format($purchase->paid, 2) }}</td>
                                    </tr>
                                    @php
                                        $totalAmount += $purchase->subtotal - $purchase->discount;
                                        $totalPaid += $purchase->paid;
                                    @endphp
                                @empty
                                    <tr><td colspan="8" class="text-center">No Purchase available.</td></tr>
                                @endforelse
                                <tr>
                                    <th colspan="4" class="text-right">Total: </th>
                                    <th>{{ number_format($totalAmount, 2) }}</th>
                                    <th>{{ number_format($totalPaid, 2) }}</th>
                                </tr>

                                </tbody>
                            </table>

                            <!-- paginate -->
                            <div class="float-right m-3">{{ $records->appends(Request::except('page'))->links() }}
                            </div>

                            @endif
                        </div>
                </div>
            </div>
        </div>
    </div>
@endsection
