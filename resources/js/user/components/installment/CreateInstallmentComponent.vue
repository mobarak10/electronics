<template>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="m-0">Installment Collection </h5>

            <div class="btn-group" role="group" aria-level="Action area">
                <a href="#" title="show due manage list" class="btn btn-success" style="margin-right: 5px">
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
                    <label for="partyId">Name</label>
                </div>

                <div class="form-group col-md-6">
                    <v-select
                        :options="customers"
                        v-model="partyId"
                        id="partyId"
                        :reduce="customer => customer.id"
                        @input="getCustomerDetails(partyId)"
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
                            <input v-model="partyBalance" type="text" disabled class="form-control" id="balance">
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
                    <input type="text" id="voucher_no" disabled class="form-control" v-model="voucherNo">
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

<!--                    <div class="form-group col-md-3 required text-right">-->
<!--                        <label for="branch">Branch Name</label>-->
<!--                    </div>-->

<!--                    <div class="form-group required col-md-6">-->
<!--                        <input type="text" id="branch" v-model="bank.branchName" class="form-control">-->
<!--                    </div>-->
<!--                    <div class="col-md-3">-->
<!--                    </div>-->

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

<!--                <div class="form-group col-md-3 text-right">-->
<!--                    <label for="total_due">Total Due(TK)</label>-->
<!--                </div>-->

<!--                <div class="form-group col-md-6">-->
<!--                    <div class="row">-->
<!--                        <div class="col-md-7">-->
<!--                            <input type="text" disabled class="form-control" id="total_due">-->
<!--                        </div>-->

<!--                        <div class="col-md-5">-->
<!--                            <input type="text" disabled class="form-control">-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--                <div class="col-md-3">-->
<!--                </div>-->

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
    data() {
        return {
            date: new Date().toISOString().slice(0, 10),
            partyId: null,
            cashId: null,
            partyBalance: null,
            hire_sale_id: null,
            balanceStatus: null,
            voucherNo: null,
            due: null,
            payment: null,
            remission: null,
            adjustment: null,
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
        cashDetails(id) {
            console.log(id)
        },
        getCustomerDetails(id) {
            axios.post(baseURL + 'user/get-details-from-customer/' + id)
            .then(response => {
                if (response.data.installment_status === 0){
                    this.partyBalance = Math.abs(response.data.customer.balance)
                    this.voucherNo = response.data.voucher_no
                    this.hire_sale_id = response.data.id
                    this.due = response.data.total_due
                    this.installmentAmount = response.data.installment_amount
                    this.installmentAmountStatus = "Monthly"
                    if (response.data.customer.balance <= 0){
                        this.balanceStatus = 'Receivable'
                    }else {
                        this.balanceStatus = 'Payable'
                    }
                }
            })
        },
        saveInstallment() {
            if (!this.hire_sale_id){
                alert('No installment available')
                return
            }
            this.$awn.asyncBlock(
                axios.post(baseURL + 'user/installmentCollection', {
                    date: this.date,
                    party_id: this.partyId,
                    cash_id: this.cashId,
                    party_balance: this.partyBalance,
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
                    console.log(response.data)
                    this.partyBalance = null
                    this.balanceStatus = null
                    this.voucherNo = null
                    this.installmentAmountStatus = null
                    this.date = new Date().toISOString().substr(0, 10)
                    this.partyId = null
                    this.cashId = null
                    this.partyBalance = null
                    this.hire_sale_id = null
                    this.due = null
                    this.payment = null
                    this.remission = null
                    this.adjustment = null
                    this.paidBy = null
                    this.installmentAmount = null
                    this.where = null
                    this.bank.accountId = null
                    this.bank.checkNo = null
                    this.$awn.success('Installment given successfully')
                },
                error => {

                }
            )
        },
    },

    mounted() {
        this.cashId = this.cashes[0].id
        console.log(this.bankAccounts)
    }
}
</script>

<style scoped>

</style>
