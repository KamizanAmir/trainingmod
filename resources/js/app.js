require('./bootstrap');

window.Vue = require('vue');

// Imported packages

import moment from 'moment';
import VueProgressBar from 'vue-progressbar'
import VueRouter from 'vue-router'
import Swal from 'sweetalert2'
import {
    Form,
    HasError,
    AlertError
} from 'vform';

// Global declaration for vue form
window.Form = Form;
Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)
Vue.filter('dFormat', function(value) {
    if (value) {
        return moment(String(value)).format('DD-MM-YYYY HH:mm:ss');
    }
});
// Initialize vue router and register
Vue.use(VueRouter)
let routes = [{
        path: '/category',
        component: require('./components/Category.vue').default
    },
    {
        path: '/items',
        component: require('./components/items/Index.vue').default
    },
    {
        path: '/search',
        component: require('./components/search.vue').default,
        name: 'search'
    },
    {
        path: '/items-create',
        component: require('./components/items/Create.vue').default
    },
    {
        path: '/items-edit/:id',
        name: 'edit',
        component: require('./components/items/Edit.vue').default,
        props: true
    },
    {
        path: '/items-show/:id',
        name: 'show',
        component: require('./components/items/Show.vue').default,
        props: true
    },
    {
        path: '/intro',
        component: require('./components/Intro.vue').default,

    },
    {
        path: '/related/:id',
        component: require('./components/Related.vue').default,
        props: true
    },
    {
        path: '*',
        component: require('./components/Error.vue').default
    },
]

const router = new VueRouter({
    mode: 'history', //HTML5 History mode
    routes // short for `routes: routes`
})

// Using filters and formating 
Vue.filter('ucFirst', function (text) {
    return text.charAt(0).toUpperCase() + text.slice(1)
})
Vue.filter('dFormat', function (date) {
    return moment().format("MMMM Do YYYY");
})

//Useing vue progress bar

Vue.use(VueProgressBar, {
    color: 'rgb(143, 255, 199)',
    failedColor: 'red',
    height: '2px'
})
// Using Sweet alert
window.Swal = Swal
//sweet alert toaster
const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000
});
window.Toast = Toast

const app = new Vue({
    el: '#app',
    router //using router
});