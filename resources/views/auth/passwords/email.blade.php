@extends('layouts.auth')

@section('content')
    <!-- login section start -->
    <section class="login-section">
        <div class="cover">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="company-description pr-lg-5">
                            <div>
                                <h2>Whatever CTA's wave purpose important exit element</h2>
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="form-content pl-lg-4">
                            <div>
                                <h2>{{ __('Reset Password') }}</h2>
                                <p>Enter the email address associated with your account and we will sent you instruction for resetting your password.</p>

                                @include('layouts.partials.alert')

                                @if (session('status'))
                                    <div class="alert alert-success" role="alert">
                                        {{ session('status') }}
                                    </div>
                                @endif

                                <form method="POST" action="{{ route('password.email') }}">
                                    @csrf

                                    <div class="form-group">
                                        <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" placeholder="Email address" value="{{ old('email') }}" required>

                                        @if ($errors->has('email'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('email') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="form-group action-area">
                                        <button type="submit" class="btn">{{ __('Send Password Reset Link') }}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- login section end -->
@endsection
