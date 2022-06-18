<template>
    <div class="row">
        <div class="col-md-5 required">
            <label for="business">Business</label>
            <select name="businessId" class="form-control" v-model="report.business.id" @change="getWarehouse(report.business.id)" id="business">
                <option selected>Chosse one</option>
                <option v-for="(business, index) in businesses" :value="business.id" :key="index">{{ business.name }}</option>
            </select>
        </div>

        <div class="col-md-5">
            <label for="warehouse">Warehouse</label>
            <select name="warehouseId" class="form-control" id="warehouse" v-model="report.business.warehouse">
                <option selected disabled>Chosse one</option>
                <option v-for="(warehouse, index) in warehouses" :value="warehouse.id" :key="index">{{ warehouse.title }}</option>
            </select>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'BusinessWarehouseReportComponent',
        props: ['businesses'],
        data(){
            return {
                warehouses: [],
                report:{
                    business:{
                        id: null,
                        warehouse: null,
                    },

                }
            }
        },
        methods: {
            getWarehouse(id){
                this.$awn.asyncBlock(
                    axios.post(baseURL + 'get-business-warehouse', {
                        id: id
                    }),
                    response => {
                        //set data for warehouse
                        this.warehouses = response.data;
                        // console response
                        console.log(response.data);
                    },
                    reason => {
                        console.log(reason)
                    })
            }
        },
        mounted() {
            console.log(this.businesses)
        }
    }
</script>
