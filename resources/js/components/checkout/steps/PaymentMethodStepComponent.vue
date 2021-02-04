<template>
    <div>
        <div class="checkout-wrapper">
            <div class="col-xl-6 col-lg-8 col-md-9 col-sm-12 item-summary float-none p-0 mx-auto">
                <div class="title-div mb-4 row">
                    <div class="col-md-12">
                        <h4>{{trans('payment')}}</h4>
                    </div>
                </div>
                <div class="radios-green mb-5">
                    <div class="custom-control custom-radio mb-4"
                         v-for="(paymentMethod, index) in checkoutData.PaymentMethods"
                         @click="selectPaymentMethod(paymentMethod)"
                         v-if="paymentMethod.Name !== 'wallet' && paymentMethod.Name !== 'voucher'">
                        <input type="radio" class="custom-control-input"
                               :checked="paymentMethod.PaymentId === item.payment_method.PaymentId">
                        <label class="custom-control-label text-uppercase">
                            {{paymentMethod.Label}}
                        </label>

                        <div v-if="item.payment_method.Currencies.length > 0 && item.payment_method.Name === 'credit'">
                            <hr v-if="paymentMethod.Name === 'credit'"/>
                            <div class="row">
                                <div class="col-md-3" v-for="currency in paymentMethod.Currencies"
                                     @click="chooseCurrency(currency)">
                                    <input type="radio" class="custom-control-input curr req"
                                           :checked="currency.Id === item.currency.Id">
                                    <label class="custom-control-label text-uppercase">{{currency.Currency}}</label>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4"
                             v-if="item.payment_method.Currencies.length > 0 && item.payment_method.Name === 'credit' && paymentMethod.Name === 'credit'">
                            <div class="row mb-2" v-for="(card,index) in cards"
                                 @click="chooseCard(card)">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <input type="radio" class="custom-control-input curr req"
                                                   :checked="card.Token === item.card.Token">
                                            <label
                                                class="custom-control-label text-uppercase">Card {{index+1}}</label>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-inline">
                                                <a href="javascript:void(0)" @click="removeCard(card)"
                                                   class="d-inline-block">
                                                    <img v-if="!loadingDeleteCard" width="30"
                                                         src="/assets/images/icon-checkout-close.png"/>
                                                    <i v-else class="fas fa-circle-notch fa-spin"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="d-inline pr-4">{{card.Brand}}</div>
                                            <div class="d-inline">{{card.Card}}</div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="row pt-2">
                                <div class="col-md-12">
                                    <button type="button" class="btn btn-8DBF43 mr-sm-4 add-card-btn"
                                            @click="addCard()">
                                        <span v-if="!newCardLoading"><i class="fa fa-plus" aria-hidden="true"></i> {{trans('card')}}</span>
                                        <span v-else>   <i
                                            class="fas fa-circle-notch fa-spin"></i> {{trans('card')}}</span>

                                    </button>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
                <div class="action-buttons text-center">
                    <button type="button" class="btn btn-8DBF43 text-uppercase mr-sm-4 confirm"
                            @click="confirm()" :disabled="loading">
                        <span v-if="!loading">{{trans('confirm')}}</span>
                        <i v-else class="fas fa-circle-notch fa-spin"></i>
                    </button>
                    <button v-if="currentStep.Required === false" type="button"
                            class="btn btn-B3B3B3 text-uppercase skip"
                            @click="skip()">
                        <span v-if="!loading">{{trans('skip')}}</span>
                        <i v-else class="fas fa-circle-notch fa-spin"></i>
                    </button>
                </div>
            </div>
        </div>
        <order-summary-modal-component/>
    </div>
</template>

<script>
    import StepMixin from "../../../mixins/StepMixin";
    import GlobalMixin from "../../../mixins/GlobalMixin";

    export default {
        name: "PaymentMethodStepComponent",
        mixins: [StepMixin, GlobalMixin],
        props: {
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
                loading: false,
                cards: this.checkoutData.GatewayToken,
                newCardLoading: false,
                loadingDeleteCard: false
            }
        },
        created() {
            this.item.new_card = 0;
            if (!this.checkoutInfo.hasOwnProperty('payment_method')) {
                this.item = {payment_method: {Currencies: []}, currency: {}};
            }
            if (!this.checkoutInfo.hasOwnProperty('card')) {
                this.item.card = {}
            }
        },
        mounted() {
            Bus.$on('step-confirmed', (response) => {
                this.loading = false;
                this.newCardLoading = false;
                Bus.$emit('open-order-summary-modal');
            });
        },
        methods: {
            getPaymentCards() {
                axios.get(`/customer/get-card`).then((response) => {
                    this.cards = response.data;
                }).catch((error) => {
                    console.log(error);
                });
            },
            chooseCard(card) {
                this.item.card = card;
            },
            addCard() {
                this.item.card = {};
                this.item.new_card = 1;
                this.newCardLoading = true
                this.nextStep(null, this.item);
            },
            removeCard(card) {
                this.loadingDeleteCard = true;
                let formData = new FormData();
                formData.append('id', card.Id);
                axios.post(`/customer/delete-card`, formData).then((response) => {
                    this.loadingDeleteCard = false;
                    this.getPaymentCards();
                    this.fireAlert(response.data, "");
                }).catch((error) => {
                    console.log(error);
                });
            },
            selectPaymentMethod(paymentMethod) {
                this.item.payment_method = paymentMethod;
            },
            chooseCurrency(currency) {
                this.item.currency = currency;
            },
            confirm() {
                this.loading = true;
                if (!this.item.payment_method.hasOwnProperty('Name')) {
                    this.fireAlert("Select payment method", "Message");
                    this.loading = false;
                    return;
                }
                if (this.item.payment_method.Name !== 'credit') {
                    this.item.currency = {};
                } else {
                    if (!this.item.hasOwnProperty('currency') || Object.keys(this.item.currency).length === 0) {
                        this.fireAlert('Title', 'Choose Currency before');
                        this.loading = false;
                        return;
                    }
                }
                this.item.new_card = 0;
                this.nextStep(this.currentStep.NextRouteObj, this.item);
            },

        }
    }
</script>

<style scoped>
    .add-card-btn {
        border-radius: 20px;
        padding: 4px 25px;
        font-size: 12px;
        font-family: 'Futura-Medium-BT';
        -webkit-box-shadow: 0px 0px 5px 0px rgba(0, 0, 0, 0.75);
        -moz-box-shadow: 0px 0px 5px 0px rgba(0, 0, 0, 0.75);
        box-shadow: 0px 0px 5px 0px rgba(0, 0, 0, 0.75)
    }
</style>
