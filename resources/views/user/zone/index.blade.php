@extends('layouts.user')

@section('title', $title)

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 py-3">
                <div class="card">
                    <div class="d-none mt-2 text-center d-print-block">
                        <h5 class="mb-0 center" style="font-size: 25px"> <strong>{{ config('print.print_details.name') }}</strong> </h5>
                        <p class="mb-0 font-12">{{ config('print.print_details.address') }}</p>
                        <span class="mb-0 font-12">{{ config('print.print_details.mobile') }}</span>
                        <p class="mb-0" style="font-size: 15px">{{ Carbon\Carbon::now()->format('j F, Y h:i:s a') }}</p>
                    </div>

                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="m-0">Zone</h5>
                        <span class="d-none d-print-block">{{ date('d/m/Y') }}</span>
                        <div class="action-area print-none" role="group" aria-level="Action area">
                            <a href="#" onclick="window.print();" title="Print" class="btn btn-warning"><i aria-hidden="true" class="fa fa-print"></i></a>
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#newEUnitModal" title="Create new Unit">
                                <i class="fa fa-plus"></i>
                            </button>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Zone Name</th>
                                <th>@lang('contents.description')</th>
                                <th class="text-right print-none">@lang('contents.action')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($zones as $zone)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $zone->name }}</td>
                                    <td>{{ $zone->description }}</td>

                                    <td class="text-right print-none">

                                        <a class="btn btn-primary" href="{{ route('zone.edit', $zone->id) }}" title="Change zone information.">
                                            <i class="fa fa-pencil" aria-hidden="true"></i>
                                        </a>

                                        <a href="{{ route('zone.index') }}" class="btn btn-danger" title="Trash" onClick="if(confirm('Are you sure, want to move Trashed this record?')){event.preventDefault();document.getElementById('delete-form-{{ $zone->id }}').submit();} else {event.preventDefault();}">
                                            <i class="fa fa-times" aria-hidden="true"></i>
                                        </a>

                                        <form action="{{ route('zone.destroy', $zone->id) }}" method="post" id="delete-form-{{ $zone->id }}" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No zone available</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                        <!-- paginate -->
                        <div class="float-right mx-2">
                            {{ $zones->links() }}
                        </div>
                    </div>

                    <!-- start Add expense modal -->
                    <div class="modal fade" id="newEUnitModal" tabindex="-1" role="dialog" aria-labelledby="insertModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <form action="{{ route('zone.store') }}" method="post">
                                    @csrf

                                    <div class="modal-header">
                                        <h5 class="modal-title" id="insertModalLabel">Create Zone</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>

                                    <div class="modal-body">
                                        <div class="form-group required">
                                            <label for="name">Name</label>
                                            <input type="text" name="name" value="{{ old('name') }}" class="form-control" id="name" placeholder="Zone Name" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="description">@lang('contents.description')</label>
                                            <textarea name="description" placeholder="Zone Description" class="form-control" id="description"></textarea>
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">@lang('contents.close')</button>
                                        <button type="submit" class="btn btn-primary">@lang('contents.save')</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- end Add unit modal -->
                </div>
            </div>
        </div>
    </div>
@endsection
