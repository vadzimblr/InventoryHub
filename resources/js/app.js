import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import axios from 'axios';
axios.defaults.withCredentials = true;
axios.defaults.withXSRFToken = true;
import Toast from 'vue-toastification';
import 'vue-toastification/dist/index.css';
const token = localStorage.getItem('authToken');
if (token) {
    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
}

export function setAuthorizationToken(token) {
    if (token) {
        localStorage.setItem('authToken', token);
        axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
    } else {
        localStorage.removeItem('authToken');
        delete axios.defaults.headers.common['Authorization'];
    }
}

createInertiaApp({
    resolve: name => {
        const pages = import.meta.glob('./Pages/**/*.vue', { eager: true });
        return pages[`./Pages/${name}.vue`];
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(Toast, {
                timeout: 3000,
                position: 'top-right',
                closeOnClick: true,
                hideProgressBar: true,
            })
            .mount(el);
    },
});
