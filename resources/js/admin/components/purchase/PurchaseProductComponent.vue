<template>
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="form-row">
                        <div class="form-group col-md-6 required">
                            <label for="date">Date</label>
                            <input type="date" v-model="date" name="date" class="form-control" id="date" placeholder="Pick a date">
                        </div>
                        <div class="form-group col-md-6 required">
                            <label for="voucher-no">Voucher No</label>
                            <input type="text" v-model="voucherNo" name="voucher_no" class="form-control" id="voucher-no" placeholder="Enter voucher no">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6 required">
                            <label for="supplier">Supplier</label>
                            <select name="party_id" v-model="supplierId" @change="loadDetailsOfSupplier" id="supplier" class="form-control">
                                <option :value="null" disabled>Select Supplier</option>
                                <option v-for="(supplier, supplierIndex) in suppliers" :key="supplierIndex" :value="supplier.id">{{ supplier.name }}</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6 required">
                            <label for="product">Product</label>
                            <!-- <v-suggest :data="list" show-field="name"></v-suggest> -->
                            <select name="product_id" v-model="productId" id="product" class="form-control">
                                <option :value="null" disabled>Select Product</option>
                                <option v-for="(product, productIndex) in products" :key="productIndex" :value="product.id">{{ product.name }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="text-right my-2">
                        <button type="button" role="button" class="btn btn-primary" @click="setQuantityModal">Set Quantity</button>
                    </div>

                    <div class="form-row" v-if="cartContents.length !== 0">
                        <div class="col-sm-12">
                            <table class="table table-bordered table-sm table-responsive-md">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Details</th>
                                    <th>Quantity</th>
                                    <th class="text-right">Purchase Price (BDT)</th>
                                    <th class="text-right">Retail Price (BDT)</th>
                                    <th class="text-right">Wholesale Price (BDT)</th>
                                    <th class="text-right">Line Total (BDT)</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="(item, itemIndex, _itemIndex) in cartContents" :key="itemIndex">
                                    <td>{{ _itemIndex + 1 }}</td>
                                    <td>
                                        {{ item.name }}
                                        <small class="d-block">{{ item.attributes.meta.product.model }}</small>
                                    </td>
                                    <td>
                                        {{ item.attributes.meta.total_quantity.display }}
                                    </td>
                                    <td class="text-right">
                                        {{ parseFloat(item.attributes.meta.request.product_purchase_price).toFixed(2) }}
                                    </td>
                                    <td class="text-right">
                                        {{ parseFloat(item.attributes.meta.request.product_retail_price).toFixed(2) }}
                                    </td>
                                    <td class="text-right">
                                        {{ parseFloat(item.attributes.meta.request.product_wholesale_price).toFixed(2) }}
                                    </td>
                                    <td class="text-right">{{ item.attributes.price.priceSumWithConditions.toFixed(2) }}</td>
                                    <td>
                                        <a href="#" class="btn btn-info btn-sm" @click.prevent="editCartProduct(itemIndex)">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a href="#" class="btn btn-danger btn-sm" @click.prevent="removeCartItem(itemIndex)">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="8"></td>
                                </tr>
                                <tr>
                                    <td class="text-right" colspan="6">Subtotal</td>
                                    <td class="text-right">{{ parseFloat(subtotal).toFixed(2) }}</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="text-right" colspan="6"><label for="discount">Discount</label></td>
                                    <td class="text-right">
                                        <input type="number" v-model="discount" placeholder="0.00" id="discount" class="form-control text-right">
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="text-right" colspan="6">Supplier's Balance</td>
                                    <td class="text-right" :class="{'text-success' : supplier.balance > 0, 'text-danger' : supplier.balance < 0}">{{ (supplier.balance) ? parseFloat(supplier.balance).toFixed(2) : 'No supplier selected'}}</td>
                                    <td>{{ supplierBalanceStats }}</td>
                                </tr>
                                <tr>
                                    <td class="text-right" colspan="6">Grand Total</td>
                                    <td class="text-right" :class="{'text-success' : grandTotal > 0, 'text-danger' : grandTotal < 0}">{{ grandTotal }}</td>
                                    <td>{{ grandTotalStats }}</td>
                                </tr>
                                <tr>
                                    <td class="text-right" colspan="6">
                                        {{ payment.paymentFormText }}
                                    </td>
                                    <td class="text-right">
                                        <span v-if="payment.amount">{{ parseFloat(payment.amount).toFixed(2) }}</span>
                                    </td>
                                    <td>
                                        <div v-if="payment.amount">
                                            <button type="button" role="button" class="btn btn-primary btn-block mb-1" @click="changePaymentModal">Change</button>
                                            <button type="button" role="button" class="btn btn-danger btn-block" @click="clearPaymentMethod">Clear</button>
                                        </div>
                                        <button type="button" role="button" v-else class="btn btn-primary" @click="setPaymentModal">Select Method</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-right" colspan="6">
                                        Supplier's current balance
                                    </td>
                                    <td class="text-right" :class="{'text-success' : supplierCurrentBalance > 0, 'text-danger' : supplierCurrentBalance < 0}">
                                        <span>{{ supplierCurrentBalance }}</span>
                                    </td>
                                    <td>{{ supplierCurrentBalanceStats }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="note">Note</label>
                        <textarea class="form-control" v-model="note" id="note" rows="3" placeholder="Enter note"></textarea>
                    </div>
                    <div class="form-group text-right">
                        <button type="button" @click="clearCart" class="btn btn-danger">Clear Cart</button>
                        <button type="submit" @click.prevent="proceedPurchase" class="btn btn-success">Purchase</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quantity Modal -->
        <div class="modal fade" id="quantityModal" ref="quantityModal" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Select Quantity</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" v-if="quantityModalData">
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="purchase-price">Purchase Price</label>
                                <div class="input-group">
                                    <input type="number" v-model="quantityModalData.purchase_price" step="any" class="form-control" id="purchase-price" placeholder="Enter purchase price">
                                    <div class="input-group-append" v-if="quantityModalData.product_unit_labels">
                                        <span class="input-group-text">{{ quantityModalData.product_unit_labels[quantityModalData.product_unit_labels.length - 1] }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="retail-price">Retail Price</label>
                                <div class="input-group">
                                    <input type="number" v-model="quantityModalData.retail_price" step="any" class="form-control" id="retail-price" placeholder="Enter retail price">
                                    <div class="input-group-append" v-if="quantityModalData.product_unit_labels">
                                        <span class="input-group-text">{{ quantityModalData.product_unit_labels[quantityModalData.product_unit_labels.length - 1] }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="wholesale-price">Wholesale Price</label>
                                <div class="input-group">
                                    <input type="number" v-model="quantityModalData.wholesale_price" step="any" class="form-control" id="wholesale-price" placeholder="Enter wholesale price">
                                    <div class="input-group-append" v-if="quantityModalData.product_unit_labels">
                                        <span class="input-group-text">{{ quantityModalData.product_unit_labels[quantityModalData.product_unit_labels.length - 1] }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" v-if="quantityModalData.product_unit_labels">
                            <div class="col-md-3" v-for="(warehouse, warehouseIndex) in warehouses" :key="warehouseIndex">
                                <h5 class="card-title mb-0">{{ warehouse.title }}</h5>
                                <small class="card-subtitle text-muted">{{ (quantityModalData.warehouses.find( productWarehouse => (productWarehouse.id === warehouse.id))) ? 'Available in stock: ' + quantityModalData.warehouses.find( productWarehouse => (productWarehouse.id === warehouse.id)).product_quantity_in_unit.display : 'Stock is not available in this warehouse' }}</small>
                                <div class="input-group mb-3" v-for="(label, labelIndex) in quantityModalData.product_unit_labels" :key="labelIndex">
                                    <input
                                        type="number"
                                        v-if="quantities[warehouse.id]"
                                        class="form-control"
                                        :value="quantities[warehouse.id][labelIndex]"
                                        @keyup="addQuantity($event, warehouse.id, labelIndex)"
                                        @blur="addQuantity($event, warehouse.id, labelIndex)"
                                        @change="addQuantity($event, warehouse.id, labelIndex)"
                                    />
                                    <input
                                        v-else
                                        type="number"
                                        class="form-control"
                                        @keyup="addQuantity($event, warehouse.id, labelIndex)"
                                        @blur="addQuantity($event, warehouse.id, labelIndex)"
                                        @change="addQuantity($event, warehouse.id, labelIndex)"
                                    />
                                    <div class="input-group-append">
                                        <span class="input-group-text">{{ label }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" v-if="errors.quantities">
                            <div class="col-12 text-danger">
                                <small><strong>Error: </strong> {{ errors.quantities[0] }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                        <button type="button" @click="addToCart" class="btn btn-success">Add to Cart</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Modal -->
        <div class="modal fade" id="paymentModal" ref="paymentModal" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form @submit.prevent="submitPaymentMethod">
                        <div class="modal-header">
                            <h5 class="modal-title">Select Payment Method</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="form-group col-12">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" @change="paymentMethodChange" v-model="payment.selectedMethod" id="payment-method-cash" value="cash">
                                        <label class="form-check-label" for="payment-method-cash">Cash</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" @change="paymentMethodChange" v-model="payment.selectedMethod" id="payment-method-bank" value="bank">
                                        <label class="form-check-label" for="payment-method-bank">Bank</label>
                                    </div>
                                </div>
                                <div class="form-group col-12 required" v-if="payment.selectedMethod === 'cash'">
                                    <label for="payment-cash">Cash</label>
                                    <select class="form-control" @change="getCashBalance" v-model="payment.selectedCash" id="payment-cash">
                                        <option :value="null" disabled>Select Cash</option>
                                        <option v-for="(_cash, cashIndex) in payment.cashes" :value="_cash.id" :key="cashIndex">{{ _cash.title }}</option>
                                    </select>
                                </div>
                                <div class="form-group col-12" v-else>
                                    <div class="form-row">
                                        <div class="col-md-6 required">
                                            <label for="payment-bank">Bank</label>
                                            <select class="form-control" id="payment-bank" v-model="payment.selectedBank" @change="getBankAccounts">
                                                <option :value="null" disabled>Select Bank</option>
                                                <option v-for="(_bank, index) in payment.banks" :value="_bank.id" :key="index">{{ _bank.name }}</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 required">
                                            <label for="payment-bank-account">Bank Account</label>
                                            <select class="form-control" id="payment-bank-account" v-model="payment.selectedBankAccount" @change="getBankAccountBalance">
                                                <option :value="null" disabled>Select Account</option>
                                                <option v-for="(_account, index) in payment.bankAccounts" :value="_account.id" :key="index">{{ _account.account_name }} ({{ _account.account_number }})</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <label for="payment-check-number">Check Number</label>
                                            <input type="text" id="payment-check-number" v-model="payment.checkNo" class="form-control">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="payment-issue-date">Issue Date</label>
                                            <input type="date" id="payment-issue-date" v-model="payment.issueDate" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-12">
                                    <div v-if="payment.balance !== null">
                                        <p class="bg-dark text-white p-2">Current Balance <strong>BDT {{ payment.balance }}</strong></p>
                                    </div>
                                    <div class="required">
                                        <label for="payment-amount">Amount</label>
                                        <input type="number" id="payment-amount" v-model="payment.rawAmount" placeholder="Enter payment amount" step="any" min="0" :max="payment.balance" required :readonly="!payment.balance" class="form-control">
                                        <small class="form-text text-muted" v-if="payment.balance">* Amount must be less than or equal current balance ({{ payment.balance }})</small>
                                        <small class="form-text text-muted" v-else>* Select Cash or Bank Account</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success">Select</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    // import { Suggest } from 'v-suggest'
    export default {
        // components: {
        //     'v-suggest': Suggest
        //   },
        name: "PurchaseProductComponent",
        watch: {
            productId: {
                handler: function (productId) {
                    this.product = this.products.find(product => product.id === productId)
                }
            },
            supplierId: {
                handler: 'loadDetailsOfSupplier'
            }
        },
        computed: {
            supplierBalanceStats(){
                let status = ''
                if(this.supplier.balance < 0){
                    status = 'Payable'
                }else if(this.supplier.balance > 0){
                    status = 'Receivable'
                }
                return status
            },
            grandTotal() {
                //return  this.supplier.balance;
                return parseFloat(parseFloat(this.supplier.balance) - (parseFloat(this.subtotal) - this.discount)).toFixed(2)
            },
            grandTotalStats(){
                let status = ''
                if(this.grandTotal < 0){
                    status = 'Payable'
                }else if(this.grandTotal > 0){
                    status = 'Receivable'
                }
                return status
            },
            supplierCurrentBalance(){
                if(this.payment.amount === null){
                    return  parseFloat(this.grandTotal).toFixed(2)
                }
                return (parseFloat(this.grandTotal) + parseFloat(this.payment.amount))
            },
            supplierCurrentBalanceStats(){
                let status = ''
                if(this.supplierCurrentBalance < 0){
                    status = 'Payable'
                }else if(this.supplierCurrentBalance > 0){
                    status = 'Receivable'
                }
                return status
            },
        },
        data(){
            return {
                // list: [
                //         { id: 1, name: 'Chicago Bulls', desc: '芝加哥公牛' },
                //         { id: 2, name: 'Cleveland Cavaliers', desc:'克里夫兰骑士' },
                //       ],
                errors: {},
                date: null,
                voucherNo: null,
                note: null,
                suppliers: [],
                supplierId: null,
                supplier: {},
                products: [],
                product: {},
                productId: null,
                warehouses: [],
                quantityModal: {},
                paymentModal: {},
                quantityModalData: {},
                quantities: {},
                cartContents: [],
                subtotal: null,
                discount: null,
                payment: {
                    cashes: [],
                    selectedMethod: 'cash',
                    selectedCash: null,
                    balance: null,
                    banks: [],
                    selectedBank: null,
                    bankAccounts: [],
                    selectedBankAccount: null,
                    issueDate: null,
                    checkNo: null,
                    rawAmount: null,
                    amount: null,
                    paymentFormText: ''
                }
            }
        },
        methods: {
            editCartProduct(itemIndex){
                let cartItem = this.cartContents[itemIndex]

                this.quantityModalData = {
                    id: cartItem.id,
                    purchase_price: cartItem.price,
                    retail_price: cartItem.attributes.meta.request.product_retail_price,
                    wholesale_price: cartItem.attributes.meta.request.product_wholesale_price,
                    product_unit_labels: cartItem.attributes.meta.product.product_unit_labels,
                    warehouses: cartItem.attributes.meta.product.warehouses,
                }

                this.quantities = cartItem.attributes.meta.request.quantities

                this.showQuantityModal()
            },
            init(){
                this.allActiveSuppliers()
            },
            setSessionData(data){
                this.date = data.date || null
                this.voucherNo = data.voucher_no || null
                this.supplierId = data.supplier_id || null
                this.note = data.note || null
            },
            setCartPrice(data){
                this.subtotal = data.subtotal || null
            },
            getCartContents(){
                this.$awn.asyncBlock(
                    axios.post(baseURL + 'admin/purchase/get-cart-contents'),
                    response => {
                        this.cartContents = response.data
                    },
                    reason => console.log(reason)
                )
            },
            setQuantityModal(){
                if(!this.supplierId){
                    alert('Select Supplier')
                    return
                }

                if(!this.productId){
                    alert('Select Product')
                    return
                }

                this.quantityModalData = this.product

                this.showQuantityModal()
            },
            addQuantity(event, warehouseId, order){
                if (!(warehouseId in this.quantities)) {
                    this.$set(this.quantities, warehouseId, {})
                }
                this.$set(this.quantities[warehouseId], order, event.target.value )
            },
            showQuantityModal(){
                this.quantityModal.modal('show')
            },
            hideQuantityModal(){
                this.quantityModal.modal('hide')
            },
            showPaymentModal(){
                this.paymentModal.modal('show')
            },
            hidePaymentModal(){
                this.paymentModal.modal('hide')
            },
            resetQuantityModal(){
                this.errors = {}
                this.productId = null
                this.quantities = {}
                this.quantityModalData = {}
            },
            allActiveSuppliers(){ //load all active suppliers
                axios.all([
                    axios.post(baseURL + 'get-all-active-suppliers'),
                    axios.post(baseURL + 'get-all-products'),
                    axios.post(baseURL + 'get-all-active-warehouses'),
                    axios.post(baseURL + 'admin/purchase/get-cart-contents'),
                    axios.post(baseURL + 'get-all-cashes'),
                    axios.post(baseURL + 'get-all-banks'),
                ])
                    .then(axios.spread((suppliers, products, warehouses, cartContents, cashes, banks) => {
                        this.suppliers = suppliers.data
                        this.products = products.data
                        this.warehouses = warehouses.data
                        this.cartContents = cartContents.data.purchase_items
                        this.setSessionData(cartContents.data.input)
                        this.setCartPrice(cartContents.data.purchase_items_prices)
                        this.payment.cashes = cashes.data;
                        this.payment.banks = banks.data;
                    }))
                    .catch(error => console.log(error))
            },
            loadDetailsOfSupplier(){
                axios.post(baseURL + 'get-details-from-party', {
                    id: this.supplierId
                })
                    .then(response => {
                        this.supplier = response.data
                    })
                    .catch(reason => console.log(reason))
            },
            addToCart(){
                this.errors = {}
                this.$awn.asyncBlock(
                    axios.post(baseURL + 'admin/purchase/add-to-cart', {
                        date: this.date,
                        product_id: this.quantityModalData.id,
                        voucher_no: this.voucherNo,
                        note: this.note,
                        supplier_id : this.supplierId,
                        product_purchase_price:  this.quantityModalData.purchase_price,
                        product_retail_price:  this.quantityModalData.retail_price,
                        product_wholesale_price:  this.quantityModalData.wholesale_price,
                        quantities: this.quantities
                    }),
                    response => {
                        //console.log(response.data)
                        this.cartContents = response.data.purchase_items
                        this.setSessionData(response.data.input)
                        this.setCartPrice(response.data.purchase_items_prices)
                        this.hideQuantityModal()
                    },
                    reason => {
                        if(reason.response.status === 422){
                            this.errors = reason.response.data.errors
                        }else{
                            this.$awn.alert('Opps! Something went wrong')
                        }
                    }
                )
            },
            removeCartItem(itemIndex){
                if (!confirm('Are you sure want to remove this item?')) return;

                let cartItem = this.cartContents[itemIndex]
                this.$awn.asyncBlock(
                    axios.post(baseURL + 'admin/purchase/remove-cart-item', {
                        product_id: cartItem.id
                    }),
                    response => {
                        this.cartContents = response.data.purchase_items
                        this.setSessionData(response.data.input)
                        this.setCartPrice(response.data.purchase_items_prices)
                    },
                    reason => console.log(reason)
                )
            },
            clearCart(){
                this.$awn.asyncBlock(
                    axios.post(baseURL + 'admin/purchase/clear-cart-contents'),
                    response => {
                        location.reload(true)
                    },
                    reason => {
                        console.log(reason)
                    }
                )
            },
            setPaymentModal(){
                this.showPaymentModal()
            },
            getCashBalance(){
                this.$awn.asyncBlock(
                    axios.post(baseURL + 'get-details-from-cash', {
                        id: this.payment.selectedCash
                    }),
                    response => {
                        this.payment.balance = response.data.amount // cash balance
                    },
                    reason => console.log(reason)
                )
            },
            getBankAccounts(){
                this.payment.selectedBankAccount = null
                this.payment.balance = null

                this.$awn.asyncBlock(
                    axios.post(baseURL + 'get-accounts-from-bank', {
                        id: this.payment.selectedBank
                    }),
                    response => {
                        this.payment.bankAccounts = response.data // bank accounts
                    },
                    reason => console.log(reason)
                )
            },
            getBankAccountBalance(){
                this.$awn.asyncBlock(
                    axios.post(baseURL + 'get-details-from-account', {
                        id: this.payment.selectedBankAccount
                    }),
                    response => {
                        this.payment.balance = response.data.balance
                    },
                    reason => console.log(reason)
                )
            },
            resetPaymentModal(){
                this.payment.selectedCash = null
                this.payment.balance = null
                this.payment.rawAmount = null
                this.payment.selectedBank = null
                this.payment.bankAccounts = []
                this.payment.selectedBankAccount = null
            },
            submitPaymentMethod(){
                if(!this.payment.balance) {
                    alert('Select Cash or Bank Account')
                    return;
                }
                if(this.payment.rawAmount <= 0) {
                    alert('Amount must be greater than 0')
                    return;
                }

                if(this.payment.selectedMethod === 'cash'){
                    this.payment.paymentFormText = 'From cash ' + this.payment.cashes.find(cash => cash.id === this.payment.selectedCash).title
                }else { // 'bank account'
                    let bank = this.payment.banks.find(bank => bank.id === this.payment.selectedBank);
                    let bankAccount = this.payment.bankAccounts.find(account => account.id === this.payment.selectedBankAccount);
                    this.payment.paymentFormText = `From ${bankAccount.account_name} (${bank.name})`


                }
                this.payment.amount = this.payment.rawAmount
                this.hidePaymentModal()

            },
            changePaymentModal(){
                this.showPaymentModal()
            },
            paymentMethodChange(){
                this.resetPaymentModal()
            },
            proceedPurchase(){

                // date validation
                if (!this.date){
                    alert('Enter Date')
                    return;
                }

                // voucher validation
                if (!this.voucherNo){
                    alert('Enter Voucher Number')
                    return;
                }

                // supplier id validation
                if (!this.supplierId){
                    alert('Select supplier')
                    return;
                }

                this.$awn.asyncBlock(
                    axios.post(baseURL + 'admin/purchase', {
                        date: this.date,
                        product_id: this.quantityModalData.id,
                        voucher_no: this.voucherNo,
                        note: this.note,
                        supplier_id : this.supplierId,
                        discount: this.discount,
                        payment: {
                            selectedMethod: this.payment.selectedMethod,
                            selectedCash: this.payment.selectedCash,
                            selectedBank: this.payment.selectedBank,
                            selectedBankAccount: this.payment.selectedBankAccount,
                            issueDate: this.payment.issueDate,
                            checkNo: this.payment.checkNo,
                            amount: this.payment.amount
                        }
                    }),
                    response => {
                        //redirect to purchase details page
                        window.location.href = baseURL + 'admin/purchase/' + response.data.id
                    },
                    reason => console.log(reason)
                )
            },
            clearPaymentMethod(){
                this.resetPaymentModal()
                this.payment.amount = null
                this.payment.paymentFormText = ''
            }
        },
        mounted() {
            this.quantityModal = $(this.$refs.quantityModal)
            this.paymentModal = $(this.$refs.paymentModal)
            this.init()
            //this.showPaymentModal()
            this.quantityModal.on('hidden.bs.modal', () => this.resetQuantityModal())
            //this.paymentModal.on('hidden.bs.modal', () => this.resetPaymentModal())
        }
    }
</script>

<style scoped>
table td, table th{
    padding: 5px;
}
</style>
