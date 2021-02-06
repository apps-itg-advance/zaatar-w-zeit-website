<template>
    <div>
        <div class="cart-items">
            <div class="text-center text-bold">
                <h3>{{trans('order_summary')}}</h3>
            </div>
            <div class="cart-item mb-4 row" v-if="isAuthed">
                <div class="col-md-8 pt-1">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="d-inline name text-4D4D4D" style="width: 50%;">
                                <span class="name-value">
                                    {{trans('address')}}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="info text-808080">
                                {{selectedAddress}}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 pt-1 text-right">
                    <a @click="openGeoLocation()" href="javascript:void(0)">
                        <img src="/assets/images/icon-cart-edit.png"/>
                    </a>
                </div>
            </div>
            <div class="text-center pl-3 pr-3 mb-3" v-if="isAuthed">
                <div style="border-bottom: 1px solid #4D4D4D;"></div>
            </div>
            <div class="cart-item mb-4 row" v-for="(cartItem,index) in cartItems">
                <div class="col-md-8 pt-1">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="d-inline name text-4D4D4D" style="width: 60%;">
                                <span class="name-value" v-if="cartItem.hasOwnProperty('Components')">{{cartItem.ComboName}}</span>
                                <span class="name-value" v-else>{{cartItem.ItemName}}</span>
                            </div>
                            <div class="price d-inline name text-4D4D4D pl-2" style="width: 40%;">
                                {{numberFormat(cartItem.TotalPrice)}}
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="info text-808080" v-if="cartItem.hasOwnProperty('Components')">
                                {{parseAppliedItems(cartItem)}}
                            </div>
                            <div class="info text-808080" v-else>
                                {{parseModifiers(cartItem)}}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 pt-1 text-right">
                    <a v-if="cartItem.hasOwnProperty('Components')" @click="editItemComboModal(cartItem,index)"
                       href="javascript:void(0)">
                        <img src="/assets/images/icon-cart-edit.png"/>
                    </a>
                    <a v-else @click="editItemCustomizationModal(cartItem,index)" href="javascript:void(0)">
                        <img src="/assets/images/icon-cart-edit.png"/>
                    </a>
                    <a @click="copyCartItem(cartItem)" href="javascript:void(0)">
                        <img src="/assets/images/icon-cart-add.png"/>
                    </a>
                    <a @click="removeCartItem(cartItem,index)" href="javascript:void(0)">
                        <img src="/assets/images/icon-cart-delete.png"/>
                    </a>
                </div>
                <div class="col-md-12"
                     v-if="cartItem.AppliedMeal.hasOwnProperty('AppliedItems') && cartItem.AppliedMeal.AppliedItems.length > 0">
                    <div class="speacial-meal bg-8DBF43">
                        <div class="meal-banner">
                            <span class="text-uppercase">{{trans('meal')}}</span>
                            <span class="d-inline-block mx-3">{{checkLang(cartItem.AppliedMeal.Details)}}</span>
                            <span class="d-inline-block">{{numberFormat(cartItem.AppliedMeal.Price)}}</span>
                        </div>
                        <a href="javascript:void(0)" class="close" @click="removeMeal(cartItem)">
                            <img src="/assets/images/icon-close-white.png"/>
                        </a>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>

        <div class="carttotal-block mt-3">
            <div class="delivery-fee">
                <span>{{trans('delivery_fees')}}</span>
                <span class="text-right float-right">{{numberFormat(org.delivery_charge)}} {{org.currency}}</span>
            </div>
            <hr/>
            <div class="total-fee">
                <span>{{trans('total')}}</span>
                <span class="text-right float-right">{{numberFormat(total + parseInt(org.delivery_charge))}} {{org.currency}}</span>
            </div>
        </div>
        <hr/>
        <div class="action-buttons text-center mb-3">
            <button :disabled="cartItems.length === 0" class="btn btn-B3B3B3 text-uppercase"
                    @click="clearCart()">
                <span v-if="!loading">{{trans('clear_all')}}</span>
                <i v-else class="fas fa-circle-notch fa-spin"></i>
            </button>
            <button :disabled="cartItems.length === 0" @click="checkout" href="#" class="btn btn-8DBF43 text-uppercase">
                <span v-if="!checkoutLoading">{{trans('checkout')}}</span>
                <i v-else class="fas fa-circle-notch fa-spin"></i>
            </button>
        </div>
    </div>
</template>
<script>
    import GlobalMixin from "../../mixins/GlobalMixin";

    export default {
        name: "CartComponent",
        mixins: [GlobalMixin],
        props: {
            cart: {
                type: Array,
                default: []
            }
        },
        data() {
            return {
                cartItems: this.cart,
                total: 0,
                isEdit: false,
                editedIndex: null,
                loading: false,
                checkoutLoading: false,
            }
        },
        created() {
            if (this.cartItems === null) {
                this.cartItems = [];
            }
        },
        mounted() {
            this.calculateTotal();
            Bus.$on('add-edit-to-cart-item', (item, splice = false) => {
                this.addEditToCart(item, splice);
                $('#customization-modal').modal('hide');
                $('#combo-modal').modal('hide');
                if (!item.hasOwnProperty('Components') && Object.keys(item.MakeMeal).length > 0 && Object.keys(item.AppliedMeal).length === 0) {
                    Bus.$emit('open-meal-modal', item);
                }
            });
            Bus.$on('confirm-meal', (item) => {
                if (this.editedIndex === null) {
                    this.cartItems[this.cartItems.length - 1] = item;
                } else {
                    this.cartItems[this.editedIndex] = item;
                }
                this.editedIndex = null;
                this.saveCart();
                $('#meal-modal').modal('hide');
            });
            Bus.$on('cart-save-array', (cartItems) => {
                console.log("cart-save-array",cartItems)
                this.cartItems = [];
                this.cartItems = cartItems;
                this.saveCart();
            });
        },
        methods: {
            openGeoLocation() {
                Bus.$emit('open-geo-tagging-modal', this.addresses);
            },
            checkout() {
                if (!this.isAuthed) {
                    window.location.href = `/login`;
                    return
                }
                if (this.defaultAddress === null) {
                    Bus.$emit('open-geo-tagging-modal', this.addresses);
                    return;
                }
                this.checkoutLoading = true
                window.location.href = `/checkout?step=Addresses`;
            },
            editItemCustomizationModal(cartItem, index) {
                this.isEdit = true;
                this.editedIndex = index
                Bus.$emit('open-customization-modal', cartItem, true);
            },
            editItemComboModal(cartItem, index) {
                this.isEdit = true;
                this.editedIndex = index
                Bus.$emit('open-combo-modal', cartItem, true);
            },
            removeCartItem(cartItem, index) {
                cartItem.Quantity -= 1;
                this.cartItems.splice(index, 1);
                this.saveCart();
            },
            copyCartItem(cartItem) {
                cartItem.Quantity += 1;
                let parsedCartItem = JSON.parse(JSON.stringify(cartItem));
                this.cartItems.push(parsedCartItem);
                this.saveCart();
            },
            removeMeal(cartItem) {
                cartItem.TotalPrice -= parseInt(cartItem.AppliedMeal.Price);
                cartItem.AppliedMeal = {};
                this.saveCart();
            },
            addEditToCart(item, splice = false) {
                if (this.cartItems === null) {
                    this.cartItems = [];
                }
                if (!item.hasOwnProperty('AppliedModifiers')) {
                    item.AppliedModifiers = [];
                }
                if (!item.hasOwnProperty('AppliedMeal')) {
                    item.AppliedMeal = [];
                }

                if (!splice) {
                    if (this.cartItems.length === 0 || !this.isEdit) {
                        this.cartItems.push(item);
                    } else {
                        this.cartItems[this.editedIndex] = item;
                    }
                } else {
                    const index = this.getIndex(this.cartItems, i => i.PLU === item.PLU)
                    if(index >= 0){
                        this.cartItems.splice(index, 1);
                    }
                }
                this.saveCart();
            },
            saveCart() {
                let formData = new FormData();
                formData.append('cart_items', JSON.stringify(this.cartItems));
                axios.post(`/cart/save-cart`, formData).then((response) => {
                    this.calculateTotal();
                }).catch((error) => {
                    console.log(error);
                }).finally(() => {
                    this.isEdit = false;
                });
            },
            clearCart() {
                this.loading = true;
                axios.get(`/cart/clear-cart`).then((response) => {
                    this.cartItems = [];
                    this.calculateTotal();
                }).catch((error) => {
                    console.log(error);
                }).finally(() => {
                    this.loading = false;
                });
            },
            parseModifiers(cartItem) {
                return cartItem.AppliedModifiers.map(function (elem) {
                    return elem.ModifierDetails;
                }).join(", ");
            },
            parseAppliedItems(cartItem) {
                let appliedItems = "";
                cartItem.Components.map(function (component) {
                    appliedItems += appliedItems = component.AppliedItems.map(function (item) {
                        return item.Name;
                    }).join(", ");
                });
                appliedItems += cartItem.AppliedModifiers.map(function (elem) {
                    return elem.ModifierDetails;
                }).join(", ");
                return appliedItems;
            },
            calculateTotal() {
                this.total = 0;
                this.cartItems.forEach((cartItem) => {
                    this.total += parseInt(cartItem.Price);
                    if (cartItem.hasOwnProperty('AppliedModifiers') && cartItem.AppliedModifiers.length > 0) {
                        cartItem.AppliedModifiers.forEach((modifier) => {
                            this.total += parseInt(modifier.Price);
                        })
                    }
                    if (cartItem.hasOwnProperty('AppliedMeal') && Object.keys(cartItem.AppliedMeal).length > 0) {
                        this.total += parseInt(cartItem.AppliedMeal.Price);
                    }
                });
                Bus.$emit('recalculate-menu-item-quantity', this.cartItems);
            },
        },
    }
</script>

<style scoped>
    .meal-banner span {
        font-size: 14px;
    }

    .meal-banner span:first-of-type {
        font-weight: bold;
    }
</style>
