require('./bootstrap');
window.Vue = require('vue');
import App from './App.vue'

import VueRouter from 'vue-router'
import {routes} from './routes.js'
Vue.use(VueRouter);
const router = new VueRouter({
    routes
});

import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.min.css';
import 'material-design-icons-iconfont/dist/material-design-icons.css';
Vue.use(Vuetify, {
    rtl: true
});
new Vue({
    router,
    render: h => h(App)
}).$mount('#app');