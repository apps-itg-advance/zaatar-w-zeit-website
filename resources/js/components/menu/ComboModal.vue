<template>
    <div class="cartbig-modal modal fade" id="combo-modal" tabindex="-1" role="dialog"
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
                                <span style="float: none !important;">{{customizedItem.ComboName}}</span>
                                <span>{{numberFormat(customizedItem.Price)}}  {{org.currency}}</span>
                            </h5>
                            <div class="info">{{customizedItem.Details}}</div>
                        </div>
                    </div>
                </div>
                <div class="items-row row mt-4 pl-2">
                    <div class="col-lg-6 col-md-6 item-col mb-4 pr-5" data-mh="matchHeight"
                         v-for="component in  customizedItem.Components">
                        <h5 class="text-uppercase text-center" :class="component.Name === null ? 'mb-4' : ' mb-3 '">
                            {{component.Name}}</h5>
                        <div class="custom-control custom-radio" v-for="(i, index) in  component.Items">
                            <div class="row">
                                <div class="col-8">
                                    <input type="checkbox" class="custom-control-input"
                                           v-model="i.AppliedItems" :value="i">
                                    <label class="custom-control-label" style="vertical-align: bottom;">
                                        <div style="float: left; max-width: 75%; overflow: hidden;">
                                            {{i.Name}}
                                        </div>
                                    </label>

                                </div>
                                <div class="col-4 text-right">
                                    <div class="btn-group" role="group" aria-label="Basic example">
                                        <button type="button" class="btn btn-default btn-circle btn-lg"
                                                @click="SubQty(component,i)">
                                            -
                                        </button>
                                        <button type="button" class="btn btn-default btn-circle quantity-middle"
                                                :class="i.Quantity > 0 ? 'quantity-middle-active': ''">
                                            {{i.Quantity}}
                                        </button>
                                        <button type="button" class="btn btn-default btn-circle"
                                                @click="AddQty(component,i)"
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
                <div class="items-row row mt-4 pl-2">
                    <div class="col-lg-6 col-md-6 item-col mb-4 pr-5" data-mh="matchHeight"
                         v-for="modifier in  customizedItem.Modifiers">
                        <h5 class="text-uppercase mb-3 text-center">
                            {{modifier.details.CategoryName}}</h5>
                        <div class="custom-control custom-radio" v-for="(i, index) in  modifier.details.items">
                            <div class="row">
                                <div class="col-8">
                                    <input type="checkbox" class="custom-control-input"
                                           v-model="customizedItem.AppliedModifiers" :value="i">
                                    <label class="custom-control-label" style="vertical-align: bottom;">
                                        <div style="float: left; max-width: 75%; overflow: hidden;">
                                            {{i.ModifierName}}
                                        </div>
                                        <span v-if="i.Price > 0" class="price"
                                              style="vertical-align: bottom; display: inline-block; height: 100%">
                                             {{i.Quantity > 0 ? (numberFormat(i.Price) * i.Quantity)  : numberFormat(i.Price)}} {{org.currency}}
                                        </span>
                                    </label>
                                </div>
                                <div class="col-4 text-right">
                                    <div class="btn-group" role="group" aria-label="Basic example">
                                        <button type="button" class="btn btn-default btn-circle btn-lg"
                                                @click="SubQty(modifier,i,true)">
                                            -
                                        </button>
                                        <button type="button" class="btn btn-default btn-circle quantity-middle"
                                                :class="i.Quantity > 0 ? 'quantity-middle-active': ''">
                                            {{i.Quantity}}
                                        </button>
                                        <button type="button" class="btn btn-default btn-circle"
                                                @click="AddQty(modifier,i,true)"
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
                <div class="modal-footer pt-0">
                    <span class="title d-inline-block">{{trans('total')}}</span>
                    <span class="amount d-inline-block mx-5" id="DisplayTotal">{{numberFormat(customizedItem.Price)}} {{org.currency}}</span>
                    <a class="btn btn-8DBF43 text-uppercase btn-a" @click="addCombo()">{{trans('confirm')}}</a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import GlobalMixin from "../../mixins/GlobalMixin";
    import ItemMixin from "../../mixins/ItemMixin";

    export default {
        name: "ComboModal",
        mixins: [GlobalMixin, ItemMixin],
        props: {},
        mounted() {
            Bus.$on('open-combo-modal', (item, edit = false) => {
                if (!edit) {
                    item.TotalQuantity = 0;
                    item.MinTotalQuantity = 0;
                    item.Components.forEach((component) => {
                        component.AppliedItems = [];
                        component.TotalQuantity = 0;
                        component.Items.forEach((i) => {
                            if (i.IsDefault === "1") {
                                i.Quantity = 1;
                                item.TotalQuantity += 1;
                                component.TotalQuantity += 1;
                                component.AppliedItems.push(i);
                            } else {
                                i.Quantity = 0;
                            }
                        })
                        item.MinTotalQuantity += parseInt(component.MinChoose);
                    });

                    item.Modifiers.forEach((modifier) => {
                        item.AppliedModifires = [];
                        modifier.details.items.forEach((i) => {
                            i.Quantity = 0;
                        });
                    });
                }
                this.customizedItem = JSON.parse(JSON.stringify(item));
                $('#combo-modal').modal('show');
            });
        },
        data() {
            return {
                customizedItem: {}
            }
        },
        methods: {
            addCombo() {
                if (this.customizedItem.TotalQuantity < this.customizedItem.MinTotalQuantity) {
                    this.fireAlert(`Min quantity to be selected is ${this.customizedItem.MinTotalQuantity}`, ``,false);
                    return;
                }
                Bus.$emit('add-edit-to-cart-item', this.customizedItem);
            },
            SubQty(component, item, isModifier = false) {
                if (!isModifier) {
                    if (item.Quantity > 0) {
                        item.Quantity -= 1;
                        component.TotalQuantity -= 1;
                        this.customizedItem.TotalQuantity -= 1
                        const index = this.getIndex(component.AppliedItems, i => i.ID === item.ID)
                        component.AppliedItems.splice(index, 1);
                    }
                } else {
                    if (item.Quantity > 0) {
                        item.Quantity -= 1;
                        const index = this.getIndex(this.customizedItem.AppliedModifires, i => i.ID === item.ID)
                        this.customizedItem.AppliedModifires.splice(index, 1);
                    }
                }
            },
            AddQty(component, item, isModifier = false) {
                if (!isModifier) {
                    if (component.TotalQuantity < component.MaxChoose) {
                        item.Quantity += 1;
                        component.TotalQuantity += 1;
                        this.customizedItem.TotalQuantity += 1
                        component.AppliedItems.push(item);
                    } else {
                        this.fireAlert(`You can select max ${component.MaxChoose}`, ``,false);
                    }
                } else {
                    if (item.Quantity < item.MaxQuantity) {
                        item.Quantity += 1;
                        this.customizedItem.AppliedModifires.push(item);
                    } else {
                        this.fireAlert(`You can select max ${item.MaxQuantity}`, ``,false);
                    }
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
