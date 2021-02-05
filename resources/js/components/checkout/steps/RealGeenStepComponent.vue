<template>
    <div class="checkout-wrapper">
        <div class="col-xl-6 col-lg-10 col-md-12 col-sm-12 float-none p-0 mx-auto item-summary">
            <div class="title-div mb-4 row">
                <div class="col-md-12">
                    <h4>{{trans('real_green')}}</h4>
                </div>
            </div>
            <div class="greeninfo my-3">
                <div class="line-1">{{trans('our_aim')}}</div>
                <div class="line-2 text-8DBF43">{{trans('we_have_removed')}}</div>
            </div>
            <div class="greeninfo mb-5">
                <div class="line-1">{{trans('help_us')}}</div>
            </div>
            <div class="radios-green">
                <div class="custom-control custom-radio mb-4" v-for="(green, index) in checkoutData.RealGreen"
                     @click="chooseGreenOption(green)">
                    <input type="radio" :checked="green.ID === item.green_option.ID" class="custom-control-input">
                    <label class="custom-control-label text-uppercase">
                        {{green.Title}}
                    </label>
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
                    <span v-if="!loading">{{trans('skip')}}</span>
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
                loading: false
            }
        },
        created() {
            if (!this.checkoutInfo.hasOwnProperty('green_option')) {
                this.item = {green_option: {}};
            }
        },
        mounted() {
            console.log("checkoutInfo", this.checkoutInfo);
        },
        methods: {
            chooseGreenOption(greenOption) {
                this.item.green_option = greenOption;
            },
            confirm() {
                this.loading = true;
                if (Object.keys(this.item.green_option).length === 0) {
                    this.fireAlert("Select option first", "", false);
                    this.loading = false;
                    return;
                }
                this.nextStep(this.currentStep.NextRouteObj, this.item);
            },
            skip() {
                this.loading = true;
                this.nextStep(this.currentStep.NextRouteObj, this.item, true);
            },
        }
    }
</script>

<style scoped>

</style>
