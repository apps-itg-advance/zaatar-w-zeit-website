<template>
    <div class="cartbig-modal modal fade" id="customization-modal" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <div class="row">
                        <div class="col-lg-6 image-col">
                            <img :src="customizedItem.ThumbnailImg"
                                 class="img-fluid d-block mx-auto"/>
                        </div>
                        <div class="col-lg-6 text-col py-4">
                            <h5>
                                <span style="float: none !important;">{{customizedItem.ItemName}}</span>
                                <span>{{numberFormat(customizedItem.Price)}}  {{org.currency}}</span>
                            </h5>
                            <div class="info">{{customizedItem.Details}}</div>
                        </div>
                    </div>
                </div>

                <div class="items-row row mt-4 pl-2">
                    <div class="col-lg-6 col-md-6 item-col mb-4 pr-5" data-mh="matchHeight"
                         v-for="modifier in  customizedItem.Modifiers">
                        <h5 class="text-uppercase mb-3 text-center">
                            {{modifier.details.CategoryName}}</h5>
                        <div class="custom-control custom-radio" v-for="(i, index) in  modifier.details.items">
                            <div class="row">
                                <div class="col-9">
                                    <input type="checkbox" class="custom-control-input"
                                           v-model="customizedItem.AppliedModifiers" :value="i">
                                    <label class="custom-control-label" style="vertical-align: bottom;">
                                        <div style="float: left; max-width: 75%; overflow: hidden;">
                                            {{i.ModifierName}}
                                        </div>
                                        <span v-if="i.Price > 0" class="price"
                                              style="vertical-align: bottom; display: inline-block; height: 100%">
                                             {{i.Quantity > 0 ? numberFormat(i.Price * i.Quantity)  : numberFormat(i.Price)}} {{org.currency}}
                                        </span>
                                    </label>
                                </div>
                                <div class="col-3 text-right">
                                    <div class="btn-group" role="group" aria-label="Basic example">
                                        <button type="button" class="btn btn-default btn-circle btn-lg"
                                                @click="SubQty(i)">
                                            -
                                        </button>
                                        <button type="button" class="btn btn-default btn-circle quantity-middle"
                                                :class="i.Quantity > 0 ? 'quantity-middle-active': ''">
                                            {{i.Quantity}}
                                        </button>
                                        <button type="button" class="btn btn-default btn-circle"
                                                @click="AddQty(i)"
                                                :class="i.Quantity > 0 ? 'active': 'btn-lg'">
                                            +
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>

                <div class="items-row items-favourite row align-items-center mt-3" v-if="isAuthed">
                    <div class="col-lg-12 col-md-12 item-col" style="margin-left: -10px !important;">
                        <h5 class="favourite-title futura-b">{{trans('want_to_personalize')}}</h5>
                        <div class="col-lg-12 col-md-12" style="margin-left: -10px !important;">
                            <a @click="setFav(customizedItem)"
                               href="javascript:void(0)"
                               :class="customizedItem.IsFavorite === '1' ? 'active' : '' "
                               class="effect-underline link-favourite-u mr-3"></a>
                            <span>{{trans('fav_your_customized_item')}}!</span>
                            <input type="text" name="favourite_name" class="txt-favourite"
                                   :value="customizedItem.fav_name">
                        </div>
                    </div>
                </div>


                <div class="items-row row align-items-center pl-4"
                     v-if="customizedItem.hasOwnProperty('MakeMeal') && customizedItem.MakeMeal.hasOwnProperty('Items')">
                    <div class="col-md-6">
                        <h4>{{checkLang(customizedItem.MakeMeal.Details)}} {{customizedItem.MakeMeal.Price}}</h4>
                    </div>
                    <div class="col-md-6">
                        <div class="d-inline-block custom-control custom-radio"
                             v-for="i in customizedItem.MakeMeal.Items" @click="checkItem(i)">
                            <input
                                :checked="customizedItem.AppliedMeal.hasOwnProperty('AppliedItems') && exist(customizedItem.AppliedMeal.AppliedItems, e => e.ID === i.ID)"
                                type="checkbox"
                                class="custom-control-input" :value="i">
                            <label class="custom-control-label" style="vertical-align: bottom;">
                                <div class="pr-2">
                                    {{checkLang(i.Name)}} <span
                                    v-if="i.Price > 0">{{numberFormat(i.Price)}} {{org.currency}}</span>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="modal-footer pt-0">
                    <span class="title d-inline-block">{{trans('total')}}</span>
                    <span class="amount d-inline-block mx-5" id="DisplayTotal">{{numberFormat(customizedItem.TotalPrice)}} {{org.currency}}</span>
                    <a class="btn btn-8DBF43 text-uppercase btn-a"
                       @click="confirmCustomization()">{{trans('confirm')}}</a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import GlobalMixin from "../../mixins/GlobalMixin";
    import ItemMixin from "../../mixins/ItemMixin";

    export default {
        name: "CustomizationModel",
        mixins: [GlobalMixin, ItemMixin],
        props: {},
        mounted() {
            Bus.$on('open-customization-modal', (item, edit = false) => {
                if (!edit) {
                    item.Modifiers.forEach((modifier) => {
                        modifier.TotalQuantity = 0;
                        modifier.details.items.forEach((i) => {
                            if (i.IsDefault === "yes") {
                                i.Quantity = 1;
                                modifier.TotalQuantity += 1;
                                item.AppliedModifiers.push(i);
                            } else {
                                i.Quantity = 0;
                            }
                        })
                    });
                }
                this.customizedItem = JSON.parse(JSON.stringify(item));
                $('#customization-modal').modal('show');
            });
        },
        data() {
            return {
                customizedItem: {}
            }
        },
        methods: {
            setFav(customizedItem) {
                if (!this.isAuthed) {
                    window.location.href = `/login`;
                    return
                }
                this.toggleFavorite(customizedItem, true);
            },
            confirmCustomization() {
                this.customizedItem.Quantity += 1;
                Bus.$emit('add-edit-to-cart-item', this.customizedItem);
            },
            SubQty(item) {
                if (item.Quantity > 0) {
                    item.Quantity -= 1;
                    const index = this.getIndex(this.customizedItem.AppliedModifiers, i => i.ID === item.ID)
                    this.customizedItem.AppliedModifiers.splice(index, 1);
                    this.customizedItem.TotalPrice -= parseInt(item.Price);
                }
            },
            AddQty(item) {
                if (item.Quantity < item.MaxQuantity || item.MaxQuantity === "0") {
                    item.Quantity += 1;
                    this.customizedItem.TotalPrice += parseInt(item.Price);
                    this.customizedItem.AppliedModifiers.push(item);
                } else {
                    this.fireAlert(`You can select max ${item.MaxQuantity}`, ``, false);
                }
            },
            checkItem(item) {
                if (Object.keys(this.customizedItem.AppliedMeal).length === 0 && !this.customizedItem.AppliedMeal.hasOwnProperty('AppliedItems')) {
                    this.customizedItem.TotalPrice += parseInt(this.customizedItem.MakeMeal.Price);
                    this.customizedItem.AppliedMeal = this.customizedItem.MakeMeal;
                    this.customizedItem.AppliedMeal.AppliedItems = [];
                } else {
                    this.customizedItem.TotalPrice -= parseInt(this.customizedItem.MakeMeal.Price);
                    this.customizedItem.TotalPrice += parseInt(this.customizedItem.MakeMeal.Price);
                }
                this.customizedItem.AppliedMeal.AppliedItems[0] = item;
                console.log(this.customizedItem)
            },
            checkModifier(modifierItem) {
                let exist = this.exist(this.customizedItem.AppliedModifiers, item => item.ID === modifierItem.ID)
                if (!exist) {
                    this.customizedItem.AppliedModifiers.push(modifierItem);
                } else {
                    const index = this.getIndex(this.customizedItem.AppliedModifiers, item => item.ID === modifierItem.ID)
                    this.customizedItem.AppliedModifiers.splice(index, 1);
                }
                let modifierItemsTotalPrice = 0;
                this.customizedItem.AppliedModifiers.forEach((i) => {
                    modifierItemsTotalPrice += parseInt(i.Price);
                });
                if (this.customizedItem.Quantity > 0) {
                    this.customizedItem.TotalPrice = parseInt((this.customizedItem.Price * this.customizedItem.Quantity)) + parseInt(modifierItemsTotalPrice);
                } else {
                    this.customizedItem.TotalPrice = parseInt(this.customizedItem.Price) + parseInt(modifierItemsTotalPrice);
                }
                /**
                 *  Add isSelected for favorites feature cause we're saving JSON object as it's in DB
                 */
                if (!modifierItem.hasOwnProperty('isSelected')) {
                    modifierItem.isSelected = true
                } else {
                    modifierItem.isSelected = !modifierItem.isSelected;
                }
            },
        }
    }
</script>

<style scoped>
    .btn-circle {
        width: 20px;
        height: 20px;
        text-align: center;
        padding: 6px 0;
        font-size: 12px;
        line-height: 0;
        border-radius: 25px !important;
    }

    .btn-circle.btn-lg {
        width: 20px;
        height: 20px;
        padding: 2px 3px;
        font-size: 14px;
        line-height: 0;
        border-radius: 25px !important;
        border-color: gray;
    }

    .btn-circle.active {
        border-color: #8DBF43;
        color: white;
        background-color: #8DBF43;
    }

    .quantity-middle {
        width: 20px;
        height: 20px;
        padding: 2px 5px;
        font-size: 16px;
        line-height: 0;
        border-radius: 25px;
        border-color: transparent;
    }

    .quantity-middle-active {
        color: #8DBF43
    }
</style>
