<template>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="m-0">Transaction</h5>
        </div>

        <div class="card-body p-0">
            <div class="col-12 py-2">
                <form action="" method="GET" @submit.prevent="transaction">
                    <div class="form-row">
                        <div class="form-group col-md-6 pr-3 required">
                            <label for="date">Transaction Date</label>
                            <input type="date" v-model="date" class="form-control" id="date" required>
                        </div>

                        <div class="form-group col-md-6 pl-3 required">
                            <label for="amount">Amount (BDT)</label>
                            <input type="number" v-model="amount" class="form-control" placeholder="0.00" id="amount"
                                   required>
                        </div>
                    </div>

                    <div class="from-row mt-2 mb-2">
                        <div class="form-check form-check-inline">
                            <label class="form-check-label">Transaction From: </label>
                        </div>

                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" v-model="transactionFrom"
                                   id="supplier-to-customer" value="supplier">
                            <label class="form-check-label" for="supplier-to-customer">Supplier To Customer</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" v-model="transactionFrom"
                                   id="customer-to-supplier" value="customer">
                            <label class="form-check-label" for="customer-to-supplier">Customer To Supplier</label>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6 required">
                            <label for="supplier-name">Supplier</label>
                            <v-select
                                id="supplier-name"
                                required
                                :options="suppliers"
                                v-model="partyId"
                                @input="getSupplierBalance"
                                :reduce="supplier => supplier.id"
                                placeholder="Select supplier"
                                label="name">
                            </v-select>
                        </div>

                        <div class="form-group col-md-6 required">
                            <label for="customer-name">Customer</label>
                            <v-select
                                id="customer-name"
                                :options="customers"
                                @input="getCustomerBalance"
                                v-model="customerId"
                                :reduce="customer => customer.id"
                                placeholder="Select customer"
                                label="name">
                            </v-select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-md-6">
                            <p class="px-2 text-white bg-dark" v-if="supplierBalance != null"> BDT {{ supplierBalance }}
                                {{ supplierBalance > 0 ? 'Receivable' : 'payable' }} </p>
                        </div>

                        <div class="col-md-6">
                            <p class="px-2 text-white bg-dark" v-if="customerBalance != null"> BDT {{ customerBalance }}
                                {{ customerBalance > 0 ? 'Receivable' : 'payable' }} </p>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="note">Note </label>
                            <textarea v-model="note" class="form-control" id="note" placeholder="Optional"></textarea>
                        </div>
                    </div>

                    <div class="form-row">
                        <!-- Send SMS Start -->
                        <div class="form-group col-md-6">
                            <div class="custom-control custom-checkbox">
                                <input
                                    type="checkbox"
                                    v-model="sms"
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
                        <!-- Send SMS End -->
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-12 text-right">
                            <button type="reset" class="btn btn-danger">Reset</button>
                            <button type="submit" class="btn btn-primary">Transfer now</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</template>

<script>
import "vue-select/dist/vue-select.css";

export default {
    name: "SupplierCustomerTransactionComponent",
    props: {
        customers: Array,
        suppliers: Array,
        oldTransaction: Object,
    },
    data() {
        return {
            date: new Date().toISOString().substr(0, 10),
            amount: null,
            note: null,
            sms: false,
            transactionFrom: 'supplier',
            customerId: null,
            customerBalance: null,
            supplierBalance: null,
            partyId: null,
        }
    },

    methods: {
        getCustomerBalance() {
            this.customerBalance = this.customers.find(customer => customer.id == this.customerId).balance
        },

        getSupplierBalance() {
            this.supplierBalance = this.suppliers.find(supplier => supplier.id == this.partyId).balance
        },

        transaction() {
            if(this.oldTransaction){
                this.proceedToUpdateTransaction()
            }else {
                this.proceedToNewTransaction()
            }

        },
        proceedToNewTransaction(){
            this.$awn.asyncBlock(
                axios.post(baseURL + 'user/transaction', {
                    date: this.date,
                    amount: this.amount,
                    note: this.note,
                    sms: this.sms,
                    transaction_from: this.transactionFrom,
                    customer_id: this.customerId,
                    party_id: this.partyId,
                }),
                response => {
                    console.log(response.data);
                    this.$awn.success("Transaction completed.");
                    window.location.href = baseURL + 'user/transaction'
                },
                error => {
                    console.log(error);
                    this.$awn.alert("Op's! Something went wrong.");
                }
            )
        },
        proceedToUpdateTransaction(){
            this.$awn.asyncBlock(
                axios.patch(baseURL + 'user/transaction/' + this.oldTransaction.id, {
                    date: this.date,
                    amount: this.amount,
                    note: this.note,
                    sms: this.sms,
                    transaction_from: this.transactionFrom,
                    customer_id: this.customerId,
                    party_id: this.partyId,
                }),
                response => {
                    console.log(response.data);
                    this.$awn.success("Transaction completed.");
                    window.location.href = baseURL + 'user/transaction'
                },
                error => {
                    console.log(error);
                    this.$awn.alert("Op's! Something went wrong.");
                }
            )
        },
        initOldData(){
            this.date = this.oldTransaction.date.substr(0, 10)
            this.amount = this.oldTransaction.amount;
            this.note = this.oldTransaction.note;
            this.transactionFrom = this.oldTransaction.transaction_from;
            this.customerId = this.oldTransaction.customer_id;
            this.partyId = this.oldTransaction.party_id;
            this.getSupplierBalance()
            this.getCustomerBalance()
        }
    },
    mounted() {
        if(this.oldTransaction){
            this.initOldData()
        }
    }
}
</script>

<style scoped>

</style>
