<template>
    <div class="pad">
        <button class="btn" type="button" data-toggle="modal" data-target=".customer-modal">{{ customer.name || 'Customer' }}</button>

        <!-- Customer Modal -->
        <div class="modal fade customer-modal" ref="modal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="mb-0">Choose a customer</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&#10005;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <!-- Customer list -->
                        <div class="row">
                            <div class="col-lg-6">

                                <!-- Customer search -->
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" v-model="searchCustomer" @input="searchCustomerFilter" placeholder="Phone number">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-info" type="button" title="search" @click.prevent="searchCustomerFilter"><i class="fa fa-search"></i></button>
                                    </div>
                                </div>
                                <!-- End of the customer search -->

                                <ul class="customer-list">
                                    <li class="text-center" v-if="!filteredCustomers.length">
                                        No Customer found
                                    </li>
                                    <li v-for="(_customer, index) in filteredCustomers">
                                        <label :class="{active: customerIndex === index}">
                                            <input type="radio" v-model="customerIndex" :value="index">
                                            <div class="image">
                                                <img :src="baseURL + ((_customer.media) ? _customer.media.real_path : 'public/images/avatars/user.png')" class="img-fluid" :alt="_customer.name">
                                            </div>
                                            <div class="details">
                                                <h5>{{ _customer.name }}</h5>
                                                <small>{{ _customer.phone || 'No phone number' }} | BDT {{ Math.abs(_customer.balance) }} {{ (_customer.balance < 0) ? 'Receivable' : 'Payable' }}</small>
                                            </div>
                                        </label>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-6 border-right">
                                <div class="add-customer">
                                    <h5 class="mb-3">New customer</h5>
                                    <form @submit.prevent="addNewCustomer">
                                        <div class="form-group required">
                                            <label for="name">Full name</label>
                                            <input type="text" id="name" v-model="newCustomer.name" class="form-control" placeholder="Ex. MaxSOP">
                                        </div>
                                        <div class="form-group">
                                            <label for="phone">Phone number</label>
                                            <input type="tel" id="phone" v-model="newCustomer.phone" class="form-control" placeholder="Ex. 01786494650">
                                        </div>
                                        <div class="form-group">
                                            <label for="address">Address</label>
                                            <textarea id="address" v-model="newCustomer.address" class="form-control" placeholder="Ex. 5B Green House, 27/2 Ram Babu Road, Mymensingh, Bangladesh"></textarea>
                                        </div>
                                        <!--<div class="form-group">
                                            <label for="email">Email address</label>
                                            <input type="email" id="email" v-model="newCustomer.email" class="form-control"
                                                   placeholder="Email address">
                                        </div>-->
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-success btn-block">Save</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- End of the customer list -->

                    </div>
                    <div class="modal-footer">
                        <div class="btn-wrapper">
                            <button type="button" class="btn btn-danger mr-2" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary" @click.prevent="selectCustomer">Select</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of the Customer modal -->

    </div>
</template>

<script>
    export default {
        name: "CustomerComponent",
        watch: {
            customers: {
                handler: function (val, oldVal) {
                    this.filteredCustomers = this.customers
                },
                immediate: true,
                deep: true
            }
        },
        data(){
            return {
                customerModal: {},
                customers: [],
                filteredCustomers: [],
                customerIndex: null,
                customer: {},
                searchCustomer: '',
                newCustomer: {
                    name: null,
                    address: null,
                    phone: null,
                    email: null,
                }
            }
        },
        methods: {
            searchCustomerFilter(){
                if (this.searchCustomer.length === 0){
                    this.filteredCustomers = this.customers
                }else{
                    this.filteredCustomers = this.customers.filter(customer => {
                        return customer.phone != null && customer.phone.includes(this.searchCustomer)
                    })
                }
            },
            selectCustomer(){
                if(this.customerIndex === null){
                    alert('Select Customer')
                    return;
                }
                this.customer = this.customers[this.customerIndex]
                this.hideModal()
            },
            loadCustomers(){
                return new Promise((resolve, reject) => {
                    axios.post(baseURL + 'get-all-active-customers')
                        .then(response => {
                            this.customers = response.data
                            resolve()
                        })
                        .catch(error => {
                            console.log(error)
                            reject()
                        })
                })
            },
            addNewCustomer(){
                this.$awn.asyncBlock(
                    axios.post(baseURL + 'create-new-customer', this.newCustomer),
                    response => {
                        this.resetNewCustomerField()
                        this.loadCustomers()
                    },
                    reason => console.log(reason)
                )
            },
            resetNewCustomerField(){ // reset new customer fields
                this.newCustomer = {
                    name: null,
                    address: null,
                    phone: null,
                    email: null,
                }
            },
            showModal(){
                this.customerModal.modal('show')
            },
            hideModal(){
                this.customerModal.modal('hide')
            },
            initMethods(){
                this.loadCustomers()
            }
        },
        mounted() {
            this.customerModal = $(this.$refs.modal)
            this.initMethods()
        }
    }
</script>

<style scoped>

</style>
