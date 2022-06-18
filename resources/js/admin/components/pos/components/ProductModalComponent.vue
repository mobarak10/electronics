<template>
    <div ref="modal" class="modal fade products-modal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="#" method="POST" @submit.prevent="submitForm">
                    <div class="modal-header">
                        <h5 v-if="product.name">{{ product.name }}</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&#10005;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <preloader-component v-if="loading"></preloader-component>

                        <div class="row">
                            <!-- End forms -->
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <!-- <div class="form-group col-md-6">
                                        <label for="code">Code</label>
                                        <input type="text" name="name" id="code" class="form-control" :value="product.code" readonly>
                                    </div> -->

                                    <div class="form-group col-md-6">
                                        <label for="barcode">Barcode</label>
                                        <input type="text" name="barcode" id="barcode" class="form-control" :value="product.barcode" readonly>
                                    </div>

                                    <!-- <div v-if="product.model" class="form-group col-md-6"> -->
                                    <div class="form-group col-md-6">
                                        <label for="model">Model</label>
                                        <input type="text" name="name" id="model" class="form-control" :value="product.model" readonly>
                                    </div>
                                </div>

                                <!--<div class="form-group">
                                    <label for="discount">Discount per unit</label>
                                    <div class="input-group mb-3">
                                        <input type="number" class="form-control" step="any" v-model="discount" id="discount" placeholder="Optional">
                                        <div class="input-group-append">
                                            <select class="form-control" v-model="discountType">
                                                <option value="flat">BDT</option>
                                                <option value="percentage">Percentage (%)</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>-->

                                <div class="form-group">
                                    <div class="d-flex">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" v-model="priceType" :value="'wholesale'" id="wholesale" class="custom-control-input">
                                            <label class="custom-control-label" for="wholesale">Wholesale</label>
                                        </div>

                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" v-model="priceType" :value="'retail'" id="retail" class="custom-control-input">
                                            <label class="custom-control-label" for="retail">Retail</label>
                                        </div>
                                    </div>

                                    <span class="price mt-2 d-block" v-if="price">Price: {{ price }} TK</span>
                                    <span class="price mt-2 d-block" v-if="price">Unit: {{ product.unit.description }}</span>
                                </div>
                            </div>
                            <!-- End of the End forms -->

                            <!-- Right forms -->
                            <div class="col-lg-6">
                                <div class="form-group" v-for="(warehouse, index) in product.warehouses" :key="index">
                                    <label class="mb-0">
                                        {{ warehouse.title }}
                                        <small class="form-text text-muted mt-0">Available in stock {{ warehouse.product_quantity_in_unit.display }}</small>
                                    </label>

                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <button class="btn" type="button"><i class="fa fa-minus"></i></button>
                                        </div>

                                        <input type="number"
                                               v-for="(label, labelIndex) in warehouse.product_quantity_in_unit.labels"
                                               :value="(quantity[warehouse.id]) ? (quantity[warehouse.id][labelIndex]) ? quantity[warehouse.id][labelIndex] : '' : ''"
                                               @blur="addQuantity($event, warehouse.id, labelIndex)"
                                               @change="addQuantity($event, warehouse.id, labelIndex)"
                                               @keyup="addQuantity($event, warehouse.id, labelIndex)"
                                               :placeholder="label" :key="labelIndex"
                                               :max="(labelIndex === 0) ? warehouse.product_quantity_in_unit.result[labelIndex] : ''"
                                               min="0" class="form-control">

                                        <div class="input-group-prepend">
                                            <button class="btn" type="button"><i class="fa fa-plus" aria-hidden="true"></i></button>
                                        </div>
                                    </div>
                                    <small class="d-block text-danger" v-if="errors[warehouse.id]"><strong>Error:</strong> {{ errors[warehouse.id][0] }}</small>
                                </div>
                            </div>
                            <!-- End of the right forms -->

                            <div class="col-lg-12">
                                <div class="form-group">
                                    <textarea class="form-control" v-model="note" placeholder="Write your note"></textarea>
                                </div>
                            </div>

                            <div class="col-12 m-0" v-if="errors.quantities">
                                <span class="text-danger"><strong>Error:</strong> {{ errors.quantities[0] }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer p-3">
                        <div class="btn-wrapper">
                            <button type="button" class="btn btn-danger mr-2" data-dismiss="modal">Cancel</button>
                            <button type="submit" v-if="submitType === 'addToCart'" class="btn btn-primary">Add to Cart</button>
                            <button type="submit" v-if="submitType === 'updateCartItem'" class="btn btn-primary">Update Cart</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapGetters } from 'vuex'
    import PreloaderComponent from "../../resuable/PreloaderComponent";
    export default {
        name: "ProductModalComponent",
        components: {PreloaderComponent},
        computed: {
            ...mapGetters([
                'getterSingleActiveProduct',
                'getterSingleCartItem'
            ]),
            price(){
                let price = null
                if (this.priceType === 'retail'){
                    price =  this.product.retail_price
                }else if (this.priceType === 'wholesale'){
                    price =  this.product.wholesale_price
                }

                return price
            }
        },
        data() {
            return {
                loading: false,
                submitType: null,
                productModal: {},
                product: {},
                note: null,
                quantity: {},
                discount: null,
                discountType: 'flat',
                priceType: 'retail',
                errors: {}
            }
        },
        methods: {
            resetModal(){
                this.discount = null
                this.discountType = 'flat'
                this.submitType = null
                this.errors = {}
                this.note = null
                this.product = {}
                this.quantity = {}
                this.priceType = 'retail'
            },
            submitForm(){
                if (this.submitType === 'addToCart'){
                    this.addToCart()
                }else if(this.submitType === 'updateCartItem') {
                    this.addToCart()
                }
            },
            showModal(){
                this.productModal.modal('show')
            },
            hideModal(){
                this.productModal.modal('hide')
            },
            showPreloader(){
                this.loading = true
            },
            hidePreloader(){
                this.loading = false
            },
            addQuantity(event, warehouseId, order){
                if (!(warehouseId in this.quantity)) {
                    this.$set(this.quantity, warehouseId, {} )
                }
                this.$set(this.quantity[warehouseId], order, event.target.value )
            },
            modalShowProductInfo(barcode){ //get product info from vuex
                this.resetModal()
                this.submitType = 'addToCart'
                this.product = this.getterSingleActiveProduct(barcode)
                this.showModal()
            },
            /*
            modalShowProductInfo(productCode){ // get product info from database
                this.resetModal()
                this.showPreloader()
                axios.post(baseURL + 'get-details-from-product', {
                    code: productCode
                })
                    .then(response => {
                        console.log(response.data)
                        this.product = response.data
                        this.hidePreloader()
                    })
                    .catch(error => {

                    })

                this.showModal()
            }
            */
            addToCart(){
                this.errors = {}
                this.$awn.asyncBlock(
                    axios.post(baseURL + 'admin/pos/add-to-cart', {
                    product_id: this.product.id,
                    price_type: this.priceType,
                    quantities: this.quantity,
                    discount: this.discount,
                    discount_type: this.discountType,
                    note: this.note
                }),
                    response => {
                        eventBus.$emit('refreshCartProducts');
                        this.hideModal()
                    },
                    reason => {
                        if(reason.response.status === 422){
                            this.errors = reason.response.data.errors
                        }else{
                            this.$awn.alert('Opps! Something went wrong')
                        }
                    })
            },
            editCartProduct(productId){
                this.resetModal()
                this.submitType = 'updateCartItem'
                let cartItem = this.getterSingleCartItem(productId)

                this.priceType = cartItem.attributes.price_type
                this.note = cartItem.attributes.note
                this.product = cartItem.attributes.meta.product
                this.quantity = cartItem.attributes.selected_quantity
                this.discount = cartItem.attributes.discount.amount
                this.discountType = cartItem.attributes.discount.type
                this.showModal()
            }
        },
        mounted() {
            this.productModal = $(this.$refs.modal)

            eventBus.$on('modalShowProductInfo', (barcode) => this.modalShowProductInfo(barcode))
            eventBus.$on('editCartProduct', (productId) => this.editCartProduct(productId))

            this.productModal.on('hide.bs.modal', () => this.resetModal())
        },
        beforeDestroy() {
            eventBus.$off('modalShowProductInfo', (barcode) => this.modalShowProductInfo(barcode))
            eventBus.$off('editCartProduct', (productId) => this.editCartProduct(productId))
        }
    }
</script>

<style scoped>

</style>
