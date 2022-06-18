@extends('layouts.admin')

@section('title', $title)
@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-12 py-3">
			<div class="card">

				<div class="card-header d-flex justify-content-between align-item-center">
					<h5>Add Account</h5>
					<div class="btn-group" role="group" area-level="Active area">
						<a href="{{ route('bank_account.store') }}" class="btn btn-primary" title="All Account">
							<i class="fa fa-list" aria-hidden="true"></i>
						</a>
					</div>
				</div>

				<div class="card-body">
					<form action="" method="POST" accept-charset="utf-8">
						@csrf
						<div class="form-row">
							<div class="form-group col-md-6 required">
	                            <label for="name">Account Owner Name</label>
	                            <input type="text" value="{{ old('name') }}" placeholder="Enter Account Owner name" class="form-control" id="name" name="name" required>
	                        </div>

	                        <div class="form-group col-md-6 required">
	                            <label for="bank_id">Bank ID</label>
	                            <input type="text" value="{{ old('bank_id') }}" class="form-control" id="bank_id" name="bank_id" required>
	                        </div>
						</div>

						<div class="form-row">
	                        <div class="form-group col-md-6 required">
	                            <label for="account_number">Account Number</label>
	                            <input type="text" value="{{ old('account_number') }}" class="form-control" id="account_number" name="account_number" required>
	                        </div>

							<div class="form-group col-md-6">
	                            <label for="balance">Ballance</label>
	                            <input type="number"  value="{{ old('balance') }}" class="form-control" id="ballance" name="balance">
	                        </div>
						</div>

						<div class="form-row">
	                        <div class="form-group col-md-6 required">
	                            <label for="branch">Branch</label>
	                            <select name="branch" id="branch" class="form-control">
	                            	<option value="">1</option>
	                            	<option value="">2</option>
	                            	<option value="">3</option>
	                            </select>
	                        </div>

							<div class="form-group col-md-6 required">
	                            <label for="type">Account Type</label>
	                            <select name="type" class="form-control" id="ballance">
	                            	<option value="">Deposite</option>
	                            	<option value="">Savings</option>
	                            </select>
	                        </div>
						</div>

	                    <div class="text-right">
	                    	<button type="reset" class="btn btn-danger">Reset</button>
	                    	<button type="submit" class="btn btn-primary">Add Account</button>
	                    </div>
					</form>
				</div>
			</div>
		</div>		
	</div>	
</div>
@endsection