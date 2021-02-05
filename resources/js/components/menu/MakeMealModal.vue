<template>
    <div class="cartbig-modal modal fade" id="meal-modal" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content" v-if="item.hasOwnProperty('MakeMeal')">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <div class="row">
                        <div class="col-6 image-col">
                            <img :src="item.ThumbnailImg" class="img-fluid d-block mx-auto"/>
                        </div>
                        <div class="col-6">
                            <div class="row">
                                <div class="col-md-12 text-uppercase">
                                    <h1>{{item.MakeMeal.Title}}</h1>
                                </div>
                                <div class="col-md-12">
                                    <span>{{item.ItemName}}</span>
                                    <span>{{numberFormat(item.Price)}} {{org.currency}}</span>
                                </div>
                            </div>
                            <div class="row pt-3">
                                <div class="col-md-12">
                                    <h4>{{item.MakeMeal.Details}}</h4>
                                </div>
                                <div class="col-md-12">
                                    <div class="d-inline-block custom-control custom-radio"
                                         v-for="i in item.MakeMeal.Items" @click="checkItem(i)">
                                        <input
                                            :checked="appliedMeal.hasOwnProperty('AppliedItems') && exist(appliedMeal.AppliedItems, e => e.ID === i.ID)"
                                            type="checkbox"
                                            class="custom-control-input" :value="i">
                                        <label class="custom-control-label" style="vertical-align: bottom;">
                                            <div class="pr-2">
                                                {{i.Name}} <span
                                                v-if="i.Price > 0">{{numberFormat(i.Price)}} {{org.currency}}</span>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row pt-4">
                                <div class="col-md-12">
                                    <span class="title d-inline-block">{{trans('total')}}</span>
                                    <span class="amount d-inline-block pl-4">{{numberFormat(totalPrice)}} {{org.currency}}</span>
                                    <a class="btn btn-8DBF43 text-uppercase btn-a btn-confirm" @click="confirmMeal()">{{trans('confirm')}}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import GlobalMixin from "../../mixins/GlobalMixin";

    export default {
        name: "MakeMealModal",
        mixins: [GlobalMixin],
        data() {
            return {
                item: {},
                appliedMeal: {},
                totalPrice: 0
            }
        },
        mounted() {
            Bus.$on('open-meal-modal', (item) => {
                this.item = JSON.parse(JSON.stringify(item))
                this.appliedMeal = this.item.AppliedMeal
                this.totalPrice = this.item.TotalPrice
                $('#meal-modal').modal('show');
                console.log("open-meal-modal", this.item)
            });
        },
        methods: {
            checkItem(item) {
                if (Object.keys(this.appliedMeal).length === 0) {
                    this.totalPrice += parseInt(this.item.MakeMeal.Price);
                    this.appliedMeal = this.item.MakeMeal;
                    this.appliedMeal.AppliedItems = [];
                } else {
                    this.totalPrice -= parseInt(this.item.MakeMeal.Price);
                    this.totalPrice += parseInt(this.item.MakeMeal.Price);
                }
                this.appliedMeal.AppliedItems.push(item);
            },
            confirmMeal() {
                this.item.TotalPrice = parseInt(this.totalPrice);
                this.item.AppliedMeal = this.appliedMeal
                Bus.$emit('confirm-meal', this.item);
                this.item = {};
            }
        },
    }
</script>

<style scoped>
    .btn-confirm {
        border-radius: 20px;
    }

    .title {
        font-size: 22px;
        font-weight: bold;
    }

    .amount {
        font-size: 18px;
        font-weight: bold;
    }
</style>
