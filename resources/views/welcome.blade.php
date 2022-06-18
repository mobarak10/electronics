@extends('layouts.app')

@section('content')
    <section class="main-page">
        <div class="haat-custom-style" style="position: absolute; top: 25%;">
            <img src="{{ asset('public/images/walton.png') }}" style="width: 100px;">
            <span style="font-size: 40px; font-weight: 900; position: relative; top: 12px;">{{ __(config('app.name', 'Laravel')) }}</span>
        </div>
        @if (Route::has('login'))
            @auth
                <a href="{{ url('/home') }}" class="item-box ml-1">
                    <div>
                        <img src="{{ asset('public/images/home.png') }}" alt="Home">
                        <h4>@lang('contents.home')</h4>
                        {{-- <small>Muffin lemon drops chocolate carrot cake chocolate bar sweet roll.</small> --}}
                    </div>
                </a>
            @else
                <a href="{{ route('login') }}" class="item-box">
                    <div>
                        <img src="{{ asset('public/images/pos.png') }}" alt="Point of sale">
                        <h4>@lang('contents.pos')</h4>
                        {{-- <small>Muffin lemon drops chocolate carrot cake chocolate bar sweet roll.</small> --}}
                    </div>
                </a>

{{--                @if (Route::has('register'))--}}
{{--                    <a href="{{ route('register') }}" class="item-box ml-1">--}}
{{--                        <div>--}}
{{--                            <img src="{{ asset('public/images/register.png') }}" alt="Register">--}}
{{--                            <h4>@lang('contents.register')</h4>--}}
{{--                            --}}{{-- <small>Muffin lemon drops chocolate carrot cake chocolate bar sweet roll.</small> --}}
{{--                        </div>--}}
{{--                    </a>--}}
{{--                @endif--}}
            @endauth
        @endif

        <a href="{{ route('admin.login') }}" target="_blank" class="item-box ml-1">
            <div>
                <img src="{{ asset('public/images/settings.png') }}" alt="Settings">
                <h4>@lang('contents.administrator')</h4>
                {{-- <small>Muffin lemon drops chocolate carrot cake chocolate bar sweet roll.</small> --}}
            </div>
        </a>
    </section>
@endsection
