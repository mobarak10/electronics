<template>
    <div class="order-header">
        <form action="" @submit.prevent="searchByBarcode">
            <div class="input-group">
                <input type="text" v-model="barcode" class="form-control rounded-0" placeholder="Barcode" required>

                <div class="input-group-append">
                    <button class="btn btn-info rounded-0" type="submit" title="search">
                        <i class="fa fa-search"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
    import { mapGetters } from 'vuex'
    
    export default {
        name: "SearchByBarcodeComponent",
        computed: {
            ...mapGetters([
                'getterSingleActiveProduct'
            ])
        },
        data() {
            return {
                barcode: null
            }
        },
        methods: {
            searchByBarcode(){
                if(this.getterSingleActiveProduct(this.barcode)){
                    eventBus.$emit('modalShowProductInfo', this.barcode )
                } else {
                    this.$awn.alert('Product not found')
                }

                this.barcode = null
            }
        }

    }
</script>

<style scoped>

</style>
