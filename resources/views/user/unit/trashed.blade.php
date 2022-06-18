@extends('layouts.user')

@section('title', 'Trashed Unit')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 py-3">

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="m-0">Trashed Unit</h5>

                        <div class="btn-group" role="group" aria-label="Action area">
                            <a href="{{ route('unit.index') }}" class="btn btn-primary" title="Show All">
                                <i class="fa fa-list"></i>
                            </a>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>@lang('contents.unit_name')</th>
                                <th class="text-right">@lang('contents.action')</th>
                            </tr>
                            </thead>

                            <tbody>
                            @forelse($trashedUnit as $unit)
                                <tr>
                                    <td>{{ $loop->iteration }}.</td>
                                    <td>{{ $unit->name }}</td>

                                    <td class="text-right">
                                        <a
                                            href="{{ route('unit.viewTrashed', $unit->id) }}"
                                            class="btn btn-sm btn-primary"
                                            title="Restore Category."
                                            onClick="if(confirm('Are you sure want to restore this record?'))
                                            {event.preventDefault();document.getElementById('restore-form-{{ $unit->id }}').submit();} else {event.preventDefault();}">
                                            <i class="fa fa-repeat" aria-hidden="true"></i>
                                        </a>

                                        <form action="{{ route('unit.restore', $unit->id) }}" method="post" id="restore-form-{{ $unit->id }}" style="display: none;">
                                            @csrf
                                            @method('POST')
                                        </form>

                                        <a href="{{ route('unit.viewTrashed') }}" class="btn btn-sm btn-danger" title="permanently delete" onClick="if(confirm('Are you sure want to permanently delete this record?')){event.preventDefault();document.getElementById('delete-form-{{ $unit->id }}').submit();} else {event.preventDefault();}">
                                            <i class="fa fa-times" aria-hidden="true"></i>
                                        </a>

                                        <form action="{{ route('unit.forceDelete', $unit->id) }}" method="post" id="delete-form-{{ $unit->id }}" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Empty Trashed</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>

                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
