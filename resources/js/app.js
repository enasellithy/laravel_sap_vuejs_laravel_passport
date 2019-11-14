require('./bootstrap');
import Vue from 'vue';
import App from './App.vue';
import VueRouter from 'vue-router';
import VueAxios from 'vue-axios';
import axios from 'axios';
import {routes} from './routes';
import auth from './auth.js';
window.Vue = Vue;


Vue.use(VueAxios, axios);
import swal from 'sweetalert2'
window.swal = swal;

const toast = swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000
})
window.toast = toast;

export default axios.create({
    baseURL: `http://127.0.0.1:8000/`,
    headers: {
        'Content-Type': 'application/json',
        'Authorization': "Bearer " + localStorage.getItem('token')
    },
    xsrfCookieName: 'csrftoken',
    xsrfHeaderName: 'X-CSRFToken',
    withCredentials: true
});

Vue.use(VueRouter);

window.auth = auth;
window.Event = new Vue;
import Api from './api.js';

window.api = new Api();



const router = new VueRouter({
    mode: 'history',
    routes: routes
});

router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.middlewareAuth)) {
        if (!auth.check()) {
            next({
                path: '/login',
                query: { redirect: to.fullPath }
            });

            return;
        }
    }

    next();
})

const app = new Vue({
    el: '#app',
    router: router,
    // i18n,
    render: h => h(App),
});
