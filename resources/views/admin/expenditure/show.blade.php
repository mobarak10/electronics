@extends('layouts.admin')

@section('title', __('contents.expenditure'))

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 py-3">
            <h1 class="text-center pt-5 pb-4 d-none d-print-block">জননী বস্ত্রালয়aa</h1>
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div>
                        <h5 class="m-0">@lang('contents.all_record')</h5>
                        <small>{{ date('j F, Y', strtotime($date)) }} @lang('contents.expenditure')</small>
                    </div>
                    <a href="#" onclick="window.print();" title="Print" class="btn btn-warning pb-2 print-none"><i aria-hidden="true" class="fa fa-print"></i></a>
                </div>

                <div class="card-body p-0">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>@lang('contents.expenditure_head')</th>
                                <th>@lang('contents.operator')</th>
                                <th class="text-right">@lang('contents.amount') (BDT)</th>
                                <th class="text-right print-none">@lang('contents.action')</th>
                            </tr>
                        </thead>

                        <tbody>
                            @php
                                $totalexpense = 0;
                            @endphp

                            @foreach($expenditures as $expenditure)
                                @php
                                    $totalexpense += $expenditure->amount;
                                @endphp
                            
                                <tr>
                                    <td>{{ $loop->iteration }}.</td>
                                    <td>{{ $expenditure->glAccountHead->name }}</td>
                                    <td>{{ $expenditure->user->name }}</td>
                                    <td class="text-right">{{ number_format($expenditure->amount, 2) }}</td>

                                    <td class="text-right print-none">
                                        <a class="btn btn-primary" href="{{ route('admin.expenditure.edit', $expenditure->id) }}" target="_blank" title="Show all expense in this date.">
                                            <i class="fa fa-pencil" aria-hidden="true"></i>
                                        </a>

                                        <a href="{{ route('admin.expenditure.destroy', $expenditure->id) }}" class="btn btn-danger" title="Trash" onClick="if(confirm('Are you sure, want to delete this record?')){event.preventDefault();document.getElementById('delete-form-{{ $expenditure->id }}').submit();} else {event.preventDefault();}">
                                            <i class="fa fa-times" aria-hidden="true"></i>
                                        </a>

                                        <form action="{{ route('admin.expenditure.destroy', $expenditure->id) }}" method="post" id="delete-form-{{ $expenditure->id }}" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            @endforeach

                            <tr>
                                <th colspan="3" class="text-right">@lang('contents.total') </th>
                                <th class="text-right">{{ number_format($totalexpense, 2) }}</th>
                                <th class="print-none">&nbsp;</th>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
