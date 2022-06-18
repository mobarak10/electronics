@extends('layouts.admin')

@section('title', $title)

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-12 py-3">
			<div class="card">

				<div class="card-header d-flex justify-content-between align-items-center">
					<h5 class="m-0">@lang('contents.edit_bank')</h5>
					<div class="btn-group" role="group" area-level="Action area">
						<a href="{{ route('admin.bank.index') }}" class="btn btn-primary" title="All Bank">
							<i class="fa fa-list" aria-hidden="true"></i>
						</a>
					</div>
				</div>	

				<div class="card-body">
					<form action="{{ route('admin.bank.update', $bank->id) }}" method="POST" accept-charset="utf-8">
						@csrf
						@method('PATCH')
						<div class="form-row">
							<div class="form-group col-md-6 required">
								<label for="name">@lang('contents.bank_name')</label>
								<input type="text" name="name" placeholder="Enter bank name" class="form-control" value="{{ $bank->name }}" id="name">
							</div>

							<div class="form-group col-md-6 required">
								<label for="status">@lang('contents.status')</label>
								<select name="status" class="form-control" id="status" required>
                                    <option value="1" {{ $bank->status == 1 ? 'selected' : '' }}>Active</option>

                                    <option value="0" {{ $bank->status == 0 ? 'selected' : '' }}>Deactive</option>
                                </select>
							</div>
						</div>
						<div class="text-right">
							<button type="reset" class="btn btn-danger">@lang('contents.reset')</button>
							<button type="submit" class="btn btn-primary left">@lang('contents.save_changes')</button>
						</div>
					</form>
				</div>			
			</div>			
		</div>		
	</div>	
</div>
@endsection