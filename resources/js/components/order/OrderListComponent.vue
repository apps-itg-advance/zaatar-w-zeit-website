<template>
    <div id="accordion">
        <div class="row">
            <div class="col-md-12">
                <h4 class="font-weight-bold">{{trans('order_history')}}</h4>
            </div>
        </div>
        <div class="empty-parent" v-if="orders.hasOwnProperty('rows') && orders.rows.length === 0">
            <h2>{{trans('no_order_history')}}</h2>
        </div>
        <div class="parent" v-if="loading">
            <div class="child">
                <i class="fas fa-circle-notch fa-spin fa-5x"></i>
            </div>
        </div>
        <div v-else>
            <div class="order-box pt-3 pl-3 pr-3 favourite-box data-row mb-4" v-for="(order,index) in orders.rows">
                <order-item-component :order="order"
                                      :index="index"
                                      btnFav>
                </order-item-component>
            </div>
            <customization-modal/>
        </div>
    </div>
</template>
<script>
    import GlobalMixin from "../../mixins/GlobalMixin";

    export default {
        name: "OrderListComponent",
        mixins: [GlobalMixin],
        data() {
            return {
                orders: [],
                loading: false,
            }
        },
        mounted() {
            this.getAllOrders();
        },
        methods: {
            getAllOrders() {
                this.loading = true;
                axios.get('/orders/all').then((response) => {
                    let items = [];
                    let mainPlus = [];
                    let parsedItem = {AppliedComboItems:[], AppliedModifiers: [], AppliedMeal: {}};
                    let append = false;
                    let newPLU = null;
                    let netAmount = 0;
                    let paymentMethod = null;
                    let wallet = null;
                    let voucher = null;
                    response.data.rows.forEach((order) => {

                        order.Items.forEach((item) => {
                            if (item.MenuType === '1' && newPLU !== null) {
                                append = true;
                            }
                            if (append) {
                                items.push(parsedItem);
                                parsedItem = {AppliedComboItems:[], AppliedModifiers: [], AppliedMeal: {}};
                                append = false;
                                newPLU = null;
                            }

                            if (item.OpenItem !== '1') {
                                if (item.MenuType === '1') {
                                    parsedItem.MainItem = item;
                                    if(parseInt(item.GrossPrice) > 0){
                                        newPLU = item.PLU;
                                        mainPlus.push(item.PLU);
                                    }else{
                                        parsedItem.AppliedComboItems.push(item)
                                    }
                                } else if (item.MenuType === '3') {
                                    parsedItem.AppliedModifiers.push(item);
                                } else if (item.MenuType === '5') {
                                    if (Object.keys(parsedItem.AppliedMeal).length === 0) {
                                        parsedItem.AppliedMeal = item;
                                        parsedItem.AppliedMeal.AppliedItems = [];
                                    } else {
                                        if(Object.keys( parsedItem.AppliedMeal).length === 0){
                                            parsedItem.AppliedMeal = item;
                                            parsedItem.AppliedMeal.AppliedItems = [];
                                        }else{
                                            parsedItem.AppliedMeal.AppliedItems[0] = item;
                                        }
                                    }
                                }
                                parsedItem.MainItem.NetAmount = parseInt(parsedItem.MainItem.NetAmount) + parseInt(item.GrossPrice);
                            }
                        });

                        order.Payments.forEach((payment) => {
                            if (payment.PaymentName === 'voucher') {
                                voucher = payment.PaymentAmount;
                            } else if (payment.PaymentName === 'wallet') {
                                wallet = payment.PaymentAmount;
                            } else if (payment.PaymentName === 'credit') {
                                paymentMethod = payment.PaymentLabel;
                                netAmount = parseInt(payment.PaymentAmount);
                            } else {
                                netAmount = parseInt(payment.PaymentAmount);
                                paymentMethod = payment.PaymentLabel;
                            }
                        });

                        order.Voucher = voucher;
                        order.Wallet = wallet;
                        order.PaymentMethod = paymentMethod;
                        order.NetAmount = netAmount;
                        order.Items = items;
                        order.MainPlus = mainPlus;
                        items = [];
                        mainPlus = [];
                        netAmount = 0;
                        paymentMethod = null;
                        wallet = null;
                        voucher = null;
                    });

                    this.orders = response.data
                }).catch((error) => {
                    console.log(error);
                }).finally(() => {
                    this.loading = false;
                });
            }
        },
    }
</script>

<style scoped>
    .parent {
        height: 200px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .child {
        width: 100px;
        height: 100px;
    }
</style>
