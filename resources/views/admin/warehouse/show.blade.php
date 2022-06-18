@extends('layouts.admin')

@section('title', $title)

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 py-3">

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="m-0">@lang('contents.warehouse_records')</h5>

                        <div class="btn-group" role="group" aria-label="Action area">
                            <a href="{{ route('admin.warehouse.index') }}" class="btn btn-primary" title="All cash">
                                <i class="fa fa-list" aria-hidden="true"></i>
                            </a>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <table class="table table-hover">
                            {{-- <thead>
                            <tr>
                                <th>Code</th>
                                <th>Title</th>
                                <th>Address</th>
                                <th>Description</th>
                                <th>Manager</th>
                                <th>Status</th>
                                <th class="text-right">Action</th>
                            </tr>
                            </thead> --}}

                            <tbody>
                                <tr>
                                    <td>@lang('contents.code'):</td>
                                    <td>{{ $warehouse->code }}</td>
                                </tr>

                                <tr>
                                    <td>@lang('contents.title'):</td>
                                    <td>{{ $warehouse->title }}</td>
                                </tr>

                                <tr>
                                    <td>@lang('contents.address'):</td>
                                    <td>{{ $warehouse->address }}</td>
                                </tr>

                                <tr>
                                    <td>@lang('contents.description'):</td>
                                    <td><textarea class="form-control" readonly>{{ $warehouse->description }}</textarea></td>
                                </tr>

                                <tr>
                                    <td>@lang('contents.employee_name'):</td>
                                    <td>{{ $warehouse->user->name }}</td>
                                </tr>

                                <tr>
                                    <td>@lang('contents.status'):</td>
                                    <td>
                                        @if($warehouse->status == 1)
                                            {{ 'Active' }}
                                            @else
                                            {{ 'Deactive' }}
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
