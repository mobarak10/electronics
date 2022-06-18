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
            <form method="post" @submit.prevent="save" class="row">
                <!-- start get supplier/customer -->
                <div class="form-group col-md-12 required">
                    <label for="holders">Holder</label>
                    <select id="holders" class="form-control" v-model="manageDue.holder.id" @change="getBalance(manageDue.holder.id, 'holder')" required>
                        <option selected disabled>Choose one</option>
                        <option v-for="(holder, index) in holders" :value="holder.id" :key="index">{{ holder.name }}</option>
                    </select>
                </div>
                <!-- end get supplier/customer -->

                 <!-- balance for supplier/customer start -->
                <div class="col-md-12">
                    <p class="d-block bg-dark text-light p-1 px-2" v-if="manageDue.holder.balance != null">
                        BDT {{ manageDue.holder.balance }} {{ partyBalanceStatus }}
                    </p>
                </div>
                <!-- balance for supplier/customer end -->

                <div class="form-group col-md-6 required">
                    <label for="date">Date</label>
                    <input v-model="manageDue.date" type="date" class="form-control" id="date" required>
                </div>

                <div class="form-group col-md-6 required">
                    <label for="payment">Pay / Receive Amount</label>
                    <div class="input-group">
                        <input v-model="manageDue.amount" type="number" class="form-control" id="payment" placeholder="Enter Amount (BDT)" required>
                        <div class="input-group-append">
                            <select class="btn btn-primary dropdown-toggle" v-model="manageDue.type">
                                <option class="dropdown-item" value="paid">{{ (holder_genus == 'supplier') ? 'Pay' : 'Receive' }}</option>
                                <option class="dropdown-item" value="received">{{ (holder_genus == 'supplier') ? 'Receive' : 'Pay' }}</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- select cash/bank start -->
                <div class="col-md-12">
                    <div class="form-check form-check-inline">
                        <input type="radio" style="cursor: pointer;" class="form-check-input" name="managedueFrom" v-model="manageDue.where" id="cash" value="cash">
                        <label for="cash" class="form-check-label">Cash</label>
                    </div>

                    <div class="form-check form-check-inline">
                        <input type="radio" style="cursor: pointer;" class="form-check-input" name="managedueFrom" v-model="manageDue.where" id="bank" value="bank">
                        <label for="bank" class="form-check-label">Bank</label>
                    </div>
                </div>
                <!-- select cash/bank end -->

                <!-- start cash part -->
                <div class="col-md-12 pt-3" v-if="manageDue.where == 'cash'">
                    <div class="form-group required">
                        <label for="cash">Cash</label>
                        <select v-model="manageDue.cash.id" @change="getBalance(manageDue.cash.id, 'cash')" class="form-control" required>
                            <option selected disabled>Chosse one</option>
                            <option v-for="(cash, index) in cashes" :value="cash.id" :key="index">{{ cash.title }}</option>
                        </select>
                    </div>
                </div>
                <!-- end cash part -->

                <!-- start bank part -->
                <div class="col-md-12 pt-3" v-if="manageDue.where == 'bank'">
                    <div class="row">
                        <div class="form-group col-md-6 required">
                            <label for="bank">Bank</label>
                            <select v-model="manageDue.bank.id" @change="getBankAccount(manageDue.bank.id)" class="form-control" required>
                                <option selected disabled>Chosse one</option>
                                <option v-for="(bank, index) in banks" :value="bank.id" :key="index">{{ bank.name }}</option>
                            </select>
                        </div>

                        <div class="form-group col-md-6 required">
                            <label for="bank-account">Bank Account</label>
                            <select id="bank-account" v-model="manageDue.bank.account" @change="getBalance(manageDue.bank.account, 'bank')" class="form-control" required>
                                <option value="" selected disabled>Choose one</option>
                                <option v-for="(account, index) in accounts" :value="account.id" :key="index">{{ account.account_name }}</option>
                            </select>
                        </div>

                        <!-- balance for bank start -->
                        <div class="col-md-12">
                            <p class="d-block bg-dark text-light p-1 px-2" v-if="manageDue.bank.balance != null">BDT {{ manageDue.bank.balance }} </p>
                        </div>
                        <!-- balance for bank end -->

                        <div class="form-group col-md-6">
                            <label for="date">Date</label>
                            <input v-model="manageDue.bank.date" type="date" class="form-control" id="date">
                        </div>

                        <div class="form-group col-md-6">
                            <label for="check">Check number</label>
                            <input v-model="manageDue.bank.check" type="text" class="form-control" id="check">
                        </div>
                    </div>
                </div>
                <!-- end bank part -->

                <!-- balance for cash start -->
                <div class="col-md-12">
                    <p class="d-block bg-dark text-light p-1 px-2" v-if="manageDue.cash.balance != null">BDT {{ manageDue.cash.balance }} </p>
                </div>
                <!-- balance for cash end -->
                <div class="form-group col-md-12">
                    <label for="description">Description</label>
                    <textarea v-model="manageDue.description" class="form-control" id="description" placeholder="Write something (optional)"></textarea>
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
        name: 'DueManagementComponent',
        props: ['holders', 'banks', 'cashes', 'holder_genus'],
        computed: {
            listURL(){
                return baseURL + 'dueManagement/' + ((window.location.pathname.indexOf('supplier') > 0) ? 'supplier' : 'customer') + '/index'
                console.log(listURL);
            },
            partyBalanceStatus() {
                if(this.manageDue.holder_type == null) return;

                if(this.manageDue.holder_type == 'supplier') {
                    if(this.manageDue.holder.balance >= 0) {
                        return "Receiveable";
                    } else {
                        return "Payable";
                    }

                } else if(this.manageDue.holder_type == 'customer') {
                    if(this.manageDue.holder.balance >= 0) {
                        return "Payable";
                    } else {
                        return "Receiveable";
                    }
                }
            }
        },
        data() {
            return {
                accounts: [],
                manageDue: {
                    date: null,
                    amount: null,
                    holder_type: null,
                    // genus: null,
                    type: 'paid',
                    where: 'cash',
                    cash: {
                        id: null,
                        balance: null,
                    },
                    bank: {
                        id: null,
                        account: null,
                        date: null,
                        check: null,
                        balance: null,
                    },
                    holder: {
                        id: null,
                        balance: null,
                    },
                    description: '',
                }
            }
        },

        methods: {
            getBankAccount(id){
                this.$awn.asyncBlock(
                    axios.post(baseURL + 'get-accounts-from-bank', {
                        id: id
                    }),
                    response => {
                        //set data for bank
                        this.accounts = response.data;

                        // set null value for cash
                        this.manageDue.cash.id = null;
                        this.manageDue.cash.balance = null;

                        // console response
                        console.log(response.data);
                    },
                    reason => {
                        console.log(reason)
                    })
            },

            getBalance(id, where){
                if(where == 'holder') {
                    // console.log(id);
                    this.$awn.asyncBlock(
                        axios.post(baseURL + 'get-details-from-party', {
                            id: id
                        }),
                        response => {
                            // set data
                            this.manageDue.holder.balance = response.data.balance;
                            this.manageDue.holder_type    = response.data.genus;

                            // console
                            console.log(response.data);
                            console.log(response.data.genus);
                        },
                        reason => {
                            console.log(reason)
                        })
                }
                else if(where == "cash"){
                     this.$awn.asyncBlock(
                        axios.post(baseURL + 'get-details-from-cash', {
                            id: id
                        }),
                        response => {
                            // set data
                            this.manageDue.cash.balance = response.data.amount;

                            // set null value for cash
                            this.manageDue.bank.id = null;
                            this.manageDue.bank.account = null;
                            this.manageDue.bank.balance = null;

                            // console
                            console.log(response.data);
                        },
                        reason => {
                            console.log(reason)
                        })
                }
                else{
                    this.$awn.asyncBlock(
                        axios.post(baseURL + 'get-details-from-account', {
                            id: id
                        }),
                        response => {
                            // set data
                            this.manageDue.bank.balance = response.data.balance;

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
            save() {
                this.$awn.asyncBlock(
					axios.post(baseURL + 'dueManagement', this.manageDue),
					response => {
                        // console
                        console.log(response.data);
						location.reload(true);

                        // flase message
						this.$awn.success("Due manage successfully.");
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
            console.log(this.holders);
            console.log(this.banks);
            console.log(this.cashes);
            console.log(this.holder_genus);
        }
    }
</script>
