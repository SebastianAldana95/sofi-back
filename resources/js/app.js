require('./bootstrap');


// window.Vue = require('vue');

//Import View Router
import Vue from 'vue';
import VueRouter from "vue-router";

// Vue.use(VueRouter)

window.Vue = Vue;
Vue.use(VueRouter);


// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('user-component', require('./components/UserComponent.vue'));


const app = new Vue({
    el: '#app',
    router
});


