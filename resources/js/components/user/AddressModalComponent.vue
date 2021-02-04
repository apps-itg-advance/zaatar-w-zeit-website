<template>
    <div>

        <div class="cartbig-modal modal fade" id="address-modal" tabindex="-1" role="dialog"
             aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <div class="modal-body col-xl-10 float-none mx-auto pt-0">
                            <div class="row">
                                <div class="col-md-12">
                                    <h2>{{trans('add_Address')}}</h2>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{trans('address_nickname')}}</label>
                                        <input type="text" class="form-control" v-model="item.nick_name"/>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{trans('city')}}</label>
                                        <select class="form-control" v-if="cities.length > 0" v-model="item.city_id">
                                            <option v-for="city in cities" :value="city.CityId">
                                                {{ city.CityName}}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4" v-for="addressType in addressesTypes">
                                    <div class="form-group">
                                        <input type="radio" class="address_type" :disabled="addressType.used"
                                               v-model="item.type_id" :value="addressType.ID"
                                               @click="toggleCompanyField(addressType)"/>
                                        <label>{{addressType.Title}}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>{{trans('street')}}</label>
                                        <input type="text" class="form-control" v-model="item.street"/>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{trans('building_name')}}</label>
                                        <input type="text" class="form-control" v-model="item.building_name"/>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>{{trans('building_number')}}</label>
                                        <input type="text" class="form-control" v-model="item.building_number"/>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>{{trans('floor')}}</label>
                                        <input type="text" class="form-control" v-model="item.floor_number"/>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-sm-8">
                                            <div class="form-group">
                                                <label>{{trans('phone_number')}}</label>
                                                <input type="text" class="form-control" readonly :value="4545"/>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>{{trans('ext')}}.</label>
                                                <input type="text" class="form-control" v-model="item.ext"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12" id="company-input-container" v-if="item.show_company">
                                    <div class="form-group">
                                        <label>Company</label>
                                        <input type="text" class="form-control" required v-model="item.company"/>
                                    </div>
                                </div>
                                <div class="form-group row d-none">
                                    <input type="hidden" class="form-control" id="modal_latitude" value=""
                                           name="y_location">
                                    <input type="hidden" class="form-control" id="modal_longitude" value=""
                                           name="x_location">
                                    <label class="form-control-label col-md-12">Latitude & Longitude</label>
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-5">
                                                <input class="form-control" type="text" id="manual_latitude" value=""
                                                       placeholder="Latitude">
                                            </div>
                                            <div class="col-md-5">
                                                <input class="form-control" type="text" id="manual_longitude" value=""
                                                       placeholder="Longitude">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 text-right">
                                    <button class="btn btn-sm btn-primary futura-book" @click="getCurrentLocation()">
                                        {{trans('my_location')}}
                                    </button>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label col-md-3"></label>
                                        <GmapMap
                                            ref="map"
                                            :center="coordinates"
                                            :zoom="17"
                                            map-type-id="terrain"
                                            style="height: 300px">
                                            <GmapMarker :position="coordinates"
                                                        :clickable="true" :draggable="true" @drag="updateCoordinates"/>
                                        </GmapMap>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button v-if="!loading" @click="submit()"
                                    class="btn btn-8DBF43 mb-3 text-uppercase futura-book btn-confirm">
                                {{trans('confirm')}}
                            </button>
                            <div v-else class="sp sp-circle"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="full-map-modal" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-body">
                        <GmapMap
                            ref="fullMap"
                            :center="coordinates"
                            :zoom="17"
                            map-type-id="terrain"
                            style="height: 700px">
                            <GmapMarker :position="coordinates"
                                        :clickable="true" :draggable="true" @drag="updateCoordinates"/>
                        </GmapMap>
                    </div>
                    <div class="modal-footer">
                        <button v-if="!loading" @click="confirmCurrentLocation()"
                                class="btn btn-8DBF43 mb-3 text-uppercase futura-book btn-confirm">
                            {{trans('confirm')}}
                        </button>
                        <div v-else class="sp sp-circle"></div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
    import {gmapApi} from 'vue2-google-maps';
    import GlobalMixin from "../../mixins/GlobalMixin";

    export default {
        name: "AddressModalComponent",
        mixins: [GlobalMixin],
        props: {},
        data() {
            return {
                item: {},
                cities: [],
                addressesTypes: [],
                map: null,
                coordinates: {lat: 10, lng: 10},
                loading: false
            }
        },
        mounted() {

            this.$refs.map.$mapPromise.then(map => {
                this.map = map;
            });

            Bus.$on('open-map', () => {
                navigator.geolocation.getCurrentPosition(
                    position => {
                        this.coordinates.lat = position.coords.latitude;
                        this.coordinates.lng = position.coords.longitude;
                        $('#full-map-modal').modal('show');
                    },
                    error => {
                        console.log(error.message);
                    },
                )
            });

            Bus.$on('open-address-modal', (item) => {
                this.item = {}
                this.getCities();
                this.getAddressesTypes();
                if (item !== undefined && Object.keys(item).length > 0) {
                    let line2 = item.Line2.split(' ');
                    let buildingName = line2[0] ? line2[0] : '';
                    let buildingNumber = line2[2] ? line2[2] : '';
                    let appNumber = item.AptNumber.split(' ');
                    let floorNumber = appNumber[0] ? appNumber[0] : '';
                    let ext = appNumber[2] ? appNumber[2] : '';
                    this.item = {
                        id: item.ID,
                        building_name: buildingName,
                        building_number: buildingNumber,
                        ext: ext,
                        floor_number: floorNumber,
                        nick_name: item.Name,
                        street: '',
                        type_id: item.TypeID,
                        city_id: item.CityId,
                        company: item.CompanyName === '0' ? '' : item.CompanyName,
                        details: item.ExtraAddress,
                        is_default: item.IsDefault,
                        show_company: item.CompanyName !== null,
                        x_location: JSON.parse(item.XLocation),
                        y_location: JSON.parse(item.YLocation),
                        google_zone: item.GoogleZone,
                        google_street: item.GoogleStreet,
                        google_city: item.GoogleCity,
                    }
                    this.coordinates.lng = JSON.parse(item.XLocation);
                    this.coordinates.lat = JSON.parse(item.YLocation);
                }
                $('#address-modal').modal('show');
            });
        },
        computed: {
            google: gmapApi,
        },
        methods: {
            getCities() {
                axios.get('/general/cities').then((response) => {
                    this.cities = response.data
                    console.log(response.data)
                }).catch((error) => {
                    console.log(error);
                }).finally(() => {
                });
            },
            getAddressesTypes() {
                axios.get('/address/addresses-types').then((response) => {
                    this.addressesTypes = response.data;
                }).catch((error) => {
                    console.log(error);
                }).finally(() => {
                });
            },
            toggleCompanyField(addressType) {
                this.item.type_id = addressType.ID;
                if (addressType.Title === 'Business') {
                    this.item.show_company = true;
                } else {
                    this.item.show_company = false;
                }
            },
            getCurrentLocation() {
                navigator.geolocation.getCurrentPosition(
                    position => {
                        let lat = position.coords.latitude;
                        let lng = position.coords.longitude;
                        this.coordinates.lat = lat;
                        this.coordinates.lng = lng;
                        this.item.x_location = lng;
                        this.item.y_location = lat;
                        this.geoCodedAddress();
                    },
                    error => {
                        console.log(error.message);
                    },
                );
                console.log(this.coordinates)
            },
            confirmCurrentLocation() {
                this.getCities();
                this.getAddressesTypes();
                this.checkZone();
            },
            updateCoordinates(location) {
                let lat = location.latLng.lat();
                let lng = location.latLng.lng();

                this.coordinates.lat = lat;
                this.coordinates.lng = lng;

                this.item.x_location = lng;
                this.item.y_location = lat;

                this.geoCodedAddress();
            },
            geoCodedAddress() {
                let geoCoder = new this.google.maps.Geocoder();
                geoCoder.geocode({'location': this.coordinates}, (results, status) => {
                    if (status === 'OK') {
                        if (results[0]) {
                            let location = {};
                            let addressComponents = results[0].address_components;
                            if (addressComponents.length > 0) {
                                addressComponents.forEach((k, v1) => {
                                    if (k.hasOwnProperty('types') && k.types.length > 0) {
                                        k.types.forEach((k2, v2) => {
                                            location[k2] = k.long_name
                                        });
                                    }
                                });
                            }
                            if (location.route) {
                                this.item.street = location.route;
                            }
                            let city;
                            let postal_code;
                            let state;
                            let country;
                            if (location.locality) {
                                city = location.locality;
                            }
                            if (!city) {
                                city = location.administrative_area_level_1;
                            }
                            if (location.postal_code) {
                                postal_code = location.postal_code;
                            }
                            if (location.administrative_area_level_1) {
                                state = location.administrative_area_level_1;
                            }
                            if (location.country) {
                                country = location.country;
                            }
                        } else {
                            console.log(status);
                        }
                    }
                });
            },
            checkZone() {
                this.loading = true;
                axios.post('/address/save', {
                    lat: this.coordinates.lat,
                    lng: this.coordinates.lng,
                }).then((response) => {
                    console.log(response)
                    $('#full-map-modal').modal('hide');
                    $('#address-modal').modal('show');
                }).catch((error) => {
                    this.fireAlert(error.response.data.message, 'Choose another location', false);
                    console.log(error.response.data.message);
                }).finally(() => {
                    this.loading = false;
                });
            },
            submit() {
                this.loading = true;
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
                axios.post('/address/save', formData).then((response) => {
                    $('#address-modal').modal('hide');
                    this.$emit('add-edit-address');
                    this.item = {}
                }).catch((error) => {
                    this.fireAlert(error.response.data.message, 'Choose another location', false);
                    console.log(error.response.data.message);
                }).finally(() => {
                    this.loading = false;
                });
            }
        }
    }
</script>

<style scoped>
    .modal {
        overflow: auto !important;
    }
</style>
