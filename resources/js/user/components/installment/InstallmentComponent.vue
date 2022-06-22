<template>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="m-0">{{ lang.installment_collection }} </h5>

            <div class="btn-group" role="group" aria-level="Action area">
                <a :href="listUrl" title="show due manage list" class="btn btn-success" style="margin-right: 5px">
                    <i class="fa fa-list" aria-hidden="true"></i>
                </a>
            </div>
        </div>

        <div class="card-body">
            <form method="post" @submit.prevent="saveInstallment" class="row">
                <div class="form-group col-md-3 required text-right">
                    <label for="date">{{ lang.date }}</label>
                </div>

                <div class="form-group col-md-6">
                    <input v-model="date" type="date" class="form-control" id="date" required>
                </div>
                <div class="col-md-3">
                </div>

                <div class="form-group col-md-3 required text-right">
                    <label for="customerId">{{ lang.name }}</label>
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
                    <label for="balance">{{ lang.balance }}</label>
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
                    <label for="voucher_no">{{ lang.voucher_no }}</label>
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
                    <label for="due">{{ lang.due }}</label>
                </div>

                <div class="form-group col-md-6">
                    <input type="text" id="due" disabled class="form-control" v-model="due">
                </div>
                <div class="col-md-3">
                </div>

                <div class="form-group col-md-3 text-right">
                    <label for="installment_amount">{{ lang.installment }} {{ lang.amount }}</label>
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
                   <label for="method">{{ lang.collection_method }}</label>
               </div>

               <div class="form-group col-md-6">
                   <select v-model="where" id="method" class="form-control">
                       <option value="cash">{{ lang.cash }}</option>
                       <option value="bank">{{ lang.bank }}</option>
                       <option value="bkash">{{ lang.bkash }}</option>
                   </select>
               </div>
               <div class="col-md-3">
               </div>

                <div v-if="where === 'cash'" class="row w-100 mx-0">
                    <div class="form-group col-md-3 required text-right">
                        <label for="cash">{{ lang.cash }}</label>
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
                        <label for="bkash">{{ lang.bkash }}</label>
                    </div>

                    <div class="form-group col-md-6">
                        <input type="text" v-model="bkashNo" id="bkash" class="form-control">
                    </div>
                    <div class="col-md-3">
                    </div>
                </div>

                <div v-if="where === 'bank'" class="row w-100 mx-0">
                    <div class="form-group col-md-3 required text-right">
                        <label for="bank">{{ lang.bank }}</label>
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
                        <label for="issue_date">{{ lang.issue_date }}</label>
                    </div>

                    <div class="form-group col-md-6">
                        <input type="date" id="issue_date" v-model="bank.issueDate" class="form-control">
                    </div>
                    <div class="col-md-3">
                    </div>

                    <div class="form-group col-md-3 text-right">
                        <label for="check_no">{{ lang.check_no }}</label>
                    </div>

                    <div class="form-group col-md-6">
                        <input type="text" v-model="bank.checkNo" id="check_no" class="form-control">
                    </div>
                    <div class="col-md-3">
                    </div>
                </div>

                <div class="form-group col-md-3 required text-right">
                    <label for="payment">{{ lang.payment }} {{ lang.amount }}</label>
                </div>

                <div class="form-group col-md-6">
                    <input type="number" step="any" required v-model="payment" id="payment" class="form-control">
                </div>
                <div class="col-md-3">
                </div>

                <div class="form-group col-md-3 text-right">
                    <label for="remission">{{ lang.remission }} {{ lang.tk }}</label>
                </div>

                <div class="form-group col-md-6">
                    <input type="number" step="any" v-model="remission" id="remission" class="form-control">
                </div>
                <div class="col-md-3">
                </div>

                <div class="form-group col-md-3 text-right">
                    <label for="adjustment">{{ lang.adjustment }} {{ lang.tk }}</label>
                </div>

                <div class="form-group col-md-6">
                    <input type="number" step="any" v-model="adjustment" id="adjustment" class="form-control">
                </div>
                <div class="col-md-3">
                </div>

                <div class="form-group col-md-3 text-right">
                    <label for="paid_by">{{ lang.paid_by }}</label>
                </div>

                <div class="form-group col-md-6">
                    <input type="text" v-model="paidBy" id="paid_by" class="form-control">
                </div>
                <div class="col-md-3">
                </div>

                <div class="form-group col-md-9 text-right">
                    <button type="reset" class="btn btn-danger">{{ lang.reset }}</button>
                    <button type="submit" class="btn btn-primary">{{ lang.save }}</button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
export default {
    name: "InstallmentComponent",
    props: ['bankAccounts', 'oldInstallment', 'cashes', 'lang', 'customers'],
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
            installmentId: null,
            from: 'new',
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
            return new Promise((resolve, reject) => {
                axios.post(baseURL + 'user/get-details-from-customer', {
                    'id': id,
                    'from': this.from,
                })
                    .then(response => {
                        this.customerBalance = this.customers.find(customer => customer.id == id).balance
                        this.hireSales = response.data
                        if (this.customerBalance >= 0) {
                            this.balanceStatus = 'Receivable'
                        } else {
                            this.balanceStatus = 'Payable'
                        }
                        resolve();
                    })
            })
        },

        async initialValues() {
            if (this.oldInstallment) {
                console.log(this.oldInstallment)
                this.customerId = this.oldInstallment.customer_id
                this.from = 'old'
                this.installmentId = this.oldInstallment.id
                await this.getCustomerDetails(this.customerId)
                let hireSale = this.hireSales.find(hire_sale => hire_sale.id == this.oldInstallment.hire_sale_id)
                this.voucherNo = hireSale.voucher_no
                this.hire_sale_id = hireSale.id
                this.due = hireSale.total_due
                this.where = this.oldInstallment.installment_payment.payment_method
                this.cashId = this.oldInstallment.installment_payment.cash_id
                this.installmentAmount = hireSale.installment_amount
                this.installmentAmountStatus = "Monthly"
                this.payment = this.oldInstallment.payment_amount
                this.remission = this.oldInstallment.remission
                this.adjustment = this.oldInstallment.adjustment
                this.paidBy = this.oldInstallment.paid_by
            }
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
            const form = {};
            form.date = this.date;
            form.customer_id = this.customerId;
            form.cash_id = this.cashId;
            form.customer_balance = this.customerBalance;
            form.hire_sale_id = this.hire_sale_id;
            form.due = this.due;
            form.payment_amount = this.payment;
            form.remission = this.remission;
            form.adjustment = this.adjustment;
            form.paid_by = this.paidBy;
            form.installment_amount = this.installmentAmount;
            form.where = this.where;
            form.bank_account_id = this.bank.accountId;
            form.check_number = this.bank.checkNo;

            if (this.oldInstallment) {
                return this.updateInstallment(form)
            }else{
                return this.createInstallment(form)
            }
        },

        createInstallment(data) {
            this.$awn.asyncBlock(
                axios.post(baseURL + 'user/installmentCollection', data),
                response => {
                    this.$awn.success('Installment given successfully')
                    window.location.href = baseURL + 'user/hire-sale/' + response.data.voucher_no
                },
                error => {
                    console.log(error)
                }
            )
        },

        updateInstallment(data) {
            this.$awn.asyncBlock(
                axios.put(baseURL + 'user/installmentCollection/' + this.installmentId, data),
                response => {
                    this.$awn.success('Installment updated successfully')
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
        if (this.oldInstallment) {
            this.initialValues()
        }
    }
}
</script>

<style scoped>

</style>
