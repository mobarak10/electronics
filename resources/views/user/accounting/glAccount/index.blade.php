@extends('layouts.user')

@section('title', $title)

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-12 py-3">

			<div class="card">
				<div class="card-header d-flex justify-content-between align-items-center">
					<h5 class="m-0">@lang('contents.gl_accounts')</h5>
					<div class="btn-group" role="group" aria-level="Action area">
						<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#newGLModal" title="Create new GL">
							<i class="fa fa-plus"></i>
						</button>
					</div>
				</div>

				<div class="card-body p-0 table-responsive">
					<table class="table table-hover">
						<thead>
							<tr>
								<th class="text-center">#</th>
								<th>@lang('contents.account_name')</th>
								<th>@lang('contents.operator')</th>
								<th>@lang('contents.status')</th>
								<th class="text-right">@lang('contents.action')</th>
							</tr>
						</thead>

						<tbody>
							@forelse($gl_accounts as $account)
								<tr>
									<td class="text-center">{{ $loop->iteration }}.</td>
									<td>{{ $account->name }}</td>
									<td>{{ $account->operator->name }}</td>
									<td>
										<a href="{{ route('glAccount.status', $account->id) }}" title="Click here to {{ ($account->status) ? 'Inactive' : 'Active' }}">
											<button class="btn btn-{{ ($account->status) ? 'danger' : 'success' }}">{{ ($account->status) ? 'Active' : 'Inactive' }}</button>
										</a>
									</td>
									
									<td class="text-right">
										<a class="btn btn-primary" href="{{ route('glAccount.show', $account->id) }}" title="Show GL account information.">
											<i class="fa fa-eye" aria-hidden="true"></i>
										</a>

										<a class="btn btn-primary" href="{{ route('glAccount.edit', $account->id) }}" title="Change GL account information.">
											<i class="fa fa-pencil" aria-hidden="true"></i>
										</a>
											
										<a href="{{ route('glAccount.index') }}" class="btn btn-danger" title="Trash" onClick="if(confirm('Are you sure, want to delete this record?')){event.preventDefault();document.getElementById('delete-form-{{ $account->id }}').submit();} else {event.preventDefault();}">
											<i class="fa fa-times" aria-hidden="true"></i>
										</a>

										<form action="{{ route('glAccount.destroy', $account->id) }}" method="post" id="delete-form-{{ $account->id }}" style="display: none;">
											@csrf
											@method('DELETE')
										</form>
									</td>
								</tr>
							@empty
								<tr>
									<td colspan="5" class="text-center">No GL Account available</td>
								</tr>
							@endforelse
						</tbody>
					</table>

					<!-- paginate -->
                    <div class="float-right mx-2">{{ $gl_accounts->links() }}</div>
				</div>

				<!-- start Add expense modal -->
                <div class="modal fade" id="newGLModal" tabindex="-1" role="dialog" aria-labelledby="insertModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <form action="{{ route('glAccount.store') }}" method="post">
                                @csrf

                                <div class="modal-header">
                                    <h5 class="modal-title" id="insertModalLabel">Create GL Account</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>

                                <div class="modal-body">
                        			<div class="form-group required">
                        		        <label for="name">GL Account Name</label>
                        		        <input type="text" name="name" value="{{ old('name') }}" class="form-control" id="name" placeholder="Ex:Custromer" required>
                        		    </div>

                                	<div class="form-group">
                                	    <label for="description">Decription</label>
                                	    <textarea name="description" class="form-control" id="description" placeholder="Write GL Account description (optional)"></textarea>
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
                <!-- end Add Expense modal -->
			</div>			
		</div>		
	</div>
</div>
@endsection