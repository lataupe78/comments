require('./bootstrap');

import Vue from 'vue';
import Vuex from 'vuex';
import store from './store/store'

//window.Vue = require('vue');
Vue.use(Vuex);

import VueScrollTo from'vue-scrollto'
Vue.use(VueScrollTo)

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

//Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('comments', require('./components/CommentsComponent.vue').default);


const app = new Vue({
    el: '#app',
    store
});
