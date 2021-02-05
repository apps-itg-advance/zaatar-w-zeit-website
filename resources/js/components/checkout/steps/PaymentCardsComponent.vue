<template>
    <div>
        <div class="checkout-wrapper">
            <div class="col-xl-6 col-lg-8 col-md-9 col-sm-12 item-summary float-none p-0 mx-auto">
                <div class="radios-green mb-5">

                    <div>

                    </div>

                </div>
                <div class="action-buttons text-center">
                    <button type="button" class="btn btn-8DBF43 text-uppercase mr-sm-4 confirm"
                            @click="confirm()" :disabled="loading">
                        <span v-if="!loading">{{trans('confirm')}}</span>
                        <i v-else class="fas fa-circle-notch fa-spin"></i>
                    </button>
                    <button @click="addNewCard()" type="button" class="btn btn-B3B3B3 text-uppercase skip">
                        <span v-if="!newCardLoading">Add New Card</span>
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
        name: "PaymentCards",
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
                newCardLoading:false
            }
        },
        created() {
            console.log(this.item)
        },
        mounted() {
            this.getPaymentCards();
            Bus.$on('step-confirmed', (response) => {
                this.loading = false;
                Bus.$emit('open-order-summary-modal');
            });
        },
        methods: {
            getPaymentCards(){
                axios.get(`/checkout/payment-cards`).then((response) => {
                   console.log(response);
                }).catch((error) => {
                    console.log(error);
                });
            },
            addNewCard() {
                this.newCardLoading = true
                this.nextStep(null, this.item);
            },
            confirm() {
                //todo add validation if not selected any card
                this.loading = true
                this.nextStep(null, this.item);
            },
        }
    }
</script>

<style scoped>

</style>
