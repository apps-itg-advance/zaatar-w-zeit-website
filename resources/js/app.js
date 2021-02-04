/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
window.Bus = new Vue();
Vue.mixin(require('./mixins/Trans.js'))


import * as VueGoogleMaps from 'vue2-google-maps'

Vue.use(VueGoogleMaps, {
    load: {
        key: 'AIzaSyBr8W0tXG2qoFjFiyu4zbJMQVbYc2QQ40c',
        libraries: 'places', // This is required if you use the Autocomplete plugin
        // OR: libraries: 'places,drawing'
        // OR: libraries: 'places,drawing,visualization'
        // (as you require)
        //// If you want to set the version, you can do so:
        // v: '3.26',
    },
    //// If you intend to programmatically custom event listener code
    //// (e.g. `this.$refs.gmap.$on('zoom_changed', someFunc)`)
    //// instead of going through Vue templates (e.g. `<GmapMap @zoom_changed="someFunc">`)
    //// you might need to turn this on.
    // autobindAllEvents: false,
    //// If you want to manually install components, e.g.
    //// import {GmapMarker} from 'vue2-google-maps/src/components/marker'
    //// Vue.component('GmapMarker', GmapMarker)
    //// then set installComponents to 'false'.
    //// If you want to automatically install all the components this property must be set to 'true':
    installComponents: true
})

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))


Vue.component('main-component', require('./components/MainComponent.vue').default);

//Menu
Vue.component('items-list-component', require('./components/menu/ItemsListComponent.vue').default);
Vue.component('item-component', require('./components/menu/ItemComponent.vue').default);
Vue.component('combo-item-component', require('./components/menu/ComboItemComponent.vue').default);
Vue.component('customization-modal', require('./components/menu/CustomizationModal.vue').default);
Vue.component('combo-modal', require('./components/menu/ComboModal.vue').default);
Vue.component('make-meal-modal', require('./components/menu/MakeMealModal.vue').default);

//Cart
Vue.component('cart-component', require('./components/cart/CartComponent.vue').default);

//Checkout
Vue.component('checkout-wizard', require('./components/checkout/WizardComponent.vue').default);
Vue.component('address-step-component', require('./components/checkout/steps/AddressStepComponent.vue').default);
Vue.component('gift-step-component', require('./components/checkout/steps/GiftStepComponent.vue').default);
Vue.component('wallet-step-component', require('./components/checkout/steps/WalletStepComponent.vue').default);
Vue.component('real-green-step-component', require('./components/checkout/steps/RealGeenStepComponent.vue').default);
Vue.component('special-instructions-step-component', require('./components/checkout/steps/SpecialInstructionsStepComponent.vue').default);
Vue.component('payment-method-step-component', require('./components/checkout/steps/PaymentMethodStepComponent.vue').default);
Vue.component('payment-credit-cards-component', require('./components/checkout/steps/PaymentCardsComponent.vue').default);
Vue.component('invalid-step-component', require('./components/checkout/steps/InvalidStepComponent.vue').default);
Vue.component('order-summary-modal-component', require('./components/checkout/OrderSummaryModalComponent.vue').default);


//Orders
Vue.component('orders-list-component', require('./components/order/OrderListComponent.vue').default);
Vue.component('order-item-component', require('./components/order/OrderItemComponent.vue').default);

//Favorites
Vue.component('favorites-list-component', require('./components/favorite/FavoritesListComponent.vue').default);
Vue.component('favorite-order-list-component', require('./components/favorite/FavoriteOrdersListComponent.vue').default);

//User
Vue.component('address-modal-component', require('./components/user/AddressModalComponent.vue').default);
Vue.component('geo-tagging-component', require('./components/user/GeoTaggingComponent.vue').default);
Vue.component('loyalty-component', require('./components/user/LoyaltyComponent.vue').default);
Vue.component('profile-modal-component', require('./components/user/ProfileModalComponent.vue').default);

//widgets
Vue.component('map-component', require('./components/widgets/Map.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app'
});
