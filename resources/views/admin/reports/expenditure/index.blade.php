@extends('layouts.admin')

@section('title', $title)

@section('content')
<div class="container">
	<div class="row">

		<div class="col-md-12 py-3">
            <h1 class="text-center pt-5 pb-4 d-none d-print-block">জননী বস্ত্রালয়</h1>
			<div class="card">
				<div class="card-header d-flex justify-content-between align-items-center">

                    <h5 class="m-0">Expenditure List </h5>
                    <span class="d-none d-print-block">{{ date('d-m-Y') }}</span>
					<div class="btn-group print-none" role="group" aria-level="Action area">
                        <a href="{{ route('admin.expenditureReport.index') }}" class="btn btn-info" title="Refesh list.">
                            <i class="fa fa-refresh" aria-hidden="true"></i>
                        </a>
					</div>
				</div>

                    <div class="card-body p-0 print-none">
                        <div class="card card-body">
                            <form action="{{ route('admin.expenditureReport.index') }}" method="GET">
                                <input type="hidden" name="search" value="1">

                                <div class="form-row col-md-12">
                                    <div class="form-group col-md-3 required">
                                        <label for="from-date">From date</label>
                                        <input type="date" class="form-control" name="from" value="{{ date('Y-m-d') }}" placeholder="From date" id="from-date" required>
                                    </div>

                                    <div class="form-group col-md-3">
                                        <label for="to-date">To date</label>
                                        <input type="date" class="form-control" name="to" value="{{ date('Y-m-d') }}" placeholder="To date" id="to-date">
                                    </div>
                                    <business-expenditure-component :businesses="{{ $businesses }}"></business-expenditure-component>
                                </div>

                                <div class="form-group text-right" style="margin-right: 24px">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-search"></i> &nbsp;
                                        Search
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    @if($records != null)
                        {{-- report header --}}
                        <div class="card print-none">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="m-0">{{ date('j F, Y', strtotime($from)) }} To {{ date('j F, Y', strtotime($to)) }} Dates Expenditure Reports</h5>
                                </div>

                                <div class="btn-group" role="group" aria-level="Action area">
                                    <a href="#" onclick="window.print();" class="btn btn-warning" title="Print.">
                                        <i class="fa fa-print" aria-hidden="true"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="card-body p-0">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-center">Sl</th>
                                        <th>Expenditure Head</th>
                                        <th>Operator</th>
                                        <th>Date</th>
                                        <th class="text-right">Amount (BDT)</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @php
                                        $totalAmount = 0.00;
                                    @endphp

                                    @forelse($records as $date => $value)
                                        @php
                                            $totalAmount += $value->amount;
                                        @endphp

                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}.</td>
                                            <td>{{ $value->glAccountHead->name }}</td>
                                            <td>{{ $value->user->name }}</td>
                                            <td>{{ date('j F, Y', strtotime($value->date)) }}</td>
                                            <td class="text-right">{{ number_format($value->amount, 2) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">No expense available.</td>
                                        </tr>
                                    @endforelse

                                    <tr>
                                        <th colspan="4" class="text-right">Total </th>
                                        <th class="text-right">{{ number_format($totalAmount, 2) }}</th>
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
