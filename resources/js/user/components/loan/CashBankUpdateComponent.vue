<template>
    <div class="col-md-12">
        <div class="form-group col-md-12">
            <div class="form-check form-check-inline">
                <input type="radio" style="cursor: pointer;" class="form-check-input" name="payment_method" v-model="where" id="cash" value="cash">
                <label for="cash" class="form-check-label">Cash</label>
            </div>

            <div class="form-check form-check-inline">
                <input type="radio" style="cursor: pointer;" class="form-check-input" name="payment_method" v-model="where" id="bank" value="bank">
                <label for="bank" class="form-check-label">Bank</label>
            </div>
        </div>
        <!-- select cash/bank end -->

        <!-- start cash part -->
        <div class="form-group col-md-12" v-if="where === 'cash'">
            <label for="cash">Cash</label>
            <select v-model="cash.id" @change="getCashBalance(cash.id)" name="transactionable[id]" class="form-control" required>
                <option selected disabled>Choose one</option>
                <option v-for="(cash, index) in cashes" :value="cash.id" :key="index">{{ cash.title }}</option>
            </select>
        </div>
        <!-- end cash part -->

        <!-- start bank part -->
        <div class="form-group col-md-12" v-if="where === 'bank'">
            <div class="row">
                <div class="form-group col-md-6 required">
                    <label for="bank">Bank</label>
                    <select v-model="bank.id" @change="getBankAccount(bank.id)" class="form-control" required>
                        <option selected disabled>Choose one</option>
                        <option v-for="(bank, index) in banks" :value="bank.id" :key="index">{{ bank.name }}</option>
                    </select>
                </div>

                <div class="form-group col-md-6 required">
                    <label for="bank-account">Bank Account</label>
                    <select id="bank-account" name="transactionable[id]" v-model="bank.account" @change="getBankBalance(bank.account, 'bank')" class="form-control" required>
                        <option value="" selected disabled>Choose one</option>
                        <option v-for="(account, index) in accounts" :value="account.id" :key="index">{{ account.account_name }}</option>
                    </select>
                </div>

                <!-- balance for bank start -->
                <div class="col-md-12">
                    <p class="d-block bg-dark text-light" v-if="bank.balance != null">BDT {{ bank.balance }} </p>
                </div>
                <!-- balance for bank end -->
            </div>
        </div>

        <!-- balance for cash start -->
        <div class="col-md-12">
            <p class="d-block bg-dark text-light" v-if="cash.balance != null">BDT {{ cash.balance }} </p>
        </div>
        <!-- balance for cash end -->
    </div>
</template>

<script>
export default {
    name: "CashBankUpdateComponent",
    props: ['banks', 'cashes', 'loan'],
    data() {
        return {
            accounts: [],
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
        }
    },

    methods: {
        getCashBalance(id){
            axios.post(baseURL + 'user/get-details-from-cash', {
                id: id
            })
                .then(response => {
                    // set data
                    this.cash.balance = response.data.amount;

                    // set null value for cash
                    this.bank.id = null;
                    this.bank.account = null;
                    this.bank.balance = null;

                    // console
                    console.log(response.data);
                })
                .catch(reason => {
                    console.log(reason)
                })
        },

        getBankAccount(id){
            axios.post(baseURL + 'user/get-accounts-from-bank', {
                id: id
            })
                .then(response => {
                    //set data for bank
                    this.accounts = response.data;

                    // set null value for cash
                    this.cash.id = null;
                    this.cash.balance = null;

                    // console response
                    console.log(response.data);
                })
                .catch(reason => {
                    console.log(reason)
                })
        },

        getBankBalance(id) {
            axios.post(baseURL + 'user/get-details-from-account', {
                id: id
            })
                .then(response => {
                    // set data
                    this.bank.balance = response.data.balance;

                    // set null value for cash
                    this.cash.id = null;
                    this.cash.balance = null;

                    // console
                    console.log(response.data);
                })
                .catch(reason => {
                    console.log(reason)
                })
        },

        initValues(){
            if(this.loan.transactionable_type === 'App\\Models\\Cash'){
                let cash = this.cashes.find(cash => cash.id === this.loan.transactionable_id)
                this.cash.id = cash.id
                this.cash.balance = cash.amount
            }else{
                let bank = this.banks.find(bank => bank.bank_accounts.find(account => account.id === this.loan.transactionable_id).id)
                this.bank.id = bank.id
                this.where = 'bank'
                this.accounts = bank.bank_accounts
                let bankAccount = bank.bank_accounts.find(account => account.id === this.loan.transactionable_id)
                this.bank.account = bankAccount.id
                this.bank.balance = bankAccount.balance
                console.log(bankAccount)
            }
        },
    },

    mounted() {
        this.initValues()
        console.log(this.loan)
    }
}
</script>

<style scoped>

</style>
