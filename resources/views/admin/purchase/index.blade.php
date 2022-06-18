@extends('layouts.admin')

@section('title', __('contents.purchase'))

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">

                <h1 class="text-center pt-5 pb-4 d-none d-print-block">জননী বস্ত্রালয়</h1>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5>@lang('contents.all_purchases')</h5>
                   <span class="d-none d-print-block">{{ date('d-m-Y') }}</span>
                    <div class="print-none">
                        {{-- for refresh --}}
                        <a href="{{ route('admin.purchase.index') }}" class="btn btn-primary print-none" title="Refresh">
                            <i class="fa fa-refresh" aria-hidden="true"></i>
                        </a>
                        {{-- for collaps search --}}
                        <button class="btn btn-primary print-none" type="button" data-toggle="collapse" data-target="#collapseSearch" aria-expanded="false" aria-controls="collapseSearch">
                        <i class="fa fa-search"></i>
                        </button>
                        {{-- for print --}}
                        <a href="#" onclick="window.print();" title="Print" class="btn btn-warning print-none"><i aria-hidden="true" class="fa fa-print"></i></a>
                    </div>
                </div>
                <div class="collapse align-items-center" id="collapseSearch">
                    <div class="card card-body">
                        <form action="{{ route('admin.purchase.index') }}" method="GET">
                            <input type="hidden" name="search" value="1">
            
                            <div class="row">
                                <div class="form-group col-md-4 required">
                                    <label for="date">Date</label>
                                    <input type="date" class="form-control" name="date" placeholder="Enter date" id="date">
                                </div>

                                <div class="form-group col-md-3 required">
                                    <label for="supplier">Supplier</label>
                                    <select name="party_id" class="form-control" id="supplier">
                                        <option selected disabled>Choose one</option>
                                        @foreach($parties as $party)
                                            <option value="{{ $party->id }}">{{ $party->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-3 required">
                                    <label for="voucher_no">Voucher NO</label>
                                    <input type="text" class="form-control" name="voucher_no" placeholder="Enter voucher number" id="voucher_no">
                                </div>
                                
                                <div class="form-group col-md-2 text-right">
                                    <label for="voucher_no"></label>
                                    <button style="padding-top: 8px" type="submit" class="btn btn-primary">
                                        <i class="fa fa-search"></i> &nbsp;
                                        Search 
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-sm">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>@lang('contents.date')</th>
                            <th>@lang('contents.supplier')</th>
                            <th>@lang('<contents class="voucher_no"></contents>')</th>
                            <th>Total</th>
                            <th>Paid</th>
                            <th class="print-none">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($purchases as $purchase)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $purchase->date->format('d F, Y') }}</td>
                                <td>{{ $purchase->party->name }}</td>
                                <td>{{ $purchase->voucher_no }}</td>
                                <td>{{ $purchase->subtotal - $purchase->discount }}</td> {{--Only for flat discount--}}
                                <td>{{ $purchase->paid }}</td>
                                <td class="print-none">
                                    <a href="{{ route('admin.purchase.show', $purchase->id) }}"
                                       class="btn btn-sm btn-info" title="View">
                                        <i class="fa fa-eye"></i>
                                    </a>

                                    <a href="{{ route('admin.purchase.return', $purchase->id) }}"
                                       class="btn btn-sm btn-danger" title="Return">
                                        <i class="fa fa-undo"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No purchase history available</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $purchases->links() }}
            </div>
        </div>
    </div>
@endsection
