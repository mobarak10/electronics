@extends('layouts.user')
@section('title', $title)
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="m-0">
                            Due management 
                            @if( $holder == 'customer')
                                {{ 'for customer' }}
                            @else
                                {{ 'for supplier' }}    
                            @endif
                        </h5>

                        <div class="btn-group" role="group" aria-label="Action area">
                            <a href="{{ route('dueManagement.create', ($holder == 'customer') ? 'customer' : 'supplier') }}" class="btn btn-primary" title="">
                                <i class="fa fa-plus"></i>
                            </a>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        @if($holder == 'customer')
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Customer Name</th>
                                    <th>Amount</th>
                                    <th>Payment Type</th>
                                    <th>Date</th>
                                    <th class="text-right">Action</th>
                                </tr>
                                </thead>

                                <tbody>
                                @forelse($manage_dues as $due)
                                    <tr>
                                       @if($due->party->genus == 'customer')
                                            <td>{{ $loop->iteration }}.</td>
                                            <td>{{ $due->party->name }}.</td>
                                            <td>{{ $due->amount }}</td>
                                            <td>{{ ucfirst(trans($due->payment_type)) }}</td>
                                            <td>{{ $due->date }}</td>
                                            <td class="text-right">
                                                <a href="#" class="btn btn-primary" title="Update Due.">
                                                    <i class="fa fa-pencil" aria-hidden="true"></i>
                                                </a>
                                            </td>
                                        @endif
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No due manage available</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        @else
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Supplier Name</th>
                                    <th>Amount</th>
                                    <th>Payment Type</th>
                                    <th>Date</th>
                                    <th class="text-right">Action</th>
                                </tr>
                                </thead>

                                <tbody>
                                @forelse($manage_dues as $due)
                                    <tr>
                                       @if($due->party->genus == 'supplier')
                                            <td>{{ $loop->iteration }}.</td>
                                            <td>{{ $due->party->name }}.</td>
                                            <td>{{ $due->amount }}</td>
                                            <td>{{ ucfirst(trans($due->payment_type)) }}</td>
                                            <td>{{ $due->date }}</td>
                                            <td class="text-right">
                                                <a href="#" class="btn btn-primary" title="Update Due.">
                                                    <i class="fa fa-pencil" aria-hidden="true"></i>
                                                </a>
                                            </td>
                                        @endif
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No due manage available</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        @endif
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
