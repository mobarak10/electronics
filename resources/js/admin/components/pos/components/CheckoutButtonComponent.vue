<template>
    <!-- <div class="payment-process">
        <a href="#" class="btn btn-danger btn-block btn-lg" @click.prevent="clearCartItems">Clear cart</a>
        <a :href="baseURL + 'admin/pos/checkout'" class="btn btn-primary btn-block btn-lg">Checkout</a>
    </div> -->

    <div class="d-flex" role="group" aria-label="Cart button group" >
        <a href="#" class="btn btn-danger btn-lg col-6 mr-1 rounded-0" @click.prevent="clearCartItems"><i class="fa fa-trash ml-1"></i> Clear Cart</a>
        <a :href="baseURL + 'admin/pos/checkout'" class="btn btn-primary btn-lg col-6 ml-1 rounded-0"><i class="fa fa-shopping-cart ml-1"></i> Checkout</a>
    </div>
</template>

<script>
    import { mapActions } from 'vuex'
    export default {
        name: "CheckoutButtonComponent",
        methods: {
            ...mapActions([
                'actionClearCartItems'
            ]),
            clearCartItems(){
                if (confirm('Are you sure want to clear all items?')){
                    this.$awn.asyncBlock(
                        this.actionClearCartItems()
                    )
                }
            }
        }
    }
</script>

<style scoped>

</style>
