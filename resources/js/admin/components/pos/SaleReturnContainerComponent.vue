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
            <tr v-for="(_saleDetail, saleDetailIndex) in sale.saleDetails" :key="saleDetailIndex">
                <th scope="row">{{ saleDetailIndex + 1 }}</th>
                <td>
                     {{ _saleDetail.product.name }}<br>
                    <span class="small">{{ _saleDetail.total_product_rest_quantities_in_unit['display'] || 'No quantity for return' }}</span>
                </td>
                <td>
                    <div class="input-group input-group-sm">
                        <input type="number" class="form-control" :value="_saleDetail.sale_price"  placeholder="Enter return price" autocomplete="off" disabled>
                        <div class="input-group-append">
                            <span class="input-group-text">{{ _saleDetail.product.product_unit_labels[_saleDetail.product.product_unit_labels.length - 1] }}</span>
                        </div>
                    </div>
                </td>
                <td>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item bg-transparent" v-for="(_warehouse, warehouseIndex) in _saleDetail.quantities" :key="warehouseIndex">
                            <p class="m-0">{{ _warehouse.title }}</p>
                            <p class="m-0">{{ _warehouse.sale_return_product_rest_quantity_warehouse_wise_in_unit.display || 'No quantity for return' }}</p>
                            <div class="input-group input-group-sm m-2" v-for="(_label, labelIndex) in _saleDetail.product.product_unit_labels" :key="labelIndex">
                                <input type="number" class="form-control form-control-sm"
                                       @keyup="addQuantity($event,_saleDetail.product.id, _warehouse.id, labelIndex)"
                                       @change="addQuantity($event,_saleDetail.product.id, _warehouse.id, labelIndex)"
                                       :placeholder="'Enter ' + _label"
                                       min="0"
                                       :disabled="! (_warehouse.sale_product_rest_quantity)"
                                >
                                <div class="input-group-append">
                                    <span class="input-group-text" style="min-width: 70px">{{ _label }}</span>
                                </div>
                            </div>
                            <div class="small text-danger" v-if="errors[sale.id] && errors[sale.id][_saleDetail.product.id] && errors[sale.id][_saleDetail.product.id][_warehouse.id]">
                                Error: {{ errors[sale.id][_saleDetail.product.id][_warehouse.id] }}
                            </div>
                            <div class="text-right">
                                <button type="button" @click="calculateLineTotal" :disabled="! (_warehouse.sale_product_rest_quantity)" class="btn btn-sm btn-outline-info"><i class="fa fa-calculator"></i> Calculate</button>
                            </div>
                        </li>
                    </ul>
                </td>
                <td class="text-right">
                    {{ parseFloat((lineTotals[_saleDetail.product_id] || 0)).toFixed(2) }}
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
                <td colspan="3"></td>
                <td class="text-right">
                    <label for="select-cash">Pay From:</label>
                </td>
                <td class="text-right">
                    <select v-model="cashId" @change="getCurrentBalance" class="form-control form-control-sm" id="select-cash">
                        <option :value="null" disabled>Select Cash</option>
                        <option v-for="(_cash, cashIndex) in extras.cashes" :value="_cash.id" :key="cashIndex">{{ _cash.title }}</option>
                    </select>
                    <div class="bg-dark text-white mt-1" v-if="availableBalance !== null">
                      Balance: BDT {{ availableBalance }}
                    </div>
                </td>
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
                    <button type="button" @click="returnProceed" class="btn btn-success" :disabled="pay"><i class="fa fa-money"></i> Pay</button>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        name: "SaleReturnContainerComponent",
        props: {
            sale: {
                required: true,
                type: Object
            },
            extras: {
                required: true,
                type: Object
            }
        },
        computed: {
            subtotal() {
                let subtotal = 0
                for (let productId in this.lineTotals){
                    subtotal += this.lineTotals[productId]
                }
                return subtotal;
            },
            total(){
                if (this.adjustment){
                    return this.subtotal + parseFloat(this.adjustment)
                }

                return this.subtotal
            },
            pay(){
                return this.processing || this.subtotal <= 0
            }
        },
        data(){
            return {
                returnQuantities: {
                    quantities: {}
                },
                lineTotals: {},
                adjustment: null,
                cashId: null,
                paidFrom: 'cash',
                note: null,
                availableBalance: null,
                errors: {},
                processing: false,
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
            calculateLineTotal(){ //calculate line total
                this.resetComponent()

                this.$awn.asyncBlock(
                    axios.post(baseURL + 'calculate-sale-return-line-total', {
                        saleId: this.sale.id,
                        quantities: this.returnQuantities.quantities
                    }),
                    response => {
                        this.lineTotals = response.data
                    },
                    reason =>  {
                        this.errors = reason.response.data
                    }
                )
            },
            returnProceed(){
                this.resetComponent()

                //subtotal
                if(!this.subtotal){
                    alert('Not enough quantity')
                    return;
                }

                //select cash
                if(!this.cashId) {
                    alert('Select cash first')
                    return;
                }
                //valid cash amount
                if(this.total > this.availableBalance){
                    alert('Not enough balance')
                    return;
                }
                if (!confirm('Click ok to proceed')) return ;

                this.processing = true;

                this.$awn.asyncBlock(
                    axios.post(baseURL + 'admin/pos/return', {
                        sale_id: this.sale.id,
                        quantities: this.returnQuantities.quantities,
                        adjustment: this.adjustment,
                        paid_from: this.paidFrom,
                        cash_id: this.cashId,
                        note: this.note
                    }),
                    response => {
                        location.href = baseURL + 'invoice-generate/' + this.sale.invoice_no
                        this.processing = false;
                    },
                    reason => {
                        this.errors = reason.response.data
                        this.processing = false;
                    }
                )
            },
            getCurrentBalance(){
                this.$awn.asyncBlock(
                    axios.post(baseURL + 'get-details-from-cash', {
                        id: this.cashId
                    }),
                    response => {
                        this.availableBalance = response.data.amount
                    },
                    reason => console.log(reason)
                )
            }
        },
        mounted() {
            console.log(this.sale)
        }
    }
</script>

<style scoped>

</style>
