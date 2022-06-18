@extends('layouts.admin')

@section('title', $title)

@section('content')
<!-- Main Content -->
<div class="container">
    <div class="row">
        <div class="col-md-12 pb-3">
            <div class="card">
                <div class="card-header">
					<h5 class="m-0">@lang('contents.update_profile')</h5>
                </div>

                <div class="card-body">
                    <form action="{{ route('admin.account.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <input type="hidden" name="section" value="basic">

                        <div class="form-group row">
                            <label for="name" class="col-sm-3 col-form-label">@lang('contents.full_name')</label>
                            <div class="col-sm-9">
                                <input type="text" name="name" value="{{ (old('name')) ? old('name') : $user->name }}" class="form-control" id="name">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="phone" class="col-sm-3 col-form-label">@lang('contents.phoneNumber')</label>
                            <div class="col-sm-9">
                                <input type="text" name="phone" value="{{ (old('phone')) ? old('phone') : $user->phone }}" class="form-control" id="phone">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email-address" class="col-sm-3 col-form-label">@lang('contents.email')</label>
                            <div class="col-sm-9">
                                <input type="email" name="email" value="{{ (old('email')) ? old('email') : $user->email }}" class="form-control" id="email-address">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="username" class="col-sm-3 col-form-label">@lang('contents.username')</label>
                            <div class="col-sm-9">
                                <input type="text" value="{{ $user->username }}" class="form-control" id="username" readonly>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="dob" class="col-sm-3 col-form-label">@lang('contents.dob')</label>
                            <div class="col-sm-9">
                                <input type="date" name="dob" value="{{ date('Y-m-d', strtotime($user->getMetaValue('dob'))) }}" class="form-control" id="dob">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="address" class="col-sm-3 col-form-label">@lang('contents.address')</label>
                            <div class="col-sm-9">
                                <textarea name="address" class="form-control" id="address">{{ $user->getMetaValue('address') }}</textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="thumbnail" class="col-sm-3 col-form-label">@lang('contents.thumbnail')</label>
                            <div class="col-sm-9">
                                <input type="text" name="thumbnail" value="{{ $user->thumbnail }}" class="form-control" id="thumbnail">
                            </div>
                        </div>

                        {{-- <div class="form-group col-md-6">
                            <label for="password">Password</label>
                            <input type="password" name="password" class="form-control" id="password">
                        </div>

                        <div class="form-group col-md-6">
                            <label for="password_confirmation">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-control" id="password_confirmation">
                        </div> --}}

                        <div class="form-group">
                            <div class="row">
                                <label class="col-form-label col-sm-3 pt-0">@lang('contents.gander')</label>

                                <div class="col-sm-9">
                                    <div class="form-check">
                                    <input class="form-check-input" type="radio" name="gender" id="male" value="Male" {{ ($user->getMetaValue('gender') == 'Male') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="male">@lang('contents.male')</label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="gender" id="female" value="Female" {{ ($user->getMetaValue('gender') == 'Female') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="female">@lang('contents.female')</label>
                                    </div>

                                    <div class="form-check disabled">
                                        <input class="form-check-input" type="radio" name="gender" id="other" value="Other" {{ ($user->getMetaValue('gender') == 'Other') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="other">@lang('contents.other')</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="form-check">
                                <input type="checkbox" name="status" value="1" {{ (old('status') == 1 || $user->status == 1) ? 'checked' : '' }} class="form-check-input" id="status">
                                <label class="form-check-label" for="status">@lang('contents.enable_account') </label>
                            </div>
                        </div>

                        <div class="form-group text-right">
                            <button type="reset" class="btn btn-danger">@lang('contents.reset')</button>
                            <button type="submit" class="btn btn-primary">@lang('contents.save_changes')</button>
                        </div>
                    </form>

                    <!-- change password section start -->
                    <div class="col-md-12">
                        <strong class="d-block my-3">@lang('contents.password_section')<hr></strong>

                        <form action="{{ route('admin.account.update', $user->id) }}" method="POST">
                            @csrf
                            @method('PATCH')

                            <input type="hidden" name="section" value="password">

                            <div class="form-row">
                                <div class="form-group col-md-6 required">
                                    <label for="password">@lang('contents.password')</label>
                                    <input type="password" name="password" class="form-control" id="password" required>
                                </div>

                                <div class="form-group col-md-6 required">
                                    <label for="password_confirmation">@lang('contents.confirmPassword')</label>
                                    <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" required>
                                </div>

                                <div class="form-group col-md-12 text-right">
                                    <button type="reset" class="btn btn-danger">@lang('contents.reset')</button>
                                    <button type="submit" class="btn btn-primary">@lang('contents.save_changes')</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- change password section end -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
