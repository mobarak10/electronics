    /**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/*
* add base url into vue
* */
Vue.prototype.baseURL = document.head.querySelector('meta[name="base-url"]').content + '/';
window.baseURL = document.head.querySelector('meta[name="base-url"]').content + '/';



window.Chart = require('chart.js');
require("chart.js/dist/Chart.min.css");

/**
 * Vuex initialization
 */


import store from './admin/store/index'
Vue.use(store)

// Vue awesome notification
import VueAWN from "vue-awesome-notifications";
require("vue-awesome-notifications/dist/styles/style.css");
Vue.use(VueAWN);

// Global install as a vue plugin
// import vSuggest from 'v-suggest'
// Vue.use(vSuggest)
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
Vue.component('passport-clients', require('./components/passport/Clients.vue'));
Vue.component('passport-authorized-clients', require('./components/passport/AuthorizedClients.vue'));
Vue.component('passport-personal-access-tokens', require('./components/passport/PersonalAccessTokens.vue'));

// Admin Component
Vue.component('pos-component', require('./admin/components/pos/POSContainerComponent.vue'));
Vue.component('pos-checkout-component', require('./admin/components/pos/POSCheckoutContainerComponent.vue'));
Vue.component('sale-return-component', require('./admin/components/pos/SaleReturnContainerComponent.vue'));
Vue.component('product-purchase-component', require('./admin/components/purchase/PurchaseProductComponent.vue'))
Vue.component('product-purchase-return-container-component', require('./admin/components/purchase/PurchaseReturnContainerComponent.vue'))
Vue.component('create-product-component', require('./admin/components/product/CreateProductComponent.vue'));
Vue.component('product-search-company-brand-category', require('./admin/components/product/search-form/CompanyBrandCategoryComponent.vue'));
Vue.component('update-product-component', require('./admin/components/product/UpdateProductComponent.vue'));
Vue.component('bank-transaction-component', require('./admin/components/banking/TransactionComponent.vue'));

// business report
Vue.component('business-warehouse-component', require('./admin/components/report/search-form/BusinessWarehouseReportComponent'));
Vue.component('business-expenditure-component', require('./admin/components/report/search-form/BusinessExpenditureReportComponent'));

// gl account head component
Vue.component('create-gl-account-head-component', require('./admin/components/glAccountHead/CreateGLAccountHeadComponent.vue'));
Vue.component('update-gl-account-head-component', require('./admin/components/glAccountHead/UpdateGLAccountHeadComponent.vue'));

// expense components
Vue.component('expense-entry-component', require('./components/expense/ExpenseCreateComponent.vue'));

// supplier-due-management components
Vue.component('supplier-due-manage-component', require('./admin/components/supplierDueManagement/SupplierDueManagementComponent.vue'));

// Manage Due
Vue.component('manage-due-create', require('./components/due-management/ManageDueCreateComponent.vue'));

// create salary component
Vue.component('create-salary', require('./admin/components/payroll/CreateSalaryComponent'));
Vue.component('selected-user-salary', require('./admin/components/payroll/SelectedUserSalaryComponent'));

// Vue.component('example-component', require('./components/ExampleComponent.vue'));

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

//global event bus for vue component
window.eventBus = new Vue()


const app = new Vue({
    el: '#app',
    store
});
