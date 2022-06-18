@extends('layouts.user')

@section('title', 'Loan Account')

@section('content')
    <div class="container sale">
        <!-- table -->
        <div class="border-0 card">

            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="m-0">All Loan Account</h5>
                <div class="action-area print-none" role="group" aria-level="Action area">
                    <a href="#" onclick="window.print();" title="Print" class="btn btn-warning"><i aria-hidden="true" class="fa fa-print"></i></a>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#newLoanAccountModal" title="Create new loan account">
                        <i class="fa fa-plus"></i>
                    </button>
                </div>
            </div>

            <!-- New Category Modal -->
            <div class="modal fade" id="newLoanAccountModal" tabindex="-1" role="dialog" aria-labelledby="insertModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <form action="{{ route('loanAccount.store') }}" method="post">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5
                                    class="modal-title"
                                    id="paymentMethodLabel"
                                >
                                    Add New Loan Account
                                </h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="row g-2">
                                    <div class="col-12 required">
                                        <label for="name" class="form-label required">Name</label>
                                        <input type="text" required class="form-control" name="name" id="name" placeholder="Enter Loan Account Name"/>
                                    </div>

                                    <div class="col-12 required">
                                        <label for="phone" class="form-label required">Phone</label>
                                        <input type="text" required class="form-control" name="phone" id="phone" placeholder="Enter phone number"/>
                                    </div>

                                    <div class="col-12">
                                        <label for="address" class="form-label">Address</label>
                                        <textarea class="form-control" name="address" id="address" placeholder="Enter address"></textarea>
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

            <!-- content body -->
            <div class="p-0 card-body">
                <div class="table-responsive-md">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th scope="col" width="100">SL</th>
                            <th scope="col">Name</th>
                            <th scope="col">Phone</th>
                            <th scope="col">Total Loan</th>
                            <th scope="col">Total Paid</th>
                            <th scope="col">Total Adjustment</th>
                            <th scope="col">Balance</th>
                            <th scope="col" width="100">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <!-- Starts from here -->
                        @forelse($loanAccounts as $account)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ $account->name }}</td>
                                <td>{{ $account->phone }}</td>
                                <td>
                                    {{ number_format($account->total_loan, 2 ) }}
                                    <span class="{{ $account->total_loan <= 0 ? 'text-success' : 'text-danger' }}">
                                        ({{ $account->total_loan <= 0 ? "Rec" : "Pay" }})
                                    </span>
                                </td>
                                <td>
                                    {{ number_format($account->total_paid, 2) }}
                                </td>
                                <td>
                                    {{ $account->total_adjustment }}
                                </td>
                                <td>
                                    {{ number_format($account->total_due, 2) }}

                                    <span class="{{ $account->total_due <= 0 ? 'text-success' : 'text-danger'}}">
                                        ({{ $account->total_due <= 0 ? "Rec" : "Pay" }})
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('loanAccount.edit', $account->id) }}" class="btn btn-sm btn-primary" title="Change loan account information.">
                                        <i class="fa fa-pencil" aria-hidden="true"></i>
                                    </a>

                                    <a href="{{ route('loanAccount.index') }}" class="btn btn-sm btn-danger {{ $account->loans_count > 0 ? 'disabled' : '' }}" title="Trash" onClick="if(confirm('Are you sure, You want to delete this record?')){event.preventDefault();document.getElementById('delete-form-{{ $account->id }}').submit();} else {event.preventDefault();}">
                                        <i class="fa fa-times" aria-hidden="true"></i>
                                    </a>

                                    <form action="{{ route('loanAccount.destroy', $account->id) }}" method="post" id="delete-form-{{ $account->id }}" style="display: none;">
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

