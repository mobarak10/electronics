 <template>
    <div class="pos">
        <div class="row">
            <div class="col-12">
                <div class="container-fluid">
                    <form action="" @submit.prevent="saleReturn">
                        <!-- Top Form Start-->
                        <div class="row mt-2">
                            <div class="col-md-3">
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

                            <div class="col-md-5">
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
                                            <th class="text-right">{{ lang.stock }}</th>
                                            <th>{{ lang.quantity }}</th>
                                            <th>{{ lang.sale_price }}</th>
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

                                            <td class="text-right">
                                                <span :class="product.total_product_quantity >= 0 ? 'text-success' : 'text-danger'">
                                                    {{ product.display_quantity }}
                                                </span>
                                            </td>
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
                                                <div v-if="product.error" id="quantityError" class="form-text text-danger">
                                                    {{ product.error }}
                                                </div>
                                            </td>
                                            <td>
                                                <!-- Sale Price -->
                                                <input
                                                    type="number"
                                                    step="any"
                                                    class="form-control form-control-sm"
                                                    v-model.trim="product.dealer_price"
                                                />
                                            </td>

                                            <td class="text-right">
                                                <div>
                                                    {{
                                                        Number.parseFloat(
                                                            (product.total_price =
                                                                parseFloat(product.return_quantity || 0)
                                                                * parseFloat(product.dealer_price || 0))).toFixed(2)
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
                                                    for="client-name"
                                                >{{ lang.customer_name }}</label
                                                >
                                                <div class="col-md-9">
                                                    <v-select
                                                        :options="customers"
                                                        v-model="customerId"
                                                        :reduce="customer => customer.id"
                                                        placeholder="Select Client"
                                                        @input="getCustomerDetails(customerId)"
                                                        label="custom_name">
                                                        <template slot="option" slot-scope="option">
                                                            <span class="fa" :class="option.icon"></span>
                                                            {{ option.name +' '+ (option.phone || '') }}
                                                        </template>
                                                    </v-select>
                                                </div>
                                            </div>
                                            <!-- Cient Select End -->
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
                                                        id="model">
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
                                                        id="address"
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
                                                    v-model="form.note"
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
                                                >{{ lang.total }} {{ lang.sl }}</label
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

                                        <div class="form-group row">
                                            <label for="previous_balance" class="text-right col-3 col-form-label">{{ lang.previous_balance }}</label>
                                            <div class="col-5">
                                                <input type="number" disabled class="form-control form-control-sm" :value="Math.abs(customerBalance)" id="previous_balance">
                                            </div>
                                            <div class="col-4">
                                                <input type="text" disabled :value="(customerBalance >= 0) ? 'Receivable' : 'Payable'" class="form-control form-control-sm">
                                            </div>
                                        </div>

                                        <!-- Total Discount Start -->
                                        <div class="form-group row">
                                            <div class="text-right col-3 col-form-label">
                                                <div class="form-check form-check-inline">
                                                    <label class="form-check-label" for="flat">
                                                        {{ lang.discount }}
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="col-9">
                                                <div
                                                    class="input-group input-group-sm"
                                                >
                                                    <input
                                                        type="number"
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
                                                                (parseFloat(subtotal)
                                                                -
                                                                parseFloat(customerBalance )
                                                                -
                                                                parseFloat(form.payment.discount || 0))).toFixed(2)"/>
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
                                                        v-model="
                                                            form.payment.method
                                                        "
                                                    >
                                                        <option value="cash"
                                                            >{{ lang.cash }}</option
                                                        >
                                                        <option value="bank"
                                                            >{{ lang.bank }}</option
                                                        >
                                                        <option value="bkash"
                                                            >{{ lang.bkash }}</option
                                                        >
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
                                                        v-model="cash_id">
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
                                                        v-model="bank_account_id">
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
                                                    >{{ lang.bKash }} {{ lang.number }}</label
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
                                                        : ""
                                                }}</label
                                            >
                                            <div v-if="dueOrChange >= 0" class="col-9">
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
                                        <button :disabled="returnDisable" class="btn btn-sm btn-primary">
                                           {{ lang.sale }} {{ lang.return }}
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

export default {
    name: "SaleReturnCreateComponent",
    props: {
        warehouses: Array,
        cashes: Array,
        customers: Array,
        bankAccounts: Array,
        lang: Object,
    },
    watch: {
        selectedProducts: {
            deep: true,
            handler: function(value) {
                if(this.selectedProducts.length > 0 && this.returnType === 'dealer'){
                    this.disableWarehouse = true;
                }else{
                    this.disableWarehouse = false;
                }
                this.subtotal = value.reduce((total, item) => {
                    return parseFloat(item.total_price) + total;
                }, 0);
            }
        },

        brandId: {
            handler: function() {
                if(this.customerId) {
                    this.getCustomerDetails(this.customerId)
                }
            }
        }
    },
    computed: {
        //...mapState("retailSale", ["warehouses"]),
        products: function() {
            try {
                return this.warehouses.find(
                        warehouse => warehouse.id === this.warehouse_id
                    ).products || []
            } catch (error) {
                return [];
            }
        },

        dueOrChange() {
            return (
                (parseFloat(this.subtotal || 0)
                - parseFloat(this.customerBalance || 0))
                -
                (parseFloat(this.form.payment.discount) || 0)
                -
                (parseFloat(this.form.payment.paid) || 0)
            );
        }
    },
    data() {
        return {
            returnDisable: false,
            form: {
                date: new Date().toISOString().slice(0, 10),
                payment: {
                    discount: 0,
                    paid: null,
                    method: "cash"
                },
                note: null,
                sms: false,
            },
            errors: null,
            disableWarehouse: false,
            disableBrand: false,
            customPrice: 0,
            returnType: 'dealer',
            warehouse_id: null,
            selectedProducts: [],
            selectedProduct: null,
            customerId: null,
            customerMobile: null,
            customerAddress: null,
            subtotal: 0,
            cash_id: null,
            phone_number: null,
            bank_account_id: null,
            cheque_number: null,
            issue_date: null,
            customerBalance: 0,
        };
    },

    mounted() {
        this.customers.map(function (customer) {
            return customer.custom_name = customer.name + ' ' + (customer.phone || '')
        })
        this.init();
    },

    methods: {
        onProductSelected(value) {
            const index = this.selectedProducts.findIndex(
                product => product.id === value.id
            );

            if (index === -1) {
                let displayQuantity = this.convertToUpperUnit(value.total_product_quantity, value.unit);
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
                    return_quantity: 0,
                    total_price: 0,
                    error: '',
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
                alert('product already added in cart!');
                return
            }
        },

        addQuantity(event, productId, order) {
            let product = this.selectedProducts.find(product => product.id === productId)
            this.$set(product.quantity, order, parseFloat(event.target.value));

            product.quantity[order] = event.target.value ? event.target.value : null

            const lowestUnit = this.convertToLowestUnit(product.quantity, product.unit)
            product.return_quantity = lowestUnit;

        },

        convertToLowestUnit(quantity, unit) {
            const unitRelation = unit.relation.split('/')
            let lowestUnit = 0
            for (let i = 0; i < unitRelation.length; i++) {
                lowestUnit += parseFloat(quantity[i] || '0')
                lowestUnit *= parseFloat(unitRelation[i + 1] || '1')
            }
            return lowestUnit;
        },

        convertToUpperUnit(quantity, unit){
            let relation = unit.relation.split('/')
            let labels = unit.labels.split('/')
            let last = labels.length - 1;
            let recordWithCleanUnit = [];

            for (let i = last; i >= 0; i--) {
                let divisor = parseFloat(relation[i])
                let remainder = (quantity % divisor);
                quantity = (quantity - remainder) / divisor;

                if(i === 0) {
                    recordWithCleanUnit.push(quantity + ' ' + labels[i]);
                } else {
                    remainder = Number.parseFloat(remainder).toFixed(2);
                    if (remainder != 0 ) {
                        recordWithCleanUnit.push(remainder + ' ' + labels[i]);
                    }
                }
            }
            let reverseUnit = recordWithCleanUnit.reverse();
            return reverseUnit.join(' ');
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
            this.customerBalance = 0;
        },

        getCustomerDetails(id) {
            if(this.customerId) {
                let customer = this.customers.find(customer => customer.id == id)
                this.customerBalance = customer.balance
                this.customerMobile = customer.phone
                this.customerAddress = customer.address
            }else{
                this.customerMobile = null
                this.customerAddress = null
            }
        },

        saleReturn() {
            const form = {
                ...this.form,
                products: []
            };

            // for products
            let quantityError = false;
            this.selectedProducts.forEach(product => {
                if(product.return_quantity <= 0) {
                    quantityError = true
                    product.error = "Quantity can\'t be empty"
                }
                form.products.push({
                    id: product.id,
                    quantity: product.return_quantity,
                    quantity_in_unit: product.quantity,
                    price: product.dealer_price,
                    purchase_price: product.purchasePrice,
                    line_total: product.total_price
                });
            });

            if(quantityError) {
                form.products = []
                return
            }

            // add subtotal
            form.cash_id = this.cash_id;
            form.phone_number = this.phone_number;
            form.bank_account_id = this.bank_account_id;
            form.cheque_number = this.cheque_number;
            form.issue_date = this.issue_date;
            form.payment.subtotal = this.subtotal;
            form.payment.discount = this.form.payment.discount;

            // add sale type
            form.customer_id  = this.customerId;
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
            this.returnDisable = true;

            this.$awn.asyncBlock(
                axios.post(baseURL + "user/saleReturn", form),
                response => {
                    // redirect to invoice
                    window.location.href =
                        baseURL +
                        "user/saleReturn/" +
                        response.data.id;
                    console.log(response.data)
                },
                reason => {
                    this.returnDisable = false;
                    console.log(reason.response.data)
                    this.errors = reason.response.data;
                }
            );
        },
        init() {
            try {
                // select first cash id
                this.warehouse_id = this.warehouses[0].id
                this.cash_id = (this.cashes.length > 0) ? this.cashes[0].id : null;
                // select first bank account
                this.bank_account_id = (this.bankAccounts.length > 0) ? this.bankAccounts[0].id : null;
            } catch (error) {
                console.log(error);
            }
        }
    },

};
</script>

<style scoped></style>
