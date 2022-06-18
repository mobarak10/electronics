<template>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-item-center">
            <h5>Pay Salary</h5>
            <a :href="listURL" class="btn btn-primary" title="Salary list">
                <i class="fa fa-list" aria-hidden="true"></i>
            </a>
        </div>

        <div class="card-body">
            <form method="post" @submit.prevent="paySalary">
                <div class="form-row">
                    <div class="form-group col-md-12 required">
                        <label for="user_id">Name</label>
                        <select id="user_id" v-model="user.id" class="form-control" @change="getAdvancedSalary(user.id)">
                            <option value="" selected disabled>Choose one</option>
                            <option v-for="(user, index) in records.users" :value="user.id" :key="index">{{ user.name }}</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6 required">
                        <label for="salary_of_month_year">Salary of</label>
                        <input type="month" class="form-control" id="salary_of_month_year" v-model="salary_of_month_year">
                    </div>

                    <div class="form-group col-md-6 required">
                        <label for="given_date">Given Date</label>
                        <input type="date" class="form-control" id="given_date" v-model="given_date">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6 required">
                        <label for="basic_salary">Basic Salary</label>
                        <input type="text" placeholder="value must be 0.00 or above" v-model="basic_salary" id="basic_salary" class="form-control" required>
                    </div>

                    <div class="form-group col-md-6 required">
                        <label for="home_allowance">Home Allowance</label>
                        <input type="text" placeholder="value must be 0.00 or above" v-model="home_allowance" id="home_allowance" class="form-control" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6 required">
                        <label for="transport_allowance">Home Allowance</label>
                        <input type="text" placeholder="value must be 0.00 or above" v-model="transport_allowance" id="transport_allowance" class="form-control" required>
                    </div>

                    <div class="form-group col-md-6 required">
                        <label for="medical_allowance">Medical Allowance</label>
                        <input type="number" placeholder="value must be 0.00 or above" class="form-control" v-model="medical_allowance" id="medical_allowance" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6 required">
                        <label for="installment">Installment</label>
                        <input type="number" placeholder="value must be 0.00 or above" class="form-control" v-model="installments" id="installment" required>
                    </div>

                    <div class="form-group col-md-6 required">
                        <label for="deductions">Deductions</label>
                        <input type="number" placeholder="value must be 0.00 or above" class="form-control" v-model="deductions" id="deductions" required>
                    </div>

                    <!-- balance for supplier/customer start -->
                    <div class="col-md-12">
                        <p class="d-block bg-dark text-light p-1 px-2" v-if="advanced > 0">
                            Installment Due BDT {{ installmentDue }}
                        </p>
                    </div>
                    <!-- balance for supplier/customer end -->
                </div>

                <div class="col-md-6">
                    <!-- Send SMS Start -->
                    <div class="form-group row">
                        <div class="col-10">
                            <div class="custom-control custom-checkbox">
                                <input
                                    type="checkbox"
                                    v-model="sms"
                                    class="custom-control-input"
                                    id="client-send-sms"
                                />
                                <label
                                    class="custom-control-label"
                                    for="client-send-sms">
                                    Send SMS
                                </label>
                            </div>
                        </div>
                    </div>
                    <!-- Send SMS End -->
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
        props: ['records', 'selectedUser'],
        computed: {
            listURL() {
                return baseURL + 'admin/salary'
                // console.log(baseURL)
            },
        },
        data(){
            return {
                errors: {},
                user: {
                    id: null,
                },
                sms: false,
                given_date: null,
                salary_of_month_year: null,
                basic_salary: null,
                home_allowance: null,
                medical_allowance: null,
                transport_allowance: null,
                advanced: null,
                installments: null,
                installmentDue: null,
                totalPayInstallment: null,
                deductions: Number.parseFloat(0).toFixed(2),
            }
        },
        methods: {
            getAdvancedSalary(id){
                this.$awn.asyncBlock(
                    axios.post(baseURL + 'get-salary-details', {
                        id: id
                    }),
                    response => {
                        // console.log(response.data)
                        // set data
                        if (response.data.advanced_salaries){
                            // console.log(response.data)

                            // get all installment amount array
                            const totalInstallment = (response.data.advanced_salaries.advanced_salary_details.map(installments => installments.installment_amount));

                            // sum all installment amount
                            if (totalInstallment != null) {
                                this.installments = Number.parseFloat(totalInstallment.reduce(function(a, b){
                                        return parseInt(a) + parseInt(b);
                                    })).toFixed(2);
                            }else{
                                this.installments = Number.parseFloat(0).toFixed(2);
                            }

                            // get all advanced array
                            const totalAdvanced = (response.data.advanced_salaries.advanced_salary_details.map(installments => installments.adv_amount));
                            // console.log(totalAdvanced);

                            // sum all advanced
                            if (totalAdvanced != null) {
                                this.advanced = totalAdvanced.reduce(function(a, b){
                                    return parseInt(a) + parseInt(b);
                                });
                            }else{
                                this.advanced = Number.parseFloat(0).toFixed(2);
                            }

                            // get advanced paid amount
                            this.totalPayInstallment = response.data.advanced_salaries.total_installment_paid;

                            // get total due installment
                            this.installmentDue = Number.parseFloat(this.advanced - this.totalPayInstallment).toFixed(2);

                        }else{
                            this.installments = Number.parseFloat(0).toFixed(2)
                            this.advanced = Number.parseFloat(0).toFixed(2)
                        }
                        this.basic_salary = response.data.metas.find(_meta => _meta['meta_key'] == 'basic_salary').meta_value;
                        this.home_allowance = response.data.metas.find(_meta => _meta['meta_key'] == 'home_allowance').meta_value;
                        this.medical_allowance = response.data.metas.find(_meta => _meta['meta_key'] == 'medical_allowance').meta_value;
                        this.transport_allowance = response.data.metas.find(_meta => _meta['meta_key'] == 'transport_allowance').meta_value;

                    },
                    reason => {
                        console.log(reason)
                    })
            },

            paySalary(){
                // check validation
                if (this.installments > (this.advanced-this.totalPayInstallment)){
                    alert('Installment amount must be less or equal '+(this.advanced-this.totalPayInstallment))
                    return;
                }

                // check validation
                if (!this.salary_of_month_year) {
                    alert('Please select month')
                    return;
                }

                // check validation
                if (!this.given_date) {
                    alert('Please select given date')
                    return;
                }

                // save salary
                this.$awn.asyncBlock(
                    axios.post(baseURL + 'admin/salary', {
                        user_id: this.user.id,
                        salary_of_month_year: this.salary_of_month_year,
                        given_date: this.given_date,
                        basic_salary: this.basic_salary,
                        sms: this.sms,
                        home_allowance: this.home_allowance,
                        medical_allowance: this.medical_allowance,
                        transport_allowance: this.transport_allowance,
                        installments: this.installments,
                        deductions: this.deductions,
                    }),
                    response => {
                        // console
                        console.log(response.data);
                        window.location.href = baseURL + 'admin/salaryView/' + response.data

                    },
                    error => {
                        if (error.response.status === 422) { // validation error
                            this.errors = error.response.data.errors
                            this.$awn.alert('Salary allready given')
                        } else {
                            this.$awn.alert('Opps! something went wrong. Try again later')
                        }
                    }
                )
            }
        },
        mounted(){
            console.log(this.listURL)
            console.log(this.records)
        },

    }
</script>
