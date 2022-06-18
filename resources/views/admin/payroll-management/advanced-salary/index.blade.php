@extends('layouts.admin')

@section('title', $title)

@section('content')
    <!-- Main Content -->
    <div class="container">

        <div class="card">
            <h1 class="text-center pt-2 pb-2 d-none d-print-block">Maxsop</h1>
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="m-0">Advanced</h5>

                <div class="action-area print-none" role="group" aria-level="Action area">
                    <a href="#" onclick="window.print();" title="Print" class="btn btn-warning"><i aria-hidden="true" class="fa fa-print"></i></a>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#newAdvancedModal" title="Create new advanced salary">
                        <i class="fa fa-plus"></i>
                    </button>
                </div>

            </div>

            <div class="card-body p-0">
                <table class="table table-sm table-hover">
                    <thead>
                    <tr>
                        <th class="text-center">SI</th>
                        <th>@lang('contents.name')</th>
                        <th>@lang('contents.phoneNumber')</th>
                        <th class="text-right">Advanced </th>
                        {{-- <th class="text-right">Installment</th> --}}
                        <th class="text-right">Pay</th>
                        <th class="text-right">@lang('contents.action')</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($advanced_salary as $advanced)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}.</td>
                            <td>{{ $advanced->user->name }}</td>
                            <td>{{ $advanced->user->phone }}</td>
                            <td class="text-right">{{ number_format($advanced->advancedSalaryDetails->sum('adv_amount'), 2) }}</td>
                            @php
                                $total_pay = 0;
                            @endphp
                            <td class="text-right">
                                @foreach($advanced->advancedSalaryDetails as $details)
                                    @php
                                        $total_pay += $details->advancedSalaryPaidDetails->sum('installment_pay')
                                    @endphp
                                @endforeach

                                {{ number_format($total_pay, 2) }}
                            </td>
                            <td class="text-right print-none">
                                <a href="{{ route('admin.advancedSalary.show', $advanced->id) }}" class="btn btn-info" title="Show advanced details.">
                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                </a>

                                {{-- <a href="{{ route('admin.advancedSalary.edit', $advanced->id) }}" class="btn btn-success" title="Edit advanced details.">
                                    <i class="fa fa-pencil" aria-hidden="true"></i>
                                </a> --}}

                                {{-- <a href="{{ route('admin.advancedSalary.index') }}" class="btn btn-danger" title="Trash" onClick="if(confirm('Are you sure, You want to delete this record?')){event.preventDefault();document.getElementById('delete-form-{{ $advanced->id }}').submit();} else {event.preventDefault();}">
                                    <i class="fa fa-times" aria-hidden="true"></i>
                                </a>

                                <form action="{{ route('admin.advancedSalary.destroy', $advanced->id) }}" method="post" id="delete-form-{{ $advanced->id }}" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form> --}}
                            </td>
                        </tr>
                    @endforeach
                    {{-- <tr>
                        <th colspan="3" class="text-right">Total </th>
                        <th class="text-right">{{ number_format($total_advanced, 2) }}</th>
                        <th></th>
                        <th class="text-right">{{ number_format($total_pay, 2) }}</th>
                    </tr> --}}
                    </tbody>
                </table>
            </div>

            <!-- New bank modal -->
            <div class="modal fade" id="newAdvancedModal" tabindex="-1" role="dialog" aria-labelledby="insertModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <form action="{{ route('admin.advancedSalary.store') }}" method="post">
                            @csrf

                            <div class="modal-header">
                                <h5 class="modal-title" id="insertModalLabel">New Advanced Salary </h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <div class="modal-body">
                                <div class="form-group col-md-12 required">
                                    <label for="user_id">Name</label>
                                    <select class="form-control" name="user_id" id="user_id" required>
                                        <option selected disabled>Choose one</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-12 required">
                                    <label for="adv_amount">Amount</label>
                                    <input type="text" placeholder="Enter advanced given amount" name="adv_amount" class="form-control" id="adv_amount" required>
                                </div>

                                <div class="form-group col-md-12 required">
                                    <label for="installment_amount">Installment</label>
                                    <input type="text" placeholder="Enter installment amount" name="installment_amount" class="form-control" id="installment_amount" required>
                                </div>

                                <div class="form-group col-md-12">
                                    <label for="note">Note</label>
                                    <textarea name="note" placeholder="Write something about advanced salary" class="form-control" id="note"></textarea>
                                </div>

                                <div class="col-md-6">
                                    <!-- Send SMS Start -->
                                    <div class="form-group row">
                                        <div class="col-10">
                                            <div class="custom-control custom-checkbox">
                                                <input
                                                    type="checkbox"
                                                    name="sms"
                                                    class="custom-control-input"
                                                    id="client-send-sms"
                                                />
                                                <label
                                                    class="custom-control-label"
                                                    for="client-send-sms">
                                                    Send SMS
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Send SMS End -->
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

