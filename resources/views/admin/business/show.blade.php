@extends('layouts.admin')

@section('title', $title)

@section('content')
<!-- Main Content -->
<div class="container-fluid">
	<div class="row">

		<!-- Profile -->
		<div class="col-md-4 mb-3">
			<div class="card">
				<img src="{{ asset(($business->media) ? $business->media->real_path : 'public/images/avatar.jpeg') }}" class="card-img-top" alt="{{ $business->name }}">

				<div class="card-body">
					<h5 class="card-title">{{ $business->name }}</h5>
					<p class="card-text">{{ $business->email }}</p>
				</div>

				<ul class="list-group list-group-flush">
					<li class="list-group-item">
						<small class="d-block">Phone</small>
						{{ $business->phone }}
					</li>

					<li class="list-group-item">
						<small class="d-block">Address</small>
						{{ $business->address }}
					</li>

				</ul>

				<div class="card-body">
					<a href="{{ route('admin.business.edit', $business->id) }}" class="card-link">Change</a>
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
					<a class="nav-link" data-toggle="tab" href="#profile-blank" role="tab" aria-controls="blank" aria-selected="false">Blank</a>
				</li>
			</ul>

			<div class="tab-content">
				<div class="tab-pane fade show active" id="profile-home" role="tabpanel" aria-labelledby="home-tab">
					<div class="col mt-3">
						<p>Account create at <strong>{{ $business->created_at->format('j F, Y') }}</strong> and last updated at <strong>{{ $business->updated_at->format('j F, Y') }}</strong></p>
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

