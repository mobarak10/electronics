@extends('layouts.user')
@section('title', $title)
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="m-0"> @lang('contents.supplier') @lang('contents.due_management')</h5>
                        <div role="group" aria-label="Action area">
                            <button class="btn btn-info" type="button" data-toggle="collapse"
                                    data-target="#search-form">
                                <i class="fa fa-search"></i>
                            </button>
                            <a href="{{ route('supplierDueManage.create') }}" class="btn btn-primary" title="">
                                <i class="fa fa-plus"></i>
                            </a>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <div class="collapse @if(request('from_date') || request('to_date')) show @endif" id="search-form">
                            <div class="card-body">
                                <form action="{{ url()->current() }}" method="GET">
                                    <div class="form-row">
                                        <div class="col-md-4">
                                            <label for="from-date">From Date</label>
                                            <input type="date" class="form-control form-control-sm" id="from-date" name="from_date" value="{{ request('from_date', '') }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="to-date">To Date</label>
                                            <input type="date" class="form-control form-control-sm" id="to-date" name="to_date" value="{{ request('to_date', '') }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label>&nbsp;</label>
                                            <div class="row">
                                                <div class="col-6">
                                                    <button class="btn btn-primary btn-block btn-sm" type="submit">Search</button>
                                                </div>
                                                <div class="col-6">
                                                    <a class="btn btn-secondary btn-block btn-sm" href="{{ url()->current() }}">Reset</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <table class="table table-sm table-hover">
                            <thead>
                            <tr>
                                <th>@lang('contents.sl')</th>
                                <th>@lang('contents.party_name')</th>
                                <th>@lang('contents.payment_type')</th>
                                <th>@lang('contents.date')</th>
                                <th class="text-right">@lang('contents.amount')</th>
                                <th class="text-right">@lang('contents.action')</th>
                            </tr>
                            </thead>

                            <tbody>
                            @forelse($manage_dues as $due)
                                <tr>
                                    <td>{{ $loop->iteration }}.</td>
                                    <td>{{ $due->party->name ?? '' }}.</td>
                                    <td>{{ $due->amount > 0 ? 'Received' : 'Paid' }}</td>
                                    <td>{{ $due->date->format('d F Y') }}</td>
                                    <td class="text-right">{{ number_format(abs($due->amount), 2) }}</td>
                                    <td class="text-right">
                                        <a href="{{ route('supplierDueManage.show', $due->id) }}" class="btn btn-sm btn-primary" title="Update Due.">
                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                        </a>

                                        <a href="{{ route('supplierDueManage.edit', $due->id) }}" class="btn btn-sm btn-primary" title="Update Due.">
                                            <i class="fa fa-pencil" aria-hidden="true"></i>
                                        </a>

                                        <a href="{{ route('supplierDueManage.destroy', $due->id) }}"
                                           class="btn btn-sm btn-danger" title="Delete Due."
                                           onclick="event.preventDefault();
                                               if(confirm('Are you sure want to delete?')){ document.getElementById('delete-form-{{ $due->id }}').submit(); }else { return false}">
                                            <i class="fa fa-trash" aria-hidden="true"></i>
                                        </a>
                                        <form id="delete-form-{{ $due->id }}" action="{{ route('supplierDueManage.destroy', $due->id) }}" method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">@lang('contents.no_supplier_due_manage_available')</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                        <!-- paginate -->
                        <div class="float-right mx-2">
                            {{ $manage_dues->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
