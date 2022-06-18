@extends('layouts.admin')

@section('title', __('contents.all_employees'))

@section('content')
<!-- Main Content -->
<div class="container">
	
	<div class="card">
		<div class="card-header d-flex justify-content-between align-items-center">
			<h5 class="m-0">@lang('contents.all_employees')</h5>

			<div class="btn-group" role="group" aria-label="Action area">
				<a href="{{ route('admin.employee.create') }}" class="btn btn-primary" title="Create new account.">
					<i class="fa fa-plus"></i>
				</a>
			</div>
			
		</div>
			
		<div class="card-body p-0">
			<table class="table">
				<thead>
					<tr>
						<th class="text-center">#</th>
						<th>@lang('contents.name')</th>
						<th>@lang('contents.phoneNumber')</th>
						<th>@lang('contents.email')</th>
						<th>@lang('contents.status')</th>
						<th class="text-right">@lang('contents.action')</th>
					</tr>
				</thead>
		
				<tbody>
					@foreach($users as $key => $user)
						<tr>
							<td class="text-center">{{ $loop->index + 1 }}.</td>
							<td>{{ $user->name }}</td>
							<td>{{ $user->phone }}</td>
							<td>{{ $user->email }}</td>
							<td>
								<a href="{{ route('admin.employee.changeEmployeeStatus', $user->id) }}" title="{{ $user->status ? 'Active' : 'Inactive' }}" class="{{ ($user->status) ? "btn btn-success" : "btn btn-danger" }}" title="Employee details.">
								{{ $user->status ? 'Active' : 'Inactive' }}
								</a>
							</td>
							<td class="text-right">
								<a href="{{ route('admin.employee.show', $user->id) }}" class="btn btn-success" title="Employee details.">
									<i class="fa fa-eye" aria-hidden="true"></i>
								</a>

								<a href="{{ route('admin.employee.edit', $user->id) }}" class="btn btn-primary" title="Change account information.">
									<i class="fa fa-pencil" aria-hidden="true"></i>
								</a>

								{{--
								<a href="{{ route('admin.employee.index') }}" class="btn btn-danger" title="Trash" onClick="if(confirm('Are you sure, You want to delete this?')){event.preventDefault();document.getElementById('delete-form-{{ $user->id }}').submit();} else {event.preventDefault();}">
									<i class="fa fa-times" aria-hidden="true"></i>
								</a>

								<form action="{{ route('admin.employee.destroy', $user->id) }}" method="post" id="delete-form-{{ $user->id }}" style="display: none;">
									{{ csrf_field() }}
									{{ method_field('DELETE') }}
								</form>
								--}}
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>
@endsection

