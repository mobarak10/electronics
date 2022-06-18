@extends('layouts.user')

@section('title', $title)

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 py-3">

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="m-0">Accounts in {{ $bank->name }}</h5>

                        <div class="btn-group" role="group" aria-label="Action area">
                            <a href="{{ route('bankAccount.index') }}" class="btn btn-primary" title="All bank">
                                <i class="fa fa-list" aria-hidden="true"></i>
                            </a>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>@lang('contents.account_name')</th>
                                    <th>@lang('contents.bank_name')</th>
                                    <th>@lang('contents.account_number')</th>
                                    <th class="text-right">@lang('contents.balance') (@lang('contents.bdt'))</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($bank->bankAccounts as $account)
                                    <tr>
                                        <td>{{ $loop->iteration }}.</td>
                                        <td>{{ $account->account_name }}</td>
                                        <td>{{ $account->bank->name }}</td>
                                        <td>
                                            <a title="Show transaction details." href="#">
                                                {{ $account->account_number }}
                                            </a>
                                        </td>
                                        <td class="text-right">{{ number_format($account->balance, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No bank account available.</td>
                                    </tr>
                                @endforelse

                                <tr>
                                    <td colspan="4" class="text-right">@lang('contents.total') </td>
                                    <td class="text-right">{{ number_format($bank->bankAccounts()->sum('balance'), 2) }}</td>
                                    <td>&nbsp;</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
