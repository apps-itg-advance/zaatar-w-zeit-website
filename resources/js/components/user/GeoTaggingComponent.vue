<template>
    <div>
        <div class="cartbig-modal modal fade" id="geo-tagging-modal" tabindex="-1" role="dialog"
             aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <div class="modal-body col-xl-10 float-none mx-auto pt-0">
                            <div class="row">
                                <div class="col-12">
                                    <h3 class="modal-heading">{{trans('address')}}</h3>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-8">
                                    <div class="form-group mb-0">
                                        <label for="exampleInputEmail1" class="search-location-label">{{trans('search_location')}}</label>
                                        <input :disabled="addresses.length === 3" type="email" class="form-control"
                                               id="exampleInputEmail1"
                                               aria-describedby="emailHelp"
                                               :placeholder="trans('search_for_your_location')">
                                        <button :disabled="addresses.length === 3" class="location-btn"
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
                            <div class="row">
                                <div class="col-12 pb-2">
                                    <h3 class="addresses-heading">{{trans('saved_addresses')}}</h3>
                                </div>
                                <div class="col-12 pb-4" v-if="addresses.length < 3">
                                    <button type="button" class="btn btn-8DBF43 mr-sm-4 add-address-btn"
                                            @click="openAddressModal()">
                                        <i class="fa fa-plus" aria-hidden="true"></i> {{trans('address')}}
                                    </button>
                                </div>
                                <div class="col-12">
                                    <div class="checkout-wrapper">
                                        <div class="item-summary">
                                            <div class="summary-items">
                                                <div class="summary-item mb-5 mb-sm-4"
                                                     v-for="(address, index) in addresses">
                                                    <div class="custom-control custom-radio mb-1"
                                                         @click="chooseAddress(address)">
                                                        <input type="radio"
                                                               :checked="defaultAddress.IsDefault === '1' && defaultAddress.ID === address.ID"
                                                               class="custom-control-input">
                                                        <label class="custom-control-label">
                                                            <p class="text-uppercase m-0">{{address.Name}}
                                                                <!--                                                                <span class="delivery-eta">ETA</span>-->
                                                            </p>
                                                            <span
                                                                class="d-block">{{address.CityName}} , {{address.ProvinceName}} <br>{{address.Line1}} - {{address.Line2}}</span>
                                                        </label>
                                                    </div>
                                                    <div class="buttons">
                                                        <a href="#" @click="editAddress(address)"
                                                           class="d-inline-block mr-3">
                                                            <img width="30" src="/assets/images/icon-checkout-edit.png"/>
                                                        </a>
                                                        <a href="javascript:void(0)"
                                                           @click="removeAddress(address,index)"
                                                           class="d-inline-block">
                                                            <img width="30" src="/assets/images/icon-checkout-close.png"/>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row p-4">
                        <div class="col-md-12 text-right">
                            <button :disabled="loading" type="button" class="btn btn-8DBF43 mr-sm-4 add-address-btn"
                                    @click="updateAddresses()">
                                <span v-if="!loading">{{trans('confirm')}}</span>
                                <i v-else class="fas fa-circle-notch fa-spin"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <address-modal-component @add-edit-address="getAllAddresses()"/>
    </div>
</template>

<script>
    import GlobalMixin from "../../mixins/GlobalMixin";
    import Trans from "../../mixins/GlobalMixin";

    export default {
        name: "GeoTaggingComponent",
        mixins: [GlobalMixin, Trans],
        props: {},
        data() {
            return {
                item: {},
                defaultAddress: {},
                addresses: [],
                loading: false
            }
        },
        mounted() {
            Bus.$on('open-geo-tagging-modal', (addresses) => {
                this.addresses = JSON.parse(JSON.stringify(addresses));
                $('#geo-tagging-modal').modal('show');
                this.addresses.forEach((address) => {
                    if (address.IsDefault === '1') {
                        this.defaultAddress = address;
                    }
                });
            });
        },
        methods: {
            openMap() {
                Bus.$emit('open-map');
            },
            openAddressModal() {
                Bus.$emit('open-address-modal');
                $('#geo-tagging-modal').modal('show');
            },
            getAllAddresses() {
                axios.get(`/address/all`).then((response) => {
                    this.addresses = response.data;
                    Bus.$emit('update-default-address', this.addresses);
                }).catch((error) => {
                    console.log(error);
                }).finally(() => {
                    this.loading = false
                    $('#geo-tagging-modal').modal('hide');
                });
            },
            removeAddress(address, index) {
                axios.delete(`/address/delete/${address.ID}`).then((response) => {
                    this.addresses.splice(index, 1);
                }).catch((error) => {
                    console.log(error);
                }).finally(() => {
                });
            },
            editAddress(address) {
                Bus.$emit('open-address-modal', address);
            },
            parseAddress(address) {
                let line2 = address.Line2.split(' ');
                let buildingName = line2[0] ? line2[0] : '';
                let buildingNumber = line2[2] ? line2[2] : '';
                let appNumber = address.AptNumber.split(' ');
                let floorNumber = appNumber[0] ? appNumber[0] : '';
                let ext = appNumber[2] ? appNumber[2] : '';
                this.item = {
                    id: address.ID,
                    building_name: buildingName,
                    building_number: buildingNumber,
                    ext: ext,
                    floor_number: floorNumber,
                    nick_name: address.Name,
                    street: '',
                    type_id: address.AddressType,
                    city_id: address.CityId,
                    company: address.CompanyName === '0' ? '' : address.CompanyName,
                    details: address.ExtraAddress,
                    is_default: address.IsDefault,
                    show_company: address.CompanyName !== null,
                    x_location: address.XLocation,
                    y_location: address.YLocation,
                }
                return this.item;
            },
            chooseAddress(address) {
                this.defaultAddress = address;
                this.defaultAddress.IsDefault = '1';
            },
            updateAddresses() {
                this.addresses.forEach((address) => {
                    if (address.ID !== this.defaultAddress.ID) {
                        address.IsDefault = '0';
                    } else {
                        address.IsDefault = '1';
                    }
                    this.parseAddress(address);
                    this.confirm();
                });
            },
            confirm() {
                this.loading = true
                let formData = new FormData();
                for (let key in this.item) {
                    if (this.item.hasOwnProperty(key)) {
                        if (Array.isArray(this.item[key])) {
                            let i = 0;
                            for (const item of this.item[key]) {
                                formData.append(key + '[' + i + ']', JSON.stringify(item));
                                i++;
                            }
                        } else if (typeof this.item[key] === 'object') {
                            formData.append(key, JSON.stringify(this.item[key]));
                        } else {
                            formData.append(key, this.item[key]);
                        }
                    }
                }
                axios.post(`/address/save`, formData).then((response) => {
                    this.getAllAddresses();
                }).catch((error) => {
                    console.log(error);
                });
            }
        }
    }
</script>

<style scoped>
    .modal-heading {
        font-family: 'Futura-Medium-BT';
    }

    .addresses-heading {
        font-family: 'Futura-Medium-BT';
        color: gray;
        font-size: 20px;
    }

    .search-location-label {
        font-size: 20px;
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
        font-size: 20px;
        cursor: pointer;
        font-family: 'Futura-Medium-BT';
    }

    .location-btn:hover {
        opacity: 0.3;
    }
</style>
