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
                <a href="{{ route('home') }}">
                    <i class="fa fa-tachometer" aria-hidden="true"></i>
                    <span title="Dashboard">@lang('contents.dashboard')</span>
                </a>
            </li>

            <!-- purchase -->
            <li id="purchase" class="dropdown">
                <a href="#">
                    <i class="fa fa-cart-plus" aria-hidden="true"></i>
                    <span title="Company">@lang('contents.purchase')</span>
                    <span class="float-right">
                        <i class="fa fa-angle-right right" aria-hidden="true"></i>
                        <i class="fa fa-angle-down down" aria-hidden="true"></i>
                    </span>
                </a>

                <ul>
                    <li id="list"><a href="{{ route('purchase.index') }}"
                            title="All Purchases">@lang('contents.all_purchases')</a></li>
                    <li id="add"><a href="{{ route('purchase.create') }}"
                            title="Add new Purchase">@lang('contents.new_purchase')</a></li>
                </ul>
            </li>

            <!-- purchase return start --->
            <li id="sale-return" class="dropdown">
                <a href="#">
                    <i class="fa fa-undo" aria-hidden="true"></i>
                    <span title="Purchase Return">@lang('contents.purchase_return')</span>
                    <span class="float-right">
                        <i class="fa fa-angle-right right" aria-hidden="true"></i>
                        <i class="fa fa-angle-down down" aria-hidden="true"></i>
                    </span>
                </a>

                <ul>
                    <li id="list">
                        <a href="{{ route('purchase-return.index') }}">
                            <span title="Sale list">@lang('contents.all') @lang('contents.return')</span>
                        </a>
                    </li>

                    <li id="add">
                        <a href="{{ route('purchase-return.create') }}">
                            <span title="New Sale">@lang('contents.new') @lang('contents.return')</span>
                        </a>
                    </li>
                </ul>
            </li>
            <!-- purchase return end --->

            <!-- sale -->
            <li id="pos" class="dropdown">
                <a href="#">
                    <i class="fa fa-shopping-cart"></i>
                    <span title="Point of Sale">@lang('contents.sale')</span>
                    <span class="float-right">
                        <i class="fa fa-angle-right right" aria-hidden="true"></i>
                        <i class="fa fa-angle-down down" aria-hidden="true"></i>
                    </span>
                </a>

                <ul>
                    <li id="pos-list">
                        <a href="{{ route('pos.index') }}">
                            <span title="Sale list">@lang('contents.all_sales')</span>
                        </a>
                    </li>

                    <li id="pos-add">
                        <a href="{{ route('pos.create') }}">
                            <span title="New Sale">@lang('contents.new_sale')</span>
                        </a>
                    </li>
                </ul>
            </li>

            {{--hire sale start--}}
            <li id="hire-sale" class="dropdown">
                <a href="#">
                    <i class="fa fa-shopping-cart"></i>
                    <span title="Point of Sale">@lang('contents.hire_sale')</span>
                    <span class="float-right">
                        <i class="fa fa-angle-right right" aria-hidden="true"></i>
                        <i class="fa fa-angle-down down" aria-hidden="true"></i>
                    </span>
                </a>

                <ul>
                    <li id="list">
                        <a href="{{ route('hire-sale.index') }}">
                            <span title="Pre order list">@lang('contents.all') @lang('contents.hire_sale')</span>
                        </a>
                    </li>

                    <li id="add">
                        <a href="{{ route('hire-sale.create') }}">
                            <span title="New pre order">@lang('contents.new') @lang('contents.hire_sale')</span>
                        </a>
                    </li>
                </ul>
            </li>
            <!-- hire sale end -->

            <!-- installment collection start -->
            <li id="installment-collection" class="dropdown">
                <a href="#">
                    <i class="fa fa-address-card" aria-hidden="true"></i>
                    <span title="Company">Installment Collection</span>
                    <span class="float-right">
                        <i class="fa fa-angle-right right" aria-hidden="true"></i>
                        <i class="fa fa-angle-down down" aria-hidden="true"></i>
                    </span>
                </a>

                <ul>
                    <li id="all"><a href="{{ route('installmentCollection.index') }}" title="Records">All Collection</a></li>
                    <li id="new"><a href="{{ route('installmentCollection.create') }}" title="Create new">New Collection</a></li>
                </ul>
            </li>
            <!-- installment collection end -->

            <!-- sale return start --->
            <li id="sale-return" class="dropdown">
                <a href="#">
                    <i class="fa fa-undo" aria-hidden="true"></i>
                    <span title="Sale Return">@lang('contents.sale_return')</span>
                    <span class="float-right">
                        <i class="fa fa-angle-right right" aria-hidden="true"></i>
                        <i class="fa fa-angle-down down" aria-hidden="true"></i>
                    </span>
                </a>

                <ul>
                    <li id="list">
                        <a href="{{ route('saleReturn.index') }}">
                            <span title="Sale list">@lang('contents.all') @lang('contents.return')</span>
                        </a>
                    </li>

                    <li id="add">
                        <a href="{{ route('saleReturn.create') }}">
                            <span title="New Sale">@lang('contents.new') @lang('contents.return')</span>
                        </a>
                    </li>
                </ul>
            </li>
            <!-- sale return end --->

            <!-- due management -->
            <li id="manage-due" class="dropdown">
                <a href="#">
                    <i class="fa fa-credit-card" aria-hidden="true"></i>
                    <span title="Due collection">@lang('contents.due_manage')</span>
                    <span class="float-right">
                        <i class="fa fa-angle-right right" aria-hidden="true"></i>
                        <i class="fa fa-angle-down down" aria-hidden="true"></i>
                    </span>
                </a>

                <ul>
                    <li id="supplier"><a href="{{ route('supplierDueManage.index') }}">@lang('contents.supplier')</a>
                    </li>
                    <li id="customer"><a href="{{ route('customerDueManage.index') }}">@lang('contents.customer')</a>
                    </li>
                    {{-- <li id="retail"><a href="{{ route('retailDueCollection.index') }}">Retail</a></li>--}}
                </ul>
            </li>

            <!-- transaction -->
{{--            <li id="transaction" class="dropdown">--}}
{{--                <a href="#">--}}
{{--                    <i class="fa fa-exchange" aria-hidden="true"></i>--}}
{{--                    <span title="Balance Transaction">@lang('contents.transaction')</span>--}}
{{--                    <span class="float-right">--}}
{{--                        <i class="fa fa-angle-right right" aria-hidden="true"></i>--}}
{{--                        <i class="fa fa-angle-down down" aria-hidden="true"></i>--}}
{{--                    </span>--}}
{{--                </a>--}}

{{--                <ul>--}}
{{--                    <li id="list"><a href="{{ route('transaction.index') }}">@lang('contents.all')--}}
{{--                            @lang('contents.transaction')</a></li>--}}
{{--                    <li id="add"><a href="{{ route('transaction.create') }}">@lang('contents.new')--}}
{{--                            @lang('contents.transaction')</a></li>--}}
{{--                </ul>--}}
{{--            </li>--}}

            <!-- product stock -->
            <li id="stock" class="dropdown">
                <a href="#">
                    <i class="fa fa-archive" aria-hidden="true"></i>
                    <span title="Product stock">@lang('contents.product_stock')</span>
                    <span class="float-right">
                        <i class="fa fa-angle-right right" aria-hidden="true"></i>
                        <i class="fa fa-angle-down down" aria-hidden="true"></i>
                    </span>
                </a>

                <ul>
                    <li id="current-stock"><a href="{{ route('stock.index') }}"
                            title="Avaible stock">@lang('contents.current_stock')</a></li>
                    <li id="damage-stock"><a href="{{ route('damageStock.index') }}"
                            title="Damage stock">@lang('contents.damage_stock')</a></li>
                    <li id="product-transfer"><a href="{{ route('productTransfer.index') }}"
                            title="Product transfer">@lang('contents.product_transfer')</a></li>
                </ul>
            </li>

            {{-- <li id="zone">
                <a href="{{ route('zone.index') }}">
                    <i class="fa fa-tachometer" aria-hidden="true"></i>
                    <span title="Zone">Zone</span>
                </a>
            </li> --}}

            <!-- customer/parties -->
            <li id="customer" class="dropdown">
                <a href="#">
                    <i class="fa fa-users"></i>
                    <span title="Company">@lang('contents.customer')</span>
                    <span class="float-right">
                        <i class="fa fa-angle-right right" aria-hidden="true"></i>
                        <i class="fa fa-angle-down down" aria-hidden="true"></i>
                    </span>
                </a>

                <ul>
                    <li id="customer-list"><a href="{{ route('customer.index') }}"
                            title="Records">@lang('contents.records')</a></li>
                    <li id="customer-add"><a href="{{ route('customer.create') }}"
                            title="Create new">@lang('contents.create_new')</a></li>
                </ul>
            </li>

            <!-- customer/parties -->
            {{-- <li id="installment-collection" class="dropdown">--}}
                {{-- <a href="#">--}}
                    {{-- <i class="fa fa-address-card" aria-hidden="true"></i>--}}
                    {{-- <span title="Company">Installment Collection</span>--}}
                    {{-- <span class="float-right">--}}
                        {{-- <i class="fa fa-angle-right right" aria-hidden="true"></i>--}}
                        {{-- <i class="fa fa-angle-down down" aria-hidden="true"></i>--}}
                        {{-- </span>--}}
                    {{-- </a>--}}

                {{-- <ul>--}}
                    {{-- <li id="all"><a href="{{ route('installmentCollection.index') }}" title="Records">All
                            Collection</a></li>--}}
                    {{-- <li id="new"><a href="{{ route('installmentCollection.create') }}" title="Create new">New
                            Collection</a></li>--}}
                    {{-- </ul>--}}
                {{-- </li>--}}

            <!-- supplier/company -->
            <li id="supplier" class="dropdown">
                <a href="#">
                    <i class="fa fa-building" aria-hidden="true"></i>
                    <span title="Company">@lang('contents.supplier')</span>
                    <span class="float-right">
                        <i class="fa fa-angle-right right" aria-hidden="true"></i>
                        <i class="fa fa-angle-down down" aria-hidden="true"></i>
                    </span>
                </a>

                <ul>
                    <li id="supplier-list"><a href="{{ route('supplier.index') }}"
                            title="Records">@lang('contents.records')</a></li>
                    <li id="supplier-add"><a href="{{ route('supplier.create') }}"
                            title="Create new">@lang('contents.create_new')</a></li>
                </ul>
            </li>

            <!-- cash -->
            <li id="cash" class="dropdown">
                <a href="#">
                    <i class="fa fa-money" aria-hidden="true"></i>
                    <span title="Banking">@lang('contents.cash')</span>
                    <span class="float-right">
                        <i class="fa fa-angle-right right" aria-hidden="true"></i>
                        <i class="fa fa-angle-down down" aria-hidden="true"></i>
                    </span>
                </a>

                <ul>
                    <li id="cash-list"><a href="{{ route('cash.index') }}"
                            title="Cash list">@lang('contents.cash_list')</a></li>
                    <li id="cash-create"><a href="{{ route('cash.create') }}"
                            title="Create new">@lang('contents.create_new')</a></li>
                </ul>
            </li>

            <!-- banking -->
            <li id="bank" class="dropdown">
                <a href="#">
                    <i class="fa fa-university" aria-hidden="true"></i>
                    <span title="Banking">@lang('contents.banking') </span>
                    <span class="float-right">
                        <i class="fa fa-angle-right right" aria-hidden="true"></i>
                        <i class="fa fa-angle-down down" aria-hidden="true"></i>
                    </span>
                </a>

                <ul>
                    <li id="bank-list"><a href="{{ route('bank.index') }}"
                            title="Bank list">@lang('contents.bank_list')</a></li>
                    <li id="bank-account-list"><a href="{{ route('bankAccount.index') }}"
                            title="Back account list">@lang('contents.bank_account_list')</a></li>
                    <li id="balance-transfer"><a href="{{ route('balanceTransfer.index') }}"
                            title="Balance transfer">@lang('contents.balance_transfer')</a></li>
                </ul>
            </li>

            <!---- Start Loan ---->
            <li id="loan" class="dropdown">
                <a href="#">
                    <i class="fa fa-credit-card" aria-hidden="true"></i>
                    <span title="Loan">@lang('contents.loan') </span>
                    <span class="float-right">
                        <i class="fa fa-angle-right right" aria-hidden="true"></i>
                        <i class="fa fa-angle-down down" aria-hidden="true"></i>
                    </span>
                </a>

                <ul>
                    <li id="account"><a href="{{ route('loanAccount.index') }}"
                            title="Account List">@lang('contents.loan') @lang('contents.account')</a></li>
                    <li id="list"><a href="{{ route('loan.index') }}" title="Loan list">@lang('contents.loan')</a></li>
                </ul>
            </li>
            <!---- Start Loan ---->

            <!-- banking -->
            {{-- <li id="production" class="dropdown">--}}
                {{-- <a href="#">--}}
                    {{-- <i class="fa fa-university" aria-hidden="true"></i>--}}
                    {{-- <span title="Production">Production</span>--}}
                    {{-- <span class="float-right">--}}
                        {{-- <i class="fa fa-angle-right right" aria-hidden="true"></i>--}}
                        {{-- <i class="fa fa-angle-down down" aria-hidden="true"></i>--}}
                        {{-- </span>--}}
                    {{-- </a>--}}

                {{-- <ul>--}}
                    {{-- <li id="production-id"><a href="{{ route('productionIn.index') }}" title="Bank list">Production
                            In</a></li>--}}
                    {{-- <li id="production-out"><a href="#" title="Back account list">Production Out</a></li>--}}
                    {{-- </ul>--}}
                {{-- </li>--}}

            <!-- reports -->
            <li id="reports" class="dropdown">
                <a href="#">
                    <i class="fa fa-area-chart" aria-hidden="true"></i>
                    <span title="Banking">@lang('contents.reports') </span>
                    <span class="float-right">
                        <i class="fa fa-angle-right right" aria-hidden="true"></i>
                        <i class="fa fa-angle-down down" aria-hidden="true"></i>
                    </span>
                </a>

                <ul>
                    {{-- cash book report start --}}
                    <li id="cash-book">
                        <a href="{{ route('cashBook.index') }}" title="Cash Book">@lang('contents.cash_book')</a>
                    </li>
                    {{-- cash book report start --}}

                    {{-- profit loss report start --}}
                    <li id="profit-and-loss">
                        <a href="{{ route('profitLoss.index') }}"
                            title="Profit & Loss">@lang('contents.profit_and_loss')</a>
                    </li>
                    {{-- profite loss report start --}}

                    <!-- income report start -->
                    <li id="income-report">
                        <a href="{{ route('incomeReport.index') }}" title="Product wise report">@lang('contents.income_report')</a>
                    </li>
                    <!-- income report end -->

                    {{-- sales report start --}}
                    <li id="sales-report">
                        <a href="{{ route('report.sales.total') }}"
                            title="Sales Report">@lang('contents.sale_report')</a>
                    </li>
                    {{-- sales report end --}}

                    {{-- invoice report start --}}
                    <li id="invoice-report">
                        <a href="{{ route('invoiceReport') }}" title="invoice Report">@lang('contents.invoice')
                            @lang('contents.report')</a>
                    </li>
                    {{-- invoice report end --}}

                    {{-- purchase report start --}}
                    <li id="purchase-report">
                        <a href="{{ route('report.purchases') }}"
                            title="Purchase Report">@lang('contents.purchase_report')</a>
                    </li>
                    {{-- purchase report end --}}

                    {{-- expense report start --}}
                    <li id="expenditure-report">
                        <a href="{{ route('report.expenditure') }}"
                            title="Expenditure Report">@lang('contents.expenditure_report')</a>
                    </li>
                    {{-- expense report end --}}

                    {{-- product report start --}}
                    <li id="product-wise-report">
                        <a href="{{ route('productReport.index') }}"
                            title="Product wise report">@lang('contents.product_wise_report')</a>
                    </li>
                    {{-- product report end --}}

                    {{-- monthly report start --}}
                    <li id="monthly-report">
                        <a href="{{ route('monthlyReport.index') }}" title="Monthly Report">@lang('contents.monthly')
                            @lang('contents.report')</a>
                    </li>
                    {{-- monthly report end --}}
                </ul>
            </li>

            <!-- ledger -->
            <li id="ledger-report" class="dropdown">
                <a href="#">
                    <i class="fa fa-line-chart" aria-hidden="true"></i>
                    <span title="Ledger Report">@lang('contents.ledger') @lang('contents.report')</span>
                    <span class="float-right">
                        <i class="fa fa-angle-right right" aria-hidden="true"></i>
                        <i class="fa fa-angle-down down" aria-hidden="true"></i>
                    </span>
                </a>

                <ul>
                    <li id="ledger-report"><a href="{{ route('report.supplierLedger') }}"
                            title="Supplier Ledger Report">@lang('contents.supplier') @lang('contents.ledger')</a></li>
                    <li id="ledger-report"><a href="{{ route('report.customerLedger') }}"
                            title="Customer Ledger Report">@lang('contents.customer') @lang('contents.ledger')</a></li>
                </ul>
            </li>
            <!-- Sms  -->

            <li id="sms" class="dropdown">
                <a href="#">
                    <i class="fa fa-comments" aria-hidden="true"></i>
                    <span title="sms">@lang('contents.sms')</span>
                    <span class="float-right">
                        <i class="fa fa-angle-right right" aria-hidden="true"></i>
                        <i class="fa fa-angle-down down" aria-hidden="true"></i>
                    </span>
                </a>

                <ul>
                    <li id="sms"><a href="{{ route('sms.groupSms') }}" title="Send group sms">@lang('contents.group') @lang('contents.sms')</a></li>
                </ul>
                <ul>
                    <li id="sms"><a href="{{ route('sms.customSms') }}" title="Send group sms">@lang('contents.custom') @lang('contents.sms')</a></li>
                </ul>
                <ul>
                    <li id="sms"><a href="{{ route('sms.smsReport') }}" title="Sms report">@lang('contents.sms_report')</a></li>

                </ul>
            </li>
        </ul>

        <!-- Accounting -->
        <h6>@lang('contents.accounting')</h6>
        <ul>
            <li id="general-ledger" class="dropdown">
                <a href="#">
                    <i class="fa fa-university" aria-hidden="true"></i>
                    <span title="General Ledger">@lang('contents.general_ledger')</span>
                    <span class="float-right">
                        <i class="fa fa-angle-right right" aria-hidden="true"></i>
                        <i class="fa fa-angle-down down" aria-hidden="true"></i>
                    </span>
                </a>

                <ul>
                    <li id="gl-account"><a href="{{ route('glAccount.index') }}"
                            title="GL Account">@lang('contents.gl_account')</a></li>
                    <li id="gl-account-head"><a href="{{ route('glAccountHead.index') }}"
                            title="GL Account Head">@lang('contents.gl_account_head')</a></li>
                </ul>
            </li>

            <!-- Expenditure -->
            <li id="expenditure" class="dropdown">
                <a href="#">
                    <i class="fa fa-cc-mastercard" aria-hidden="true"></i>
                    <span title="Expenditure">@lang('contents.expenditure')</span>
                    <span class="float-right">
                        <i class="fa fa-angle-right right" aria-hidden="true"></i>
                        <i class="fa fa-angle-down down" aria-hidden="true"></i>
                    </span>
                </a>

                <ul>
                    <li id="expenditure-index"><a href="{{ route('expenditure.index') }}"
                            title="Expenditure list">@lang('contents.todays_list')</a></li>
                    <li id="expenditure-create"><a href="{{ route('expenditure.create') }}"
                            title="Add new expenditure">@lang('contents.new_expense')</a></li>
                </ul>
            </li>

            <!-- Income -->
            <li id="income" class="dropdown">
                <a href="#">
                    <i class="fa fa-bar-chart" aria-hidden="true"></i>
                    <span title="Income">@lang('contents.income')</span>
                    <span class="float-right">
                        <i class="fa fa-angle-right right" aria-hidden="true"></i>
                        <i class="fa fa-angle-down down" aria-hidden="true"></i>
                    </span>
                </a>

                <ul>
                    <li id="income-sector"><a href="{{ route('incomeSector.index') }}"
                            title="Income Sector">@lang('contents.income') @lang('contents.sector')</a></li>
                    <li id="income-list"><a href="{{ route('incomeRecord.index') }}"
                            title="Income List">@lang('contents.income') @lang('contents.record')</a></li>
                </ul>
            </li>

            <!-- <li id="journal">
                <a href="#">
                    <i class="fa fa-area-chart" aria-hidden="true"></i>
                    <span title="journal">@lang('contents.journal')</span>
                </a>
            </li> -->

            <!-- <li id="daily-entry">
                <a href="#">
                    <i class="fa fa-area-chart" aria-hidden="true"></i>
                    <span title="Daily Accounts">@lang('contents.daily_accounts')</span>
                </a>
            </li> -->
        </ul>

        <!-- App Modules -->
        <h6>@lang('contents.app_modules')</h6>
        <ul>
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
                    <li id="media-list"><a href="{{ route('media.index') }}" title="Images">@lang('contents.images')</a>
                    </li>
                    <li id="media-add"><a href="{{ route('media.create') }}"
                            title="New upload">@lang('contents.new_upload')</a></li>
                </ul>
            </li>

            <!-- barcode -->
            <li id="barcode" class="dropdown">
                <a href="#">
                    <i class="fa fa-barcode" aria-hidden="true"></i>
                    <span title="Barcode">@lang('contents.barcode')</span>
                    <span class="float-right">
                        <i class="fa fa-angle-right right" aria-hidden="true"></i>
                        <i class="fa fa-angle-down down" aria-hidden="true"></i>
                    </span>
                </a>

                <ul>
                    <li id="batch_generator">
                        <a href="{{ route('barcode.index') }}" title="Generator">@lang('contents.batch_generator') </a>
                    </li>
                    <li id="single_generator">
                        <a href="{{ route('barcode.single') }}" title="Generator">@lang('contents.single_generator')
                        </a>
                    </li>

                    <li id="invoice_generator">
                        <a href="{{ route('barcode.invoice') }}" title="Generator">@lang('contents.voucher_generator' )
                        </a>
                    </li>

                </ul>
            </li>
        </ul>
    </nav>
</aside>
<!-- aside content end -->
