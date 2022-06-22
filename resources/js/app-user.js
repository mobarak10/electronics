/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import vSelect from "vue-select";
import "select2/dist/css/select2.css";
import 'select2/dist/js/select2';

require("./bootstrap");
window.Vue = require("vue");
Vue.component("v-select", vSelect);


// select-2
import 'select2/dist/css/select2.css';
import 'select2/dist/js/select2'

/*
 * add base url into vue
 * */

//console.log(document.getElementsByTagName("meta").find(meta => meta.name == ''))
Vue.prototype.baseURL =
    document.head.querySelector('meta[name="base-url"]').content + "/";
window.baseURL =
    document.head.querySelector('meta[name="base-url"]').content + "/";

window.Chart = require("chart.js");
require("chart.js/dist/Chart.min.css");

/**
 * Vuex initialization
 */

import store from "./user/store/index";
Vue.use(store);

// Vue awesome notification
import VueAWN from "vue-awesome-notifications";
require("vue-awesome-notifications/dist/styles/style.css");
Vue.use(VueAWN);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key)))

// for passport components
Vue.component("passport-clients", require("./components/passport/Clients.vue"));
Vue.component(
    "passport-authorized-clients",
    require("./components/passport/AuthorizedClients.vue")
);
Vue.component(
    "passport-personal-access-tokens",
    require("./components/passport/PersonalAccessTokens.vue")
);

// User Component
Vue.component(
    "sale-return-component",
    require("./user/components/pos/SaleReturnContainerComponent.vue")
);

Vue.component(
    "product-purchase-component",
    require("./user/components/purchase-old/PurchaseProductComponent.vue")
);

Vue.component(
    "product-purchase-return-container-component",
    require("./user/components/purchase-old/PurchaseReturnContainerComponent.vue")
);

Vue.component(
    "purchase-create-component",
    require("./user/components/purchase/PurchaseCreateComponent.vue")
)

Vue.component(
    "create-product-component",
    require("./user/components/product/CreateProductComponent.vue")
);
Vue.component(
    "product-search-company-brand-category",
    require("./user/components/product/search-form/CompanyBrandCategoryComponent.vue")
);
Vue.component(
    "update-product-component",
    require("./user/components/product/UpdateProductComponent.vue")
);
Vue.component(
    "bank-transaction-component",
    require("./user/components/banking/TransactionComponent.vue")
);

// gl account head component
Vue.component(
    "create-gl-account-head-component",
    require("./user/components/glAccountHead/CreateGLAccountHeadComponent.vue")
);
Vue.component(
    "update-gl-account-head-component",
    require("./user/components/glAccountHead/UpdateGLAccountHeadComponent.vue")
);

// expense components
Vue.component(
    "expense-entry-component",
    require("./user/components/expense/ExpenseCreateComponent.vue")
);

// income create components
Vue.component(
    "income-entry-component",
    require("./user/components/income/IncomeCreateComponent.vue")
);

// income update components
Vue.component(
    "income-update-component",
    require("./user/components/income/IncomeUpdateComponent.vue")
);

// Manage Due
Vue.component(
    "manage-due-create",
    require("./user/components/due-management/ManageDueCreateComponent.vue")
);

// Manage Due for customer
Vue.component(
    "customer-due-manage-create",
    require("./user/components/customer-due-management/CustomerDueManageCreateComponent.vue")
);

// Edit Manage Due for customer
Vue.component(
    "customer-due-manage-edit",
    require("./user/components/customer-due-management/CustomerDueManageEditComponent.vue")
);

// Manage Due for supplier
Vue.component(
    "supplier-due-manage-create",
    require("./user/components/supplier-due-management/SupplierDueManageCreateComponent.vue")
);

// Edit Manage Due for customer
Vue.component(
    "supplier-due-manage-edit",
    require("./user/components/supplier-due-management/SupplierDueManageEditComponent.vue")
);

// product-transfer components
Vue.component(
    "product-transfer-creat-component",
    require("./user/components/product-transfer/ProductTransferCreatComponent.vue")
);
// product-transfer components
Vue.component(
    "order-confirm-component",
    require("./user/components/order-manage/OrderConfirmComponent.vue")
);

// production in create component
Vue.component(
    "production-in-create-component",
    require("./user/components/production/ProductionInCreateComponent.vue")
);
// hire sale component
Vue.component(
    "hire-sale-component",
    require("./user/components/pos/HireSaleComponent.vue")
);

// sale return component
Vue.component("sale-return-component",require("./user/components/pos/SaleReturnComponent"));

Vue.component(
    "installment-component",
    require("./user/components/installment/InstallmentComponent.vue")
);


// retail sale
Vue.component(
    "retail-sale",
    require("./user/components/pos/RetailSaleComponent.vue")
);

// pre order create component
Vue.component(
    "pre-order-create",
    require("./user/components/pre-order/PreOrderCreateComponent.vue")
);

// pre order delivery component
Vue.component(
    "pre-order-delivery",
    require("./user/components/pre-order/PreOrderDeliveryComponent.vue")
);

//supplier customer transaction
Vue.component('supplier-customer-transaction', require("./user/components/transaction/SupplierCustomerTransactionComponent.vue"))

// retail sale update
Vue.component(
    "retail-sale-update",
    require("./user/components/pos/RetailSaleUpdateComponent.vue")
);

// sale return create
Vue.component(
    "sale-return-create",
    require("./user/components/sale-return/SaleReturnCreateComponent.vue")
);

// purchase return create
Vue.component(
    "purchase-return-create",
    require("./user/components/purchase-return/PurchaseReturnCreateComponent.vue")
);

// loan cash bank create component
Vue.component('cash-bank-component', require('./user/components/loan/CashBankComponent'));
// loan cash bank update component
Vue.component('cash-bank-update-component', require('./user/components/loan/CashBankUpdateComponent'));
// Vue.component('example-component', require('./components/ExampleComponent.vue'));

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

//global event bus for vue component
window.eventBus = new Vue();

const app = new Vue({
    el: "#app",
    store
});
