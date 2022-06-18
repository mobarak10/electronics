<template>
    <div class="form-row">
        <div class="form-group col-md-6 required">
            <label for="business">Business</label>
            <select name="business_id" id="business" v-model="report.business.id" @change="getExpenditure(report.business.id)" class="form-control" required>
                <option selected disabled>Choose one</option>
                <option v-for="(business, index) in businesses" :value="business.id" :key="index">{{ business.name }}</option>
            </select>
        </div>

        <div class="form-group col-md-6">
            <label for="sector">Expenditure Sector</label>
            <select name="sectorId" id="sector" class="form-control">
                <option selected disabled>Choose one</option>
                <option v-for="(expenditure, index) in expenditures" :value="expenditure.id" :key="index">{{ expenditure.name }}</option>
            </select>
        </div>
    </div>
</template>
<script>
    export default {
        name: 'BusienessExpenditureReportComponent',
        props: ['businesses'],
        data(){
            return{
                expenditures: [],
                report:{
                    business:{
                        id: null,
                        expenditure: null,
                    }
                }
            }
        },
        methods:{
            getExpenditure(id){
                this.$awn.asyncBlock(
                    axios.post(baseURL + 'get-business-expenditure', {
                        id : id
                    }),
                    response => {
                        this.expenditures = response.data;
                        console.log(response.data);
                    },
                    reason => {
                        console.log(reason);
                    })
            }
        },
        mounted() {
            console.log(this.businesses)
        }
    }
</script>
