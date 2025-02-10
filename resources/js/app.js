import './bootstrap'
import '../css/app.css'

import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/inertia-vue3'
import { InertiaProgress } from '@inertiajs/progress'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m'
import CKEditor from '@ckeditor/ckeditor5-vue';
import axios from 'axios'
import 'vue3-toastify/dist/index.css' 

import CategoryHelper from '@/CategoryHelper'


axios.defaults.baseURL = `${document.location.origin}/api/`;
const appName = window.document.getElementsByTagName('title')[0]?.innerText

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, app, props, plugin }) {
        return createApp({ render: () => h(app, props) })
            .use(plugin)
            .use(ZiggyVue, Ziggy)
            .use(CKEditor)
            .use(CategoryHelper, props.initialPage.props)
            .mount(el);
    },
})

InertiaProgress.init({ color: '#ef4444', delay: 100 })
