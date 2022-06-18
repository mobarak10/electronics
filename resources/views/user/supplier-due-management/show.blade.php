@extends('layouts.user')

@section('title', $title)

@push('style')
    <link href="{{ asset('public/css/invoice.css') }}" rel="stylesheet">
    <link href="{{ asset('public/fonts/font.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div class="container">
        <!-- Print btn -->
        <div class="print pb-3">
            <div class="btn-group">
                <button class="btn" onclick="window.print()">
                    <i class="fa fa-print"></i>
                </button>

                {{-- <a class="btn btn-success" href="{{ route('pos.create') }}" title="Back to POS.">
                    <i class="fa fa-chevron-left" aria-hidden="true"></i>
                    &nbsp; Back
                </a> --}}
            </div>
        </div>
        <!-- End of the Print btn -->

        <div class="row">
            <!-- Invoice -->
            <div class="invoice">
                <!-- Invoice header -->
                <div>
                    <div class="row align-items-center justify-content-between">
                        <div class="col-12">
                            <div class="text text-center mt-3">
                                <h6>{{ $business->name ?? '' }}</h6>
                                <h6>Phone: {{ $business->phone ?? ''}} </h6>
                                <h6>Address: {{ $business->address ?? '' }}</h6>
                                <h6>Email: {{ $business->email ?? '' }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End of the invoice header -->

                <!-- Client details -->
                <div class="client-details">
                    <div class="row">
                        <div class="col-3">
                            <div class="single">
                                <div class="title">Billed to</div>
                                <span>{{ $due_manage->party->name }}</span>
                                <span>{{ $due_manage->party->address }}</span>
                            </div>
                        </div>
                        <div class="col-4 pl-4">
                        </div>

                        <div class="col-5">
                            <div class="single text-right">
                                <div class="single">
                                    <div class="title">Date of issue</div>
                                    <span>{{ $due_manage->date->format('F d, Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End of the client details -->

                <!-- Description -->
                <div class="description">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Description</th>
                            <th>Transaction By</th>
                            <th>Transaction Type</th>
                            <th class="text-right">Amount</th>
                            <th class="text-right">Current Balance</th>
                        </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td class="text-wrap">
                                    {{ $due_manage->description }}
                                </td>
                                <td>{{ ($due_manage->cash_id) ? 'Cash' : 'Bank' }}</td>
                                <td>{{ $due_manage->amount > 0 ? 'Received' : 'Paid' }}</td>
                                <td class="text-right">{{ number_format(abs($due_manage->amount), 2) }}</td>
                                <td class="text-right">{{ number_format(abs($due_manage->current_balance), 2) }} {{ $due_manage->current_balance >= 0 ? 'Receivable' : 'Payable' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- End of the description -->

            <!-- Footer -->
                <div class="footer">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <p>Thank you for your business</p>
                        </div>
                        <div class="col-6">
                            <div class="signature">
                                Authorized sign
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End of the footer -->
            </div>
            <!-- End of the invoice -->
        </div>
    </div>
@endsection
