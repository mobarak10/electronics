<template>
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="form-row">
                        <div class="form-group col-md-6 required">
                            <label for="date">Date</label>
                            <input type="date" v-model="date" name="date" class="form-control" id="date" placeholder="Pick a date">
                        </div>
                        <div class="form-group col-md-6 required">
                            <label for="voucher-no">Voucher No</label>
                            <input type="text" v-model="voucherNo" name="voucher_no" class="form-control" id="voucher-no" placeholder="Enter voucher no">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-12 required">
                            <label for="product">Product</label>
                            <select name="product_id" v-model="productId" id="product" class="form-control">
                                <option :value="null" disabled>Select Product</option>
                                <option v-for="(product, productIndex) in products" :key="productIndex" :value="product.id">{{ product.name }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="text-right my-2">
                        <button type="button" role="button" class="btn btn-primary" @click="setQuantityModal">Set Quantity</button>
                    </div>

                    <div class="form-row" v-if="cartContents.length !== 0">
                        <div class="col-sm-12">
                            <table class="table table-bordered table-sm table-responsive-md">
                                <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Details</th>
                                    <th class="text-center">Quantity</th>
                                    <th class="text-right">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="(item, itemIndex, _itemIndex) in cartContents" :key="itemIndex">
                                    <td class="text-center">{{ _itemIndex + 1 }}</td>
                                    <td class="text-center">
                                        {{ item.name }}
                                        <small class="d-block">{{ item.attributes.meta.product.model }}</small>
                                    </td>
                                    <td class="text-center">
                                        {{ item.attributes.meta.total_quantity.display }}
                                    </td>
                                    <td class="text-right">
                                        <a href="#" class="btn btn-info btn-sm" @click.prevent="editCartProduct(itemIndex)">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a href="#" class="btn btn-danger btn-sm" @click.prevent="removeCartItem(itemIndex)">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="note">Note</label>
                        <textarea class="form-control" v-model="note" id="note" rows="3" placeholder="Enter note"></textarea>
                    </div>
                    <div class="form-group text-right">
                        <button type="button" @click="clearCart" class="btn btn-danger">Clear Cart</button>
                        <button type="submit" @click.prevent="proceedPurchase" class="btn btn-success">Production In</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quantity Modal -->
        <div class="modal fade" id="quantityModal" ref="quantityModal" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Select Quantity</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" v-if="quantityModalData">
                        <div class="row" v-if="quantityModalData.product_unit_labels">
                            <div class="col-md-3" v-for="(warehouse, warehouseIndex) in warehouses" :key="warehouseIndex">
                                <h5 class="card-title mb-0">{{ warehouse.title }}</h5>
                                <small class="card-subtitle text-muted">{{ (quantityModalData.warehouses.find( productWarehouse => (productWarehouse.id === warehouse.id))) ? 'Available in stock: ' + quantityModalData.warehouses.find( productWarehouse => (productWarehouse.id === warehouse.id)).product_quantity_in_unit.display : 'Stock is not available in this warehouse' }}</small>
                                <div class="input-group mb-3" v-for="(label, labelIndex) in quantityModalData.product_unit_labels" :key="labelIndex">
                                    <input
                                        type="number"
                                        v-if="quantities[warehouse.id]"
                                        class="form-control"
                                        :value="quantities[warehouse.id][labelIndex]"
                                        @keyup="addQuantity($event, warehouse.id, labelIndex)"
                                        @blur="addQuantity($event, warehouse.id, labelIndex)"
                                        @change="addQuantity($event, warehouse.id, labelIndex)"
                                    />
                                    <input
                                        v-else
                                        type="number"
                                        class="form-control"
                                        @keyup="addQuantity($event, warehouse.id, labelIndex)"
                                        @blur="addQuantity($event, warehouse.id, labelIndex)"
                                        @change="addQuantity($event, warehouse.id, labelIndex)"
                                    />
                                    <div class="input-group-append">
                                        <span class="input-group-text">{{ label }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" v-if="errors.quantities">
                            <div class="col-12 text-danger">
                                <small><strong>Error: </strong> {{ errors.quantities[0] }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                        <button type="button" @click="addToCart" class="btn btn-success">Add to Cart</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
export default {
    name: "PurchaseProductComponent",
    watch: {
        productId: {
            handler: function (productId) {
                this.product = this.products.find(product => product.id === productId)
            }
        },
    },

    data(){
        return {
            errors: {},
            date: null,
            voucherNo: null,
            note: null,
            products: [],
            product: {},
            productId: null,
            warehouses: [],
            quantityModal: {},
            quantityModalData: {},
            quantities: {},
            cartContents: [],
            quantityModelIsOpen: false,
        }
    },
    methods: {
        editCartProduct(itemIndex){
            let cartItem = this.cartContents[itemIndex]

            this.quantityModalData = {
                id: cartItem.id,
                product_unit_labels: cartItem.attributes.meta.product.product_unit_labels,
                warehouses: cartItem.attributes.meta.product.warehouses,
            }

            this.quantities = cartItem.attributes.meta.request.quantities

            this.showQuantityModal()
        },
        init(){
            this.allActiveData()
        },
        setSessionData(data){
            this.date = data.date || null
            this.voucherNo = data.voucher_no || null
            this.note = data.note || null
        },

        getCartContents(){
            this.$awn.asyncBlock(
                axios.post(baseURL + 'user/purchase/get-cart-contents'),
                response => {
                    this.cartContents = response.data
                },
                reason => console.log(reason)
            )
        },
        setQuantityModal(){
            if(!this.productId){
                alert('Select Product')
                return
            }

            this.quantityModalData = this.product

            this.showQuantityModal()
        },
        addQuantity(event, warehouseId, order){
            if (!(warehouseId in this.quantities)) {
                this.$set(this.quantities, warehouseId, {})
            }
            this.$set(this.quantities[warehouseId], order, event.target.value )
        },
        showQuantityModal(){
            this.quantityModal.modal('show')
            this.quantityModelIsOpen = true
        },
        hideQuantityModal(){
            this.quantityModal.modal('hide')
            this.quantityModelIsOpen = false
        },

        resetQuantityModal(){
            this.errors = {}
            this.productId = null
            this.quantities = {}
            this.quantityModalData = {}
        },
        allActiveData(){ //load all active suppliers
            axios.all([
                axios.post(baseURL + 'user/get-all-active-products'),
                axios.post(baseURL + 'user/get-all-active-warehouses'),
                axios.post(baseURL + 'user/purchase/get-cart-contents'),
            ])
                .then(axios.spread((products, warehouses, cartContents,) => {
                    this.products = products.data
                    this.warehouses = warehouses.data
                    this.cartContents = cartContents.data.purchase_items
                    this.setSessionData(cartContents.data.input)
                }))
                .catch(error => console.log(error))
        },
        addToCart(){
            this.errors = {}
            this.$awn.asyncBlock(
                axios.post(baseURL + 'user/production-in/add-to-cart', {
                    date: this.date,
                    product_id: this.quantityModalData.id,
                    voucher_no: this.voucherNo,
                    note: this.note,
                    quantities: this.quantities
                }),
                response => {
                    this.cartContents = response.data.purchase_items
                    this.setSessionData(response.data.input)
                    this.hideQuantityModal()
                    console.log(response.data)
                },
                reason => {
                    if(reason.response.status === 422){
                        this.errors = reason.response.data.errors
                        this.$awn.alert('Insufficient quantity')
                    }else{
                        this.$awn.alert('Opps! Something went wrong')
                    }
                }
            )
        },
        removeCartItem(itemIndex){
            if (!confirm('Are you sure want to remove this item?')) return;

            let cartItem = this.cartContents[itemIndex]
            this.$awn.asyncBlock(
                axios.post(baseURL + 'user/production-in/remove-cart-item', {
                    product_id: cartItem.id
                }),
                response => {
                    this.cartContents = response.data.purchase_items
                    this.setSessionData(response.data.input)
                    console.log(response.data)
                },
                reason => console.log(reason)
            )
        },
        clearCart(){
            this.$awn.asyncBlock(
                axios.post(baseURL + 'user/production-in/clear-cart-contents'),
                response => {
                    location.reload(true)
                },
                reason => {
                    console.log(reason)
                }
            )
        },

        proceedPurchase(){

            if (this.quantityModelIsOpen){
                this.addToCart()
                return
            }
            // date validation
            if (!this.date){
                alert('Enter Date')
                return;
            }

            // voucher validation
            if (!this.voucherNo){
                alert('Enter Voucher Number')
                return;
            }

            this.$awn.asyncBlock(
                axios.post(baseURL + 'user/productionIn', {
                    date: this.date,
                    product_id: this.quantityModalData.id,
                    voucher_no: this.voucherNo,
                    note: this.note,

                }),
                response => {
                    //redirect to purchase details page
                    window.location.href = baseURL + 'user/productionIn/'
                    console.log(response.data)
                },
                reason => console.log(reason)
            )
        },
    },
    mounted() {
        this.quantityModal = $(this.$refs.quantityModal)
        this.init()
        this.quantityModal.on('hidden.bs.modal', () => this.resetQuantityModal())
    }
}
</script>

<style scoped>
table td, table th{
    padding: 5px;
}
</style>
