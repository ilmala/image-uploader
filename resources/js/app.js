import Vue from 'vue';

require('./bootstrap');

Vue.component('image-uploader', require('./components/ImageUploader.vue').default);

const app = new Vue({
    el: '#app',
})
