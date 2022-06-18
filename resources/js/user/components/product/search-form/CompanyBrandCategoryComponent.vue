<template>
    <div class="form-row">
        <div class="col-md-4 mb-3">
            <label for="company">Supplier/Company</label>
            <select name="party_id" v-model="supplierId" @change="loadBrands(supplierId)" id="company" class="form-control">
                <option :value="null">All Suppliers/Companies</option>
                <option v-for="(supplier, index) in suppliers" :value="supplier.id" :key="index">{{ supplier.name }}</option>
            </select>
        </div>
        <div class="col-md-4 mb-3">
            <label for="brand">Brand</label>
            <select name="brand_id" v-model="brandId" @change="loadCategories(brandId)" class="form-control" id="brand">
                <option :value="null">All Brands</option>
                <option value="" v-for="(brand, index) in brands" :value="brand.id" :key="index">{{ brand.name }}</option>
            </select>
        </div>
        <div class="col-md-4 mb-3">
            <label for="category">Category</label>
            <select name="category_id" v-model="categoryId" class="form-control" id="category">
                <option :value="null">All Categories</option>
                <option v-for="(category, index) in categories" :value="category.id" :key="index">{{ category.name }}</option>
            </select>
        </div>
    </div>
</template>

<script>
    export default {
        name: "CompanyBrandCategoryComponent",
        props: {
            suppliers: {
                type: Array,
                required: true
            },
            // categories: {
            //     type: Array,
            //     required: true
            // },
            searchedQuery: {
                type: Object,
                required: false,
                default: function () {
                    return {}
                }
            }
        },
        data() {
            return {
                supplierId : null,
                brandId: null,
                brands: [],
                categoryId: null,
                categories: []
            }
        },
        methods: {
            loadBrands(supplierId){
                if (!supplierId) {
                    this.brandId = null
                    this.brands = []
                    this.categoryId = null
                    this.categories = []
                    return
                }
                this.$awn.asyncBlock(
                    axios.post(baseURL + 'user/get-brands-from-supplier', {
                        supplierId
                    }),
                    response => {
                        console.log(response.data)
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
                if (!brandId) {
                    this.categoryId = null
                    this.categories = []
                    return
                }
                this.$awn.asyncBlock(
                    axios.post(baseURL + 'user/get-categories-from-brand', {
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
            initValues(){
                //set option
                if ('brands' in this.searchedQuery){
                    this.brands = this.searchedQuery.brands
                }
                if ('categories' in this.searchedQuery){
                    this.categories = this.searchedQuery.categories
                }

                //set value
                if ('party_id' in this.searchedQuery){
                    this.supplierId = this.searchedQuery.party_id
                }
                if ('brand_id' in this.searchedQuery){
                    this.brandId = this.searchedQuery.brand_id
                }
                if ('category_id' in this.searchedQuery){
                    this.categoryId = this.searchedQuery.category_id
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
