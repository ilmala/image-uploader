require('./bootstrap');
import Vue from 'vue';
import Clipboard from 'v-clipboard'

Vue.use(Clipboard)

Vue.component('image-uploader', require('./components/ImageUploader.vue').default);

const app = new Vue({
    el: '#app',
})
