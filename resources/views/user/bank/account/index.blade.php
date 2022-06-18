@extends('layouts.user')

@section('title', $title)

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-12 py-3">
			<div class="card">
                <div class="d-none mt-2 text-center d-print-block">
                    <h5 class="mb-0 center" style="font-size: 25px"> <strong>{{ config('print.print_details.name') }}</strong> </h5>
                    <p class="mb-0 font-12">{{ config('print.print_details.address') }}</p>
                    <span class="mb-0 font-12">{{ config('print.print_details.mobile') }}</span>
                    <p class="mb-0" style="font-size: 15px">{{ Carbon\Carbon::now()->format('j F, Y h:i:s a') }}</p>
                </div>

				<div class="card-header d-flex justify-content-between align-items-center">
					<h5 class="m-0">@lang('contents.bank_account')</h5>
					<span class="d-none d-print-block">10/5/20</span>
					<div class="action-area print-none" role="group" aria-level="Action area">
						<a href="#" onclick="window.print();" title="Print" class="btn btn-warning print-none"><i aria-hidden="true" class="fa fa-print"></i></a>
						<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#newBankAccoutnModal" title="Create new Account">
							<i class="fa fa-plus"></i>
						</button>
					</div>
				</div>

				<div class="card-body p-0">
					<table class="table table-hover">
						<thead>
							<tr>
								<th>#</th>
								<th>@lang('contents.account_name')</th>
								<th>@lang('contents.bank_name')</th>
								<th>@lang('contents.account_number')</th>
								<th class="text-right">@lang('contents.balance') (@lang('contents.bdt'))</th>
								<th class="text-right print-none">@lang('contents.action')</th>
							</tr>
						</thead>

						<tbody>
							@forelse($bank_account as $account)
							<tr>
								<td>{{ $loop->iteration }}.</td>
								<td>{{ $account->account_name }}</td>
								<td>{{ $account->bank->name }}</td>
								<td>
									<a title="show transaction details" href="#">
										{{ $account->account_number }}
									</a>
								</td>
								<td class="text-right">{{ number_format($account->balance, 2) }}</td>
								<td class="text-right print-none">
									<a href="{{ route('bankAccount.show', $account->bank->id) }}" class="btn btn-primary" title="Show this bank account information.">
                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                    </a>

                                     <a href="{{ route('bankAccount.edit', $account->id) }}" class="btn btn-primary" title="Change Bank Account information.">
                                        <i class="fa fa-pencil" aria-hidden="true"></i>
                                    </a>

                                    <a href="{{ route('bankAccount.index') }}" class="btn btn-danger" title="Trash" onClick="if(confirm('Are you sure, You want to delete this record?')){event.preventDefault();document.getElementById('delete-form-{{ $account->id }}').submit();} else {event.preventDefault();}">
                                        <i class="fa fa-times" aria-hidden="true"></i>
                                    </a>

                                    <form action="{{ route('bankAccount.destroy', $account->id) }}" method="post" id="delete-form-{{ $account->id }}" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
							</tr>
							@empty
							<tr>
								<td colspan="6" class="text-center">No bank account available.</td>
							</tr>
							@endforelse

                            <tr>
                                <td colspan="4" class="text-right">Total </td>
                                <td class="text-right">{{ number_format($total_bank_balance, 2) }}</td>
                                <td class="print-none">&nbsp;</td>
                            </tr>
						</tbody>
					</table>

					<!-- paginate -->
                    <div class="float-right mx-2">
                        {{ $bank_account->links() }}
                    </div>
				</div>

				<!-- New account modal -->
                <div class="modal fade" id="newBankAccoutnModal" tabindex="-1" role="dialog" aria-labelledby="insertModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <form action="{{ route('bankAccount.store') }}" method="post">
                                @csrf

                                <div class="modal-header">
                                    <h5 class="modal-title" id="insertModalLabel">Create new Bank Account </h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>

                                <div class="modal-body">
                                	<div class="row">
                                    	<div class="form-group col-md-6 required">
	                                        <label for="name">@lang('contents.account_name')</label>
	                                        <input type="text" name="name" class="form-control" id="name" placeholder="Enter account owner Name" value="{{ old('name') }}" required>
	                                    </div>

	                                     <div class="form-group col-md-6 required">
				                            <label for="bank_id">@lang('contents.bank_name')</label>
	                                         <select name="bank_id" id="bank_id" class="form-control" required>
	                                             <option value="" selected disabled>Choose a bank</option>
	                                             @foreach($banks as $bank)
	                                             	<option value="{{ $bank->id }}">{{ $bank->name }}</option>}
	                                             @endforeach
	                                         </select>
				                        </div>

				                        <div class="form-group col-md-6 required">
				                            <label for="kind">@lang('contents.account_type')</label>
				                            <select name="kind" class="form-control" id="kind" required>
				                            	<option value="" selected disabled>Choose account type</option>

	                                            @foreach(config('coderill.bank.account.kind') as $key => $kind)
				                            	    <option value="{{ $key }}">{{ $kind }}</option>
	                                            @endforeach
				                            </select>
				                        </div>

				                        <div class="form-group col-md-6 required">
				                            <label for="account_number">@lang('contents.account_number')</label>
				                            <input type="text" class="form-control" id="account_number" placeholder="enter account number" name="account_number" required>
				                        </div>

				                        <div class="form-group col-md-6 required">
				                            <label for="branch">@lang('contents.branch')</label>
	                                         <input type="text" name="branch" class="form-control" id="branch" placeholder="enter branch address of this bank" required>
				                        </div>

				                        <div class="form-group col-md-6">
				                            <label for="balance">@lang('contents.balance') (@lang('contents.bdt'))</label>
				                            <input type="number" placeholder="enter balance" class="form-control" id="balance" name="balance" step="any">
				                        </div>
			                        </div>

			                        <div class="form-group">
			                            <label for="note">@lang('contents.note')</label>
			                            <textarea name="note" id="ballance" class="form-control" placeholder="write sort note (optional)"></textarea>
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
			</div>
		</div>
	</div>
</div>
@endsection
