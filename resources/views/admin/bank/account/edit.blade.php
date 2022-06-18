@extends('layouts.admin')

@section('title', $title)

@section('content')
<div class="container">
    <div class="row"> 
        <div class="col-md-12 py-3">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center"> 
                    <h5 class="m-0">@lang('contents.edit_account')</h5>

                    <div class="btn-group" role="group" aria-label="Action area">
                        <a href="{{ route('admin.bankAccount.index') }}" class="btn btn-primary" title="All bank">
                            <i class="fa fa-list" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <form action="{{ route('admin.bankAccount.update', $bank_account->id) }}" method="post">
                        @csrf
                        @method('PATCH')
                        <div class="form-row">
                            <div class="form-group col-md-6 required">
                                <label for="name">@lang('contents.account_name')</label>
                                <input type="text" class="form-control" value="{{ $bank_account->account_name }}" id="name" placeholder="Enter account owner name" name="name" required>
                            </div>

                            <div class="form-group col-md-6 required">
                                <label for="bank_id">@lang('contents.bank_name')</label>
                                <select name="bank_id" class="form-control" id="bank_id" required>
                                    <option value="" selected disabled>Select one</option>
                                    @foreach($banks as $bank)
                                        <option value="{{ $bank->id }}" {{ $bank->id == $bank_account->bank->id ? 'selected' : '' }}>{{ $bank->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6 required">
                                <label for="kind">@lang('contents.account_type')</label>
                                <select name="kind" class="form-control" id="kind" required>
                                    <option value="" selected disabled>Please select account type</option>

                                    @foreach(config('coderill.bank.account.kind') as $key => $kind)
                                        <option value="{{ $key }}" {{ $key == $bank_account->type ? 'selected' : '' }}>{{ $kind }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-6 required">
                                <label for="account_number">@lang('contents.account_number')</label>
                                <input type="text" class="form-control" id="account_number" value="{{ $bank_account->account_number }}" placeholder="Enter account number" name="account_number" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6 required">
                                <label for="branch">@lang('contents.branch')</label>
                                 <input type="text" name="branch" class="form-control" id="branch" value="{{ $bank_account->branch }}" placeholder="Enter branch name" required>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="balance">@lang('contents.balance') (@lang('contents.bdt'))</label>
                                <input type="number" value="{{ $bank_account->balance }}" class="form-control" placeholder="Enter Ammount" id="balance" name="balance" step="any">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="note">@lang('contents.note')</label>
                            <textarea name="note" placeholder="Write something" id="ballance" class="form-control">{{ $bank_account->note }}</textarea>
                        </div>

                        <div class="text-right">
                            <button type="reset" class="btn btn-danger">@lang('contents.reset')</button>
                            <button type="submit" class="btn btn-primary">@lang('contents.save_changes')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- main-panel ends -->
@endsection
