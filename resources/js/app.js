import './bootstrap';

import {createApp} from 'vue';
import JsonExcel from "vue-json-excel3";
axios.defaults.baseURL = 'http://localhost:300/api/'
import router from "./routes/router";
import App    from "./App.vue";

const app = createApp(App);
app.component("downloadExcel", JsonExcel);
app.use(router);
app.mount('#app');
