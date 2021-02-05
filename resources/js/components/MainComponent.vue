<template>
    <div></div>
</template>

<script>
    export default {
        name: "MainComponent",
        props: {
            org: {
                type: Object,
                default: {}
            },
            user: {
                type: Object,
                default: {}
            },
            addresses: {
                type: Array,
                default: []
            }
        },
        created() {
            Vue.prototype.$org = this.org;
            Vue.prototype.$user = this.user;
            Vue.prototype.$addresses = this.addresses;
        }
    }
</script>

<style scoped>

</style>
