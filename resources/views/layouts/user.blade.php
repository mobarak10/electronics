<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="base-url" content="{{ url('/') }}">
    <link rel="icon" href="{{ asset('public/images/walton.png') }}" type="image/png" sizes="16x16">

    <title>@yield('title') | {{ __(config('app.name', 'Laravel')) }} | User Panel</title>

    <!-- Styles -->
    <link href="{{ asset('public/css/app-user.css') }}" rel="stylesheet">

    {{-- add stylesheet --}}
    @stack('style')
</head>
<body>
    <div id="app">
        <section class="wrapper {{ (isset($aside) AND !$aside) ? 'aside-close' : '' }}" data-menu="{{ $menu }}" data-submenu="{{ $menu . '-' . $submenu }}">
            <!-- aside -->
            @include('layouts.partials.user.aside')

            <!-- right aside -->
            @include('layouts.partials.user.right-aside')

            <!-- body section start -->
            <div class="column body">
                <!-- navbar -->
                @include('layouts.partials.user.navbar')

                <!-- body container start -->
                <div class="body-container">
                    <div class="container-fluid">
                        <div class="row">
                            <!-- body header start -->
                            @unless(isset($header) AND !$header)
                            <header class="body-header">
                                <div class="container">
                                    <div class="header-title">
                                        <h3>@yield('title')</h3>
                                    </div>
                                </div>
                            </header>
                            @endunless
                            <!-- body header end -->

                            <!-- body content start -->
                            <div class="body-content">
                                <div class="col-md-12 mt-2">
                                    @include('layouts.partials.alert')
                                </div>

                                @section('content')
                                @show
                            </div>

                            <!-- footer content Start -->
                            <footer class="main-footer">
                                <strong>Copyright © 2018-{{ date('Y') }} <a href="http://maxsop.com" target="_blank">MaxSOP</a>.</strong> All rights reserved.
                                <div class="Version"><strong>Version</strong> 2.4.13</div>
                            </footer>
                            <!-- footer content end -->
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- overlay layer -->
        <div class="wrapper-background"></div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('public/js/app-user.js') }}"></script>
    <script src="{{ asset('public/js/app.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>

    <!-- custom scripts -->
    @stack('script')
</body>
</html>

