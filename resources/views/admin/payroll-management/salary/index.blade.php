@extends('layouts.admin')

@section('title', $title)

@section('content')
    <!-- Main Content -->
    <div class="container">

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="m-0">Salary Sheet</h5>

                <div class="action-area print-none" role="group" aria-level="Action area">
                    <a href="#" onclick="window.print();" title="Print" class="btn btn-warning"><i aria-hidden="true" class="fa fa-print"></i></a>

                    <a href="{{ route('admin.salary.index') }}" class="btn btn-info" title="Refesh list.">
                        <i class="fa fa-refresh" aria-hidden="true"></i>
                    </a>

                    <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseSearch" aria-expanded="false" aria-controls="collapseSearch">
                        <i class="fa fa-search"></i>
                    </button>

                    <a href="{{ route('admin.salary.create') }}" class="btn btn-primary" title="Pay salary">
                        <i class="fa fa-plus"></i>
                    </a>
                </div>

            </div>

            <div class="card-body p-0">
                <div class="collapse" id="collapseSearch">
                    <div class="card card-body">
                        <form action="{{ route('admin.salary.index') }}" method="GET">
                            <input type="hidden" name="search" value="1">

                            <div class="row">
                                <div class="form-group col-md-10 required">
                                    <label for="from-date">Month</label>
                                    <input type="month" class="form-control" name="month" placeholder="enter month" required>
                                </div>

                                <div class="form-group col-md-2 text-right" style="padding-top: 8px">
                                    <label for=""></label>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-search"></i> &nbsp;
                                        Search
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <table class="table table-hover table-sm">
                    <thead>
                    <tr>
                        <th class="text-center">SI</th>
                        <th>@lang('contents.name')</th>
                        <th>@lang('contents.phoneNumber')</th>
                        <th>Salary</th>
                        <th class="text-right print-none">@lang('contents.action')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}.</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->phone }}</td>

                            <td>
                                @if($user->last_month_paid_status)
                                    <span class="badge badge-success">Paid</span>
                                @else
                                    <a href="{{ route('admin.salaryPay', $user->id) }}" class="btn btn-danger">Unpaid</a>
                                @endif
                            </td>

                            <td class="text-right print-none">
                                @if($user->last_month_paid_status)
                                    <a href="{{ (request()->search) ? route('admin.salaryView', $user->salaries->last()['id']) : route('admin.salaryView', $user->salaries->last()['id']) }}" class="btn btn-success"
                                       title="view last salary details.">
                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        // $(document).ready(function() {
        //     $(".datepicker").datepicker({
        //         format: 'mm-dd-yyyy'
        //     });
        // });
    </script>
@endpush

