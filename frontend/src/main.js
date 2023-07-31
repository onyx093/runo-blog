import { createApp } from 'vue';
import { createPinia } from 'pinia';
import Vue3Toastify from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';

import App from './App.vue';
import router from './router';

const app = createApp(App);

app.use(createPinia());
app.use(router);
app.use(Vue3Toastify, {
  autoClose: 3000,
  position: 'bottom-right',
});

app.mount('#app');
