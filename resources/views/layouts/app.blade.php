<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="base-url" content="{{ url('/') }}">
    <link rel="icon" href="{{ asset('public/images/sharif.png') }}" type="image/png" sizes="16x16">
    <title>Home | {{ __(config('app.name', 'Laravel')) }} </title>

    <!-- Styles -->
    <link href="{{ asset('public/css/app-user.css') }}" rel="stylesheet">
    <!-- Scripts -->
    <script src="{{ asset('public/js/app-user.js') }}" defer></script>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
            <div class="container">
                <img src="{{ asset('public/images/sharif.png') }}" style="width: 50px; padding-right: 10px">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ __(config('app.name', 'Laravel')) }}
                </a>

                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Localization -->
                        <li class="nav-item">
                            @php
                                $locale = (Config::get('app.locale') == 'en') ? 'bn' : 'en';
                            @endphp

                            <a class="nav-link" href="{{ url('locale/' . $locale) }}">{{ __('contents.' . $locale) }}</a>
                        </li>

                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('contents.login') }}</a>
                            </li>

{{--                            <li class="nav-item">--}}
{{--                                @if (Route::has('register'))--}}
{{--                                    <a class="nav-link" href="{{ route('register') }}">{{ __('contents.register') }}</a>--}}
{{--                                @endif--}}
{{--                            </li>--}}
                        @else
                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('api-token') }}">@lang('contents.passport')</a>
                            </li>

                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main>
            <div class="container">
                @include('layouts.partials.alert')
            </div>

            @yield('content')

        </main>
    </div>
</body>
</html>
