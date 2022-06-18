@extends('layouts.user')

@section('title', $title)

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 mt-3">
                {{-- <div class="alert {{ ($total_balance < 0) ? 'alert-danger' : 'alert-success' }}">
                    <strong>@lang('contents.total_cash_bdt') {{ $total_balance }}</strong>
                </div> --}}
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 py-3">

                <h1 class="text-center pt-5 pb-4 d-none d-print-block">MaxSOP</h1>

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="m-0">Order Management</h5>
                        <span class="d-none d-print-block">{{ date('d M-Y') }}</span>
                        <div class="action-area print-none">
                            <a href="#" onclick="window.print();" title="Print" class="btn btn-warning"><i aria-hidden="true" class="fa fa-print"></i>
                            </a>

                            <a href="{{ route('orderManagement.index') }}" class="btn btn-success" title="Refresh.">
                                <i class="fa fa-refresh"></i>
                            </a>

                            <button class="btn btn-info" type="button" data-toggle="collapse" data-target="#customer-search">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <div class="collapse col-md-12" id="customer-search">
                            <form action="#" method="GET">
                                <input type="hidden" name="search" value="1">
                    
                                <div class="row">
                                    <div class="form-group col-md-5">
                                        <label for="name">Name</label>
                                        <input type="text" class="form-control" name="condition[name]" placeholder="enter name" id="name">
                                    </div>
                    
                                    <div class="form-group col-md-5">
                                        <label for="phone">Phone</label>
                                        <input type="text" class="form-control" name="condition[phone]" placeholder="enter phone number" id="phone">
                                    </div>

                                    <div class="form-group pt-2 col-md-2">
                                        <label for=""></label>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa fa-search"></i> &nbsp;
                                            Search 
                                        </button>
                                    </div>

                                </div>
                            </form>
                        </div>

                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Customer Name</th>
                                <th>Order Number</th>
                                <th>Status</th>
                                <th class="text-right">Action</th>
                            </tr>
                            </thead>

                            <tbody>
                            @forelse($orders as $order)
                                <tr>
                                    <td>{{ $loop->iteration }}.</td>
                                    <td>{{ $order->customers->name }}</td>
                                    <td>{{ $order->order_no }}</td>
                                    <td>{{ ($order->status) ? 'Delivered' : 'Pending' }}</td>
                                    <td class="text-right print-none">
                                    	<a href="{{ route('orderManagement.show', $order->id) }}" class="btn btn-success" title="Refresh.">
			                                <i class="fa fa-eye"></i>
			                            </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No order available</td>
                                </tr>
                            @endforelse
                            </tbody>
                            <!-- paginate -->
                            <div class="float-right mx-2">
                                {{ $orders->links() }}
                            </div>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

