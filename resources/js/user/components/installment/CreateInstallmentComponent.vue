<template>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="m-0">Installment Collection </h5>

            <div class="btn-group" role="group" aria-level="Action area">
                <a :href="listUrl" title="show due manage list" class="btn btn-success" style="margin-right: 5px">
                    <i class="fa fa-list" aria-hidden="true"></i>
                </a>
            </div>
        </div>

        <div class="card-body">
            <form method="post" @submit.prevent="saveInstallment" class="row">
                <div class="form-group col-md-3 required text-right">
                    <label for="date">Date</label>
                </div>

                <div class="form-group col-md-6">
                    <input v-model="date" type="date" class="form-control" id="date" required>
                </div>
                <div class="col-md-3">
                </div>

                <div class="form-group col-md-3 required text-right">
                    <label for="customerId">Name</label>
                </div>

                <div class="form-group col-md-6">
                    <v-select
                        :options="customers"
                        v-model="customerId"
                        id="customerId"
                        :reduce="customer => customer.id"
                        @input="getCustomerDetails(customerId)"
                        placeholder="Select customer"
                        label="name">
                        <template slot="option" slot-scope="option">
                            <span class="fa" :class="option.icon"></span>
                            {{ option.name }}
                        </template>
                    </v-select>
                </div>
                <div class="col-md-3">
                </div>

                <div class="form-group col-md-3 text-right">
                    <label for="balance">Balance</label>
                </div>

                <div class="form-group col-md-6">
                    <div class="row">
                        <div class="col-md-7">
                            <input v-model="customerBalance" type="text" disabled class="form-control" id="balance">
                        </div>

                        <div class="col-md-5">
                            <input v-model="balanceStatus" type="text" disabled class="form-control">
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                </div>

                <div class="form-group col-md-3 text-right">
                    <label for="voucher_no">Voucher No</label>
                </div>

                <div class="form-group col-md-6">
                    <v-select
                        :options="hireSales"
                        v-model="voucherNo"
                        id="voucher_no"
                        @input="getVoucherDetails"
                        :reduce="hire_sale => hire_sale.voucher_no"
                        placeholder="Select voucher"
                        label="voucher_no">
                        <template slot="option" slot-scope="option">
                            <span class="fa" :class="option.icon"></span>
                            {{ option.voucher_no }}
                        </template>
                    </v-select>
                </div>
                <div class="col-md-3">
                </div>

                <div class="form-group col-md-3 text-right">
                    <label for="due">Due</label>
                </div>

                <div class="form-group col-md-6">
                    <input type="text" id="due" disabled class="form-control" v-model="due">
                </div>
                <div class="col-md-3">
                </div>

                <div class="form-group col-md-3 text-right">
                    <label for="installment_amount">Installment Amount</label>
                </div>

                <div class="form-group col-md-6">
                    <div class="row">
                        <div class="col-md-7">
                            <input v-model="installmentAmount" type="text" disabled class="form-control" id="installment_amount">
                        </div>

                        <div class="col-md-5">
                            <input v-model="installmentAmountStatus" type="text" disabled class="form-control">
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                </div>

               <div class="form-group col-md-3 required text-right">
                   <label for="method">Collection Method</label>
               </div>

               <div class="form-group col-md-6">
                   <select v-model="where" id="method" class="form-control">
                       <option value="cash">Cash</option>
                       <option value="bank">Bank</option>
                       <option value="bkash">Bkash</option>
                   </select>
               </div>
               <div class="col-md-3">
               </div>

                <div v-if="where === 'cash'" class="row w-100 mx-0">
                    <div class="form-group col-md-3 required text-right">
                        <label for="cash">Cash Name</label>
                    </div>

                    <div class="form-group col-md-6">
                        <select id="cash" @change="cashDetails(cashId)" v-model="cashId" class="form-control">
                            <option
                                v-for="(cash, cashIndex) in cashes"
                                :key="cashIndex"
                                :value="cash.id">
                                {{ cash.title }}
                            </option>
                        </select>
                    </div>
                    <div class="col-md-3">
                    </div>
                </div>

                <div v-if="where === 'bkash'" class="row w-100 mx-0">
                    <div class="form-group col-md-3 required text-right">
                        <label for="bkash">Bkash Number</label>
                    </div>

                    <div class="form-group col-md-6">
                        <input type="text" v-model="bkashNo" id="bkash" class="form-control">
                    </div>
                    <div class="col-md-3">
                    </div>
                </div>

                <div v-if="where === 'bank'" class="row w-100 mx-0">
                    <div class="form-group col-md-3 required text-right">
                        <label for="bank">Bank Name</label>
                    </div>

                    <div class="form-group col-md-6">
                        <select id="bank" v-model="bank.accountId" class="form-control">
                            <option
                                v-for="(account, accountIndex) in bankAccounts"
                                :key="accountIndex"
                                :value="account.id">
                                {{ account.bank.name + '(' + account.account_name + ')' }}
                            </option>
                        </select>
                    </div>
                    <div class="col-md-3">
                    </div>

                    <div class="form-group col-md-3 required text-right">
                        <label for="issue_date">Issue Date</label>
                    </div>

                    <div class="form-group col-md-6">
                        <input type="date" id="issue_date" v-model="bank.issueDate" class="form-control">
                    </div>
                    <div class="col-md-3">
                    </div>

                    <div class="form-group col-md-3 text-right">
                        <label for="check_no">Check No</label>
                    </div>

                    <div class="form-group col-md-6">
                        <input type="text" v-model="bank.checkNo" id="check_no" class="form-control">
                    </div>
                    <div class="col-md-3">
                    </div>
                </div>

                <div class="form-group col-md-3 required text-right">
                    <label for="payment">Payment(TK)</label>
                </div>

                <div class="form-group col-md-6">
                    <input type="number" step="any" required v-model="payment" id="payment" class="form-control">
                </div>
                <div class="col-md-3">
                </div>

                <div class="form-group col-md-3 text-right">
                    <label for="remission">Remission(TK)</label>
                </div>

                <div class="form-group col-md-6">
                    <input type="number" step="any" v-model="remission" id="remission" class="form-control">
                </div>
                <div class="col-md-3">
                </div>

                <div class="form-group col-md-3 text-right">
                    <label for="adjustment">Adjustment(TK)</label>
                </div>

                <div class="form-group col-md-6">
                    <input type="number" step="any" v-model="adjustment" id="adjustment" class="form-control">
                </div>
                <div class="col-md-3">
                </div>

                <div class="form-group col-md-3 text-right">
                    <label for="paid_by">Paid By</label>
                </div>

                <div class="form-group col-md-6">
                    <input type="text" v-model="paidBy" id="paid_by" class="form-control">
                </div>
                <div class="col-md-3">
                </div>

                <div class="form-group col-md-9 text-right">
                    <button type="reset" class="btn btn-danger">Reset</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
export default {
    name: "CreateInstallmentComponent",
    props: ['bankAccounts', 'cashes', 'customers'],
    computed: {
        listUrl(){
            return baseURL + 'user/installmentCollection'
        },
    },
    data() {
        return {
            date: new Date().toISOString().slice(0, 10),
            customerId: null,
            cashId: null,
            customerBalance: null,
            hire_sale_id: null,
            balanceStatus: null,
            voucherNo: null,
            due: null,
            payment: null,
            hireSales: [],
            remission: 0,
            adjustment: 0,
            paidBy: null,
            installmentAmount: null,
            installmentAmountStatus: null,
            where: 'cash',
            bank: {
                accountId: null,
                checkNo: null,
                branchName: null,
                accountNo: null,
                issueDate: new Date().toISOString().substr(0, 10)
            }
        }
    },

    methods: {
        getCustomerDetails(id) {
            axios.post(baseURL + 'user/get-details-from-customer/' + id)
            .then(response => {
                this.customerBalance = this.customers.find(customer => customer.id == id).balance
                this.hireSales = response.data
                if (this.customerBalance >= 0){
                    this.balanceStatus = 'Receivable'
                }else {
                    this.balanceStatus = 'Payable'
                }
            })
        },

        getVoucherDetails() {
            if (this.voucherNo) {
                let hireSale = this.hireSales.find(hire_sale => hire_sale.voucher_no == this.voucherNo)
                this.voucherNo = hireSale.voucher_no
                this.hire_sale_id = hireSale.id
                this.due = hireSale.total_due
                this.installmentAmount = hireSale.installment_amount
                this.installmentAmountStatus = "Monthly"
            }
        },

        saveInstallment() {
            if (!this.hire_sale_id){
                alert('No installment available')
                return
            }
            this.$awn.asyncBlock(
                axios.post(baseURL + 'user/installmentCollection', {
                    date: this.date,
                    customer_id: this.customerId,
                    cash_id: this.cashId,
                    customer_balance: this.customerBalance,
                    hire_sale_id: this.hire_sale_id,
                    due: this.due,
                    payment_amount: this.payment,
                    remission: this.remission,
                    adjustment: this.adjustment,
                    paid_by: this.paidBy,
                    installment_amount: this.installmentAmount,
                    where: this.where,
                    bank_account_id: this.bank.accountId,
                    check_number: this.bank.checkNo,
                }),
                response => {
                    this.$awn.success('Installment given successfully')
                    window.location.href = baseURL + 'user/hire-sale/' + response.data.voucher_no
                },
                error => {
                    console.log(error)
                }
            )
        },
    },

    mounted() {
        this.cashId = this.cashes[0].id
        // console.log(this.bankAccounts)
    }
}
</script>

<style scoped>

</style>
