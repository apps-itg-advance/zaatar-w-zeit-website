<template>
    <GmapMap
        ref="map"
        :center="{lat:coordinates.lat, lng:coordinates.lng}"
        :zoom="17"
        map-type-id="terrain"
        style="height: 300px">
        <GmapMarker :position="this.coordinates"
                    :clickable="true" :draggable="true" @drag="updateCoordinates"/>
    </GmapMap>
</template>

<script>
    import {gmapApi} from 'vue2-google-maps';

    export default {
        name: "Map",
        props: {
            initCoordinates: {
                type: Object,
                default: {lat: 10.00, lng: 10.00}
            }
        },
        data() {
            return {
                coordinates: this.initCoordinates,
                map: null,
            }
        },
        mounted() {
            console.log(this.coordinates)
            this.$refs.map.$mapPromise.then(map => {
                this.map = map;
            });
        },
        computed: {
            google: gmapApi,
        },
        methods: {
            updateCoordinates(location) {
                this.coordinates.lat = location.latLng.lat();
                this.coordinates.lng = location.latLng.lng();
                let geoLocation = this.getGeoLocation();
                this.$emit('update-coordinates', (this.coordinates, geoLocation));
            },
            getGeoLocation() {
                let geoCoder = new this.google.maps.Geocoder();
                let coordinates = {
                    lat: this.coordinates.lat,
                    lng: this.coordinates.lng
                };
                let location = {};
                geoCoder.geocode({'location': coordinates}, (results, status) => {
                    if (status === 'OK') {
                        if (results[0]) {
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
                                location.street = location.route;
                            }
                            let city;
                            if (location.locality) {
                                city = location.locality;
                            }
                            if (!city) {
                                city = location.administrative_area_level_1;
                            }
                        } else {
                            console.log(status);
                        }
                    }
                });
                return location
            }
        }
    }
</script>

<style scoped>

</style>
