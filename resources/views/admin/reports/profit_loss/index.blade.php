@extends('layouts.admin')

@section('title', $title)

@section('content')
    <div class="container">
        <div class="row">

            <div class="col-md-12">
                <h1 class="text-center pt-5 pb-4 d-none d-print-block">MaxSOP</h1>
                <div class="card current-stock">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="d-none d-print-block">Profite Loss Report</h4>
                        <h5 class="m-0 print-none">Profite Loss Report</h5>
                        <div class="action-area print-none" role="group" aria-label="Action area">
                            <a href="{{ route('admin.profitLossReport') }}" class="btn btn-primary" title="Refresh">
                                <i class="fa fa-refresh" aria-hidden="true"></i>
                            </a>
                            <a href="#" onclick="window.print();" title="Print" class="btn btn-warning"><i aria-hidden="true" class="fa fa-print"></i></a>
                        </div>
                    </div>

                    <!-- search form start -->
                    <div class="card-body print-none">
                        <form action="{{ route('admin.profitLossReport') }}" method="GET" class="row">
                            <input type="hidden" name="search" value="1">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-10">
                                        <label for="business">Business</label>
                                        <select name="business_id" id="business" class="form-control">
                                            <option selected disabled>Choose one</option>
                                            @foreach($businesses as $business)
                                                <option value="{{ $business->id }}">{{ $business->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2" style="padding-top: 30px">
                                        <button type="submit" class="btn btn-primary" type="button" title="search">
                                            <i class="fa fa-search"></i> Search
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-10">
                        @if(request()->search)
                            <h3>Total Income: {{ number_format($total_income, 2) }} BDT</h3>
                            <h3>Total Expense: {{ number_format($total_expense, 2) }} BDT</h3>
                            @if($total_income < $total_expense)
                                <h4>Total Loss: {{ number_format(abs($total_income - $total_expense), 2) }} BDT</h4>
                            @else
                                <h3>Total Profit: {{ number_format(abs($total_income - $total_expense), 2) }} BDT</h3>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

