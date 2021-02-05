<template>
    <div class="col-xl-7 col-lg-5 col-md-12 col-sm-12 col-favourite-items">
        <div class="title-div mb-4 pb-2 text-center">
            <h2 class="title text-8DBF43">{{title}}</h2>
        </div>
        <div class="col-lg-12 float-none p-0 mx-auto">
            <div class="row-favourite mx-auto">
                <div class="col-favourite" v-for="combo in combos">
                    <combo-item-component :menuItem="combo"
                                          showFavBtn
                                          @trigger-combo-modal="TriggerComboModal(combo)"/>
                </div>
                <div class="col-favourite" v-for="item in menuItems">
                    <item-component :menuItem="item"
                                    showFavBtn
                                    @trigger-customization-modal="TriggerCustomizationModal(item)"/>
                </div>
            </div>
        </div>
        <geo-tagging-component/>
        <customization-modal/>
        <make-meal-modal/>
        <combo-modal/>
    </div>
</template>
<script>
    import GlobalMixin from "../../mixins/GlobalMixin";

    export default {
        name: "ItemsListComponent",
        mixins: [GlobalMixin],
        props: {
            title: {
                type: String,
                default: "Title"
            },
            items: {
                type: Array,
                default: []
            },
            combos: {
                type: Array,
                default: []
            },
            cart: {
                type: Array,
                default: []
            },
        },
        data() {
            return {
                menuItems: this.items,
                cartItems: this.cart,
            }
        },
        mounted() {
            this.recalculateMenuItemsQuantity();
            Bus.$on('recalculate-menu-item-quantity', (cartItems) => {
                this.cartItems = JSON.parse(JSON.stringify(cartItems));
                this.recalculateMenuItemsQuantity();
            });
            if (this.defaultAddress === null && this.isAuthed) {
                Bus.$emit('open-geo-tagging-modal', this.$addresses);
            }
        },
        methods: {
            TriggerCustomizationModal(item) {
                Bus.$emit('open-customization-modal', item);
            },
            TriggerComboModal(combo) {
                Bus.$emit('open-combo-modal', combo);
            },
            recalculateMenuItemsQuantity() {
                if (this.cartItems.length === 0) {
                    this.items.forEach((item) => {
                        item.Quantity = 0;
                    });
                } else {
                    this.cartItems.forEach((cartItem) => {
                        this.items.forEach((item) => {
                            if (cartItem.PLU === item.PLU) {
                                item.Quantity = cartItem.Quantity
                            }
                        });
                    });
                }
            }
        },
    }
</script>

<style scoped>

</style>
