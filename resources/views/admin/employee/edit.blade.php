@extends('layouts.admin')

@section('title', __('contents.employee'))

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
                    <div class="row">

                        <!-- basic information section start -->
                        <div class="col-md-12">
                            <form action="{{ route('admin.employee.update', $user->id) }}" method="POST">
                                @csrf
                                @method('PATCH')

                                <input type="hidden" name="section" value="basic">
                                <input type="hidden" name="account_type" value="general">

                                <div class="row">
                                    <div class="col-md-12">
                                        <strong class="d-block my-3">@lang('contents.basic_section')<hr></strong>

                                        <div class="form-row">
                                            <div class="form-group col-md-6 required">
                                                <label for="name">@lang('contents.full_name')</label>
                                                <input type="text" name="name" value="{{ (old('name')) ? old('name') : $user->name }}" class="form-control" id="name" required>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label for="username">@lang('contents.username')</label>
                                                <input type="text" value="{{ $user->username }}" class="form-control" id="username" readonly>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label for="phone">@lang('contents.phone')</label>
                                                <input type="text" name="phone" value="{{ (old('phone')) ? old('phone') : $user->phone }}" class="form-control" id="phone">
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label for="email-address">@lang('contents.email')</label>
                                                <input type="email" name="email" value="{{ (old('email')) ? old('email') : $user->email }}" class="form-control" id="email-address">
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label for="dob">@lang('contents.dob')</label>
                                                <input type="date" name="dob" value="{{ $user->getMetaValue('dob') }}" class="form-control" id="dob">
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label for="thumbnail">@lang('contents.thumbnail')</label>
                                                <input type="text" name="thumbnail" value="{{ $user->thumbnail }}" class="form-control" id="thumbnail">
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label for="father_name">Father's Name</label>
                                                <input type="text" name="father_name" value="{{ $user->getMetaValue('father_name') }}" class="form-control" id="father_name">
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label for="mother_name">Mother Name</label>
                                                <input type="text" name="mother_name" value="{{ $user->getMetaValue('mother_name') }}" class="form-control" id="mother_name">
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label for="contact_person_number">Contact Person Number</label>
                                                <input type="text" name="contact_person_number" value="{{ $user->getMetaValue('contact_person_number') }}" class="form-control" id="contact_person_number">
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label for="nid_number">NID Number</label>
                                                <input type="text" name="nid_number" value="{{ $user->getMetaValue('nid_number') }}" class="form-control" id="nid_number">
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label for="present_address">Present Address</label>
                                                <textarea name="present_address" class="form-control" id="present_address">{{ $user->getMetaValue('present_address') }}</textarea>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label for="permanent_address">Present Address</label>
                                                <textarea name="permanent_address" class="form-control" id="permanent_address">{{ $user->getMetaValue('permanent_address') }}</textarea>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label for="business_id">@lang('contents.select_business')</label>
                                                <select name="business_id" class="form-control" id="business_id">
                                                    <option selected disabled>Choose one</option>
                                                    @foreach($businesses as $business)
                                                        <option value="{{ $business->id }}" {{ ($business->id == $user->business_id) ? 'selected' : '' }}>{{ $business->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-group col-md-6 required">
                                                <label for="basic_salary">Basic Salary</label>
                                                <input type="text" name="basic_salary" value="{{ $user->getMetaValue('basic_salary') }}" class="form-control" id="basic_salary" />
                                            </div>

                                            <div class="form-group col-md-12">
                                                <div class="form-check">
                                                    <input type="checkbox" name="status" value="1" {{ (old('status') == 1 || $user->status == 1) ? 'checked' : '' }} class="form-check-input" id="status">
                                                    <label class="form-check-label" for="status">@lang('contents.enable_account')</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- role section start -->
                                    <div class="col-md-6">
                                        <strong class="d-block my-3">Role section<hr></strong>

                                        <div class="form-row">
                                            <div class="form-group">
                                                @foreach ($roles as $role)
                                                    <div class="form-check">
                                                        <input type="checkbox" name="roles[]" value="{{ $role->id }}" id="{{ $role->slug }}" class="form-check-input" {{ (in_array($role->id, $userRoles)) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="{{ $role->slug }}">
                                                            <span class="d-block">{{ $role->name }}</span>
                                                            <small>{{ $role->description }}</small>
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    <!-- role section end -->

                                    <div class="form-group col-md-12 text-right">
                                        <button type="reset" class="btn btn-danger">@lang('contents.reset')</button>
                                        <button type="submit" class="btn btn-primary">@lang('contents.save')</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- basic information section end -->

                        <!-- change password section start -->
                        <div class="col-md-12">
                            <strong class="d-block my-3">@lang('contents.password_section')<hr></strong>

                            <form action="{{ route('admin.employee.update', $user->id) }}" method="POST">
                                @csrf
                                @method('PATCH')

                                <input type="hidden" name="section" value="password">

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="password">@lang('contents.password')</label>
                                        <input type="password" name="password" class="form-control" id="password">
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="password_confirmation">@lang('contents.confirmPassword')</label>
                                        <input type="password" name="password_confirmation" class="form-control" id="password_confirmation">
                                    </div>

                                    <div class="form-group col-md-12 text-right">
                                        <button type="reset" class="btn btn-danger">@lang('contents.reset')</button>
                                        <button type="submit" class="btn btn-primary">@lang('contents.save')</button>
                                    </div>
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
