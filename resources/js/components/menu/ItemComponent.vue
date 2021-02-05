<template>
    <div class="favourite-box">
        <div class="media media-item">
            <img :src="item.ThumbnailImg" style="cursor: pointer"
                 class="mr-3 img-thum b-lazy" alt="...">
            <div class="media-body">
                <h5 class="mt-0">
                    <a href="javascript:void(0)" style="max-width: 60% !important; float: left">{{item.ItemName}}</a>
                    <span class="price"
                          style="max-width: 38% !important; float:right; vertical-align: text-top">{{numberFormat(item.Price)}}  {{org.currency}}</span>
                    <div class="clearfix"></div>
                    <ul class="icon"></ul>
                </h5>
                <div class="content">{{item.Details}}</div>
            </div>
        </div>
        <div class="mediabox row align-items-center pl-2">
            <div class="col-md-7 pl-4">
                <a v-if="showFavBtn" href="javascript:void(0)"
                   @click="toggleFavorite(item)"
                   :class="item.IsFavorite === '1' ? 'active' : '' "
                   class="effect-underline link-favourite mr-3 pl-3">
                    <span class="pl-2">{{trans('add_to_favourites')}}</span>
                </a>
                <a v-if="item.Modifiers.length > 0" @click="TriggerCustomizationModal(item)"
                   class="link-customize pointer effect-underline">
                    <span class="customize-label">{{trans('customize')}}</span>
                </a>
            </div>
            <div class="col-sm-5 text-center">
                <div class="input-group mx-auto item-plus-minus">
                    <div class="input-group-append">
                        <button type="button" class="btn btn-link btn-link-minus pointer"
                                @click="SubQty()">
                            &nbsp;
                        </button>
                    </div>
                    <input type="text" class="form-control" v-model="item.Quantity"
                           style="background: none !important" readonly="readonly"/>
                    <div class="input-group-prepend">
                        <button type="button" class="btn btn-link btn-link-plus pointer"
                                @click="AddQty()">
                            &nbsp;
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <a v-if="!showFavBtn" href="#!" @click="setFavorite(item);" class="link-close">
            <img v-if="!loading" src="/assets/images/icon-close.png"/>
            <i v-else class="fas fa-circle-notch fa-spin"></i>
        </a>
    </div>
</template>

<script>
    import GlobalMixin from "../../mixins/GlobalMixin";
    import ItemMixin from "../../mixins/ItemMixin";

    export default {
        name: "ItemComponent",
        mixins: [GlobalMixin, ItemMixin],
        props: {
            menuItem: {
                type: Object,
                default: {}
            },
            showFavBtn: {
                type: Boolean,
                default: false
            },
            index: {
                type: Number,
                default: 0,
            },
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
            setFavorite(item) {
                if (!this.isAuthed) {
                    window.location.href = `/login`;
                    return
                }
                this.loading = true;
                this.toggleFavorite(item);
            },
            TriggerCustomizationModal(item) {
                if (this.isAuthed) {
                    if (this.defaultAddress === null) {
                        Bus.$emit('open-geo-tagging-modal', this.addresses);
                        return;
                    }
                }
                this.$emit('trigger-customization-modal', item);
            },
            SubQty() {
                if (this.isAuthed) {
                    if (this.defaultAddress === null) {
                        Bus.$emit('open-geo-tagging-modal', this.addresses);
                        return;
                    }
                }
                if (this.item.hasOwnProperty('Quantity')) {
                    this.item.Quantity -= 1;
                } else {
                    this.item.Quantity = 0;
                }
                Bus.$emit('add-edit-to-cart-item', this.item);
            },
            AddQty() {
                console.log(this.item.MakeMeal);
                if (this.isAuthed) {
                    if (this.defaultAddress === null) {
                        Bus.$emit('open-geo-tagging-modal', this.addresses);
                        return;
                    }
                }
                this.item.Modifiers.forEach((modifier) => {
                    modifier.TotalQuantity = 0;
                    modifier.details.items.forEach((i) => {
                        if (i.IsDefault === "yes") {
                            i.Quantity = 1;
                            modifier.TotalQuantity += 1;
                            this.item.AppliedModifiers.push(i);
                        } else {
                            i.Quantity = 0;
                        }
                    })
                });
                if (this.item.hasOwnProperty('Quantity')) {
                    this.item.Quantity += 1;
                } else {
                    this.item.Quantity = 1;
                }
                Bus.$emit('add-edit-to-cart-item', this.item);
            },
        }
    }
</script>

<style scoped>

</style>
