/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import './bootstrap';
import { createApp } from 'vue';
import App from './components/App.vue';
import router from './router';
import '@fortawesome/fontawesome-free/css/all.min.css';
import VueSelect from 'vue-select';

const app = createApp(App);

app.use(router);
app.component('v-select', VueSelect);

app.mount('#app');
