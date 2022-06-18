<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('public/images/walton.png') }}" type="image/png" sizes="16x16">

    <title>Auth | {{ __(config('app.name', 'Laravel')) }}</title>

    <!-- Styles -->
    <link href="{{ asset('public/css/app-user.css') }}" rel="stylesheet">
    <link href="{{ asset('public/css/credential.css') }}" rel="stylesheet">
</head>
<body>
<div id="app">
    <!-- credential nav start -->
    <nav class="fixed-top">
        <div class="navber credential-nav">
            <div class="container">
                <div class="nav-content">
                    <div class="brand">
                        <a href="{{ url('/') }}">
                            {{ __(config('app.name', 'Laravel')) }}
                        </a>
                    </div>

                    <div class="log">
                        @if(Route::current()->getName() != 'admin.login')
                            <span>Don't have an account?</span>

{{--                            @if (Route::has('register'))--}}
{{--                                <a href="{{ route('register') }}">{{ __('contents.register') }}</a>--}}
{{--                            @endif--}}
                        @endif

                        @php
                            $locale = (Config::get('app.locale') == 'en') ? 'bn' : 'en';
                        @endphp

                        <a href="{{ url('locale/' . $locale) }}">{{ __('contents.' . $locale) }}</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    <!-- credential nav end -->

    <main style="background-color: #949398FF;">
        @yield('content')
    </main>
</div>
</body>
</html>
