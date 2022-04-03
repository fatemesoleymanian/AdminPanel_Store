require('./bootstrap');
window.Vue = require('vue').default;

import ViewUI from 'view-design';
import 'view-design/dist/styles/iview.css';
import VueRouter from 'vue-router'
import Vuex from 'vuex'
import axios from 'axios'
import VueAxios from 'vue-axios'
import tinymce from 'vue-tinymce-editor'
import VuePersianDatetimePicker from 'vue-persian-datetime-picker';

//Routes
import { routes } from './routes';
import common from './common';
import store from "./store";
import VueSweetalert2 from 'vue-sweetalert2';

// styles
import 'sweetalert2/dist/sweetalert2.min.css';

Vue.use(VueSweetalert2);
Vue.component('tinymce',tinymce)
Vue.prototype.$store = store;
Vue.use(VueRouter)
Vue.use(Vuex)
Vue.use(VueAxios, axios)
Vue.use(ViewUI)
Vue.component('datetime-picker', VuePersianDatetimePicker);
Vue.mixin(common)
//Register Routes
export const router = new VueRouter({
    mode: 'history',
    routes,
    store

});


/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 */

Vue.component('sidebar', require('./components/sidebar').default);


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    router
}).$mount('#app')
