<template>
    <div class="pos">
        <div class="row">
            <div class="col-12">
                <div class="container-fluid">
                    <div class="text-center">
                        <h6><strong>Customer Name:</strong> {{ preOrder.customer.name }}</h6>
                        <h6><strong>Order Number:</strong> {{ preOrder.order_no }}</h6>
                        <h6><strong>Order Date:</strong> {{ preOrder.formatted_date }}</h6>
                        <h6><strong>Order Total:</strong> {{ Number.parseFloat(preOrder.pre_order_grand_total).toFixed(2) }}</h6>
                    </div>
                    <form action="" @submit.prevent="deliver">
                        <!-- Top Form Start-->
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <input
                                    type="date"
                                    class="form-control form-control-sm"
                                    v-model="date"
                                />
                            </div>

<!--                            <div class="col-md-6">
                                <select class="form-control form-control-sm" v-model="warehouseId">
                                    <option :value="null" disabled>Select warehouse</option>
                                    <option
                                        v-for="(warehouse,
                                        warehouseIndex) in warehouses"
                                        :value="warehouse.id"
                                        :key="warehouseIndex"
                                        v-text="warehouse.title"
                                    />
                                </select>
                            </div>-->
                        </div>
                        <!-- Top Form End -->

                        <!-- Table Form Start -->
                        <div class="row">
                            <div class="my-2 col-12 border-top border-bottom">
                                <table class="table my-2 table-striped table-bordered table-sm">
                                    <thead>
                                        <tr>
                                            <th class="text-center">SL</th>
                                            <th>Product Name</th>
                                            <th class="text-right">Sale Quantity</th>
                                            <th class="text-right">Delivery Quantity</th>
                                            <th class="text-center">Quantity</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    <tr v-for="(details, index) in preOrder.pre_order_details" :key="index">
                                        <td class="text-right">{{ index + 1 }}.</td>
                                        <td>{{ details.product.name }}</td>

                                        <td class="text-right">
                                            {{ upperUnit(details.quantity, details.product.unit) }}
                                        </td>

                                        <td class="text-right">
                                            {{ upperUnit(details.delivery_quantity, details.product.unit) }}
                                        </td>

                                        <td>
                                            <!-- Quantity -->
                                            <div class="input-group">
                                                <input
                                                    type="number"
                                                    aria-describedby="quantityError"
                                                    v-for="(label,
                                                        labelIndex) in details.product.product_unit_labels"
                                                    :placeholder="label"
                                                    :value="details.delivery_quantity_in_unit[labelIndex]"
                                                    :key="labelIndex"
                                                    @blur="
                                                        addQuantity(
                                                                $event,
                                                                details.id,
                                                                labelIndex
                                                            )"
                                                    @change="
                                                            addQuantity(
                                                                $event,
                                                                details.id,
                                                                labelIndex
                                                            )"
                                                    @keyup="
                                                            addQuantity(
                                                                $event,
                                                                details.id,
                                                                labelIndex
                                                            )"
                                                    min="0"
                                                    class="form-control form-control-sm"
                                                />
                                            </div>
                                            <div v-if="details.error" id="quantityError" class="form-text text-danger">
                                                {{ details.error }}
                                            </div>
                                        </td>

                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- Table Form End -->

                        <div class="row">
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

                            <div class="text-right col-12">
                                <button :disabled="disableDeliver" class="btn btn-sm btn-primary">
                                    Deliver
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import lowestConverter from '../reusable/ConvertToLowestUnit'
import upperConverter from '../reusable/ConvertToUpperUnit'
export default {
    name: "PreOrderDeliveryComponent",
    props: {
        preOrder: Object,
        warehouses: Array,
    },
    data() {
        return{
            orderDetails: [],
            sms: false,
            date: new Date().toISOString().substr(0, 10),
            warehouseId: null,
            disableDeliver: false,
        }
    },
    methods: {
        deliver() {
            // if(!this.warehouseId) {
            //     this.$awn.alert('Please select warehouse!')
            //     return;
            // }
            let quantityError = false;
            this.orderDetails.forEach(details => {
                let total_delivery_quantity = (parseFloat(details.delivery_quantity) + parseFloat(details.new_delivery_quantity))
                if(total_delivery_quantity > parseFloat(details.quantity)) {
                    quantityError = true
                    details.error = "Delivery quantity can\'t be greater than sale quantity"
                }else {
                    quantityError = false
                    details.error = ''
                }
            })
            if (quantityError){
                return;
            }
            // this.disableDeliver = true
            this.$awn.asyncBlock(
                axios.put(baseURL + "user/pre-order-process/" + this.preOrder.id, {
                    order_details: this.orderDetails,
                    date: this.date,
                    sms: this.sms,
                    // warehouse_id: this.warehouseId,
                }),
                response => {
                    window.location.href =
                        baseURL +
                        "user/preOrder/" +
                        response.data.id;
                    console.log(response.data)
                },
                reason => {
                    console.log(reason)
                }
            );
        },
        upperUnit(quantity, unit){
            return upperConverter(quantity, unit)
        },

        addQuantity(event, detailsId, order) {
            let details = this.orderDetails.find(details => details.id === detailsId)
            this.$set(details.delivery_quantity_in_unit, order, parseFloat(event.target.value));
            //
            details.delivery_quantity_in_unit[order] = event.target.value ? event.target.value : null
            details.new_delivery_quantity = lowestConverter(details.delivery_quantity_in_unit, details.product.unit)

        },
    },
    mounted() {
        this.orderDetails = this.preOrder.pre_order_details;
    }
}
</script>

<style scoped>

</style>
