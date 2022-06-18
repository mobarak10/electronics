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
					<h5 class="m-0">@lang('contents.unit_converter')</h5>
					<span class="d-none d-print-block">{{ date('d/m/Y') }}</span>
					<div class="action-area print-none" role="group" aria-level="Action area">
						<a href="#" onclick="window.print();" title="Print" class="btn btn-warning"><i aria-hidden="true" class="fa fa-print"></i></a>
						<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#newEUnitModal" title="Create new Unit">
							<i class="fa fa-plus"></i>
						</button>
						<a href="{{ route('unit.viewTrashed') }}" title="view trashed" class="btn btn-danger">
                                <i class="fa fa-trash" aria-hidden="true"></i>
                            </a>
					</div>
				</div>

				<div class="card-body p-0">
					<table class="table table-hover">
						<thead>
							<tr>
								<th>#</th>
								<th>@lang('contents.unit_name')</th>
								<th>@lang('contents.labels')</th>
								<th>@lang('contents.relation')</th>
								<th>@lang('contents.description')</th>
								<th class="text-right print-none">@lang('contents.action')</th>
							</tr>
						</thead>
						<tbody>
							@forelse($units as $unit)
							<tr>
								<td>{{ $loop->iteration }}</td>
								<td>{{ $unit->name }}</td>
								<td>{{ $unit->labels }}</td>
								<td>{{ $unit->relation }}</td>
								<td>{{ $unit->description }}</td>

								<td class="text-right print-none">
                                    @unless($unit->products->count())
                                        <a class="btn btn-primary" href="{{ route('unit.edit', $unit->id) }}" title="Change unit information.">
                                            <i class="fa fa-pencil" aria-hidden="true"></i>
                                        </a>
                                    @endunless

                                    @unless($unit->products->count())
                                    	<a href="{{ route('unit.index') }}" class="btn btn-danger" title="Trash" onClick="if(confirm('Are you sure, want to move Trashed this record?')){event.preventDefault();document.getElementById('delete-form-{{ $unit->id }}').submit();} else {event.preventDefault();}">
                                    	    <i class="fa fa-times" aria-hidden="true"></i>
                                    	</a>

                                    	<form action="{{ route('unit.destroy', $unit->id) }}" method="post" id="delete-form-{{ $unit->id }}" style="display: none;">
                                    	    @csrf
                                    	    @method('DELETE')
                                    	</form>
                                    @endunless
                                </td>
							</tr>
							@empty
							<tr>
								<td colspan="8" class="text-center">No unit available</td>
							</tr>
							@endforelse
						</tbody>
					</table>
					<!-- paginate -->
                    <div class="float-right mx-2">
                        {{ $units->links() }}
                    </div>
				</div>

				<!-- start Add expense modal -->
                <div class="modal fade" id="newEUnitModal" tabindex="-1" role="dialog" aria-labelledby="insertModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <form action="{{ route('unit.store') }}" method="post">
                                @csrf

                                <div class="modal-header">
                                    <h5 class="modal-title" id="insertModalLabel">@lang('contents.create_unit')</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>

                                <div class="modal-body">
                                	<div class="form-group required">
                                        <label for="name">@lang('contents.unit_name')</label>
                                        <input type="text" name="name" value="{{ old('name') }}" class="form-control" id="name" placeholder="Box" required>
                                    </div>

                                    <div class="row">
                                    	<div class="form-group col-md-6 required">
	                                        <label for="label">@lang('contents.labels')</label>
	                                        <input type="text" name="label" value="{{ old('name') }}" class="form-control" id="label" placeholder="Ex: Box/Kg" required>
	                                    </div>

	                                    <div class="form-group col-md-6 required">
	                                        <label for="relation">@lang('contents.relation')</label>
	                                        <input type="text" name="relation" value="{{ old('name') }}" class="form-control" id="relation" placeholder="Ex: 1/50" required>
	                                    </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="description">@lang('contents.description')</label>
                                        <textarea name="description" placeholder="Ex:(1 Box = 50 Kg)" class="form-control" required id="description"></textarea>
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
