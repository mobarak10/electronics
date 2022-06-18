@extends('layouts.admin')

@section('title', $title)

@section('content')
<!-- Main Content -->
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header d-flex justify-content-between align-items-center">
					<h5 class="m-0">All Permissions</h5>

					<div class="btn-group" role="group" aria-level="Action area">
						<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#newBankModal" title="Create new Bank">
							<i class="fa fa-plus"></i>
						</button>
					</div>
				</div>
	
				<div class="card-body p-0">
					<table class="table table-hover">
						<thead>
							<tr>
								<th>SL</th>
								<th>Menu name</th>
								<th class="text-right">Action</th>
							</tr>
						</thead>
				
						<tbody>
							@foreach($permissions as $key => $permission)
								<tr>
									<td>{{ $loop->index + 1 }}.</td>
									<td>{{ $permission->menu->name }}</td>
									<td class="text-right">
									<a href="{{ route('admin.permission.show', $permission->menu_id) }}" target="_blank" class="btn btn-info">
											<i class="fa fa-eye"></i>
										</a>
									</td>
								</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>

			<!-- insert modal start -->
			<div class="modal fade" id="newBankModal" tabindex="-1" role="dialog" aria-labelledby="insertModalLabel" aria-hidden="true">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<form action="{{ route('admin.permission.store') }}" method="post">
							@csrf

							<div class="modal-header">
								<h5 class="modal-title" id="insertModalLabel">Create a new Permission </h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>

							<div class="modal-body">
								<div class="form-group required">
									<label>Permission name</label>
									<select name="menu" class="form-control" required>
										<option value="" selected disabled>&nbsp;</option>

										@foreach ($menus as $menu)
											<option value="{{ $menu->id }}">{{ $menu->name }}</option>
										@endforeach
									</select>
								</div>

								<div class="form-group required">
									@foreach (config('coderill.actions') as $slug => $action)
										<div class="form-check form-check-inline">
										<input class="form-check-input" type="checkbox" name="action[]" id="action-{{ $slug }}" value="{{ $slug }}" checked>
											<label class="form-check-label" for="action-{{ $slug }}">{{ $action }}</label>
										</div>
									@endforeach
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
			<!-- insert modal end -->
		</div>
	</div>
</div>
@endsection

