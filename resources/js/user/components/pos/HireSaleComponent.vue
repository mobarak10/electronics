<template>
    <div class="pos">
        <div class="row">
            <div class="col-12">
                <div class="container-fluid">
                    <form method="POST" @submit.prevent="hireSale">
                        <div class="row ">
                            <div class="col-md-3">
                                <input type="date" class="form-control" v-model="date">
                            </div>
                            <div class="col-md-3">
                                <select v-model="warehouseId" class="form-control">
                                    <option selected disabled :value="null">Select Warehouse</option>
                                    <option
                                        v-for="(warehouse, warehouseIndex) in warehouses"
                                        :value="warehouse.id"
                                        :key="warehouseIndex"
                                    >
                                        {{ warehouse.title }}
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <v-select
                                    :options="products"
                                    v-model="selectedProduct"
                                    placeholder="Select product"
                                    @input="addToCart"
                                    label="name">
                                    <template slot="option" slot-scope="option">
                                        <span class="fa" :class="option.icon"></span>
                                        {{ option.name }}
                                    </template>
                                </v-select>

                            </div>
                            <div class="col-md-12 my-2 border-top border-bottom">
                                <table class="table table-striped table-bordered table-sm">
                                    <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>Product Name</th>
                                        <th>Model</th>
                                        <th>Serial No</th>
                                        <th class="text-right">Stock</th>
                                        <th>Quantity</th>
                                        <th>Sale Price</th>
                                        <th class="text-right">Total</th>
                                        <th class="text-right print-none">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="(product, cartIndex) in cartProducts" :key="cartIndex">
                                        <td class="text-center">1.</td>
                                        <td>{{ product.name }}</td>
                                        <td>{{ product.model }}</td>
                                        <td><input
                                            type="text"
                                            class="form-control"
                                            v-model.trim="product.serial_no">
                                        </td>
                                        <td class="text-right">{{ product.total_product_quantity }}</td>

                                        <td><input
                                            type="text"
                                            class="form-control"
                                            @blur="updateQuantity($event, cartIndex)"
                                            @change="updateQuantity($event, cartIndex)"
                                            @keyup="updateQuantity($event, cartIndex)"
                                            :value="product.saleQuantity">
                                        </td>

                                        <td><input
                                            type="text"
                                            class="form-control"
                                            v-model.trim="product.retail_price">
                                        </td>
                                        <td class="text-right">
                                            {{
                                                Number.parseFloat(
                                                    (product.total_price =
                                                        Math.ceil(parseFloat(product.saleQuantity || 0)
                                                            * parseFloat(product.retail_price || 0)))).toFixed(2)
                                            }}
                                        </td>
                                        <td class="text-right print-none">
                                            <button
                                                type="button"
                                                class="btn btn-danger"
                                                @click.prevent="cartProducts.splice(cartIndex,1)"
                                                title="remove">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </td>
                                    </tr>

                                    <tr v-if="cartProducts.length === 0">
                                        <td colspan="10" class="text-center">No product in cart</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-1"></div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <label class="col-sm-2 col-form-label">Client</label>
                                            <div class="col-sm-10">
                                                <v-select
                                                    :options="customers"
                                                    v-model="customerId"
                                                    :reduce="customer => customer.id"
                                                    placeholder="Select Client"
                                                    label="name">
                                                    <template slot="option" slot-scope="option">
                                                        <span class="fa" :class="option.icon"></span>
                                                        {{ option.name }}
                                                    </template>
                                                </v-select>
                                            </div>
                                        </div>

                                        <div class="row mt-3">
                                            <label for="model" class="col-sm-2 col-form-label">Mobile</label>
                                            <div class="col-sm-10">
                                                <input type="text" disabled class="form-control" v-model="customerMobile" id="model">
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <label for="address" class="col-sm-2 col-form-label">Address</label>
                                            <div class="col-sm-10">
                                                <textarea id="address" disabled v-model="customerAddress" rows="3" class="form-control"></textarea>
                                            </div>
                                        </div>
                                        <div class="row border-top mt-4 pt-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="" id="sms">
                                                <label class="form-check-label" for="sms">
                                                    Send sms
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-5">

                                        <div class="row">
                                            <label for="total_price" class="col-sm-2 col-form-label" >Total Price</label>
                                            <div class="col-sm-10">
                                                <input type="number" disabled :value="subTotal" class="form-control" id="total_price">
                                            </div>
                                        </div>

                                        <div class="row mt-2">
                                            <label for="down_payment" class="col-sm-2 col-form-label">Down Payment</label>
                                            <div class="col-sm-6">
                                                <input
                                                    type="number"
                                                    class="form-control"
                                                    v-model="downPayment"
                                                    id="down_payment">
                                            </div>
                                            <div class="col-sm-4">
                                                <select v-model="where" class="form-control">
                                                    <option value="cash">Cash</option>
                                                    <option value="bank">Bank</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div v-if="where === 'cash'" class="row mt-2">
                                            <label class="col-sm-2 col-form-label" >Cash Name</label>
                                            <div class="col-sm-10">
                                                <select v-model="cashId" @change="cashDetails(cashId)" class="form-control">
                                                    <option v-for="(cash, index) in cashes" :key="index" :value="cash.id">{{ cash.title }}</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div v-if="where === 'bank'">
                                            <div class="row mt-2">
                                                <label for="total_price" class="col-sm-2 col-form-label" >Bank Name</label>
                                                <div class="col-sm-10">
                                                    <select v-model="bankAccountId" @change="bankDetails(bankAccountId)" class="form-control">
                                                        <option :value="null">Choose Bank</option>
                                                        <option v-for="(account, index) in bankAccounts" :key="index" :value="account.id">{{ account.bank.name + ' (' + account.account_name + ')' }}</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row mt-2">
                                                <label for="total_price" class="col-sm-2 col-form-label" >Branch</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control" placeholder="enter branch name" v-model="branch">
                                                </div>
                                            </div>

                                            <div class="row mt-2">
                                                <label for="total_price" class="col-sm-2 col-form-label" >Cheque No</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control" placeholder="enter cheque number" v-model="chequeNo">
                                                </div>
                                            </div>

                                            <div class="row mt-2">
                                                <label for="total_price" class="col-sm-2 col-form-label" >Issue Date</label>
                                                <div class="col-sm-10">
                                                    <input type="date" class="form-control" v-model="issueDate">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-2">
                                            <label for="due" class="col-sm-2 col-form-label">Due</label>
                                            <div class="col-sm-5">
                                                <input type="number" :value="Math.abs(totalDue)" disabled class="form-control" id="due">
                                            </div>
                                            <div class="col-sm-5">
                                                <input type="text" class="form-control" :value="totalDue > 0 ? 'Receivable' : 'Payable'" placeholder="Receivable">
                                            </div>
                                        </div>

                                        <div class="row mt-2">
                                            <label for="value_add" class="col-sm-2 col-form-label">Value Add</label>
                                            <div class="col-sm-4">
                                                <input
                                                    type="number"
                                                    class="form-control"
                                                    v-model="percentageValue"
                                                    @change="updateValue"
                                                    @blur="updateValue"
                                                    @keyup="updateValue"
                                                    id="value_add">
                                            </div>
                                            <div class="col-sm-6">
                                                <input
                                                    type="number"
                                                    v-model="totalValue"
                                                    class="form-control">
                                            </div>
                                        </div>

                                        <div class="row mt-2">
                                            <label for="hire_outstanding" class="col-sm-2 col-form-label">Hire Outstanding</label>
                                            <div class="col-sm-10">
                                                <input type="number" disabled class="form-control" id="hire_outstanding" :value="
                                                    parseFloat(
                                                    parseFloat(this.subTotal || 0)
                                                    +
                                                    parseFloat(this.totalValue || 0))
                                                    .toFixed(2)
                                                "
                                                >
                                            </div>
                                        </div>

                                        <div class="row mt-2">
                                            <label for="total_amount" class="col-sm-2 col-form-label">Total Amount</label>
                                            <div class="col-sm-10">
                                                <input disabled type="number" :value="grandTotal" class="form-control" id="total_amount">
                                            </div>
                                        </div>

                                        <div class="row mt-2" v-if="grandTotal >= 0">
                                            <label for="installment_number" class="col-sm-2 col-form-label">Installment Number</label>
                                            <div class="col-sm-4">
                                                <select v-model="installmentNumber" @change="getInstallmentAmount" class="form-control" id="installment_number">
                                                    <option selected disabled :value="null">Select installment</option>
                                                    <option
                                                        v-for="installmentNumber in 15"
                                                        :value="installmentNumber"
                                                        :key="installmentNumber">
                                                        {{ installmentNumber }}
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="col-sm-6">
                                                <input type="number" v-model="installmentAmount" disabled class="form-control">
                                            </div>
                                        </div>

                                        <div class="row mt-2">
                                            <button  type="submit" class="btn btn-primary ml-auto mr-3">Sale</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</template>

<script>
import 'vue-select/dist/vue-select.css';

export default {
    name: "HireSaleComponent",
    props: {
        warehouses: Array,
        customers: Array,
        cashes: Array,
        bankAccounts: Array,
        oldHiresale: Object,
    },
    watch: {
        customerId: {
            deep: true,
            handler: function (value) {
                this.getCustomerDetails(value)
            }
        },

        warehouseId: {
            deep: true,
            handler: function (warehouse_id) {
                this.getProductByWarehouse(warehouse_id)
            }
        },

        cartProducts: {
            deep: true,
            handler: function (value) {
                this.subTotal = value.reduce((total, item) => {
                    return parseFloat(item.total_price) + total;
                }, 0);
            },
        },

        grandTotal: {
            deep: true,
            handler: 'getInstallmentAmount',
        },
    },
    computed: {
        totalDue() {
            let _due = 0;
            if (this.customerBalance > 0){
                _due = (this.subTotal - parseFloat(this.customerBalance)) - this.downPayment
                this.due = _due
                return _due
            }else{
                _due = (this.subTotal + parseFloat(Math.abs(this.customerBalance))) - this.downPayment
                this.due = _due
                return _due
            }

        },

        grandTotal() {
            return parseFloat(
                parseFloat(this.due)
                +
                parseFloat(this.totalValue ? this.totalValue : 0))
                .toFixed(2)
        },
    },
    data() {
        return{
            warehouseId: null,
            date: new Date().toISOString().slice(0, 10),
            products: [],
            due: 0,
            totalValue: 0,
            valueAdd: null,
            cashId: null,
            where: 'cash',
            subTotal: 0,
            branch: null,
            bkashNo: null,
            chequeNo: null,
            selectedProduct: null,
            issueDate: new Date().toISOString().slice(0, 10),
            customerId: null,
            totalAmount: null,
            percentageValue: null,
            downPayment: 0,
            installmentNumber: null,
            installmentAmount: null,
            hireOutstanding: null,
            bankAccountId: null,
            customerMobile: null,
            customerBalance: 0,
            customerAddress: null,
            selectedWarehouse:null,
            cartProducts: [],
        }
    },

    methods: {
        getProductByWarehouse(id){
            let warehouse = this.warehouses.find(warehouse => warehouse.id === id)
            this.products = warehouse.products
        },

        cashDetails() {
            this.bankAccountId = null
            this.bkashNo = null
        },

        bankDetails() {
            this.cashId = null
            this.bkashNo = null
        },

        loadOldHireSaleData() {
            this.date = this.oldHiresale.date.slice(0, 10)
            this.loadOldSelectedParty()
            this.loadCartProducts()
            this.warehouse_id = this.oldHiresale.warehouse_id
            this.installmentNumber = this.oldHiresale.installment_number
            this.totalValue = this.oldHiresale.added_value
            this.downPayment = this.oldHiresale.down_payment
            this.where = this.oldHiresale.hire_sale_payment.payment_method

            if(this.oldHiresale.hire_sale_payment.payment_method == 'cash'){
                this.cashId = this.oldHiresale.hire_sale_payment.cash_id
            }else {
                this.bankAccountId = this.oldHiresale.hire_sale_payment.bank_account_id
            }
        },
        loadOldSelectedParty() {
            this.customerId = this.oldHiresale.customer_id
        },

        loadCartProducts() {
            this.cartProducts = this.oldHiresale.hire_sale_products.map((details) => {
                details['name'] = details.product.name
                details['model'] = details.product.model
                details['serial_no'] = details.product_serial
                details['saleQuantity'] = details.quantity
                details['retail_price'] = details.sale_price
                details['total_price'] = details.line_total
                details['id'] = details.product_id
                return details;
            })
        },

        addToCart(value){
            const index = this.cartProducts.findIndex(
                product => product.id === value.id
            );

            if (index === -1) {
                this.cartProducts.push(value);
                const newProduct = {
                    ...value,
                    serial_no: null,
                    saleQuantity: 1,
                    total_price: value.retail_price,
                };

                this.cartProducts.splice(
                    this.cartProducts.length - 1,
                    1,
                    newProduct
                );

                this.selectedProduct = null;
            } else {
                this.$awn.warning(value.name + ' already added in cart')
            }
        },

        getInstallmentAmount() {
            this.installmentAmount = Number.parseFloat(this.grandTotal/this.installmentNumber).toFixed(2)
        },

        getCustomerDetails(id) {
            let customer = this.customers.find(customer => customer.id === id)
            this.customerMobile = customer.phone
            this.customerAddress = customer.address
        },

        updateQuantity(event, index) {
            const previousCartProducts = {...this.cartProducts[index]};
            previousCartProducts.saleQuantity = event.target.value;
            this.cartProducts.splice(index, 1, previousCartProducts)
        },

        updateValue() {
            let valueForDownPayment = this.subTotal - this.downPayment
            this.totalValue = ((valueForDownPayment * this.percentageValue) / 100)
        },

        hireSale(){
            this.getInstallmentAmount()
            if (this.cartProducts.length === 0) {
                this.$awn.warning('Cart is empty!')
                return
            }
            if (!this.customerId) {
                this.$awn.warning('Please select customer!')
                return
            }

            if (!this.cashId && !this.bkashNo && !this.bankAccountId) {
                this.$awn.warning('Please select payment method!')
                return
            }

            if (this.grandTotal > 0 && !this.installmentNumber){
                this.$awn.warning('Please enter installment number!')
                return
            }

            const form = {
                ...this.form,
                products: []
            };

            // for products
            let quantityError = false;
            this.cartProducts.forEach(product => {
                if (product.saleQuantity <= 0) {
                    quantityError = true
                    product.error = "Quantity can\'t be empty"
                }else{
                    quantityError = false
                    product.error = ''
                }
                form.products.push({
                    id: product.id,
                    quantity: product.saleQuantity,
                    quantity_in_unit: product.quantity_in_unit,
                    sale_price: product.retail_price,
                    purchase_price: product.purchase_price,
                    line_total: product.total_price
                });
            });

            if (quantityError) {
                form.products = []
                return
            }

            form.date = this.date;
            form.due = this.due;
            form.added_value = this.totalValue;
            form.cash_id = this.cashId;
            form.where = this.where;
            form.subtotal = this.subTotal;
            form.branch = this.branch;
            form.bkash_no = this.bkashNo;
            form.check_no = this.chequeNo;
            form.issue_date = this.issueDate;
            form.party_id = this.customerId;
            form.down_payment = this.downPayment;
            form.installment_number = this.installmentNumber;
            form.installment_amount = this.installmentAmount;
            form.bank_account_id = this.bankAccountId;
            form.warehouse_id = this.warehouseId;

            if (this.oldHiresale) {
                return this.proceedToUpdateHiresale(form)
            }

            return this.proceedToNewHiresale(form)
        },

        proceedToUpdateHiresale(data) {
            this.$awn.asyncBlock(
                axios.post(baseURL + "user/hire-sale/" + this.oldHiresale.id, data),
                response => {
                    console.log(response.data)
                }
            );
        },
        proceedToNewHiresale(data) {
            this.$awn.asyncBlock(
                axios.post(baseURL + "user/hire-sale", data),
                response => {
                    console.log(response.data)
                }
            );
        },
    },

    mounted() {
        this.cashId = this.cashes[0].id
        this.warehouseId = this.warehouses[0].id
        if (this.oldHiresale) {
            this.loadOldHireSaleData()
        }
    }
}
</script>

<style scoped>

</style>
