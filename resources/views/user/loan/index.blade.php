@extends('layouts.user')

@section('title', 'Loan')

@section('content')
    <div class="container sale">
        <!-- table -->
        <div class="border-0 card">

            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="m-0">All Loan</h5>
                <div class="action-area print-none" role="group" aria-level="Action area">
                    <a href="#" onclick="window.print();" title="Print" class="btn btn-warning"><i aria-hidden="true" class="fa fa-print"></i></a>
                    <a href="{{ route('loan.create') }}" class="btn btn-primary" title="Create new loan">
                        <i class="fa fa-plus"></i>
                    </a>
                </div>
            </div>


            <!-- content body -->
            <div class="p-0 card-body">
                <div class="table-responsive-md">
                    <table class="table table-sm table-bordered">
                        <thead>
                        <tr>
                            <th scope="col" width="100">SL</th>
                            <th scope="col">Loan Account</th>
                            <th scope="col">Date</th>
                            <th scope="col">Amount</th>
                            <th scope="col">Paid</th>
                            <th scope="col">Adjustment</th>
                            <th scope="col">Due</th>
                            <th scope="col">Expired Date</th>
                            <th scope="col" class="text-right">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($loans as $loan)
                        <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td>{{ $loan->loan_account_name }}</td>
                            <td>{{ $loan->date }}</td>
                            <td>
                                {{ number_format(abs($loan->amount), 2) }}

                                <span class="{{ $loan->amount < 0 ? 'text-success' : 'text-danger' }}">
                                    ({{ $loan->amount < 0 ? "Rec" : "Pay" }})
                                </span>
                            </td>
                            <td>{{ number_format(abs($loan->paid), 2) }}</td>
                            <td>{{ number_format($loan->adjustment, 2) }}</td>
                            <td>
                                {{ number_format(abs($loan->due), 2) }}
                                <span class="{{ $loan->due <= 0 ? 'text-success': 'text-danger' }}">
                                    ({{ $loan->due <= 0 ? "Rec" : "Pay" }})
                                </span>
                            </td>
                            <td>
                                {{ $loan->expired_date }}
                            </td>
                            <td class="text-right">
                                <a href="{{ route('loan.show', $loan->id) }}"
                                    class=" btn btn-primary btn-sm"
                                    title="Details">
                                    <i class="fa fa-eye"></i>
                                </a>
                                <a
                                    href="{{ route('loan.edit', $loan->id) }}"
                                    class=" btn btn-sm btn-warning"
                                    title="Edit"
                                >
                                    <i class="fa fa-pencil"></i>
                                </a>

                                <a href="{{ route('loan.index') }}" class="btn btn-sm btn-danger {{ $loan->loan_installments_count > 0 ? 'disabled' : '' }}" title="Trash" onClick="if(confirm('Are you sure, You want to delete this record?')){event.preventDefault();document.getElementById('delete-form-{{ $loan->id }}').submit();} else {event.preventDefault();}">
                                    <i class="fa fa-times" aria-hidden="true"></i>
                                </a>

                                <form action="{{ route('loan.destroy', $loan->id) }}" method="post" id="delete-form-{{ $loan->id }}" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                        @empty
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- pagination -->
        </div>
    </div>
@endsection

