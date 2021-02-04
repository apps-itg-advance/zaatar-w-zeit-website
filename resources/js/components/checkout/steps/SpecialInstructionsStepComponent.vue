<template>
    <div class="checkout-wrapper">
        <div class="col-xl-6 col-lg-10 col-md-12 col-sm-12 float-none p-0 mx-auto item-summary">
            <div class="title-div mb-4 row">
                <div class="col-md-12">
                    <h4>{{trans('special_instructions')}}</h4>
                </div>
            </div>
            <div class="radios-green mb-5">
                <div class="custom-control custom-radio mb-4"
                     v-for="(instruction, index) in checkoutData.SpecialInstructions"
                     @click="selectInstruction(instruction)">
                    <input type="checkbox" class="custom-control-input sp_inst"
                           :checked="exist(item.instructions, item => item.ID === instruction.ID)">
                    <label class="custom-control-label text-uppercase">
                        {{instruction.Title}}
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
        name: "SpecialInstructionsStepComponent",
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
                skipLoading:false
            }
        },
        created() {
            if (!this.checkoutInfo.hasOwnProperty('instructions')) {
                this.item = {instructions: []};
            }
        },
        mounted() {
            console.log("checkoutInfo", this.checkoutInfo);
        },
        methods: {
            selectInstruction(instruction) {
                let exist = this.exist(this.item.instructions, item => item.ID === instruction.ID)
                if (!exist) {
                    this.item.instructions.push(instruction);
                } else {
                    const index = this.getIndex(this.item.instructions, item => item.ID === instruction.ID)
                    this.item.instructions.splice(index, 1);
                }
            },
            confirm() {
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
