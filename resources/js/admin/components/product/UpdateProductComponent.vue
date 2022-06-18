<template>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="m-0">Update Product</h5>
        </div>

        <div class="card-body p-0">
            <div class="col-12 py-2">
                <form action="" method="POST" @submit.prevent="saveProduct">
                    <div class="form-row">
                        <div class="col-md-8 pr-5">
                            <div class="form-row">
                                <div class="form-group col-md-6 required">
                                    <label for="supplier">Company/Supplier</label>
                                    <select name="party_id" v-model="supplierId" class="form-control" @change="loadBrands(supplierId)" id="supplier">
                                        <option :value="null" selected disabled>Select Company/Supplier</option>
                                        <option v-for="(supplier, index) in suppliers" :value="supplier.id" :key="index">{{ supplier.name }}</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6 required">
                                    <label for="brand">Brand</label>
                                    <select name="brand_id" v-model="brandId" @change="loadCategories(brandId)" class="form-control" id="brand">
                                        <option :value="null" selected disabled>Select Brand</option>
                                        <option value="" v-for="(brand, index) in brands" :value="brand.id" :key="index">{{ brand.name }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6 required">
                                    <label for="category">Category</label>
                                    <select name="category_id" v-model="categoryId" class="form-control" id="category">
                                        <option :value="null" selected disabled>Select Category</option>
                                        <option v-for="(category, index) in categories" :value="category.id" :key="index">{{ category.name }}</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="model">Model</label>
                                    <input type="text" v-model="model" class="form-control" id="model" placeholder="Enter model no">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6 required">
                                    <label for="name">Product Name</label>
                                    <input type="text" v-model="name" class="form-control" id="name" placeholder="Enter product pame">
                                </div>
                                <div class="form-group col-md-6 required">
                                    <label for="name">@lang('contents.barcode')</label>
                                    <input type="text" readonly v-model="barcode" class="form-control" id="barcode">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="purchase-price">Purchase Price</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">BDT</span>
                                        </div>
                                        <input type="number" v-model="purchasePrice" step="any" id="purchase-price" class="form-control" placeholder="Enter purchase price">
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="wholesale-price">Wholesale Price</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">BDT</span>
                                        </div>
                                        <input type="number" v-model="wholesalePrice" step="any" id="wholesale-price" class="form-control" placeholder="Enter wholesale price">
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="retail-price">Retail Price</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">BDT</span>
                                        </div>
                                        <input type="number" v-model="retailPrice" step="any" id="retail-price" class="form-control" placeholder="Enter retail price">
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="stock-alert">Stock Alert</label>
                                    <input type="text" v-model="stockAlert" name="phone" class="form-control" id="stock-alert" placeholder="Enter minimum amount of stock alert">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea class="form-control" v-model="description" placeholder="Enter product description" id="description" rows="5"></textarea>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group col-md-12">
                                <div class="form-row">
                                    <div class="form-group col-md-12 required">
                                        <label for="unit">Unit</label>
                                        <select v-model="unit" @change="changeUnit" class="form-control" id="unit">
                                            <option :value="null" selected disabled>Select Unit</option>
                                            <option v-for="(unit, index) in units" :value="unit" :key="index">{{ unit.name }} ({{ unit.description }})</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-12">
                                        <div v-for="(warehouse, index) in warehouses" :key="index" class="card mb-2">
                                            <div class="card-header">
                                                {{ warehouse.title }}
                                            </div>

                                            <div class="card-body p-0">
                                                <input type="number" v-if="!parsedUnits.length" class="form-control" placeholder="Select Unit First" disabled>
                                                <div class="input-group required" v-else v-for="(unit, unitIndex) in parsedUnits" :key="unitIndex">
                                                    <input type="number" min="0"
                                                           :value="(quantity[warehouse.id]) ? (quantity[warehouse.id][unitIndex]) ? quantity[warehouse.id][unitIndex] : '' : ''"
                                                           @keyup="addQuantity($event, warehouse.id, unitIndex)"
                                                           @blur="addQuantity($event, warehouse.id, unitIndex)"
                                                           @change="addQuantity($event, warehouse.id, unitIndex)"
                                                           class="form-control" :placeholder="'Enter '+ unit.toLowerCase() +' amount'">
                                                    <div class="input-group-append text-right">
                                                        <span class="input-group-text" style="min-width: 100px;">{{unit}}</span>
                                                    </div>
                                                </div>
                                                <!--<input type="number" v-if="!parsedUnits.length" class="form-control" placeholder="Select Unit First" disabled>
                                                <input type="number" v-else v-for="(unit, unitIndex) in parsedUnits" :key="unitIndex" :value="quantity[warehouse.id][unitIndex]" @keyup="addQuantity($event, warehouse.id, unitIndex)" class="form-control" :placeholder="unit">-->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <button type="button" @click.prevent="initValues()" class="btn btn-danger">Reset</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</template>

<script>
    export default {
        name: "UpdateProductComponent",
        props: {
            product: {
                type: Object,
                required: true
            },
            suppliers: {
                type: Array,
                required: true,
            },
            units: {
                type: Array,
                required: true
            },
            warehouses: {
                type: Array,
                required: true
            },
            extras: {
                type: Object,
                required: true
            }
        },
        data() {
            return {
                supplierId: null,
                brands: [],
                brandId: null,
                categoryId: null,
                categories: [],
                unit: null,
                parsedUnits: [],
                /*----*/
                model: null,
                name: null,
                barcode: null,
                purchasePrice: null,
                wholesalePrice: null,
                retailPrice: null,
                stockAlert: null,
                description: null,
                quantity: []
            }
        },
        methods: {
            loadBrands(supplierId){
                this.$awn.asyncBlock(
                    axios.post(baseURL + 'get-brands-from-supplier', {
                        supplierId
                    }),
                    response => {
                        this.brands = response.data
                        this.brandId = null
                        this.categoryId = null
                        this.categories = []
                    },
                    error => {
                        console.log(error)
                    }
                )
            },
            loadCategories(brandId){
                this.$awn.asyncBlock(
                    axios.post(baseURL + 'get-categories-from-brand', {
                        brandId
                    }),
                    response => {
                        this.categories = response.data
                        this.categoryId = null
                    },
                    error => {
                        console.log(error)
                    }
                )
            },
            changeUnit(event){
                if (!this.unit) {
                    this.parsedUnits = []
                    return
                }
                this.quantity = {}
                this.parsedUnits = this.unit.labels.split('/')
            },
            addQuantity(event, warehouseId, order){
                if (!(warehouseId in this.quantity)) {
                    this.$set(this.quantity, warehouseId, {} )
                }
                this.$set(this.quantity[warehouseId], order, event.target.value )
            },
            saveProduct(){
                this.$awn.asyncBlock(
                    axios.patch(baseURL + 'admin/product/' + this.product.id, {
                        party_id: this.supplierId,
                        brand_id: this.brandId,
                        category_id: this.categoryId,
                        model: this.model,
                        name: this.name,
                        barcode: this.barcode,
                        purchase_price: this.purchasePrice,
                        wholesale_price: this.wholesalePrice,
                        retail_price: this.retailPrice,
                        stock_alert: this.stockAlert,
                        description: this.description,
                        unit_id: this.unit.id,
                        /*-----------*/
                        quantity: this.quantity

                    }),
                    response => {
                        //console.log(response.data)
                        location.href = response.data
                    },
                    error => {
                        this.$awn.alert('Opps! something went wrong. Try again later')
                    }
                )
            },
            initValues() {
                //set option
                this.brands = this.extras.brands
                this.categories = this.extras.categories

                //set value
                this.supplierId = this.product.party_id
                this.brandId = this.product.brand_id
                this.categoryId = this.product.category_id
                this.model = this.product.model
                this.name = this.product.name
                this.barcode = this.product.barcode
                this.purchasePrice = this.product.purchase_price
                this.wholesalePrice = this.product.wholesale_price
                this.retailPrice = this.product.retail_price
                this.stockAlert = this.product.stock_alert
                this.description = this.product.description
                this.unit = this.extras.unit
                this.changeUnit()
                if (!(this.extras.quantity.length === 0)) { //if has quantity then assign
                    this.quantity = this.extras.quantity
                }
            }
        },
        mounted() {
            this.initValues()
        }
    }
</script>

<style scoped>

</style>
