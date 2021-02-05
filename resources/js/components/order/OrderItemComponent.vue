<template>
    <div>
        <div class="row">
            <div class="col-md-6 text-left">
                <h5 class="title">
                    {{trans('order')}} {{order.OrderId}}
                </h5>
            </div>
            <div class="col-md-6 text-right">
                <h5 class="title">
                    {{order.OrderDate}}
                </h5>
            </div>
        </div>
        <div class="order-info cursor-pointer" data-toggle="collapse" :data-target="'#collapse-'+index"
             aria-expanded="true" aria-controls="collapseOne">
            <div class="row align-items-center mb-3">
                <div class="col-sm-4 text-left text-sm-right">
                    <h5 class="text-label text-uppercase text-666666 font-weight-bold">{{trans('address')}}</h5>
                </div>
                <div class="col-sm-8 text-808080  pb-2">
                    {{orderAddress}}
                </div>
            </div>
            <div class="order-history-details order-history collapse " :id="'collapse-'+index"
                 aria-labelledby="headingOne"
                 data-parent="#accordion">
                <div class="row">
                    <h5 class="col-sm-4 text-left text-sm-right text-label text-uppercase font-weight-bold text-666666 mb-3">
                        {{trans('order')}}
                    </h5>
                    <div class="col-sm-6 order-price-info pb-2">
                        <div class="row mb-2" v-for="item in order.Items">
                            <div class="col-md-10">
                                <h6 class="mb-1">
                                    {{item.MainItem.ItemName}} <span
                                    class="text-right">{{numberFormat(item.MainItem.NetAmount)}}</span>
                                </h6>
                                <p class="text-808080 modifiers-text">
                                    {{parseModifiers(item)}}
                                </p>
                                <p class="text-808080 modifiers-text"
                                   v-if="item.AppliedMeal.hasOwnProperty('AppliedItems') && item.AppliedMeal.AppliedItems.length > 0">
                                    {{checkLang(item.AppliedMeal.AppliedItems[0].ItemName)}}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-8 offset-2">
                        <div class="total-block text-right">
                            {{trans('sub_total')}}
                            <span class="price d-inline-block ml-4" style="width: 30% !important;">
                                  {{numberFormat(order.NetAmount - parseInt(order.DeliveryCharge))}} {{org.Currency}}
                            </span>
                        </div>
                        <div class="total-block text-right">
                            {{trans('delivery_fee')}}
                            <span class="price d-inline-block ml-4" style="width: 30% !important;">
                                {{numberFormat(order.DeliveryCharge)}} {{org.Currency}}
                            </span>
                        </div>
                        <hr/>
                        <div class="total-block text-right futura-b total-price">
                            {{trans('total')}}
                            <span class="price d-inline-block ml-4" style="width: 30%;">
                                {{numberFormat(order.NetAmount)}} {{org.Currency}}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="order-info">

                    <!--                    <div class="row align-items-center">-->
                    <!--                        <div class="col-4 text-left text-sm-right mb-3">-->
                    <!--                            <h5 class="text-label text-uppercase text-666666 font-weight-bold">-->
                    <!--                                {{trans('wallet')}}</h5>-->
                    <!--                        </div>-->
                    <!--                        <div class="col-6 text-808080 mb-3 futura-book" v-if="order.Wallet !== null">-->
                    <!--                            {{order.Wallet}}-->
                    <!--                        </div>-->
                    <!--                    </div>-->

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
                            {{parseOpenItem('Real Green')}}
                        </div>
                    </div>
                    <div class="row align-items-center">
                        <div class="col-4 text-left text-sm-right mb-3">
                            <h5 class="text-label text-uppercase text-666666 font-weight-bold">
                                {{trans('method')}}</h5>
                        </div>
                        <div class="col-6 text-808080 mb-3 futura-book  pb-2" v-if="order.PaymentMethod !== null">
                            {{order.PaymentMethod}}
                        </div>
                    </div>

                    <div v-if="order.ScheduleTime !== '0000-00-00 00:00:00'" class="row align-items-center">
                        <div class="col-4 text-left text-sm-right mb-3  pb-2">
                            <h5 class="text-label text-uppercase text-666666 font-weight-bold">
                                {{trans('scheduled')}}</h5>
                        </div>
                        <div class="col-6 text-808080 mb-3 futura-book">
                            {{order.ScheduleTime}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
            </div>
            <div class="col-md-3 text-right pt-2">
                <a v-if="btnFav" @click="setFav(order)"
                   href="javascript:void(0)"
                   :class="order.Favorite === '1' ? 'active' : '' "
                   class="effect-underline link-favourite">{{trans('favourite')}}</a>
            </div>
            <div class="col-md-3 text-right">
                <a class="btn btn-orderrepeat effect-underline" @click="getItemsByPlus()">
                    <img v-if="!loading" src="/assets/images/icon-refresh.png" width="15" class="mr-1"/>
                    <i v-else class="fas fa-circle-notch fa-spin"></i>
                    {{trans('repeat_order')}}
                </a>
            </div>
        </div>
        <a href="#" v-if="!btnFav" @click="removeFav(order)" id="Favourite"
           class="link-close">
            <img v-if="!removeFavLoading" src="/assets/svg/icon-close.svg" width="24">
            <i v-else class="fas fa-circle-notch fa-spin"></i>
        </a>
    </div>
</template>

<script>
    import GlobalMixin from "../../mixins/GlobalMixin";

    export default {
        name: "OrderItemComponent",
        mixins: [GlobalMixin],
        props: {
            order: {
                type: Object,
                default: {},
            },
            index: {
                type: Number,
                default: 0,
            },
            btnFav: {
                type: Boolean,
                default: false,
            }
        },
        data() {
            return {
                menuItems: [],
                loading: false,
                removeFavLoading: false
            }
        },
        mounted() {
        },
        methods: {
            setFav(order) {
                order.Favorite = '1';
                let data = {
                    order_id: order.OrderId,
                }
                let formData = new FormData();
                for (let key in data) {
                    if (data.hasOwnProperty(key)) {
                        formData.append(key, data[key]);
                    }
                }
                axios.post('/favorite/set-favourite-order', formData).then((response) => {
                    console.log(response);
                }).catch((error) => {
                    console.log(error);
                });
            },
            removeFav(order) {
                this.removeFavLoading = true
                let data = {
                    order_id: order.OrderId,
                }
                let formData = new FormData();
                for (let key in data) {
                    if (data.hasOwnProperty(key)) {
                        formData.append(key, data[key]);
                    }
                }
                axios.post('/favorite/remove-favourite-order', formData).then((response) => {
                    this.$emit('reload-data', this.index);
                }).catch((error) => {
                    console.log(error);
                }).finally(() => {
                    this.removeFavLoading = false;
                });
            },
            getItemsByPlus() {
                this.loading = true;
                axios.get(`/orders/get-items-by-plus`, {
                    params: {
                        plus: JSON.stringify(this.order.MainPlus)
                    }
                }).then((response) => {
                    console.log("By PLU", response);
                    this.menuItems = response.data;
                    this.repeatOrder();
                }).catch((error) => {
                    console.log(error);
                });
            },
            parseOpenItem(label) {
                let value = '';
                this.order.OpenItems.forEach((item) => {
                    if (item.Label === label) {
                        if (item.Value === 'Yes') {
                            value = this.trans('yes');
                        } else {
                            value = this.trans('no');
                        }
                    }
                });
                return value;
            },
            parseModifiers(orderItem) {
                return orderItem.AppliedModifiers.map(function (elem) {
                    return `${elem.Prefix} ${elem.ItemName}`
                }).join(",");
            },
            repeatOrder() {
                //todo parse order items to be same as cart object
                let parsedOrders = [];
                this.order.Items.forEach((item) => {
                    let parsedItem = {
                        AppliedMeal: {},
                        AppliedModifiers: []
                    }
                    this.menuItems.forEach((menuItem) => {

                        if (menuItem.PLU === item.MainItem.PLU) {
                            for (let key in menuItem) {
                                if (menuItem.hasOwnProperty(key)) {
                                    if (key === 'Price') {
                                        parsedItem.TotalPrice = parseInt(menuItem[key]);
                                    }
                                    parsedItem[key] = menuItem[key];
                                }
                            }
                            item.AppliedModifiers.forEach((appliedModifier) => {
                                menuItem.Modifiers.forEach((modifier) => {
                                    modifier.details.items.forEach((modifierItem) => {
                                        if (appliedModifier.PLU === modifierItem.PLU) {
                                            parsedItem.TotalPrice += parseInt(modifierItem.Price);
                                            parsedItem.AppliedModifiers.push(modifierItem);
                                        }
                                    });
                                });
                            })

                            if (item.AppliedMeal.hasOwnProperty('AppliedItems') && item.AppliedMeal.AppliedItems.length > 0) {
                                item.AppliedMeal.AppliedItems.forEach((appliedItem) => {
                                    menuItem.MakeMeal.Items.forEach((mealItem) => {
                                        if (appliedItem.PLU === mealItem.PLU) {
                                            parsedItem.TotalPrice += parseInt(menuItem.MakeMeal.Price);
                                            menuItem.MakeMeal.Details = this.checkLang(menuItem.MakeMeal.Details);
                                            parsedItem.AppliedMeal = menuItem.MakeMeal;
                                            parsedItem.AppliedMeal.AppliedItems = [];
                                            parsedItem.AppliedMeal.AppliedItems[0] = mealItem;
                                        }
                                    });
                                })
                            }

                        }
                    });
                    parsedOrders.push(parsedItem);
                });
                this.loading = false
                Bus.$emit('cart-save-array', parsedOrders);
            }
        },
        computed: {
            orderAddress() {
                if (this.defaultAddress !== null) {
                    let address = this.order;
                    return `${address.AddressName} - ${address.City},${address.Line1},${address.Line2},${address.Apartment}`
                }
                return "-";
            },
        }
    }
</script>

<style scoped>
    .modifiers-text {
        font-size: 12px;
        line-height: 1;
    }
</style>
