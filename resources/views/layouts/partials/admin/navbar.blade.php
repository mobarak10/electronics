<!-- body nav start -->
<nav class="main-nav">
    <ul class="float-left">
        <li class="user-dropdown">
            <a href="#" id="aside-toggle">
                <i class="fa fa-bars" aria-hidden="true"></i>
            </a>
        </li>
    </ul>

    <ul class="float-right">
        <!-- language -->
        <li class="lenguage">
            @php
                $locale = (Config::get('app.locale') == 'en') ? 'bn' : 'en';
            @endphp

            <a href="{{ url('locale/' . $locale) }}" class="language">
                {{-- {{ __('contents.' . $locale) }} --}}
                <i class="fa fa-language" aria-hidden="true"></i>
            </a>
        </li>

        <!-- notifications -->
{{--        <li class="user-dropdown">--}}
{{--            <a href="#" class="menu-button">--}}
{{--                <i class="fa fa-bell" aria-hidden="true"></i>--}}
{{--            </a>--}}

{{--            <ul class="sub-menu">--}}
{{--                <li class="head">--}}
{{--                    <a href="#">--}}
{{--                        <h6>You have 2 notifications</h6>--}}
{{--                    </a>--}}
{{--                </li>--}}
{{--                <li>--}}
{{--                    <a href="#">--}}
{{--                        <span>Awesome aminmate.css</span>--}}
{{--                        <small>10 minit ago</small>--}}
{{--                    </a>--}}
{{--                </li>--}}
{{--                <li>--}}
{{--                    <a href="#">--}}
{{--                        <span>Awesome aminmate.css</span>--}}
{{--                        <small>10 minit ago</small>--}}
{{--                    </a>--}}
{{--                </li>--}}
{{--            </ul>--}}
{{--        </li>--}}

        <!-- settings -->
{{--        <li class="user-dropdown">--}}
{{--            <a href="#" class="settings">--}}
{{--                <i class="fa fa-wrench" aria-hidden="true"></i>--}}
{{--            </a>--}}
{{--        </li>--}}
        <!-- profile -->
        <li class="user-dropdown">
            <a href="#" class="menu-button">
                <img src="{{ asset('public/images/avatars/user.png') }}" alt="Avatars">
            </a>

            <ul class="sub-menu">
                <li class="head">
                    <a href="#">
                        <h6>{{ Auth::user()->name }}</h6>
                        <small>{{ Auth::user()->email }}</small>
                        <small>{{ Auth::user()->created_at->toFormattedDateString() }}</small>
                    </a>
                </li>
                <li><a href="{{ route('admin.account.index') }}">Admins</a></li>
                <li><a href="{{ route('admin.employee.index') }}">Employees</a></li>
                <li>
                    <a href="{{ route('admin.account.show', Auth::user()->id) }}">
                        <span>Profile</span>
                        <span class="badge float-right">30%</span>
                    </a>
                </li>

                <li><a href="#">Help</a></li>
                <li>
                    <a href="{{ route('admin.logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                        {{ __('Logout') }}
                    </a>

                    <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>
        </li>
    </ul>
</nav>
<!-- body nav end -->
