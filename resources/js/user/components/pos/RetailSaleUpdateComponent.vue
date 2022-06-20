 <template>
    <div class="pos">
        <div class="row">
            <div class="col-12">
                <div class="container-fluid">
                    <form action="" @submit.prevent="updateSale">
                        <!-- Top Form Start-->
                        <div class="row mt-2">
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
                                    v-model="warehouse_id"
                                >
                                    <option :value="null" disabled
                                        >Select warehouse</option
                                    >
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
                                    class="bg-white"
                                ></v-select>
                            </div>
                        </div>
                        <!-- Top Form End -->

                        <!-- Table Form Start -->
                        <div class="row">
                            <div class="my-2 col-12 border-top border-bottom">
                                <table
                                    class="table my-2 table-striped table-bordered table-sm"
                                >
                                    <thead>
                                        <tr>
                                            <th class="text-center">{{ lang.sl }}</th>
                                            <th style="min-width: 180px">{{ lang.product_name }}</th>
                                            <th>Serial No</th>
                                            <th>{{ lang.quantity }}</th>
                                            <th>{{ lang.sale_price }}</th>
                                            <th title="Discount">{{ lang.discount }} {{ '%' }}</th>
                                            <th class="text-right">{{ lang.total }}</th>
                                            <th class="text-center print-none">
                                                {{ lang.action }}
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-if="selectedProducts.length === 0">
                                            <td
                                                colspan="20"
                                                class="text-center"
                                            >
                                                {{ lang.no_product_selected }}
                                            </td>
                                        </tr>
                                        <tr
                                            v-for="(product,
                                            productIndex) in selectedProducts"
                                            :key="productIndex"
                                        >
                                            <td>{{ productIndex + 1 }}.</td>
                                            <td>{{ product.name }}</td>
                                            <td>{{ product.serial_number }}</td>

                                            <!-- <td class="text-right">
                                                <span :class="product.total_product_quantity >= 0 ? 'text-success' : 'text-danger'">
                                                    {{ product.display_quantity }}
                                                </span>
                                            </td> -->
                                            <td>
                                                <!-- Quantity -->
                                                <div class="input-group">
                                                    <input
                                                        type="number"
                                                        step="any"
                                                        aria-describedby="quantityError"
                                                        v-for="(label,
                                                        labelIndex) in product
                                                        .product_unit_labels"
                                                        :placeholder="label"
                                                        :key="labelIndex"
                                                        :value="product.quantity[labelIndex]"
                                                        @blur="
                                                        addQuantity(
                                                                $event,
                                                                product.id,
                                                                labelIndex
                                                            )"
                                                        @change="
                                                            addQuantity(
                                                                $event,
                                                                product.id,
                                                                labelIndex
                                                            )"
                                                        @keyup="
                                                            addQuantity(
                                                                $event,
                                                                product.id,
                                                                labelIndex
                                                            )"
                                                        min="0"
                                                        class="form-control form-control-sm"
                                                    />
                                                </div>
                                            </td>
                                            <td>
                                                <!-- Sale Price -->
                                                <input
                                                    type="number"
                                                    step="any"
                                                    class="form-control form-control-sm"
                                                    v-model.trim="product.price"
                                                />
                                            </td>

                                            <td>
                                                <!-- discount Price -->
                                                <input
                                                    type="number"
                                                    step="any"
                                                    class="form-control form-control-sm"
                                                    v-model.trim="product.discount"
                                                />
                                            </td>

                                            <td class="text-right">
                                                <div>
                                                    {{
                                                        Number.parseFloat(
                                                            (product.total_price =
                                                                (Math.ceil(parseFloat(product.sale_quantity || 0)
                                                    * parseFloat(product.price || 0))) - ((product.sale_quantity * product.price) * product.discount) / 100 )).toFixed(2)
                                                    }}
                                                </div>
                                            </td>

                                            <td class="text-center">
                                                <button
                                                    class="btn btn-sm btn-danger"
                                                    type="button"
                                                    @click.prevent="selectedProducts.splice(productIndex,1)"
                                                >
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
                                                <label
                                                    class="col-md-3 col-form-label"
                                                    for="customer-search"
                                                >{{ lang.customer_name }}</label
                                                >
                                                <div class="col-md-9">
                                                    <select v-model="customerId" @change="getCustomerDetails" class="form-control">
                                                        <option v-for="(customer, index) in customers" :value="customer.id" :key="index">
                                                            {{ customer.name }}
                                                        </option>
                                                    </select>
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
                                            <label
                                                class="col-md-3 col-form-label"
                                                for="remark"
                                                >{{ lang.remark }}</label
                                            >
                                            <div class="col-md-9">
                                                <textarea
                                                    class="form-control form-control-sm"
                                                    id="remark"
                                                    v-model="form.comment"
                                                    rows="8"
                                                    cols="8"
                                                ></textarea>
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
                                                for="total-amount"
                                                >{{ lang.total }} {{ lang.amount }}</label
                                            >
                                            <div class="col-9">
                                                <div
                                                    class="input-group input-group-sm"
                                                >
                                                    <input
                                                        type="number"
                                                        class="form-control form-control-sm"
                                                        id="total-amount"
                                                        autocomplete="off"
                                                        :value="Number.parseFloat(subtotal).toFixed(2)"
                                                        disabled
                                                    />
                                                    <div
                                                        class="input-group-append"
                                                    >
                                                        <span
                                                            class="input-group-text"
                                                            >৳</span
                                                        >
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Total Amount End -->

                                        <!-- customer previous balance start -->
                                        <div class="form-group row">
                                            <label for="previous_balance" class="text-right col-3 col-form-label">Previous Balance</label>
                                            <div class="col-5">
                                                <input type="number" disabled class="form-control form-control-sm" :value="Math.abs(customerBalance)" id="previous_balance">
                                            </div>
                                            <div class="col-4">
                                                <input type="text" disabled :value="(customerBalance >= 0) ? 'Receivable' : 'Payable'" class="form-control form-control-sm">
                                            </div>
                                        </div>
                                        <!-- customer previous balance end -->

                                        <!-- Total Discount Start -->
                                        <div class="form-group row">
                                            <div class="text-right col-5 col-form-label">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" v-model="form.payment.discountType" id="flat" value="flat">
                                                    <label class="form-check-label" for="flat">{{ lang.flat }} {{ lang.discount }}</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" v-model="form.payment.discountType" id="percentage" value="percentage">
                                                    <label class="form-check-label" for="percentage">{{ lang.discount }} (%)</label>
                                                </div>
                                            </div>

                                            <div class="col-7">
                                                <div
                                                    class="input-group input-group-sm"
                                                >
                                                    <input
                                                        type="number"
                                                        step="any"
                                                        class="form-control form-control-sm"
                                                        id="total-discount"
                                                        v-model="form.payment.discount"
                                                        autocomplete="off"
                                                    />
                                                    <div
                                                        class="input-group-append"
                                                    >
                                                        <span
                                                            class="input-group-text"
                                                            >৳</span
                                                        >
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Total Discount End -->

                                        <!-- labour cost Start -->
                                        <div class="form-group row">
                                            <div class="text-right col-3 col-form-label">
                                                <div class="form-check form-check-inline">
                                                    <label class="form-check-label" for="labour-cost">{{lang.labour_cost}} </label>
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
                                                    <label class="form-check-label" for="transport-cost">{{lang.transport_cost}}</label>
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
                                                for="grand-total"
                                                >{{ lang.grand_total }}</label
                                            >
                                            <div class="col-9">
                                                <div
                                                    class="input-group input-group-sm"
                                                >
                                                    <input
                                                        type="number"
                                                        class="form-control form-control-sm"
                                                        id="grand-total"
                                                        autocomplete="off"
                                                        disabled
                                                        :value="
                                                            Number.parseFloat(
                                                                ((parseFloat(subtotal) + parseFloat(this.customerBalance || 0) + parseFloat(form.labourCost || 0) + parseFloat(form.transportCost || 0)))
                                                                -
                                                                parseFloat(totalDiscount || 0)).toFixed(2)"
                                                    />

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
                                                for="paid"
                                                >{{ lang.paid }}</label
                                            >
                                            <div class="col-6">
                                                <div
                                                    class="input-group input-group-sm"
                                                >
                                                    <input
                                                        type="number"
                                                        class="form-control form-control-sm"
                                                        id="paid"
                                                        min="0"
                                                        step="any"
                                                        required
                                                        autocomplete="off"
                                                        v-model="form.payment.paid"/>
                                                    <div
                                                        class="input-group-append"
                                                    >
                                                        <span
                                                            class="input-group-text"
                                                            >৳</span
                                                        >
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
                                        <div
                                            v-if="form.payment.method == 'cash'"
                                        >
                                            <div class="form-group row">
                                                <label
                                                    class="text-right col-3 col-form-label"
                                                    for="bank-name"
                                                    >{{ lang.cash_name }}</label
                                                >
                                                <div class="col-9">
                                                    <select
                                                        class="form-control form-control-sm"
                                                        v-model="paymentInfo.cash_id">
                                                        <option
                                                            v-for="(cash,
                                                            cashIndex) in cashes"
                                                            :key="cashIndex"
                                                            :value="cash.id"
                                                            >{{
                                                                cash.title
                                                            }}</option
                                                        >
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Cash Extra Field End -->

                                        <!-- Bank Extra Field Start -->
                                        <div
                                            v-if="form.payment.method == 'bank'"
                                        >
                                            <div class="form-group row">
                                                <label
                                                    class="text-right col-3 col-form-label"
                                                    for="bank-name"
                                                    >{{ lang.bank_name }}</label
                                                >
                                                <div class="col-9">
                                                    <select
                                                        class="form-control form-control-sm"
                                                        v-model="paymentInfo.bank_account_id">
                                                        <option
                                                            v-for="(bankAccount,
                                                            bankIndex) in bankAccounts"
                                                            :value="bankAccount.id"
                                                            :key="bankIndex"
                                                            >
                                                            {{ `${bankAccount.bank.name} (${bankAccount.account_name})` }}
                                                        </option
                                                        >
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <label
                                                    class="text-right col-3 col-form-label"
                                                    for="cheque-number"
                                                    >{{ lang.check_no }}</label
                                                >
                                                <div class="col-9">
                                                    <input
                                                        type="text"
                                                        class="form-control form-control-sm"
                                                        id="cheque-number"
                                                        v-model="paymentInfo.cheque_number"
                                                        placeholder="Enter check no"
                                                        autocomplete="off"
                                                    />
                                                </div>
                                            </div>

                                            <div class="row">
                                                <label
                                                    class="text-right col-3 col-form-label"
                                                    for="issue-date"
                                                    >{{ lang.issue_date }}</label
                                                >
                                                <div class="col-9">
                                                    <input
                                                        type="date"
                                                        class="form-control form-control-sm"
                                                        id="issue-date"
                                                        v-model="paymentInfo.issue_date"
                                                        autocomplete="off"
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Bank Extra Field End -->

                                        <!-- bKash Extra Field Start -->
                                        <div
                                            v-if="form.payment.method == 'bkash'">
                                            <div class="row">
                                                <label
                                                    class="text-right col-3 col-form-label required"
                                                    for="bank-name"
                                                    >{{ lang.bkash }} {{ lang.number }}</label
                                                >
                                                <div class="col-9">
                                                    <input
                                                        type="text"
                                                        class="form-control form-control-sm"
                                                        required
                                                        v-model.trim="paymentInfo.phone_number"
                                                        placeholder="Enter bKash phone number"
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                        <!-- bKash Extra Field End -->

                                        <!-- Amount Start -->
                                        <div class="form-group row">
                                            <label
                                                class="text-right col-3 col-form-label"
                                                for="due-or-change"
                                                >{{
                                                    dueOrChange >= 0
                                                        ? lang.due
                                                        : lang.return
                                                }}</label
                                            >
                                            <div class="col-9">
                                                <div
                                                    class="input-group input-group-sm"
                                                >
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
                                        <div class="form-group row d-none">
                                            <div class="col-10 offset-2">
                                                <div
                                                    class="custom-control custom-checkbox"
                                                >
                                                    <input
                                                        type="checkbox"
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
                                        <button class="btn btn-sm btn-primary">
                                            {{ lang.sale }}
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
import "vue-select/dist/vue-select.css";
import lowestConverter from '../reusable/ConvertToLowestUnit'
import upperConverter from '../reusable/ConvertToUpperUnit'

export default {
    name: "RetailSaleComponent",
    props: {
        warehouses: Array,
        cashes: Array,
        customers: Array,
        bankAccounts: Array,
        sale: Object,
        lang: Object,
    },
    watch: {
        selectedProducts: {
            deep: true,
            handler: function(value) {
                if(this.selectedProducts.length > 0 && this.saleType === 'dealer'){
                    this.disableWarehouse = true;
                }else{
                    this.disableWarehouse = false;
                }
                this.subtotal = value.reduce((total, item) => {
                    return parseFloat(item.total_price) + total;
                }, 0);

            }
        },

    },
    computed: {
        //...mapState("retailSale", ["warehouses"]),
        products: function() {
            try {
                return this.warehouses.find(
                        warehouse => warehouse.id == this.warehouse_id
                    ).products || []
            } catch (error) {
                return [];
            }
        },
        totalDiscount() {
            if(this.form.payment.discountType === 'flat') {
                return this.form.payment.discount || 0
            }else{
                return (
                    (parseFloat(this.subtotal || 0) * parseFloat(this.form.payment.discount || 0))
                    /
                    (parseFloat(100))
                )
            }
        },
        dueOrChange() {
            return (
                ((parseFloat(this.subtotal || 0)
                + parseFloat(this.form.labourCost || 0)
                + parseFloat(this.customerBalance || 0)
                + parseFloat(this.form.transportCost)))
                -
                (parseFloat(this.totalDiscount) || 0)
                -
                (parseFloat(this.form.payment.paid) || 0)
            );
        }
    },
    data() {
        return {
            form: {
                delivered: 1,
                labourCost: 0,
                sale_amount: 0,
                sale_due: 0,
                transportCost: 0,
                cNum: null,
                date: new Date().toISOString().slice(0, 10),
                payment: {
                    discountType: 'flat',
                    discount: 0,
                    paid: null,
                    method: "cash"
                },
                comment: null,
            },
            saleId: null,
            errors: null,
            disableWarehouse: false,
            disableBrand: false,
            barcode: null,
            customPrice: 0,
            saleType: 'dealer',
            warehouse_id: null,
            selectedProducts: [],
            selectedProduct: null,
            customerId: null,
            customerMobile: null,
            customerBalance: 0,
            previous_sale_customer_balance: 0,
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

    mounted() {
        this.customers.map(function (customer) {
            return customer.custom_name = customer.name + ' ' + (customer.phone || '')
        })
        this.init();

        $(document).ready(function() {
            $('#customer-search').select2({
                width: 400,
                height: 100
            });
        });
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

                value.quantity = _quantity

                const newProduct = {
                    ...value,
                    sale_quantity: 0,
                    price: value.retail_price,
                    total_price: 0,
                    discount: 0,
                    discount_type: 'percentage',
                    display_quantity: displayQuantity,
                    purchasePrice: value.stock.average_purchase_price,
                };

                this.selectedProducts.splice(
                    this.selectedProducts.length - 1,
                    1,
                    newProduct
                );

                this.selectedProduct = null;
            }else{
                this.$awn.warning(value.name + ' already added in cart!', {durations: {warning: 2000}})
                return
            }
        },

        addQuantity: function (event, productId, order) {
            let product = this.selectedProducts.find(product => product.id === productId)
            this.$set(product.quantity, order, parseFloat(event.target.value));

            product.quantity[order] = event.target.value ? event.target.value : null
            product.sale_quantity = lowestConverter(product.quantity, product.unit);

        },

        getCustomerDetails() {
            let customer = this.customers.find(customer => customer.id == this.customerId)
            this.customerMobile = customer.phone
            this.customerBalance = this.customerId == this.sale.customer_id ? this.previous_sale_customer_balance : customer.balance
            this.customerAddress = customer.address

        },

        updateSale() {
            const form = {
                ...this.form,
                products: []
            };

            // for products
            this.selectedProducts.forEach(product => {
                form.products.push({
                    id: product.id,
                    quantity: product.sale_quantity,
                    discount: product.discount,
                    discount_type: 'percentage',
                    price: product.price,
                    purchase_price: product.purchasePrice,
                    line_total: product.total_price
                });
            });

            // for payments details
            switch (form.payment.method) {
                case "cash":
                    form.payment.sale_payments = {
                        cash_id: this.paymentInfo.cash_id
                    };
                    break;
                case "bank":
                    form.payment.sale_payments = {
                        bank_account_id: this.paymentInfo.bank_account_id,
                        cheque_number: this.paymentInfo.cheque_number,
                        issue_date: this.paymentInfo.issue_date
                    };
                    break;
                case "bkash":
                    form.payment.sale_payments = {
                        phone_number: this.paymentInfo.phone_number
                    };
                    break;

                default:
                    break;
            }
            // add subtotal
            form.payment.subtotal = this.subtotal;
            form.payment.total_discount = this.totalDiscount;

            // add sale type
            form.sale_type    = this.saleType;
            form.customer_id  = this.customerId;
            form.warehouse_id = this.warehouse_id;

            form.previous_balance = this.customerBalance;
            form.sale_due = this.sale_due;
            form.sale_amount = this.sale_amount

            // due or change
            if (this.dueOrChange > 0) {
                form.payment.due = Math.abs(this.dueOrChange);
                form.due = Math.abs(this.dueOrChange);
                form.payment.change = 0;
            } else {
                form.payment.change = Math.abs(this.dueOrChange);
                form.payment.due = 0;
            }
            this.saleDisable = true;
            form.due = form.payment.due
            form.paid = form.payment.paid - form.payment.change

            this.$awn.asyncBlock(
                axios.put(baseURL + "user/retail-sale/" + this.saleId, form),
                response => {
                    // redirect to invoice
                    window.location.href =
                        baseURL +
                        "user/invoice-generate/" +
                        response.data.invoice_no;
                    console.log(response.data)
                },
                reason => {
                    console.log(reason.response.data)
                    this.errors = reason.response.data;
                }
            );
        },
        init() {
            try {
                this.saleId = this.sale.id
                this.warehouse_id = this.sale.warehouse_id
                this.customerId = this.sale.customer_id
                this.getCustomerDetails()
                this.previous_sale_customer_balance = this.sale.previous_balance < 0 ? -1 * this.sale.previous_balance : this.sale.previous_balance
                this.customerBalance = this.sale.previous_balance
                this.form.payment.paid = this.sale.paid
                this.form.payment.discountType = this.sale.discount_type
                this.form.payment.discount = this.sale.discount
                this.form.transportCost = this.sale.transport_cost
                this.form.labourCost = this.sale.labour_cost
                this.paymentInfo.cash_id = (this.cashes.length > 0) ? this.cashes[0].id : null;
                this.sale.saleDetails.forEach(details => {
                    details.product.sale_quantity = details.quantity
                    details.product.purchasePrice = details.purchase_price
                    details.product.total_price = details.line_total
                    details.product.sale_price = details.sale_price
                    details.product.serial_number = details.serial_number
                    details.product.discount = details.discount
                    details.product.price = details.sale_price
                    // details.product.quantity = Object.assign({}, details.quantity_in_unit)
                    details.product.quantity = {
                        0: details.quantity
                    }
                    this.selectedProducts.push(details.product);
                })
                // select first bank account
                this.paymentInfo.bank_account_id = (this.bankAccounts.length > 0) ? this.bankAccounts[0].id : null;
            } catch (error) {
                console.log(error);
            }
        }
    },

};
</script>

<style scoped></style>
