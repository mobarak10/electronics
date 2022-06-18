@extends('layouts.admin')

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
                <button class="btn mr-2" onclick="console.log(window.print())">
                    <i class="fa fa-print"></i>
                </button>
                <a class="btn btn-success" href="{{ route('admin.salary.index') }}" title="Back to list.">
                    <i class="fa fa-list" aria-hidden="true"></i>
                </a>
            </div>
        </div>
        <!-- End of the Print btn -->

        <div class="row">

            <!-- Invoice -->
            <div class="invoice">

                <!-- Invoice header -->
                <div class="invoice-header">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-6">
                            <div class="logo">
                                <img src="{{ asset('public/images/Maxsop-original-logo_white.png') }}" class="img-fluid" alt="">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text">
                                <strong class="text-white">MaxSOP</strong>
                                <span>Phone: 01701028220</span>
                                <span>5B Green House, 27/2 Ram Babu Road, <br> Mymensingh Sadar - 2200.</span>
                                <span>info@maxsop.com</span>
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
                                <div class="title">
                                    Salary to
                                </div>
                                <span>{{ $salary->user->name }}</span>
                            </div>
                        </div>
                        <div class="col-4 pl-4">
                            <div class="single">
                                    Salary Month: {{ $salary->salary_of_month_year->format('F Y') }}
                            </div>
                            <div class="single">
                                    Salary Given Date: {{ $salary->given_date }}
                            </div>
                        </div>
                        <div class="col-5">
                            <div class="single text-right">
                                <div class="title">
                                    Total Salary
                                </div>
                                <div class="total">
                                    BDT {{ number_format(($salary['increment']) - ($salary['decrement']), 2) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
{{--                <!-- End of the client details -->--}}

{{--                <!-- Terms and total -->--}}
                <div class="terms-and-total">
                    <div class="row align-items-center">
                        <div class="col-12">
                            <!-- Total -->
                            <div class="total text-right">
                                <div class="single">
                                    @if($salary['basic_salary'] != null)
                                        Basic Salary <span>{{ number_format($salary['basic_salary']->dtls_amount, 2) }}</span>
                                    @endif
                                </div>
                                <div class="single">
                                    @if($salary['home_allowance'] != null)
                                        Home Allowance <span>{{ number_format($salary['home_allowance']->dtls_amount, 2) }}</span>
                                    @endif
                                </div>
                                <div class="single">
                                    @if($salary['medical_allowance'] != null)
                                        Medical Allowance <span>{{ number_format($salary['medical_allowance']->dtls_amount, 2) }}</span>
                                    @endif
                                </div>
                                <div class="single">
                                    @if($salary['transport_allowance'] != null)
                                        Transport Allowance <span>{{ number_format($salary['transport_allowance']->dtls_amount, 2) }}</span>
                                    @endif
                                </div>
                                <div class="single">
                                    @if($salary['installments'] != null)
                                        Installment <span>-{{ number_format($salary['installments']->dtls_amount, 2) }}</span>
                                    @endif
                                </div>
                                <div class="single">
                                    @if($salary['deductions'] != null)
                                        Deduction <span>-{{ number_format($salary['deductions']->dtls_amount, 2) }}</span>
                                    @endif
                                </div>
                                <hr>
                                <div class="single">
                                    Total <span> {{ number_format(($salary['increment']) - ($salary['decrement']), 2) }}</span>
                                </div>
                            </div>
                            <!-- End of the total -->
                        </div>
                    </div>
                </div>

{{--                <!-- Footer -->--}}
                <div class="footer">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <div class="employeesignature">
                                Employee sign
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="signature">
                                Authorized sign
                            </div>
                        </div>
                    </div>
                </div>
{{--                <!-- End of the footer -->--}}
            </div>

        </div>
    </div>
@endsection


