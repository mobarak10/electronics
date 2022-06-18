@extends('layouts.user')

@section('title', $title)

@section('content')
<!-- Main Content -->
<div class="container-fluid">
	<div class="row">

		<!-- Profile -->
		<div class="col-md-4 mb-3">
			<div class="card">
				<img src="{{ asset($record->media->real_path ?? '') }}" class="card-img-top" alt="{{ $record->name }}">

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
							<small class="d-block">
								@php 
								$metaKey = config('coderill.admin.meta');
								@endphp

								{{ $metaKey[$meta->meta_key] }}
							</small>
							{{ $meta->meta_value }}
						</li>
					@endforeach
				</ul>

				<div class="card-body">
					<a href="{{ route('account.edit', $record->id) }}" class="card-link">Change</a>
					<a href="{{ route('account.edit', $record->id) }}" class="card-link">Deactivate</a>
				</div>
			</div>
		</div>
		<!-- End of the profile -->

		<!-- Details -->
		<div class="col-md-8">
			<article>
				<h4>Account</h4>
				<p>Account create at <strong>{{ $record->created_at->format('j F, Y') }}</strong> and last updated at <strong>{{ $record->updated_at->format('j F, Y') }}</strong></p>
			</article>
			<hr>

			<article>
				<h4>Role and Permission</h4>

				@foreach ($record->roles as $role)
					<div class="mb-3">
						<strong>{{ $role->name }}</strong>
						<p>{{ $role->description }}</p>

						<div class="ml-3">
							<strong class="d-block">Permissions</strong>

							@foreach ($role->permissions as $permission)
								<span class="badge badge-primary rounded-0 px-2 py-1" title="{{ $permission->description }}">
									{{ $permission->name }}
								</span>
							@endforeach
						</div>
					</div>
				@endforeach
			</article>
		</div>
		<!-- Details end -->

	</div>
</div>
@endsection

