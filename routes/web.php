<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// frontend
Route::get('/', 'WelcomeController@welcome');

// localization
Route::get('/locale/{lang}', 'LocaleController@changeLocale');

// Auth
Auth::routes();
Route::get('/subscriber/activate/{email}/{token}', 'Auth\RegisterController@activate')->name('subscriber.activate');

// User controller
Route::group(['namespace' => 'User'], function () {
    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('user/home/dailySale', 'HomeController@getDailySale');
    Route::get('/api-token', 'HomeController@generateToken');

    //sms route
    Route::get('user/group-sms', 'Sms\SmsController@groupSms')->name('sms.groupSms');
    Route::post('user/group-sms', 'Sms\SmsController@groupSmsSend');

    Route::get('user/custom-sms', 'Sms\SmsController@customSms')->name('sms.customSms');
    Route::post('user/custom-sms', 'Sms\SmsController@customSmsSend');

    Route::get('user/sms-report', 'Sms\SmsController@smsReport')->name('sms.smsReport');

    // Warehouse Route
    Route::get('user/warehouse/{id}/status', 'WarehouseController@changeStatus')->name('warehouse.status');

    // cash ledger details route
    Route::get('user/cash-ledger-details/{id}', 'CashController@ledgerDetails')->name('cash.ledger-details');

    // bank Route
    Route::get('user/bank/{id}/status', 'BankController@changeStatus')->name('bank.status');

    // bank account Route
    Route::get('user/bank/transaction/{id}', 'BankAccountController@showTransaction')->name('bankAccount.transaction');

    // accounting Route
    Route::get('user/glAccount/{id}/status', 'GLAccountController@changeStatus')->name('glAccount.status');

    Route::get('user/glAccountHead/{id}/status', 'GLAccountHeadController@changeStatus')->name('glAccountHead.status');

    //point of sale (pos) list
    Route::get('user/pos/', 'POSController@index')->name('pos.index');
    Route::get('user/pos/export', 'POSController@export')->name('pos.export');
    Route::delete('user/pos/{id}', 'POSController@destroy')->name('pos.destroy');

    Route::post('user/pos/delivered/{sale}', 'POSController@deliver')->name('pos.deliver');

    // trashed category
    Route::get('user/category/trashed', 'CategoryController@viewTrashed')->name('category.viewTrashed');
    Route::delete('user/category/trashed/{id}', 'CategoryController@forceDelete')->name('category.forceDelete');
    Route::post('user/category/trashed/{id}', 'CategoryController@restore')->name('category.restore');

    // unit trashed
    Route::get('user/unit/trashed', 'UnitController@viewUnitTrashed')->name('unit.viewTrashed');
    Route::delete('user/unit/trashed/{id}', 'UnitController@forceDelete')->name('unit.forceDelete');
    Route::post('user/unit/trashed/{id}', 'UnitController@restore')->name('unit.restore');

    // brand trashed
    Route::get('user/brand/trashed', 'BrandController@viewTrashed')->name('brand.viewTrashed');
    Route::delete('user/brand/trashed/{id}', 'BrandController@forceDelete')->name('brand.forceDelete');
    Route::post('user/brand/trashed/{id}', 'BrandController@restore')->name('brand.restore');

    // product trashed
    Route::get('user/product/trashed', 'ProductController@viewTrashed')->name('product.viewTrashed');
    Route::delete('user/product/trashed/{id}', 'ProductController@forceDelete')->name('product.forceDelete');
    Route::post('user/product/trashed/{id}', 'ProductController@restore')->name('product.restore');

    // supplier trashed
    Route::get('user/supplier/trashed', 'SupplierController@viewTrashed')->name('supplier.viewTrashed');
    Route::delete('user/supplier/trashed/{id}', 'SupplierController@forceDelete')->name('supplier.forceDelete');
    Route::post('user/supplier/trashed/{id}', 'SupplierController@restore')->name('supplier.restore');

    // Customer trashed
    Route::get('user/customer/trashed', 'CustomerController@viewTrashed')->name('customer.viewTrashed');
    Route::delete('user/customer/trashed/{id}', 'CustomerController@forceDelete')->name('customer.forceDelete');
    Route::post('user/customer/trashed/{id}', 'CustomerController@restore')->name('customer.restore');

    // warehouse trashed
    Route::get('user/warehouse/trashed', 'WarehouseController@viewTrashed')->name('warehouse.viewTrashed');
    Route::delete('user/warehouse/trashed/{id}', 'WarehouseController@forceDelete')->name('warehouse.forceDelete');
    Route::post('user/warehouse/trashed/{id}', 'WarehouseController@restore')->name('warehouse.restore');

    //point of sale (pos) new
    Route::get('user/pos/create', 'POSController@create')->name('pos.create');
    //point of sale (pos) show
    Route::get('user/pos/show/{sale}', 'POSController@show')->name('pos.show');
    //pos user payment proceed
    Route::get('user/pos/checkout', 'POSController@checkout')->name('pos.checkout');
    //pos return
    // Route::get('user/pos/return/{invoice_no}', 'POSController@return')->name('pos.return');
    // Route::post('user/pos/return', 'POSController@returnProceed');

    // pre-order delivery route
    Route::get('user/pre-order-delivery/{id}', 'PreOrderController@delivery')->name('preOrder.delivery');
    // pre-order delivery
    Route::put('user/pre-order-process/{id}', 'PreOrderController@deliveryProcess');

    //invoice generate
    Route::get('user/invoice-generate/{invoice_no}', 'InvoiceController@index')->name('invoice.generate');
    Route::get('user/invoice-print/{invoice_no}', 'InvoiceController@print')->name('invoice.print');

    // Category Active Toggle Route
    Route::get('user/category/{category}/toggleActive', 'CategoryController@toggleActive')->name('category.toggleActive');

    // change supplier status
    Route::get('user/supplier/{id}/status', 'SupplierController@changeSuppliersStatus')->name('supplier.changeSuppliersStatus');

    // change customer status
    Route::get('user/customer/{id}/status', 'CustomerController@changeCustomerStatus')->name('customer.changeCustomerStatus');

    // Brand Active Toggle Route
    Route::get('user/brand/{brand}/toggleActive', 'BrandController@toggleActive')->name('brand.toggleActive');

    // daily report route
    // Route::get('user/Reports/cashBook', 'CashBookController@index')->name('cashBook.index');

    // retail due manage create route
    Route::get('user/retail-due-collection/{id}', 'RetailDueCollectionController@createRetailDue')->name('createRetailDue');

    //product search route
    Route::get('user/search-product', 'ProductController@search')->name('product.search');
    Route::get('user/product-export', 'ProductController@export')->name('product.export');

    // barcode routes
    Route::get('user/barcode', 'BarcodeGeneratorController@index')->name('barcode.index');
    Route::get('user/barcode/single', 'BarcodeGeneratorController@single')->name('barcode.single');
    Route::get('user/barcode/invoice', 'BarcodeGeneratorController@invoice')->name('barcode.invoice');
    Route::get('user/barcode/single-print', 'BarcodeGeneratorController@singlePrint')->name('barcode.single-print');

    //damage-stock edit route
    Route::get('user/damage-stock/{id}/edit', 'DamageStockController@editDamage')->name('damage.edit');
    // excel stock upload
    Route::post('user/stock/upload-excel', 'StockController@import')->name('stock.import');
    Route::get('user/stock/upload-excel', 'StockController@excel')->name('excel');

    // excel customer upload
    Route::post('user/customer/upload-excel', 'CustomerController@import')->name('customer.import');
    Route::get('user/customer/upload-excel', 'CustomerController@excel')->name('excel');

    // excel supplier upload
    Route::post('user/supplier/upload-excel', 'SupplierController@import')->name('supplier.import');
    Route::get('user/supplier/upload-excel', 'SupplierController@excel')->name('excel');

    //damage-stock update route
    Route::post('user/damage-stock/{id}/update', 'DamageStockController@updateDamage')->name('damage.update');

    // Due management route
    Route::get('user/dueManagement/{type}/index', 'DueManagementController@index')->name('dueManagement.index');
    Route::get('user/dueManagement/{type}', 'DueManagementController@create')->name('dueManagement.create');
    Route::post('user/dueManagement', 'DueManagementController@store')->name('dueManagement.store');

    //purchase return
    Route::get('user/purchase/{purchase}/return', 'PurchaseControllerOld@returnPurchase')->name('purchase.return');
    Route::post('user/purchase/{id}/return', 'PurchaseControllerOld@returnPurchaseProceed');

    // due collection route
    Route::get('user/due-collection', 'DueCollectionController@index')->name('dueCollection.index');
    Route::get('user/due-collection/create/{id}', 'DueCollectionController@create')->name('dueCollection.create');
    Route::get('user/due-collection/all-collection', 'DueCollectionController@allCollection')->name('dueCollection.all');

    Route::group(['namespace' => 'Report'], function () {
        Route::get('user/Reports/cashBook', 'CashBookController@index')->name('cashBook.index'); // daily report route
        Route::post('user/Reports/storeBalance', 'CashBookController@storeBalance')->name('cashBook.storeBalance');
        Route::get('user/Reports/profit-loss', 'ProfitLossController@index')->name('profitLoss.index'); // profit-loss report route
        Route::get('user/Reports/productReport', 'ProductReportController@index')->name('productReport.index'); // purchase report route
        // income report route
        Route::get('user/Reports/income-report', 'IncomeReportController@index')->name('incomeReport.index');

        // sale reports
        Route::get('user/reports/salesReport', 'SalesReportController@index')->name('report.sales');
        Route::get('user/reports/salesReport/{period}', 'SalesReportController@daily')->name('report.sales.daily');
        Route::get('user/reports/salesReport/details/{period}', 'SalesReportController@details')->name('report.sales.details');
        Route::get('user/reports/allSalesReport', 'SalesReportController@totalSales')->name('report.sales.total');

        // supplier ledger report
        Route::get('user/reports/supplierLedger', 'LedgerReportController@supplierLedger')->name('report.supplierLedger');
        // customer ledger report
        Route::get('user/reports/customerLedger', 'LedgerReportController@customerLedger')->name('report.customerLedger');
        // hire sale ledger
        Route::get('user/reports/hire-sale-ledger', 'LedgerReportController@hireSaleLedger')->name('report.hireSaleLedger');

        // purchase reports
        Route::get('user/reports/purchasesReport', 'PurchaseReportController@index')->name('report.purchases');
        Route::get('user/reports/purchasesReport/{period}', 'PurchaseReportController@daily')->name('report.purchases.daily');
        Route::get('user/reports/purchasesReport/details/{period}', 'PurchaseReportController@details')->name('report.purchases.details');

        // purchase reports
        Route::get('user/reports/expenditureReport', 'ExpenditureReportController@index')->name('report.expenditure');
        Route::get('user/reports/expenditureReport/{period}', 'ExpenditureReportController@daily')->name('report.expenditure.daily');
        Route::get('user/reports/expenditureReport/details/{period}', 'ExpenditureReportController@details')->name('report.expenditure.details');

        // monthly report route
        Route::get('user/reports/monthly_report', 'MonthlyReportController@index')->name('monthlyReport.index');

        // invoice wise report route
        Route::get('user/reports/invoice-wise-report', 'InvoiceReportController@index')->name('invoiceReport');

    });

    Route::resources([
        'user/stock'                    => 'StockController', // stock resource route
        'user/damageStock'              => 'DamageStockController', // damage-stock
        'user/category'                 => 'CategoryController', // category resource route
        'user/cash'                     => 'CashController', // cash resource route
        'user/media'                    => 'MediaController', // media resource route
        'user/account'                  => 'AccountController', // account resource route
        'user/unit'                     => 'UnitController', // unit resource route
        'user/warehouse'                => 'WarehouseController', // Warehouse Route
        'user/supplier'                 => 'SupplierController', // supplier resource route
        'user/brand'                    => 'BrandController', // brand resource route
        'user/customer'                 => 'CustomerController', // customer resource route
        'user/zone'                     => 'ZoneController', // customer resource route
        'user/product'                  => 'ProductController', // product resource route
        'user/bank'                     => 'BankController', // bank resource route
        'user/bankAccount'              => 'BankAccountController', // bank-account resource route
        'user/balanceTransfer'          => 'BalanceTransferController', // balance-transfer resource route
        'user/customerDueManage'        => 'CustomerDueManageController', // customer due manage resource route
        'user/retailDueCollection'      => 'RetailDueCollectionController', // retail due manage resource route
        'user/supplierDueManage'        => 'SupplierDueManageController', // supplier due manage resource route
        // accounting
        'user/glAccount'                => 'GLAccountController', // general ledger account resource route
        'user/glAccountHead'            => 'GLAccountHeadController', // general ledger account head resource route
        'user/expenditure'              => 'ExpenditureController', // expenditure resource route
        'user/purchaseOld'              => 'PurchaseControllerOld', // purchase Old resource route
        'user/purchase'                 => 'PurchaseController', // purchase resource route
        'user/purchase-return'          => 'PurchaseReturnController', // purchase return resource route
        'user/saleReturn'               => 'SaleReturnController', // sale return resource route
        'user/productTransfer'          => 'ProductTransferController', // product-transfer resource route
        'user/orderManagement'          => 'OrderManagementController', // order-manage resource route
        'user/incomeSector'             => 'IncomeSectorController', // income-sector resource route
        'user/incomeRecord'             => 'IncomeRecordController', // income-record resource route
        'user/productionIn'             => 'ProductionInController', // production-in resource route
        'user/productionOut'            => 'ProductionOutController', // production-out resource route
        'user/installmentCollection'    => 'InstallmentCollectionController', // installment collection resource route
        'user/loan'                     => 'LoanController', // Loan resource route
        'user/loanAccount'              => 'LoanAccountController', // Loan account resource route
        'user/loanInstallment'          => 'LoanInstallmentController', // Loan account resource route
        'user/preOrder'                 => 'PreOrderController', // Pre Order resource route
        // <!-- transaction balance from customer to supplier or vise-versa. -->
        'user/transaction'              => 'TransactionController', // transaction resource route
        // <!-- transaction balance from customer to supplier or vise-versa. -->
        'user/hire-sale'                => 'HireSaleController', // hire sale resource route
    ]);

    /*------------------AJAX Route Start------------------*/

    //get all salesman
    Route::post('get-salesmen', 'POSController@salesmen');

    //get all active warehouses
    Route::post('user/get-all-active-warehouses', 'WarehouseController@allActiveWarehouses');

    //calculate sale return line total
    Route::post('user/calculate-sale-return-line-total', 'POSController@calculateLineTotal');

    //get all active categories
    Route::post('user/get-all-active-categories', 'POSController@allActiveCategories');

    //get product details
    Route::post('user/get-details-from-product', 'POSController@productDetails');

    //get all active products
    Route::post('user/get-all-active-products', 'POSController@allActiveProducts');

    // get all filtered products
    Route::post('user/pos/filter-wise-products', 'POSController@filterWiseProducts');

    Route::post('user/get-all-products', 'POSController@allProducts');

    //get warehouse-wise product
    Route::post('user/get-product-from-warehouse', 'StockController@getProductsFromWarehouse');

    //get product unit
    Route::post('user/get-product-unit', 'StockController@getProductUnit');

    // get details from cashes
    Route::post('user/get-details-from-cash', 'CashController@cashDetails');

    //get all cashes
    Route::post('user/get-all-cashes', 'CashController@allCashes');

    // get accounts from bank
    Route::post('user/get-accounts-from-bank', 'BankController@accounts');

    // get banks
    Route::post('user/get-all-banks', 'BankController@allBankAccounts');

    // get details from account
    Route::post('user/get-details-from-account', 'BankAccountController@accountDetails');

    //pos user - add to cart
    Route::post('user/pos/add-to-cart', 'CartController@addToCart');

    //pos user - update cart item
    Route::post('user/pos/update-cart-item', 'CartController@updateCartItem');

    //pos user get current cart content
    Route::post('user/pos/get-current-cart-items', 'CartController@cartItems');

    //pos user get sub total
    Route::post('user/pos/get-sub-total', 'CartController@subTotal');

    //pos user get total
    Route::post('user/pos/get-total', 'CartController@total');

    //pos user get calculated vat amount of subtotal
    Route::post('user/pos/get-calculated-amount-of-subtotal-for-regular-vat', 'CartController@calculateValueOfSubtotalForRegularVat');

    //pos user apply discount
    Route::post('user/pos/apply-discount', 'CartController@applyDiscount');

    //pos user apply discount
    Route::post('user/pos/get-applied-discount', 'CartController@appliedDiscount');

    //pos user remove specific item form cart
    Route::post('user/pos/remove-cart-item', 'CartController@removeItem');

    //pos user clear cart
    Route::post('user/pos/clear-cart-items', 'CartController@clearCartItems');

    //pos user payment proceed
    Route::post('user/pos-proceed-payment', 'CartController@proceedPayment');

    // get brands from suppliers
    Route::post('user/get-brands-from-supplier', 'SupplierController@brands');

    // get categories from brand
    Route::post('user/get-categories-from-brand', 'BrandController@categories');

    //get all supplier
    Route::post('user/get-all-active-suppliers', 'SupplierController@allActiveSuppliers');

    //get all customers
    Route::post('user/get-all-active-customers', 'CustomerController@allActiveCustomers');

    //create new customer for pos
    Route::post('user/create-new-customer', 'CustomerController@createNewCustomer');

    //get supplier details
    Route::post('user/get-details-from-party', 'SupplierController@partyDetails');
    // get customer details
    Route::post('user/get-details-from-customer/{id}', 'CustomerController@customerDetails');

    // get gl head
    Route::post('user/get-gl-account-heads', 'GLAccountController@getGLAccountHeads');

    //purchase user add to cart
    Route::post('user/purchase/add-to-cart', 'PurchaseControllerOld@addToCart');
    //purchase user get cart contents
    Route::post('user/purchase/get-cart-contents', 'PurchaseControllerOld@getCartContents');
    //purchase user remove item
    Route::post('user/purchase/remove-cart-item', 'PurchaseControllerOld@removeCartItem');
    //purchase user clear cart contents
    Route::post('user/purchase/clear-cart-contents', 'PurchaseControllerOld@clearCartContents');

    Route::post('user/calculate-purchase-return-line-total', 'PurchaseControllerOld@calculatePurchaseReturnLineTotal');
//    Route::post('user/purchase-return', 'PurchaseControllerOld@returnProceed');

    //get all active product from supplier
    Route::post('get-all-active-products-from-supplier', 'SupplierController@allActiveProducts');

    //production-in user add to cart
    Route::post('user/production-in/add-to-cart', 'ProductionInController@addToCart');
    //purchase user remove item
    Route::post('user/production-in/remove-cart-item', 'ProductionInController@removeCartItem');
    // clear session data
    Route::post('user/production-in/clear-cart-contents', 'ProductionInController@clearCartContents');

    // Retail sale
    Route::post('user/retail-sale', 'RetailSaleController@sale');
    Route::get('user/retail-sale/update/{id}', 'RetailSaleController@edit')->name('saleUpdate');
    Route::put('user/retail-sale/{id}', 'RetailSaleController@retailSaleUpdate');
    /*-------------------AJAX Route End------------------*/
});

// Admin controller
Route::name('admin.')->group(function () {
    Route::group(['namespace' => 'Admin'], function () {
        // admin dashboard
        Route::get('admin/home', 'HomeController@index')->name('home');
        Route::get('admin/home/dailySale', 'HomeController@getDailySale');

        // change Employee status
        Route::get('admin/employee/{id}/status', 'EmployeeController@changeEmployeeStatus')->name('employee.changeEmployeeStatus');

        Route::group(['namespace' => 'Reports'], function () {
            // current-stock report route
            Route::get('admin/Reports/currentStock', 'StockReportController@currentStock')->name('currentStockReport.currentStock');
            // damage-stock report route
            Route::get('admin/Reports/damageStock', 'StockReportController@damageStock')->name('damageStockReport.damageStock');
            // expenditure report route
            Route::get('admin/Reports/expenditure', 'ExpenditureReportController@index')->name('expenditureReport.index');
            // daily report route
            Route::get('admin/Reports/dailyReport', 'DailyReportController@index')->name('dailyReport.index');
            // sale report route
            Route::get('admin/Reports/saleReport', 'SaleReportController@saleReport')->name('saleReport');
            // purchase report route
            Route::get('admin/Reports/purchaseReport', 'PurchaseReportController@purchaseReport')->name('purchaseReport');
            // sale return report route
            Route::get('admin/Reports/saleReturnReport', 'SaleReturnReportController@saleReturn')->name('saleReturnReport');
            // profit loss report route
            Route::get('admin/Reports/profitLossReport', 'ProfitLossReportController@index')->name('profitLossReport');
        });

        Route::resources([
            'admin/account'                     => 'AccountController', // account resource routes
            'admin/role'                        => 'RoleController', // role resource routes
            'admin/permission'                  => 'PermissionController', // permission resource routes
            'admin/media'                       => 'MediaController', // media resource route
            'admin/employee'                    => 'EmployeeController', // employee resource route
            'admin/business'                    => 'BusinessController', // businesses resource route
            'admin/settings'                    => 'SettingsController', // businesses resource route
        ]);

        Route::group(['namespace' => 'PayrollManagement'], function () {
            Route::get('admin/salaryPay/{id}', 'SalaryController@salaryPay')->name('salaryPay');
            Route::get('admin/salaryView/{id}', 'SalaryController@salaryView')->name('salaryView');
            Route::resources([
                'admin/advancedSalary'      => 'AdvancedSalaryController', // advanced salary management resource route
                'admin/salary'              => 'SalaryController', // salary management resource route
            ]);
        });


        /*------------------AJAX Route Start------------------*/

        // salaryDetails route
        Route::post('get-salary-details', 'PayrollManagement\SalaryController@salaryDetails');

        /*-------------------AJAX Route End------------------*/

        // barcode routes
        Route::get('barcode', 'BarcodeGeneratorController@index')->name('barcode.index');

        // admin auth
        Route::get('admin-login', 'Auth\LoginController@showLoginForm')->name('login');
        Route::post('admin-login', 'Auth\LoginController@login');
        Route::post('admin-logout', 'Auth\LoginController@logout')->name('logout');
    });
});

// routes for 2nd database connection
Route::get('get-meta', 'SystemController@getMeta');
Route::get('set-meta', 'SystemController@setMeta');
Route::get('set-meta2', 'SystemController@setMeta2');

Route::get('/storage_link', function () {
    Artisan::call('storage:link');
});
