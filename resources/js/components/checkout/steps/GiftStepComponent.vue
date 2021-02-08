<template>
    <div class="checkout-wrapper">
        <div class="col-xl-6 col-lg-10 col-md-12 col-sm-12 float-none p-0 mx-auto item-summary">

            <div class="title-div mb-4 row">
                <div class="col-md-12">
                    <h4>{{trans('gift')}}</h4>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <p><img src="/assets/images/gift-image.png" class="img-fluid d-block mx-auto"/></p>
                </div>
                <div class="col-sm-6 pl-4">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">{{trans('to')}}</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" v-model="item.gift_to" placeholder="Enter Name">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">{{trans('from')}}</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" v-model="item.gift_from" placeholder="Enter Name">
                        </div>
                    </div>
                    <div class="radios mt-4">
                        <div class="custom-control custom-radio mb-1" v-for="(gift, index) in checkoutData.Gift"
                             @click="chooseGiftOption(gift)">
                            <input type="radio" :checked="gift.ID === item.gift_option.ID" class="custom-control-input">
                            <label class="custom-control-label futura-medium">
                                {{gift.Title}}
                            </label>
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
        name: "GiftStepComponent",
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
                skipLoading: false
            }
        },
        created() {
            if (!this.checkoutInfo.hasOwnProperty('gift_option')) {
                this.item = {gift_option: {}};
            }
        },
        mounted() {
            console.log("checkoutInfo", this.checkoutInfo);
        },
        methods: {
            chooseGiftOption(giftOption) {
                this.item.gift_option = giftOption;
            },
            confirm() {
                if (!this.item.hasOwnProperty('gift_from') || this.item.gift_from === "" || !this.item.hasOwnProperty('gift_to') || this.item.gift_to === "") {
                    this.fireAlert("Names are required", "", false);
                    return;
                }

                if (!this.item.hasOwnProperty('gift_option') || Object.keys(this.item.gift_option).length === 0) {
                    this.fireAlert("Select option", "", false);
                    return;
                }

                this.loading = true;
                this.nextStep(this.currentStep.NextRouteObj, this.item);
            },
            skip() {
                this.skipLoading = true;
                this.nextStep(this.currentStep.NextRouteObj, this.item, true);
            },
        }
    }
</script>

<style scoped>

</style>
