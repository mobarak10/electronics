<template>
    <div class="pos">
        <div class="row">
            <div class="col-12">
                <div class="container-fluid">
                    <form action="" @submit.prevent="saleReturn">
                        <!-- Table Form Start -->
                        <div class="row">
                            <div class="my-2 col-12 border-top border-bottom">
                                <table
                                    class="table my-2 table-striped table-bordered table-sm"
                                >
                                    <thead>
                                        <tr>
                                            <th class="text-center">{{ lang.sl }}</th>
                                            <th style="min-width: 180px">Product Name</th>
                                            <th class="text-right">Sale Quantity</th>
                                            <th>Return Quantity</th>
                                            <th>Return Price</th>
                                            <th class="text-right">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <tr
                                            v-for="(details,
                                            index) in saleDetails"
                                            :key="index"
                                        >
                                            <td class="text-right">{{ index + 1 }}.</td>
                                            <td>{{ details.product.name }}</td>

                                            <td class="text-right">
                                                {{ details.quantity }}
                                            </td>
                                            <td>
                                                <!-- Quantity -->
                                                <input
                                                    type="number"
                                                    class="form-control form-control-sm"
                                                    aria-describedby="quantityError"
                                                    min="0"
                                                    @change="getSubtotal"
                                                    @blur="getSubtotal"
                                                    @keyup="getSubtotal"
                                                    :max="details.quantity"
                                                    v-model.trim="details.return_quantities"
                                                />
                                                <div v-if="details.error" id="quantityError" class=" text-small form-text text-danger">
                                                    {{ details.error }}
                                                </div>
                                            </td>
                                            <td>
                                                <!-- Sale Price -->
                                                <input
                                                    type="number"
                                                    step="any"
                                                    @change="getSubtotal"
                                                    @blur="getSubtotal"
                                                    @keyup="getSubtotal"
                                                    class="form-control form-control-sm"
                                                    v-model.trim="details.sale_price"
                                                />
                                            </td>


                                            <td class="text-right">
                                                <div>
                                                    {{ Number.parseFloat((details.total_price = parseFloat(details.return_quantities || 0) * parseFloat(details.sale_price || 0)) || 0).toFixed(2) }}
                                                </div>
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
                                        <div class="form-group row required">
                                            <label
                                                class="col-md-3 col-form-label"
                                                for="client-name"
                                            >Customer Name</label
                                            >
                                            <div class="col-md-9">
                                                <input
                                                    type="text"
                                                    disabled
                                                    :value="sale.customer.name"
                                                    class="form-control"
                                                    id="model">
                                            </div>
                                        </div>
                                        <!-- Cient Select End -->
                                        <!-- Mobile Number Start -->
                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label"
                                                for="client-phone"
                                            >Phone</label
                                            >
                                            <div class="col-md-9">
                                                <input
                                                    type="text"
                                                    :value="sale.customer.phone"
                                                    disabled
                                                    class="form-control"
                                                    id="model">
                                            </div>
                                        </div>

                                        <!-- Mobile Number End -->
                                        <!-- Address Start -->
                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label"
                                                for="client-address"
                                            >Address</label
                                            >
                                            <div class="col-md-9">
                                                <textarea
                                                    id="address"
                                                    disabled
                                                    rows="3"
                                                    class="form-control">
                                                    {{ sale.customer.address }}
                                                </textarea>
                                            </div>
                                        </div>


                                        <!-- Remark Start -->
                                        <div class="form-group row">
                                            <label
                                                class="col-md-3 col-form-label"
                                                for="remark"
                                                >Remark</label
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
                                                >Total Amount</label
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
                                                        :value="subtotal"
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

                                        <!-- Total Discount Start -->
                                        <div class="form-group row">
                                            <label
                                                class="text-right col-3 col-form-label"
                                                for="total-discount"
                                                >Adjustment</label
                                            >
                                            <div class="col-9">
                                                <div
                                                    class="input-group input-group-sm"
                                                >
                                                    <input
                                                        type="number"
                                                        class="form-control form-control-sm"
                                                        id="total-discount"
                                                        v-model="
                                                            form.payment
                                                                .discount
                                                        "
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
                                                >Grand Total</label
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
                                                        :value="Number.parseFloat(parseFloat(subtotal)
                                                                -
                                                                parseFloat(form.payment.discount || 0).toFixed(2))"
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
                                        <!-- Grand Total End -->

                                        <!-- Paid Start -->
                                        <div class="form-group row required">
                                            <div class="text-right col-5 col-form-label">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio"  id="paid" v-model="paymentType" value="paid">
                                                    <label class="form-check-label" for="paid">Paid</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio"  id="adjustment" v-model="paymentType" value="adjustment">
                                                    <label class="form-check-label" for="adjustment">Adjust to Balance</label>
                                                </div>
                                            </div>

                                            <div class="col-7">
                                                <div class="input-group-append">
                                                    <select
                                                        :disabled="paymentType==='adjustment'"
                                                        class="form-control form-control-sm"
                                                        v-model="
                                                            form.payment.method
                                                        "
                                                    >
                                                        <option value="cash"
                                                            >Cash</option
                                                        >
                                                        <option value="bank"
                                                            >Bank</option
                                                        >
                                                        <option value="bkash"
                                                            >bKash</option
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
                                                    >Cash Name</label
                                                >
                                                <div class="col-9">
                                                    <select
                                                        class="form-control form-control-sm"
                                                        v-model="
                                                            paymentInfo.cash_id
                                                        "
                                                    >
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
                                                    >Bank Name</label
                                                >
                                                <div class="col-9">
                                                    <select
                                                        class="form-control form-control-sm"
                                                        v-model="
                                                            paymentInfo.bank_account_id
                                                        "
                                                    >
                                                        <option
                                                            v-for="(bankAccount,
                                                            bankIndex) in bankAccounts"
                                                            :value="
                                                                bankAccount.id
                                                            "
                                                            :key="bankIndex"
                                                            >{{
                                                                `${bankAccount.bank.name} (${bankAccount.account_name})`
                                                            }}</option
                                                        >
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <label
                                                    class="text-right col-3 col-form-label"
                                                    for="cheque-number"
                                                    >Check No</label
                                                >
                                                <div class="col-9">
                                                    <input
                                                        type="text"
                                                        class="form-control form-control-sm"
                                                        id="cheque-number"
                                                        v-model="
                                                            paymentInfo.cheque_number
                                                        "
                                                        placeholder="Enter check no"
                                                        autocomplete="off"
                                                    />
                                                </div>
                                            </div>

                                            <div class="row">
                                                <label
                                                    class="text-right col-3 col-form-label"
                                                    for="issue-date"
                                                    >Issue Date</label
                                                >
                                                <div class="col-9">
                                                    <input
                                                        type="date"
                                                        class="form-control form-control-sm"
                                                        id="issue-date"
                                                        v-model="
                                                            paymentInfo.issue_date
                                                        "
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
                                                    >bKash Number</label
                                                >
                                                <div class="col-9">
                                                    <input
                                                        type="text"
                                                        class="form-control form-control-sm"
                                                        required
                                                        v-model.trim="
                                                            paymentInfo.phone_number
                                                        "
                                                        placeholder="Enter bKash phone number"
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                        <!-- bKash Extra Field End -->
                                    </div>
                                    <!-- Bottom Right Section End -->
                                </div>

                                <div class="pt-2 row border-top">
                                    <div class="col-md-6">
                                        <!-- Send SMS Start -->
                                        <div class="form-group row">
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
                                                        for="client-send-sms"
                                                        >Send SMS</label
                                                    >
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Send SMS End -->
                                    </div>
                                    <div class="text-right col-md-6">
                                        <button :disabled="disabledReturn" class="btn btn-sm btn-primary">
                                            Return
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
    name: "SaleReturnComponent",
    props: {
        cashes: Array,
        bankAccounts: Array,
        sale: Object,
        lang: Object,
    },

    data() {
        return {
            disabledReturn: false,
            form: {
                date: new Date().toISOString().slice(0, 10),
                payment: {
                    discount: 0,
                    paid: null,
                    method: "cash"
                },
                comment: null,
            },
            paymentType: 'paid',
            errors: null,
            customPrice: 0,
            saleDetails: [],
            selectedProduct: null,
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
        this.saleDetails = this.sale.saleDetails
        this.init();
    },

    methods: {
        getSubtotal(){
            let totalPrice = 0;
            for(let i = 0; i < this.saleDetails.length; i++){
                totalPrice += this.saleDetails[i].total_price
            }
            this.subtotal = Number.parseFloat(totalPrice).toFixed(2)
        },

        saleReturn() {
            this.disabledReturn = true;
            const form = {
                ...this.form,
                products: []
            };

            // for products
            let quantityError = false
            this.saleDetails.forEach(details => {
                if(details.quantity < details.return_quantities) {
                    quantityError = true
                    details.error = "Return Quantity can\'t be greater than sale quantity"
                }else{
                    if(details.return_quantities) {
                        form.products.push({
                            id: details.product_id,
                            quantities: details.return_quantities,
                            discount: details.total_discount || 0,
                            return_price: details.sale_price,
                            line_total: details.total_price
                        });
                    }
                }
            });

            if(quantityError) {
                this.disabledReturn = false;
                return
            }

            if(form.products.length <= 0){
                this.disabledReturn = false;
                alert('No return quantity given!')
                return
            }

            form.adjust_to_customer_balance = (this.paymentType === 'paid' ? false : true);
            form.sale_id = this.sale.id

            if(this.paymentType === 'paid') {
                // for payments details
                switch (form.payment.method) {
                    case "cash":
                        form.payment.return_payments = {
                            cash_id: this.paymentInfo.cash_id
                        };
                        break;
                    case "bank":
                        form.payment.return_payments = {
                            bank_account_id: this.paymentInfo.bank_account_id,
                            cheque_number: this.paymentInfo.cheque_number,
                            issue_date: this.paymentInfo.issue_date
                        };
                        break;
                    case "bkash":
                        form.payment.return_payments = {
                            phone_number: this.paymentInfo.phone_number
                        };
                        break;

                    default:
                        break;
                }
            }
            // add subtotal
            form.payment.subtotal = this.subtotal;

            this.$awn.asyncBlock(
                axios.post(baseURL + "user/pos/return", form),
                response => {
                    // redirect to invoice
                    window.location.href =
                        baseURL +
                        "user/invoice-generate/" +
                        response.data.invoice_no;
                },
                reason => {
                    this.disabledReturn = false;
                    this.errors = reason.response.data;
                }
            );
        },
        init() {
            try {
                // select first cash id
                this.paymentInfo.cash_id = (this.cashes.length > 0) ? this.cashes[0].id : null;
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
