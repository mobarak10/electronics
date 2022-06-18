<template>
    <div class="card col-md-12">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="m-0">Product Transfer </h5>
            <div class="btn-group" role="group" aria-level="Action area">
                <a :href="listURL" title="Create new transfer" class="btn btn-success" style="margin-right: 5px">
                    <i class="fa fa-list" aria-hidden="true"></i>
                </a>
            </div>
        </div>
        <div class="card-body">
            <form @submit.prevent="transferProducts" method="post">
                <div class="row">
                    <div class="form-group col-md-6 required">
                        <label for="fromWarehouse">From Warehouse</label>
                        <select v-model="transferProduct.fromWarehouse.id" @change="getProduct(transferProduct.fromWarehouse.id)" id="fromWarehouse" class="form-control">
                            <option value="" selected disabled>Choose one</option>
                            <option v-for="(fromWarehouse, index) in warehouses" :value="fromWarehouse" :key="index">{{ fromWarehouse.title }}</option>
                        </select>
                    </div>

                    <div class="form-group col-md-6 required">
                        <label for="toWarehouse">To Warehouse</label>
                        <select id="toWarehouse" v-model="transferProduct.toWarehouseId" class="form-control">
                            <option value="" selected disabled>Choose one</option>
                            <option v-for="(toWarehouse, index) in warehouses" :value="toWarehouse.id" :key="index">{{ toWarehouse.title }}</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-6 required">
                        <label for="products">Products</label>
                        <select v-model="transferProduct.product" id="products" class="form-control">
                            <option value="" selected disabled>Choose one</option>
                            <option v-for="(_product, index) in products" :value="_product" :key="index">{{ _product.name }}</option>
                        </select>
                    </div>

                    <div class="form-group col-md-6 required">
                        <label for="quantity">Quantity</label>
                        <div v-if="transferProduct.product != null">
                            <div v-for="(warehouse, index) in transferProduct.fromWarehouse" :key="index">
                                <div class="input-group mb-1" v-for="(unit, labelIndex) in transferProduct.product.product_unit_labels" :key="labelIndex">
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
                                        <span class="input-group-text">{{ unit }}</span>
                                    </div>
                                </div>
                                <small class="card-subtitle text-muted">{{ (transferProduct.product.warehouses.find( productWarehouse => (productWarehouse.id === transferProduct.fromWarehouse.id.id))) ? '( Available in stock in this warehouse: ' + transferProduct.product.warehouses.find( productWarehouse => (productWarehouse.id === transferProduct.fromWarehouse.id.id)).product_quantity_in_unit.display + ')'  : 'Stock is not available in this warehouse' }}</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-right">
                    <button type="submit" class="btn btn-primary">Transfer</button>
                </div>
            </form>
        </div>
    </div>
</template>
<script>
    export default {
        name: 'ProductTransferCreatComponent',
        props: ['warehouses'],
        computed: {
            listURL(){
                return baseURL + 'user/productTransfer'
                console.log(listURL);
            }
        },
        data(){
            return {
                transferProduct: {
                    fromWarehouse: {
                        id: null,
                    },
                    toWarehouseId: null,
                    product: null,
                    quantity: [],
                },
                products: {},
                quantities: {},
            }
        },

        methods: {
            getProduct(id){
                this.$awn.asyncBlock(
                    axios.post(baseURL + 'user/get-product-from-warehouse', {
                        id: id
                    }),
                    response => {
                        // set data
                        this.products = response.data;

                        // console
                        console.log(response.data);
                    },
                    reason => {
                        console.log(reason)
                    })
            },

            addQuantity(event, warehouseId, order){
                if (!(warehouseId in this.quantities)) {
                    this.$set(this.quantities, warehouseId, {})
                }
                this.$set(this.quantities[warehouseId], order, event.target.value )
            },

            transferProducts(){
                this.errors = {}
                // validation
                if (!this.transferProduct.fromWarehouse.id) {
                    alert('Select From Warehouse')
                    return
                }

                if (!this.transferProduct.toWarehouseId) {
                    alert('Select To warehouse')
                    return
                }

                if (!this.transferProduct.product) {
                    alert('Select product')
                    return
                }

                if (!this.quantities) {
                    alert('Enter quantity')
                    return
                }
                this.$awn.asyncBlock(
                    axios.post(baseURL + 'user/productTransfer', {
                        fromWarehouseId: this.transferProduct.fromWarehouse.id.id,
                        toWarehouseId: this.transferProduct.toWarehouseId,
                        productId: this.transferProduct.product.id,
                        quantities: this.quantities,
                    }),
                    response => {
                        this.transferProduct.product = null
                        this.quantities = false
                        // this.$awn.warning(response.data.warning);
                        // this.$awn.success(response.data.message);
                        window.location.reload();
                    },
                    reason => {

                    })
            },

        },

        mounted() {
            console.log(this.fromWarehouse)
        }
    }

</script>
