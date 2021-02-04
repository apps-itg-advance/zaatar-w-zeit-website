<template>
    <div class="checkout-wrapper">
        <div class="col-xl-6 col-lg-8 col-md-9 col-sm-12 item-summary float-none p-0 mx-auto">
            <div class="title-div mb-4 row">
                <div class="col-md-12">
                    <h4>{{trans('wallet')}}</h4>
                </div>
            </div>
            <div class="row my-5">
                <div class="col-md-12 text-center">
                    <h3>Soon</h3>
                </div>
<!--                <div class="col-md-8 offset-2">-->
<!--                    <div class="card-style item active" data-mh="matchHeight" id="wallet-b">-->
<!--                        <div class="item-div active text-white p-3" id="wallet-b-1">-->
<!--                            <div class="py-4 item-quantity float-right">-->
<!--                                <div class="float-right"></div>-->
<!--                            </div>-->
<!--                            <div class="media">-->
<!--                                <div class="media-body">-->
<!--                                    <h5 class="mt-0 text-uppercase">-->
<!--                                        {{trans('cashback')}} {{cashBack}} {{org.currency}}-->
<!--                                    </h5>-->
<!--                                    <h5 style="line-height: 0.3;font-size: 12px;">-->
<!--                                        {{trans('expire')}} <span>1/1/220</span>-->
<!--                                    </h5>-->
<!--                                </div>-->
<!--                                <img class="mr-3" width="50" src="/assets/images/icon-logowhite.png"-->
<!--                                     alt="Generic placeholder image">-->
<!--                            </div>-->
<!--                            <div class="form-group row pt-2">-->
<!--                                <label for="staticEmail"-->
<!--                                       class="col-sm-2 col-form-label pt-0 text-white">{{trans('amount')}}</label>-->
<!--                                <div class="col-sm-6">-->
<!--                                    <input :disabled="parseInt(checkoutData.Wallet.RedeemableAmountBalance) === 0"-->
<!--                                           type="number" class="form-control" id="staticEmail" v-model="item.amount">-->
<!--                                </div>-->
<!--                            </div>-->
<!--                            <div class="row">-->
<!--                                <div class="col-md-12">-->
<!--                                    <button @click="calculateTotal"-->
<!--                                            :disabled="parseInt(checkoutData.Wallet.RedeemableAmountBalance) === 0"-->
<!--                                            style="cursor: pointer" class="btn redeem-btn btn-sm">{{trans('redeem')}}-->
<!--                                    </button>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
            </div>
            <div class="action-buttons text-center" v-if="parseInt(checkoutData.Wallet.RedeemableAmountBalance) > 0">
<!--                <button type="button" class="btn btn-8DBF43 text-uppercase mr-sm-4 confirm"-->
<!--                        @click="confirm()" :disabled="loading">-->
<!--                    <span v-if="!loading">{{trans('confirm')}}</span>-->
<!--                    <i v-else class="fas fa-circle-notch fa-spin"></i>-->
<!--                </button>-->
                <button v-if="currentStep.Required === false" type="button" class="btn btn-B3B3B3 text-uppercase skip"
                        @click="skip()">
                    <span v-if="!skipLoading">{{trans('skip')}}</span>
                    <i v-else class="fas fa-circle-notch fa-spin"></i>
                </button>
            </div>
            <div class="action-buttons text-center" v-else>
                <button v-if="currentStep.Required === false" type="button" class="btn btn-B3B3B3 text-uppercase skip"
                        @click="skip()">
                    <span v-if="!skipLoading">{{trans('skip')}}</span>
                    <i v-else class="fas fa-circle-notch fa-spin"></i>
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    import StepMixin from "../../../mixins/StepMixin";
    import GlobalMixin from "../../../mixins/GlobalMixin";

    export default {
        name: "WalletStepComponent",
        mixins: [StepMixin, GlobalMixin],
        props: {
            cart: {
                type: Array,
                default: []
            },
            checkoutData: {
                type: Object,
                default: {}
            },
            checkoutInfo: {
                type: Object,
                default: {}
            },
            currentStep: {
                type: Object,
                default: {}
            },
        },
        data() {
            return {
                item: this.checkoutInfo,
                cartItems: this.cart,
                total: 0,
                cashBack: 0,
                loading: false,
                skipLoading:false
            }
        },
        created() {
            this.item.amount = 0;
            this.checkoutData.Wallet.RedeemableAmountBalance = 100;
        },
        mounted() {
            this.calculateTotal();
            console.log("cart", this.cart);
            console.log("checkoutInfo", this.checkoutInfo);
        },
        methods: {
            confirm() {
                this.loading = true;
                this.nextStep(this.currentStep.NextRouteObj, this.item);
            },
            skip() {
                this.skipLoading = true;
                this.nextStep(this.currentStep.NextRouteObj, this.item, true);
            },
            calculateTotal() {
                this.total = 0;
                this.cartItems.forEach((cartItem) => {
                    this.total += parseInt(cartItem.Price);
                    if (cartItem.hasOwnProperty('AppliedModifiers') && Object.keys(cartItem.AppliedModifiers).length > 0) {
                        cartItem.AppliedModifiers.forEach((modifier) => {
                            this.total += parseInt(modifier.Price);
                        })
                    }
                });
                if (parseInt(this.checkoutData.Wallet.RedeemableAmountBalance) > 0 && this.item.amount > 0) {
                    if (this.total > this.item.amount) {
                        this.cashBack = this.total - this.item.amount;
                    } else {
                        this.cashBack = this.item.amount - this.total;
                    }
                }
                console.log("Total Cart:", this.total)
                console.log("Cash Back:", this.cashBack)
            },
        },
        watch: {
            'item.amount': (oldVal, newVal) => {
                console.log("Changed")
            }
        }
    }
</script>

<style scoped>

    .card-style {
        background-color: #999999;
        border-radius: 15px;
        box-shadow: 0px 0px 10px;
    }

    .redeem-btn {
        background-color: white;
        color: #8DBF43;
        width: 150px;
    }

</style>
