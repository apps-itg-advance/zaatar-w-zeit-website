<template>
    <div class="favourite-box">
        <div class="media media-item">
            <img :src="item.ThumbnailImg" style="cursor: pointer"
                 class="mr-3 img-thum b-lazy" alt="...">
            <div class="media-body">
                <h5 class="mt-0">
                    <a href="javascript:void(0)" style="max-width: 60% !important; float: left">{{item.ComboName}}</a>
                    <span class="price"
                          style="max-width: 38% !important; float:right; vertical-align: text-top">{{numberFormat(item.Price)}}  {{org.currency}}</span>
                    <div class="clearfix"></div>
                    <ul class="icon"></ul>
                </h5>
                <div class="content">{{item.Details}}</div>
            </div>
        </div>
        <div class="mediabox row align-items-center">
            <div class="col-sm-7 text-center"></div>
            <div class="col-sm-5 text-center">
                <div class="input-group mx-auto item-plus-minus">
                    <div class="input-group-append">
                        <button type="button" class="btn btn-link btn-link-minus pointer"
                                @click="subQty(item)">
                            &nbsp;
                        </button>
                    </div>
                    <input type="text" class="form-control" v-model="item.Quantity"
                           style="background: none !important" readonly="readonly"/>
                    <div class="input-group-prepend">
                        <button type="button" class="btn btn-link btn-link-plus pointer"
                                @click="addQty(item)">
                            &nbsp;
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <a v-if="!showFavBtn" href="#!" @click.prevent="ChangeLoading();toggleFavorite(item);" class="link-close">
            <img v-if="!loading" src="/assets/images/icon-close.png"/>
            <div v-else class="sp-over-container">
                <div class="sp sp-circle"></div>
            </div>
        </a>
    </div>
</template>

<script>
    import GlobalMixin from "../../mixins/GlobalMixin";
    import ItemMixin from "../../mixins/ItemMixin";

    export default {
        name: "ComboItemComponent",
        mixins: [GlobalMixin, ItemMixin],
        props: {
            menuItem: {
                type: Object,
                default: {}
            },
            showFavBtn: {
                type: Boolean,
                default: false
            }
        },
        data() {
            return {
                item: this.menuItem,
                loading: false
            }
        },
        mounted() {
            Bus.$on('reload-data', (data) => {
                this.loading = false;
            })
        },
        methods: {
            ChangeLoading() {
                this.loading = true;
            },
            subQty(combo) {
                //todo remove combo from cart
            },
            addQty(combo) {
                if (this.isAuthed) {
                    if(this.defaultAddress === null){
                        Bus.$emit('open-geo-tagging-modal',this.addresses);
                        return;
                    }
                }
                this.$emit('trigger-combo-modal', combo);
            },
        }
    }
</script>

<style scoped>

</style>
