@extends('layouts.user')
@section('title', $title)
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="m-0">Income Record</h5>

                        <div class="btn-group" role="group" aria-label="Action area">
                            <a href="{{ route('incomeRecord.create') }}" class="btn btn-primary" title="">
                                <i class="fa fa-plus"></i>
                            </a>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <table class="table table-sm table-hover">
                            <thead>
                            <tr>
                                <th>SL</th>
                                <th>Date</th>
                                <th>Field Of Income</th>
                                <th>Payment Type</th>
                                <th>Income By</th>
                                <th class="text-right">Amount</th>
                               <th class="text-right">Action</th>
                            </tr>
                            </thead>

                            <tbody>
                            @forelse($income_records as $income)
                                <tr>
                                    <td>{{ $loop->iteration }}.</td>
                                    <td>{{ $income->date->format('d F Y') }}</td>
                                    <td>{{ $income->incomeSector->sector_name }}</td>
                                    <td>{{ ($income->cash_id != null) ? "Cash (".$income->cash->title.")" : "Bank (".$income->bank->name."/".$income->bankAccount->account_name.")" }}</td>
                                    <td>{{ $income->income_by }}</td>
                                    <td class="text-right">{{ $income->amount }}</td>
                                   <td class="text-right">
                                       <a href="{{ route('incomeRecord.edit', $income->id) }}" class="btn btn-sm btn-primary" title="Update record.">
                                           <i class="fa fa-pencil" aria-hidden="true"></i>
                                       </a>
                                   </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">No income available</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                        <!-- paginate -->
                        <div class="float-right mx-2">
                            {{ $income_records->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
