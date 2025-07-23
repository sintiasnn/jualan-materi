import './bootstrap';
import { createApp } from 'vue';
import Editor from "../views/TextEditor.vue";
import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

const app = createApp({});
app.component('text-editor', Editor)
app.use(VueSweetalert2);
app.mount('#app');
