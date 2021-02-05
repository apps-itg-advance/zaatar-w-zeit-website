<template>
    <div>
        <div class="empty-parent" v-if="favoriteItems.length === 0">
            <h2>{{trans('no_fav_items')}}</h2>
        </div>
        <div class="parent" v-if="loading">
            <div class="child">
                <i class="fas fa-circle-notch fa-spin fa-5x"></i>
            </div>
        </div>
        <div v-else>
            <div class="col-lg-12 float-none p-0 mx-auto">
                <div class="row-favourite mx-auto">
                    <div class="col-favourite" v-for="(item, index) in favoriteItems">
                        <item-component :menuItem="item"
                                        :index="index"
                                        @trigger-customization-modal="TriggerCustomizationModal(item)"/>
                    </div>
                </div>
            </div>
            <customization-modal/>
        </div>
    </div>
</template>

<script>
    import GlobalMixin from "../../mixins/GlobalMixin";

    export default {
        name: "FavoritesListComponent",
        mixins: [GlobalMixin],
        props: {},
        data() {
            return {
                favoriteItems: [],
                loading: false
            }
        },
        mounted() {
            this.getAllItems();
            Bus.$on('reload-data', (index) => {
                this.fireAlert(this.trans('your_favourite_item_removed_successfully.'), "")
                this.getAllItems();
            });
        },
        methods: {
            TriggerCustomizationModal(item) {
                Bus.$emit('open-customization-modal', item);
            },
            getAllItems() {
                this.loading = true;
                axios.get('/favorite/get-items').then((response) => {
                    response.data.data.forEach((item) => {
                        item.Quantity = 0;
                        item.TotalPrice = parseInt(item.Price);
                    });
                    this.favoriteItems = response.data.data;
                }).catch((error) => {
                    console.log(error);
                }).finally(() => {
                    this.loading = false;
                });
            }
        }
    }
</script>

<style scoped>
    .parent {
        height: 200px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .child {
        width: 100px;
        height: 100px;
    }
</style>
