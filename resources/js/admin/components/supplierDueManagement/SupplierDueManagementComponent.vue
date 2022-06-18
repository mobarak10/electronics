<template>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="m-0">Manage Supplier Due</h5>
            
            <!-- 
            <div class="btn-group" role="group" aria-level="Action area">
                <a href="#" class="btn btn-primary">
                    <i class="fa fa-chevron-left" aria-hidden="true"></i> &nbsp; Back
                </a>
            </div>
            -->
        </div>

        <div class="card-body">
            <form method="post" @submit.prevent="save" class="row">
                <!-- supplier name -->
                <div class="form-group col-md-6">
                    <label for="supplier">Supplier Name</label>
                    <input type="text" readonly v-model="this.supplier.name" class="form-control" id="supplier">
                </div>
                <!--  -->

                <!-- supplier ballance -->
                <div class="form-group col-md-6">
                    <label for="supplier">Supplier Balance</label>
                    <input type="text" readonly v-model="this.supplier.balance" class="form-control" id="supplier">
                </div>
                <!--  -->

                <!-- date part start -->
                <div class="form-group col-md-6 required">
                    <label for="date">Date</label>
                    <input type="date" v-model="manageDue.date" class="form-control" id="date" required>
                </div>
                <!-- date part end -->

                <!-- amount -->
                <div class="form-group col-md-6 required">
                    <label for="amount">Amount</label>
                    <input type="number" v-model="manageDue.amount" class="form-control" id="amount" placeholder="Enter amount (BDT)" step="any" required>
                </div>

                <div class="col-md-12">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="manageDueFrom" v-model="manageDue.where" id="manageDueFromCash" value="cash">
                        <label class="form-check-label" for="manageDueFromCash">Cash</label>
                    </div>

                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="manageDueFrom" v-model="manageDue.where" id="manageDueFromBank" value="bank">
                        <label class="form-check-label" for="manageDueFromBank">Bank </label>
                    </div>
                </div>

                <!-- cash part start -->
                <div class="col-md-12 pt-3" v-if="manageDue.where == 'cash'">
                    <div class="form-group required">
                        <label>Cash </label>
                        <select v-model="manageDue.cash.id" @change="getBalance(manageDue.cash.id, 'cash')" class="form-control" required>
                            <option value="" selected disabled>Choose one</option>
                            <option v-for="(cash, index) in cashes" :value="cash.id" :key="index">{{ cash.title }}</option>
                        </select>
                    </div>
                </div>
                <!-- cash part end -->

                <!-- bank part start -->
                <div class="col-md-12 pt-3" v-if="manageDue.where == 'bank'">
                    <div class="row">
                        <div class="form-group col-md-6 required">
                            <label>Bank </label>
                            <select v-model="manageDue.bank.id" @change="getBankAccount(manageDue.bank.id)" class="form-control" required>
                                <option value="" selected disabled>Choose one</option>
                                <option v-for="(bank, index) in banks" :value="bank.id" :key="index">{{ bank.name }}</option>
                            </select>
                        </div>

                        <div class="form-group col-md-6 required">
                            <label>Bank Account</label>
                            <select v-model="manageDue.bank.account" @change="getBalance(manageDue.bank.account, 'bank')" class="form-control" required>
                                <option value="" selected disabled>Choose one</option>
                                <option v-for="(account, index) in accounts" :value="account.id" :key="index">{{ account.account_name }}</option>
                            </select>
                        </div>
                    </div>
                </div>
                <!-- bank part end -->

                <!-- balance start -->
                <div class="col-md-12">
                    <p class="d-block bg-dark text-light p-1 px-2" v-if="manageDue.bank.balance != null">BDT {{ manageDue.bank.balance }} </p>
                    <p class="d-block bg-dark text-light p-1 px-2" v-if="manageDue.cash.balance != null">BDT {{ manageDue.cash.balance }} </p>
                </div>
                <!-- balance end -->

                <div class="form-group col-md-12">
                    <label for="description">Description</label>
                    <textarea v-model="manageDue.description" class="form-control" id="description" placeholder="Write Something (optional)"></textarea>
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
        name: 'SupplierDueManagementComponent',
        props: ['suppliers', 'banks', 'cashes'],

        data() {
            return {
                accounts: [],
                // glAccountHeads: [],
                manageDue: {
                    date: new Date().toISOString().substring(0, 10),
                    amount: null,
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
            }
        },

        methods: {
            getBankAccount(id) {
                this.$awn.asyncBlock(
                    axios.post(baseURL + 'get-accounts-from-bank', {
                        id: id
                    }),
                    response => {
                        // set data
                        this.accounts = response.data;

                        // set null value for cash
                        this.manageDue.cash.id = null;
                        this.manageDue.cash.balance = null;

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
                        axios.post(baseURL + 'get-details-from-cash', {
                            id: id
                        }),
                        response => {
                            // set data
                            this.manageDue.cash.balance = response.data.amount;

                            // set null value for bank
                            this.manageDue.bank.id = null;
                            this.manageDue.bank.account = null;
                            this.manageDue.bank.balance = null;

                            // console
                            console.log(response.data);
                        },
                        reason => {
                            console.log(reason)
                        })
                } else {
                    this.$awn.asyncBlock(
                        axios.post(baseURL + 'get-details-from-account', {
                            id: id
                        }),
                        response => {
                            // set data
                            this.manageDue.bank.balance = response.data.balance;

                            // console
                            console.log(response.data);
                        },
                        reason => {
                            console.log(reason)
                        })
                }
            },
            // getGLAccountHead(id) {
            //     this.$awn.asyncBlock(
            //         axios.post(baseURL + 'get-gl-account-heads', {
            //             id: id
            //         }),
            //         response => {
            //             // set data
            //             this.glAccountHeads = response.data;

            //             // console
            //             console.log(response.data);
            //         },
            //         reason => {
            //             console.log(reason)
            //         })
            // },
            // save() {
            //     this.$awn.asyncBlock(
			// 		axios.post(baseURL + 'admin/expenditure', this.manageDue),
			// 		response => {
            //             // console
            //             console.log(response.data);
                        
            //             // flase message
			// 			this.$awn.success("GL account head created.");
			// 			// location.reload(true);
			// 		},
			// 		error => {
			// 			if (error.response.status === 422) { // validation error
			// 				this.errors = error.response.data.errors
			// 				this.$awn.alert('Opps! Enter the valid information of product')
			// 			} else {
			// 				this.$awn.alert('Opps! something went wrong. Try again later')
			// 			}
			// 		}
			// 	)
            // }
        },

        mounted() {
            console.log('Component mounted.');
            console.log(this.supplier.name)
        }
    }
</script>