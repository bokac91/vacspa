require('./bootstrap');

window.Vue = require('vue');

import Vue from 'vue';

import router from './routes.js';
import store from './store.js';

import App from './components/App.vue';
import BaseCard from './components/ui/BaseCard.vue';
import BaseNotification from './components/ui/BaseNotification.vue';

Vue.component('base-card', BaseCard);
Vue.component('base-notification', BaseNotification);

const app = new Vue({
    render: h => h(App),
    router,
    store
});


app.$mount('#app');