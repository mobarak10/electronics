<template>
    <div class="total">
        <ul class="list-group">
            <li class="list-group-item row mx-0">
                <div class="col-6 px-0">Subtotal</div>
                <div class="col-6 px-0 text-right">{{ cartSubTotal ? Number.parseFloat(cartSubTotal).toFixed(2) : '0.00' }}</div>
            </li>
            <li class="list-group-item row mx-0">
                <div class="col-6 px-0">{{ regularVat.name }}</div>
                <div class="col-6 px-0 text-right">{{ Number.parseFloat(regularVat.amount).toFixed(2) }}</div>
            </li>
            <li class="list-group-item row mx-0">
                <div class="col-6 px-0">Total</div>
                <div class="col-6 px-0 text-right">{{ Number.parseFloat(cartSubTotal + regularVat.amount).toFixed(2) }}</div>
            </li>
            <li class="list-group-item row mx-0">
                <div class="col-7 px-0">
                    Discount
                    <div class="row mx-0 mt-1" style="font-size: 14px;">
                        <div class="custom-control custom-radio mr-3">
                            <input type="radio" id="taka" v-model="discount.type" value="flat" @change="applyDiscount" class="custom-control-input">
                            <label class="custom-control-label" for="taka">Flat</label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input type="radio" id="customRadio2" v-model="discount.type" value="percentage" @change="applyDiscount" class="custom-control-input">
                            <label class="custom-control-label" for="customRadio2">Percentage</label>
                        </div>
                    </div>
                </div>
                <div class="col-5 px-0 align-self-center">
                    <input type="number" class="form-control text-right" v-model="discount.amount"
                           @blur="applyDiscount"
                           :disabled="!(discount.type)"
                           step="any"
                           placeholder="Enter discount amount (0.00)">
                </div>
            </li>
            <li class="list-group-item row mx-0">
                <div class="col-6 px-0">Grand total</div>
                <div class="col-6 px-0 text-right">{{ cartTotal ? Number.parseFloat(cartTotal).toFixed(2) : '0.00' }}</div>
            </li>
            <li class="list-group-item row mx-0 align-items-center">
                <div class="col-7 px-0">Tendered</div>
                <div class="col-5 px-0">
                    <input type="number" class="form-control text-right" step="any" v-model="tendered"
                           @keyup="updateDueChange"
                           @blur="updateDueChange"
                           @change="updateDueChange"
                           placeholder="0.00"
                    >
                </div>
            </li>
            <li class="list-group-item row mx-0" v-if="due !== null">
                <div class="col-6 px-0">Due</div>
                <div class="col-6 px-0 text-right">{{ Number.parseFloat(due).toFixed(2) }}</div>
            </li>
            <li class="list-group-item row mx-0" v-if="change">
                <div class="col-6 px-0">Change</div>
                <div class="col-6 px-0 text-right">{{ Number.parseFloat(change).toFixed(2) }}</div>
            </li>
        </ul>
    </div>
</template>

<script>
    import { mapState, mapActions } from 'vuex'
    export default {
        name: "PaymentDetailsComponent",
        computed: {
            ...mapState([
                'cartSubTotal'
            ])
        },
        data() {
            return {
                cartTotal: null,
                regularVat: {},
                discount: {
                    type: 'flat',
                    amount: 0.0
                },
                tendered: null,
                due: 0,
                change: 0
            }
        },
        methods: {
            ...mapActions([
                'actionRefreshCartSubTotal'
            ]),
            refreshCartTotal(){
                this.getAppliedDiscount()
                    .then(() => {
                        axios.post(baseURL + 'admin/pos/get-total')
                            .then(response => {
                                this.actionRefreshCartSubTotal() // update cart sub total
                                    .then(() => {
                                        this.getAmountOfVatInSubtotal() //update the vat amount in subtotal
                                        this.cartTotal = response.data
                                        this.updateDueChange()
                                    })
                                    .catch(reason => console.log(reason))
                            })
                            .catch(reason => console.log(reason.data))
                    })
            },
            getAmountOfVatInSubtotal(){
                axios.post(baseURL + 'admin/pos/get-calculated-amount-of-subtotal-for-regular-vat')
                    .then(response => this.regularVat =  response.data)
                    .catch(reason => console.log(reason))
            },
            getAppliedDiscount(){
                return new Promise((resolve, reject) => {
                    axios.post(baseURL + 'admin/pos/get-applied-discount')
                        .then(response => {
                            this.discount.type = response.data.type
                            this.discount.amount = response.data.amount
                            resolve()
                        })
                })
            },
            applyDiscount(){
                this.$awn.asyncBlock(
                    axios.post(baseURL + 'admin/pos/apply-discount', {
                        discount: {
                            type: this.discount.type,
                            amount: this.discount.amount || 0
                        }
                    }),
                    response => {
                        this.cartTotal = response.data
                        this.updateDueChange()
                    },
                    reason => console.log(reason)
                )
            },
            updateDueChange(){
                let value = this.tendered - this.cartTotal

                if(value > 0){
                    this.change = value
                    this.due = null
                }else{
                    this.change = 0
                    this.due = Math.abs(value)
                }
            }
        },
        mounted(){
            this.refreshCartTotal()
        }
    }
</script>

<style scoped>

</style>
