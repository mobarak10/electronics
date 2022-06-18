<template>
    <div class="card">
        <!-- header start-->
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="m-0">{{lang.create_new_product}}</h5>
            <div class="btn-group" role="group" aria-level="Action area">
                <a :href="baseURL + 'user/product'" title="show product list" class="btn btn-success" style="margin-right: 5px">
                    <i class="fa fa-list" aria-hidden="true"></i>
                </a>
            </div>
        </div>
        <!-- header end-->

        <!-- Body Start-->
        <div class="card-body p-0">
            <div class="col-12 py-2">
                <form action="" method="POST" @submit.prevent="saveProduct">
                    <div class="form-row">
                        <div class="form-group col-md-8 pr-md-5">
                            <div class="form-row">
                                <!-- Brand Start -->
                                <div class="form-group col-md-6 required">
                                    <label for="brand">{{lang.brand}}</label>
                                    <select
                                        name="brand_id"
                                        v-model="brandId"
                                        class="form-control"
                                        id="brand">
                                        <option :value="null" selected disabled> {{lang.brand}} {{lang.select}}</option>
                                        <option
                                            v-for="(brand, index) in brands"
                                            :value="brand.id"
                                            :key="index"
                                        >{{ brand.name }}
                                        </option>
                                    </select>
                                </div>
                                <!-- Brand End -->

                                <!-- Category Start -->
                                <div class="form-group col-md-6 required">
                                    <label for="category">{{lang.category}}</label>
                                    <select
                                        name="category_id"
                                        v-model="categoryId"
                                        class="form-control"
                                        id="category">

                                        <option :value="null" selected disabled>{{lang.category}} {{lang.select}}</option>
                                        <option
                                            v-for="(category,
                                            index) in categories"
                                            :value="category.id"
                                            :key="index">
                                            {{ category.name }}
                                        </option>
                                    </select>
                                </div>
                                <!-- Category End -->

                                <!-- Name Start -->
                                <div class="form-group col-md-6 required">
                                    <label for="name">{{lang.product_name}}</label>
                                    <input
                                        type="text"
                                        v-model="name"
                                        class="form-control"
                                        id="name"
                                        placeholder="Enter product name"/>
                                </div>
                                <!-- Name End -->

                                <!-- Barcode Start -->
                                <div class="form-group col-md-6">
                                    <label for="barcode">Barcode</label>
                                    <input
                                        type="text"
                                        v-model="barcode"
                                        class="form-control"
                                        id="barcode"
                                        placeholder="Enter barcode"/>
                                </div>
                                <!-- Barcode End -->

                                <!-- Purchase Price Start -->
                                <div class="form-group col-md-6 required">
                                    <label for="purchase-price">{{lang.purchase_price}}</label>

                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">BDT</span>
                                        </div>
                                        <input
                                            type="number"
                                            v-model="purchasePrice"
                                            step="any"
                                            id="purchase-price"
                                            class="form-control"
                                            placeholder="Enter purchase price"/>
                                    </div>
                                </div>
                                <!-- Purchase Price End -->

                                <!-- Dealer Price Start -->
                                <div class="form-group col-md-6 required">
                                    <label for="sale-price">{{lang.sale_price}}</label>

                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">BDT</span>
                                        </div>
                                        <input
                                            type="number"
                                            v-model="salePrice"
                                            step="any"
                                            id="sale-price"
                                            class="form-control"
                                            placeholder="Enter sale price"/>
                                    </div>
                                </div>
                                <!-- Dealer Price End -->

                                <!-- Sub Dealer Price Start -->
                                <div class="form-group col-md-6 required">
                                    <label for="wholesale_price">{{lang.wholesale_price}}</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">BDT</span>
                                        </div>
                                        <input
                                            type="number"
                                            v-model="wholesalePrice"
                                            step="any"
                                            required
                                            id="wholesale_price"
                                            class="form-control"
                                            placeholder="Enter sub dealer price"/>
                                    </div>
                                </div>
                                <!-- Sub Dealer Price End -->

                                <div class="form-group col-md-6">
                                    <label for="stockAlert">{{ lang.stock_alert }}</label>
                                    <input
                                        type="text"
                                        v-model="stockAlert"
                                        class="form-control"
                                        id="stockAlert"
                                        placeholder="Enter stock alert"/>
                                </div>

                            </div>
                            <div class="form-group">
                                <label for="description">{{ lang.description }}</label>
                                <textarea
                                    class="form-control"
                                    v-model="description"
                                    placeholder="Enter product description"
                                    id="description"
                                    rows="5">
                                </textarea>
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <div class="form-row">
                                <div class="form-group col-md-12 required">
                                    <label for="unit">{{ lang.unit }}</label>
                                    <select
                                        v-model="unit"
                                        @change="changeUnit"
                                        class="form-control"
                                        id="unit"
                                        required>
                                        <option :value="null" selected disabled>{{ lang.unit }} {{ lang.select }} </option>
                                        <option
                                            v-for="(unit, index) in units"
                                            :value="unit"
                                            :key="index">
                                            {{ unit.name }} ({{ unit.description }})
                                        </option>
                                    </select>
                                </div>

                                <div class="form-group col-md-12">
                                    <div
                                        v-for="(warehouse, index) in warehouses"
                                        :key="index"
                                        class="card mb-2">

                                        <div class="card-header">
                                            {{ warehouse.title }}
                                        </div>

                                        <div class="card-body p-0">
                                            <input
                                                type="number"
                                                v-if="!parsedUnits.length"
                                                class="form-control"
                                                placeholder="Select Unit First"
                                                disabled/>
                                            <div
                                                class="input-group"
                                                v-else
                                                v-for="(unit,
                                                unitIndex) in parsedUnits"
                                                :key="unitIndex">
                                                <input
                                                    type="number"
                                                    min="0"
                                                    :value="
                                                        quantity[warehouse.id]
                                                            ? quantity[
                                                                  warehouse.id
                                                              ][unitIndex]
                                                            : ''
                                                    "
                                                    @keyup="
                                                        addQuantity(
                                                            $event,
                                                            warehouse.id,
                                                            unitIndex
                                                        )
                                                    "
                                                    @blur="
                                                        addQuantity(
                                                            $event,
                                                            warehouse.id,
                                                            unitIndex
                                                        )
                                                    "
                                                    @change="
                                                        addQuantity(
                                                            $event,
                                                            warehouse.id,
                                                            unitIndex
                                                        )
                                                    "
                                                    class="form-control"
                                                    :placeholder="
                                                        'Enter ' +
                                                            unit.toLowerCase() +
                                                            ' amount'
                                                    "
                                                />
                                                <div class="input-group-append">
                                                    <span class="input-group-text" style="min-width: 100px;">{{ unit }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-12 text-right">
                            <button type="reset" class="btn btn-danger">
                                {{ lang.reset }}
                            </button>
                            <button type="submit" class="btn btn-primary">
                                {{ lang.save }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- Body End-->
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
        },
        lang: {
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
            name: null,
            barcode: null,
            purchasePrice: null,
            salePrice: null,
            wholesalePrice: null,
            stockAlert: null,
            vat: 0,
            description: null,
            quantity: []
        };
    },
    methods: {
        changeUnit(event) {
            if (!this.unit) {
                this.parsedUnits = [];
                return;
            }
            this.quantity = {};
            this.parsedUnits = this.unit.labels.split("/");
        },
        addQuantity(event, warehouseId, order) {
            if (!(warehouseId in this.quantity)) {
                this.$set(this.quantity, warehouseId, {});
            }
            this.$set(this.quantity[warehouseId], order, event.target.value);
        },
        saveProduct() {
            this.$awn.asyncBlock(
                axios.patch(baseURL + "user/product/" + this.product.id, {
                    brand_id: this.brandId,
                    category_id: this.categoryId,
                    name: this.name,
                    barcode: this.barcode,
                    purchase_price: this.purchasePrice,
                    sale_price: this.salePrice,
                    wholesale_price: this.wholesalePrice,
                    description: this.description,
                    stock_alert: this.stockAlert,
                    unit_id: this.unit.id,
                    /*-----------*/
                    quantity: this.quantity
                }),
                response => {
                    //console.log(response.data)
                    location.href = response.data;
                },
                error => {
                    this.$awn.alert(
                        "Opps! something went wrong. Try again later"
                    );
                }
            );
        },
        initValues() {
            //set option
            this.brands = this.extras.brands;
            this.categories = this.extras.categories;
            //set value
            this.supplierId = this.product.party_id;
            this.brandId = this.product.brand_id;
            this.categoryId = this.product.category_id;
            this.model = this.product.model;
            this.name = this.product.name;
            this.barcode = this.product.barcode;
            this.purchasePrice = this.product.purchase_price;
            this.salePrice = this.product.sale_price;
            this.wholesalePrice = this.product.wholesale_price
            this.stockAlert = this.product.stock_alert;
            this.vat = this.product.vat;
            this.description = this.product.description;
            this.unit = this.extras.unit;
            this.changeUnit();
            if (!(this.extras.quantity.length === 0)) {
                //if it has quantity then assign
                this.quantity = this.extras.quantity;
            }
        }
    },
    mounted() {
        this.initValues();
    }
};
</script>

<style scoped></style>
