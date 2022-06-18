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
                                        :class="{
                                            'is-invalid': errors.brand_id
                                        }"
                                        id="brand">
                                        <option :value="null" selected disabled> {{lang.brand}} {{lang.select}}</option>
                                        <option
                                            v-for="(brand, index) in brands"
                                            :value="brand.id"
                                            :key="index"
                                            >{{ brand.name }}
                                        </option>
                                    </select>

                                    <div v-if="errors.brand_id" class="invalid-feedback">
                                        {{ errors.brand_id[0] }}
                                    </div>
                                </div>
                                <!-- Brand End -->

                                <!-- Category Start -->
                                <div class="form-group col-md-6 required">
                                    <label for="category">{{lang.category}}</label>
                                    <select
                                        name="category_id"
                                        v-model="categoryId"
                                        class="form-control"
                                        :class="{
                                            'is-invalid': errors.category_id
                                        }"
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

                                    <div v-if="errors.category_id" class="invalid-feedback">
                                        {{ errors.category_id[0] }}
                                    </div>
                                </div>
                                <!-- Category End -->

                                <!-- Name Start -->
                                <div class="form-group col-md-6 required">
                                    <label for="name">{{lang.product_name}}</label>
                                    <input
                                        type="text"
                                        v-model="name"
                                        class="form-control"
                                        :class="{ 'is-invalid': errors.name }"
                                        id="name"
                                        placeholder="Enter product name"/>

                                    <div v-if="errors.name" class="invalid-feedback">
                                        {{ errors.name[0] }}
                                    </div>
                                </div>
                                <!-- Name End -->

                                <!-- Barcode Start -->
                                <div class="form-group col-md-6">
                                    <label for="barcode">Barcode</label>
                                    <input
                                        type="text"
                                        v-model="barcode"
                                        class="form-control"
                                        :class="{
                                            'is-invalid': errors.barcode
                                        }"
                                        id="barcode"
                                        placeholder="Enter barcode"/>

                                    <div v-if="errors.barcode" class="invalid-feedback">
                                        {{ errors.barcode[0] }}
                                    </div>
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
                                            :class="{
                                                'is-invalid':
                                                    errors.purchase_price
                                            }"
                                            placeholder="Enter purchase price"/>

                                        <div v-if="errors.purchase_price" class="invalid-feedback">
                                            {{ errors.purchase_price[0] }}
                                        </div>
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
                                            :class="{
                                                'is-invalid':
                                                    errors.sale_price
                                            }"
                                            placeholder="Enter sale price"/>

                                        <div v-if="errors.sale_price" class="invalid-feedback">
                                            {{ errors.sale_price[0] }}
                                        </div>
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
                                            :class="{
                                                'is-invalid':
                                                    errors.wholesale_price
                                            }"
                                            placeholder="Enter sub dealer price"/>

                                        <div v-if="errors.wholesale_price" class="invalid-feedback">
                                            {{ errors.wholesale_price[0] }}
                                        </div>
                                    </div>
                                </div>
                                <!-- Sub Dealer Price End -->

                                <div class="form-group col-md-6">
                                    <label for="stockAlert">{{ lang.stock_alert }}</label>
                                    <input
                                        type="text"
                                        v-model="stockAlert"
                                        class="form-control"
                                        :class="{ 'is-invalid': errors.stockAlert }"
                                        id="stockAlert"
                                        placeholder="Enter stock alert"/>

                                    <div v-if="errors.stockAlert" class="invalid-feedback">
                                        {{ errors.stockAlert[0] }}
                                    </div>
                                </div>

                            </div>
                            <div class="form-group">
                                <label for="description">{{ lang.description }}</label>
                                <textarea
                                    class="form-control"
                                    :class="{
                                        'is-invalid': errors.description
                                    }"
                                    v-model="description"
                                    placeholder="Enter product description"
                                    id="description"
                                    rows="5">
                                </textarea>

                                <div v-if="errors.description" class="invalid-feedback">
                                    {{ errors.description[0] }}
                                </div>
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
                                        :class="{
                                            'is-invalid': errors.unit_id
                                        }"
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

                                    <div v-if="errors.unit_id" class="invalid-feedback">
                                        {{ errors.unit_id[0] }}
                                    </div>
                                </div>

<!--                                <div class="form-group col-md-12">-->
<!--                                    <div-->
<!--                                        v-for="(warehouse, index) in warehouses"-->
<!--                                        :key="index"-->
<!--                                        class="card mb-2">-->

<!--                                        <div class="card-header">-->
<!--                                            {{ warehouse.title }}-->
<!--                                        </div>-->

<!--                                        <div class="card-body p-0">-->
<!--                                            <input-->
<!--                                                type="number"-->
<!--                                                v-if="!parsedUnits.length"-->
<!--                                                class="form-control"-->
<!--                                                placeholder="Select Unit First"-->
<!--                                                disabled/>-->
<!--                                            <div-->
<!--                                                class="input-group"-->
<!--                                                v-else-->
<!--                                                v-for="(unit,-->
<!--                                                unitIndex) in parsedUnits"-->
<!--                                                :key="unitIndex">-->
<!--                                                <input-->
<!--                                                    type="number"-->
<!--                                                    min="0"-->
<!--                                                    :value="-->
<!--                                                        quantity[warehouse.id]-->
<!--                                                            ? quantity[-->
<!--                                                                  warehouse.id-->
<!--                                                              ][unitIndex]-->
<!--                                                            : ''-->
<!--                                                    "-->
<!--                                                    @keyup="-->
<!--                                                        addQuantity(-->
<!--                                                            $event,-->
<!--                                                            warehouse.id,-->
<!--                                                            unitIndex-->
<!--                                                        )-->
<!--                                                    "-->
<!--                                                    @blur="-->
<!--                                                        addQuantity(-->
<!--                                                            $event,-->
<!--                                                            warehouse.id,-->
<!--                                                            unitIndex-->
<!--                                                        )-->
<!--                                                    "-->
<!--                                                    @change="-->
<!--                                                        addQuantity(-->
<!--                                                            $event,-->
<!--                                                            warehouse.id,-->
<!--                                                            unitIndex-->
<!--                                                        )-->
<!--                                                    "-->
<!--                                                    class="form-control"-->
<!--                                                    :placeholder="-->
<!--                                                        'Enter ' +-->
<!--                                                            unit.toLowerCase() +-->
<!--                                                            ' amount'-->
<!--                                                    "-->
<!--                                                />-->
<!--                                                <div class="input-group-append">-->
<!--                                                    <span class="input-group-text" style="min-width: 100px;">{{ unit }}</span>-->
<!--                                                </div>-->
<!--                                            </div>-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                </div>-->
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
    name: "CreateProductComponent",
    props: {
        categories: {
            type: Array,
            required: true
        },
        brands: {
            type: Array,
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
        lang: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            errors: {},
            supplierId: null,
            brandId: null,
            categoryId: null,
            unit: null,
            parsedUnits: [],
            /*----*/
            model: null,
            name: null,
            barcode: null,
            purchasePrice: null,
            salePrice: null,
            wholesalePrice: null,
            stockAlert: null,
            description: null,
            quantity: {}
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
                axios.post(baseURL + "user/product", {
                    brand_id: this.brandId,
                    category_id: this.categoryId,
                    model: this.model,
                    name: this.name,
                    barcode: this.barcode,
                    purchase_price: this.purchasePrice,
                    sale_price: this.salePrice,
                    wholesale_price: this.wholesalePrice,
                    stock_alert: this.stockAlert,
                    vat: this.vat,
                    description: this.description,
                    unit_id: this.unit.id,
                    /*-----------*/
                    quantity: this.quantity
                }),
                response => {
                    location.reload(true);
                    console.log(response.data)
                },
                error => {
                    if (error.response.status === 422) {
                        //validation error
                        this.errors = error.response.data.errors;
                        this.$awn.alert(
                            "Op's! Enter the valid information of product"
                        );
                    } else {
                        this.$awn.alert(
                            "Op's! something went wrong. Try again later"
                        );
                    }
                }
            );
        },
        resetFields() {
            console.log("reset field triggered");
        }
    },
    mounted() {}
};
</script>

<style scoped></style>
