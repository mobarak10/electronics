<template>
    <div class="pad">
        <button class="btn btn-primary" type="button" :data-toggle="[modalEnable ? 'modal' : '']" data-target=".cash-modal">{{ cash.title || 'Select Cash' }}</button>

        <!-- Cash Modal -->
        <div class="modal fade cash-modal" ref="modal">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <form @submit.prevent="submitForm">
                        <div class="modal-header">
                            <h5 class="mb-0">Cash</h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span>&#10005;</span>
                            </button>
                        </div>
                        <div class="modal-body">

                            <!-- Cash -->
                            <div class="cash">
                                <div class="row">
                                    <div class="col-sm-4 mb-3" v-for="(_cash, index) in cashes" :key="index">
                                        <label :for="'cash-' + index" class="cash-single">
                                            <input
                                                type="radio"
                                                v-model="cashIndex"
                                                :value="index"
                                                :id="'cash-' + index"
                                            >
                                            <div>{{ _cash.title }}</div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <!-- End of the cash -->

                        </div>
                        <div class="modal-footer">
                            <div class="btn-wrapper">
                                <button type="button" class="btn mr-2" data-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn">Select</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- End of the Cash modal -->

    </div>
</template>

<script>
    export default {
        name: "CashComponent",
        data(){
            return {
                cashes: [],
                cashIndex: null,
                cashModal: {},
                cash: {},
                modalEnable: false
            }
        },
        methods: {
            showModal(){
                this.cashModal.modal('show')
            },
            hideModal(){
                this.cashModal.modal('hide')
            },
            loadCashes(){ // load cashes
                axios.post(baseURL + 'get-all-cashes')
                    .then(response => {
                        //let cash = [{"id":1,"title":"Main","slug":"main","amount":"492700.00","created_at":"2019-09-25 16:15:44","updated_at":"2019-09-28 19:34:41"}];
                        if(response.data.length === 1){
                            this.selectCash(response.data)
                        }else{
                            this.cashes = response.data
                            this.modalEnable = true
                        }
                    })
                    .catch(reason => console.log(reason))
            },
            selectCash(data){
                this.modalEnable = false
                this.cash = data[0]
            },
            submitForm(){
                this.cash = this.cashes[this.cashIndex]
                this.hideModal()
            },
            initMethods(){ // init method
                this.loadCashes(); //load cashes
            }
        },
        mounted(){
            this.cashModal = $(this.$refs.modal)
            this.initMethods() // init all methods
        }
    }
</script>
