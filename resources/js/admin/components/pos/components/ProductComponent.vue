<template>
    <div class="pos-products">
        <strong>PRODUCTS</strong>

        <div class="row mx-n2">
            <div class="col-6 col-lg-2 col-sm-3 px-2" v-for="(product, index) in products" :key="index">
                <!-- Single -->
                <div class="single border mb-3">
                    <a href="#" @click="showItemInformation(product.barcode)" class="stretched-link" data-toggle="modal">
                        <div class="content">
                            <span class="name">{{ product.name }}</span>
                            <div class="price"><span>{{ product.barcode }}</span></div>
                            <div class="price"><span>RP: BDT {{ product.retail_price }}</span></div>
                            <div class="price"><span>WP: BDT {{ product.wholesale_price }}</span></div>
                        </div>
                    </a>
                </div>
                <!-- End of the single -->
            </div>

            <div class="col-12 px-2 text-center" v-if="!products.length">
                <!-- Single -->
                No product available
                <!-- End of the single -->
            </div>

            <!--
            With image
            <div class="col-6 col-lg-2 col-sm-3 px-2">
                &lt;!&ndash; Single &ndash;&gt;
                <div class="single w-image border">
                    <a href="#" data-toggle="modal" data-target=".products-modal">
                        <div class="content">
                            <span class="name">i7s TWS Smart Earphone</span>
                            <div class="price">price:<span>$300</span></div>
                        </div>
                        <img src="https://www.desibela.com/ProductImages/109516/big/urbanears-plattan-2_0024_61egnrq9col._sl1000_.jpg" class="img-fluid" alt="">
                    </a>
                </div>
                &lt;!&ndash; End of the single &ndash;&gt;
            </div>
            -->

            <product-modal-component></product-modal-component>
        </div>
    </div>
</template>

<script>
    import { mapState, mapActions, mapGetters } from 'vuex'
    import ProductModalComponent from "./ProductModalComponent";
    export default {
        name: "ProductComponent",
        components: {ProductModalComponent},
        computed: {
            ...mapState([
                'activeProducts',
                'productFilter'
            ]),
            ...mapGetters([
                'getterFilterWiseProducts'
            ])
        },
        watch: {
            activeProducts: {
                deep: true,
                immediate: true,
                handler: function(val, oldVal){
                    this.products = val
                }
            },
            productFilter: {
                deep: true,
                handler: 'filterProducts'
            }
        },
        data(){
            return {
                products: []
            }
        },
        methods: {
            showItemInformation(barcode){
                eventBus.$emit('modalShowProductInfo', barcode )
            },
            filterProducts(payload){
                this.products = this.getterFilterWiseProducts(payload)
            }
        }, mounted() {

        },
        beforeDestroy() {

        }
    }
</script>

<style scoped>

</style>
