<template>
    <div class="pos">
        <div class="row">
            <div class="col-12">
                <div class="container-fluid">
                    <form action="" @submit.prevent="preOrder">
                        <!-- Top Form Start-->
                        <div class="row mt-3">
                            <div class="col-md-4">
                                <input
                                    type="date"
                                    class="form-control form-control-sm"
                                    v-model="form.date"
                                />
                            </div>

                            <div class="col-md-4">
                                <select
                                    :disabled="disableWarehouse"
                                    class="form-control form-control-sm"
                                    v-model="warehouse_id">
                                    <option :value="null" disabled>Select warehouse</option>
                                    <option
                                        v-for="(warehouse,
                                        warehouseIndex) in warehouses"
                                        :value="warehouse.id"
                                        :key="warehouseIndex"
                                        v-text="warehouse.title"
                                    />
                                </select>
                            </div>

                            <div class="col-md-4">
                                <v-select
                                    :options="products"
                                    label="name"
                                    v-model="selectedProduct"
                                    placeholder="Select product"
                                    @input="onProductSelected"
                                    class="bg-white">
                                </v-select>
                            </div>
                        </div>
                        <!-- Top Form End -->

                        <!-- Table Form Start -->
                        <div class="row">
                            <div class="my-2 col-12 border-top border-bottom">
                                <table class="table my-2 table-striped table-bordered table-sm">
                                    <thead>
                                    <tr>
                                        <th class="text-center">{{ lang.sl }}</th>
                                        <th style="min-width: 180px">{{ lang.product_name }}</th>
                                        <th class="text-right">{{ lang.stock }}</th>
                                        <th>{{ lang.order }} {{ lang.quantity }}</th>
                                        <th>{{ lang.delivery }} {{ lang.quantity }}</th>
                                        <th>{{ lang.sale }} {{ lang.price }}</th>
                                        <th class="text-right">{{ lang.total }}</th>
                                        <th class="text-center print-none">
                                            {{ lang.action }}
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-if="selectedProducts.length === 0">
                                        <td colspan="20" class="text-center">
                                            {{ lang.no_product_selected }}
                                        </td>
                                    </tr>

                                    <tr
                                        v-for="(product,productIndex) in selectedProducts"
                                        :key="productIndex">
                                        <td>{{ productIndex + 1 }}.</td>
                                        <td>{{ product.name }}</td>
                                        <td class="text-right">
                                            <span
                                                :class="product.total_product_quantity >= 0 ? 'text-success' : 'text-danger'">
                                                {{ product.display_quantity }}
                                            </span>
                                        </td>

                                        <!-- Pre Order Quantity -->
                                        <td>
                                            <div class="input-group">
                                                <input
                                                    type="number"
                                                    aria-describedby="quantityError"
                                                    v-for="(label,labelIndex) in product.product_unit_labels"
                                                    :placeholder="label"
                                                    :key="labelIndex"
                                                    :value="product.quantity[labelIndex]"
                                                    @blur="addQuantity($event, product.id, labelIndex)"
                                                    @change="addQuantity($event, product.id, labelIndex)"
                                                    @keyup="addQuantity($event, product.id, labelIndex)"
                                                    min="0"
                                                    class="form-control form-control-sm"
                                                />
                                            </div>

                                            <div v-if="product.quantityError" id="saleQuantityError"
                                                 class="form-text text-wrap text-danger">
                                                {{ product.quantityError }}
                                            </div>
                                        </td>
                                        <!-- Pre Order Quantity -->

                                        <!-- Delivery Quantity -->
                                        <td>
                                            <div class="input-group">
                                                <input
                                                    type="number"
                                                    aria-describedby="quantityError"
                                                    v-for="(label,labelIndex) in product.product_unit_labels"
                                                    :placeholder="label"
                                                    :key="labelIndex"
                                                    :value="product.deliveryQuantityInUnit[labelIndex]"
                                                    @blur="addDeliveryQuantity($event, product.id, labelIndex)"
                                                    @change="addDeliveryQuantity($event, product.id, labelIndex)"
                                                    @keyup="addDeliveryQuantity($event, product.id, labelIndex)"
                                                    min="0"
                                                    class="form-control form-control-sm"
                                                />
                                            </div>

                                            <div v-if="product.deliveryError" id="deliveryQuantityError"
                                                 class="form-text text-wrap text-danger">
                                                {{ product.deliveryError }}
                                            </div>
                                        </td>
                                        <!-- Delivery Quantity -->

                                        <td>
                                            <!-- Sale Price -->
                                            <input
                                                type="number"
                                                step="any"
                                                class="form-control form-control-sm"
                                                v-model.trim="product.sale_price"
                                            />
                                        </td>

                                        <td class="text-right">
                                            <div>
                                                {{
                                                    Number.parseFloat(
                                                        (product.total_price =
                                                            parseFloat(product.sale_quantity || 0)
                                                            * parseFloat(product.sale_price || 0))).toFixed(2)
                                                }}
                                            </div>
                                        </td>

                                        <td class="text-center">
                                            <button
                                                class="btn btn-sm btn-danger"
                                                type="button"
                                                @click.prevent="selectedProducts.splice(productIndex,1)">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- Table Form End -->

                        <!-- Bottom Section Start -->
                        <div class="row">
                            <div class="col-12">
                                <div class="row">
                                    <!-- Bottom Left Section Start -->
                                    <div class="col-md-6">
                                        <!-- Client Select Start -->
                                        <div>
                                            <div class="form-group row required">
                                                <label class="col-md-3 col-form-label"
                                                       for="client-name">{{ lang.customer_name }}</label>
                                                <div class="col-md-9">
                                                    <v-select
                                                        id="client-name"
                                                        :options="searchCustomer"
                                                        v-model="customerId"
                                                        :reduce="customer => customer.id"
                                                        placeholder="Select Client"
                                                        @input="getCustomerDetails(customerId)"
                                                        label="custom_name">
                                                        <template slot="option" slot-scope="option">
                                                            <span class="fa" :class="option.icon"></span>
                                                            {{ option.name + ' ' + (option.phone || '') }}
                                                        </template>
                                                    </v-select>
                                                </div>
                                            </div>
                                            <!-- Client Select End -->

                                            <!-- Mobile Number Start -->
                                            <div class="form-group row">
                                                <label
                                                    class="col-md-3 col-form-label"
                                                    for="client-phone"
                                                >{{ lang.phone }}</label
                                                >
                                                <div class="col-md-9">
                                                    <input
                                                        type="text"
                                                        disabled
                                                        class="form-control"
                                                        v-model="customerMobile"
                                                        id="client-phone">
                                                </div>
                                            </div>
                                            <!-- Mobile Number End -->

                                            <!-- Address Start -->
                                            <div class="form-group row">
                                                <label
                                                    class="col-md-3 col-form-label"
                                                    for="client-address"
                                                >{{ lang.address }}</label
                                                >
                                                <div class="col-md-9">
                                                    <textarea
                                                        id="client-address"
                                                        disabled
                                                        v-model="customerAddress"
                                                        rows="3"
                                                        class="form-control">
                                                    </textarea>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Remark Start -->
                                        <div class="form-group row">
                                            <label class="col-md-3 col-form-label" for="remark">{{
                                                    lang.remark
                                                }}</label>
                                            <div class="col-md-9">
                                                <textarea
                                                    class="form-control form-control-sm"
                                                    id="remark"
                                                    v-model="form.comment"
                                                    rows="8"
                                                    cols="8">
                                                </textarea>
                                            </div>
                                        </div>
                                        <!-- Remark End -->
                                    </div>
                                    <!-- Bottom Left Section End -->

                                    <!-- Bottom Right Section Start -->
                                    <div class="col-md-6">
                                        <!-- Total Amount Start -->
                                        <div class="form-group row">
                                            <label
                                                class="text-right col-3 col-form-label"
                                                for="total-amount">
                                                {{ lang.total }} {{ lang.amount }}
                                            </label>

                                            <div class="col-9">
                                                <div class="input-group input-group-sm">
                                                    <input
                                                        type="number"
                                                        class="form-control form-control-sm"
                                                        id="total-amount"
                                                        autocomplete="off"
                                                        :value="Number.parseFloat(subtotal).toFixed(2)"
                                                        disabled
                                                    />

                                                    <div class="input-group-append">
                                                        <span class="input-group-text">৳</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Total Amount End -->

                                        <!--                                        &lt;!&ndash; previous balance start &ndash;&gt;-->
                                        <!--                                        <div class="form-group row">-->
                                        <!--                                            <label for="previous_balance" class="text-right col-3 col-form-label">Previous Balance</label>-->
                                        <!--                                            <div class="col-5">-->
                                        <!--                                                <input type="number" disabled class="form-control form-control-sm" :value="Math.abs(customerBalance)" id="previous_balance">-->
                                        <!--                                            </div>-->
                                        <!--                                            <div class="col-4">-->
                                        <!--                                                <input type="text" disabled :value="(customerBalance >= 0) ? 'Receivable' : 'Payable'" class="form-control form-control-sm">-->
                                        <!--                                            </div>-->
                                        <!--                                        </div>-->
                                        <!--                                        &lt;!&ndash; previous balance end &ndash;&gt;-->

                                        <!-- Total Discount Start -->
                                        <div class="form-group row">
                                            <div class="text-right col-5 col-form-label">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio"
                                                           v-model="form.payment.discountType" id="flat" value="flat">
                                                    <label class="form-check-label" for="flat">{{ lang.flat }}
                                                        {{ lang.discount }}</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio"
                                                           v-model="form.payment.discountType" id="percentage"
                                                           value="percentage">
                                                    <label class="form-check-label" for="percentage">{{ lang.discount }}
                                                        (%)</label>
                                                </div>
                                            </div>

                                            <div class="col-7">
                                                <div class="input-group input-group-sm">
                                                    <input
                                                        type="number"
                                                        step="any"
                                                        class="form-control form-control-sm"
                                                        id="total-discount"
                                                        v-model="form.payment.discount"
                                                        autocomplete="off"
                                                    />

                                                    <div class="input-group-append">
                                                        <span class="input-group-text">৳</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Total Discount End -->

                                        <!-- labour cost Start -->
                                        <div class="form-group row">
                                            <div class="text-right col-3 col-form-label">
                                                <div class="form-check form-check-inline">
                                                    <label class="form-check-label"
                                                           for="labour-cost">{{ lang.labour_cost }}</label>
                                                </div>
                                            </div>

                                            <div class="col-9">
                                                <div class="input-group input-group-sm">
                                                    <input
                                                        type="number"
                                                        class="form-control form-control-sm"
                                                        id="labour-cost"
                                                        step="any"
                                                        v-model="form.labourCost"
                                                    />

                                                    <div class="input-group-append">
                                                        <span class="input-group-text">৳</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- labour cost End -->

                                        <!-- transport cost Start -->
                                        <div class="form-group row">
                                            <div class="text-right col-3 col-form-label">
                                                <div class="form-check form-check-inline">
                                                    <label class="form-check-label"
                                                           for="transport-cost">{{ lang.transport_cost }}</label>
                                                </div>
                                            </div>

                                            <div class="col-9">
                                                <div class="input-group input-group-sm">
                                                    <input
                                                        type="number"
                                                        class="form-control form-control-sm"
                                                        id="transport-cost"
                                                        step="any"
                                                        v-model="form.transportCost"
                                                    />

                                                    <div class="input-group-append">
                                                        <span class="input-group-text">৳</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- transport cost End -->

                                        <!-- Grand Total Start -->
                                        <div class="form-group row">
                                            <label
                                                class="text-right col-3 col-form-label"
                                                for="grand-total">
                                                {{ lang.grand_total }}
                                            </label>

                                            <div class="col-9">
                                                <div class="input-group input-group-sm">
                                                    <input
                                                        type="number"
                                                        class="form-control form-control-sm"
                                                        id="grand-total"
                                                        autocomplete="off"
                                                        disabled
                                                        :value="
                                                            Number.parseFloat(
                                                                ((parseFloat(subtotal) + parseFloat(form.labourCost || 0) + parseFloat(form.transportCost || 0)))
                                                                -
                                                                parseFloat(totalDiscount || 0)).toFixed(2)"/>

                                                    <div class="input-group-append">
                                                        <span class="input-group-text">৳</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Grand Total End -->

                                        <!-- Paid Start -->
                                        <div class="form-group row required">
                                            <label
                                                class="text-right col-3 col-form-label"
                                                for="paid">
                                                {{ lang.paid }}
                                            </label>

                                            <div class="col-6">
                                                <div class="input-group input-group-sm">
                                                    <input
                                                        type="number"
                                                        class="form-control form-control-sm"
                                                        id="paid"
                                                        step="any"
                                                        min="0"
                                                        required
                                                        autocomplete="off"
                                                        v-model="form.payment.paid"/>

                                                    <div class="input-group-append">
                                                        <span class="input-group-text">৳</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="input-group-append">
                                                    <select
                                                        class="form-control form-control-sm"
                                                        v-model="form.payment.method">
                                                        <option value="cash">{{ lang.cash }}</option>
                                                        <option value="bank">{{ lang.bank }}</option>
                                                        <option value="bkash">{{ lang.bkash }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Paid End -->

                                        <!-- Cash Extra Field Start -->
                                        <div v-if="form.payment.method == 'cash'">
                                            <div class="form-group row">
                                                <label
                                                    class="text-right col-3 col-form-label"
                                                    for="cash-name">
                                                    {{ lang.cash_name }}
                                                </label>
                                                <div class="col-9">
                                                    <select
                                                        id="cash-name"
                                                        class="form-control form-control-sm"
                                                        v-model="paymentInfo.cash_id">
                                                        <option
                                                            v-for="(cash,
                                                            cashIndex) in cashes"
                                                            :key="cashIndex"
                                                            :value="cash.id">
                                                            {{ cash.title }}
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Cash Extra Field End -->

                                        <!-- Bank Extra Field Start -->
                                        <div v-if="form.payment.method == 'bank'">
                                            <div class="form-group row">
                                                <label
                                                    class="text-right col-3 col-form-label"
                                                    for="bank-name">
                                                    {{ lang.bank_name }}
                                                </label>
                                                <div class="col-9">
                                                    <select
                                                        id="bank-name"
                                                        class="form-control form-control-sm"
                                                        v-model="paymentInfo.bank_account_id">
                                                        <option
                                                            v-for="(bankAccount,
                                                            bankIndex) in bankAccounts"
                                                            :value="bankAccount.id"
                                                            :key="bankIndex">
                                                            {{
                                                                `${bankAccount.bank.name} (${bankAccount.account_name})`
                                                            }}
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Bank Extra Field End -->

                                        <!-- bkash Extra Field Start -->
                                        <div
                                            v-if="form.payment.method == 'bkash'">
                                            <div class="row">
                                                <label
                                                    class="text-right col-3 col-form-label required"
                                                    for="bkash-number">
                                                    {{ lang.bkash }} {{ lang.number }}
                                                </label>

                                                <div class="col-9">
                                                    <input
                                                        type="text"
                                                        id="bkash-number"
                                                        class="form-control form-control-sm"
                                                        required
                                                        v-model.trim="paymentInfo.phone_number"
                                                        placeholder="Enter bKash phone number"
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                        <!-- bkash Extra Field End -->

                                        <!-- Amount Start -->
                                        <div class="form-group row">
                                            <label
                                                class="text-right col-3 col-form-label"
                                                for="due-or-change">
                                                {{ dueOrChange >= 0 ? lang.due : lang.return }}
                                            </label>

                                            <div class="col-9">
                                                <div class="input-group input-group-sm">
                                                    <input
                                                        type="number"
                                                        class="form-control form-control-sm"
                                                        id="due-or-change"
                                                        autocomplete="off"
                                                        disabled
                                                        :value="
                                                            Number.parseFloat(
                                                                Math.abs(
                                                                dueOrChange
                                                            )
                                                            ).toFixed(2)"/>

                                                    <div class="input-group-append">
                                                        <span class="input-group-text">৳</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Amount End -->
                                    </div>
                                    <!-- Bottom Right Section End -->
                                </div>

                                <div class="pt-2 row border-top">
                                    <div class="col-md-6">
                                        <!-- Send SMS Start -->
                                        <div class="form-group row">
                                            <div class="col-10 offset-2">
                                                <div class="custom-control custom-checkbox">
                                                    <input
                                                        type="checkbox"
                                                        v-model="form.sms"
                                                        class="custom-control-input"
                                                        id="client-send-sms"
                                                    />
                                                    <label
                                                        class="custom-control-label"
                                                        for="client-send-sms">
                                                        Send SMS
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Send SMS End -->
                                    </div>
                                    <div class="text-right col-md-6">
                                        <button :disabled="orderDisable" class="btn btn-sm btn-primary">
                                            Pre Order
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Bottom Section End -->
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import lowestConverter from '../reusable/ConvertToLowestUnit'
import upperConverter from '../reusable/ConvertToUpperUnit'

export default {
    name: "PreOrderCreateComponent",
    props: {
        warehouses: Array,
        cashes: Array,
        customers: Array,
        customerType: Object,
        lang: Object,
        bankAccounts: Array,
        oldPreOrder: Object,
    },

    watch: {
        selectedProducts: {
            deep: true,
            handler: function (value) {
                this.disableWarehouse = this.selectedProducts.length > 0;
                this.subtotal = value.reduce((total, item) => {
                    return parseFloat(item.total_price) + total;
                }, 0);
            }
        },
    },

    computed: {
        //...mapState("retailSale", ["warehouses"]),
        products: function () {
            try {
                return this.warehouses.find(
                    warehouse => warehouse.id == this.warehouse_id
                ).products || []
            } catch (error) {
                return [];
            }
        },
        totalDiscount() {
            if (this.form.payment.discountType === 'flat') {
                return this.form.payment.discount || 0
            } else {
                return (
                    ((parseFloat(this.subtotal || 0) * parseFloat(this.form.payment.discount || 0))
                        /
                        (parseFloat(100)))
                )
            }
        },
        dueOrChange() {
            return (
                ((parseFloat(this.subtotal || 0)
                        + parseFloat(this.form.labourCost || 0)
                        + parseFloat(this.form.transportCost))
                )
                -
                (parseFloat(this.totalDiscount) || 0)
                -
                (parseFloat(this.form.payment.paid) || 0)
            );
        }
    },

    data() {
        return {
            searchCustomer: [],
            orderDisable: false,
            customer_type: null,
            form: {
                sms: false,
                labourCost: 0,
                transportCost: 0,
                delivered: 0,
                date: new Date().toISOString().slice(0, 10),
                customer: {
                    name: null,
                    phone: null,
                    address: null
                },
                payment: {
                    discountType: 'flat',
                    discount: 0,
                    paid: null,
                    method: "cash"
                },
                comment: null,
            },
            errors: null,
            disableWarehouse: false,
            disableBrand: false,
            barcode: null,
            customPrice: 0,
            warehouse_id: null,
            selectedProducts: [],
            selectedProduct: null,
            customerId: null,
            customerMobile: null,
            customerAddress: null,
            subtotal: 0,
            paymentInfo: {
                cash_id: null,
                phone_number: null,
                bank_account_id: null,
                cheque_number: null,
                issue_date: null
            },

        };
    },
    methods: {
        onProductSelected(value) {
            const index = this.selectedProducts.findIndex(
                product => product.id === value.id
            );

            if (index === -1) {
                let displayQuantity = upperConverter(value.total_product_quantity, value.unit);
                // default quantity
                this.selectedProducts.push(value);

                const unitRelation = value.unit.relation.split('/')
                let _quantity = [];
                for (let i = 0; i < unitRelation.length; i++) {
                    _quantity[i] = null
                }

                value.quantity = [..._quantity]
                value.deliveryQuantityInUnit = [..._quantity]
                value.firstDeliveryQuantityInUnit = [..._quantity]

                const newProduct = {
                    ...value,
                    sale_quantity: 0,
                    delivery_quantity: 0,
                    total_price: 0,
                    deliveryError: '',
                    quantityError: '',
                    display_quantity: displayQuantity || 'No stock available',
                    purchasePrice: value.stock.average_purchase_price,
                };

                this.selectedProducts.splice(
                    this.selectedProducts.length - 1,
                    1,
                    newProduct
                );

                this.selectedProduct = null;
            } else {
                this.$awn.warning(value.name + ' already added in cart')
            }
        },

        addQuantity(event, productId, order) {
            let product = this.selectedProducts.find(product => product.id === productId)
            this.$set(product.quantity, order, parseFloat(event.target.value));

            product.quantity[order] = event.target.value ? event.target.value : null
            product.sale_quantity = lowestConverter(product.quantity, product.unit);
        },

        addDeliveryQuantity(event, productId, order) {
            let product = this.selectedProducts.find(product => product.id === productId)
            this.$set(product.deliveryQuantityInUnit, order, parseFloat(event.target.value));

            product.deliveryQuantityInUnit[order] = event.target.value ? event.target.value : null
            product.delivery_quantity = lowestConverter(product.deliveryQuantityInUnit, product.unit);
        },

        getDealerDetails() {
            this.form.customer.name = null;
            this.form.customer.address = null;
            this.form.customer.phone = null;
        },

        getRetailDetails() {
            this.customerId = null;
            this.customerMobile = null;
            this.customerAddress = null;

        },

        getCustomerDetails(id) {
            if (this.customerId) {
                let customer = this.customers.find(customer => customer.id == id)

                this.customerMobile = customer.phone
                this.customerAddress = customer.address
            } else {
                this.customerMobile = null
                this.customerAddress = null
            }
        },

        preOrder() {
            // let grandTotal = (
            //     (parseFloat(this.subtotal || 0)
            //         + parseFloat(this.form.transportCost)
            //         + parseFloat(this.form.labourCost))
            //     - parseFloat(this.totalDiscount)
            // )
            //
            // let saleDue = parseFloat(grandTotal) - parseFloat(this.form.payment.paid);

            const form = {
                ...this.form,
                products: []
            };

            // for products
            let quantityError = false;
            this.selectedProducts.forEach(product => {
                if (product.delivery_quantity > product.sale_quantity || product.sale_quantity <= 0) {
                    quantityError = true
                    if (product.sale_quantity <= 0) {
                        product.quantityError = "Quantity can\'t be empty"
                    } else {
                        product.quantityError = ''
                    }
                    if (product.delivery_quantity > product.sale_quantity) {
                        product.deliveryError = "Delivery quantity can\'t be greater than sale quantity"
                    } else {
                        product.deliveryError = ''
                    }
                } else {
                    product.quantityError = '';
                    product.deliveryError = '';
                    quantityError = false;
                }
                form.products.push({
                    id: product.id,
                    quantity: product.sale_quantity,
                    quantity_in_unit: product.quantity,
                    delivery_quantity: product.delivery_quantity,
                    delivery_quantity_in_unit: product.deliveryQuantityInUnit,
                    first_delivery_quantity_in_unit: product.firstDeliveryQuantityInUnit,
                    price: product.sale_price,
                    purchase_price: product.purchasePrice,
                    line_total: product.total_price
                });
            });

            if (quantityError) {
                form.products = []
                return
            }

            // for payments details
            switch (form.payment.method) {
                case "cash":
                    form.payment = {
                        cash_id: this.paymentInfo.cash_id,
                        paid: form.payment.paid,
                        method: form.payment.method,
                    };
                    break;
                case "bank":
                    form.payment = {
                        bank_account_id: this.paymentInfo.bank_account_id,
                        paid: form.payment.paid,
                        method: form.payment.method,
                    };
                    break;
                case "bkash":
                    form.payment = {
                        phone_number: this.paymentInfo.phone_number,
                        paid: form.payment.paid,
                        method: form.payment.method,
                    };
                    break;

                default:
                    break;
            }
            // add subtotal
            form.payment.subtotal = this.subtotal;
            form.payment.total_discount = this.totalDiscount;

            // add sale type
            form.customer_id = this.customerId;
            form.warehouse_id = this.warehouse_id;

            // due or change
            if (this.dueOrChange > 0) {
                // due
                form.payment.due = Math.abs(this.dueOrChange);
            } else {
                // change
                form.payment.change = Math.abs(this.dueOrChange);
                form.payment.due = 0;
            }
            this.orderDisable = true;

            if (this.oldPreOrder) {
                // update pre order
                this.updatePreOrder(form);
            } else {
                // create new preorder
                this.createNewPreOrder(form)
            }
        },
        updatePreOrder(data) {
            this.$awn.asyncBlock(
                axios.patch(baseURL + "user/preOrder/" + this.oldPreOrder.id, data),
                response => {
                    // redirect to invoice
                    window.location.href =
                        baseURL +
                        "user/preOrder/" +
                        response.data.id;
                    // console.log(response.data)
                    this.orderDisable = false;

                },
                reason => {
                    this.orderDisable = false;
                    console.log(reason.response.data)
                    this.errors = reason.response.data;
                }
            );
        },
        createNewPreOrder(data) {
            this.$awn.asyncBlock(
                axios.post(baseURL + "user/preOrder", data),
                response => {
                    // redirect to invoice
                    window.location.href =
                        baseURL +
                        "user/preOrder/" +
                        response.data.id;
                    // console.log(response.data)
                },
                reason => {
                    this.orderDisable = false;
                    console.log(reason.response.data)
                    this.errors = reason.response.data;
                }
            );
        },
        init() {
            try {
                // select first cash id
                this.warehouse_id = this.warehouses[0].id
                this.paymentInfo.cash_id = (this.cashes.length > 0) ? this.cashes[0].id : null;
                this.searchCustomer = this.customers
                // select first bank account
                this.paymentInfo.bank_account_id = (this.bankAccounts.length > 0) ? this.bankAccounts[0].id : null;
            } catch (error) {
                console.log(error);
            }
        },
        loadOldPreOrder() {
            console.log(this.oldPreOrder)
            this.warehouse_id = this.oldPreOrder.warehouse_id
            this.form.date = this.oldPreOrder.date.substr(0, 10)
            this.customer_type = this.oldPreOrder.customer.type
            this.customerId = this.oldPreOrder.customer_id
            this.getCustomerDetails(this.oldPreOrder.customer_id)

            this.form.comment = this.oldPreOrder.comment
            this.form.labourCost = this.oldPreOrder.labour_cost
            this.form.transportCost = this.oldPreOrder.transport_cost

            this.form.payment.discountType = this.oldPreOrder.discount_type
            this.form.payment.discount = this.oldPreOrder.discount
            this.form.payment.paid = this.oldPreOrder.paid
            this.form.payment.method = this.oldPreOrder.payment_type

            this.paymentInfo.cash_id = this.oldPreOrder.cash_id
            this.paymentInfo.bank_account_id = this.oldPreOrder.bank_account_id

            const selectedProducts = this.oldPreOrder.products.map(product => {

                const preOrderDeliveryDetails = this.oldPreOrder.pre_order_delivery_details
                    .find(preOrderDeliveryDetail => preOrderDeliveryDetail.product_id == product.id)

                const preOrderDetails = this.oldPreOrder.pre_order_details
                    .find(preOrderDetail => preOrderDetail.product_id == product.id)

                if (preOrderDeliveryDetails) {
                    product.delivery_quantity = preOrderDeliveryDetails.delivery_quantity
                    product.deliveryQuantityInUnit = preOrderDeliveryDetails.delivery_quantity_in_unit
                }else{
                    product.delivery_quantity = 0;
                    product.deliveryQuantityInUnit = preOrderDetails.delivery_quantity_in_unit
                }

                product.sale_quantity = preOrderDetails.quantity
                product.quantity = preOrderDetails.quantity_in_unit
                product.firstDeliveryQuantityInUnit = preOrderDetails.delivery_quantity_in_unit
                product.sale_price = preOrderDetails.sale_price
                product.total_price = preOrderDetails.line_total
                product.purchasePrice = product.warehouses.find(warehouse => warehouse.id == this.oldPreOrder.warehouse_id)
                    .stock
                    .average_purchase_price
                product.display_quantity = product.warehouses.find(warehouse => warehouse.id == this.oldPreOrder.warehouse_id)
                    .product_quantity_in_unit
                    .display

                return product;
            })

            this.selectedProducts = selectedProducts
        }
    },
    mounted() {
        this.customers.map(function (customer) {
            return customer.custom_name = customer.name + ' ' + (customer.phone || '')
        })
        if (this.oldPreOrder) {
            this.loadOldPreOrder()
        } else {
            this.init();
        }
    },
}
</script>

<style scoped>

</style>
