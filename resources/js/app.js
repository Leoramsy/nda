/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/* Register new component: */
Vue.component('contact-component', require('./components/Contact.vue'));

const app = new Vue({
    el: '#app'
});