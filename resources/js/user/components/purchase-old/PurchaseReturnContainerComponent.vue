<template>
    <div class="col-md-12">
        <h5 class="text-center">Return Items</h5>
        <table class="table table-borderless table-sm">
            <thead>
            <tr class="border-top border-bottom border-dark">
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Price</th>
                <th scope="col" style="width: 40%">Quantity</th>
                <th scope="col" class="text-right">Line Total</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(_purchaseDetails, purchaseDetailsIndex) in purchase.details" :key="purchaseDetailsIndex">
                <th scope="row">{{ purchaseDetailsIndex +1 }}</th>
                <td>
                    {{ _purchaseDetails.product.name  }}
                    <small class="small d-block">{{ _purchaseDetails.purchase_total_quantities_in_unit['display']}} </small>
                </td>
                <td>
                    <div class="input-group input-group-sm">
                        <input type="number" class="form-control" :value="_purchaseDetails.purchase_price" placeholder="Enter return price" autocomplete="off" disabled>
                        <div class="input-group-append">
                            <span class="input-group-text">{{ _purchaseDetails.product.product_unit_labels[_purchaseDetails.product.product_unit_labels.length - 1] }}</span>
                        </div>
                    </div>
                </td>
                <td>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item bg-transparent" v-for="(_warehouse, warehouseIndex) in _purchaseDetails.quantities" :key="warehouseIndex">
                            <p class="m-0">{{ _warehouse.title }}</p>
                            <p class="m-0">{{ _warehouse.purchase_product_rest_quantity_warehouse_wise_in_unit['display'] || 'No quantity for return' }}</p>
                            <div class="input-group input-group-sm m-2" v-for="(_label, labelIndex) in _purchaseDetails.product.product_unit_labels" :key="labelIndex">
                                <input type="number" class="form-control form-control-sm"
                                       @keyup="addQuantity($event,_purchaseDetails.product.id, _warehouse.id, labelIndex)"
                                       @change="addQuantity($event,_purchaseDetails.product.id, _warehouse.id, labelIndex)"
                                       min="0"
                                       :placeholder="'Enter ' + _label"
                                >
                                <div class="input-group-append">
                                    <span class="input-group-text" style="min-width: 70px">{{ _label }}</span>
                                </div>
                            </div>
                            <div class="small text-danger" v-if="errors[purchase.id] && errors[purchase.id][_purchaseDetails.product.id] && errors[purchase.id][_purchaseDetails.product.id][_warehouse.id]">
                                Error: {{ errors[purchase.id][_purchaseDetails.product.id][_warehouse.id] }}
                            </div>
                            <div class="text-right">
                                <button type="button" @click="calculateLineTotal" class="btn btn-sm btn-outline-info"><i class="fa fa-calculator"></i> Calculate</button>
                            </div>
                        </li>
                    </ul>
                </td>
                <td class="text-right">
                    {{ parseFloat(lineTotals[_purchaseDetails.product.id] || 0).toFixed(2) }}
                </td>
            </tr>
            <tr>
                <td colspan="3"></td>
                <td class="text-right">Subtotal:</td>
                <td class="text-right border-bottom border-dark" style="border-style: dashed !important;">{{ parseFloat(subtotal).toFixed(2) }}</td>
            </tr>
            <tr>
                <td colspan="3"></td>
                <td class="text-right">Adjustment:</td>
                <td class="text-right border-bottom border-dark" style="border-style: dashed !important;">
                    <input type="number" v-model="adjustment" class="form-control form-control-sm text-right" placeholder="0.00"></td>
            </tr>
            <tr>
                <td colspan="3"></td>
                <td class="text-right">Total:</td>
                <td class="text-right border-bottom border-dark" style="border-style: dashed !important;">{{ parseFloat(total).toFixed(2) }}</td>
            </tr>
            <tr>
                <td colspan="2" class="text-right">
                    <label for="note">Note:</label>
                </td>
                <td colspan="3" class="text-right">
                    <textarea id="note" v-model="note" class="form-control form-control-sm" rows="5" placeholder="Enter return note"></textarea>
                </td>
            </tr>
            <tr>
                <td colspan="4"></td>
                <td class="text-right">
                    <button type="button" @click="proceedReturn" class="btn btn-success"><i class="fa fa-money"></i> Submit</button>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        name: "PurchaseReturnContainerComponent",
        props: {
            purchase: {
                type: Object,
                required: true
            }
        },
        computed: {
            subtotal(){
                let subtotal = 0
                for (let key in this.lineTotals) {
                    subtotal += this.lineTotals[key]
                }
                return subtotal
            },
            total(){
                if (this.adjustment){
                    return this.subtotal + parseFloat(this.adjustment)
                }

                return this.subtotal
            },
        },
        data(){
            return {
                returnQuantities: {
                    quantities: {}
                },
                lineTotals: {},
                note: null,
                adjustment: null,
                processing: false,
                errors: {}
            }
        },
        methods: {
            resetComponent(){
                this.errors = {}
            },
            addQuantity(event, productId, warehouseId, order){ // add to quantity
                if (!(productId in this.returnQuantities.quantities)) {
                    this.$set(this.returnQuantities.quantities, productId, {})
                }
                if (!(warehouseId in this.returnQuantities.quantities[productId])) {
                    this.$set(this.returnQuantities.quantities[productId], warehouseId, {})
                }
                this.$set(this.returnQuantities.quantities[productId][warehouseId], order, event.target.value )
            },
            calculateLineTotal(){
                this.resetComponent()
                this.$awn.asyncBlock(
                    axios.post(baseURL + 'user/calculate-purchase-return-line-total', {
                        purchase_id: this.purchase.id,
                        quantities: this.returnQuantities.quantities
                    }),
                    response => {
                        //console.log(response.data)
                        this.lineTotals = response.data
                    },
                    reason =>  {
                        //console.log(reason.response.data)
                        this.errors = reason.response.data
                    }
                )
            },
            proceedReturn(){
                //subtotal
                if(!this.subtotal){
                    alert('Not enough quantity')
                    return;
                }

                if (!confirm('Click ok to proceed')) return ;

                this.processing = true;

                this.resetComponent()

                this.$awn.asyncBlock(
                    axios.post(baseURL + 'user/purchase-return', {
                        purchase_id: this.purchase.id,
                        quantities: this.returnQuantities.quantities,
                        adjustment: this.adjustment,
                        note: this.note
                    }),
                    response => {
                        location.href = baseURL + 'user/purchase/' + this.purchase.id
                        //console.log(response.data)
                        this.processing = false;
                    },
                    reason => {
                        this.errors = reason.response.data
                        this.processing = false;
                    }
                )
            }
        },
        mounted() {
            console.log(this.purchase)
        }
    }
</script>

<style scoped>

</style>
