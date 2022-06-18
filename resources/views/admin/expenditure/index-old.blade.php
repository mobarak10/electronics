@extends('layouts.admin')

@section('title', $title)
@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-12 py-3">
			<div class="card">

				<div class="card-header d-flex justify-content-between align-items-center">
					<h5 class="m-0">Expenditure</h5>
					<div class="btn-group" role="group" aria-level="Action area">
						<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#newExpenseModal" title="Create new Unit">
							<i class="fa fa-plus"></i>
						</button>
					</div>
				</div>

				<div class="card-body p-0">
					<table class="table table-hover">
						<thead>
							<tr>
								<th>Sl</th>
								<th>Title</th>
								<th>Status</th>
								<th class="text-right">Action</th>
							</tr>
						</thead>
						<tbody>
							@forelse($expenditures as $expenditure)
							<tr>
								<td>{{ $loop->iteration }}.
								</td>
								<td>{{ $expenditure->name }}</td>
								<td>
									<a href="{{ route('admin.expenseditureHead.status', $expenditure->id) }}" title="Click here to {{ ($expenditure->active) ? 'Inactive' : 'Active' }}" class="btn btn-{{ ($expenditure->active) ? 'danger' : 'success' }}">
                                        {{ ($expenditure->active) ? 'Inactive' : 'Active' }}
                                    </a>
								</td>
								
								<td class="text-right">

									<a class="btn btn-primary" href="{{ route('admin.expenditureHead.edit', $expenditure->id) }}" title="Change expenditure information.">
										<i class="fa fa-pencil" aria-hidden="true"></i>
									</a>
	                                    

                                    <a href="{{ route('admin.expenditureHead.index') }}" class="btn btn-danger" title="Trash" onClick="if(confirm('Are you sure, want to delete this record?')){event.preventDefault();document.getElementById('delete-form-{{ $expenditure->id }}').submit();} else {event.preventDefault();}">
                                        <i class="fa fa-times" aria-hidden="true"></i>
                                    </a>

                                    <form action="{{ route('admin.expenditureHead.destroy', $expenditure->id) }}" method="post" id="delete-form-{{ $expenditure->id }}" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
							</tr>
							@empty
							<tr>
								<td colspan="5" class="text-center">No expense available</td>
							</tr>
							@endforelse
						</tbody>
					</table>
					<!-- paginate -->
                    <div class="float-right mx-2">
                        {{ $expenditures->links() }}
                    </div>
				</div>

				<!-- start Add expense modal -->
                <div class="modal fade" id="newExpenseModal" tabindex="-1" role="dialog" aria-labelledby="insertModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <form action="{{ route('admin.expenditureHead.store') }}" method="post">
                                @csrf

                                <div class="modal-header">
                                    <h5 class="modal-title" id="insertModalLabel">Create Expense</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>

                                <div class="modal-body">
                                	<div class="form-group required">
                                        <label for="name">Expense Title</label>
                                        <input type="text" name="title" value="{{ old('title') }}" class="form-control" id="title" placeholder="Expense title maximum in 190 characters" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <textarea name="description" class="form-control" id="description" placeholder="Write Expense description (optional)"></textarea>
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
                <!-- end Add Expense modal -->
			</div>			
		</div>		
	</div>
</div>
@endsection