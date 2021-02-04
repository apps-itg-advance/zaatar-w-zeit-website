<template>
    <div class="cartbig-modal modal fade" id="meal-modal" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <div class="row">
                        <div class="col-lg-6 image-col">
                            <img :src="item.ThumbnailImg" class="img-fluid d-block mx-auto"/>
                        </div>
                        <div class="col-lg-6 text-col py-4">
                            <h5>
                                <span style="float: none !important;">{{item.ItemName}}</span>
                                <span>{{numberFormat(item.Price)}} {{org.currency}}</span>
                            </h5>
                            <div class="info">{{item.Details}}</div>
                        </div>
                    </div>
                </div>
                <div class="items-row row mt-4 pl-2"></div>
                <div class="modal-footer pt-0">
                    <span class="title d-inline-block">{{trans('total')}}</span>
                    <span class="amount d-inline-block mx-5" id="DisplayTotal">{{numberFormat(item.TotalPrice)}} {{org.currency}}</span>
                    <a class="btn btn-8DBF43 text-uppercase btn-a"
                       @click="confirmMeal()">{{trans('confirm')}}</a>
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
            }
        },
        mounted() {
            Bus.$on('open-meal-modal', (item) => {
                this.item = item;
                $('#meal-modal').modal('show');
            });
        },
        methods: {
            confirmMeal() {
                Bus.$emit('confirm-meal', this.item);
            }
        },
    }
</script>

<style scoped>

</style>
