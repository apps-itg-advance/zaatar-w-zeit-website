<template>
    <div class="pb-5">
        <address-step-component v-if="'Addresses' === selectedStep"
                                :cart="cart"
                                :current-step="step"
                                :checkout-data="checkoutData"
                                :checkout-info="checkoutInfo"/>
        <wallet-step-component v-else-if="'Wallet' === selectedStep"
                               :cart="cart"
                               :current-step="step"
                               :checkout-data="checkoutData"
                               :checkout-info="checkoutInfo"/>
        <gift-step-component v-else-if="'Gift' === selectedStep"
                             :cart="cart"
                             :current-step="step"
                             :checkout-data="checkoutData"
                             :checkout-info="checkoutInfo"/>
        <real-green-step-component v-else-if="'RealGreen' === selectedStep"
                                   :cart="cart"
                                   :current-step="step"
                                   :checkout-data="checkoutData"
                                   :checkout-info="checkoutInfo"/>
        <special-instructions-step-component v-else-if="'SpecialInstructions' === selectedStep"
                                             :cart="cart"
                                             :current-step="step"
                                             :checkout-data="checkoutData"
                                             :checkout-info="checkoutInfo"/>
        <payment-method-step-component v-else-if="'PaymentMethods' === selectedStep"
                                       :cart="cart"
                                       :current-step="step"
                                       :checkout-data="checkoutData"
                                       :checkout-info="checkoutInfo"/>
        <payment-credit-cards-component v-else-if="'CreditCards' === selectedStep"
                                        :cart="cart"
                                        :current-step="step"
                                        :checkout-data="checkoutData"
                                        :checkout-info="checkoutInfo"/>
    </div>
</template>

<script>
    export default {
        name: "WizardComponent",
        props: {
            cart: {
                type: Array,
                default: []
            },
            step: {
                type: Object,
                default: {}
            },
            checkoutData: {
                type: Object,
                default: {}
            },
            checkoutInfo: {
                type: Object,
                default: {}
            },
        },
        data() {
            return {
                selectedStep: null,
            }
        },
        created() {
            const urlParams = new URLSearchParams(window.location.search);
            this.selectedStep = urlParams.get('step');
            console.log(this.checkoutData)
            console.log("Selected Step", this.selectedStep)
        },
        methods: {}
    }
</script>

<style scoped>
</style>
