@extends('layouts.user')

@section('title', $title)

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 py-3">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="m-0">Create New Loan</h5>

                        <div class="btn-group" role="group" aria-label="Action area">
                            <a href="{{ route('loan.index') }}" class="btn btn-primary" title="All Loan">
                                <i class="fa fa-list" aria-hidden="true"></i>
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('loan.store') }}" method="post" enctype="multipart/form-data">
                            @csrf

                            <div class="form-row">
                                <div class="form-group col-md-12 required">
                                    <label class="form-label required">Loan Type</label>
                                    <div class=" form-check form-check-inline">
                                        <input
                                            class=" form-check-input"
                                            type="radio"
                                            name="loan_type"
                                            checked
                                            id="loan-take"
                                            value="take"/>
                                        <label class=" form-check-label" for="loan-take">Take</label>
                                    </div>

                                    <div class=" form-check form-check-inline">
                                        <input
                                            class=" form-check-input"
                                            type="radio"
                                            name="loan_type"
                                            id="loan-give"
                                            value="give"
                                        />
                                        <label class=" form-check-label" for="loan-give">Give</label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6 required">
                                    <label for="loan_account">Loan Account</label>
                                    <select name="loan_account_id" id="loan_account" class="form-control">
                                        <option value="">Choose One</option>
                                        @foreach($loanAccounts as $account)
                                            <option value="{{ $account->id }}">{{ $account->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-6 required">
                                    <label for="date">Date</label>
                                    <input type="date" name="date" value="{{ date('Y-m-d') }}" class="form-control">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6 required">
                                    <label for="amount">Amount</label>
                                    <input type="number" value="{{ old('amount') }}" class="form-control" name="amount" placeholder="Enter loan amount">
                                </div>

                                <div class="form-group col-md-6 required">
                                    <label for="expire_date">Expire Date</label>
                                    <input type="date" name="expired_date" value="{{ date('Y-m-d') }}" class="form-control">
                                </div>
                            </div>

                            <div class="form-row">
                                <cash-bank-component :banks="{{ $banks }}" :cashes="{{ $cashes }}"></cash-bank-component>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-12 required">
                                    <label for="note">Note</label>
                                    <textarea name="note" class="form-control" id="note" cols="30" placeholder="write note" rows="5">{{ old('note') }}</textarea>
                                </div>
                            </div>

                            <div class="text-right">
                                <button type="reset" class="btn btn-danger">@lang('contents.reset')</button>
                                <button type="submit" class="btn btn-primary">@lang('contents.save')</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- main-panel ends -->
@endsection
