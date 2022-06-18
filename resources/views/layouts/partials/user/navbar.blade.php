<!-- body nav start -->
<nav class="main-nav">
    <ul class="float-left">
        <li class="user-dropdown">
            <a href="#" id="aside-toggle">
                <i class="fa fa-bars" aria-hidden="true"></i>
            </a>
        </li>
        <li class="ml-1">
            <a href="{{ route('pos.create') }}" class="btn btn-success btn-sm">@lang('contents.new_sale')</a>
        </li>

        <li class="ml-1">
            <a href="{{ route('purchase.create') }}" class="btn btn-success btn-sm">@lang('contents.new_purchase')</a>
        </li>

        <li class="ml-1">
            <a href="{{ route('customer.index') }}" class="btn btn-primary btn-sm">@lang('contents.customer')</a>
        </li>

        <li class="ml-1">
            <a href="{{ route('supplierDueManage.index') }}" class="btn btn-primary btn-sm" >@lang('contents.supplier') @lang('contents.due_manage')</a>
        </li>

        <li class="ml-1">
            <a href="{{ route('customerDueManage.index') }}" class="btn btn-primary btn-sm">@lang('contents.customer') @lang('contents.due_manage')</a>
        </li>

        <li class="ml-1">
            <a href="{{ route('cashBook.index') }}" class="btn btn-light btn-sm">@lang('contents.cash_book')</a>
        </li>
    </ul>

    <ul class="float-right">
        <!-- language -->
        <li class="lenguage">
            @php
                $locale = (Config::get('app.locale') == 'en') ? 'bn' : 'en';
            @endphp

            <a href="{{ url('locale/' . $locale) }}" class="language">
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
        <li class="user-dropdown">
            <a href="#" class="menu-button">
                <i class="fa fa-cog" aria-hidden="true"></i>
            </a>

            <ul class="sub-menu">
                <li><a href="{{ route('unit.index') }}">@lang('contents.unit_converter')</a></li>
                <li><a href="{{ route('warehouse.index') }}">@lang('contents.warehouse')</a></li>
                <li><a href="{{ route('category.index') }}">@lang('contents.category')</a></li>
                <li><a href="{{ route('brand.index') }}"><span title="Unit">@lang('contents.brand')</a></li>
                <li><a href="{{ route('product.index') }}">@lang('contents.product')</a></li>
            </ul>
        </li>

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
                        <small>{{ Auth::user()->created_at->format('j F, Y') }}</small>
                    </a>
                </li>

                <li>
                    <a href="{{ route('account.show', Auth::user()->id) }}">
                        <span>@lang('contents.profile')</span>
                        <span class="badge float-right">30%</span>
                    </a>
                </li>

                <li><a href="#">@lang('contents.user_manual')</a></li>

                <li><a href="#">@lang('contents.help')</a></li>

                <li>
                    <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                        @lang('contents.logout')
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>
        </li>
    </ul>
</nav>
<!-- body nav end -->
