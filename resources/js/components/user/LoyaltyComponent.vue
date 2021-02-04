<template>
    <div>
        <div class="row profile-btn">
            <div class="col-md-9">
                <button @click="openAddressModal()" type="button" id="edit-profile" class="btn btn-8DBF43 btn-1-anim btn-user header-btn">
                    {{trans('edit_profile')}}
                </button>&nbsp;&nbsp;
                <a href="/logout"
                   class="btn btn-808080 btn-1-anim btn-user header-btn">
                    {{trans('logout')}}
                </a>
            </div>
        </div>
        <profile-modal-component/>
    </div>
</template>

<script>
    import GlobalMixin from "../../mixins/GlobalMixin";

    export default {
        name: "LoyaltyComponent",
        mixins:[GlobalMixin],
        data(){
            return{
                addresses:[]
            }
        },
        methods:{
            openAddressModal() {
                Bus.$emit('open-profile-modal',this.defaultAddress);
            }
        }
    }
</script>

<style scoped>
    .header-btn {
        border-radius: 20px;
        padding: 4px 25px;
        font-size: 12px;
        font-family: 'Futura-Medium-BT';
        -webkit-box-shadow: 0px 0px 5px 0px rgba(0, 0, 0, 0.75);
        -moz-box-shadow: 0px 0px 5px 0px rgba(0, 0, 0, 0.75);
        box-shadow: 0px 0px 5px 0px rgba(0, 0, 0, 0.75)
    }
</style>
