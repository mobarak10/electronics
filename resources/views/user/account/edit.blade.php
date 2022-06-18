@extends('layouts.user')

@section('title', $title)

@section('content')
<!-- Main Content -->
<div class="container">
    <div class="row">
        <div class="col-md-12 pb-3">

            <!-- basic data -->
            <div class="card mb-3">
                <div class="card-header">
					<h5 class="m-0">Update profile</h5>
                </div>
                
                <div class="card-body">
                    <form action="{{ route('account.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <input type="hidden" name="section" value="basic">

                        <div class="form-group row">
                            <label for="name" class="col-sm-3 col-form-label">Fullname</label>
                            <div class="col-sm-9">
                                <input type="text" name="name" value="{{ (old('name')) ? old('name') : $user->name }}" class="form-control" id="name">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="phone" class="col-sm-3 col-form-label">Phone</label>
                            <div class="col-sm-9">
                                <input type="text" name="phone" value="{{ (old('phone')) ? old('phone') : $user->phone }}" class="form-control" id="phone">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email-address" class="col-sm-3 col-form-label">Email</label>
                            <div class="col-sm-9">
                                <input type="email" name="email" value="{{ (old('email')) ? old('email') : $user->email }}" class="form-control" id="email-address">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="username" class="col-sm-3 col-form-label">Username</label>
                            <div class="col-sm-9">
                                <input type="text" value="{{ $user->username }}" class="form-control" id="username" readonly>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="dob" class="col-sm-3 col-form-label">Date of birth</label>
                            <div class="col-sm-9">
                                <input type="date" name="dob" value="{{ date('Y-m-d', strtotime($user->getMetaValue('dob'))) }}" class="form-control" id="dob">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="address" class="col-sm-3 col-form-label">Address</label>
                            <div class="col-sm-9">
                                <textarea name="address" class="form-control" id="address">{{ $user->getMetaValue('address') }}</textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="thumbnail" class="col-sm-3 col-form-label">Thumbnail</label>
                            <div class="col-sm-9">
                                <input type="text" name="thumbnail" value="{{ $user->thumbnail }}" class="form-control" id="thumbnail">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <label class="col-form-label col-sm-3 pt-0">Gender</label>

                                <div class="col-sm-9">
                                    <div class="form-check">
                                    <input class="form-check-input" type="radio" name="gender" id="male" value="Male" {{ ($user->getMetaValue('gender') == 'Male') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="male">Male</label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="gender" id="female" value="Female" {{ ($user->getMetaValue('gender') == 'Female') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="female">Female</label>
                                    </div>

                                    <div class="form-check disabled">
                                        <input class="form-check-input" type="radio" name="gender" id="other" value="Other" {{ ($user->getMetaValue('gender') == 'Other') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="other">Other</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="form-check">
                                <input type="checkbox" name="status" value="1" {{ (old('status') == 1 || $user->status == 1) ? 'checked' : '' }} class="form-check-input" id="status">
                                <label class="form-check-label" for="status">enable your account </label>
                            </div>
                        </div>

                        <div class="form-group text-right">
                            <button type="reset" class="btn btn-danger">Reset</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- basic data end -->

            <!-- password change -->
            <div class="card">
                <div class="card-header">
                    <h5 class="m-0">Update profile</h5>
                </div>
                
                <div class="card-body">
                    <form action="{{ route('account.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <input type="hidden" name="section" value="password">

                        <div class="form-group row required">
                            <label for="password" class="col-sm-3 col-form-label">Password</label>
                            <div class="col-sm-9">
                                <input type="password" name="password" class="form-control" id="password" required>
                            </div>
                        </div>

                        <div class="form-group row required">
                            <label for="password_confirmation" class="col-sm-3 col-form-label">Confirm Password</label>
                            <div class="col-sm-9">
                                <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" required>
                            </div>
                        </div>

                         <div class="text-right">
                            <button type="reset" class="btn btn-danger">@lang('contents.reset')</button>
                            <button type="submit" class="btn btn-primary">@lang('contents.save')</button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- password change end -->

        </div>
    </div>
</div>
@endsection
