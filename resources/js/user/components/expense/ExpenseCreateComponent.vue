<template>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="m-0">Add new expense</h5>
            
            <div class="btn-group" role="group" aria-level="Action area">
                <a href="#" class="btn btn-primary">
                    <i class="fa fa-chevron-left" aria-hidden="true"></i> &nbsp; Back
                </a>
            </div>
        </div>

        <div class="card-body">
            <form method="post" @submit.prevent="save" class="row">
                <div class="form-group col-md-6 required">
                    <label for="date">Date</label>
                    <input type="date" v-model="expense.date" class="form-control" id="date" required>
                </div>

                <div class="form-group col-md-6 required">
                    <label for="amount">Amount</label>
                    <input type="number" v-model="expense.amount" class="form-control" id="amount" placeholder="Enter amount (BDT)" step="any" required>
                </div>

                <div class="form-group col-md-6 required">
                    <label for="expenseGroup">Expense group</label>
                    <select v-model="expense.glAccount" @change="getGLAccountHead(expense.glAccount)" id="expenseGroup" class="form-control" required>
                        <option value="" selected disabled>Choose Expense Group</option>
                        <option v-for="(account, index) in glAccounts" :value="account.id" :key="index">{{ account.name }}</option>
                    </select>
                </div>

                <div class="form-group col-md-6 required">
                    <label for="expenseGroupHead">Expense group head</label>
                    <select v-model="expense.glAccountHead" id="expenseGroupHead" class="form-control" required>
                        <option value="" selected disabled>Choose Expense Group Head</option>
                        <option v-for="(head, index) in glAccountHeads" :value="head.id" :key="index">{{ head.name }}</option>
                    </select>
                </div>

                <div class="col-md-12">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="expenseFrom" v-model="expense.where" id="expenseFromCash" value="cash">
                        <label class="form-check-label" for="expenseFromCash">Cash</label>
                    </div>

                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="expenseFrom" v-model="expense.where" id="expenseFromBank" value="bank">
                        <label class="form-check-label" for="expenseFromBank">Bank </label>
                    </div>
                </div>

                <!-- cash part start -->
                <div class="col-md-12 pt-3" v-if="expense.where == 'cash'">
                    <div class="form-group required">
                        <label>Cash </label>
                        <select v-model="expense.cash.id" @change="getBalance(expense.cash.id, 'cash')" class="form-control" required>
                            <option value="" selected disabled>Choose one</option>
                            <option v-for="(cash, index) in cashes" :value="cash.id" :key="index">{{ cash.title }}</option>
                        </select>
                    </div>
                </div>
                <!-- cash part end -->

                <!-- bank part start -->
                <div class="col-md-12 pt-3" v-if="expense.where == 'bank'">
                    <div class="row">
                        <div class="form-group col-md-6 required">
                            <label>Bank </label>
                            <select v-model="expense.bank.id" @change="getBankAccount(expense.bank.id)" class="form-control" required>
                                <option value="" selected disabled>Choose one</option>
                                <option v-for="(bank, index) in banks" :value="bank.id" :key="index">{{ bank.name }}</option>
                            </select>
                        </div>

                        <div class="form-group col-md-6 required">
                            <label>Bank Account</label>
                            <select v-model="expense.bank.account" @change="getBalance(expense.bank.account, 'bank')" class="form-control" required>
                                <option value="" selected disabled>Choose one</option>
                                <option v-for="(account, index) in accounts" :value="account.id" :key="index">{{ account.account_name }}</option>
                            </select>
                        </div>
                    </div>
                </div>
                <!-- bank part end -->

                <!-- balance start -->
                <div class="col-md-12">
                    <p class="d-block bg-dark text-light p-1 px-2" v-if="expense.bank.balance != null">BDT {{ expense.bank.balance }} </p>
                    <p class="d-block bg-dark text-light p-1 px-2" v-if="expense.cash.balance != null">BDT {{ expense.cash.balance }} </p>
                </div>
                <!-- balance end -->

                <div class="form-group col-md-12">
                    <label for="description">Description</label>
                    <textarea v-model="expense.description" class="form-control" id="description" placeholder="Enter Expense description (optional)"></textarea>
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
        name: "CreateExpenseComponent",
        props: ['cashes', 'banks', 'glAccounts'],
        data() {
            return {
                accounts: [],
                glAccountHeads: [],
                expense: {
                    date: new Date().toISOString().substring(0, 10),
                    amount: null,
                    where: 'cash',
                    glAccount: null,
                    glAccountHead: null,
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
            }
        },
        methods: {
            getBankAccount(id) {
                this.$awn.asyncBlock(
                    axios.post(baseURL + 'user/get-accounts-from-bank', {
                        id: id
                    }),
                    response => {
                        // set data
                        this.accounts = response.data;

                        // set null value for cash
                        // this.expense.cash.id = null;
                        // this.expense.cash.balance = null;

                        // console
                        console.log(response.data);
                    },
                    reason => {
                        console.log(reason)
                    })
            },
            getBalance(id, where) {
                if(where == 'cash') {
                    this.$awn.asyncBlock(
                        axios.post(baseURL + 'user/get-details-from-cash', {
                            id: id
                        }),
                        response => {
                            // set data
                            this.expense.cash.balance = response.data.amount;

                            // set null value for bank
                            this.expense.bank.id = null;
                            this.expense.bank.account = null;
                            this.expense.bank.balance = null;

                            // console
                            console.log(response.data);
                        },
                        reason => {
                            console.log(reason)
                        })
                } else {
                    this.$awn.asyncBlock(
                        axios.post(baseURL + 'user/get-details-from-account', {
                            id: id
                        }),
                        response => {
                            // set data
                            this.expense.bank.balance = response.data.balance;

                            // set null value for cash
                            this.manageDue.cash.id = null;
                            this.manageDue.cash.balance = null;

                            // console
                            console.log(response.data);
                        },
                        reason => {
                            console.log(reason)
                        })
                }
            },
            getGLAccountHead(id) {
                this.$awn.asyncBlock(
                    axios.post(baseURL + 'user/get-gl-account-heads', {
                        id: id
                    }),
                    response => {
                        // set data
                        this.glAccountHeads = response.data;

                        // console
                        console.log(response.data);
                    },
                    reason => {
                        console.log(reason)
                    })
            },
            save() {
                this.$awn.asyncBlock(
					axios.post(baseURL + 'user/expenditure', this.expense),
					response => {
                        // console
                        console.log(response.data);
                        
                        // flase message
						// this.$awn.success("Expense successfully created.");
						location.reload(true);
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
            }
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
