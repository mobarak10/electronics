@extends('layouts.admin')

@section('title', $title)
@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-12 py-3">
            <h1 class="text-center pt-5 pb-4 d-none d-print-block">জননী বস্ত্রালয়</h1>
			<div class="card">

				<div class="card-header d-flex justify-content-between align-items-center">
					<h5 class="m-0">@lang('contents.banks')</h5>
                    <span class="d-none d-print-block">10/5/20</span>
					<div class="action-area print-none" role="group" aria-level="Action area">
                        <a href="#" onclick="window.print();" title="Print" class="btn btn-warning"><i aria-hidden="true" class="fa fa-print"></i></a>
						<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#newBankModal" title="Create new Bank">
							<i class="fa fa-plus"></i>
						</button>
					</div>
				</div>

				<div class="card-body p-0">
					<table class="table table-hover">
						<thead>
							<tr>
								<th>#</th>
								<th>@lang('contents.bank_name')</th>
								<th class="print-none">@lang('contents.status')</th>
								<th class="text-right print-none">@lang('contents.action')</th>
							</tr>
						</thead>
						<tbody>
							@forelse($banks as $bank)
							<tr>
								<td>{{ $loop->iteration }}.</td>
								<td><a title="Show this bank account information." href="{{ route('admin.bankAccount.show', $bank->id) }}">{{ $bank->name }}</a></td>
								<td class="print-none">
									<a href="{{ route('admin.bank.status', $bank->id) }}" title="Click here to {{ ($bank->status) ? 'Inactive' : 'Active' }}" class="btn btn-{{ ($bank->status) ? 'danger' : 'success' }}">
                                        {{ ($bank->status) ? 'Inactive' : 'Active' }}
                                    </a>
								</td>

								<td class="text-right print-none">

                                    <a href="{{ route('admin.bankAccount.show', $bank->id) }}" class="btn btn-primary" title="Show this bank account information.">
                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                    </a>

                                     <a href="{{ route('admin.bank.edit', $bank->id) }}" class="btn btn-primary" title="Change bank information.">
                                        <i class="fa fa-pencil" aria-hidden="true"></i>
                                    </a>

                                    <a href="{{ route('admin.bank.index') }}" class="btn btn-danger" title="Trash" onClick="if(confirm('Are you sure, You want to delete this record?')){event.preventDefault();document.getElementById('delete-form-{{ $bank->id }}').submit();} else {event.preventDefault();}">
                                        <i class="fa fa-times" aria-hidden="true"></i>
                                    </a>

                                    <form action="{{ route('admin.bank.destroy', $bank->id) }}" method="post" id="delete-form-{{ $bank->id }}" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
							</tr>
							@empty
							<tr>
								<td colspan="5" class="text-center">No Bank available</td>
							</tr>
							@endforelse
						</tbody>
                    </table>
                    
					<!-- paginate -->
                    <div class="float-right mx-2">
                        {{ $banks->links() }}
                    </div>
                </div>
                
				<!-- New bank modal -->
                <div class="modal fade" id="newBankModal" tabindex="-1" role="dialog" aria-labelledby="insertModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <form action="{{ route('admin.bank.store') }}" method="post">
                                @csrf

                                <div class="modal-header">
                                    <h5 class="modal-title" id="insertModalLabel">Create a new Bank </h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>

                                <div class="modal-body">
                                    <div class="form-group required">
                                        <label for="name">Add New Bank</label>
                                        <input type="text" name="name" value="{{ old('name') }}" class="form-control" id="name" placeholder="Bank name" required>
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
			</div>			
		</div>		
	</div>
</div>
@endsection