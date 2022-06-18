@extends('layouts.user')

@section('title', 'Retail Due Collection')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 py-3">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="m-0">Due Collect</h5>

                        <div class="btn-group" role="group" aria-label="Action area">
                            <a href="{{ route('retailDueCollection.index') }}" class="btn btn-primary" title="All Bank">
                                <i class="fa fa-list" aria-hidden="true"></i>
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-4 font-weight-bold">
                                <h5>Name: {{ $sale->customer->name ?? '' }}</h5>
                                <h5>Mobile: {{ $sale->customer->phone ?? '' }}</h5>
                            </div>

                            <div class="col-4 font-weight-bold">
                                <h5>Invoice: {{ $sale->invoice_no }}</h5>
                                <h5>Sales Man: {{ $sale->user->name ?? '' }}</h5>
                            </div>

                            <div class="col-4 font-weight-bold">
                                <h5>Date: {{ $sale->date->format('d F Y') }}</h5>
                            </div>
                        </div>

                        <table class="mt-4 table table-sm table-bordered">
                            <tbody>
                                <tr>
                                    <th class="text-center">Invoice No</th>
                                    <th class="text-right">Grand Total</th>
                                    <th class="text-right">Paid</th>
                                    <th class="text-right">Due</th>
                                </tr>
                                <tr>
                                    <td class="text-center">{{ $sale->invoice_no }}</td>
                                    <td class="text-right">{{ number_format($sale->grand_total, 2) }}</td>
                                    <td class="text-right">{{ number_format($sale->paid, 2) }}</td>
                                    <td class="text-right">{{ number_format($sale->due, 2) }}</td>
                                </tr>
                            </tbody>
                        </table>

                        <form action="{{ route('retailDueCollection.update', $sale->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <div class="row mt-4">
                                <div class="col-2"></div>
                                    <div class="col-8">
                                        <div class="row">
                                            <label class="col-4 control-label"> <h5>Previous Paid</h5> </label>
                                            <div class="col-8">
                                                <input type="number" class="form-control" value="{{ $sale->paid }}" step="any" readonly="">
                                            </div>
                                        </div>

                                        <div class="row mt-2">
                                            <label class="col-4 control-label"> <h5>Paid</h5> </label>
                                            <div class="col-8">
                                                <input type="number" required class="form-control" name="paid" step="any">
                                            </div>
                                        </div>

                                        <div class="row mt-2">
                                            <label class="col-4 control-label"> <h5>Payment Date</h5> </label>
                                            <div class="col-8">
                                                <input type="date" value="{{ date('Y-m-d') }}" required class="form-control" name="date">
                                            </div>
                                        </div>

                                        <div class="row mt-2">
                                            <label class="col-4 control-label"> <h5>Rimission</h5> </label>
                                            <div class="col-8">
                                                <input type="number" class="form-control" name="discount" step="any">
                                            </div>
                                        </div>

                                        {{-- <div class="row mt-2">
                                            <label class="col-4 control-label"> <h5>Due</h5> </label>
                                            <div class="col-8">
                                                <input type="number" class="form-control" value="{{ $sale->paid }}" step="any" readonly="">
                                            </div>
                                        </div> --}}

                                        <div class="row mt-2">
                                            <label class="col-4 control-label"> <h5>Next Payment Date</h5> </label>
                                            <div class="col-8">
                                                <input type="date" name="promise_date" class="form-control">
                                            </div>
                                        </div>

                                        <div class="text-right mt-2">
                                            <button type="submit" class="btn btn-success">Submit</button>
                                        </div>
                                    </div>

                                <div class="col-2"></div>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer"></div>
                </div>
            </div>
        </div>
    </div>
@endsection
