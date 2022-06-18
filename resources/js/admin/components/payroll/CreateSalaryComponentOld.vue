<template>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-item-center">
            <h5>Pay Salary</h5>
            <a :href="listURL" class="btn btn-primary" title="Salary list">
                <i class="fa fa-list" aria-hidden="true"></i>
            </a>
        </div>

        <div class="card-body">
            <form method="POST">
                <div class="form-row">
                    <div class="form-group col-md-6 required">
                        <label for="user_id">Name</label>

                        <select id="user_id" v-model="user.id" class="form-control" @change="getAdvancedSalary(user.id)">
                            <option value="" selected disabled>Choose one</option>
                            <option v-for="(user, index) in records.users" :value="user.id" :key="index">{{ user.name }}</option>
                        </select>
                    </div>

                    <div class="form-group col-md-6 required">
                        <label for="salary_of">Salary of</label>
                        <input type="month" class="form-control" id="salary_of" name="salary_of" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6 required">
                        <label for="given_date">Given Date</label>
                        <input type="date" class="form-control" id="given_date" name="given_date" required>
                    </div>

                    <div class="form-group col-md-6 required">
                        <label for="basic_salary">Basic Salary</label>
                        <input type="text" v-model="basic_salary" id="basic_salary" class="form-control" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="inst_amount">Installment</label>
                        <input type="number" class="form-control" v-model="installments" id="inst_amount">
                    </div>

                    <div class="form-group col-md-6 required">
                        <div class="row">
                            <div class="col-md-12">
                                <label for="type">Allowance or Deductions</label>
                                <div v-if="allowances.length > 0">
                                    <div class="row mb-2" v-for="(allowance, allowaneIndex) in allowances" :key="allowaneIndex">
                                        <div class="col-md-4 mb-1">
                                            <select id="" class="form-control" @change="addToItem($event, allowaneIndex)">
                                                <option v-model="test" v-for="(_allowance, _allowanceIndex) in records.allowance"
                                                        :key="_allowanceIndex" :value="_allowanceIndex">{{
                                                    _allowance.title }}
                                                </option>
                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <input type="text" class="form-control">
                                        </div>

                                        <div class="col-md-4">
                                            <button class="btn btn-primary" type="button" @click="pushObject">+</button>
                                            <button class="btn btn-danger" type="button" @click="removeObject(allowaneIndex)">-</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-2" v-else>
                                    <div class="col-md-12">
                                        <button class="btn btn-primary" type="button" @click="pushObject">+</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-right">
                    <button type="reset" class="btn btn-danger">Reset</button>
                    <button type="submit" class="btn btn-primary">Pay Salary</button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'CreateSalaryComponent',
        props: ['records'],
        computed: {
            listURL() {
                return baseURL + 'admin/salary'
                console.log(baseURL)
            },
        },
        data(){
            return {
                user: {
                    id: null,
                },
                allowances: [],
                test: {},
                basic_salary: null,
                installments: null,
            }
        },
        methods: {
            getAdvancedSalary(id){
                this.$awn.asyncBlock(
                    axios.post(baseURL + 'get-salary-details', {
                        id: id
                    }),
                    response => {
                        // set data
                        if (response.data.advanced_salaries.length > 0){
                            this.installments = response.data.advanced_salaries[0].installment_amount;
                        }else{
                            this.installments = Number.parseFloat(0).toFixed(2)
                        }
                        this.basic_salary = response.data.metas.find(_meta => _meta['meta_key'] == 'basic_salary').meta_value;

                        // console
                        console.log(response.data.metas);
                    },
                    reason => {
                        console.log(reason)
                    })
            },
            pushObject(){
                this.allowances.push({});
            },
            removeObject(index){
                console.log(index)
                this.allowances.splice(index, 1);
            },
            addToItem(index, value){
                this.$set(this.test, index, value)
            }
        },
        mounted(){
            console.log(this.listURL)
            console.log(this.records)
        },

    }
</script>
