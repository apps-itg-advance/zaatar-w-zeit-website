<template>
    <div class="checkout-wrapper">
        <div class="col-xl-6 col-lg-8 col-md-9 col-sm-12 item-summary float-none p-0 mx-auto">

            <div class="title-div mb-4 row">
                <div class="col-md-12">
                    <h4>{{trans('address')}}</h4>
                </div>
            </div>

            <div class="row">
                <div class="col-8">
                    <div class="form-group mb-0">
                        <label for="exampleInputEmail1"
                               class="search-location-label">{{trans('search_location')}}</label>
                        <input :disabled="checkoutAddresses.length === 3" type="email" class="form-control"
                               id="exampleInputEmail1"
                               aria-describedby="emailHelp"
                               :placeholder="trans('search_for_your_location')">
                        <button :disabled="checkoutAddresses.length === 3" class="location-btn"
                                @click="openMap">
                            <div class="d-inline">
                                <img width="25" class="mb-2" src="/assets/images/geo-tag.png">
                            </div>
                            <div class="d-inline">
                                <span>{{trans('use_current_location')}}</span>
                            </div>
                        </button>
                    </div>
                </div>
            </div>

            <hr/>

            <div class="summary-items">
                <div class="col-12 pb-2">
                    <h3 class="addresses-heading">{{trans('saved_addresses')}}</h3>
                </div>
                <div class="col-12 pb-4" v-if="checkoutAddresses.length < 3">
                    <button type="button" class="btn btn-8DBF43 mr-sm-4 add-address-btn"
                            @click="openAddressModal()">
                        <i class="fa fa-plus" aria-hidden="true"></i> {{trans('address')}}
                    </button>
                </div>
                <div class="summary-item mb-5 mb-sm-4" v-for="(address, index) in checkoutAddresses">
                    <div class="row">
                        <div class="col-md-10">
                            <div class="custom-control custom-radio mb-1" @click="chooseAddress(address)">
                                <input type="radio" :checked="address.ID === item.address.ID"
                                       class="custom-control-input">
                                <label class="custom-control-label">
                                    <p class="text-uppercase m-0">{{address.Name}}
                                        <!--                                        <span class="pl-4">-->
                                        <!--                                            Delivery Around<span-->
                                        <!--                                            class="delivery-eta">{{address.DeliveryEta}} mins</span>-->
                                        <!--                                        </span>-->
                                    </p>
                                    <span
                                        class="d-block">{{address.CityName}} , {{address.ProvinceName}} <br>{{address.Line1}} - {{address.Line2}}</span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="d-inline">
                                <a href="#" @click="editAddress(address)" class="d-inline-block mr-3">
                                    <img width="30" src="/assets/images/icon-checkout-edit.png"/>
                                </a>
                            </div>
                            <div class="d-inline">
                                <a href="javascript:void(0)" @click="removeAddress(address,index)"
                                   class="d-inline-block">
                                    <img width="30" src="/assets/images/icon-checkout-close.png"/>
                                </a>
                            </div>
                        </div>
                    </div>


                </div>

                <div>
                    <hr/>
                    <div class="row">
                        <div class="col-sm-12"></div>
                        <div class="col-sm-2">
                            <div class="custom-control custom-radio mb-1">
                                <input type="radio" id="order_now" required value="0" class="custom-control-input"
                                       v-model="item.scheduled">
                                <label class="custom-control-label" for="order_now"><p
                                    class="text-uppercase m-0">{{trans('now')}}</p></label>
                            </div>
                        </div>
                        <div class="col-sm-5">
                            <div class="custom-control custom-radio mb-1">
                                <input type="radio" id="order_schedule" required onclick="ShowCalender()"
                                       v-model="item.scheduled" value="1" class="custom-control-input">
                                <label class="custom-control-label" for="order_schedule"><p
                                    class="text-uppercase m-0">{{trans('scheduled')}}</p></label>
                            </div>
                        </div>
                        <div class="clearfix" style="height: 50px"></div>
                        <div class="col-md-9" v-if="item.hasOwnProperty('scheduled') && item.scheduled === '1'">
                            <div class="row">
                                <div class="col-md-4">
                                    <select class="form-control" v-model="item.scheduled_on"
                                            @change="loadAvailableDates()">
                                        <option disabled selected>When</option>
                                        <option value="today">Today</option>
                                        <option value="tomorrow">Tomorrow</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <select class="form-control" v-model="item.scheduled_at">
                                        <option :value="availableDate.value"
                                                v-for="(availableDate, index) in availableDates">
                                            {{availableDate.label}}
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="clearfix" style="height: 70px"></div>
                    </div>
                </div>


                <div class="edit-address modal fade" id="edit-address" tabindex="-1" role="dialog"
                     aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div id="displayData"></div>
                </div>
                <div class="add-address modal fade" id="add-address" tabindex="-1" role="dialog"
                     aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div id="displayAddData"></div>
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
            <address-modal-component @add-edit-address="getAllAddresses()"/>
        </div>
    </div>
</template>

<script>
    import StepMixin from "../../../mixins/StepMixin";
    import GlobalMixin from "../../../mixins/GlobalMixin";

    export default {
        name: "AddressComponent",
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
                checkoutAddresses: this.checkoutData.Addresses,
                loading: false,
                availableDates: []
            }
        },
        created() {
            if (this.checkoutInfo.hasOwnProperty('scheduled_at')) {
                this.getAvailableScheduleDates();
            }
            if (!this.checkoutInfo.hasOwnProperty('address')) {
                this.item = {address: {}};
            }
            if (this.defaultAddress !== null) {
                this.item.address = this.defaultAddress;
            }
            console.log("checkoutInfo", this.checkoutInfo);
        },
        mounted() {},
        methods: {
            openAddressModal() {
                Bus.$emit('open-address-modal');
            },
            getAllAddresses() {
                axios.get(`/address/all`).then((response) => {
                    this.checkoutAddresses = response.data;
                }).catch((error) => {
                    console.log(error);
                });
            },
            getDateTime() {
                axios.get(`/checkout/get-datetime`).then((response) => {
                    console.log(response);
                }).catch((error) => {
                    console.log(error);
                });
            },
            getAvailableScheduleDates() {
                axios.get(`/checkout/get-available-schedule-dates`, {
                    params: {
                        open_time: this.item.address.OpenHours,
                        close_time: this.item.address.CloseHours,
                        scheduled_on: this.item.scheduled_on,
                        eta: this.item.address.DeliveryEta,
                    }
                }).then((response) => {
                    this.availableDates = response.data
                    if (!this.checkoutInfo.hasOwnProperty('scheduled_at')) {
                        this.item.scheduled_at = response.data[0].value
                    }
                }).catch((error) => {
                    console.log(error);
                });
            },
            loadAvailableDates() {
                this.getAvailableScheduleDates();
            },
            removeAddress(address, index) {
                axios.delete(`/address/delete/${address.ID}`).then((response) => {
                    this.checkoutAddresses.splice(index, 1);
                }).catch((error) => {
                    console.log(error);
                }).finally(() => {
                });
            },
            editAddress(address) {
                Bus.$emit('open-address-modal', address);
            },
            chooseAddress(address) {
                this.item.address = address;
                this.getAvailableScheduleDates();
            },
            skip() {
                this.loading = true;
                this.nextStep(this.currentStep.NextRouteObj, this.item, true);
            },
            openMap() {
                Bus.$emit('open-map');
            },
            confirm() {
                this.loading = true;
                if (Object.keys(this.item.address).length === 0) {
                    this.fireAlert("Select Address", "", false);
                    this.loading = false;
                    return;
                }
                if (this.item.hasOwnProperty('scheduled')) {
                    if (this.item.scheduled === '1') {
                        if (!this.item.hasOwnProperty('scheduled_on') || !this.item.hasOwnProperty('scheduled_at')) {
                            this.fireAlert("Select date and time", "", false);
                            this.loading = false;
                            return;
                        }
                    }
                } else {
                    this.fireAlert("Select Option", "", false);
                    this.loading = false;
                    return;
                }
                this.nextStep(this.currentStep.NextRouteObj, this.item);
            }
        },
        watch: {
            item: {
                handler(val) {
                    if (val.scheduled === '0' && val.hasOwnProperty('scheduled_at')) {
                        this.$delete(this.item, 'scheduled_at')
                    }
                    console.log("Watch Result", val)
                },
                deep: true
            }
        }
    }
</script>


<style scoped>
    .addresses-heading {
        font-size: 22px;
        color: #808080;
        font-family: 'Futura-Medium-BT', serif;
    }

    .add-address-btn {
        border-radius: 20px;
        padding: 4px 25px;
        font-size: 12px;
        font-family: 'Futura-Medium-BT';
        -webkit-box-shadow: 0px 0px 5px 0px rgba(0, 0, 0, 0.75);
        -moz-box-shadow: 0px 0px 5px 0px rgba(0, 0, 0, 0.75);
        box-shadow: 0px 0px 5px 0px rgba(0, 0, 0, 0.75)
    }

    .location-btn {
        background-color: transparent;
        border: none;
        color: #ED1C24;
        padding-top: 12px;
        font-size: 25px;
        cursor: pointer;
        font-family: 'Futura-Medium-BT';
    }

    .location-btn:hover {
        opacity: 0.3;
    }

    .search-location-label {
        font-size: 22px;
        color: #808080;
        font-family: 'Futura-Medium-BT', serif;
    }
</style>
