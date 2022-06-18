<template>
    <div class="order">
        <!-- Product header -->
        <search-by-barcode-component></search-by-barcode-component>

        <!-- cart start -->
        <table class="table">
            <thead>
                <tr>
                    <th>Details</th>
                    <th class="text-right">Price (BDT)</th>
                    <th class="text-right"><i class="fa fa-trash"></i></th>
                </tr>
            </thead>

            <tbody>
                <tr v-if="cartProducts.length === 0">
                    <th colspan="3">No item available in cart.</th>
                </tr>

                <tr v-for="(product, index) in cartProducts" :key="index">
                    <td>
                        <a href="#" @click.prevent="editCartProduct(product.id)" :title="product.name + ', ' + product.attributes.meta.units.display + ', BDT' + product.price">
                            <span>{{ product.name }}</span>

                            <small class="d-block">
                                {{ product.attributes.meta.units.display }}, BDT {{ product.price }}
                            </small>
                        </a>
                    </td>

                    <td class="text-right">{{ Number.parseFloat(product.attributes.price.priceSumWithConditions).toFixed(2) }}</td>

                    <td class="text-right">
                        <a href="#" title="Remove product" @click.prevent="removeProduct(product.id)">
                            <i class="fa fa-trash text-danger"></i>
                        </a>
                    </td>
                </tr>
            </tbody>

            <!-- subtotal component start -->
            <total-amount-component></total-amount-component>
            <!-- subtotal component end -->
        </table>
        <!-- cart end -->

        <!-- Block header -->
        <!-- <div class="order-header row align-items-center mx-0">
            <div class="flex-grow-1">
                <strong>Products details</strong>
            </div>

            <div class="col-2 px-0 col-2 col-lg-3">
                <strong>Price</strong>
            </div>
        </div> -->
        <!-- End of the product header -->

        <!-- <div class="orderline-wrapper">

            -- Orderline --
            <div class="orderline row mx-0 align-items-center" v-if="cartProducts.length === 0">
                <strong>No item available in cart</strong>
            </div>

            <div class="orderline row mx-0 align-items-center" v-for="(product, index) in cartProducts" :key="index">
                <div class="flex-grow-1 left px-0">
                    <span class="product-name">
                        <a href="#" class="stretched-link" @click.prevent="editCartProduct(product.id)">{{ product.name }}</a>
                    </span>

                    <div class="quantity">
                        <p> {{ product.attributes.meta.units.display }}, BDT {{ product.price }}</p>
                    </div>
                </div>

                <div class="col-2 col-lg-3 right px-0 d-lg-flex justify-content-end align-items-center">
                    <div class="price">
                        {{ product.attributes.price.priceSumWithConditions }}
                    </div>

                    <div class="remove-product px-2" title="Remove product" @click.prevent="removeProduct(product.id)">
                        <i class="fa fa-trash"></i>
                    </div>
                </div>
            </div>
        </div> -->
    </div>
</template>

<script>
    import { mapActions, mapState } from 'vuex'
    import SearchByBarcodeComponent from "./SearchByBarcodeComponent";
    import TotalAmountComponent from "./TotalAmountComponent";

    export default {
        name: "OrderComponent",
        components: {SearchByBarcodeComponent, TotalAmountComponent},
        computed: {
            ...mapState([
                'cartProducts'
            ])
        },
        methods: {
            ...mapActions([
                'actionRefreshCartProducts',
                'actionRemoveCartProduct'
            ]),
            removeProduct(productId){
                if(confirm('Are you sure want to remove this item?')){
                    this.actionRemoveCartProduct({
                        id: productId
                    })
                }

            },
            editCartProduct(productId){
                eventBus.$emit('editCartProduct', productId)
            }
        },
        mounted() {
            this.actionRefreshCartProducts()
            eventBus.$on('refreshCartProducts', () => this.actionRefreshCartProducts())
        },
        beforeDestroy() {
            eventBus.$off('refreshCartProducts', () => this.actionRefreshCartProducts())
        }
    }
</script>

<style scoped>

</style>
