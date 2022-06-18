<template>
    <div class="card"> 
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="m-0">Create New Product</h5> 
        </div>

        <div class="card-body p-0">
            <div class="col-12 py-2">
                <form action="" method="POST" @submit.prevent="saveProduct">
                    <div class="form-row">
                        <div class="form-group col-md-8 pr-md-5">
                            <div class="form-row">
                                <div class="form-group col-md-6 required">
                                    <label for="supplier">Company/Supplier</label>
                                    <select name="party_id" v-model="supplierId" class="form-control" :class="{'is-invalid': errors.party_id }" @change="loadBrands(supplierId)" id="supplier">
                                        <option :value="null" selected disabled>Select Company/Supplier</option>
                                        <option v-for="(supplier, index) in suppliers" :value="supplier.id" :key="index">{{ supplier.name }}</option>
                                    </select>
                                    <div v-if="errors.party_id" class="invalid-feedback">{{ errors.party_id [0] }}</div>
                                </div>
                                <div class="form-group col-md-6 required">
                                    <label for="brand">Brand</label>
                                    <select name="brand_id" v-model="brandId" @change="loadCategories(brandId)" class="form-control" :class="{'is-invalid': errors.brand_id }" id="brand">
                                        <option :value="null" selected disabled>Select Brand</option>
                                        <option value="" v-for="(brand, index) in brands" :value="brand.id" :key="index">{{ brand.name }}</option>
                                    </select>
                                    <div v-if="errors.brand_id" class="invalid-feedback">{{ errors.brand_id [0] }}</div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6 required">
                                    <label for="category">Category</label>
                                    <select name="category_id" v-model="categoryId" class="form-control" :class="{'is-invalid': errors.category_id }" id="category">
                                        <option :value="null" selected disabled>Select Category</option>
                                        <option v-for="(category, index) in categories" :value="category.id" :key="index">{{ category.name }}</option>
                                    </select>
                                    <div v-if="errors.category_id" class="invalid-feedback">{{ errors.category_id [0] }}</div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="model">Model</label>
                                    <input type="text" v-model="model" class="form-control" :class="{'is-invalid': errors.model }" id="model" placeholder="Enter model no">
                                    <div v-if="errors.model" class="invalid-feedback">{{ errors.model [0] }}</div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6 required">
                                    <label for="name">Product Name</label>
                                    <input type="text" v-model="name" class="form-control" :class="{'is-invalid': errors.name }" id="name" placeholder="Enter product name">
                                    <div v-if="errors.name" class="invalid-feedback">{{ errors.name [0] }}</div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="barcode">Barcode</label>
                                    <input type="text" v-model="barcode" class="form-control" :class="{'is-invalid': errors.barcode }" id="barcode" placeholder="Enter barcode">
                                    <div v-if="errors.barcode" class="invalid-feedback">{{ errors.barcode [0] }}</div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="purchase-price">Purchase Price</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">BDT</span>
                                        </div>
                                        <input type="number" v-model="purchasePrice" step="any" id="purchase-price" class="form-control" :class="{'is-invalid': errors.purchase_price }" placeholder="Enter purchase price">
                                        <div v-if="errors.purchase_price" class="invalid-feedback">{{ errors.purchase_price [0] }}</div>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="wholesale-price">Wholesale Price</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">BDT</span>
                                        </div>
                                        <input type="number" v-model="wholesalePrice" step="any" id="wholesale-price" class="form-control" :class="{'is-invalid': errors.wholesale_price }" placeholder="Enter wholesale price">
                                        <div v-if="errors.wholesale_price" class="invalid-feedback">{{ errors.wholesale_price [0] }}</div>
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
                                        <input type="number" v-model="retailPrice" step="any" id="retail-price" class="form-control" :class="{'is-invalid': errors.retail_price }" placeholder="Enter retail price">
                                        <div v-if="errors.retail_price" class="invalid-feedback">{{ errors.retail_price [0] }}</div>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="stock-alert">Stock Alert</label>
                                    <input type="number" v-model="stockAlert" name="phone" class="form-control" :class="{'is-invalid': errors.stock_alert }" id="stock-alert" placeholder="Enter minimum amount of stock alert">
                                    <div v-if="errors.stock_alert" class="invalid-feedback">{{ errors.stock_alert [0] }}</div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea class="form-control" :class="{'is-invalid': errors.description }" v-model="description" placeholder="Enter product description" id="description" rows="5"></textarea>
                                <div v-if="errors.description" class="invalid-feedback">{{ errors.description [0] }}</div>
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <div class="form-row">
                                <div class="form-group col-md-12 required">
                                    <label for="unit">Unit</label>
                                    <select v-model="unit" @change="changeUnit" class="form-control" :class="{'is-invalid': errors.unit_id }" id="unit" required>
                                        <option :value="null" selected disabled>Select Unit</option>
                                        <option v-for="(unit, index) in units" :value="unit" :key="index">{{ unit.name }} ({{ unit.description }})</option>
                                    </select>
                                    <div v-if="errors.unit_id" class="invalid-feedback">{{ errors.unit_id [0] }}</div>
                                </div>

                                <div class="form-group col-md-12">
                                    <div v-for="(warehouse, index) in warehouses" :key="index" class="card mb-2">
                                        <div class="card-header">
                                            {{ warehouse.title }}
                                        </div>

                                        <div class="card-body p-0">
                                            <input type="number" v-if="!parsedUnits.length" class="form-control" placeholder="Select Unit First" disabled>
                                            <div class="input-group" v-else v-for="(unit, unitIndex) in parsedUnits" :key="unitIndex">
                                                <input type="number" min="0" :value="(quantity[warehouse.id]) ? quantity[warehouse.id][unitIndex] : ''"
                                                       @keyup="addQuantity($event, warehouse.id, unitIndex)"
                                                       @blur="addQuantity($event, warehouse.id, unitIndex)"
                                                       @change="addQuantity($event, warehouse.id, unitIndex)"
                                                       class="form-control" :placeholder="'Enter '+ unit.toLowerCase() +' amount'">
                                                <div class="input-group-append">
                                                    <span class="input-group-text" style="min-width: 100px;">{{unit}}</span>
                                                </div>
                                            </div>
                                            <!--<input type="number" v-if="!parsedUnits.length" class="form-control" placeholder="Select Unit First" disabled>
                                            <input type="number" v-else v-for="(unit, unitIndex) in parsedUnits" :key="unitIndex" @keyup="addQuantity($event, warehouse.id, unitIndex)" class="form-control" :placeholder="unit">-->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-12 text-right">
                            <button type="submit" class="btn btn-primary">Create</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</template>

<script>
    export default {
        name: "CreateProductComponent",
        props: {
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
            }
        },
        data() {
            return {
                errors: {},
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
                quantity: {}
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
                this.parsedUnits = this.unit.labels.split('/');
            },
            addQuantity(event, warehouseId, order){
                if (!(warehouseId in this.quantity)) {
                    this.$set(this.quantity, warehouseId, {} )
                }
                this.$set(this.quantity[warehouseId], order, event.target.value )
            },
            saveProduct(){
                this.$awn.asyncBlock(
                    axios.post(baseURL + 'admin/product', {
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
                        location.reload(true)
                    },
                    error => {
                        if (error.response.status === 422){ //validation error
                            this.errors = error.response.data.errors
                            this.$awn.alert('Opps! Enter the valid information of product')
                        }else{
                            this.$awn.alert('Opps! something went wrong. Try again later')
                        }
                    }
                )
            },
            resetFields() {
                console.log('reset field triggered')
            }
        },
        mounted() {

        }
    }
</script>

<style scoped>

</style>
