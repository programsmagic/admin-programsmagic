/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import moment from "moment";

require('./bootstrap');

window.Vue = require('vue');
import {Form, HasError, AlertError} from 'vform';
import Gate from "./Gate";
import store from './store';


Vue.prototype.$gate = new Gate(window.user);
window.Form = Form;
Vue.component(HasError.name, HasError);
Vue.component(AlertError.name, AlertError);
Vue.component('pagination', require('laravel-vue-pagination'));

import VueRouter from 'vue-router';
import VueProgressBar from 'vue-progressbar';
import Swal from 'sweetalert2';
import Vuetify from "vuetify";


const vuetify = new Vuetify({
    lang: {
        // If you change the language here, then it changes in the editor itself
        current: "en" // en | es | fr | pl | ru
    }
});

// you don't need it. this is for example purposes
const iconsGroup = localStorage.getItem("current_icons_group") || "fa";

Vue.use(Vuetify);
Vue.config.productionTip = false;
Vue.use(VueRouter);
Vue.use(VueProgressBar, {
    color: 'rgb(143, 255, 199)',
    failedColor: 'red',
    height: '2px'
});
window.Vuetify = Vuetify;
window.Swal = Swal;
const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    onOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
});
window.toast = Toast;
let routes = [
    {path: '/', component: require('./components/AllPosts.vue').default},
    {path: '/dashboard', component: require('./components/AllPosts.vue').default},
    {path: '/users', component: require('./components/Users.vue').default},

    {path: '/category', component: require('./components/Category.vue').default},
    {path: '/posts', component: require('./components/Posts.vue').default},
    {path: '/post/form', component: require('./components/post/form.vue').default, name: 'create-post'},
    {path: '/editor', component: require('./components/post/Editor.vue').default, name: 'Editor'},
    {path: '/post/form/:postId', component: require('./components/post/form.vue').default, name: 'edit-post'},
    {path: '/post/view/:postId', component: require('./components/post/PostView.vue').default, name: 'view-post'},

    {path: '/ads', component: require('./components/Ads.vue').default},

    {path: '/profile', component: require('./components/Profile.vue').default},
    {path: '/developers', component: require('./components/Developers.vue').default},
    {path: '*', component: require('./components/NotFound.vue').default},

    // comments
    {path: '/comments', component: require('./components/comment/Comments.vue').default},
    {path: '/comments/:commentId', component: require('./components/comment/SingleComment').default, name: 'single-comment'},

];

const router = new VueRouter({
    mode: "history",
    routes
});

//custom event

window.Fire = new Vue();

Vue.filter('upText', function (text) {
    return text.charAt(0).toUpperCase() + text.slice(1)
});

Vue.filter('myDate', function (value) {
    return moment(value).format('MMMM Do YYYY');
});
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('dashboard', require('./components/Dashboard.vue').default);
Vue.component('profile', require('./components/Profile.vue').default);

//passport
Vue.component(
    'passport-clients',
    require('./components/passport/Clients.vue').default
);

Vue.component(
    'passport-authorized-clients',
    require('./components/passport/AuthorizedClients.vue').default
);

Vue.component(
    'passport-personal-access-tokens',
    require('./components/passport/PersonalAccessTokens.vue').default
);

Vue.component(
    'not-found',
    require('./components/NotFound.vue').default
);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    store,
    router,
    vuetify,
    data: {
        search: '',
    },
    methods: {
        searchit: _.debounce(() => {
            console.log('SEARCHING');
            Fire.$emit('Searching');
        }, 10),
    }

});
