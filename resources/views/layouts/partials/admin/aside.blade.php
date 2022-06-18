<!-- aside content start -->
<aside class="column aside">
    <div class="brand">
        <span title="Company name">{{ config('app.name') }}</span>
        <a href="#" id="aside-close">
            <i class="fa fa-times" aria-hidden="true"></i>
        </a>
    </div>

    <!-- aside nav start -->
    <nav class="aside-nav overlay-scrollbar">
        <h6>@lang('contents.basic')</h6>
        <ul>
            <!-- dashboard -->
            <li id="dashboard">
                <a href="{{ route('admin.home') }}">
                    <i class="fa fa-tachometer" aria-hidden="true"></i>
                    <span title="Dashboard">@lang('contents.dashboard')</span>
                </a>
            </li>

            <!-- media -->
            <li id="media" class="dropdown">
                <a href="#">
                    <i class="fa fa-camera" aria-hidden="true"></i>
                    <span title="Media">@lang('contents.media')</span>
                    <span class="float-right">
                        <i class="fa fa-angle-right right" aria-hidden="true"></i>
                        <i class="fa fa-angle-down down" aria-hidden="true"></i>
                    </span>
                </a>

                <ul>
                    <li id="media-list"><a href="{{ route('admin.media.index') }}" title="Images">@lang('contents.images')</a></li>
                    {{-- <li id="media-others"><a href="{{ route('admin.media.index') }}" title="Others">Others</a></li> --}}
                    <li id="media-add"><a href="{{ route('admin.media.create') }}" title="New upload">@lang('contents.new_upload')</a></li>
                </ul>
            </li>
        </ul>

        <h6>@lang('contents.app_modules')</h6>
        <ul>
            {{-- report --}}
            <li id="report" class="dropdown">
                <a href="#">
                    <i class="fa fa-bar-chart" aria-hidden="true"></i>
                    <span title="Reports">@lang('contents.reports')</span>
                    <span class="float-right">
                        <i class="fa fa-angle-right right" aria-hidden="true"></i>
                        <i class="fa fa-angle-down down" aria-hidden="true"></i>
                    </span>
                </a>

                 <ul>
                    {{-- total stock report --}}
                    <li id="current-stock"><a href="{{ route('admin.currentStockReport.currentStock') }}" title="avaible stock">@lang('contents.current_stock')</a></li>
                    {{-- damage report --}}
                    <li id="damage-stock"><a href="{{ route('admin.damageStockReport.damageStock') }}" title="Damage Stock">@lang('contents.damage_stock')</a></li>
                    {{-- sale report --}}
                    <li id="sale"><a href="{{ route('admin.saleReport') }}" title="sale">@lang('contents.sale')</a></li>
                     {{-- sale return report --}}
                     <li id="sale-return"><a href="{{ route('admin.saleReturnReport') }}" title="sale">@lang('contents.sale_return')</a></li>
                     {{-- purchase report --}}
                     <li id="purchase"><a href="{{ route('admin.purchaseReport') }}" title="sale">@lang('contents.purchase')</a></li>
                    {{-- expenditure report --}}
                    <li id="expenditure"><a href="{{ route('admin.expenditureReport.index') }}" title="Expenditure">@lang('contents.expenditure')</a></li>
                    {{-- daily report --}}
                    <li id="daily-report"><a href="{{ route('admin.dailyReport.index') }}" title="DailyReport">@lang('contents.daily_report')</a></li>
                     {{-- loss profit report --}}
                     <li id="loss-profit-report"><a href="{{ route('admin.profitLossReport') }}" title="Loss-Profit">Profit & Loss</a></li>
                </ul>
            </li>

            {{-- add business --}}
            <li id="business">
                <a href="{{ route('admin.business.index') }}">
                    <i class="fa fa-building-o" aria-hidden="true"></i>
                    <span title="Business Add">@lang('contents.business')</span>
                </a>
            </li>

            {{-- payroll --}}
             <li id="payroll" class="dropdown">
                <a href="#">
                    <i class="fa fa-credit-card" aria-hidden="true"></i>
                    <span title="Payroll">Payroll</span>
                    <span class="float-right">
                    <i class="fa fa-angle-right right" aria-hidden="true"></i>
                    <i class="fa fa-angle-down down" aria-hidden="true"></i>
                </span>
                </a>
                <ul>
                    <li id="advanced-salary"><a href="{{ route('admin.advancedSalary.index') }}" title="Advanced">Advanced Salary</a></li>
                    <li id="salary"><a href="{{ route('admin.salary.index') }}" title="Salary">Salary</a></li>
                </ul>
            </li>
        </ul>

        <h6>@lang('contents.user_stuff')</h6>
        <ul>
            <!-- permission -->
{{--            <li id="permission">--}}
{{--                <a href="{{ route('admin.permission.index') }}">--}}
{{--                    <i class="fa fa-unlock-alt" aria-hidden="true"></i>--}}
{{--                    <span title="User permission">@lang('contents.permission')</span>--}}
{{--                </a>--}}
{{--            </li>--}}

{{--            <!-- role -->--}}
{{--            <li id="role">--}}
{{--                <a href="{{ route('admin.role.index') }}">--}}
{{--                    <i class="fa fa-users" aria-hidden="true"></i>--}}
{{--                    <span title="User roles">@lang('contents.roles')</span>--}}
{{--                </a>--}}
{{--            </li>--}}

            <!-- profile -->
            <li id="profile">
                <a href="{{ route('admin.account.index') }}">
                    <i class="fa fa-user" aria-hidden="true"></i>
                    <span title="Profile">@lang('contents.profile')</span>
                </a>
            </li>
        </ul>
    </nav>
</aside>
<!-- aside content end -->
