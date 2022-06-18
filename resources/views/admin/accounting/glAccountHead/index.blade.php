@extends('layouts.admin')

@section('title', $title)

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-12 py-3">

			<div class="card">
				<div class="card-header d-flex justify-content-between align-items-center">
					<h5 class="m-0">@lang('contents.gl_accounts_head')</h5>

					<div class="btn-group" role="group" aria-level="Action area">
						<a href="{{ route('admin.glAccountHead.create') }}" class="btn btn-primary">
							<i class="fa fa-plus"></i>
                        </a>
					</div>
				</div>

				<div class="card-body p-0">
					<table class="table table-hover">
						<thead>
							<tr>
								<th class="text-center">#</th>
								<th>@lang('contents.gl_accounts_head')</th>
								<th>@lang('contents.gl_accounts')</th>
								<th>@lang('contents.created_by')</th>
								<th>@lang('contents.status')</th>
								<th class="text-right">@lang('contents.action')</th>
							</tr>
						</thead>

						<tbody>
							@forelse($glHeads as $head)
								<tr>
									<td class="text-center">{{ $loop->iteration }}.</td>
									<td>{{ $head->name }}</td>
									<td>{{ $head->glAccount->name }}</td>
									<td>{{ $head->glAccount->operator->name }}</td>
									<td>
										<a href="{{ route('admin.glAccountHead.status', $head->id) }}" class="btn btn-{{ ($head->status) ? 'danger' : 'success' }}" title="Click here to {{ ($head->status) ? 'Inactive' : 'Active' }}">
											{{ ($head->status) ? 'Active' : 'Inactive' }}
										</a>
									</td>
									
									<td class="text-right">
										<a class="btn btn-primary" href="{{ route('admin.glAccountHead.show', $head->id) }}" title="Show GL account information.">
											<i class="fa fa-eye" aria-hidden="true"></i>
										</a>

										<a class="btn btn-primary" href="{{ route('admin.glAccountHead.edit', $head->id) }}" title="Change GL account information.">
											<i class="fa fa-pencil" aria-hidden="true"></i>
										</a>
											
										<a href="{{ route('admin.glAccountHead.index') }}" class="btn btn-danger" title="Trash" onClick="if(confirm('Are you sure, want to delete this record?')){event.preventDefault();document.getElementById('delete-form-{{ $head->id }}').submit();} else {event.preventDefault();}">
											<i class="fa fa-times" aria-hidden="true"></i>
										</a>

										<form action="{{ route('admin.glAccountHead.destroy', $head->id) }}" method="post" id="delete-form-{{ $head->id }}" style="display: none;">
											@csrf
											@method('DELETE')
										</form>
									</td>
								</tr>
							@empty
								<tr>
									<td colspan="6" class="text-center">No GL Head available</td>
								</tr>
							@endforelse
						</tbody>
					</table>

					<!-- paginate -->
                    <div class="float-right mx-2">{{ $glHeads->links() }}</div>
				</div>
			</div>	

		</div>		
	</div>
</div>
@endsection