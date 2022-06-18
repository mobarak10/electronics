@extends('layouts.admin')

@section('title', $title)

@section('content')
<!-- Main Content -->
<div class="container">

	<div class="card">
        <div class="card-header">
            <h5 class="m-0">@lang('contents.create_new_account')</h5>
        </div>

        <div class="card-body py-2">
			<form action="{{ route('admin.account.store') }}" method="POST" class="row">
				@csrf

				<div class="form-group col-md-6 required">
					<label for="name">@lang('contents.full_name')</label>
					<input type="text" name="name" class="form-control" id="name" required>
				</div>

				<div class="form-group col-md-6 required">
					<label for="phone">@lang('contents.phoneNumber')</label>
					<input type="text" name="phone" class="form-control" id="phone" required>
				</div>

				<div class="form-group col-md-6 required">
					<label for="email">@lang('contents.email')</label>
					<input type="email" name="email" class="form-control" id="email" required>
				</div>

				<div class="form-group col-md-6 required">
					<label for="username">@lang('contents.username')</label>
					<input type="text" name="username" class="form-control" id="username" required>
				</div>

				<div class="form-group col-md-6 required">
					<label for="dob">@lang('contents.dob')</label>
					<input type="date" name="dob" class="form-control" id="dob" required>
				</div>

				<div class="form-group col-md-6 required">
					<label for="gender">@lang('contents.gander')</label>
					<select name="gender" class="form-control" id="gender">
						<option value="Male" selected>@lang('contents.male')</option>
						<option value="Female">@lang('contents.female')</option>
						<option value="Other">@lang('contents.other')</option>
					</select>
				</div>

				<div class="form-group col-md-6 required">
					<label for="password">@lang('contents.password')</label>
					<input type="password" name="password" class="form-control" id="password" required>
				</div>

				<div class="form-group col-md-6 required">
					<label for="confirm-password">@lang('contents.confirmPassword')</label>
					<input type="password" name="password_confirmation" class="form-control" id="confirm-password" required>
				</div>

				<div class="form-group col-md-6 required">
					<label for="thumbnail">@lang('contents.thumbnail') (Media code)</label>
					<input type="text" name="thumbnail" class="form-control" id="thumbnail" required>
				</div>

				<div class="form-group col-md-6 text-right">
					<label>&nbsp;</label>
					<button type="reset" class="btn btn-danger">@lang('contents.reset')</button>
					<button type="submit" class="btn btn-primary">@lang('contents.create_new')</button>
				</div>
            </form>
        </div>
    </div>
</div>
@endsection

