@extends('layouts.admin')

@section('title', __('contents.employee'))

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 py-3">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="m-0">@lang('contents.add_employee')</h5>

                    <div class="btn-group" role="group" aria-label="Action area">
                        <a href="{{ route('admin.employee.index') }}" class="btn btn-primary" title="All Bank">
                            <i class="fa fa-list" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <form action="{{ route('admin.employee.store') }}" method="post" enctype="multipart/form-data">
                        @csrf

                        <h5>Basic Section</h5>
                        <div class="form-row">
                            <div class="form-group col-md-6 required">
                                <label for="name">@lang('contents.name')</label>
                                <input type="text" class="form-control" value="{{ old('name') }}" id="name" name="name" placeholder="Enter employee name" required>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="user_name">@lang('contents.username')</label>
                                <input type="text" class="form-control" name="user_name" placeholder="enter user name">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="phone_no">@lang('contents.phoneNumber')</label>
                                <input type="text" class="form-control" value="{{ old('phone_no') }}" id="phone_no" name="phone_no" placeholder="Enter employee phone no">
                            </div>

                            <div class="form-group col-md-6">
                                <label for="email">@lang('contents.email')</label>
                                <input type="email" value="{{ old('email') }}" class="form-control" name="email" placeholder="enter email">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="dob">@lang('contents.dob')</label>
                                <input type="date" value="{{ old('dob') }}" class="form-control" name="dob" placeholder="enter date of birth">
                            </div>

                            <div class="form-group col-md-6">
                                <label for="thumbnail">Photo</label>
                                <input type="text" class="form-control" value="{{ old('thumbnail') }}" name="thumbnail" placeholder="enter image code">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="father_name">Father's Name</label>
                                <input type="text" value="{{ old('father_name') }}" class="form-control" name="father_name" placeholder="enter father name">
                            </div>

                            <div class="form-group col-md-6">
                                <label for="mother_name">Mother's Name</label>
                                <input type="text" value="{{ old('mother_name') }}" class="form-control" name="mother_name" placeholder="enter mother name">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="contact_person_number">Contact Person Number</label>
                                <input type="text" class="form-control" value="{{ old('contact_person_number') }}" name="contact_person_number" placeholder="enter contact person number">
                            </div>

                            <div class="form-group col-md-6">
                                <label for="nid_number">NID Number</label>
                                <input type="text" value="{{ old('nid_number') }}" class="form-control" name="nid_number" placeholder="enter NID number">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="present_address">Present Address</label>
                                <textarea name="present_address" class="form-control" placeholder="enter present address" id="present_address">{{ old('present_address') }}</textarea>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="permanent_address">Permanent Adders</label>
                                <textarea name="permanent_address" class="form-control" placeholder="enter permanent address" id="permanent_address">{{ old('permanent_address') }}</textarea>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-12 required">
                                <label for="business">@lang('contents.select_business')</label>
                                <select name="business_id" class="form-control" id="business" required>
                                    <option selected disabled>Choose one</option>
                                    @foreach($businesses as $business)
                                        <option value="{{ $business->id }}">{{ $business->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <hr>
                        <h5>Salary Section</h5>
                        <div class="form-row">
                            <div class="form-group col-md-6 required">
                                <label for="basic_salary">Basic Salary</label>
                                <input type="number" value="{{ old('basic_salary') }}" class="form-control" required id="basic_salary" placeholder="Enter employee basic salary" name="basic_salary">
                            </div>

                            <div class="form-group col-md-6">
                                <label for="home_allowance">Home Allowance</label>
                                <input type="number" value="0.00" class="form-control" id="home_allowance" placeholder="Enter employee home allowance" name="home_allowance">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="medical_allowance">Medical Allowance</label>
                                <input type="number" value="0.00" class="form-control" id="medical_allowance" placeholder="Enter employee medical allowance" name="medical_allowance">
                            </div>

                            <div class="form-group col-md-6">
                                <label for="transport_allowance">Transport Allowance</label>
                                <input type="number" value="0.00" class="form-control" id="transport_allowance" placeholder="Enter employee transport allowance" name="transport_allowance">
                            </div>
                        </div>

                        <hr>
                        <h5>Password Section</h5>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>@lang('contents.password')</label>
                                <input type="password" class="form-control" name="password" placeholder="enter password">
                            </div>

                            <div class="form-group col-md-6">
                                <label>@lang('contents.confirmPassword')</label>
                                <input type="password" class="form-control" name="password_confirmation" placeholder="Retype password">
                            </div>
                        </div>

                        <div class="text-right">
                            <button type="reset" class="btn btn-danger">@lang('contents.reset')</button>
                            <button type="submit" class="btn btn-primary">@lang('contents.save')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- main-panel ends -->
@endsection

