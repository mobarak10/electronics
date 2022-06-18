<template>
    <div class="pos">
        <div class="row">
            <div class="col-lg-4 border-right">
                <!-- Pos left -->
                <div class="pos-left">
                    <!-- Order -->
                    <order-component></order-component>
                    <!-- End of the order -->

                    <!-- payment-process -->
                    <checkout-button-component></checkout-button-component>
                    <!-- End of the payment-process -->
                </div>
                <!-- End of the pos left -->
            </div>

            <div class="col-lg-8">
                <!-- Pos right -->
                <div class="pos-right">

                    <!-- Categories -->
                    <product-category-component></product-category-component>
                    <!-- End of the Categories -->

                    <!-- Products -->
                    <product-component></product-component>
                    <!-- End of the products -->
                </div>
                <!-- End of the pos right -->
            </div>
            <!-- Global Modal -->
            <!--<product-modal-component></product-modal-component>-->
            <!-- End of the Global modal -->
        </div>
    </div>
</template>

<script>
    import { mapState, mapActions } from 'vuex'
    import OrderComponent from "./components/OrderComponent";
    import ProductComponent from "./components/ProductComponent";
    import ProductModalComponent from "./components/ProductModalComponent";
    import ProductCategoryComponent from "./components/ProductCategoryComponent";
    import CheckoutButtonComponent from "./components/CheckoutButtonComponent";
    export default {
        name: "POSContainerComponent",
        components: {
            CheckoutButtonComponent,
            ProductCategoryComponent,
            ProductModalComponent,
            ProductComponent,
            OrderComponent
        },
        computed: {
            ...mapState([
                'activeProducts'
            ])
        },
        methods: {
            ...mapActions([
                'actionLoadActiveProducts'
            ])
        },
        mounted() {
            this.actionLoadActiveProducts()
        }
    }
</script>

<style scoped>

</style>
