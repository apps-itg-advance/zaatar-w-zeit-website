<template>
    <div id="accordion">
        <div class="empty-parent" v-if="orders.hasOwnProperty('rows') &&orders.rows.length === 0">
            <h2>{{trans('no_fav_orders')}}</h2>
        </div>
        <div class="parent" v-if="loading">
            <div class="child">
                <i class="fas fa-circle-notch fa-spin fa-5x"></i>
            </div>
        </div>
        <div v-else>
            <div class="order-box p-3 favourite-box data-row mb-4" v-for="(order,index) in orders.rows">
                <order-item-component :order="order"
                                      @reload-data="spliceOrder"
                                      :index="index">
                </order-item-component>
            </div>
        </div>
    </div>
</template>
<script>
    import GlobalMixin from "../../mixins/GlobalMixin";

    export default {
        name: "FavoriteOrdersListComponent",
        mixins: [GlobalMixin],
        props: {},
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
            spliceOrder(index) {
                this.fireAlert(this.trans('your_favourite_order_removed_successfully.'), "")
                this.orders.rows.splice(index, 1);
            },
            getAllOrders() {
                this.loading = true;
                axios.get('/favorite/get-orders').then((response) => {
                    let items = [];
                    let mainPlus = [];
                    let parsedItem = {AppliedModifiers: [], AppliedMeal: {}};
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
                                    parsedItem = {AppliedModifiers: [], AppliedMeal: {}};
                                    append = false;
                                    newPLU = null;
                                }
                            if (item.OpenItem !== '1') {
                                if (item.MenuType === '1') {
                                    parsedItem.MainItem = item;
                                    newPLU = item.PLU;
                                    mainPlus.push(item.PLU);
                                } else if (item.MenuType === '2') {
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
                    this.loading =  false;
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
