@extends('layouts.user')

@section('title', $title)

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 py-3">
                <h1 class="text-center pt-5 pb-4 d-none d-print-block">Maxsop</h1>
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="m-0">Income Source</h5>

                        <span class="d-none d-print-block">{{ date('d/m/Y') }}</span>
                        <div class="action-area print-none" role="group" aria-level="Action area">
                            <a href="#" onclick="window.print();" title="Print" class="btn btn-warning"><i aria-hidden="true" class="fa fa-print"></i></a>

                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#newIncomeSector" title="Create new income sector">
                                <i class="fa fa-plus"></i>
                            </button>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Income Source</th>
                                <th>@lang('contents.description')</th>
                                <th class="text-right print-none">@lang('contents.action')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($incomeSectors as $sector)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $sector->sector_name }}</td>
                                    <td>{{ $sector->description }}</td>

                                    <td class="text-right print-none">

                                        <a class="btn btn-primary" href="{{ route('incomeSector.edit', $sector->id) }}" title="Change unit information.">
                                            <i class="fa fa-pencil" aria-hidden="true"></i>
                                        </a>

                                        <a href="{{ route('incomeSector.index') }}" class="btn btn-danger" title="Trash" onClick="if(confirm('Are you sure, want to delete this record?')){event.preventDefault();document.getElementById('delete-form-{{ $sector->id }}').submit();} else {event.preventDefault();}">
                                            <i class="fa fa-times" aria-hidden="true"></i>
                                        </a>

                                        <form action="{{ route('incomeSector.destroy', $sector->id) }}" method="post" id="delete-form-{{ $sector->id }}" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No income sector available</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                        <!-- paginate -->
                        <div class="float-right mx-2">
                            {{ $incomeSectors->links() }}
                        </div>
                    </div>

                    <!-- start Add expense modal -->
                    <div class="modal fade" id="newIncomeSector" tabindex="-1" role="dialog" aria-labelledby="insertModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <form action="{{ route('incomeSector.store') }}" method="post">
                                    @csrf

                                    <div class="modal-header">
                                        <h5 class="modal-title" id="insertModalLabel">Create Income Source</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>

                                    <div class="modal-body">
                                        <div class="form-group required">
                                            <label for="name">Source Name</label>
                                            <input type="text" name="sector_name" value="{{ old('sector_name') }}" class="form-control" id="name" placeholder="Ex: Book Sale" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="description">@lang('contents.description')</label>
                                            <textarea name="description" placeholder="Write description here" class="form-control" id="description"></textarea>
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
