<template>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="m-0">Bank Transaction</h5>
        </div>
        
        <div class="card-body p-0">
            <div class="col-12 py-2">
                <form action="" method="GET" @submit.prevent="save">
                    <div class="form-row">
                        <div class="form-group col-md-6 pr-3 required">
                            <label for="date">Transaction Date</label>
                            <input type="date" v-model="date" class="form-control" id="date" required>
                        </div>

                        <div class="form-group col-md-6 pl-3 required">
                            <label for="amount">Amount (BDT)</label>
                            <input type="number" v-model="amount" class="form-control" placeholder="0.00" id="amount" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <!-- Transfer From -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">Transfer From </div>

                                <div class="card-body">
                                    <div class="form-group row required">
                                        <div class="col-sm-auto">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" v-model="transferFrom.media" id="from-cash" value="cash">
                                                <label class="form-check-label" for="from-cash">Cash </label>
                                            </div>
            
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" v-model="transferFrom.media" id="from-bank" value="bank">
                                                <label class="form-check-label" for="from-bank">Bank </label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- cash -->
                                    <div class="form-row" v-if="transferFrom.media == 'cash'">
                                        <div class="form-group col-md-12 required">
                                            <label>Cash</label>
                                            <select class="form-control" v-model="transferFrom.id" @change="getCashAmount(transferFrom.id, 'from')" required>
                                                <option value="" selected disabled>Select one</option>
                                                <option v-for="(cash, index) in cashes" :value="cash.id" :key="index">
                                                    {{ cash.title }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- bank -->
                                    <div class="form-row" v-if="transferFrom.media == 'bank'">
                                        <div class="form-group col-md-6 required">
                                            <label>Bank</label>
                                            <select class="form-control" v-model="transferFrom.id" @change="getBankAccount(transferFrom.id, 'from')" required>
                                                <option value="" selected disabled>Select one</option>
                                                <option v-for="(bank, index) in banks" :value="bank.id" :key="index">
                                                    {{ bank.name }}
                                                </option>
                                            </select>
                                        </div>

                                        <div class="form-group col-md-6 required">
                                            <label>Account no. </label>
                                            <select class="form-control" v-model="transferFrom.account" @change="getBankBalance(transferFrom.account, 'from')" required>
                                                <option value="" selected disabled>Select one</option>
                                                <option v-for="(account, index) in transferFrom.bankAccounts" :value="account.id" :key="index">
                                                    {{ account.account_number }} ({{ account.account_name }})
                                                </option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="col-md-12">
                                            <p class="px-2 text-white bg-dark" v-if="transferFrom.balance != null"> BDT {{ transferFrom.balance }} </p>
                                        </div>
                                    </div>

                                    <div class="form-row" v-if="transferFrom.media == 'bank'">
                                        <div class="form-group col-md-6">
                                            <label for="cheque-no">Cheque no.</label>
                                            <input type="text" v-model="transferFrom.chequeNo" class="form-control" id="cheque-no">
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="issue-date">Issue Date </label>
                                            <input type="date" v-model="transferFrom.issueDate" class="form-control" id="issue-date">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Transfer To -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">Transfer To </div>

                                <div class="card-body">
                                    <div class="form-group row required">
                                        <div class="col-sm-auto">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="to" v-model="transferTo.media" id="to-cash" value="cash">
                                                <label class="form-check-label" for="to-cash">Cash </label>
                                            </div>
            
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="to" v-model="transferTo.media" id="to-bank" value="bank">
                                                <label class="form-check-label" for="to-bank">Bank </label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- cash -->
                                    <div class="form-row" v-if="transferTo.media == 'cash'">
                                        <div class="form-group col-md-12 required">
                                            <label>Cash</label>
                                            <select class="form-control" v-model="transferTo.id" @change="getCashAmount(transferTo.id, 'to')" required>
                                                <option value="" selected disabled>Select one</option>
                                                <option v-for="(cash, index) in cashes" :value="cash.id" :key="index">
                                                    {{ cash.title }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- bank -->
                                    <div class="form-row" v-if="transferTo.media == 'bank'">
                                        <div class="form-group col-md-6 required">
                                            <label>Bank</label>
                                            <select class="form-control" v-model="transferTo.id" @change="getBankAccount(transferTo.id, 'to')" required>
                                                <option value="" selected disabled>Select one</option>
                                                <option v-for="(bank, index) in banks" :value="bank.id" :key="index">
                                                    {{ bank.name }}
                                                </option>
                                            </select>
                                        </div>

                                        <div class="form-group col-md-6 required">
                                            <label for="account">Account no. </label>
                                            <select class="form-control" v-model="transferTo.account" @change="getBankBalance(transferTo.account, 'to')" required>
                                                <option value="" selected disabled>Select one</option>
                                                <option v-for="(account, index) in transferTo.bankAccounts" :value="account.id" :key="index">
                                                    {{ account.account_number }} ({{ account.account_name }})
                                                </option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="col-md-12">
                                            <p class="px-2 text-white bg-dark" v-if="transferTo.balance != null"> BDT {{ transferTo.balance }} </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="note">Note </label>
                            <textarea v-model="note" class="form-control" id="note" placeholder="Optional"></textarea>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-12 text-right">
                            <button type="reset" class="btn btn-danger">Reset </button>
                            <button type="submit" class="btn btn-primary">Transfer now </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "TransactionComponent",
        props: ['cashes', 'banks'],
        data() {
            return {
                date: null,
                amount: 0.00,
                transferFrom: {
                    media: 'cash',
                    id: '',
                    account: '',
                    balance: null,
                    bankAccounts: [],
                    chequeNo: null,
                    issueDate: null,
                },
                transferTo: {
                    media: 'bank',
                    id: '',
                    account: '',
                    balance: null,
                    bankAccounts: [],
                },
                note: null
            }
        },
        methods: {
            getCashAmount(id, where = 'from') {
                this.$awn.asyncBlock(
                    axios.post(baseURL + 'get-details-from-cash', {
                        id: id
                    }),
                    response => {
                        console.log(response.data);

                        if(where == 'from') {
                            this.transferFrom.balance = response.data.amount;
                        } else {
                            this.transferTo.balance = response.data.amount;
                        }
                        
                    },
                    error => {
                        console.log(error)
                    }
                )
            },
            getBankAccount(id, where = 'from') {
                this.$awn.asyncBlock(
                    axios.post(baseURL + 'get-accounts-from-bank', {
                        id: id
                    }),
                    response => {
                        console.log(response.data);

                        if(where == 'from') {
                            this.transferFrom.bankAccounts = response.data;
                        } else {
                            this.transferTo.bankAccounts = response.data;
                        }
                    },
                    error => {
                        console.log(error)
                    }
                )
            },
            getBankBalance(id, where = 'from') {
                this.$awn.asyncBlock(
                    axios.post(baseURL + 'get-details-from-account', {
                        id: id
                    }),
                    response => {
                        console.log(response.data);

                        if(where == 'from') {
                            this.transferFrom.balance = response.data.balance;
                        } else {
                            this.transferTo.balance = response.data.balance;
                        }
                        
                    },
                    error => {
                        console.log(error)
                    }
                )
            },
            save() {
                this.$awn.asyncBlock(
                    axios.post(baseURL + 'admin/balanceTransfer', {
                        transfer_date: this.date,
                        transfer_from: this.transferFrom.media,
                        transfer_from_id: this.transferFrom.id,
                        transfer_from_account: this.transferFrom.account,
                        transfer_to: this.transferTo.media,
                        transfer_to_id: this.transferTo.id,
                        transfer_to_account: this.transferTo.account,
                        cheque_no: this.transferFrom.chequeNo,
                        cheque_issue_date: this.transferFrom.issueDate,
                        amount: this.amount,
                        note: this.note
                    }),
                    response => {
                        console.log(response.data);
                        this.$awn.success("Transaction completed.");
                    },
                    error => {
                        console.log(error);
                        this.$awn.alert("Opps! Something went wrong.");
                    }
                )
            }
        },
        mounted() {
            console.log(this.cashes, this.banks);
        }
    }
</script>

<style scoped>

</style>
