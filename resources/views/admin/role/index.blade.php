@extends('layouts.admin')

@section('title', $title)

@section('content')
<!-- Main Content -->
<div class="container">
	<div class="row">

		@foreach($roles as $key => $role)
			<div class="col-md-4 mb-3">
				<div class="card">
					<div class="card-body">
						<h5 class="card-title">{{ $role->name }}</h5>
						{{-- <p class="card-text">{{ $role->description }}</p> --}}

						<a href="{{ route('admin.role.show', $role->id) }}" class="btn btn-info">
							<i class="fa fa-eye"></i>
						</a>

						<a href="{{ route('admin.role.edit', $role->id) }}" class="btn btn-primary">
							<i class="fa fa-edit"></i>
						</a>

						<a href="{{ route('admin.role.index') }}" class="btn btn-danger float-right" onClick="if(confirm('Are you sure, You want to delete this?')){event.preventDefault();document.getElementById('delete-form-{{ $role->id }}').submit();} else {event.preventDefault();}">
							<i class="fa fa-trash"></i>
						</a>

						<form action="{{ route('admin.role.destroy', $role->id) }}" method="post" id="delete-form-{{ $role->id }}" style="display: none;">
							{{ csrf_field() }}
							{{ method_field('DELETE') }}
						</form>
					</div>
				</div>
			</div>
		@endforeach

		<!-- add button start -->
		<div class="col-md-4">
			<a href="{{ route('admin.role.create') }}" class="btn btn-primary d-flex justify-content-center align-items-center" title="Create new role" style="height: 7.3rem;">
				<span>
					<i class="fa fa-plus h2 mb-0"></i>
					<span class="d-block">Add a new role</span>
				</span>
			</a>
		</div>
		<!-- add button end -->
	</div>
</div>
@endsection

