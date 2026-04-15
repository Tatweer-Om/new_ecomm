import {createApp} from 'vue'
import App from './App.vue'
import router from './router'
// ✅ Import Bootstrap CSS
import 'bootstrap/dist/css/bootstrap.min.css';

// ✅ Import Bootstrap JS
import 'bootstrap/dist/js/bootstrap.bundle.min.js';
import axios from 'axios';
axios.defaults.withCredentials = true; // ✅ send cookies automatically

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

const app = createApp(App)

// app.use(router)   
// app.mount('#app')