<template>
    <tfoot>
        <tr>
            <th class="text-right">Subtotal</th>
            <th class="text-right">{{ cartSubTotal ? Number.parseFloat(cartSubTotal).toFixed(2) : '0.00' }}</th>
            <th>&nbsp;</th>
        </tr>
    </tfoot>
</template>

<script>
    import { mapState, mapActions } from 'vuex'

    export default {
        name: "TotalAmountComponent",
        computed: {
            ...mapState([
                'cartSubTotal'
            ])
        },
        methods: {
            ...mapActions([
                'actionRefreshCartSubTotal'
            ])
        },
        mounted() {
            this.actionRefreshCartSubTotal()
        }
    }
</script>

<style scoped>

</style>
