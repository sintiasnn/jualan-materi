import './bootstrap';
import { createApp } from 'vue';
import Editor from "../views/TextEditor.vue";

const app = createApp({});
app.component('text-editor', Editor)
app.mount('#app');
