@extends('layouts.admin')

@section('title', $title)

@section('content')
<!-- Main Content -->
<div class="container-fluid">
	<div class="row">

		<!-- Profile -->
		<div class="col-md-4 mb-3">
			<div class="card">
				<img src="{{ asset(($record->media) ? $record->media->real_path : 'public/images/avatar.jpeg') }}" class="card-img-top" alt="{{ $record->name }}">

				<div class="card-body">
					<h5 class="card-title">{{ $record->name }}</h5>
					<p class="card-text">{{ $record->email }}</p>
				</div>

				<ul class="list-group list-group-flush">
					<li class="list-group-item">
						<small class="d-block">Username</small>
						{{ $record->username }}
					</li>

					@foreach ($record->metas as $meta)
						<li class="list-group-item">
							<small class="d-block">{{ config('coderill.admin.meta')[$meta->meta_key] }}</small>
							{{ $meta->meta_value }}
						</li>
					@endforeach
				</ul>

				<div class="card-body">
					<a href="{{ route('admin.account.edit', $record->id) }}" class="card-link">Change</a>
					<a href="{{ route('admin.account.edit', $record->id) }}" class="card-link">
						Deactivate
					</a>
				</div>
			</div>
		</div>
		<!-- End of the profile -->

		<!-- Details tab -->
		<div class="col-md-8">
			<ul class="nav nav-tabs" role="tablist">
				<li class="nav-item">
					<a class="nav-link active" data-toggle="tab" href="#profile-home" role="tab" aria-controls="home" aria-selected="true">Home</a>
				</li>

				<li class="nav-item">
					<a class="nav-link" data-toggle="tab" href="#profile-access" role="tab" aria-controls="access" aria-selected="false">Access</a>
				</li>

				<li class="nav-item">
					<a class="nav-link" data-toggle="tab" href="#profile-blank" role="tab" aria-controls="blank" aria-selected="false">Blank</a>
				</li>
			</ul>

			<div class="tab-content">
				<div class="tab-pane fade show active" id="profile-home" role="tabpanel" aria-labelledby="home-tab">
					<div class="col mt-3">
						<p>Account create at <strong>{{ $record->created_at->format('j F, Y') }}</strong> and last updated at <strong>{{ $record->updated_at->format('j F, Y') }}</strong></p>

						{{-- @can('account-create')
							<a href="{{ route('account.create') }}">Add New</a>
						@endcan --}}
					</div>
				</div>

				<div class="tab-pane fade" id="profile-access" role="tabpanel" aria-labelledby="access-tab">
					<div class="table-responsive">
						<table class="table">
							<caption>Last 30 records ({{ $accessLogs->count() }})</caption>

							<thead>
								<tr>
									<th scope="col">#</th>
									<th scope="col">In Time</th>
									<th scope="col">Out Time</th>
									<th scope="col" class="text-center">Status</th>
									<th scope="col" class="text-right">IP Address</th>
								</tr>
							</thead>

							<tbody>
								@forelse ($accessLogs->sortByDesc('id') as $log)
									<tr>
										<th scope="row">{{ $loop->iteration }}.</th>
										<td>{{ $log->created_at->format('j M, y h:i:m a') }}</td>
										<td>{{ $log->updated_at->format('j M, y h:i:m a') }}</td>
										<td class="text-center">{{ ucwords($log->status) }}</td>
										<td class="text-right">{{ $log->ip }}</td>
									</tr>
								@empty
									
								@endforelse
							</tbody>
						</table>
					</div>
				</div>

				<div class="tab-pane fade" id="profile-blank" role="tabpanel" aria-labelledby="blank-tab">
					Blank
				</div>
			</div>
		</div>
		<!-- Details tab end -->
		
	</div>
</div>
@endsection

