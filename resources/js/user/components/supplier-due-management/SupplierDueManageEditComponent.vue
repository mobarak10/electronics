<template>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="m-0">Manage Due </h5>

            <div class="btn-group" role="group" aria-level="Action area">
                <a :href="listURL" title="show due manage list" class="btn btn-success" style="margin-right: 5px">
                    <i class="fa fa-list" aria-hidden="true"></i>
                </a>
            </div>
        </div>

        <div class="card-body">
            <form method="post" @submit.prevent="update" class="row">
                <!-- start get supplier -->
                <div class="form-group col-md-12 required">
                    <label for="suppliers">Supplier</label>
                    <select v-model="supplierId" @change="getSupplierBalance" class="form-control" id="suppliers">
                        <option :value="null">Choose One</option>
                        <option v-for="(supplier, index) in suppliers" :value="supplier.id" :key="index">{{ supplier.name }}</option>
                    </select>
                </div>
                <!-- end get supplier -->

                <!-- balance for supplier start -->
                <div class="col-md-12" v-if="supplierBalance">
                    <p class="d-block bg-dark text-light p-1 px-2">
                        BDT {{ Math.abs(supplierBalance) }} {{ supplierBalance > 0 ? 'Receivable' : 'Payable' }}
                    </p>
                </div>
                <!-- balance for supplier end -->

                <div class="form-group col-md-6 required">
                    <label for="date">Date</label>
                    <input type="date" v-model="date" class="form-control" id="date" required>
                </div>

                <div class="form-group col-md-6 required">
                    <label for="payment">Pay / Receive Amount</label>
                    <div class="input-group">
                        <input type="number" v-model="amount" class="form-control" id="payment" placeholder="Enter Amount (BDT)" required>
                        <div class="input-group-append">
                            <select v-model="paymentType" class="btn btn-primary dropdown-toggle">
                                <option class="dropdown-item" value="paid">Pay</option>
                                <option class="dropdown-item" value="received">Receive</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- select cash/bank start -->
                <div class="col-md-12">
                    <div class="form-check form-check-inline">
                        <input type="radio" style="cursor: pointer;" v-model="where" class="form-check-input" id="due-cash" value="cash">
                        <label for="due-cash" class="form-check-label">Cash</label>
                    </div>

                    <div class="form-check form-check-inline">
                        <input type="radio" style="cursor: pointer;" v-model="where" class="form-check-input" id="due-bank" value="bank">
                        <label for="due-bank" class="form-check-label">Bank</label>
                    </div>
                </div>
                <!-- select cash/bank end -->

                <!-- start cash part -->
                <div v-if="where === 'cash'" class="col-md-12 pt-3">
                    <div class="form-group required">
                        <label for="cash">Cash</label>
                        <select class="form-control" id="cash" v-model="cashId" @change="getCashBalance" required>
                            <option selected disabled :value="null">Choose one</option>
                            <option v-for="(cash, index) in cashes" :value="cash.id" :key="index">{{ cash.title }}</option>
                        </select>
                    </div>
                </div>
                <!-- end cash part -->

                <!-- start bank part -->
                <div v-if="where === 'bank'" class="col-md-12 pt-3">
                    <div class="row">
                        <div class="form-group col-md-6 required">
                            <label for="bank">Bank</label>
                            <select class="form-control" id="bank" v-model="bankId" @change="getBankAccount(bankId)" required>
                                <option selected disabled :value="null">Choose one</option>
                                <option v-for="(bank, index) in banks" :value="bank.id" :key="index">{{ bank.name }}</option>
                            </select>
                        </div>

                        <div class="form-group col-md-6 required">
                            <label for="bank-account">Bank Account</label>
                            <select id="bank-account" v-model="bankAccountId" @change="getAccountBalance" class="form-control" required>
                                <option :value="null" selected disabled>Choose one</option>
                                <option v-for="(account, index) in accounts" :value="account.id" :key="index">{{ account.account_name }}</option>
                            </select>
                        </div>

                        <!-- balance for bank start -->
                        <div class="col-md-12" v-if="bankAccountBalance">
                            <p class="d-block bg-dark text-light p-1 px-2">BDT {{ bankAccountBalance }} </p>
                        </div>
                        <!-- balance for bank end -->
                    </div>
                </div>
                <!-- end bank part -->

                <!-- balance for cash start -->
                <div class="col-md-12" v-if="cashBalance">
                    <p class="d-block bg-dark text-light p-1 px-2">BDT {{ cashBalance }} </p>
                </div>
                <!-- balance for cash end -->
                <div class="form-group col-md-12">
                    <label for="description">Description</label>
                    <textarea class="form-control" v-model="description" id="description" placeholder="Write something (optional)"></textarea>
                </div>

                <div class="form-group col-md-12 text-right">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
export default {
    name: "SupplierDueManageEditComponent",
    props: ['suppliers', 'banks', 'cashes', 'dueManage'],
    computed: {
        listURL(){
            return baseURL + 'user/supplierDueManage'
        },
    },
    data() {
        return {
            accounts: [],
            supplierId: null,
            supplierBalance: null,
            date: new Date().toISOString().substr(0, 10),
            amount: null,
            paymentType: 'received',
            where: 'cash',
            cashId: null,
            bankId: null,
            bankAccountId: null,
            bankAccountBalance: null,
            cashBalance: null,
            description: null,
        }
    },

    methods: {
        getSupplierBalance() {
            if(this.supplierId){
                this.supplierBalance = this.suppliers.find(supplier => supplier.id == this.supplierId).balance
            }
        },

        getCashBalance(){
            this.cashBalance = this.cashes.find(cash => cash.id == this.cashId).amount;
            this.bankId = null;
            this.bankAccountId = null;
            this.bankAccountBalance = null;
        },

        getBankAccount(bank_id) {
            this.accounts = this.banks.find(bank => bank.id == bank_id).bank_accounts
            this.cashId = null;
            this.cashBalance = null;
        },

        getAccountBalance() {
            this.bankAccountBalance = this.accounts.find(account => account.id == this.bankAccountId).balance
            this.cashId = null;
            this.cashBalance = null;
        },

        update() {
            this.$awn.asyncBlock(
                axios.put(baseURL + 'user/supplierDueManage/' + this.dueManage.id, {
                    'party_id': this.supplierId,
                    "date": this.date,
                    "amount": this.amount,
                    "payment_type": this.paymentType,
                    "paid_from": this.where,
                    "cash_id": this.cashId,
                    "bank_account_id": this.bankAccountId,
                    "description": this.description,
                }),
                response => {
                    console.log(response.data)
                    // flash message
                    this.$awn.success("Due manage successfully.");

                    window.location.href = baseURL + 'user/supplierDueManage'
                },
                error => {
                    if (error.response.status === 422) { // validation error
                        this.errors = error.response.data.errors
                        this.$awn.alert('Opps! Enter the valid information of product')
                    } else {
                        this.$awn.alert('Opps! something went wrong. Try again later')
                    }
                }
            )
        },

        initialValues() {
            this.supplierId = this.dueManage.party_id;
            this.getSupplierBalance()
            this.date = this.dueManage.formatted_date;
            this.amount = Math.abs(this.dueManage.amount);
            this.description = this.dueManage.description;
            this.paymentType = this.dueManage.amount >= 0 ? 'received' : 'paid';
            if (this.dueManage.paid_from == 'cash'){
                this.where = 'cash';
                this.cashId = this.dueManage.cash_id
                this.cashBalance = this.cashes.find(cash => cash.id == this.dueManage.cash_id).amount
            }else{
                this.where = 'bank';
                this.bankId = this.banks.find(bank => bank.bank_accounts.find(account => account.id == this.dueManage.bank_account_id)).id
                this.getBankAccount(this.bankId)
                this.bankAccountId = this.dueManage.bank_account_id
                this.getAccountBalance()
            }
        },

    },

    mounted() {
        this.initialValues();
    }
}
</script>

<style scoped>

</style>
