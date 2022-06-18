<template>
    <div class="col-md-12">
        <form @submit.prevent="confirmOrder">
            <table class="table table-borderless table-sm">
                <thead>
                    <tr class="border-top border-bottom border-dark">
                        <th scope="col">#</th>
                        <th scope="col">Product Name</th>
                        <th scope="col" style="width: 40%">Quantity</th>
                        <th scope="col">Line Total</th>
                        <th scope="col" class="text-right">Warehouse</th>
                    </tr>
                </thead>
                <tbody>
                <tr v-for="(details, detailsIndex) in order.order_management_details" :key="detailsIndex">
                    <th scope="row">{{ detailsIndex+1 }}</th>
                    <td>
                        {{ details.products.name  }}
                    </td>
                    
                    <td>
                        {{ details.order_total_quantities_in_unit.display }}
                    </td>

                    <td>
                        {{ (Number.parseFloat(details.quantity * details.products.wholesale_price).toFixed(2))  }}
                    </td>

                    <td>
                        <label :for="`select-warehouse-${detailsIndex}`"></label>
                        <div v-if="!details.warehouse_id">
                            <select 
                                class="form-control" 
                                :id="`select-warehouse-${detailsIndex}`"
                                @change="getWarehouseIdFromProduct(details.products.id, $event.target.value, details.quantity)"
                                required 
                                >
                                <option :value="null" selected disabled>Choose Warehouse</option>
                                <option 
                                    v-for="(warehouse, warehouseIndex) in details.products.warehouses" 
                                    :value="warehouse.id" 
                                    :key="warehouseIndex">
                                    {{ warehouse.title }} 
                                    <small>({{ warehouse.product_quantity_in_unit.display }})</small>
                                </option>
                            </select>
                        </div>
                        <div v-else>
                            {{ details.warehouses.title }}
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="4">Delivery Man</td>
                    <td class="text-right">
                        <div v-if="!order.status">
                            <select class="form-control" v-model="delivery_man_id">
                                <option :value="null" selected disabled>Choose One</option>
                                <option v-for="(user, userIndex) in users" :value="user.id" :key="userIndex">{{ user.name }}</option>
                            </select>
                        </div>
                        <div v-else>
                            {{ order.delivery_man.name }}
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="3"></td>
                    <td class="text-right">Subtotal:</td>
                    <td class="text-right border-bottom border-dark" style="border-style: dashed !important;">
                        {{ parseFloat(total_price).toFixed(2) }}
                    </td>
                </tr>
                <tr>
                    <td colspan="3"></td>
                    <td class="text-right">Discount:</td>
                    <td v-if="!order.discount" class="text-right border-bottom border-dark" style="border-style: dashed !important;">
                    <input type="number" v-model="discount" class="form-control form-control-sm text-right" placeholder="0.00"></td>
                    <td v-else class="text-right border-bottom border-dark">{{ order.discount }}</td>
                </tr>
                <tr>
                    <td colspan="3"></td>
                    <td class="text-right">Total:</td>
                    <td class="text-right border-bottom border-dark" style="border-style: dashed !important;">{{ parseFloat(total).toFixed(2) }}</td>
                </tr>
                
                <tr v-if="order.status == false">
                    <td colspan="4"></td>
                    <td class="text-right">
                        <button type="submit" class="btn btn-success">Confirm Order</button>
                    </td>
                </tr>
                </tbody>
            </table>
        </form>
    </div>
</template>

<script>
    export default {
        name: "OrderComfirmComponent",
        // props: ['order', 'total_price'],
        props: {
            order: {
                type: Object,
                required: true
            },
            users: {
                type: Array,
                required: true
            },
            total_price: {
                type: Number,
                required: true
            }
        },
        computed: {
            total(){
                if (this.discount != null){
                    return this.total_price - parseFloat(this.discount)
                }
                // console.log(total_price)
                return this.total_price
            },
        },
        data(){
            return{
                productWarehouses: {},
                discount: null,
                delivery_man_id: null,
            }
        },
        methods: {
            confirmOrder(){
                // if (this.productWarehouses.length < 0) {
                //     return alert('Please Select warehouse')
                // }
                if (!this.delivery_man_id) {
                    return alert("Please select delivery man")
                }
                this.$awn.asyncBlock(
                    axios.post(baseURL + 'user/orderManagement', {
                        order_id: this.order.id,
                        productWarehouses: this.productWarehouses,
                        delivery_man_id: this.delivery_man_id,
                        discount: this.discount,
                        subtotal: this.total_price,
                    }),
                    response => {
                        window.location.href =
                        baseURL +
                        "user/invoice-generate/" +
                        response.data.invoice_no;
                        // console.log(response.data);
                    },
                    reason => console.log(reason)
                )
            },

            getWarehouseIdFromProduct(productId, warehouseId, quantity){
                this.$set(this.productWarehouses, productId, warehouseId, quantity)
            },
        },
        mounted() {
            console.log(this.order)
        }
    }
</script>