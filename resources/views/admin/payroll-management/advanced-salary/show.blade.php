@extends('layouts.admin')

@section('title', 'Advanced Salary Details')

@section('content')
    <!-- Main Content -->
    <div class="container">

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="m-0">Details for {{$user->name }}</h5>

                <div class="action-area print-none" role="group" aria-level="Action area">
                    <a href="#" onclick="window.print();" title="Print" class="btn btn-warning"><i aria-hidden="true" class="fa fa-print"></i></a>

                    <a href="{{ route('admin.advancedSalary.index') }}" title="View All" class="btn btn-primary"><i aria-hidden="true" class="fa fa-list"></i></a>
                </div>

            </div>

            <div class="card-body p-0">
                <table class="table table-sm table-hover">
                    <thead>
                    <tr>
                        <th class="text-center">SI</th>
                        <th class="text-right">Advanced </th>
                        <th class="text-right">Installment</th>
                        <th class="text-right">Pay</th>
                        <th class="text-right">Date</th>
                        <th class="text-right">@lang('contents.action')</th>
                    </tr>
                    </thead>

                    <tbody>
                        @php
                            $total_pay = 0;
                        @endphp
                        @foreach($advanced_salary_details as $details)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}.</td>
                                <td class="text-right">{{ number_format($details->adv_amount, 2) }}</td>
                                <td class="text-right">{{ number_format($details->installment_amount, 2) }}</td>
                                <td class="text-right">{{ number_format($details->advancedSalaryPaidDetails->sum('installment_pay'), 2) }}</td>
                                <td class="text-right">{{ $details->created_at->format('d M-Y') }}</td>
                                @php
                                    $total_pay += $details->advancedSalaryPaidDetails->sum('installment_pay');
                                @endphp
                                <td class="text-right">
                                    @if($details->is_paid != true)
                                        <a href="{{ route('admin.advancedSalary.edit', $details->id) }}" class="btn btn-success" title="Edit advanced details.">
                                            <i class="fa fa-pencil" aria-hidden="true"></i>
                                        </a>   
                                    @endif 
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <th class="text-right">Total </th>
                            <th class="text-right">{{ number_format($advanced_salary_details->sum('adv_amount'), 2) }}</th>
                            <th></th>
                            <th class="text-right">{{ number_format($total_pay, 2) }}</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

