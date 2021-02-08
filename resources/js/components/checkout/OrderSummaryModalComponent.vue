<template>
    <div class="modal fade" id="order-summary-modal" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header col-xl-12 float-none mx-auto border-bottom-0">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body py-0 col-xl-12 orders-wrapper float-none mx-auto"
                     v-if="Object.keys(checkoutInfo).length > 0">
                    <div class="row">
                        <div class="col-md-6 offset-2">
                            <h2 class="futura-medium">{{trans("order_summary")}}</h2>
                        </div>
                    </div>

                    <div class="order-box">
                        <div class="order-info pt-2 pt-md-4">

                            <div class="order-info cursor-pointer">
                                <div class="row align-items-center mb-3">
                                    <div class="col-sm-4 text-left text-sm-right">
                                        <h5 class="text-label text-uppercase text-666666 font-weight-bold">
                                            {{trans('address')}}</h5>
                                    </div>
                                    <div class="col-sm-8 text-808080 pb-2">
                                        {{orderAddress}}
                                    </div>
                                </div>
                                <div class="order-history-details order-history">
                                    <div class="row">
                                        <h5 class="col-sm-4 text-left text-sm-right text-label text-uppercase font-weight-bold text-666666 mb-3">
                                            {{trans('order')}}
                                        </h5>
                                        <div class="col-sm-6 order-price-info">
                                            <div class="row pb-2" v-for="item in checkoutInfo.cart_items">
                                                <div class="col-md-10">
                                                    <h6 v-if="item.hasOwnProperty('Components')" class="mb-1">
                                                        {{item.ComboName}} <span class="pl-2 text-right">{{numberFormat(item.TotalPrice)}}</span>
                                                    </h6>
                                                    <h6 v-else class="mb-1">{{item.ItemName}}<span
                                                        class="pl-2 text-right">{{numberFormat(item.TotalPrice)}}</span>
                                                    </h6>
                                                    <p v-if="item.hasOwnProperty('Components')"
                                                       class="text-808080 modifiers-text">
                                                        {{parseAppliedItems(item)}}
                                                    </p>
                                                    <p v-else class="text-808080 modifiers-text">
                                                        {{parseModifiers(item)}}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-8 offset-2">
                                            <div class="total-block text-right">
                                                {{trans('sub_total')}}
                                                <span class="price d-inline-block ml-4 " style="width: 30% !important;">{{numberFormat(total)}} {{org.currency}}</span>
                                            </div>
                                            <div class="total-block text-right">
                                                {{trans('delivery_fee')}}
                                                <span class="price d-inline-block ml-4" style="width: 30% !important;">{{numberFormat(org.delivery_charge)}} {{org.currency}}</span>
                                            </div>
                                            <hr/>
                                            <div class="total-block text-right futura-b total-price">
                                                {{trans('total')}}
                                                <span class="price d-inline-block ml-4" style="width: 30%;">{{numberFormat(total + parseInt(org.delivery_charge))}} {{org.currency}}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="order-info">

<!--                                        <div class="row align-items-center">-->
<!--                                            <div class="col-4 text-left text-sm-right mb-3">-->
<!--                                                <h5 class="text-label text-uppercase text-666666 font-weight-bold">-->
<!--                                                    {{trans('wallet')}}</h5>-->
<!--                                            </div>-->
<!--                                            <div class="col-6 text-808080 mb-3 futura-book  pb-2">-->
<!--                                                {{parseOpenItem('Wallet')}}-->
<!--                                            </div>-->
<!--                                        </div>-->

                                        <div class="row align-items-center">
                                            <div class="col-4 text-left text-sm-right mb-3">
                                                <h5 class="text-label text-uppercase text-666666 font-weight-bold">
                                                    {{trans('gift')}}
                                                </h5>
                                            </div>
                                            <div class="col-6 text-808080 mb-3 futura-book  pb-2">
                                                {{parseOpenItem('Gift')}}
                                            </div>
                                        </div>
                                        <div class="row align-items-center">
                                            <div class="col-4 text-left text-sm-right mb-3">
                                                <h5 class="text-label text-uppercase text-666666 font-weight-bold">
                                                    {{trans('go_green')}}
                                                </h5>
                                            </div>
                                            <div class="col-6 text-808080 mb-3 futura-book  pb-2">
                                                {{parseOpenItem('RealGreen')}}
                                            </div>
                                        </div>
                                        <div class="row align-items-center">
                                            <div class="col-4 text-left text-sm-right mb-3">
                                                <h5 class="text-label text-uppercase text-666666 font-weight-bold">
                                                    {{trans('method')}}
                                                </h5>
                                            </div>
                                            <div class="col-6 text-808080 mb-3 futura-book  pb-2">
                                                {{parseOpenItem('PaymentMethods')}}
                                            </div>
                                        </div>

                                        <!--                                        <div v-if="order.ScheduleTime !== '0000-00-00 00:00:00'" class="row align-items-center">-->
                                        <!--                                            <div class="col-4 text-left text-sm-right mb-3">-->
                                        <!--                                                <h5 class="text-label text-uppercase text-666666 font-weight-bold">-->
                                        <!--                                                    {{trans('scheduled')}}-->
                                        <!--                                                </h5>-->
                                        <!--                                            </div>-->
                                        <!--                                            <div class="col-6 text-808080 mb-3 futura-book">-->
                                        <!--                                                {{order.ScheduleTime}}-->
                                        <!--                                            </div>-->
                                        <!--                                        </div>-->

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <a href="#" class="btn btn-8DBF43 mb-3 text-uppercase confirm float-right futura-book btn-confirm"
                       @click="confirm()" v-if="!loading">
                        {{trans('confirm')}}
                    </a>
                    <div v-else class="sp sp-circle float-right mb-3"></div>
                    <button v-if="!loading" type="button" style="margin-right: 10px"
                            class="btn btn-B3B3B3 mb-3 text-uppercase float-right futura-book btn-confirm cancel"
                            data-dismiss="modal">{{trans('cancel')}}
                    </button>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import StepMixin from "../../mixins/StepMixin";
    import GlobalMixin from "../../mixins/GlobalMixin";

    export default {
        name: "OrderSummaryModalComponent",
        mixins: [StepMixin, GlobalMixin],
        props: {},
        data() {
            return {
                checkoutInfo: {},
                loading: false,
                total: 0,
            }
        },
        created() {
            this.getCheckoutInfo();
        },
        mounted() {
            Bus.$on('open-order-summary-modal', () => {
                $('#order-summary-modal').modal('show');
                this.getCheckoutInfo();
            });
        },
        methods: {
            getCheckoutInfo() {
                axios.get('/checkout/info').then((response) => {
                    this.checkoutInfo = response.data
                    this.calculateTotal();
                    console.log("Checkout", response.data)
                }).catch((error) => {
                    console.log(error);
                });
            },
            confirm() {
                this.loading = true
                axios.post('/checkout/order/submit').then((response) => {
                    console.log("Submit Order Info", response);
                    if (response.data.status === 'success') {
                        $('#order-summary-modal').modal('hide');
                        if (response.data.url === 'home') {
                            this.fireAlert('You have placed new order', '');
                            window.location.href = `/customer/profile`;
                        } else {
                            window.location.href = `/checkout/payment/online`;
                        }
                    }
                }).catch((error) => {
                    console.log(error);
                }).finally(() => {
                    this.loading = false;
                });
            },
            parseModifiers(cartItem) {
                return cartItem.AppliedModifiers.map(function (elem) {
                    return elem.ModifierDetails;
                }).join(", ");
            },
            parseAppliedItems(cartItem) {
                let appliedItems = "";
                cartItem.Components.map(function (component) {
                    appliedItems += component.AppliedItems.map(function (item) {
                        return item.Name;
                    }).join(", ");
                });
                appliedItems += cartItem.AppliedModifiers.map(function (elem) {
                    return elem.ModifierDetails;
                }).join(", ");
                return appliedItems;
            },
            calculateTotal() {
                this.total = 0;
                this.checkoutInfo.cart_items.forEach((cartItem) => {
                    this.total += parseInt(cartItem.Price);
                    if (cartItem.hasOwnProperty('AppliedModifiers') && Object.keys(cartItem.AppliedModifiers).length > 0) {
                        cartItem.AppliedModifiers.forEach((modifier) => {
                            this.total += parseInt(modifier.Price);
                        })
                    }
                    if (cartItem.hasOwnProperty('AppliedMeal') && Object.keys(cartItem.AppliedMeal).length > 0) {
                        this.total += parseInt(cartItem.AppliedMeal.Price);
                    }
                });
            },
            parseOpenItem(label) {
                let value = this.trans('no');
                this.checkoutInfo.checkout_info.forEach((info) => {
                    if (label === 'Gift' && info.key === 'Gift') {
                        value = this.trans('yes');
                    } else if (label === 'RealGreen' && info.key === 'RealGreen') {
                        value = this.trans('yes');
                    } else if (label === 'PaymentMethods' && info.key === 'PaymentMethods') {
                        value = info.payment_method.Label;
                    } else if (label === 'Wallet' && info.key === 'Wallet') {
                        value = Math.trunc(parseInt(info.amount) / this.total * 100) + " % " + this.trans('discount');
                    } else {
                    }
                });
                return value;
            }
        },
        computed: {
            orderAddress() {
                if (Object.keys(this.checkoutInfo).length > 0) {
                    let address = this.checkoutInfo.checkout_info[0].address;
                    return `${address.CityName},${address.Line1},${address.Line2},${address.AptNumber},${address.CompanyName}`
                }
                return "-";
            },
        }
    }
</script>

<style scoped>

</style>
