<template>
    <div class="row">
        <div class="col-lg-7">
            <!-- total -->
            <payment-details-component ref="payDetails"></payment-details-component>
            <!-- End of the main -->

        </div>
        <div class="col-lg-5">

            <!-- Pads -->
            <div class="pads">
                <div class="card-body p-0">
                    <div class="pad">
                        <button type="button" @click.prevent="processPayment"><i class="fa fa-chevron-right"></i><span>Payment</span></button>
                    </div>
                    <cash-component ref="cash"></cash-component>
                    <customer-component ref="customer"></customer-component>
                    <div class="pad">
                        <button type="button">1</button>
                    </div>
                    <div class="pad">
                        <button type="button">2</button>
                    </div>
                    <div class="pad">
                        <button type="button">3</button>
                    </div>
                    <div class="pad">
                        <button type="button">Discount</button>
                    </div>
                    <div class="pad">
                        <button type="button">4</button>
                    </div>
                    <div class="pad">
                        <button type="button">5</button>
                    </div>
                    <div class="pad">
                        <button type="button">6</button>
                    </div>
                    <div class="pad">
                        <button type="button">Tendered</button>
                    </div>
                    <div class="pad">
                        <button type="button">7</button>
                    </div>
                    <div class="pad">
                        <button type="button">8</button>
                    </div>
                    <div class="pad">
                        <button type="button">9</button>
                    </div>

                    <div class="pad">
                        <button type="button">0</button>
                    </div>
                    <div class="pad">
                        <button type="button">.</button>
                    </div>
                    <div class="pad">
                        <button type="button">C</button>
                    </div>
                    <div class="pad">
                        <button type="button">+/-</button>
                    </div>
                </div>
            </div>
            <!-- End of the pads -->

        </div>
    </div>
</template>

<script>
    import PaymentDetailsComponent from "./components/PaymentDetailsComponent";
    import CashComponent from "./components/CashComponent";
    import CustomerComponent from "./components/CustomerComponent";
    export default {
        name: "POSCheckoutContainerComponent",
        components: {CustomerComponent, CashComponent, PaymentDetailsComponent},
        computed: {

        },
        methods: {
            processPayment(){
                let cashId = this.$refs.cash.cash.id
                let tendered = this.$refs.payDetails.tendered
                let customerId = this.$refs.customer.customer.id

                if(tendered === null){
                    alert('Enter tendered amount')
                    return
                }

                if(!cashId){
                    alert('Select cash')
                    return
                }

                if(!customerId){
                    alert('Select customer')
                    return
                }

                this.$awn.asyncBlock(
                    axios.post(baseURL + 'admin/pos-proceed-payment', {
                        cash_id: cashId,
                        tendered: tendered,
                        customer_id: customerId
                    }),
                    response => {
                        window.location.href = baseURL + 'invoice-generate/' + response.data.invoice_no
                    },
                    reason => {
                        console.log(reason)
                    })
            }
        },
        mounted() {

        }
    }
</script>

<style scoped>

</style>
