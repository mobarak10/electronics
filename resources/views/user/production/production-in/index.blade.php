@extends('layouts.user')
@section('title', $title)

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1 class="text-center pt-5 pb-4 d-none d-print-block">Maxsop</h1>

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Production In</h5>
                        <span class="d-none d-print-block">{{ date('d-m-Y') }}</span>

                        <div>
                            <a href="{{ route('productionIn.index') }}" class="btn btn-success print-none" title="Refresh">
                                <i class="fa fa-refresh" aria-hidden="true"></i>
                            </a>

                            <a href="{{ route('productionIn.create') }}" class="btn btn-primary print-none" title="Refresh">
                                <i class="fa fa-plus" aria-hidden="true"></i>
                            </a>

                            <!-- for print -->
                            <a href="#" onclick="window.print();" title="Print" class="btn btn-warning print-none">
                                <i aria-hidden="true" class="fa fa-print"></i>
                            </a>
                        </div>
                    </div>

                    <div class="card card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-sm">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th class="text-center">@lang('contents.date')</th>
                                        <th class="text-center">Voucher Number</th>
                                        <th class="text-center">User Name</th>
                                        <th class="text-right print-none">@lang('contents.action')</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @forelse($production_in as $production)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}.</td>
                                        <td class="text-center">{{ $production->date->format('d F, Y') }}</td>
                                        <td class="text-center">
                                            {{ $production->voucher_no }}
                                        </td>

                                        <td class="text-center">{{ $production->user->name ?? '' }}</td>

                                        <td class="text-right print-none">
                                            <a href="{{ route('productionIn.show', $production->id) }}" class="btn btn-sm btn-info"
                                                title="View Details">
                                                <i class="fa fa-eye"></i>
                                            </a>

{{--                                            <a href="{{ route('productionIn.return', $production->voucher_no) }}" class="btn btn-sm btn-danger" title="Return">--}}
{{--                                                <i class="fa fa-undo"></i>--}}
{{--                                            </a>--}}
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="text-center">No Production In available.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

{{--                <div class="text-right">--}}
{{--                    {{ $productions->appends($_GET)->links() }}--}}
{{--                </div>--}}
            </div>
        </div>
    </div>
@endsection
