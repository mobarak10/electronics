<template>
    <div>
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="m-0">Update income</h5>

                <div class="btn-group" role="group" aria-level="Action area">
                    <a :href="listUrl" class="btn btn-primary">
                        <i class="fa fa-list" aria-hidden="true"></i>
                    </a>
                </div>
            </div>

            <div class="card-body">
                <form method="post" @submit.prevent="update" class="row">
                    <div class="form-group col-md-6 required">
                        <label for="date">Date</label>
                        <input type="date" v-model="date" class="form-control" id="date" required>
                    </div>

                    <div class="form-group col-md-6 required">
                        <label for="amount">Amount</label>
                        <input type="number" v-model="amount" class="form-control" id="amount" placeholder="Enter amount (BDT)" step="any" required>
                    </div>

                    <div class="form-group col-md-12 required">
                        <label for="incomeSectorId">Income Sector</label>
                         <v-select
                            :options="incomeSectors"
                            label="sector_name"
                            v-model="incomeSectorId"
                            placeholder="Select sector"
                            :reduce="sector => sector.id"
                            class="bg-white"
                        ></v-select>
                    </div>

                    <div class="col-md-12">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" v-model="where" id="incomeToCash" value="cash">
                            <label class="form-check-label" for="incomeToCash">Cash</label>
                        </div>

                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" v-model="where" id="incomeToBank" value="bank">
                            <label class="form-check-label" for="incomeToBank">Bank </label>
                        </div>
                    </div>

                    <!-- cash part start -->
                    <div class="col-md-12 pt-3" v-if="where === 'cash'">
                        <div class="form-group required">
                            <label for="cash_id">Cash </label>
                            <select v-model="cash.id" class="form-control" @change="getBalance(cash.id, 'cash')" id="cash_id" required>
                                <option value="" selected disabled>Choose one</option>
                                <option v-for="(cash, index) in cashes" :value="cash.id" :key="index">{{ cash.title }}</option>
                            </select>
                        </div>
                    </div>
                    <!-- cash part end -->

                    <!-- bank part start -->
                    <div class="col-md-12 pt-3" v-if="where === 'bank'">
                        <div class="row">
                            <div class="form-group col-md-6 required">
                                <label for="bank_id">Bank </label>
                                <select v-model="bank.id" @change="getBankAccount(bank.id)" class="form-control" id="bank_id" required>
                                    <option value="" selected disabled>Choose one</option>
                                    <option v-for="(bank, index) in banks" :value="bank.id" :key="index">{{ bank.name }}</option>
                                </select>
                            </div>

                            <div class="form-group col-md-6 required">
                                <label for="bank_account">Bank Account</label>
                                <select v-model="bank.account" id="bank_account" @change="getBalance(bank.account, 'bank')" class="form-control" required>
                                    <option value="" selected disabled>Choose one</option>
                                    <option v-for="(account, index) in accounts" :value="account.id" :key="index">{{ account.account_name }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- bank part end -->

                    <!-- balance start -->
                    <div class="col-md-12">
                        <p class="d-block bg-dark text-light p-1 px-2" v-if="bank.balance != null">BDT {{ bank.balance }} </p>
                        <p class="d-block bg-dark text-light p-1 px-2" v-if="cash.balance != null">BDT {{ cash.balance }} </p>
                    </div>
                    <!-- balance end -->
                    <div class="form-group col-md-12 required">
                        <label for="income_by">Income By</label>
                        <input type="text" v-model="income_by" class="form-control" id="income_by">
                    </div>

                    <div class="form-group col-md-12">
                        <label for="description">Description</label>
                        <textarea v-model="description" class="form-control" id="description" placeholder="Enter Expense description (optional)"></textarea>
                    </div>

                    <div class="form-group col-md-12 text-right">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "IncomeCreateComponent",
    props: {
        'cashes': Array,
        'banks': Array,
        'incomeSectors': Array,
        income: Object,
    },
    computed: {
        listUrl(){
            return baseURL + 'user/incomeRecord'
        }
    },
    data() {
        return {
            id: null,
            date: new Date().toISOString().substring(0, 10),
            amount: null,
            income_by: null,
            incomeSectorId: null,
            accounts: [],
            where: 'cash',
            cash: {
                id: null,
                balance: null
            },
            bank: {
                id: null,
                account: null,
                balance: null
            },
            description: ''
        }
    },

    methods: {
        initialValues(){
            console.log(this.income);
            this.id = this.income.id
            this.date = this.income.format_date
            this.amount = this.income.amount
            this.incomeSectorId = this.income.income_sector_id
            this.income_by = this.income.income_by
            this.description = this.income.description
            if(this.income.cash_id) {
                this.where = 'cash'
                this.cash.balance = this.income.cash.amount
                this.cash.id = this.income.cash_id
            }else{
                this.where = 'bank'
                this.bank.id = this.income.bank_id
                this.cash.id = this.income.cash_id
            }
        },
        update(){
            this.$awn.asyncBlock(
                axios.patch(baseURL + 'user/incomeRecord/'+this.id, {
                    date: this.date,
                    amount: this.amount,
                    income_sector_id: this.incomeSectorId,
                    where: this.where,
                    income_by: this.income_by,
                    cash_id: this.cash.id,
                    bank_id: this.bank.id,
                    bank_account_id: this.bank.account,
                    description: this.description
                }),
                response => {
                    // console
                    console.log(response.data);
                    window.location.href = baseURL+ '/user/incomeRecord'
                    // flash message
                    this.$awn.success("Expense record successfully store.");
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
        getBankAccount(id){
            this.$awn.asyncBlock(
                axios.post(baseURL + 'user/get-accounts-from-bank', {
                    id: id
                }),
                response => {
                    //set data for bank
                    this.accounts = response.data;

                    // set null value for cash
                    this.cash.id = null;
                    this.cash.balance = null;

                    // console response
                    console.log(response.data);
                },
                reason => {
                    console.log(reason)
                }
            )
        },
        getBalance(id, where){
            if (where === 'cash') {
                this.$awn.asyncBlock(
                    axios.post(baseURL + 'user/get-details-from-cash', {
                        id: id
                    }),
                    response => {
                        // set data
                        this.cash.balance = response.data.amount;

                        // set null value for cash
                        this.bank.id = null;
                        this.bank.account = null;
                        this.bank.balance = null;

                        // console
                        console.log(response.data);
                    },
                    reason => {
                        console.log(reason)
                    }
                )
            }else{
                this.$awn.asyncBlock(
                    axios.post(baseURL + 'user/get-details-from-account', {
                        id: id
                    }),
                    response => {
                        // set data
                        this.bank.balance = response.data.balance;

                        // set null value for cash
                        this.cash.id = null;
                        this.cash.balance = null;

                        // console
                        console.log(response.data);
                    },
                    reason => {
                        console.log(reason)
                    }
                )
            }
        }
    },
    mounted() {
        this.initialValues();
    }
}
</script>

<style scoped>

</style>
