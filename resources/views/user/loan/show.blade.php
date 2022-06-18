@extends('layouts.user')

@section('title', $title)

@section('content')
    <div class="container mt-2">
        <!-- Print btn -->

        <div
            class="pb-2  print d-flex justify-content-between align-items-center"
        >
            <h4>Loan Details</h4>
            <div>
                <button
                    class="btn btn-primary print-none"
                    onclick="window.print()"
                >
                    <i class="fa fa-print"></i>
                </button>

                <a
                    class="ml-1 btn btn-success print-none"
                    href="{{ route('loan.index') }}"
                    title="Back to loan list."
                >
                    <i
                        class="fa fa-chevron-left"
                        aria-hidden="true"
                    ></i>
                    Back
                </a>
            </div>
        </div>
        <!-- End of the Print btn -->

        <!-- Loan details start -->
        <div class="card">
            <div class="text-center card-header">
                {{ $loan->loanAccount->name }}
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    Loan Type: {{ $loan->amount > 0 ? 'Take' : 'Give' }}
                </li>
                <li class="list-group-item">Date: {{ $loan->date }}</li>
                <li class="list-group-item">
                    Amount: {{ abs($loan->amount) }}
                </li>
                <li class="list-group-item">
                    Expire Date: {{ $loan->expired_date }}
                </li>
                <li class="list-group-item">
                    Total Paid: {{ abs($loan->paid) }}
                </li>
                <li class="list-group-item">
                    Total Adjustment: {{ abs($loan->adjustment) }}
                </li>
                <li class="list-group-item">
                    Total Due: {{ abs($loan->due) }}
                </li>
                <li class="list-group-item">Note: {{ $loan->note }}</li>
            </ul>
        </div>
        <!-- Loan Deatils End -->

        <!-- New Category Modal -->
        <div
            class="modal fade"
            id="newLoanInstallment"
            ref="modal"
            tabindex="-1"
            aria-labelledby="paymentMethodLabel"
            aria-hidden="true"
        >
            <div class="modal-dialog">
                <form action="{{ route('loanInstallment.store') }}" method="POST">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5
                                class="modal-title"
                                id="paymentMethodLabel"
                            >
                                Add New Loan Installment
                            </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="loan_id" value="{{ $loan->id }}">
                            <div class="row g-2">
                                <div class="col-12">
                                    <label for="date" class="form-label required">Date</label>
                                    <input type="date" name="date" class="form-control" id="date"/>
                                </div>

                                <div class="col-12">
                                    <label for="amount" class="form-label required">Amount</label>
                                    <input type="number" step="any" name="amount" class="form-control" id="amount" placeholder="Enter amount"/>
                                </div>

                                <div class="col-12">
                                    <label for="adjustment" class="form-label">Adjustment</label>
                                    <input type="number" step="any" name="adjustment" class="form-control" id="adjustment" placeholder="Enter adjustment amount"/>
                                </div>
                            </div>

                            <div class="row">
                                <cash-bank-component :banks="{{ $banks }}" :cashes="{{ $cashes }}"></cash-bank-component>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <label for="note" class="form-label">Note</label>
                                    <textarea class="form-control" name="note" id="note" placeholder="Enter note"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">@lang('contents.close')</button>
                            <button type="submit" class="btn btn-primary">@lang('contents.save')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- End New Category Modal -->

        <!-- Loan Installment Start -->
        <div class="mt-5">
            <div
                class="text-center  display-6 d-flex justify-content-center align-items-center"
            >
                <h3 class="text-decoration-underline">Installments</h3>
                <button
                    class="ms-2 m-2 btn btn-sm btn-primary"
                    title="Add new installment"
                    type="button"
                    data-toggle="modal"
                    data-target="#newLoanInstallment"
                >
                    <i class="fa fa-plus"></i>
                </button>
            </div>

            <table class="table">
                <thead>
                <tr>
                    <th scope="col">SL</th>
                    <th scope="col">Date</th>
                    <th scope="col" class="text-right">Amount</th>
                    <th scope="col" class="text-right">Adjustment</th>
                    <!-- <th scope="col">Note</th> -->
                    <th scope="col" class="text-right">Action</th>
                </tr>
                </thead>
                <tbody>
                    @forelse($loan->loanInstallments as $installment)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $installment->date }}</td>
                            <td class="text-right">{{ number_format(abs($installment->amount)) }}</td>
                            <td class="text-right">{{ $installment->adjustment }}</td>
                            <td class="text-right">
                                <a href="{{ route('loanInstallment.show', $installment->id) }}"
                                   class=" btn btn-primary btn-sm"
                                   title="Details">
                                    <i class="fa fa-eye"></i>
                                </a>
                                <a
                                    href="{{ route('loanInstallment.edit', $installment->id) }}"
                                    class=" btn btn-sm btn-warning"
                                    title="Edit"
                                >
                                    <i class="fa fa-pencil"></i>
                                </a>

                                <a href="{{ route('loanInstallment.index') }}" class="btn btn-sm btn-danger {{ $installment->loan_installments_count > 0 ? 'disabled' : '' }}" title="Trash" onClick="if(confirm('Are you sure, You want to delete this record?')){event.preventDefault();document.getElementById('delete-form-{{ $installment->id }}').submit();} else {event.preventDefault();}">
                                    <i class="fa fa-times" aria-hidden="true"></i>
                                </a>

                                <form action="{{ route('loanInstallment.destroy', $installment->id) }}" method="post" id="delete-form-{{ $installment->id }}" style="display: none;">
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
        <!-- Loan Installment End -->
    </div>
@endsection
